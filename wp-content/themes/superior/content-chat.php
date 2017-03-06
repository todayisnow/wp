<?php
global $apollo13;

echo '<h2 class="post-title"><a href="'. esc_url(get_permalink()) . '">' . get_the_title() . '</a></h2>';
?>

<div class="real-content">

    <?php echo a13_daoon_chat_post($post->post_content);?>

    <div class="clear"></div>
</div>