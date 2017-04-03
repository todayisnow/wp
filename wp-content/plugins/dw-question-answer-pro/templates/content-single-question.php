<?php
/**
 * The template for displaying single questions
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.0.1
 */
?>

<?php
function add_meta_tags() {
	$title = get_the_title();
	$link = get_permalink();
    echo "<meta property='og:title' content='{$title} - Comparlo' />
	<meta property='og:image' content='https://www.comparlo.com/wp/wp-content/uploads/2016/12/favicon.png' />
	<meta property='og:description' content='Enriching knowledge through comparisons.' />
	<meta property='og:url' content='{$link}' />
	";
}
add_action( 'wp_head', 'add_meta_tags' , 2 ); 
?>
<?php do_action( 'dwqa_before_single_question_content' ); ?>
<div class="dwqa-question-item">
	<div class="dwqa-question-vote" data-nonce="<?php echo wp_create_nonce( '_dwqa_question_vote_nonce' ) ?>" data-post="<?php the_ID(); ?>">
		<span class="dwqa-vote-count"><?php echo dwqa_vote_count() ?></span>
		<a class="dwqa-vote dwqa-vote-up" href="#"><?php _e( 'Vote Up', 'dwqa' ); ?></a>
		<a class="dwqa-vote dwqa-vote-down" href="#"><?php _e( 'Vote Down', 'dwqa' ); ?></a>
	</div>
	<div class="dwqa-question-meta">
		<?php $user_id = get_post_field( 'post_author', get_the_ID() ) ? get_post_field( 'post_author', get_the_ID() ) : false ?>
						<?
				/*
#Dev
#Todayisnow
#2017030090038
#Get title with user name in question
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
			$image_html = '<span style="position: absolute; left: 0; top: 20px; margin-top: -20px; border-radius: 48px; display: block; width: 48px; height: 48px; background-size: cover; background-image: url(\''.$avatar_url.'\'); background-repeat: no-repeat; background-position: center; "></span>';
		?>
		<?php printf( __( '<span><a href="%1$s">%2$s%3$s</a>%4$s %5$s asked %6$s ago</span>', 'dwqa' ), dwqa_get_author_link( $user_id ), $image_html, get_the_author(),$title,  dwqa_print_user_badge( $user_id ), human_time_diff( get_post_time( 'U', true ) ) ) ?>
		<span class="dwqa-question-actions"><?php dwqa_question_button_action() ?></span>
	</div>
	<div class="dwqa-question-content"><?php the_content(); ?></div>
	<footer class="dwqa-question-footer">
		<div class="dwqa-question-meta">
			<?php echo get_the_term_list( get_the_ID(), 'dwqa-question_tag', '<span class="dwqa-question-tag">' . __( 'Question Tags: ', 'dwqa' ), ', ', '</span>' ); ?>
			<?php if ( dwqa_current_user_can( 'edit_question', get_the_ID() ) || dwqa_current_user_can( 'manage_question' ) ) : ?>
				<?php if ( dwqa_is_enable_status() ) : ?>
				<span class="dwqa-question-status">
					<?php _e( 'This question is:', 'dwqa' ) ?>
					<select id="dwqa-question-status" data-nonce="<?php echo wp_create_nonce( '_dwqa_update_privacy_nonce' ) ?>" data-post="<?php the_ID(); ?>">
						<optgroup label="<?php _e( 'Status', 'dwqa' ); ?>">
							<option <?php selected( dwqa_question_status(), 'open' ) ?> value="open"><?php _e( 'Open', 'dwqa' ) ?></option>
							<option <?php selected( dwqa_question_status(), 'closed' ) ?> value="closed"><?php _e( 'Closed', 'dwqa' ) ?></option>
							<option <?php selected( dwqa_question_status(), 'resolved' ) ?> value="resolved"><?php _e( 'Resolved', 'dwqa' ) ?></option>
						</optgroup>
					</select>
					</span>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</footer>
	<?php comments_template(); ?>
</div>
<?php do_action( 'dwqa_after_single_question_content' ); ?>
