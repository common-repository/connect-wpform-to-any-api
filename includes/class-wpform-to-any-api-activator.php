<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.itpathsolutions.com
 * @since      1.0.0
 *
 * @package    Wpform_To_Any_Api
 * @subpackage Wpform_To_Any_Api/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpform_To_Any_Api
 * @subpackage Wpform_To_Any_Api/includes
 * @author     IT Path Solutions <support@contactformtoapi.com>
 */
class Wpform_To_Any_Api_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if(is_multisite()){
            if(!is_plugin_active_for_network('wpforms-lite/wpforms.php') && !in_array( 'wpforms-lite/wpforms.php', apply_filters( 'active_plugins', get_option('active_plugins')))){
                deactivate_plugins(plugin_basename( __FILE__));
                wp_die( 
                    wp_kses_post( __( 'Please activate <a href="https://wordpress.org/plugins/wpforms-lite/" target="_blank">WPForms.</a>', 'connect-wpform-to-any-api' ) ), 
                    esc_html__( 'Plugin dependency check', 'connect-wpform-to-any-api' ), 
                    array( 'back_link' => true ) 
                );

            }
        }else{
            if(!in_array( 'wpforms-lite/wpforms.php', apply_filters( 'active_plugins', get_option('active_plugins')))){
                deactivate_plugins(plugin_basename( __FILE__));
                wp_die( 
                    wp_kses_post( __( 'Please activate <a href="https://wordpress.org/plugins/wpforms-lite/" target="_blank">WPForms.</a>', 'connect-wpform-to-any-api' ) ), 
                    esc_html__( 'Plugin dependency check', 'connect-wpform-to-any-api' ), 
                    array( 'back_link' => true ) 
                );
            }
        }

        self::wpformapi_logs_install_db();
	}

	/**
     * Created Custom Database Table
     *
     * On plugin activation time created custom database table
     *
     * @since    1.0.0
     */
    public static function wpformapi_logs_install_db() {
        
        global $wpdb;
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $wpformapi_logs = $wpdb->prefix.'wpformapi_logs';
        $charset_collate = $wpdb->get_charset_collate();

        $wpformapi_sql = "CREATE TABLE $wpformapi_logs (
            id int(11) NOT NULL AUTO_INCREMENT,
            form_id int(11) NOT NULL,
            post_id int(11) NOT NULL,
            form_data LONGTEXT NOT NULL,
            log LONGTEXT NOT NULL,
            created_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        dbDelta( $wpformapi_sql );
    }

}
