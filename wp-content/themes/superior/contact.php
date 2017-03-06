<?php
/**
Template Name: Contact(map full width)
 * The template for displaying Contact form.
 *
 */
global $apollo13;

get_header();

the_post();

$map_on = $apollo13->get_option('contact','contact_map') === 'on';
?>

<?php a13_title_bar(); ?>

<article id="content" class="clearfix<?php echo esc_attr($map_on? ' with-map' : '');?>">

    <?php a13_header_tools(); ?>

    <div id="col-mask">

        <div id="post-<?php the_ID(); ?>" <?php post_class('post-content'); ?>>
            <?php
            a13_top_image_video();
            ?>

            <div class="real-content">
                <?php the_content(); ?>

                <div class="clear"></div>

                <?php
                wp_link_pages( array(
                        'before' => '<div id="page-links">'.__fe('Pages: '),
                        'after'  => '</div>')
                );
                ?>
            </div>

        </div>

        <?php get_sidebar(); ?>

    </div>
    <?php get_template_part( 'parts/map' ); ?>
</article>

<?php get_footer(); ?>