<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.itpathsolutions.com
 * @since      1.0.0
 *
 * @package    Wpform_To_Any_Api
 * @subpackage Wpform_To_Any_Api/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$wpformapi_object = new Wpform_To_Any_Api();
$wpformapi_options = $wpformapi_object->wpform_to_any_api_get_options();


$selected_form 			= (empty($wpformapi_options['wpformapi_selected_form']) ? '' : $wpformapi_options['wpformapi_selected_form']);
$wpformapi_base_url 	= (empty($wpformapi_options['wpformapi_base_url']) ? '' : $wpformapi_options['wpformapi_base_url']);
$wpformapi_input_type 	= (empty($wpformapi_options['wpformapi_input_type']) ? '' : $wpformapi_options['wpformapi_input_type']);
$wpformapi_method 		= (empty($wpformapi_options['wpformapi_method']) ? '' : $wpformapi_options['wpformapi_method']);
$wpformapi_form_field 	= (empty($wpformapi_options['wpformapi_form_field']) ? '' : $wpformapi_options['wpformapi_form_field']);
$wpformapi_header_request = (empty($wpformapi_options['wpformapi_header_request']) ? '' : $wpformapi_options['wpformapi_header_request']);

if(!class_exists('WPCF7_ContactForm')){	?>

	<div id="wpformapi_admin" class="wpformapi_wrap">
		<p><?php esc_html_e( 'WPForm api integrations requires WPForm Plugin to be installed and active', 'connect-wpform-to-any-api' ); ?></p>
	</div>
	<?php

} else{

	if(!empty($selected_form)){

		$form_field = $wpformapi_object->wpform_to_any_api_default_form_field($selected_form);

		if($form_field['status'] == 404){ ?>
			
			<div id="wpformapi_admin" class="wpformapi_wrap">
				<p><?php esc_html_e( 'Your Selected WPForm was not found Please try to add new data in this API', 'connect-wpform-to-any-api' ); ?></p>
			</div>			
			<?php
			$selected_form = '';
			$wpformapi_base_url = '';
			$wpformapi_input_type = '';
			$wpformapi_method = '';
			$wpformapi_form_field = '';
			$wpformapi_header_request = '';
		}
	} ?>

	<div id="wpformapi_admin" class="wpformapi_wrap">

		<div class="wpformapi_field">
			<?php wp_nonce_field('wpform_to_any_api_cpt_nonce','wpform_to_any_api_cpt_nonce' ); ?>
			<label for="wpformapi_selected_form"><?php esc_html_e( 'Select WPForm', 'connect-wpform-to-any-api' ); ?></label>
			<select name="wpformapi_selected_form" id="wpformapi_selected_form" required> 
				<option value=""><?php esc_html_e( 'Select WPForm', 'connect-wpform-to-any-api' ); ?></option>
				<?php
				$posts = get_posts(
					array(
						'post_type'     => 'wpforms',
						'numberposts'   => -1
					)
				);
				foreach($posts as $post){
					?>
					<option value="<?php echo esc_html($post->ID); ?>" <?php echo ($post->ID == $selected_form ? esc_html('selected="selected"') : ''); ?> ><?php echo esc_html($post->post_title.' ('.$post->ID.') '); ?> </option>
					<?php
				} ?>
			</select>
		</div>

		<div class="wpformapi_field">
			<label for="wpformapi_base_url"><?php esc_html_e( 'API url', 'connect-wpform-to-any-api' ); ?></label>
			<input type="text" id="wpformapi_base_url" name="wpformapi_base_url" value="<?php echo esc_url($wpformapi_base_url); ?>" placeholder="<?php esc_attr_e( 'Enter Your API URL', 'connect-wpform-to-any-api' ); ?>" required>
		</div>

		<div class="wpformapi_full_width">
			<label for="wpformapi_header_request"><?php esc_html_e( 'Header Request', 'connect-wpform-to-any-api' ); ?></label>

			<textarea id="wpformapi_header_request" name="wpformapi_header_request" placeholder="<?php esc_attr_e( 'Authorization: MY_API_KEY 
Authorization : Bearer xxxxxxx
Authorization : Basic xxxxxx
Content-Type: application/json

All your header Parameters set here.', 'connect-wpform-to-any-api' ); ?>"><?php echo esc_textarea($wpformapi_header_request); ?></textarea>
		</div>

		<div class="wpformapi_field">
			<label for="wpformapi_input_type"><?php esc_html_e( 'Input type', 'connect-wpform-to-any-api' ); ?></label>
			<select id="wpformapi_input_type" name="wpformapi_input_type" required>
				<option value="params" <?php echo ($wpformapi_input_type == 'params' ? esc_html('selected="selected"') : ''); ?>><?php esc_html_e( 'Parameters - GET/POST', 'connect-wpform-to-any-api' ); ?></option>
				<option value="json" <?php echo ($wpformapi_input_type == 'json' || $wpformapi_input_type == '' ? esc_html('selected="selected"') : ''); ?>><?php esc_html_e( 'JSON', 'connect-wpform-to-any-api' ); ?></option>
			</select>
		</div>

		<div class="wpformapi_field">
			<label for="wpformapi_method"><?php esc_html_e( 'Method', 'connect-wpform-to-any-api' ); ?></label>
			<select id="wpformapi_method" name="wpformapi_method" required>
				<option value=""><?php esc_html_e( 'Select Method', 'connect-wpform-to-any-api' ); ?></option>
				<option value="GET" <?php echo ($wpformapi_method == 'GET' ? esc_html('selected="selected"') : ''); ?>>GET</option>
				<option value="POST" <?php echo ($wpformapi_method == 'POST' || $wpformapi_method == '' ? esc_html('selected="selected"') : ''); ?>>POST</option>
			</select>
		</div>

	</div>

	<div class="wpformapi-form-mapping-fields">
		<h3><?php esc_html_e( 'Map your Fields', 'connect-wpform-to-any-api' ); ?></h3>
		<hr>
		<div id="wpformapi-form-fields" class="form-fields">        
			<?php
			if($wpformapi_form_field){
				foreach($wpformapi_form_field as $key => $wpform_field_value){
					?>
					<div class="wpformapi_field">
						<label for="wpformapi_<?php echo esc_html($key); ?>"><?php echo esc_html($wpform_field_value['label']); ?></label>
						<input type="text" id="wpformapi_<?php echo esc_html($key); ?>" name="wpformapi_form_field[<?php echo esc_html($key); ?>]" value="<?php echo esc_html($wpform_field_value['value']); ?>" placeholder="<?php esc_attr_e( 'Enter your API side mapping key', 'connect-wpform-to-any-api' ); ?>"> 
					</div>
					<?php
				}
			} ?>
		</div>
	</div><?php 
} 