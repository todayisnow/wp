<!DOCTYPE html>
<!--[if lt IE 8]> <html class="no-js lt-ie10 lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie10 lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>    <html class="no-js lt-ie10" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php a13_favicon(); ?>

<script src="<?php echo get_template_directory_uri(); ?>/js/modernizr.min.js" type="text/javascript"></script>

<?php
    global $apollo13;
    a13_theme_head();
    wp_head();
?>

</head>

<body id="top" <?php body_class(); ?>>

<?php
    $fixed_header   = $apollo13->get_option( 'appearance', 'fixed_header' ) === 'on';
    $with_button    = $apollo13->get_option( 'appearance', 'menu_button' ) === 'on';
    $header_search  = $apollo13->get_option( 'appearance', 'header_search' ) === 'on';
    $top_bar        = $apollo13->get_option( 'appearance', 'header_top_bar' ) === 'on';
    $img_logo       = $apollo13->get_option( 'appearance', 'logo_type' ) === 'image';

    $header_classes  = $fixed_header ? 'sticky ': '';
    $header_classes .= 'header-'.$apollo13->get_option( 'appearance', 'header_style' );

    $access_classes  = ($with_button?' with-button' : '');
    $access_classes .= ($header_search?' with-search' : '');
?>
    <header id="header" class="<?php echo esc_attr($header_classes); ?>">
        <?php if($top_bar){ a13_header_top_bar(); } ?>
        <div class="head clearfix">
            <div class="logo-container"><a <?php echo $img_logo? '' : 'class="text-logo" '; ?>id="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php
                if($img_logo)
                    a13_header_logo_image();
                else
                    echo $apollo13->get_option( 'appearance', 'logo_text' );
                ?></a></div>

            <nav id="access" role="navigation" class="<?php echo esc_attr($access_classes); ?>">
                <h3 class="assistive-text"><?php _fe( 'Main menu' ); ?></h3>
                <?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff. */ ?>
                <a class="assistive-text" href="#begin-of-content" title="<?php esc_attr_e( __fe('Skip to primary content') ); ?>"><?php _fe( 'Skip to primary content' ); ?></a>
                <a class="assistive-text" href="#secondary" title="<?php esc_attr_e( __fe('Skip to secondary content') ); ?>"><?php _fe( 'Skip to secondary content' ); ?></a>

                <div class="menu-container">
                    <?php a13_header_menu(); ?>
                </div>

                <?php a13_menu_button(); ?>
            </nav><!-- #access -->

            <?php if( $header_search ){ a13_header_search(); } ?>

        </div>
    </header>

    <div id="mid" class="clearfix<?php echo a13_get_mid_classes(); ?>">

        <?php
            if( 0 && WP_DEBUG ){ $apollo13->page_type_debug(); }
