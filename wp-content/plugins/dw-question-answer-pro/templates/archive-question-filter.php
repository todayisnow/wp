<?php
/**
 * The template for displaying answers
 *
 * @package DW Question & Answer
 * @since DW Question & Answer 1.0.1
 */

global $dwqa_general_settings;
$sort = isset( $_GET['sort'] ) ? $_GET['sort'] : '';
$filter = isset( $_GET['filter'] ) ? $_GET['filter'] : 'all';
?>
<div class="dwqa-question-filter">
	<span><?php _e( 'Filter:', 'dwqa' ); ?></span>
	<?php if ( !isset( $_GET['user'] ) ) : ?>
		<a  href="<?php echo esc_url( add_query_arg( array( 'filter' => 'all' ) ,home_url( '/' ) ,home_url( '/' ) ) ) ?>" class="<?php echo 'all' == $filter ? 'active' : '' ?>"><?php _e( 'All', 'dwqa' ); ?></a>
		<?php if ( dwqa_is_enable_status() ) : ?>
			<a style="display:none" href="<?php echo esc_url( add_query_arg( array( 'filter' => 'open' ) ,home_url( '/' ) ) ) ?>" class="<?php echo 'open' == $filter ? 'active' : '' ?>"><?php _e( 'Open', 'dwqa' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'resolved' ) ,home_url( '/' ) ) ) ?>" class="<?php echo 'resolved' == $filter ? 'active' : '' ?>"><?php _e( 'Resolved', 'dwqa' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'closed' ) ,home_url( '/' ) ) ) ?>" class="<?php echo 'closed' == $filter ? 'active' : '' ?>"><?php _e( 'Closed', 'dwqa' ); ?></a>
		<?php endif; ?>
		
		<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'unanswered' ) ,home_url( '/' ) ) ) ?>" class="<?php echo 'unanswered' == $filter ? 'active' : '' ?>"><?php _e( 'Unanswered', 'dwqa' ); ?></a>
		<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'my-questions' ) ,home_url( '/' ) ) ) ?>" class="<?php echo 'my-questions' == $filter ? 'active' : '' ?>"><?php _e( 'My questions', 'dwqa' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'my-answers' ) ,home_url( '/' ) ) ) ?>" class="<?php echo 'my-answers' == $filter ? 'active' : '' ?>"><?php _e( 'My answers', 'dwqa' ); ?></a>
			<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'my-subscribes' ) ,home_url( '/' ) ) ) ?>" class="<?php echo 'my-subscribes' == $filter ? 'active' : '' ?>"><?php _e( 'My subscribes', 'dwqa' ); ?></a>
		<?php endif; ?>
	<?php else : ?>
		<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'questions' ) ,home_url( '/' ) ) ) ?>" class="<?php echo 'questions' == $filter ? 'active' : '' ?>"><?php _e( 'Questions', 'dwqa' ); ?></a>
		<?php
		/*
		#Dev
		#Todayisnow
		#201704120150
		#profile answers and questions
		*/
		?>
		<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'answers' ) ,home_url( '/' ) ) ) ?>" class="<?php echo 'answers' == $filter ? 'active' : '' ?>"><?php _e( 'Answers', 'dwqa' ); ?></a>
		<a href="<?php echo esc_url( add_query_arg( array( 'filter' => 'subscribes' ) ,home_url( '/' ) ) ) ?>" class="<?php echo 'subscribes' == $filter ? 'active' : '' ?>"><?php _e( 'Subscribes', 'dwqa' ); ?></a>
	<?php endif; ?>
	<select id="dwqa-sort-by" class="dwqa-sort-by" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
		<option selected disabled><?php _e( 'Sort by', 'dwqa' ); ?></option>
		<option <?php selected( $sort, 'views' ) ?> value="<?php echo esc_url( add_query_arg( array( 'sort' => 'views' ) ) ) ?>"><?php _e( 'Views', 'dwqa' ) ?></option>
		<option <?php selected( $sort, 'answers' ) ?> value="<?php echo esc_url( add_query_arg( array( 'sort' => 'answers' ) ) ) ?>"><?php _e( 'Answers', 'dwqa' ); ?></option>
		<option <?php selected( $sort, 'votes' ) ?> value="<?php echo esc_url( add_query_arg( array( 'sort' => 'votes' ) ) ) ?>"><?php _e( 'Votes', 'dwqa' ) ?></option>
	</select>
</div>