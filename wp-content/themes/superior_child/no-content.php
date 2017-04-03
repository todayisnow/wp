<?php
/**
 * Used in 404 page, sitemap and no search results page
 */
    global $empty_error_msg, $wp_query, $post, $apollo13;
?>
<?php if( is_404() || isset($empty_error_msg)) : ?>
    <p><span class="info-404"><?php _fe( 'This page seems to not exist or to have been removed.' ); ?> <a href="/"><?php _fe( 'Go back to the homepage' ); ?></a> <?php _fe( 'or browse to another page.' ); ?></span></p>

<?php else:
    //site map page content
    if ( have_posts() ){
        while ( have_posts() ){
            the_post();
            the_content();
        }
    }
endif;
    ?>
    <div class="clear"></div>

