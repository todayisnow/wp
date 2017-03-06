<?php
/**
 * The template for displaying Search Results pages.
 *
 */

if( have_posts() ):
    /* Search Count */

    $all_search = new WP_Query("s=$s&showposts=-1");
    $count = $all_search->post_count;
    wp_reset_query();

    get_header();
	global $apollo13;
?>

<?php a13_title_bar(sprintf( __fe( '%1$d Search results for "%2$s"' ), $count, get_search_query() )); ?>

<article id="content" class="clearfix <?php echo esc_attr($apollo13->get_option('blog', 'blog_variant')); ?>">

    <?php a13_header_tools() ?>

    <div id="col-mask">

        <div id="post-list">

            <?php
            /* Run the loop to output the post.
             * If you want to overload this in a child theme then include a file
             * called loop-single.php and that will be used instead.
             */
            get_template_part( 'loop' );
            ?>
            <?php a13_blog_nav(); ?>

        </div>

        <?php get_sidebar(); ?>

    </div>

</article>

<?php
    get_footer();
else:
    global $empty_error_msg;
    $empty_error_msg =  sprintf( __fe( 'No Search results for "%1$s"' ), get_search_query() );
    get_template_part( '404' );
endif;
