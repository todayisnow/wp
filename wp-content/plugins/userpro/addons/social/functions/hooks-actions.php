<?php

	/* Enqueue Scripts */
	add_action('wp_enqueue_scripts', 'userpro_sc_enqueue_scripts', 99);
	function userpro_sc_enqueue_scripts(){
	
		if (!userpro_get_option('modstate_social') ) return false;
	
		wp_register_script('userpro_sc', userpro_sc_url . 'scripts/userpro-social.min.js');
		wp_enqueue_script('userpro_sc');
		
	}
	
	/* Hook after name in user list compact */
	add_action('userpro_after_name_user_list', 'userpro_sc_show_follow', 99);
	function userpro_sc_show_follow($user_id){
		global $userpro, $userpro_social;
		
		if (!userpro_get_option('modstate_social') ) return false;

		if ( userpro_is_logged_in() && !$userpro->is_user_logged_user($user_id) ) {
			echo '<div class="userpro-sc-flw">'.$userpro_social->follow_text($user_id, get_current_user_id()).'</div>';
		}
	
	}

		/*
		#Dev
		#Todayisnow
		#201704120150
		#profile answers and questions
		*/
		
	function count_answer( $user_id ) {
		global $wpdb;
		$query = "SELECT count(distinct post_parent) FROM {$wpdb->posts} WHERE post_author = {$user_id} AND post_type = 'dwqa-answer' AND post_status = 'publish'";
		$count = $wpdb->get_var( $query );		
		return $count;
	}
	 function count_question( $user_id ) {
		global $wpdb;
		$query = "SELECT count(*) FROM {$wpdb->posts} WHERE post_author = {$user_id} AND post_type = 'dwqa-question' AND post_status = 'publish'";
		$count = $wpdb->get_var( $query );
		return $count;
	}
	/* Hook after profile head */
	add_action('userpro_after_profile_head','userpro_sc_bar', 99);
	function userpro_sc_bar( $args ){
		global $userpro, $userpro_social;
		
		$user_id = $args['user_id'];
		
		if (!userpro_get_option('modstate_social') ) return false;
		
		// where to add the hook
		if ( in_array($args['template'], array('view','following','followers') )  && !isset($args['no_style']) ){
		global $dwqa_general_settings;
		$user = get_user_by( 'id', $user_id );
		$question_link = isset( $dwqa_general_settings['pages']['archive-question'] ) ? get_permalink( $dwqa_general_settings['pages']['archive-question'] ) : false;
		$url = get_the_author_link( $user_id );
		if ( $question_link ) {
			$questionUrl = add_query_arg( array( 'user' => urlencode( $user->user_login ),'filter'=> urlencode('questions') ), $question_link );
			$answerUrl = add_query_arg( array( 'user' => urlencode( $user->user_login ),'filter'=> urlencode('answers') ), $question_link );
		}
		?>
		
		<div class="userpro-sc-bar">
		<?php
		/*
		#Dev
		#Todayisnow
		#201704120150
		#profile answers and questions
		*/
		?>
			<div class="userpro-sc-left">
				<a href="<?php echo $userpro->permalink($user_id, 'following', 'userpro_sc_pages'); ?>" class="userpro-count-link"><?php echo $userpro_social->following_count( $user_id ); ?></a>
				<a href="<?php echo $userpro->permalink($user_id, 'followers', 'userpro_sc_pages'); ?>" class="userpro-count-link"><?php echo $userpro_social->followers_count( $user_id ); ?></a>
				<a href="<?php echo ($questionUrl) ;?>" class="userpro-count-link"><span><?php echo(count_question( $user_id)); ?></span> Questions</a>
				<a href="<?php echo ($answerUrl); ?>" class="userpro-count-link"><span><?php echo(count_answer( $user_id)); ?></span> Answers</a>
			</div>
			
			<div class="userpro-sc-right">
				<?php echo $userpro_social->follow_text($user_id, get_current_user_id()); ?>
				<?php do_action('userpro_social_buttons', $user_id); ?>
			</div>
			
			<div class="userpro-clear"></div>
		
		</div>
		
		<?php
		}
		
	}
