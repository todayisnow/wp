<a href="#" class="userpro-close-popup"><?php _e('Close','userpro'); ?></a>
<div class="userpro userpro-<?php echo $i; ?> userpro-<?php echo $layout; ?>" <?php userpro_args_to_data( $args ); ?>>
	
	<div class="userpro-head">

		<div class="userpro-left"><?php echo $args["{$template}_heading"]; ?></div>
		<?php if ($args["{$template}_side"]) { ?>
		<div class="userpro-right"><a href="#" data-template="<?php echo $args["{$template}_side_action"]; ?>"><?php echo $args["{$template}_side"]; ?></a></div>
		<?php } ?>
		<div class="userpro-clear"></div>
	</div>
	
	<div class="userpro-body">
	<?php   if(isset($args["form_role"]))
		 $_SESSION['form_role']=$args["form_role"];
		else
		 $_SESSION['form_role']='';
		?>
		<?php do_action('userpro_pre_form_message'); ?>

		<form action="" method="post" data-action="<?php echo $template; ?>">
		
			<input type="hidden" name="redirect_uri-<?php echo $i; ?>" id="redirect_uri-<?php echo $i; ?>" value="<?php if (isset( $args["{$template}_redirect"] ) ) echo $args["{$template}_redirect"]; ?>" />
			
			<?php // Hook into fields $args, $user_id
			if (!isset($user_id)) $user_id = 0;
			$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
			do_action('userpro_before_fields', $hook_args);
			do_action('userpro_inside_form_register');
			?>
			
			<?php
			// Multiple Registration Forms Support
			if (isset($args['type']) && $userpro->multi_type_exists($args['type'])) {
				$group = array_intersect_key( userpro_fields_group_by_template( $template, $args["{$template}_group"] ), array_flip($userpro->multi_type_get_array($args['type'])) );
			} else {
				$group = userpro_fields_group_by_template( $template, $args["{$template}_group"] );
			}
			?>
		
			<?php foreach( $group as $key => $array ) { ?>
				
				<?php  if ($array) echo userpro_edit_field( $key, $array, $i, $args ) ?>
				
			<?php } ?>
			
			<?php // Hook into fields $args, $user_id
			if (!isset($user_id)) $user_id = 0;
			$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
			do_action('userpro_after_fields', $hook_args);
			?>
						
			<?php // Hook into fields $args, $user_id
			if (!isset($user_id)) $user_id = 0;
			$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
			do_action('userpro_before_form_submit', $hook_args);
			?>
			
			<?php if ($args["{$template}_button_primary"] ||  $args["{$template}_button_secondary"] ) { ?>
			<div class="userpro-field userpro-submit userpro-column">
			
				<?php // Hook into fields $args, $user_id
				if (!isset($user_id)) $user_id = 0;
				$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
				if(userpro_get_option('sociallogin')=='1')				
				do_action('userpro_inside_form_submit', $hook_args);
				?>
				
				<?php if ($args["{$template}_button_primary"]) { ?>
				<input type="submit" value="<?php echo $args["{$template}_button_primary"]; ?>" class="userpro-button" />
				<?php } ?>
				
				<?php if ($args["{$template}_button_secondary"]) { ?>
				<input type="button" value="<?php echo $args["{$template}_button_secondary"]; ?>" class="userpro-button secondary" data-template="<?php echo $args["{$template}_button_action"]; ?>" />
				<?php } ?>

				<img src="<?php echo $userpro->skin_url(); ?>loading.gif" alt="" class="userpro-loading" />
				<div class="userpro-clear"></div>
				
			</div>
			<?php } ?>
			<div id="myHidden" style="display:none;">

		<div class='userpro-field  userpro-field-first_name ' data-key='first_name'><div class='userpro-label iconed'><label for='first_name-9999'>First Name</label><div class='required'>*</div></div><div class='userpro-input'><input type='text' name='first_name-9999' id='first_name-9999' value="" placeholder=''  data-ajaxcheck='' data-help='' data-label='First Name' data-placeholder='' data-add_condition='show' data-condition_fields='user_login' data-condition_rule='select' data-condition_value='' data-_builtin='1' data-hidden='0' data-hideable='0' data-html='0' data-locked='0' data-private='0' data-required='1' data-type='text' data-woo='0' /><div class='userpro-clear'></div></div></div><div class='userpro-clear'></div>								
		<div class='userpro-field  userpro-field-last_name ' data-key='last_name'><div class='userpro-label iconed'><label for='last_name-9999'>Last Name</label><div class='required'>*</div></div><div class='userpro-input'><input type='text' name='last_name-9999' id='last_name-9999' value="" placeholder=''  data-ajaxcheck='' data-help='' data-label='Last Name' data-placeholder='' data-add_condition='show' data-condition_fields='user_login' data-condition_rule='select' data-condition_value='' data-_builtin='1' data-hidden='0' data-hideable='0' data-html='0' data-locked='0' data-private='0' data-required='1' data-type='text' data-woo='0' /><div class='userpro-clear'></div></div></div><div class='userpro-clear'></div>				
					</div>
		</form>
	
	</div>

</div>
<?php
/*
		#Dev
		#Todayisnow
		#201704120150
		#profile answers and questions
		*/
		?>
<script>
window.onload = function(e){
	var num = jQuery("[id^=user_email]").attr('id').split('-')[1]
	jQuery("#myHidden").html(jQuery("#myHidden").html().replace(new RegExp('9999', 'g'), num));

}

jQuery("form").submit(function(){
	jQuery('[id^=first_name]').val(jQuery("[id^=user_login]").val());
	jQuery('[id^=last_name]').val(Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000)

});
</script>