<?php

/*
 * Making cover for galleries in Galleries list
 */
if(!function_exists('a13_make_gallery_image')){
    function a13_make_gallery_image( $gallery_id, $thumb_size, $hidden = false ){
        if(empty($gallery_id)){
            $gallery_id = get_the_ID();
        }
        if ( has_post_thumbnail($gallery_id) ) {
            $src = wp_get_attachment_image_src( get_post_thumbnail_id ( $gallery_id ), $thumb_size );
            $src = $src[0];
        }
        else{
            $src = A13_TPL_GFX . '/holders/photo.jpg';
        }

        $style = '';
        if($hidden){
            $style = ' style="visibility: hidden;"';
        }

        return '<img src="'.esc_url($src).'" alt=""'.$style.' />';
    }
}
