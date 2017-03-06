<?php
class Apollo13 {

	//current theme settings
	private $theme_options = array();
	private $parents_of_meta = array();
	//all possible theme settings
	public $all_theme_options = array();

	function start() {
		/**
         * Define bunch of helpful paths
         */
		define('A13_TPL_SLUG', 'superior');
		define('A13_TPL_NAME', 'Superior');
        define('THEMENAME', A13_TPL_NAME );//same cause this one is used by TGM Plugin Activation
		define('A13_TPL_ALT_NAME', 'Superior'); //alternative name for visuals
		define('A13_TPL_URI', get_template_directory_uri());
		define('A13_TPL_DIR', get_template_directory());
		define('A13_TPL_GFX', A13_TPL_URI . '/images');
		define('A13_TPL_GFX_DIR', A13_TPL_DIR . '/images');
		define('A13_TPL_CSS', A13_TPL_URI . '/css');
		define('A13_TPL_CSS_DIR', A13_TPL_DIR . '/css');
		define('A13_TPL_JS', A13_TPL_URI . '/js');
		define('A13_TPL_ADV', A13_TPL_URI . '/advance');
		define('A13_TPL_ADV_DIR', A13_TPL_DIR . '/advance');
		define('A13_TPL_PLUGINS', A13_TPL_ADV . '/plugins');
		define('A13_TPL_PLUGINS_DIR', A13_TPL_ADV_DIR . '/plugins');
		define('A13_USER_GENERATED', A13_TPL_URI . '/user');
		define('A13_USER_GENERATED_DIR', A13_TPL_DIR . '/user');
		define('A13_CUSTOM_POST_TYPE_WORK', 'work');
//		define('A13_CUSTOM_POST_TYPE_WORK_SLUG', 'work'); //defined a bit lower
		define('A13_CPT_WORK_TAXONOMY', 'genre');
		define('A13_CUSTOM_POST_TYPE_GALLERY', 'gallery');
		define('A13_CUSTOM_POST_TYPE_GALLERY_SLUG', 'gallery');
		define('A13_CPT_GALLERY_TAXONOMY', 'kind');
		define('A13_VALIDATION_CLASS', 'apollo_validation_on');
		define('A13_DOCS_LINK', 'http://apollo13.eu/docs/superior/index.html');

        //check for them version(we try to get parent theme version)
        $theme_data = wp_get_theme();
        $have_parent = $theme_data->parent();
        if($have_parent)
            $t_ver = $theme_data->parent()->Version;
        else
            $t_ver = $theme_data->Version;
		define('A13_THEME_VER', $t_ver);

		/**
         * GET THEME OPTIONS
         */
		$this->set_options();
        //if some settings have been changed
		if ( isset( $_POST[ 'theme_updated' ] ) ) {
			$options_name = str_replace( 'apollo13_', '', $_GET['page']);
			$this->update_options( $options_name );
		}
        //on fresh install/update
        if(!file_exists($this->user_css_name())){
            $this->generate_user_css();
        }

        //used to compere with global options
        $this->collect_meta_parents();

        //defined Theme constants after getting theme options
		define('A13_CUSTOM_POST_TYPE_WORK_SLUG', $this->theme_options[ 'cpt_work' ][ 'cpt_post_type_work' ]);


		// SET LANGUAGE
		add_action( 'init' , array( &$this , 'set_lang') );

		// ADMIN PART
		if ( is_admin() ) {
            require_once (A13_TPL_ADV_DIR . '/admin/admin.php');
            require_once (A13_TPL_ADV_DIR . '/admin/admin_ajax.php');
            require_once (A13_TPL_ADV_DIR . '/admin/metaboxes.php');
            require_once (A13_TPL_ADV_DIR . '/admin/multiupload.php');
            require_once (A13_TPL_ADV_DIR . '/admin/print_options.php');
		}

        // AFTER SETUP(supports for thumbnails, menus, RSS etc.)
		add_action( 'after_setup_theme', array( &$this, 'setup' ) );

        // ADD MEGA MENU
        require_once (A13_TPL_ADV_DIR . '/mega_menu.php');

        // ADD WORKS SUPPORT
        require_once (A13_TPL_ADV_DIR . '/cpt_work.php');

        // ADD GALLERIES SUPPORT
        require_once (A13_TPL_ADV_DIR . '/cpt_gallery.php');

        // ADD SIDEBARS & WIDGETS
        require_once (A13_TPL_ADV_DIR . '/widgets.php');


        // THEME SCRIPTS & STYLES
        require_once (A13_TPL_ADV_DIR . '/head_scripts_styles.php');

        /**
         * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
         */
        if(function_exists('vc_set_as_theme')){
            vc_set_as_theme(true);
            require_once (A13_TPL_PLUGINS_DIR . '/js_composer_mod/js_composer.php');
        }

        //ADD WOOCOMMERCE EXTENSION
        require_once (A13_TPL_PLUGINS_DIR . '/woocommerce_mod/woocommerce.php');

        //ADD EXTERNAL PLUGINS
        require_once (A13_TPL_ADV_DIR . '/inc/class-tgm-plugin-activation.php');
        add_action( 'tgmpa_register', array( &$this, 'register_required_plugins' ) );


		// UTILITIES
        require_once (A13_TPL_ADV_DIR . '/utilities.php');


        // FOR DEBUGGING
//		print_r( $this->theme_options );
//		print_r( $this->all_theme_options );
//		print_r( $this->parents_of_meta );
	}

	/**
     * Languages support
     */
    function set_lang() {
        // For admin translation
        if ( is_admin() ) {
            load_theme_textdomain( A13_TPL_SLUG.'_admin' , A13_TPL_DIR . '/languages/admin' );
        }
        // For front-end translation
        else{
            load_theme_textdomain( A13_TPL_SLUG , A13_TPL_DIR . '/languages' );
        }
    }

	function setup() {
        global $content_width;
        //content width
        if ( ! isset( $content_width ) ) $content_width = 799;

        //Featured image support
        add_theme_support( 'post-thumbnails' );
        add_image_size( 'sidebar-size', 100, 75, true );
        add_image_size( 'apollo-post-thumb', 799, 0, true); //(cropped)
        add_image_size( 'apollo-post-thumb-big', 1080, 0, true); //(cropped)
        add_image_size( 'apollo-post-short_list', 280, 0, true); //(cropped)
        add_image_size( 'apollo-post-brick', 460, 240, true); //(cropped)

        //blog masonry variant
        $temp_w = $this->get_option( 'blog', 'brick_width' );
        add_image_size( 'apollo-post-masonry_blog', intval($temp_w*1.2), 0, true ); /* better quality for stretched photos*/

        //work cover
        add_image_size( 'work-cover-big', 530, 270, true );
        add_image_size( 'work-cover-mid', 346, 220, true );
        add_image_size( 'work-cover-small', 255, 220, true );
//        add_image_size( 'work-cover-big-sidebar', 340, 180, true );
//        add_image_size( 'work-cover-mid-sidebar', 220, 150, true );
//        add_image_size( 'work-cover-small-sidebar', 160, 140, true );

        //work cover masonry
        $temp_w = $this->get_option( 'cpt_work', 'brick_width' );
        $temp_h = $this->get_option( 'cpt_work', 'brick_height' );
        add_image_size( 'work-cover-custom', intval($temp_w*1.2), intval($temp_h*1.2), true ); /* better quality for stretched photos*/

        //work scroller image
        $temp_h = $this->get_option( 'cpt_work', 'scroller_height' );
        add_image_size( 'work-scroller-image', 0, $temp_h, true );

        //work slider image
        $temp_h = $this->get_option( 'cpt_work', 'slider_height' );
        add_image_size( 'work-slider-image', 0, $temp_h, true );


        //Gallery cover
        $temp_w = $this->get_option( 'cpt_gallery', 'gl_brick_width' );
        $temp_h = $this->get_option( 'cpt_gallery', 'gl_brick_height' );
        add_image_size( 'gallery-cover-custom', intval($temp_w*1.2), intval($temp_h*1.2), true ); /* better quality for stretched photos*/

        //Gallery bricks
        $temp_w = $this->get_option( 'cpt_gallery', 'brick_width' );
        $temp_h = $this->get_option( 'cpt_gallery', 'brick_height' );
//        add_image_size( 'gallery-bricks', $temp_w, $temp_h, true );
        add_image_size( 'gallery-bricks', intval($temp_w*1.2), intval($temp_h*1.2), true ); /* better quality for stretched photos*/

        //gallery slider image
        $temp_h = $this->get_option( 'cpt_gallery', 'slider_height' );
        add_image_size( 'gallery-slider-image', 0, $temp_h, true );


		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Add naviagation menu ability.
		add_theme_support('menus');

		//Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

        // Add POST FORMATS
        add_theme_support('post-formats', array( 'aside', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video', 'audio'));

        // Switches default core markup for search form, comment form, and comments
        // to output valid HTML5.
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

        // WooCommerce support
        add_theme_support( 'woocommerce' );

		register_nav_menus(array(
			'header-menu' => __be('Site Navigation' ),
			'footer-menu' => __be('Footer short menu' ),
		));

        if ( current_user_can('update_core') ) {
            $this->check_for_warnings();
        }
	}

    function check_for_warnings(){
        //Notice if dir for user settings is no writable
        if(!is_writeable(A13_USER_GENERATED_DIR)){
            add_action( 'admin_notices', 'not_writable_user_dir_error_notice' );
        }

        //Notice if cpt slug is taken
        //WORKS
        $r = new WP_Query(array('post_type' => array( 'post', 'page'), 'name' => A13_CUSTOM_POST_TYPE_WORK_SLUG));
        if ($r->have_posts()){
            add_action( 'admin_notices', 'taken_work_slug_error_notice' );
        }
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        //GALLERIES
        $r = new WP_Query(array('post_type' => array( 'post', 'page'), 'name' => A13_CUSTOM_POST_TYPE_GALLERY_SLUG));
        if ($r->have_posts()){
            add_action( 'admin_notices', 'taken_gallery_slug_error_notice' );
        }
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        //UPDATE INFO
        require_once (A13_TPL_ADV_DIR . '/inc/theme-update-checker.php');
        $update_checker = new ThemeUpdateChecker(
            A13_TPL_SLUG,
            'http://apollo13.eu/themes_update/superior/info.json'
        );

        function not_writable_user_dir_error_notice(){
            echo '<div class="error"><p>';
            printf( __be('Warning - directory %s is not writable.'), A13_USER_GENERATED_DIR);
            echo '</p></div>';
        }

        function taken_work_slug_error_notice(){
            echo '<div class="error"><p>';
            printf( __be('Warning - slug for Works is taken by page or post! It may cause problems with displaying albums. Edit slug of <a href="%s">this post</a> to make sure everything works good.'), site_url('/' . A13_CUSTOM_POST_TYPE_WORK_SLUG) );
            echo '</p></div>';
        }

        function taken_gallery_slug_error_notice(){
            echo '<div class="error"><p>';
            printf( __be('Warning - slug for Galleries is taken by page or post! It may cause problems with displaying albums. Edit slug of <a href="%s">this post</a> to make sure everything works good.'), site_url('/' . A13_CUSTOM_POST_TYPE_GALLERY_SLUG) );
            echo '</p></div>';
        }

        function update_a13_theme_notice(){
            $state = get_option('external_theme_updates-'.A13_TPL_SLUG);
            $update = $state->update;

            if ( is_string($state->update) ) {
                $update = ThemeUpdate::fromJson($state->update);
            }
            if(!version_compare(A13_THEME_VER,$update->version,"<")){
                return; //other check cause update plugin is failing sometimes
            }
            echo '<div class="updated"><p>';

            printf( __be( 'There is new version <em>%1$s</em> of <strong>%2$s theme</strong> available. Please go <a href="%3$s" target="_blank">ThemeForest</a> and get new version of it. Next follow <a href="%4$s" target="_blank">update instructions from documentation</a>. Good luck ;-) <br /><a href="%5$s" target="_blank">Check changes in Change log</a>'),
                $update->version,
                A13_TPL_NAME,
                $update->details_url,
                A13_DOCS_LINK.'#!/installation_update_update_theme',
                'http://www.apollo13.eu/themes_update/changelog.php?t=superior'
            );
            echo '</p></div>';
        }
    }

    function register_required_plugins(){
        /**
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
        $plugins = require_once(A13_TPL_PLUGINS_DIR . '/plugins-list.php');

        // Change this to your theme text domain, used for internationalising strings
        $theme_text_domain = A13_TPL_SLUG;

        /**
         * Array of configuration settings. Amend each line as needed.
         * If you want the default strings to be available under your own theme domain,
         * leave the strings uncommented.
         * Some of the strings are added into a sprintf, so see the comments at the
         * end of each line for what each argument will be.
         */
        $config = array(
            'default_path' 		=> A13_TPL_PLUGINS_DIR,     // Default absolute path to pre-packaged plugins
            'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
        );

        tgmpa( $plugins, $config );
    }

	private function set_options(){
		require_once (A13_TPL_ADV_DIR . '/options.php');

		$option_func = array(
			'settings',
			'appearance',
			'blog',
			'fonts',
			'cpt_work',
			'cpt_gallery',
			'cpt_gallery',
			'contact',
			'sidebars',
			'socials',
			'advanced',
			'customize',
		);

		foreach($option_func as $function){
			$function_to_call = 'apollo13_' . $function . '_options';

			//firstly collect all default setting
			foreach( $function_to_call() as $option) {
				//get current settings
				if (isset($option['default'])) {
					$this->theme_options[ $function ][ $option['id'] ] = $option['default'];
				}
				//get possible settings
				if (isset($option['options'])) {
					$this->all_theme_options[ $function ][ $option['id'] ] = $option['options'];
				}
			}

            //get current settings saved in database
			$get_opt = get_option( A13_TPL_SLUG . '_' . $function );

			//secondly overwrite with current settings
			if( ! empty( $get_opt ) && is_array( $get_opt ) ){
				$this->theme_options[ $function ] = array_merge( (array) $this->theme_options[ $function ] , $get_opt );
			}

			//clear data
			foreach( $this->theme_options[ $function ] as $key => $value ) {
				if( ! is_array( $value ))
					$this->theme_options[ $function ][ $key ] = stripslashes( $value );
			}
		}
	}

	public function get_option( $index1, $index2 ){
		return $this->theme_options[ $index1 ][ $index2 ];
	}

	public function set_option( $index1, $index2, $value ){
		$this->theme_options[ $index1 ][ $index2 ] = $value;
	}

    private function collect_meta_parents(){
        require_once (A13_TPL_ADV_DIR . '/meta.php');

        $option_func = array(
            'post',
            'page',
            'cpt_work',
            'cpt_gallery',
            'cpt_images'
        );

        foreach($option_func as $function){
            $function_to_call = 'apollo13_metaboxes_' . $function;

            foreach( $function_to_call() as $meta) {
                if (isset($meta['global_value'])) {
                    $this->parents_of_meta[ $function ][ $meta['id'] ]['global_value'] = $meta['global_value'];
                }
                if (isset($meta['parent_option'])) {
                    $this->parents_of_meta[ $function ][ $meta['id'] ]['parent_option'] = $meta['parent_option'];
                }
                if (isset($option['parent_meta'])) {
                    $this->all_theme_options[ $function ][ $meta['id'] ]['parent_meta'] = $meta['parent_meta'];
                }
            }
        }
    }

    /*
     * Save setted options
     */
	function update_options( $options_name, $force_changes = false ){
		$copy_to_compare = $this->theme_options[ $options_name ];

		foreach( $this->theme_options[ $options_name ] as $option => $value ){
			if ( isset( $_POST[ $option ] )) {

				//if option is social options
                if( $option == 'social_services'){
                    $collector = array();
                    foreach( $this->all_theme_options[ $options_name ][ $option ] as $id => $val ){
                        if ( isset( $_POST[ $id ] )) {
                            $collector[$id]['value'] = $_POST[ $id ];
                        }
                        if ( isset( $_POST[ $id .'_pos'  ] )) {
                            $collector[$id]['pos'] = $_POST[ $id .'_pos' ];
                        }
                    }
                    //save
                    $this->theme_options[ $options_name ][ $option ] = $collector;
                }

                //adding new sidebar
                elseif( $option === 'custom_sidebars'){
                    $current_sidebars = unserialize($this->theme_options[ $options_name ][ $option ]);
                    $sidebars_count = count($current_sidebars);
                    if(is_array($current_sidebars) && $sidebars_count > 0){
                        $last_element = end($current_sidebars);
                        $id = $last_element['id'];
                        $id = intval( substr($id,strrpos($id,'_')+1) );
                        //increase id by one
                        $id += 1;

                        $current_sidebars[$id]['id'] = 'a13_custom_sidebar_'.$id;
                        $current_sidebars[$id]['name'] = strlen($_POST[ $option ])? $_POST[ $option ] : $current_sidebars[$id]['id'];
                    }
                    //adding first sidebar
                    else{
                        $current_sidebars[0]['name'] = $_POST[ $option ];
                        $current_sidebars[0]['id'] = 'a13_custom_sidebar_0';
                    }

                    //save sidebars
                    $this->theme_options[ $options_name ][ $option ] = serialize($current_sidebars);
                }

				//if single option
				else{
				    $this->theme_options[ $options_name ][ $option ] = $_POST[ $option ];
                }
			}
		}
        //if there were something changed save options to database
		if ( $force_changes || $this->theme_options[ $options_name ] != $copy_to_compare ) {
			update_option(A13_TPL_SLUG . '_' . $options_name, $this->theme_options[ $options_name ] );
			$this->generate_user_css();
            //for refresh of slugs rules
			define('A13_SETTINGS_CHANGED', true);
		}
	}

    /*
     * Get name of user CSS file in case of multisite
     */
    function user_css_name( $public = false ){
        $name = ($public ? A13_USER_GENERATED : A13_USER_GENERATED_DIR) . '/user'; /* user.css - comment just for easier searching */
        if(is_multisite()){
            //add blog id to file
            global $wpdb;
            $name .= '_'.$wpdb->blogid;
        }

        return $name.'.css';
    }

    /*
     * Make user CSS file from options about theme layout
     */
	function generate_user_css(){
		if ( is_writable( A13_USER_GENERATED_DIR ) ) {
			$file = $this->user_css_name();
			$fh = @fopen( $file, 'w+' );
			$css = include( A13_TPL_ADV_DIR . '/user-css.php' );
			if ( $fh ) {
				fwrite( $fh, $css, strlen( $css ) );
			}
		}
	}

    /*
    * Retrieves meta with checking for parent settings, and global settings
    */
    function get_meta($field, $post_id = false){
        $meta = '';
        $id = $post_id;
        $family = '';

        if(a13_is_no_property_page()){
            return NULL; //we can't get meta field for that page
        }
        else{
            if(!$id){
                $id = get_the_ID();
            }

            $meta = trim(get_post_meta($id, $field, true));
        }

        if($id){
            //get family to check for parent option
            if(get_post_type( $id ) == A13_CUSTOM_POST_TYPE_WORK ) $family = 'cpt_work';
            elseif(get_post_type( $id ) == A13_CUSTOM_POST_TYPE_GALLERY ) $family = 'cpt_gallery';
            elseif(is_page($id) || is_home($id)) $family = 'page';
            elseif(is_single($id)) $family = 'post';

            $field = substr($field, 1); //remove '_'

            //if has any parent
            if(isset($this->parents_of_meta[$family][$field])){
                $parent = $this->parents_of_meta[$family][$field];

                //meta points for global setting
                if(isset($parent['global_value']) && ($meta == $parent['global_value'] || strlen($meta) == 0 )){
                    if(isset($parent['parent_meta'])){
                        //not implemented
                    }
                    if(isset($parent['parent_option'])){
                        $po = $parent['parent_option'];
                        $meta = $this->get_option($po[0], $po[1]);
                    }
                }
            }

            return $meta;
        }
            return false;
    }

    function page_type_debug(){
        echo
              "<br />\n" . 'is front page ' . is_front_page()
            . "<br />\n" . 'is home '       . is_home()
            . "<br />\n" . 'is page '       . is_page()
            . "<br />\n" . 'is single '     . is_single()
            . "<br />\n" . 'is singular '   . is_singular()
            . "<br />\n" . 'is 404 '        . is_404()
            . "<br />\n" . 'is archive '    . is_archive()
            . "<br />\n" . 'is category '   . is_category()
            . "<br />\n" . 'is attachment ' . is_attachment()
            . "<br />\n" . 'is search '     . is_search()
            . "<br />\n";

        if(a13_is_woocommerce_activated()){
            echo
                "<br />\n" . 'is woocommerce '          . is_woocommerce()
                . "<br />\n" . 'is cart '               . is_cart()
                . "<br />\n" . 'is account page '       . is_account_page()
                . "<br />\n" . 'is checkout '           . is_checkout()
                . "<br />\n" . 'is _order_received_page '           . is_order_received_page()
                . "<br />\n";
        }
    }
}
