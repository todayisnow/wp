<?php
/**
 * The Sidebar
 *
 */
    global $apollo13;
	if( defined('A13_FULL_WIDTH') && A13_FULL_WIDTH ){
        //no sidebar
    }
	else{
        $sidebar = a13_has_active_sidebar();
        if($sidebar !== false){
	        $meta_id = false;
	        if(get_option('show_on_front') !== 'posts'){
		        if(is_front_page()){
			        $meta_id = get_option( 'page_on_front' );
		        }
		        elseif(is_home()){
			        $meta_id = get_option( 'page_for_posts' );
		        }
	        }

            echo '<aside id="secondary" class="widget-area light-theme" role="complementary">';

            //if has children nav and it is activated
            $sidebar_meta = $apollo13->get_meta('_widget_area', $meta_id);

            if(strrchr($sidebar_meta, 'nav') && a13_page_menu(true)){
                a13_page_menu();
            }

            if(is_page()){
                //for pages only if enabled
                if(strrchr($sidebar_meta, 'sidebar')){
                    dynamic_sidebar( $sidebar );
                }
            }
            else{
                dynamic_sidebar( $sidebar );
            }

            echo '<div class="clear"></div>';
            echo '</aside>';
        }
    }