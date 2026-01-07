<?php
/**
 * Faculty management class
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Faculty class
 */
class DUM_Faculty {
	
	/**
	 * Instance of this class
	 */
	private static $instance = null;
	
	/**
	 * Get instance of this class
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Constructor
	 */
	private function __construct() {
		add_action( 'admin_post_dum_add_faculty', array( $this, 'handle_add_faculty' ) );
		add_action( 'admin_post_dum_edit_faculty', array( $this, 'handle_edit_faculty' ) );
		add_action( 'admin_post_dum_delete_faculty', array( $this, 'handle_delete_faculty' ) );
	}
	
	/**
	 * Get all faculties
	 */
	public static function get_all( $args = array() ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_faculties';
		
		$defaults = array(
			'status' => 'all',
			'search' => '',
			'limit' => 1000,
			'offset' => 0,
			'orderby' => 'id',
			'order' => 'DESC',
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$where_conditions = array();
		$where_values = array();
		
		if ( $args['status'] !== 'all' ) {
			$where_conditions[] = 'status = %s';
			$where_values[] = $args['status'];
		}
		
		if ( ! empty( $args['search'] ) ) {
			$where_conditions[] = '(faculty_code LIKE %s OR faculty_name LIKE %s)';
			$search = '%' . $wpdb->esc_like( $args['search'] ) . '%';
			$where_values[] = $search;
			$where_values[] = $search;
		}
		
		$where_clause = '';
		if ( ! empty( $where_conditions ) ) {
			$where_clause = 'WHERE ' . implode( ' AND ', $where_conditions );
		}
		
		$orderby = sanitize_sql_orderby( $args['orderby'] . ' ' . $args['order'] );
		if ( ! $orderby ) {
			$orderby = 'id DESC';
		}
		
		$query = "SELECT * FROM $table $where_clause ORDER BY $orderby";
		
		if ( ! empty( $where_values ) ) {
			$query = $wpdb->prepare( $query, $where_values );
		}
		
		if ( $args['limit'] > 0 ) {
			$query .= $wpdb->prepare( ' LIMIT %d OFFSET %d', $args['limit'], $args['offset'] );
		}
		
		return $wpdb->get_results( $query );
	}
	
	/**
	 * Get faculty by ID
	 */
	public static function get( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_faculties';
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ) );
	}
	
	/**
	 * Add faculty
	 */
	public static function add( $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_faculties';
		
		$data = array(
			'faculty_code' => sanitize_text_field( $data['faculty_code'] ),
			'faculty_name' => sanitize_text_field( $data['faculty_name'] ),
			'description' => sanitize_textarea_field( $data['description'] ?? '' ),
			'status' => sanitize_text_field( $data['status'] ?? 'active' ),
		);
		
		return $wpdb->insert( $table, $data );
	}
	
	/**
	 * Update faculty
	 */
	public static function update( $id, $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_faculties';
		
		$data = array(
			'faculty_code' => sanitize_text_field( $data['faculty_code'] ),
			'faculty_name' => sanitize_text_field( $data['faculty_name'] ),
			'description' => sanitize_textarea_field( $data['description'] ?? '' ),
			'status' => sanitize_text_field( $data['status'] ?? 'active' ),
		);
		
		return $wpdb->update( $table, $data, array( 'id' => $id ) );
	}
	
	/**
	 * Delete faculty
	 */
	public static function delete( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_faculties';
		return $wpdb->delete( $table, array( 'id' => $id ) );
	}
	
	/**
	 * Count faculties
	 */
	public static function count( $status = 'all' ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_faculties';
		
		if ( $status === 'all' ) {
			return $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
		}
		
		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE status = %s", $status ) );
	}
	
	/**
	 * Handle add faculty
	 */
	public function handle_add_faculty() {
		check_admin_referer( 'dum_add_faculty' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$result = self::add( $_POST );
		
		if ( $result ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-faculties&message=added' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-faculties&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle edit faculty
	 */
	public function handle_edit_faculty() {
		check_admin_referer( 'dum_edit_faculty' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = intval( $_POST['faculty_id'] );
		$result = self::update( $id, $_POST );
		
		if ( $result !== false ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-faculties&message=updated' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-faculties&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle delete faculty
	 */
	public function handle_delete_faculty() {
		check_admin_referer( 'dum_delete_faculty' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = intval( $_GET['id'] );
		$result = self::delete( $id );
		
		if ( $result ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-faculties&message=deleted' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-faculties&message=error' ) );
		}
		exit;
	}
}

