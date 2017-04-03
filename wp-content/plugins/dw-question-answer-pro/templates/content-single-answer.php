<?php
/**
 * The template for displaying single answers
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.0.1
 */
?>
<div class="<?php echo dwqa_post_class() ?>" id="answer-<?php the_ID() ?>">
	<div class="dwqa-answer-vote" data-nonce="<?php echo wp_create_nonce( '_dwqa_answer_vote_nonce' ) ?>" data-post="<?php the_ID(); ?>">
		<span class="dwqa-vote-count"><?php echo dwqa_vote_count() ?></span>
		<a class="dwqa-vote dwqa-vote-up" href="#"><?php _e( 'Vote Up', 'dwqa' ); ?></a>
		<a class="dwqa-vote dwqa-vote-down" href="#"><?php _e( 'Vote Down', 'dwqa' ); ?></a>
	</div>
	<?php if ( dwqa_current_user_can( 'edit_question', dwqa_get_question_from_answer_id() ) || dwqa_current_user_can( 'manage_question' ) ) : ?>
		<?php $action = dwqa_is_the_best_answer() ? 'dwqa-unvote-best-answer' : 'dwqa-vote-best-answer' ; ?>
		<a class="dwqa-pick-best-answer" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'answer' => get_the_ID(), 'action' => $action ), admin_url( 'admin-ajax.php' ) ), '_dwqa_vote_best_answer' ) ) ?>"><?php _e( 'Best Answer', 'dwqa' ) ?></a>
	<?php elseif ( dwqa_is_the_best_answer() ) : ?>
		<span class="dwqa-pick-best-answer"><?php _e( 'Best Answer', 'dwqa' ) ?></span>
	<?php endif; ?>
	<div class="dwqa-answer-meta">
		<?php $user_id = get_post_field( 'post_author', get_the_ID() ) ? get_post_field( 'post_author', get_the_ID() ) : 0 ?>
		<?
				/*
#Dev
#Todayisnow
#2017030090038
#Get title with user name in answer
*/
				?>
				<?php 
		$title = get_the_author_meta( 'title', $user_id );
		if($title!="")
			$title = ", ".$title;
		?>
		<?php
			$avatar_url = get_avatar( $user_id, 48 );
			$matches = array();
			$regex = preg_match_all('@<img[^>]+src="([^">]+)"@i', $avatar_url, $matches, PREG_SET_ORDER);
			if(!empty($matches)){
				$avatar_url = $matches[0][1];
			}
			else{
				$avatar_url = get_avatar_url( $user_id, 48 );
			}
			$image_html = '<span style="position: absolute; left: 0; top: 20px; border-radius: 48px; display: block; width: 48px; height: 48px; background-size: cover; background-image: url(\''.$avatar_url.'\'); background-repeat: no-repeat; background-position: center; "></span>';
		?>
		<?php printf( __( '<span><a href="%1$s">%2$s%3$s</a>%4$s %5$s answered %6$s ago</span>', 'dwqa' ), dwqa_get_author_link( $user_id ), $image_html, get_the_author(),$title, dwqa_print_user_badge( $user_id ), human_time_diff( get_post_time( 'U', true ) ) ) ?>
		<?php if ( 'private' == get_post_status() ) : ?>
			<span><?php _e( '&nbsp;&bull;&nbsp;', 'dwqa' ); ?></span>
			<span><?php _e( 'Private', 'dwqa' ) ?></span>
		<?php endif; ?>
		<span class="dwqa-answer-actions"><?php dwqa_answer_button_action(); ?></span>
	</div>
	<div class="dwqa-answer-content"><?php the_content(); ?></div>
	<?php comments_template(); ?>
</div>
