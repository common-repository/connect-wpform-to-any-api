<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.itpathsolutions.com
 * @since      1.0.0
 *
 * @package    Wpform_To_Any_Api
 * @subpackage Wpform_To_Any_Api/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpform_To_Any_Api
 * @subpackage Wpform_To_Any_Api/admin
 * @author     IT Path Solutions <support@contactformtoapi.com>
 */
class Wpform_To_Any_Api_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpform_To_Any_Api_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpform_To_Any_Api_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpform-to-any-api-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpform_To_Any_Api_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpform_To_Any_Api_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpform-to-any-api-admin.js', array( 'jquery' ), $this->version, false );

		$data = array( 'site_url' => site_url(), 'ajax_url' => admin_url('admin-ajax.php') );
	    wp_localize_script($this->plugin_name, 'ajax_object', $data);

	}

	/**
	 * Check Plugin Dependencies
	 *
	 * @since    1.0.0
	 */
	public function wpform_to_any_api_verify_dependencies(){
		
		if(is_multisite()){
			
			if(!is_plugin_active_for_network('wpforms-lite/wpforms.php') && !is_plugin_active('wpforms-lite/wpforms.php')){
				echo '<div class="notice notice-warning is-dismissible">
	            	 <p>'.esc_html__( 'WPForm API integrations require the WPForms Plugin to be installed and active.', 'connect-wpform-to-any-api' ).'</p>
	         	</div>';
			}
		
		}else{
			
			if(!is_plugin_active('wpforms-lite/wpforms.php')){
      			echo '<div class="notice notice-warning is-dismissible">
	            	 <p>'.esc_html__( 'WPForm API integrations require the WPForms Plugin to be installed and active.', 'connect-wpform-to-any-api' ).'</p>
	         	</div>';
    		}
    	}
	}

	/**
	 * Register the Submenu
	 *
	 * @since    1.0.0
	 */
	public function wpform_to_any_api_register_submenu(){
	    add_submenu_page(
	        'edit.php?post_type=wpform_api',
	        __('Logs', 'connect-wpform-to-any-api'),
	        __('Logs', 'connect-wpform-to-any-api'),
	        'manage_options',
	        'wpformapi_logs',
	        array(&$this,'wpformapi_logs_callback')
	    );
	}


	/**
	 * Plugin page added setting and document option
	 *
	 * @since 
	 * */
	public function wpform_to_any_api_add_settings_link( $links, $file ){

		if($file === 'wpform-to-any-api/wpform-to-any-api.php' && current_user_can('manage_options')){

			$settings_url 	= admin_url('edit.php?post_type=wpform_api');
			$documentation 	= admin_url('edit.php?post_type=wpform_api&page=wpformapi_docs');
			$links = (array) $links;
			$links[] = sprintf('<a href="%s">%s</a>', $settings_url, __('Settings','connect-wpform-to-any-api'));
		}

		return $links;
	}

	/**
	 * Register the Custom Post Type
	 *
	 * @since    1.0.0
	 */
	public function wpform_to_any_api_custom_post_type(){

		$labels = array(
			'name' => _x('WPForm to Any API', 'plural', 'connect-wpform-to-any-api'),
			'singular_name' => _x('WPForm to api', 'singular', 'connect-wpform-to-any-api'),
			'menu_name' => _x('WPForm to API', 'admin menu', 'connect-wpform-to-any-api'),
			'name_admin_bar' => _x('WPForm to API', 'admin bar', 'connect-wpform-to-any-api'),
			'add_new' => _x('Add New WPForm API', 'add new', 'connect-wpform-to-any-api'),
			'add_new_item' => __('Add New WPForm API', 'connect-wpform-to-any-api'),
			'new_item' => __('New WPForm API', 'connect-wpform-to-any-api'),
			'edit_item' => __('Edit WPForm to Any API', 'connect-wpform-to-any-api'),
			'view_item' => __('View WPForm to Any API', 'connect-wpform-to-any-api'),
			'all_items' => __('All WPForm API', 'connect-wpform-to-any-api'),
			'not_found' => __('No WPForm API found.', 'connect-wpform-to-any-api'),
			'register_meta_box_cb' => 'aps_metabox',
		);
		$args = array(
			'labels' => $labels,
			'supports' =>  array('title'),
			'hierarchical' => false,
			'public' => false,
			'publicly_queryable' => false, 
			'show_ui' => true, 
			'exclude_from_search' => true,
			'show_in_nav_menus' => false,
			'has_archive' => false, 
			'rewrite' => false,
			'menu_icon' => 'dashicons-rest-api',
		);
		
		register_post_type('wpform_api', $args);
	    flush_rewrite_rules();
	}

	/**
	 * Register the Custom Meta Boxes
	 *
	 * @since    1.0.0
	 */
	public function wpform_to_any_api_metabox(){
	    add_meta_box(
	        'wpformapi-setting',
	        __( 'WPForm Api Setting', 'connect-wpform-to-any-api' ),
	        array($this,'wpform_to_any_api_settings'),
	        'wpform_api'
	    );
	}

	/**
	 * Registered Metaboxes Fields
	 *
	 * @since    1.0.0
	 */
	public static function wpform_to_any_api_settings() {
		include dirname(__FILE__).'/partials/wpform-to-any-api-admin-display.php';
	}

	/**
	 * Save the Metaboxes value on Post Save
	 *
	 * @since    1.0.0
	 */
	public static function wpform_to_any_api_save_settings( $post_id ,$post ){

		if($post->post_type == 'wpform_api'){

			$status = 'false';

			if(isset($_POST['wpform_to_any_api_cpt_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['wpform_to_any_api_cpt_nonce']), 'wpform_to_any_api_cpt_nonce')){

				$options['wpformapi_selected_form'] = (int) stripslashes(sanitize_text_field($_POST['wpformapi_selected_form']));
				$options['wpformapi_base_url'] = esc_url_raw(sanitize_text_field($_POST['wpformapi_base_url']));
				$options['wpformapi_header_request'] = sanitize_textarea_field($_POST['wpformapi_header_request']);
				$options['wpformapi_input_type'] = sanitize_text_field($_POST['wpformapi_input_type']);
				$options['wpformapi_method'] = sanitize_text_field($_POST['wpformapi_method']);
				$options['wpformapi_form_field'] = self::wpform_to_any_api_sanitize_array($_POST['wpformapi_form_field']);
				
				foreach($options as $options_key => $options_value){
					$response = update_post_meta( $post_id , $options_key, $options_value );
    			}
				if($response){
					$status = 'true';
				}
			}
		}
	}

	/**
	 * Sanitize Array Value
	 *
	 * @since     1.0.0
	 * @return    string
	 */
	public static function wpform_to_any_api_sanitize_array($array){
		
		$sanitize_array = array();

		foreach($array as $key => $value) {
			$sanitize_array[sanitize_text_field($key)] = sanitize_text_field($value);
		}

		return $sanitize_array;
	}

	/**
	 * On Metabox Form Change Show that form fields
	 *
	 * @since    1.0.0
	 */
	public static function wpformapi_get_form_field(){
		
		if(isset($_POST['nonce']) && wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'wpform_to_any_api_cpt_nonce' ) ) {

			if(empty((int)stripslashes($_POST['form_id']))){
				
				echo wp_json_encode('No Fields Found for Selected Form.');
				
				exit();
			}
			
			$html = '';
			$form_ID     = (int)stripslashes($_POST['form_id']);
			$post_id     = (int)stripslashes($_POST['post_id']);

			$wpform_api_obj = new Wpform_To_Any_Api();
			$form_fields = $wpform_api_obj::wpform_to_api_get_form_fields($form_ID);

			$post_form_id = get_post_meta($post_id,'wpformapi_selected_form',true);
			$post_form_field = get_post_meta($post_id,'wpformapi_form_field',true);
			
			if(!empty($post_form_field) && $post_form_id == $form_ID){
				foreach($form_fields as $form_fields_key => $form_fields_value){
					if($form_fields_value->basetype != 'submit'){
						$html .= '<div class="wpformapi_field">';
							$html .= '<label for="wpformapi_'.$form_fields_value['id'].'">'.$form_fields_value['label'].'</label>';
							$html .= '<input type="text" id="wpformapi_'.$form_fields_value['id'].'" name="wpformapi_form_field['.$form_fields_value['id'].']" value="'.$post_form_field[$form_fields_key].'" data-basetype="'.$form_fields_value['type'].'" placeholder="'. __( 'Enter your API side mapping key', 'connect-wpform-to-any-api' ). '">'; 
						$html .= '</div>';
					}
				}
			}
			else{
				foreach($form_fields as $form_fields_key => $form_fields_value){
					if($form_fields_value->basetype != 'submit'){
						$html .= '<div class="wpformapi_field">';
							$html .= '<label for="wpformapi_'.$form_fields_value['id'].'">'.$form_fields_value['label'].'</label>';
							$html .= '<input type="text" id="wpformapi_'.$form_fields_value['id'].'" name="wpformapi_form_field['.$form_fields_value['id'].']" data-basetype="'.$form_fields_value['type'].'" placeholder="'. __( 'Enter your API side mapping key', 'connect-wpform-to-any-api' ). '">'; 
						$html .= '</div>';
					}
				}
			}
			echo wp_json_encode($html);
		}

		exit();
	}

	/**
	 * Register Logs Submenu Callback Function
	 *
	 * @since    1.0.0
	 */
	public function wpformapi_logs_callback(){
		
		$wpformlog_table = new WPformapi_List_Table();
	  	
	  	echo '<div class="wrap"><h2>' . esc_html__( 'WPForm API Log Data', 'connect-wpform-to-any-api' ) . '</h2>';
    	  	
    	  	wp_nonce_field('wpformapi_logs_nonce','wpformapi_logs_nonce' );
	  		
	  		echo '<div class="wpformapi_log_button">';
	  			
	  			echo '<a href="javascript:void(0);" class="wpformapi_bulk_log_delete">'.esc_html__( 'Delete All Log', 'connect-wpform-to-any-api' ).'</a>';
	  		
	  		echo '</div>';
	  	
	  	$wpformlog_table->prepare_items();
	  	
	  	$wpformlog_table->display(); 
	  	
	  	echo '</div>';
	}

	/**
	 * Delete all log in a one click
	 *
	 * @since    1.0.0
	 */
	public static function wpformapi_bulk_log_delete_function(){
		
		if(isset($_POST['wpformapi_logs_nonce']) && wp_verify_nonce(sanitize_text_field($_POST['wpformapi_logs_nonce']),'wpformapi_logs_nonce') ) {
			
			global $wpdb;
			$wpdb->query('TRUNCATE TABLE '.$wpdb->prefix.'wpformapi_logs');
		}
		exit();
	}

	/**
	 * Manage Column on api list view
	 *
	 * @since    1.0.0
	 */
	public function wpform_api_filter_posts_columns($columns){
		
		$columns = array(
			'cb' => $columns['cb'],
			'title' => __('Title'),
			'wpform' => __('Form Name','connect-wpform-to-any-api'),
			'date' => __('Date','connect-wpform-to-any-api'),
		);
		
		return $columns;
	}

	/**
	 * Custom Column on WPForm list view
	 *
	 * @since    1.0.0
	 */
	public function wpform_api_custom_column($column_name,$post_id){
		
		if($column_name == 'wpform'){
	    	
	    	$wpform_id = get_post_meta($post_id,'wpformapi_selected_form',true);
	    	
	    	if($wpform_id){
	    		
	    		echo '<a href="' . esc_url( site_url() . '/wp-admin/admin.php?page=wpforms-builder&view=fields&form_id=' . $wpform_id ) . '" target="_blank">' . esc_html( get_the_title( $wpform_id ) ) . '</a>';

	    	}
	      	
	    }
	}

	/**
	 * Custom Column manage sortable on WPForm list view
	 *
	 * @since    1.0.0
	 */
	public function wpform_api_sortable_columns($columns){
		$columns['wpform'] = 'wpformapi_selected_form';
  		return $columns;
	}

	/**
	 * On Form Submit Selected Form Data send to API
	 *
	 * @since    1.0.0
	 */
	public static function wpformapi_send_data_to_api($fields, $entry, $form_data, $entry_id){
		global $wpdb;
		$wpfromapi_uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'wpform-api-uploads';
		$form_id = (int)stripslashes($form_data['id']);
		if (! is_dir($wpfromapi_uploads_dir)) {
			wp_mkdir_p( $wpfromapi_uploads_dir );
		}		
		$posted_data = $fields;
		$post_id = $form_data['id'];
		$posted_data['submitted_from'] = $post_id;
		$posted_data['submit_time'] = date('Y-m-d H:i:s');
		$posted_data['User_IP'] = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );		

		$args = array(
			'post_type' => 'wpform_api',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query' => array(
		        'relation' => 'AND',
		        array(
		            'key' => 'wpformapi_selected_form',
		            'value' => $form_id,
		            'compare' => '=',
		        ),
		    ),
		);
		
		$the_query = new WP_Query($args);
		if($the_query->have_posts()){
		    
		    while($the_query->have_posts()){
		        $the_query->the_post();
		        $api_post_array = array();
		        
		        $wpformapi_form_field 	= array_filter(get_post_meta(get_the_ID(),'wpformapi_form_field',true));
		        $wpformapi_base_url 	= get_post_meta(get_the_ID(),'wpformapi_base_url',true);
		        $wpformapi_input_type 	= get_post_meta(get_the_ID(),'wpformapi_input_type',true);
				$wpformapi_method 		= get_post_meta(get_the_ID(),'wpformapi_method',true);
				$header_request 		= get_post_meta(get_the_ID(),'wpformapi_header_request' ,true);
				$wpformapi_header_request = apply_filters( 'wpformapi_header_request', $header_request, get_the_ID(), $form_id);

		        foreach($wpformapi_form_field as $key => $value){

		        	$api_post_array[$value] = (is_array($posted_data[$key]['value']) ? implode(',', self::wpform_to_any_api_sanitize_array($posted_data[$key]['value'])) : sanitize_text_field($posted_data[$key]['value']));

		        }
		        
		        self::wpformapi_send_data_to_lead($api_post_array, $wpformapi_base_url, $wpformapi_input_type, $wpformapi_method, $form_id, get_the_ID(), $wpformapi_header_request, $posted_data);
		    }
		}
		wp_reset_postdata();
	}

	/**
	 * Child Fuction of specific form data send to the API
	 *
	 * @since    1.0.0
	 */
	public static function wpformapi_send_data_to_lead($data, $url, $input_type, $method, $form_id, $post_id, $header_request = '', $posted_data = ''){
		
		global $wp_version;

		if($method == 'GET' && ($input_type == 'params' || $input_type == 'json')){
			$args = array(
				'timeout'     => 30,
				'redirection' => 5,
				'httpversion' => '1.0',
				'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
				'blocking'    => true,
				'headers'     => array(),
				'cookies'     => array(),
				'body'        => null,
				'compress'    => false,
				'decompress'  => true,
				'sslverify'   => true,
				'stream'      => false,
				'filename'    => null
			);

			if($input_type == 'params'){
				$data_string = http_build_query($data);

        		$url = stripos($url,'?') !== false ? $url.'&'.$data_string : $url.'?'.$data_string;
			}
			else{
				$args['headers']['Content-Type'] = 'application/json';
        		$json = self::wpformapi_parse_json($data);

        		if(is_wp_error($json)){
          			return $json;
        		}
        		else{
          			$args['body'] = $json;
        		}
			}
			
			$result = wp_remote_retrieve_body(wp_remote_get($url, $args));
      		self::wpformapi_save_response_in_log($post_id, $form_id, $result, $data);
		}
		else{
			$args = array(
				'timeout'     => 30,
				'redirection' => 5,
				'httpversion' => '1.0',
				'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
				'blocking'    => true,
				'headers'     => array(),
				'cookies'     => array(),
				'body'        => $data,
				'compress'    => false,
				'decompress'  => true,
				'sslverify'   => true,
				'stream'      => false,
				'filename'    => null
			);

			if(isset($header_request) && $header_request !== ''){
      			$args['headers'] = $header_request;
      		}
			
			if($input_type == "json"){
				if(!isset($header_request) && $header_request === ''){
        			$args['headers']['Content-Type'] = 'application/json';
        		}
        		$json = self::wpformapi_parse_json($data);
        	
        		if(is_wp_error($json)){
          			return $json;
        		}  
        		else{
          			$args['body'] = $json;
    			}
      		}
      		$result = wp_remote_post($url, $args);
      		$result_body = wp_remote_retrieve_body($result);
			if(!empty($result_body)){
				$result = $result_body;
			}
      		self::wpformapi_save_response_in_log($post_id, $form_id, $result, $data);
		}
	}

	/**
	 * Form Data convert into JSON formate
	 *
	 * @since    1.0.0
	 */
	public static function wpformapi_parse_json($string){
		return wp_json_encode($string, JSON_UNESCAPED_UNICODE);
  	}

  	/**
	 * API response store into Database
	 *
	 * @since    1.0.0
	 */
  	public static function wpformapi_save_response_in_log($post_id, $form_id, $response, $posted_data){
  		
  		global $wpdb;
  		$table = $wpdb->prefix.'wpformapi_logs';

  		// Base64 image get only 10 characters
  		if(isset($posted_data)){
  			foreach($posted_data as $key => $arr){
				if(strstr($key, 'file-')){
					$posted_data[$key] = mb_substr($arr, 0, 10).'...';
			    }
			}
  		}
  		
  		$form_data = wp_json_encode($posted_data, JSON_UNESCAPED_UNICODE);
  		if (gettype($response) != 'string') {
			$response = wp_json_encode($response, JSON_UNESCAPED_UNICODE);
		}
  		$data = array(
  			'form_id' 	=> intval($form_id),
  			'post_id' 	=> intval($post_id),
  			'form_data' => wp_kses_post($form_data),
  			'log' 		=> wp_kses_post($response),
  		);

  		$wpdb->insert($table,$data);
  	}
}
