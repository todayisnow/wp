<?php

/* to display separator in footer widget area */
function a13_cur_target_sidebar_add_classes_to_params($params) {
    global $my_widget_num; // Global a counter array
    $sidebar_id = $params[0]['id'];

    if ( $sidebar_id == 'footer-widget-area' ) {

        $registered_widgets = wp_get_sidebars_widgets();
        if(!isset($registered_widgets[$sidebar_id]) || !is_array($registered_widgets[$sidebar_id])) { // Check if the current sidebar has no widgets
            return $params; // No widgets in this sidebar... bail early.
        }

        $number_of_widgets = count($registered_widgets[$sidebar_id]);

        if(!$my_widget_num) {// If the counter array doesn't exist, create it
            $my_widget_num = array();
        }

        if(isset($my_widget_num[$sidebar_id])) { // See if the counter array has an entry for this sidebar
            $my_widget_num[$sidebar_id] ++;
        } else { // If not, create it starting with 1
            $my_widget_num[$sidebar_id] = 1;
        }

        $hr = '<hr class="foot-separator ';
        $hr_e = '" />';
        $end_widget = $my_widget_num[$sidebar_id] === $number_of_widgets;

        if($my_widget_num[$sidebar_id]%2 === 0 && !$end_widget) {
            $params[0]['after_widget'] .= $hr.'after_second'.$hr_e;
        }
        if($my_widget_num[$sidebar_id]%3 === 0 && !$end_widget) {
            $params[0]['after_widget'] .= $hr.'after_third'.$hr_e;
        }
        if($my_widget_num[$sidebar_id]%4 === 0 && !$end_widget) {
            $params[0]['after_widget'] .= $hr.'after_fourth'.$hr_e;
        }
        if($my_widget_num[$sidebar_id]%5 === 0 && !$end_widget) {
            $params[0]['after_widget'] .= $hr.'after_fifth'.$hr_e;
        }
        //after all widgets
        if($end_widget) {
            $params[0]['after_widget'] .= $hr.'end_list'.$hr_e;
        }
    }

    return $params;
}
if(!is_admin()){ //only for front-end
    add_filter('dynamic_sidebar_params','a13_cur_target_sidebar_add_classes_to_params');
}

/* to unify look of post in widgets */
if(!function_exists('a13_widget_posts')){
    function a13_widget_posts($r, $instance, $type = 'normal' ){
        while ($r->have_posts()) : $r->the_post();
            $page_title = get_the_title();

            $img = a13_make_post_image( get_the_ID(), 'sidebar-size');
            $full = (!$img)? '' : ' full';

            echo '<div class="item clearfix'.$full.'">';

            if(strlen($img)){
                echo '<a class="thumb" href="' . esc_url(get_permalink()) . '" title="' . esc_attr($page_title) . '">' . $img . '</a>';
            }

            echo '<a class="post-title" href="' . esc_url(get_permalink()) . '" title="' . esc_attr($page_title) . '">' . $page_title . '</a>';
            if($type === 'popular'){
                echo '<a class="comments" href="' . get_comments_link() . '" title="' . get_comments_number() . ' ' . __fe( 'comment(s)' ). '">'.get_comments_number().' '.__fe( 'comment(s)' ).'</a>';
            }
            else{
                echo a13_posted_on(false);
            }

            //if user want excerpt also and post is not password protected
            if(!empty( $instance['content'] ) && !post_password_required()){
                echo  '<a class="content" href="' . get_permalink() . '" title="' . esc_attr($page_title) . '">';
                $text = get_the_content('');
                $text = strip_shortcodes( $text );
                $text = wp_trim_words( $text, 30, '' );
                echo $text;
                echo '</a>';
            }
            echo '</div>';

        endwhile;
    }
}


if(!function_exists('a13_add_sidebars')){
    function a13_add_sidebars() {
        //defined sidebars
        $widget_areas = array(
            // Shown on blog
            'blog-widget-area' => array(
                'name' => __be( 'Blog sidebar' ),
                'description' => __be( 'Widgets from this sidebar will appear on blog, search results, archive page and 404 error page.' ),
            ),

            // Shown in post
            'post-widget-area' => array(
                'name' => __be( 'Post sidebar' ),
                'description' => __be( 'Widgets from this sidebar will appear in single posts.' ),
            ),

            // Shown in pages
            'page-widget-area' => array(
                'name' => __be( 'Page sidebar' ),
                'description' => __be( 'Widgets from this sidebar will appear in static pages.' ),
            ),
        );

        //custom sidebars
        global $apollo13;
        $custom_sidebars = unserialize($apollo13->get_option( 'sidebars', 'custom_sidebars' ));
        $sidebars_count = count($custom_sidebars);
        if(is_array($custom_sidebars) && $sidebars_count > 0){
            foreach($custom_sidebars as $sidebar){
                $widget_areas[$sidebar['id']] = array(
                    'name' => $sidebar['name'],
                    'description' => __be( 'Widgets from this sidebar will appear in static pages.' ),
                );
            }
        }

        /**
         * Register widgets areas
         */
        foreach($widget_areas as $id => $sidebar){
            register_sidebar( array(
                'name'          => $sidebar['name'],
                'id'            => $id,
                'description'   => $sidebar['description'],
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="title"><span>',
                'after_title'   => '</span></h3>',
            ) );
        }
        // Shown in footer
        register_sidebar( array(
            'name' => __be( 'Footer widget area' ),
            'id' => 'footer-widget-area',
            'description' => __be( 'Widgets from this area will appear in footer.' ),
            'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="title"><span>',
            'after_title' => '</span></h3>',
        ) );



        class Apollo13_Widget_Recent_Posts extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_recent_posts widget_about_posts', 'description' => __be( 'The most recent posts on your site' ) );
                parent::__construct('recent-posts', __be( 'Recent Posts' ), $widget_ops);
                $this->alt_option_name = 'widget_recent_entries';

                add_action( 'save_post', array(&$this, 'flush_widget_cache') );
                add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
                add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
            }

            function widget($args, $instance) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                $cache = wp_cache_get('widget_recent_entries', 'widget');

                if ( !is_array($cache) ){
                    $cache = array();
                }

                if ( isset($cache[$args['widget_id']]) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                ob_start();
                extract($args);

                $title = apply_filters('widget_title', empty($instance['title']) ? __fe('Recent Posts' ) : $instance['title'], $instance, $this->id_base);
                if ( ! $number = absint( $instance['number'] ) ){
                    $number = 10;
                }

                $r = new WP_Query(array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
                if ($r->have_posts()) :
                    echo $before_widget;

                    if( $title ){
                        echo $before_title . $title . $after_title;
                    }

                    a13_widget_posts($r, $instance);

                    echo $after_widget;

                    // Reset the global $the_post as this query will have stomped on it
                    wp_reset_postdata();

                endif;

                $cache[$args['widget_id']] = ob_get_flush();
                wp_cache_set('widget_recent_entries', $cache, 'widget');
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['number'] = (int) $new_instance['number'];
                $instance['content'] = isset($new_instance['content']);

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_recent_entries']) )
                    delete_option('widget_recent_entries');

                return $instance;
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_recent_entries', 'widget');
            }

            function form( $instance ) {
                $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $number = isset($instance['number']) ? absint($instance['number']) : 5;
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <p><label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _be('Number of posts to show:' ); ?></label>
                <input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>

            <p><input id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" type="checkbox" <?php checked(isset($instance['content']) ? $instance['content'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php _be('Add posts excerpt'); ?></label></p>
            <?php
            }
        }
        register_widget('Apollo13_Widget_Recent_Posts');


        class Apollo13_Widget_Popular_Posts extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_popular_entries widget_about_posts', 'description' => __be( 'The most popular posts on your site' ) );
                parent::__construct('popular-posts', __be( 'Popular Posts' ), $widget_ops);
                $this->alt_option_name = 'widget_popular_entries';

                add_action( 'save_post', array(&$this, 'flush_widget_cache') );
                add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
                add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
            }

            function widget($args, $instance) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                $cache = wp_cache_get('widget_popular_entries', 'widget');

                if ( !is_array($cache) ){
                    $cache = array();
                }

                if ( isset($cache[$args['widget_id']]) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                ob_start();
                extract($args);

                $title = apply_filters('widget_title', empty($instance['title']) ? __fe('Popular Posts' ) : $instance['title'], $instance, $this->id_base);
                if ( ! $number = absint( $instance['number'] ) ){
                    $number = 10;
                }

                $r = new WP_Query(array('posts_per_page' => $number, 'no_found_rows' => true, 'orderby'=> 'comment_count', 'post_status' => 'publish', 'ignore_sticky_posts' => true));
                if ($r->have_posts()) :
                    echo $before_widget;

                    if( $title ){
                        echo $before_title . $title . $after_title;
                    }

                    a13_widget_posts($r, $instance, 'popular');

                    echo $after_widget;

                    // Reset the global $the_post as this query will have stomped on it
                    wp_reset_postdata();

                endif;

                $cache[$args['widget_id']] = ob_get_flush();
                wp_cache_set('widget_popular_entries', $cache, 'widget');
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['number'] = (int) $new_instance['number'];
                $instance['content'] = isset($new_instance['content']);

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_popular_entries']) )
                    delete_option('widget_popular_entries');

                return $instance;
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_popular_entries', 'widget');
            }

            function form( $instance ) {
                $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $number = isset($instance['number']) ? absint($instance['number']) : 5;
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <p><label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _be('Number of posts to show:' ); ?></label>
                <input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>

            <p><input id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" type="checkbox" <?php checked(isset($instance['content']) ? $instance['content'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php _be('Add posts excerpt'); ?></label></p>
            <?php
            }
        }
        register_widget('Apollo13_Widget_Popular_Posts');


        class Apollo13_Widget_Related_Posts extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_related_entries widget_about_posts', 'description' => __be( 'Related posts to current post' ) );
                parent::__construct('related-posts', __be( 'Related Posts' ), $widget_ops);
                $this->alt_option_name = 'widget_related_entries';

                add_action( 'save_post', array(&$this, 'flush_widget_cache') );
                add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
                add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
            }

            function widget($args, $instance) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                $cache = wp_cache_get('widget_related_entries', 'widget');

                if ( !is_array($cache) ){
                    $cache = array();
                }

                if ( isset($cache[$args['widget_id']]) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                ob_start();
                extract($args);

                $title = apply_filters('widget_title', empty($instance['title']) ? __fe('Related Posts' ) : $instance['title'], $instance, $this->id_base);
                if ( ! $number = absint( $instance['number'] ) ){
                    $number = 10;
                }

                global $post;

                $__search = wp_get_post_tags($post->ID);
                $search_string = 'tags__in';
                //if no tags try categories
                if( !count($__search) ){
                    $__search = wp_get_post_categories($post->ID);
                    $search_string = 'category__in';
                }

                if ( count($__search) ) {

                    $r = new WP_Query(array($search_string => $__search,'post__not_in' => array($post->ID), 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
                    if ($r->have_posts()) :
                        echo $before_widget;

                        if( $title ){
                            echo $before_title . $title . $after_title;
                        }

                        a13_widget_posts($r, $instance);

                        echo $after_widget;

                        // Reset the global $the_post as this query will have stomped on it
                        wp_reset_postdata();

                    endif;

                    $cache[$args['widget_id']] = ob_get_flush();
                    wp_cache_set('widget_related_entries', $cache, 'widget');
                }
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['number'] = (int) $new_instance['number'];
                $instance['content'] = isset($new_instance['content']);

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_related_entries']) )
                    delete_option('widget_related_entries');

                return $instance;
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_related_entries', 'widget');
            }

            function form( $instance ) {
                $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $number = isset($instance['number']) ? absint($instance['number']) : 5;
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <p><label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _be('Number of posts to show:' ); ?></label>
                <input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>

            <p><input id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" type="checkbox" <?php checked(isset($instance['content']) ? $instance['content'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php _be('Add posts excerpt'); ?></label></p>
            <?php
            }
        }
        register_widget('Apollo13_Widget_Related_Posts');


        class Apollo13_Widget_Recent_Comments extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_recent_comments', 'description' => '' . __be( 'The most recent comments' ) );
                parent::__construct('recent-comments', __be( 'Recent Comments' ), $widget_ops);
                $this->alt_option_name = 'widget_recent_comments';

                add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
                add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_recent_comments', 'widget');
            }

            function widget( $args, $instance ) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                global $comments, $comment;

                $cache = wp_cache_get('widget_recent_comments', 'widget');

                if ( ! is_array( $cache ) )
                    $cache = array();

                if ( isset( $cache[$args['widget_id']] ) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                extract($args);
                $output = '';
                $title = apply_filters('widget_title', empty($instance['title']) ? __fe('Recent Comments' ) : $instance['title']);

                if ( ! $number = absint( $instance['number'] ) )
                    $number = 5;

                $comments = get_comments( array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish' ) );
                $output .= $before_widget;
                if ( $title )
                    $output .= $before_title . $title . $after_title;


                if ( $comments ) {
                    $iter = 1;
                    foreach ( (array) $comments as $comment) {
                        $output .= '<div class="item'.($iter === 1? ' first-item' : '').'">' ;
                        $output .= '    <span class="author">' . get_comment_author() . '</span> ';
                        $output .= __be('on' );
                        $output .= '    <a class="post-title" href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . get_the_title($comment->comment_post_ID) . '</a>';
                        if(!empty( $instance['content'] )){
                            $output .=  '    <a class="content" href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . a13_get_comment_excerpt( $comment->comment_ID, 30 ) . '</a>';
                        }
                        $output .= '</div>';
                        $iter++;
                    }
                }

                $output .= $after_widget;

                echo $output;
                $cache[$args['widget_id']] = $output;
                wp_cache_set('widget_recent_comments', $cache, 'widget');
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['number'] = absint( $new_instance['number'] );
                $instance['content'] = isset($new_instance['content']);

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_recent_comments']) )
                    delete_option('widget_recent_comments');

                return $instance;
            }

            function form( $instance ) {
                $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $number = isset($instance['number']) ? absint($instance['number']) : 5;
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <p><label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _be('Number of comments to show:' ); ?></label>
                <input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>

            <p><input id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" type="checkbox" <?php checked(isset($instance['content']) ? $instance['content'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php _be('Add comments excerpt'); ?></label></p>
            <?php
            }
        }
        register_widget('Apollo13_Widget_Recent_Comments');


        class Apollo13_Nav_Menu_Widget extends WP_Widget {

            function __construct() {
                $widget_ops = array( 'description' => __be('Use this widget to add one of your custom menus as a widget.') );
                parent::__construct( 'nav_menu', __be('Custom Menu'), $widget_ops );
            }

            function widget($args, $instance) {
                // Get menu
                $nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

                if ( !$nav_menu )
                    return;

                $instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

                echo $args['before_widget'];

                if ( !empty($instance['title']) )
                    echo $args['before_title'] . $instance['title'] . $args['after_title'];

                wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu, 'walker' => new A13_widget_menu_walker ) );

                echo $args['after_widget'];
            }

            function update( $new_instance, $old_instance ) {
                $instance['title'] = strip_tags( stripslashes($new_instance['title']) );
                $instance['nav_menu'] = (int) $new_instance['nav_menu'];
                return $instance;
            }

            function form( $instance ) {
                $title = isset( $instance['title'] ) ? $instance['title'] : '';
                $nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

                // Get menus
                $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

                // If no menus exists, direct the user to go and create some.
                if ( !$menus ) {
                    echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), esc_url(admin_url('nav-menus.php')) ) .'</p>';
                    return;
                }
                ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _be('Title:') ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('nav_menu')); ?>"><?php _be('Select Menu:'); ?></label>
                <select id="<?php echo esc_attr($this->get_field_id('nav_menu')); ?>" name="<?php echo esc_attr($this->get_field_name('nav_menu')); ?>">
                    <?php
                    foreach ( $menus as $menu ) {
                        echo '<option value="' . $menu->term_id . '"'
                            . selected( $nav_menu, $menu->term_id, false )
                            . '>'. $menu->name . '</option>';
                    }
                    ?>
                </select>
            </p>
            <?php
            }
        }
        register_widget('Apollo13_Nav_Menu_Widget');


        class Apollo13_Widget_Recent_Works extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_recent_works', 'description' => __be( 'Your most recent added works' ) );
                parent::__construct('recent-works', __be( 'Recent Works' ), $widget_ops);
                $this->alt_option_name = 'widget_recent_works';

                add_action( 'save_post', array(&$this, 'flush_widget_cache') );
                add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
                add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
            }

            function widget($args, $instance) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                $cache = wp_cache_get('widget_recent_works', 'widget');

                if ( !is_array($cache) )
                    $cache = array();

                if ( isset($cache[$args['widget_id']]) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                ob_start();
                extract($args);

                $title = apply_filters('widget_title', empty($instance['title']) ? __fe('Recent Works' ) : $instance['title'], $instance, $this->id_base);
                if ( ! $number = absint( $instance['number'] ) )
                    $number = 10;

                $r = new WP_Query(array(
                    'posts_per_page' => $number,
                    'no_found_rows' => true,
                    'post_type' => A13_CUSTOM_POST_TYPE_WORK,
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => true,
                    'orderby' => 'date'
                ));
                if ($r->have_posts()) :
                    echo $before_widget;

                    if( $title ){
                        echo $before_title . $title . $after_title;
                    }

                    echo '<div class="clearfix">';

                    while ($r->have_posts()) : $r->the_post();
                        //title
                        $page_title = get_the_title();

                        //image
                        $img = a13_make_work_image(get_the_ID(), 'sidebar-size' );
                        echo '<div class="item"><a href="'.get_permalink().'" title="'.esc_attr($page_title).'">'.$img.'</a></div>';

                    endwhile;

                    echo '</div>';

                    echo $after_widget;

                    // Reset the global $the_post as this query will have stomped on it
                    wp_reset_postdata();

                endif;

                $cache[$args['widget_id']] = ob_get_flush();
                wp_cache_set('widget_recent_works', $cache, 'widget');
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['number'] = (int) $new_instance['number'];

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_recent_works']) )
                    delete_option('widget_recent_works');

                return $instance;
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_recent_works', 'widget');
            }

            function form( $instance ) {
                $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $number = isset($instance['number']) ? absint($instance['number']) : 5;
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <p><label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _be('Number of posts to show:' ); ?></label>
                <input id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>
            <?php
            }
        }
        register_widget('Apollo13_Widget_Recent_Works');

        class Apollo13_Widget_Contact_Info extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_contact_info', 'description' => __be( 'Contact information' ) );
                parent::__construct('contact-info', __be( 'Contact information' ), $widget_ops);
                $this->alt_option_name = 'widget_contact_info';

                add_action( 'save_post', array(&$this, 'flush_widget_cache') );
                add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
                add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
            }

            function widget($args, $instance) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                $cache = wp_cache_get('widget_contact_info', 'widget');

                if ( !is_array($cache) ){
                    $cache = array();
                }

                if ( isset($cache[$args['widget_id']]) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                ob_start();
                extract($args);

                $title = apply_filters('widget_title', empty($instance['title']) ? __fe('Contact information' ) : $instance['title'], $instance, $this->id_base);

                echo $before_widget;

                if( $title ){
                    echo $before_title . $title . $after_title;
                }

                echo '<div class="info">';

                if(!empty($instance['content'])){
                    echo '<div class="content-text">'.$instance['content'].'</div>';
                }
                if(!empty($instance['phone'])){
                    echo '<div class="phone with_icon"><i class="fa fa-phone"></i>'.$instance['phone'].'</div>';
                }
                if(!empty($instance['fax'])){
                    echo '<div class="fax with_icon"><i class="fa fa-print"></i>'.$instance['fax'].'</div>';
                }
                if(!empty($instance['email'])){
                    echo '<a class="email with_icon" href="mailto:'.esc_attr($instance['email']).'"><i class="fa fa-envelope"></i>'.$instance['email'].'</a>';
                }
                if(!empty($instance['www'])){
                    echo '<a class="www with_icon" href="'.esc_url($instance['www']).'"><i class="fa fa-globe"></i>'.$instance['www'].'</a>';
                }
                if(!empty($instance['open'])){
                    echo '<div class="content-open with_icon"><i class="fa fa-time"></i>'.nl2br($instance['open']).'</div>';
                }

                echo '</div>';

                echo $after_widget;

                $cache[$args['widget_id']] = ob_get_flush();
                wp_cache_set('widget_related_entries', $cache, 'widget');
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title']  = strip_tags($new_instance['title']);
                $instance['phone']  = strip_tags($new_instance['phone']);
                $instance['email']  = strip_tags($new_instance['email']);
                $instance['fax']    = strip_tags($new_instance['fax']);
                $instance['www']    = strip_tags($new_instance['www']);
                $instance['content']= $new_instance['content'];
                $instance['open']   = strip_tags($new_instance['open']);

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_contact_info']) )
                    delete_option('widget_contact_info');

                return $instance;
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_contact_info', 'widget');
            }

            function form( $instance ) {
                $title  = isset($instance['title']) ? esc_attr($instance['title']) : '';
                $phone  = isset($instance['phone']) ? esc_attr($instance['phone']) : '';
                $email  = isset($instance['email']) ? esc_attr($instance['email']) : '';
                $fax    = isset($instance['fax']) ? esc_attr($instance['fax']) : '';
                $www    = isset($instance['www']) ? esc_attr($instance['www']) : '';
                $content= isset($instance['content']) ? esc_textarea($instance['content']) : '';
                $open   = isset($instance['open']) ? esc_textarea($instance['open']) : '';
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
            <p><label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php _be('Content:' ); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" cols="20" rows="8"><?php echo esc_textarea($content); ?></textarea></p>
            <p><label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php _be('Phone:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" type="text" value="<?php echo esc_attr($phone); ?>" /></p>
            <p><label for="<?php echo esc_attr($this->get_field_id('fax')); ?>"><?php _be('Fax:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('fax')); ?>" name="<?php echo esc_attr($this->get_field_name('fax')); ?>" type="text" value="<?php echo esc_attr($fax); ?>" /></p>
            <p><label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php _be('E-mail:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" type="text" value="<?php echo esc_attr($email); ?>" /></p>
            <p><label for="<?php echo esc_attr($this->get_field_id('www')); ?>"><?php _be('Site:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('www')); ?>" name="<?php echo esc_attr($this->get_field_name('www')); ?>" type="text" value="<?php echo esc_attr($www); ?>" /></p>
            <p><label for="<?php echo esc_attr($this->get_field_id('open')); ?>"><?php _be('Open hours info:' ); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('open')); ?>" name="<?php echo esc_attr($this->get_field_name('open')); ?>" cols="20" rows="8"><?php echo esc_textarea($open); ?></textarea></p>

            <?php
            }
        }
        register_widget('Apollo13_Widget_Contact_Info');


        class Apollo13_Widget_Shortcodes extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_shortcodes', 'description' => __be('Widget to put shortcodes in'));
                $control_ops = array('width' => 400, 'height' => 350);
                parent::__construct('a13-shortcodes', __be('Shortcodes'), $widget_ops, $control_ops);
            }

            function widget( $args, $instance ) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                extract($args);
                $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
                $text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
                echo $before_widget;
                if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
            <div class="textwidget"><?php echo do_shortcode( $text ); ?></div>
            <?php
                echo $after_widget;
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                if ( current_user_can('unfiltered_html') )
                    $instance['text'] =  $new_instance['text'];
                else
                    $instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
                $instance['filter'] = isset($new_instance['filter']);
                return $instance;
            }

            function form( $instance ) {
                $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
                $title = strip_tags($instance['title']);
                $text = esc_textarea($instance['text']);
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _be('Title:'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php echo esc_attr($this->get_field_name('text')); ?>"><?php echo esc_textarea($text); ?></textarea>

            <p><input id="<?php echo esc_attr($this->get_field_id('filter')); ?>" name="<?php echo esc_attr($this->get_field_name('filter')); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr($this->get_field_id('filter')); ?>"><?php _be('Automatically add paragraphs'); ?></label></p>
            <?php
            }
        }
        register_widget('Apollo13_Widget_Shortcodes');

        class Apollo13_Widget_Social_Icons extends WP_Widget {

            function __construct() {
                $widget_ops = array('classname' => 'widget_a13_social_icons', 'description' => __be( 'Social icons form theme settings' ) );
                parent::__construct('a13-social-icons', __be( 'Apollo13 Social Icons' ), $widget_ops);
                $this->alt_option_name = 'widget_a13_social_icons';

                add_action( 'save_post', array(&$this, 'flush_widget_cache') );
                add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
                add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
            }

            function widget($args, $instance) {
	            $before_widget = $after_widget = $before_title = $after_title = '';
                $cache = wp_cache_get('widget_a13_social_icons', 'widget');

                if ( !is_array($cache) ){
                    $cache = array();
                }

                if ( isset($cache[$args['widget_id']]) ) {
                    echo $cache[$args['widget_id']];
                    return;
                }

                ob_start();
                extract($args);

                $title = apply_filters('widget_title', empty($instance['title']) ? __fe('Social Icons' ) : $instance['title'], $instance, $this->id_base);

                $icons = a13_social_icons();
                if (strlen($icons)) :
                    echo $before_widget;

                    if( $title ){
                        echo $before_title . $title . $after_title;
                    }

                    echo $icons;

                    echo $after_widget;

                endif;

                $cache[$args['widget_id']] = ob_get_flush();
                wp_cache_set('widget_a13_social_icons', $cache, 'widget');
            }

            function update( $new_instance, $old_instance ) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);

                $this->flush_widget_cache();

                $alloptions = wp_cache_get( 'alloptions', 'options' );
                if ( isset($alloptions['widget_a13_social_icons']) )
                    delete_option('widget_a13_social_icons');

                return $instance;
            }

            function flush_widget_cache() {
                wp_cache_delete('widget_a13_social_icons', 'widget');
            }

            function form( $instance ) {
                $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
                ?>
            <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _be('Title:' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

            <?php
            }
        }
        register_widget('Apollo13_Widget_Social_Icons');
    }
}

add_action( 'widgets_init', 'a13_add_sidebars' );
?>