<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.itpathsolutions.com
 * @since      1.0.0
 *
 * @package    Wpform_To_Any_Api
 * @subpackage Wpform_To_Any_Api/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wpform_To_Any_Api
 * @subpackage Wpform_To_Any_Api/includes
 * @author     IT Path Solutions <support@contactformtoapi.com>
 */
class Wpform_To_Any_Api {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wpform_To_Any_Api_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WPFORM_TO_ANY_API_VERSION' ) ) {
			$this->version = WPFORM_TO_ANY_API_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'connect-wpform-to-any-api';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wpform_To_Any_Api_Loader. Orchestrates the hooks of the plugin.
	 * - Wpform_To_Any_Api_i18n. Defines internationalization functionality.
	 * - Wpform_To_Any_Api_Admin. Defines all hooks for the admin area.
	 * - Wpform_To_Any_Api_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpform-to-any-api-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpform-to-any-api-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpform-to-any-api-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpform-to-any-api-public.php';

		/**
		 * The class responsible for defining Custom WP List Table For Log display
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpform-to-any-api-log-table.php';
		
		$this->loader = new Wpform_To_Any_Api_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpform_To_Any_Api_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wpform_To_Any_Api_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wpform_To_Any_Api_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'wpform_to_any_api_verify_dependencies' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wpform_to_any_api_register_submenu', 90);
		$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'wpform_to_any_api_add_settings_link', 10, 2 );
		$this->loader->add_action( 'init', $plugin_admin, 'wpform_to_any_api_custom_post_type' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'wpform_to_any_api_metabox' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'wpform_to_any_api_save_settings', 10, 2 );
		$this->loader->add_action( 'wp_ajax_wpformapi_get_form_field', $plugin_admin, 'wpformapi_get_form_field' );
		$this->loader->add_action( 'wp_ajax_wpformapi_bulk_log_delete', $plugin_admin, 'wpformapi_bulk_log_delete_function');
		$this->loader->add_filter( 'manage_wpform_api_posts_columns', $plugin_admin, 'wpform_api_filter_posts_columns' );
		$this->loader->add_action( 'manage_wpform_api_posts_custom_column', $plugin_admin, 'wpform_api_custom_column',10,2);
		$this->loader->add_filter( 'manage_edit-wpform_api_sortable_columns', $plugin_admin, 'wpform_api_sortable_columns');
		$this->loader->add_action( 'wpforms_process_complete', $plugin_admin, 'wpformapi_send_data_to_api', 10, 4);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		/*$plugin_public = new Wpform_To_Any_Api_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );*/

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wpform_To_Any_Api_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the all current Post API data
	 *
	 * @since     1.0.0
	 * @return    string
	 */
	public function wpform_to_any_api_get_options() {
		global $post;
		$options = [];
		$field_array = array();
		$options['wpformapi_selected_form'] = get_post_meta($post->ID,'wpformapi_selected_form',true);
		$options['wpformapi_base_url'] = get_post_meta($post->ID,'wpformapi_base_url',true);
		$options['wpformapi_input_type'] = get_post_meta($post->ID,'wpformapi_input_type',true);
		$options['wpformapi_method'] = get_post_meta($post->ID,'wpformapi_method',true);
		$options['wpformapi_form_field'] = get_post_meta($post->ID,'wpformapi_form_field',true);
		$options['wpformapi_header_request'] = get_post_meta($post->ID,'wpformapi_header_request',true);

		if(!empty($options['wpformapi_selected_form'])){
			$form_fields = self::wpform_to_api_get_form_fields($options['wpformapi_selected_form']);
			$form_fields_list = array();
			foreach($form_fields as $key => $form_fields_value){
				if($form_fields_value['type'] != 'submit'){

					$field_value = (isset($options['wpformapi_form_field'][$key]) ? $options['wpformapi_form_field'][$key] : '');
					$form_fields_list[$key] = array( 
						'id' 	=> !empty( $form_fields_value['id'] ) ? $form_fields_value['id'] : '',
						'label' => !empty( $form_fields_value['label'] ) ? $form_fields_value['label'] : 'Field Label',
						'value' => $field_value
					);
				}
			}
			$options['wpformapi_form_field'] = $form_fields_list;
		}
		return $options;
	}

	/**
	 * Saved Form Fields show by default
	 *
	 * @since     1.0.0
	 * @return    string
	 */
	public function wpform_to_any_api_default_form_field($form_id){
		$data 		 = array();
		$form_ID     = (int)stripslashes($form_id);
		$ContactForm = wpforms()->form->get( $form_ID );
		
		if($ContactForm){
			$form_fields = self::wpform_to_api_get_form_fields($form_ID );
    		$data['status'] = 200;
    		$data['fields'] = $form_fields;
    	}
    	else{
    		$data['status'] = 404;
    		$data['fields'] = 'No form Found';
    	}
		return $data;
	}

	/**
	 * Function to get all form fields by form ID
	 *
	 * @since     1.0.0
	 * @return    string
	 */
	public static function wpform_to_api_get_form_fields($form_id) {
	    
	    if ( class_exists('WPForms') && !empty($form_id)) {

	        $form = wpforms()->form->get( $form_id );
	        if ( $form ) {

	            $form_fields = !empty( $form->post_content ) ? json_decode( $form->post_content, true ) : [];

	            return !empty($form_fields['fields']) ? $form_fields['fields'] : array();

	        } else {
	            return esc_html__('WPForm not found','connect-wpform-to-any-api');
	        }
	    } else {
	        return esc_html__('WPForms plugin is not active','connect-wpform-to-any-api');
	    }
	}

}
