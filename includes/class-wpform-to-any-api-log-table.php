<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WPformapi_List_Table extends WP_List_Table{

	public $logs_data;

    public function __construct(){
    	global $status, $page;
        parent::__construct(
        	array(
            	'singular'  => __( 'wpformapi_logs', 'connect-wpform-to-any-api' ),
            	'plural'    => __( 'wpformapi_logs', 'connect-wpform-to-any-api' ),
            	'ajax'      => false,
    		)
        );
    }

  	public function column_default($item, $column_name){
    	switch($column_name){ 
        	case 'form_id':
        		return '<a href="'.site_url()."/wp-admin/admin.php?page=wpforms-builder&view=fields&form_id=".$item[ $column_name ].'" target="_blank">'.get_the_title($item[$column_name]).'</a>';
        	case 'post_id':
        		return '<a href="'.get_edit_post_link($item[$column_name]).'" target="_blank">'.get_the_title($item[$column_name]).'</a>';
        	case 'form_data':
        	case 'log':
            case 'created_date':
            	return $item[ $column_name ];
        	default:
            	return print_r($item, true);
    	}
  	}

	public function get_columns(){
        $columns = array(
            'form_id' => __( 'Form Name', 'connect-wpform-to-any-api' ),
            'post_id' => __( 'API Name', 'connect-wpform-to-any-api' ),
            'form_data' => __( 'Submitted Data', 'connect-wpform-to-any-api' ),
            'log' => __( 'API Response', 'connect-wpform-to-any-api' ),
            'created_date' => __( 'Created Date', 'connect-wpform-to-any-api' )
        );
        return $columns;
    }

	function default_logs_data($page_number = 1) {
	    global $wpdb;

	    // Get raw input and unslash it
	    $orderby_raw = sanitize_text_field($_REQUEST['orderby'] ?? 'created_date');
	    $order_raw = sanitize_text_field($_REQUEST['order'] ?? 'desc');

	    // Define a whitelist of allowed orderby columns
	    $allowed_orderby = ['created_date', 'modified_date'];

	    // Sanitize and validate the inputs
	    $orderby_sanitized = in_array($orderby_raw, $allowed_orderby) ? sanitize_key($orderby_raw) : 'created_date';
	    $order_sanitized = in_array(strtoupper($order_raw), ['ASC', 'DESC']) ? strtoupper($order_raw) : 'DESC';

	    // Cache key based on page number and ordering
	    $cache_key = 'wpformapi_logs_page_' . $page_number . '_' . $orderby_sanitized . '_' . $order_sanitized;

	    // Try to get cached data
	    $cached_data = wp_cache_get($cache_key, 'wpformapi');
	    if ($cached_data !== false) {
	        return $cached_data;
	    }

	    $offset = ($page_number === 1) ? 0 : ($page_number - 1) * 10;

	    // Prepare and execute the query
	    $query = $wpdb->prepare(
	        "SELECT * FROM {$wpdb->prefix}wpformapi_logs ORDER BY {$orderby_sanitized} {$order_sanitized} LIMIT 10 OFFSET %d", 
	        $offset
	    );

	    $result = $wpdb->get_results($query, 'ARRAY_A');

	    // Cache the result for 1 hour
	    wp_cache_set($cache_key, $result, 'wpformapi', 3600);

	    return $result;
	}






	public static function get_logs_data(){
	    global $wpdb;

	    // Check if logs data is stored in transients
	    $logs_data = get_transient('wpformapi_logs_data');
	    if ($logs_data !== false) {
	        return $logs_data;
	    }

	    // If not cached, query the database
	    $query = "SELECT * FROM {$wpdb->prefix}wpformapi_logs";
	    $logs_data = $wpdb->get_results($query, ARRAY_A);

	    // Store the result in transients for 1 hour
	    set_transient('wpformapi_logs_data', $logs_data, HOUR_IN_SECONDS);

	    return $logs_data;
	}


	public function prepare_items(){
		
		$this->logs_data = $this->get_logs_data();

  		$columns = $this->get_columns();
  		$hidden = array();
  		$sortable = $this->get_sortable_columns();
  		$this->_column_headers = array( $columns, $hidden, $sortable);

  		/* pagination */
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $total_items = count($this->logs_data);

        $this->logs_data = array_slice($this->logs_data, (($current_page - 1) * $per_page), $per_page);

        $this->set_pagination_args(array(
			'total_items' => $total_items, 
			'per_page'    => $per_page 
        ));

  		$this->items = self::default_logs_data();
	}

	public function get_sortable_columns(){
		
		$sortable_columns = array(
			'form_id' => array( 'form_id', true ),
			'post_id' => array( 'post_id', true ),
			'created_date' => array( 'created_date', true ),
		);

		return $sortable_columns;
	}

	public function usort_reorder($a, $b){
		
		$orderby = (!empty($_GET['orderby'])) ? sanitize_text_field($_GET['orderby']) : 'form_id';
		$order = (!empty($_GET['order'])) ? sanitize_text_field($_GET['order']) : 'asc';
		$result = strcmp($a[$orderby], $b[$orderby]);
		return ($order === 'asc') ? $result : -$result;
	}
}