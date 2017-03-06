<?php

if ( !defined( 'ABSPATH' ) ) exit;

class DWQA_Akismet {
	public function __construct() {
		add_filter( 'dwqa_insert_question_args', array( $this, 'check_post' ) );
		add_filter( 'dwqa_insert_answer_args', array( $this, 'check_post' ) );
		add_filter( 'dwqa_insert_comment_args', array( $this, 'check_post' ) );
	}

	public function check_post( $post_data ) {
		$current_filter = current_filter();

		switch ($current_filter) {
			// prepare when submit question and answer
			case 'dwqa_insert_question_args':
			case 'dwqa_insert_answer_args':
				if ( 'private' !== $post_data['post_status'] && 'publish' !== $post_data['post_status'] ) {
					return $post_data;
				}

				$user_data = array();

				if ( empty( $post_data['post_author'] ) ) {
					$post_data['post_author'] = 0;
				}

				$userdata = get_userdata( $post_data['post_author'] );

				if ( !empty( $userdata ) ) {
					$user_data['name'] = $userdata->display_name;
					$user_data['email'] = $userdata->user_email;
					$user_data['website'] = $userdata->$user_url;
				} else if ( isset( $post_data['is_anonymous'] ) ) {
					$user_data['name'] = isset( $post_data['dwqa_anonymous_name'] ) ? $post_data['dwqa_anonymous_name'] : __( 'Anonymous', 'dwqa' );
					$user_data['email'] = isset( $post_data['dwqa_anonymous_email'] ) ? $post_data['dwqa_anonymous_email'] : '';
					$user_data['website'] = '';
				} else {
					$user_data['name'] = '';
					$user_data['email'] = '';
					$user_data['website'] = '';
				}

				$post_permalink = '';
				if ( !empty( $post_data['post_parent'] ) ) {
					$post_permalink = get_permalink( $post_data['post_parent'] );
				}

				$_post = array(
					'comment_author' => $user_data['name'],
					'comment_author_email' => $user_data['email'],
					'comment_author_url' => $user_data['website'],
					'comment_content' => $post_data['post_content'],
					'comment_post_ID' => $post_data['post_parent'],
					'comment_type'	=> $post_data['post_type'],
					'permalink'	=> $post_permalink,
					'referrer' => $_SERVER['HTTP_REFERER'],
					'user_agent' => $_SERVER['HTTP_USER_AGENT'],
					'user_id' => $post_data['post_author'],
					'user_ip' => $_SERVER['REMOTE_ADDR'],
					'user_role' => $this->get_user_roles( $post_data['post_author'] )
				);
				break;
			
			// prepare when submit comment
			case 'dwqa_insert_comment_args':
				$permalink = '';
				if ( isset( $post_data['comment_post_ID'] ) ) {
					if ( 'dwqa-question' === get_post_type( $post_data['comment_post_ID'] ) ) {
						$permalink = get_permalink( $post_data['comment_post_ID'] );
					} else {
						$question_id = get_post_meta( $post_data['comment_post_ID'], '_question', true );
						if ( $question_id ) {
							$permalink = get_permalink( $question_id );
						}
					}
				}

				$_post = $post_data;
				$_post['permalink'] = $permalink;
				$_post['referrer'] = $_SERVER['HTTP_REFERER'];
				$_post['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
				$_post['user_ip'] = $_SERVER['REMOTE_ADDR'];
				$_post['user_role'] = $this->get_user_roles( $_post['user_id'] );
				break;
		}

		$_post = $this->check_spam( $_post );

		$post_data['dwqa_akismet_results'] = $_post['dwqa_akismet_results'];
		unset( $_post['dwqa_akismet_results'] );

		$post_data['dwqa_post_as_submitted'] = $_post;

		do_action_ref_array( 'dwqa_akismet_check_post', $post_data );

		$this->last_post = $post_data;

		return $post_data;
	}

	public function check_spam( $post_data, $check = 'check', $spam = 'spam' ) {
		global $akismet_api_host, $akismet_api_port;

		$query_string = $path = $response = '';
		$post_data['blog'] = get_option( 'home' );
		$post_data['blog_charset'] = get_option( 'blog_charset' );
		$post_data['blog_lang'] = get_locale();
		$post_data['referrer'] = $_SERVER['HTTP_REFERER'];
		$post_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

		if ( akismet_test_mode() ) {
			$post_data['is_test'] = 'true';
		}

		foreach( $_POST as $key => $value ) {
			if ( is_string( $value ) ) {
				$post_data['POST_'. $key ] = $value;
			}
		}

		$ignore = array( 'HTTP_COOKIE', 'HTTP_COOKIE2', 'PHP_AUTH_PW' );

		foreach( $_SERVER as $key => $value ) {
			if ( !in_array( $key, $ignore ) && is_string( $value ) ) {
				$post_data[$key] = $value;
			} else {
				$post_data[$key] = '';
			}
		}

		foreach ( $post_data as $key => $data ) {
			$query_string .= $key . '=' . urlencode( stripcslashes( $data ) ) . '&';
		}

		if ( 'check' == $check ) {
			$path = '/1.1/comment-check';
		} else {
			$path = '/1.1/submit-' . $spam;
		}

		$response = $this->http_post( $query_string, $akismet_api_host, $path, $akismet_api_port );

		if ( isset( $response[1] ) && 'true' == $response[1] ) {
			$post_data['dwqa_akismet_results'] = true;
		} else {
			$post_data['dwqa_akismet_results'] = false;
		}

		return $post_data;
	}

	private function http_post( $request, $host, $path, $port = 80, $ip = '' ) {
		$dwqa_version = dwqa()->version;
		$content_length = strlen( $request );
		$blog_charset = get_option( 'blog_charset' );
		$response = '';
		if ( function_exists( 'wp_remote_post' ) ) {
			$http_args = array(
				'body' => $request,
				'headers' => array(
					'Content-Type' => 'application/x-www-form-urlencoded; charset=' . $blog_charset,
					'Host' => $host,
					'User-Agent' => "DWQA/{$dwqa_version} | Akismet/" . constant( 'AKISMET_VERSION' ),
				),
				'httpversion' => '1.0',
				'timeout' => 15
			);

			$url = 'http://' . $host . $path;

			$response = wp_remote_post( $url, $http_args );

			if ( is_wp_error( $response ) ) {
				return '';
			}

			return array( $response['headers'], $response['body'] );
		} else {
			$http_request  = "POST {$path} HTTP/1.0\r\n";
			$http_request .= "Host: {$host}\r\n";
			$http_request .= "Content-Type: application/x-www-form-urlencoded; charset={$blog_charset}\r\n";
			$http_request .= "Content-Length: {$content_length}\r\n";
			$http_request .= "User-Agent: DWQA/{$dwqa_version} | Akismet/" . constant( 'AKISMET_VERSION' ) . "\r\n";
			$http_request .= "\r\n";
			$http_request .= $request;

			$errno = null;
			$errstr = null;
			$fs = @fsockopen( $host, $port, $errno, $errstr, 10 );
			if ( false !== $fs ) {

				fwrite( $fs, $http_request );

				while ( !feof( $fs ) ) {
					$response .= fgets( $fs, 1160 );
				}

				fclose( $fs );
				$response = explode( "\r\n\r\n", $response, 2 );
			}

			return $response;
		}
	}

	public function get_user_roles( $user_id = 0 ) {
		$roles = array();

		if ( !class_exists( 'WP_User' ) || !$user_id ) {
			return false;
		}

		$user = new WP_User( $user_id );
		if ( isset( $user->ID ) ) {
			$roles = (array) $user->roles;
		}

		if ( is_multisite() && is_super_admin( $user_id ) ) {
			$rolse[] = 'super_admin';
		}

		return implode( ',', $roles );
	}
}

new DWQA_Akismet();