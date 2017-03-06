<?php
$target_arr = array(__("Same window", "js_composer") => "_self", __("New window", "js_composer") => "_blank");
$align_arr  = array(__('Align center', "js_composer") => "align_center", __('Align left', "js_composer") => "align_left", __('Align right', "js_composer") => "align_right");
$align_arr_v2  = array( __('Align left', "js_composer") => "left", __('Align center', "js_composer") => "center", __('Align right', "js_composer") => "right");
$add_css_animation = array(
    "type" => "dropdown",
    "heading" => __("CSS Animation", "js_composer"),
    "param_name" => "css_animation",
    "admin_label" => true,
    "value" => array(__("No", "js_composer") => '', __("Top to bottom", "js_composer") => "top-to-bottom", __("Bottom to top", "js_composer") => "bottom-to-top", __("Left to right", "js_composer") => "left-to-right", __("Right to left", "js_composer") => "right-to-left", __("Appear from center", "js_composer") => "appear"),
    "description" => __("Select animation type if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "js_composer")
);




/* VC ROW EXTENSION
---------------------------------------------------------- */
//remove useless in the end "design options"
vc_remove_param("vc_row", 'css');
/* new from VC 4.6 - we remove it as it conflicts with what we already have in theme */
vc_remove_param("vc_row", 'full_width');
vc_remove_param("vc_row", 'full_height');
vc_remove_param("vc_row", 'content_placement');
vc_remove_param("vc_row", 'video_bg');
vc_remove_param("vc_row", 'video_bg_url');
vc_remove_param("vc_row", 'video_bg_parallax');
vc_remove_param("vc_row", 'parallax');
vc_remove_param("vc_row", 'parallax_image');

//Full width
vc_add_param("vc_row", array(
    "type" => "checkbox",
    "heading" => __be("Full width?"),
    "param_name" => "full_width",
    "value" => Array(__("Yes, please", "js_composer") => true),
));

//Full width content
vc_add_param("vc_row", array(
    "type" => "checkbox",
    "heading" => __be("Full width content?"),
    "param_name" => "full_width_content",
    "value" => Array(__("Yes, please", "js_composer") => true),
    "dependency" => array(
        "element" => "full_width",
        'is_empty' => true,
    )
));

//background color
vc_add_param("vc_row", array(
    "type" => "colorpicker",
    "heading" => __be("Background color"),
    "param_name" => "bg_color",
    "description" => __be("Select background color for your row."),
    "value" => "",
));

//background image
vc_add_param("vc_row", array(
    "type" => "attach_image",
    "heading" => __be("Background image"),
    "param_name" => "bg_image",
    "description" => __be("Select background image for your row")
));

//background position
vc_add_param("vc_row", array(
    "type" => "dropdown",
    "heading" => __be("Background position"),
    "param_name" => "bg_position",
    "value" => array(
        __be("Top left")        => "tl",
        __be("Top center")      => "tc",
        __be("Top right")       => "tr",
        __be("Middle left")     => "cl",
        __be("Middle center")   => "cc",
        __be("Middle right")    => "cr",
        __be("Bottom left")     => "bl",
        __be("Bottom center")   => "bc",
        __be("Bottom right")    => "br",
    ),
    "dependency" => array(
        "element" => "bg_image",
        "not_empty" => true
    )
));

//background repeat
vc_add_param("vc_row", array(
    "type" => "dropdown",
    "heading" => __be("Background repeat"),
    "param_name" => "bg_repeat",
    "value" => array(
        __be("No repeat")           => "no-repeat",
        __be("Repeat in both axes") => "repeat",
        __be("Repeat X axe")        => "repeat-x",
        __be("Repeat Y axe")        => "repeat-y"
    ),
    "dependency" => array(
        "element" => "bg_image",
        "not_empty" => true
    )
));

//background cover
vc_add_param("vc_row", array(
    "type" => "checkbox",
    "heading" => __be("Cover background with image?"),
    "param_name" => "bg_cover",
    "value" => Array(__("Yes, please", "js_composer") => true),
    "description" => __be("This is good if you use single not repeated image for background. If you want to use repeated pattern then uncheck this."),
    "dependency" => array(
        "element" => "bg_image",
        "not_empty" => true
    )
));

//Parallax
vc_add_param("vc_row", array(
    "type" => "checkbox",
    "heading" => __be("Enable parallax?"),
    "param_name" => "parallax",
    "value" => Array(__("Yes, please", "js_composer") => true),
    "dependency" => array(
        "element" => "bg_image",
        "not_empty" => true
    ),
));

//Parallax speed
vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => __be("Parallax speed"),
    "param_name" => "parallax_speed",
    "value" => "0.5",
    "description" => __be("Interesting values are in range of 0.01-0.99."),
    "dependency" => array(
        "element" => "bg_image",
        "not_empty" => true
    ),
    "dependency" => array(
        "element" => "parallax",
        "not_empty" => true
    ),
));

//padding top bottom
vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => __be("Padding"),
    "param_name" => "padding",
    "value" => "",
    "description" => __be("It will add padding in top and bottom. Value in pixels."),
));

//padding left right
vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => __be("Side padding"),
    "param_name" => "side_padding",
    "value" => "",
    "description" => __be("It will add padding in left and right. Value in pixels."),
));

//bottom margin
vc_add_param("vc_row", array(
    "type" => "textfield",
    "heading" => __be('Bottom margin'),
    "param_name" => "margin_bottom",
    "value" => "",
    "description" => __be("Value in pixels."),
));


//add css animation
vc_add_param("vc_row", $add_css_animation);

//move row_id to end of params
$param = WPBMap::getParam('vc_row', 'el_id');
vc_remove_param('vc_row', 'el_id');
vc_add_param('vc_row', $param);

//move extra class to end of params
$param = WPBMap::getParam('vc_row', 'el_class');
vc_remove_param('vc_row', 'el_class');
vc_add_param('vc_row', $param);



/* Separator with Text EXTENSIONS
---------------------------------------------------------- */
vc_remove_param("vc_text_separator","color"); /* builder new param */
vc_remove_param("vc_text_separator","accent_color"); /* builder new param */
vc_remove_param("vc_text_separator","style"); /* builder new param */
vc_remove_param("vc_text_separator","el_width"); /* builder new param */

vc_add_param("vc_text_separator", array(
    "type" => "dropdown",
    "heading" => __("Title size", "a13_shortcodes"),
    "param_name" => "title_size",
    "value" => array(
        'h1' => 'h1',
        'h2' => 'h2',
        'h3' => 'h3',
        'h4' => 'h4',
        'h5' => 'h5',
        'h6' => 'h6',
    ),
    "description" => __("Select size", "a13_shortcodes"),
));
vc_add_param("vc_text_separator", array(
    "type" => 'checkbox',
    "heading" => __("Bold?", "a13_shortcodes"),
    "param_name" => "bold",
    "value" => Array(__("Yes, please", "js_composer") => true)
));
vc_add_param("vc_text_separator", array(
    "type" => "dropdown",
    "heading" => __("Line type", "a13_shortcodes"),
    "param_name" => "type",
    "value" => array(
        __("Single line", "a13_shortcodes") => 'single_line',
        __("Double line", "a13_shortcodes") => 'double_line',
        __("Single dots", "a13_shortcodes") => 'single_dots',
        __("Double dots", "a13_shortcodes") => 'double_dots',
    ),
    "description" => '',
));




/* Hello text
---------------------------------------------------------- */
vc_map( array(
    "name" => __("Hello text", "a13_shortcodes"),
    "base" => "a13_hellotext",
    "icon" => "icon-wpb-layer-shape-text",
    "category" => __('Content', 'js_composer'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", "js_composer"),
            "param_name" => "upper_text",
            "holder" => "div",
            "value" => __("Title", "js_composer"),
            "description" => __("Title", "js_composer")
        ),
        array(
            "type" => "textfield",
            "heading" => __("Subtitle", "a13_shortcodes"),
            "param_name" => "lower_text",
            "holder" => "div",
            "value" => __("Subtitle", "a13_shortcodes"),
            "description" => __("Subtitle", "a13_shortcodes"),
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Texts position", "a13_shortcodes"),
            "param_name" => "title_align",
            "value" => $align_arr,
            "description" => '',
            "admin_label" => true,
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Texts size", "a13_shortcodes"),
            "param_name" => "title_size",
            "value" => array(__('Big', "a13_shortcodes") => "size_big", __('Medium', "a13_shortcodes") => "size_medium", __('Small', "a13_shortcodes") => "size_small"),
            "description" => '',
            "admin_label" => true,
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    ),
) );


/* Heading with color
---------------------------------------------------------- */
vc_map( array(
    "name" => __("Heading with color", "a13_shortcodes"),
    "base" => "a13_heading_color",
    "icon" => "icon-wpb-layer-shape-text",
    "category" => __('Content', 'js_composer'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", "js_composer"),
            "param_name" => "title",
            "holder" => "div",
            "value" => __("Title", "js_composer"),
            "description" => ''
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Title size", "a13_shortcodes"),
            "param_name" => "title_size",
            "value" => array(
                'h1' => 'h1',
                'h2' => 'h2',
                'h3' => 'h3',
                'h4' => 'h4',
                'h5' => 'h5',
                'h6' => 'h6',
            ),
            "description" => __("Select size", "a13_shortcodes"),
        ),
        array(
            "type" => 'checkbox',
            "heading" => __("Bold?", "a13_shortcodes"),
            "param_name" => "bold",
            "value" => Array(__("Yes, please", "js_composer") => true)
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Title color", "a13_shortcodes"),
            "param_name" => "text_color",
            "description" => __("Select custom text color.", "a13_shortcodes"),
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Title background color", "a13_shortcodes"),
            "param_name" => "bg_color",
            "description" => __("Select custom background color", "a13_shortcodes"),
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Text position", "a13_shortcodes"),
            "param_name" => "title_align",
            "value" => $align_arr,
            "description" => '',
            "admin_label" => true,
        ),
    ),
) );


/* Title with icon
---------------------------------------------------------- */
vc_map( array(
    "name" => __("Title with icon", "js_composer"),
    "base" => "a13_title_with_icon",
    "icon" => "icon-wpb-layer-shape-text",
    "category" => __('Content', 'js_composer'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", "js_composer"),
            "param_name" => "title",
            "holder" => "div",
            "value" => __("Title", "js_composer"),
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Title size", "a13_shortcodes"),
            "param_name" => "title_size",
            "value" => array(
                'h1' => 'h1',
                'h2' => 'h2',
                'h3' => 'h3',
                'h4' => 'h4',
                'h5' => 'h5',
                'h6' => 'h6',
            ),
            "description" => __("Select size", "a13_shortcodes"),
        ),
        array(
            "type" => 'checkbox',
            "heading" => __("Bold?", "a13_shortcodes"),
            "param_name" => "bold",
            "value" => Array(__("Yes, please", "js_composer") => true)
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Title color", "a13_shortcodes"),
            "param_name" => "text_color",
            "description" => __("Select custom text color.", "a13_shortcodes"),
        ),
        array(
            "type" => "textfield",
            "heading" => __("Icon", "js_composer"),
            "param_name" => "icon",
            "value" => '',
            "description" => __('You can see available icons at <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>. You have to use code of icon that looks like <code>fa-coffee</code>.', "a13_shortcodes"),
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Text position", "a13_shortcodes"),
            "param_name" => "title_align",
            "value" => $align_arr_v2,
            "description" => '',
            "admin_label" => true,
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    ),
) );


/* Call to Action Button
---------------------------------------------------------- */

vc_map( array(
    "name" => __("Theme Call to Action Button", "js_composer"),
    "base" => "a13_cta_button",
    "icon" => "icon-wpb-call-to-action",
    "category" => __('Content', 'js_composer'),
    "params" => array(
        array(
            "type" => "textarea",
            'admin_label' => true,
            "heading" => __("Text", "js_composer"),
            "param_name" => "call_text",
            "value" => __("Click edit button to change this text.", "js_composer"),
            "description" => __("Enter your content.", "js_composer")
        ),
        array(
            "type" => "textarea",
            'admin_label' => true,
            "heading" => __("Sub text", "a13_shortcodes"),
            "param_name" => "call_sub_text",
            "value" => '',
            "description" => __("Enter your content.", "js_composer")
        ),
        array(
            "type" => "textfield",
            "heading" => __("Text on the button", "js_composer"),
            "param_name" => "title",
            "value" => __("Text on the button", "js_composer"),
            "description" => __("Text on the button.", "js_composer")
        ),
        array(
            "type" => "textfield",
            "heading" => __("URL (Link)", "js_composer"),
            "param_name" => "href",
            "description" => __("Button link.", "js_composer")
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Target", "js_composer"),
            "param_name" => "target",
            "value" => $target_arr,
            "dependency" => Array('element' => "href", 'not_empty' => true)
        ),
        array(
            "type" => "textfield",
            "heading" => __("Icon", "js_composer"),
            "param_name" => "icon",
            "value" => '',
            "description" => __('You can see available icons at <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>. You have to use code of icon that looks like <code>fa-coffee</code>.', "a13_shortcodes"),
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    ),
    "js_view" => 'VcCallToActionView'
) );


/* Progress bar
---------------------------------------------------------- */
vc_map( array(
    "name" => __("Theme Progress Bar", "js_composer"),
    "base" => "a13_progress_bar",
    "icon" => "icon-wpb-graph",
    "description" => __('Animated progress bar', 'js_composer'),
    "category" => __('Content', 'js_composer'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Widget title", "js_composer"),
            "param_name" => "title",
            "description" => __("What text use as a widget title. Leave blank if no title is needed.", "js_composer")
        ),
        array(
            "type" => "exploded_textarea",
            "heading" => __("Graphic values", "js_composer"),
            "param_name" => "values",
            "description" => __('Input graph values here. Divide values with linebreaks (Enter). Example: <code>90|Development|black</code>. Colors to use are: <code>grey, blue, turquoise, green, orange, red, black</code>', "a13_shortcodes"),
            "value" => "90|Development|black,80|Design|turquoise,70|Marketing"
        ),
        array(
            "type" => "textfield",
            "heading" => __("Units", "js_composer"),
            "param_name" => "units",
            "description" => __("Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.", "js_composer")
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Bar color", "js_composer"),
            "param_name" => "bgcolor",
            "value" => array(__("Grey", "js_composer") => "bar_grey", __("Blue", "js_composer") => "bar_blue", __("Turquoise", "js_composer") => "bar_turquoise", __("Green", "js_composer") => "bar_green", __("Orange", "js_composer") => "bar_orange", __("Red", "js_composer") => "bar_red", __("Black", "js_composer") => "bar_black"),
            "description" => __("Select bar background color.", "js_composer"),
            "admin_label" => true
        ),
        array(
            "type" => "checkbox",
            "heading" => __("Options", "js_composer"),
            "param_name" => "options",
            "value" => array(__("Add Stripes?", "js_composer") => "striped", __("Add animation? Will be visible with striped bars.", "js_composer") => "animated")
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    )
) );


/* Separator (Divider)
---------------------------------------------------------- */
vc_map( array(
    "name"		=> __("Theme Separator", "js_composer"),
    "base"		=> "a13_separator",
    'icon'		=> 'icon-wpb-ui-separator',
    "description" => __('Horizontal separator line', 'js_composer'),
    "category"  => __('Content', 'js_composer'),
    "params" => array(
        array(
            "type" => "dropdown",
            "heading" => __("Line position", "a13_shortcodes"),
            "param_name" => "position",
            "value" => array_merge(array(__("Full width", "a13_shortcodes") => 'full_width'),$align_arr),
            "description" => ''
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Line type", "a13_shortcodes"),
            "param_name" => "type",
            "value" => array(
                __("Single line", "a13_shortcodes") => 'single_line',
                __("Double line", "a13_shortcodes") => 'double_line',
                __("Single dots", "a13_shortcodes") => 'single_dots',
                __("Double dots", "a13_shortcodes") => 'double_dots',
                __("Empty Line", "a13_shortcodes") => 'empty',
            ),
            "description" => '',
            "admin_label" => true,
        ),
        array(
            "type" => "textfield",
            "heading" => __("Margin top", "a13_shortcodes"),
            "param_name" => "margin_top",
            "value" => "",
            "description" => __("It will add margin in top. Type number. Value in pixels.", "a13_shortcodes"),
        ),
        array(
            "type" => "textfield",
            "heading" => __("Margin bottom", "a13_shortcodes"),
            "param_name" => "margin_bottom",
            "value" => "",
            "description" => __("It will add margin at bottom. Type number. Value in pixels.", "a13_shortcodes"),
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    ),
) );


/* Button
---------------------------------------------------------- */
vc_map( array(
    "name" => __("Theme Button", "js_composer"),
    "base" => "a13_button",
    "icon" => "icon-wpb-ui-button",
    "category" => __('Content', 'js_composer'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Text on the button", "js_composer"),
            "holder" => "button",
            "param_name" => "title",
            "value" => __("Text on the button", "js_composer"),
            "description" => __("Text on the button.", "js_composer")
        ),
        array(
            "type" => "textfield",
            "heading" => __("URL (Link)", "js_composer"),
            "param_name" => "href",
            "description" => __("Button link.", "js_composer")
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Target", "js_composer"),
            "param_name" => "target",
            "value" => $target_arr,
            "dependency" => Array('element' => "href", 'not_empty' => true)
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Title color", "a13_shortcodes"),
            "param_name" => "text_color",
            "value" => '#333333',
            "description" => __("Select custom text color.", "a13_shortcodes"),
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Background color", "a13_shortcodes"),
            "param_name" => "bg_color",
            "value" => '#ffffff',
            "description" => __("Select custom background color", "a13_shortcodes"),
        ),
        array(
            "type" => "colorpicker",
            "heading" => __("Border color", "a13_shortcodes"),
            "param_name" => "border_color",
            "value" => '',
            "description" => __("Select custom border color", "a13_shortcodes"),
        ),
        array(
            "type" => "textfield",
            "heading" => __("Icon", "js_composer"),
            "param_name" => "icon",
            "value" => '',
            "description" => __('You can see available icons at <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>. You have to use code of icon that looks like <code>fa-coffee</code>.', "a13_shortcodes"),
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Size", "js_composer"),
            "param_name" => "size",
            "value" => $size_arr = array(__("Regular size", "js_composer") => "a13_regularsize", __("Large", "js_composer") => "a13_largesize", __("Small", "js_composer") => "a13_smallsize"),
            "description" => __("Button size.", "js_composer")
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Button position", "a13_shortcodes"),
            "param_name" => "align",
            "value" => $align_arr_v2,
            "description" => '',
            "admin_label" => true,
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
        )
    ),
    "js_view" => 'VcButtonView'
) );


/* Testimonials by WooThemes
---------------------------------------------------------- */
if (is_plugin_active('testimonials-by-woothemes/woothemes-testimonials.php')) {
    vc_map( array(
        "name" => __("Testimonials", "js_composer"),
        "base" => "woothemes_testimonials",
        "icon" => "icon-wpb-images-stack",
        "category" => __('Content', 'js_composer'),
        "params" => array(
            array(
                "type" => "textfield",
                "heading" => __("Title", "a13_shortcodes"),
                "param_name" => "title",
                "value" => '',
                "description" => __("an optional title", "a13_shortcodes")
            ),
            array(
                "type" => "textfield",
                "heading" => __("Limit", "a13_shortcodes"),
                "param_name" => "limit",
                "value" => 5,
                "description" => __("the maximum number of items to display", "a13_shortcodes"),
                "admin_label" => true,
            ),
            array(
                "type" => "dropdown",
                "heading" => __("Per row", "a13_shortcodes"),
                "param_name" => "per_row",
                "value" => array(1,2,3),
                "description" => __("when creating rows, how many items display in a single row?", "a13_shortcodes"),
                "admin_label" => true,
            ),
            array(
                "type" => "dropdown",
                "heading" => __("Order by", "a13_shortcodes"),
                "param_name" => "orderby",
                "value" => array(
                    __("Menu order", "a13_shortcodes") => 'menu_order',
                    __("ID", "a13_shortcodes") => 'ID',
                    __("title", "a13_shortcodes") => 'title',
                    __("date", "a13_shortcodes") => 'date',
                    __("none", "a13_shortcodes") => 'none',
                    ),
                "description" => __("how to order the items - accepts all default WordPress ordering options", "a13_shortcodes")
            ),
            array(
                "type" => "dropdown",
                "heading" => __("Order", "a13_shortcodes"),
                "param_name" => "order",
                "value" => array(
                    __("descending", "a13_shortcodes") => 'DESC',
                    __("ascending", "a13_shortcodes") => 'ASC',
                    ),
                "description" => __("the order direction", "a13_shortcodes")
            ),
            array(
                "type" => "dropdown",
                "heading" => __("Display author", "a13_shortcodes"),
                "param_name" => "display_author",
                "value" => array(
                    __("True", "a13_shortcodes") => 'true',
                    __("False", "a13_shortcodes") => 'false',
                    ),
                "description" => __("whether or not to display the author information", "a13_shortcodes")
            ),
            array(
                "type" => "dropdown",
                "heading" => __("Display avatar", "a13_shortcodes"),
                "param_name" => "display_avatar",
                "value" => array(
                    __("True", "a13_shortcodes") => 'true',
                    __("False", "a13_shortcodes") => 'false',
                    ),
                "description" => __("whether or not to display the author avatar", "a13_shortcodes")
            ),
            array(
                "type" => "dropdown",
                "heading" => __("Display URL", "a13_shortcodes"),
                "param_name" => "display_url",
                "value" => array(
                    __("True", "a13_shortcodes") => 'true',
                    __("False", "a13_shortcodes") => 'false',
                    ),
                "description" => __("whether or not to display the URL information", "a13_shortcodes")
            ),
            array(
                "type" => "textfield",
                "heading" => __("ID", "a13_shortcodes"),
                "param_name" => "id",
                "value" => '0',
                "description" => __("display a specific item", "a13_shortcodes")
            ),
            array(
                "type" => "textfield",
                "heading" => __("Category", "a13_shortcodes"),
                "param_name" => "category",
                "value" => '0',
                "description" => __("the ID/slug of the category to filter by", "a13_shortcodes")
            ),
        ),
    ) );
}


/* Pricing tables via Go - Responsive Pricing & Compare Tables
---------------------------------------------------------- */
if (is_plugin_active('go_pricing/go_pricing.php')) {

    $tables = array();

    if(class_exists('GW_GoPricing_Data')){
        $pricing_tables = GW_GoPricing_Data::get_table_posts();
        if ( $pricing_tables !== false && sizeof($pricing_tables) ) {
            foreach ( (array) $pricing_tables as $table ) {
                $tables[ $table->post_title ] = $table->post_excerpt;
            }
        }
    }

    vc_map( array(
        "name" => __("Go pricing tables", "js_composer"),
        "base" => "go_pricing",
        "icon" => "icon-go-pricing",
        "category" => __('Content', 'js_composer'),
        "params" => array(
            array(
                "type" => "dropdown",
                "heading" => __("Table", "a13_shortcodes"),
                "param_name" => "id",
                "value" => $tables,
                "description" => __("Select pricing table to display", "a13_shortcodes"),
                "admin_label" => true,
            ),
            array(
                "type" => "textfield",
                "heading" => __("Margin bottom", "a13_shortcodes"),
                "param_name" => "margin_bottom",
                "value" => '20px',
                "description" => __("It will add margin at bottom. Value can be any valid CSS unit.", "a13_shortcodes"),
                "admin_label" => true,
            ),
        ),
    ) );
}