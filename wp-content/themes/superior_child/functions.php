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
 /*
#Dev
#Todayisnow
#201704194000
# search conten only
*/
add_filter( 'posts_where', 'SearchPosts', 10, 2 );
function SearchPosts( $where, $wp_query )
{
    global $wpdb;
    if ( $mySearch = $wp_query->get( 'my_search' ) ) {
			$myKeys = explode(" ", strtolower($mySearch));
			if(sizeof($myKeys) == 1) {
                $where .= ' AND (' . $wpdb->posts . '.post_content LIKE \'%"lhsI":"%' . esc_sql($wpdb->esc_like($mySearch)) . '%",%\'  OR ' . $wpdb->posts . '.post_content LIKE \'%"rhsI":"%' . esc_sql($wpdb->esc_like($mySearch)) . '%"%\' )';
            }
            else if(sizeof($myKeys) == 3) {
                $where .= ' AND (' . $wpdb->posts . '.post_content LIKE \'%"lhsI":"%' . esc_sql($wpdb->esc_like($myKeys[0])) . '%",%\'  OR ' . $wpdb->posts . '.post_content LIKE \'%"rhsI":"%' . esc_sql($wpdb->esc_like($myKeys[2])) . '%"%\' OR ' . $wpdb->posts . '.post_content LIKE \'%"lhsI":"%' . esc_sql($wpdb->esc_like($myKeys[2])) . '%",%\'  OR ' . $wpdb->posts . '.post_content LIKE \'%"rhsI":"%' . esc_sql($wpdb->esc_like($myKeys[0])) . '%"%\' )';
            }
            else
            {
                $where .=' AND 1=0';
            }


    }
    return $where;
}