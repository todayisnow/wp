<?php global $apollo13; ?>
		</div><!-- #mid -->
        <footer id="footer" class="glass">
            <div class="foot-widget-content">
                <?php
                //is there any widgets
                if( is_active_sidebar('footer-widget-area' )){
                    $the_sidebars = wp_get_sidebars_widgets();
                    $widgets_no = count( $the_sidebars['footer-widget-area'] );
                    //class for widgets
                    $_class = 'five-col';

                    if($widgets_no === 5){}
                    elseif($widgets_no < 4 || $widgets_no === 6){
                        $_class = 'three-col';
                    }
                    elseif($widgets_no % 4 === 0 || $widgets_no % 4 === 3){
                        $_class = 'four-col';
                    }

                    echo '<div class="foot-widgets clearfix '.$_class.'">';
                    dynamic_sidebar( 'footer-widget-area' );
                    echo '</div>';
                }
                ?>

                <div class="footer-items clearfix">

                    <?php
                    if ( has_nav_menu( 'footer-menu' ) ){
                        //place for 1-4 links
                        wp_nav_menu( array(
                                'container'       => false,
                                'link_before'     => '',
                                'link_after'      => '',
                                'depth'           => -1,
                                'menu_class'      => 'footer-menu',
                                'theme_location'  => 'footer-menu' )
                        );
                    }

                    $fi = $apollo13->get_option( 'appearance', 'footer_text_image' );
                    if(!empty($fi)){
                        $fi = '<img class="alignleft" src="'.esc_url($fi).'" alt="" />';
                    }

                    $ft = $apollo13->get_option( 'appearance', 'footer_text' )   ;
                    if(!empty($ft) || !empty($fi)){
                        echo '<div class="foot-text">'.$fi.nl2br($ft).'</div>';
                    }

                    ?>
                    <a href="#top" id="to-top" class="to-top fa fa-chevron-up"></a>
                </div>
            </div>
        </footer>
<?php
        /* Always have wp_footer() just before the closing </body>
         * tag of your theme, or you will break many plugins, which
         * generally use this hook to reference JavaScript files.
         */

        wp_footer();
?>

</body>
</html>