<?php
/**
 *  Plugin Name: Interdesigns Question Answer Enhancer
 *  Description: A WordPress plugin developed by Interdesigns.com to build a complete Question & Answer system for your WordPress site like Quora, Stackoverflow, etc. with custom answers and question builder
 *  Author: Interdesigns
 *  Author URI: http://www.Interdesigns.com
 *  Version: 1.1.8
 *  Text Domain: iqa
 */

add_filter( 'dynamic_sidebar_params', 'my_filter_dynamic_sidebar_params' );
add_filter( 'widget_output', 'my_widget_output_filter', 10, 3 );
add_action('wp_enqueue_scripts', 'add_scripts');
add_action('wp_enqueue_scripts', 'iqa_init');
add_action('admin_footer-users.php', 'add_verify_users');
add_action('admin_action_verify_users', 'handel_action_verify_users');
add_action('admin_action_un_verify_users', 'handel_action_un_verify_users');
add_action('admin_notices', 'my_actions_admin_notice');

function add_scripts(){
	wp_register_style('hide', plugins_url('/css/hide.css', __FILE__));
	wp_enqueue_style('hide');
	
	wp_enqueue_script( 'dwqa-questions-list', plugins_url('/dw-question-answer-pro/templates/assets/js/dwqa-questions-list.js') , array( 'jquery', 'jquery-ui-autocomplete' ), '', true );
	wp_register_script('all-pages-search-js', plugins_url( '/js/all_pages_search.js', __FILE__ ), array('jquery', 'jquery-ui-autocomplete'));
	$the_query = new WP_Query(array('s' => "dwqa-submit-question-form"));
	if(isset($the_query) && $the_query->post_count != 0){
		$q_link = get_permalink($the_query->posts[0]->ID);
	}
	wp_localize_script( 'all-pages-search-js', 'qa', array("q_link" => $q_link));
	wp_enqueue_script('all-pages-search-js');
}

function iqa_init() {
	global $wp_filter;
	$post_id = get_the_ID();
	$post = get_post($post_id);
	if(has_shortcode($post->post_content, "dwqa-list-questions")){
		$content = do_shortcode("[dwqa-list-questions]");
		$matches = array();
		$usersList = array();
		preg_match_all('#\/\?user=([A-Za-z\d\.\-\_\%\+]+)[\'"]#i', $content, $matches, PREG_SET_ORDER);
		foreach($matches as $user){
			$userInfo = get_user_by('login', urldecode($user[1]));
			if(!isset($usersList[$user[1]])){
				$usersList[$user[1]] = (get_user_meta($userInfo->ID, "userpro_verified", true) == "1") ? true : false;
			}
		}
		$usersList = json_encode($usersList);
		$the_query = new WP_Query(array('s' => "dwqa-submit-question-form"));
		if(isset($the_query) && $the_query->post_count != 0){
			$q_link = get_permalink($the_query->posts[0]->ID);
		}
		wp_register_script('search-js', plugins_url( '/js/search.js', __FILE__ ), array('jquery', 'jquery-ui-autocomplete'));
		wp_localize_script( 'search-js', 'users', array("list" => $usersList));
		wp_enqueue_script('search-js');
	}
	
	else if($post->post_type === "dwqa-question"){
		if(isset($_GET['edit'])){
			$post_info = get_post($_GET['edit']);
			if($post_info->post_type === "dwqa-question"){
				if ( ! current_user_can('administrator') ) {
					wp_register_style('question-css', plugins_url('/css/question.css', __FILE__));
					wp_dequeue_style('hide');
					wp_enqueue_style('question-css');
				}
				wp_register_script('question-edit-js', plugins_url( '/js/question_edit.js', __FILE__ ), array('jquery', 'jquery-ui-autocomplete'));
				wp_enqueue_script('question-edit-js');
			}
			
			else if($post_info->post_type === "dwqa-answer"){
				wp_register_script('answer-edit-js', plugins_url( '/js/answer_edit.js', __FILE__ ), array('jquery', 'jquery-ui-autocomplete'));    
				wp_localize_script( 'answer-edit-js', 'upload', array("url" => plugins_url( '/upload.php', __FILE__ ), "css" => plugins_url( '/css/editor.css', __FILE__ ), "content" => strip_tags($post->post_content)));
				wp_enqueue_script('answer-edit-js');
			}
		}
		
		else if(isset($_GET['comment_edit'])){
			wp_register_style('comment', plugins_url('/css/comment.css', __FILE__));
			wp_dequeue_style('hide');
			wp_enqueue_style('comment');
			wp_register_script('comment-js', plugins_url( '/js/comment.js', __FILE__ ), array('jquery', 'jquery-ui-autocomplete'));
			wp_enqueue_script('comment-js');
		}
		
		else{
			global $wpdb;
			
			$verified_list = array();
			$options = get_option('dwqa_options');
			$page_number = isset($_REQUEST['ans-page']) ? $_REQUEST['ans-page'] : 1;
			$per_page = isset($options['answer-per-page']) ? $options['answer-per-page'] : 0;
			$best_answer_id = get_post_meta($post_id, "_dwqa_best_answer", true);
			
			$sql = "SELECT `post_author` FROM `{$wpdb->prefix}posts` WHERE `post_type`='dwqa-question' AND `post_status`='publish' AND `ID`='{$post_id}'";
			$posts = $wpdb->get_results($sql);
			
			$tmp_list = array();
			foreach($posts as $single_post){
				$user_state = false;
				if(get_user_meta($single_post->post_author, 'userpro_verified', true) == '1'){
					$user_state = true;
				}
				$comments_list = array();
				$sql = "SELECT `user_id` FROM `{$wpdb->prefix}comments` WHERE `comment_approved`='1' AND `comment_type`='dwqa-comment' AND `comment_post_id`='{$post_id}' ORDER BY `comment_ID` ASC";
				$comments = $wpdb->get_results($sql);
				foreach($comments as $single_comment){
					$comment_state = false;
					if(get_user_meta($single_comment->user_id, 'userpro_verified', true) == '1'){
						$comment_state = true;
					}
					$comments_list[] = $comment_state;
				}
				$tmp_list = array('user_state' => $user_state, 'comments_state'=> $comments_list);	
			}
			
			$verified_list["question_info"] = $tmp_list;
			
			if($best_answer_id !== ''){
				
				$sql = "SELECT `ID`,`post_author` FROM `{$wpdb->prefix}posts` WHERE `post_type`='dwqa-answer' AND `post_status`='publish' AND `ID`='{$best_answer_id}'";
				$posts = $wpdb->get_results($sql);
				$tmp_list = array();
				foreach($posts as $single_post){
					$user_state = false;
					if(get_user_meta($single_post->post_author, 'userpro_verified', true) == '1'){
						$user_state = true;
					}
					$comments_list = array();
					$sql = "SELECT `user_id` FROM `{$wpdb->prefix}comments` WHERE `comment_approved`='1' AND `comment_type`='dwqa-comment' AND `comment_post_id`='{$single_post->ID}' ORDER BY `comment_ID` ASC";
					$comments = $wpdb->get_results($sql);
					foreach($comments as $single_comment){
						$comment_state = false;
						if(get_user_meta($single_comment->user_id, 'userpro_verified', true) == '1'){
							$comment_state = true;
						}
						$comments_list[] = $comment_state;
					}
					$tmp_list = array('user_state' => $user_state, 'comments_state'=> $comments_list);	
				}
				
				$verified_list["best_answer_info"] = $tmp_list;
			}
			
			
			$sql = "SELECT `ID`,`post_author` FROM `{$wpdb->prefix}posts` WHERE `post_type`='dwqa-answer' AND `post_status`='publish' AND `post_parent`='{$post_id}'";
			if($best_answer_id != ''){
				$sql .= " AND `ID` != '{$best_answer_id}'";
			}
			
			$sql .= " ORDER BY `ID` ASC";
			
			if($per_page != 0){
				$upper_limit = $per_page * $page_number;
				$lower_limit = $upper_limit - $per_page;
				$sql .= " LIMIT {$lower_limit}, {$per_page}";
			}
			
			$posts = $wpdb->get_results($sql);
			$tmp_list = array();
			foreach($posts as $single_post){
				$user_state = false;
				if(get_user_meta($single_post->post_author, 'userpro_verified', true) == '1'){
					$user_state = true;
				}
				$comments_list = array();
				$sql = "SELECT `user_id` FROM `{$wpdb->prefix}comments` WHERE `comment_approved`='1' AND `comment_type`='dwqa-comment' AND `comment_post_id`='{$single_post->ID}' ORDER BY `comment_ID` ASC";
				$comments = $wpdb->get_results($sql);
				foreach($comments as $single_comment){
					$comment_state = false;
					if(get_user_meta($single_comment->user_id, 'userpro_verified', true) == '1'){
						$comment_state = true;
					}
					$comments_list[] = $comment_state;
				}
				$tmp_list[] = array('user_state' => $user_state, 'comments_state'=> $comments_list);	
			}
			
			$verified_list["answers_info"] = $tmp_list;
			
			
			wp_register_script('answer-js', plugins_url( '/js/answer.js', __FILE__ ), array('jquery', 'jquery-ui-autocomplete'));    
			wp_localize_script( 'answer-js', 'upload', array("url" => plugins_url( '/upload.php', __FILE__ ), "css" => plugins_url( '/css/editor.css', __FILE__ )));
			wp_localize_script( 'answer-js', 'list', array("verified" => json_encode($verified_list)));
			wp_localize_script( 'answer-js', 'links', array("login" => wp_login_url(), "reg" => wp_registration_url()));
			wp_enqueue_script('answer-js');
		}
	}
	
	else if(has_shortcode($post->post_content, "dwqa-submit-question-form")){
		wp_register_script('question-js', plugins_url( '/js/question.js', __FILE__ ), array('jquery', 'jquery-ui-autocomplete'));
		wp_localize_script( 'question-js', 'links', array("login" => wp_login_url(), "reg" => wp_registration_url()));
		wp_enqueue_script('question-js');
	}
}

function my_actions_admin_notice() {
	if ( ! empty( $_REQUEST['action'] && $_REQUEST['action'] == "verify_users") ) {
		echo '<div id="message" class="updated notice"><p>Successfully make users virefied</p></div>';
	}
	if ( ! empty( $_REQUEST['action'] && $_REQUEST['action'] == "un_verify_users") ) {
		echo '<div id="message" class="updated notice"><p>Successfully make users Unvirefied</p></div>';
	}

}

function add_verify_users() { ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('<option>').val('verify_users').text('Verify users')
                .appendTo("select[name='action'], select[name='action2']");
        });
		jQuery(document).ready(function($) {
            $('<option>').val('un_verify_users').text('Unverify users')
                .appendTo("select[name='action'], select[name='action2']");
        });
    </script>
    <?php
}

function handel_action_verify_users() {
	$users_Ids = $_REQUEST['users'];
	foreach($users_Ids as $user_id){
		add_user_meta( $user_id, "userpro_verified", "1", true);
	}
}

function handel_action_un_verify_users() {
	$users_Ids = $_REQUEST['users'];
	foreach($users_Ids as $user_id){
		delete_user_meta( $user_id, "userpro_verified", "1" );
	}
}




function my_custom_widget_callback_function() {
 
    global $wp_registered_widgets;
    $original_callback_params = func_get_args();
    $widget_id = $original_callback_params[0]['widget_id'];
 
    $original_callback = $wp_registered_widgets[ $widget_id ]['original_callback'];
    $wp_registered_widgets[ $widget_id ]['callback'] = $original_callback;
 
    $widget_id_base = $wp_registered_widgets[ $widget_id ]['callback'][0]->id_base;
 
    if ( is_callable( $original_callback ) ) {
 
        ob_start();
        call_user_func_array( $original_callback, $original_callback_params );
        $widget_output = ob_get_clean();
 
        echo apply_filters( 'widget_output', $widget_output, $widget_id_base, $widget_id );
 
    }
 
}

function my_filter_dynamic_sidebar_params( $sidebar_params ) {
 
    if ( is_admin() ) {
        return $sidebar_params;
    }
 
    global $wp_registered_widgets;
    $widget_id = $sidebar_params[0]['widget_id'];
 
    $wp_registered_widgets[ $widget_id ]['original_callback'] = $wp_registered_widgets[ $widget_id ]['callback'];
    $wp_registered_widgets[ $widget_id ]['callback'] = 'my_custom_widget_callback_function';
 
    return $sidebar_params;
 
}

function my_widget_output_filter( $widget_output, $widget_id_base, $widget_id ) {
	if(strpos($widget_id, 'leaderboard') !== false){
		$matches = array();
		$usersList = array();
		preg_match_all('#\/\?user=([A-Za-z\d\.\-\_\%\+]+)[\'"]#i', $widget_output, $matches, PREG_SET_ORDER);
		foreach($matches as $user){
			$userInfo = get_user_by('login', urldecode($user[1]));
			$usersList[] = (get_user_meta($userInfo->ID, "userpro_verified", true) == "1") ? true : false;
		}
		$usersList = json_encode($usersList);
		$append = '<i class="fa fa-check-circle verified" title="Verified User" aria-hidden="true"></i>';
		$script = "<script>
			var listUsers = {$usersList};
			jQuery('div#{$widget_id} ul.dwqa-leaderboard li').each(function(index){
				if(listUsers[index]){
					jQuery(this).find('span.dwqa-user-header').append('{$append}');
				}
			});
		</script>";
		$widget_output = $widget_output.$script;
	}

    return $widget_output;
}