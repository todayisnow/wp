<?php

function a13_child_style(){
    global $wp_styles;

    //use also for child theme style
    $user_css_deps = $wp_styles->registered['user-css']->deps;
    wp_enqueue_style('child-style', get_stylesheet_directory_uri(). '/style.css', $user_css_deps, A13_THEME_VER);

    //change loading order of user.css
    array_push($user_css_deps, array('child-style'));

    //take it out of queue and insert at end
    wp_dequeue_style('user-css');
    wp_enqueue_style('user-css');
}
add_action('wp_enqueue_scripts', 'a13_child_style',27);

/*
 * Add here your functions below, and overwrite native theme functions
 */
/*
 * Add here your functions below, and overwrite native theme functions
 */
 /*
#Dev
#Todayisnow
#201703080333
#include lightbox files
*/
function LoadLightBox() {
	 wp_enqueue_style('lightbox', get_stylesheet_directory_uri(). '/assets/lightbox/src/css/lightbox.css');
	 wp_enqueue_script('lightbox', get_stylesheet_directory_uri(). '/assets/lightbox/src/js/lightbox.js');
	}
// Add hook for front-end <head></head>
add_action('wp_head', 'LoadLightBox');



 /*
#Dev
#Todayisnow
#201703214000
# update slug after updating posttitle
*/
add_filter( 'wp_insert_post_data', 'UpdatePostSlug', 50, 2 );
function UpdatePostSlug( $data, $postarr ) {
    //Check for the  post statuses you want to avoid
    if ( !in_array( $data['post_status'], array( 'draft', 'pending', 'auto-draft' ) ) ) {           
        $data['post_name'] = sanitize_title( $data['post_title'] );
    }
    return $data;
}

add_filter( 'dwqa_prepare_archive_posts', 'dwqa_refilter_question' );
function dwqa_refilter_question( $args ) {
 // change 0 to page id you had place other archive page contain shortcode [dwqa-list-questions]
 if ( is_page( 0 ) ) {
  $answer_args = array(
   'post_type' => 'dwqa-answer',
   'posts_per_page' => 5,
   'post_status' => 'publish',
   'fields' => 'ids',
   'no_found_rows' => true,
   'update_post_term_cache' => false,
   'update_post_meta_cache' => false,
   'author' => get_current_user_id(),
   'orderby' => 'date'
  );

  // this will return array with 5 answer's ids
  $answers = get_posts( $answer_args );
  $question_lists = array();

  foreach( $answers as $answer_id ) {
   $question_lists[] = get_post_meta( $answer_id, '_question', true );
  }

  $args['post__in'] = $question_lists;
 }

 return $args;
}