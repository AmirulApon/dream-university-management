<?php
/**
 * Department management class
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Department class
 */
class DUM_Department {
	
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
		add_action( 'admin_post_dum_add_department', array( $this, 'handle_add_department' ) );
		add_action( 'admin_post_dum_edit_department', array( $this, 'handle_edit_department' ) );
		add_action( 'admin_post_dum_delete_department', array( $this, 'handle_delete_department' ) );
	}
	
	/**
	 * Get all departments
	 */
	public static function get_all( $args = array() ) {
		global $wpdb;
		$departments_table = $wpdb->prefix . 'dum_departments';
		$faculties_table = $wpdb->prefix . 'dum_faculties';
		
		$defaults = array(
			'faculty_id' => 0,
			'status' => 'all',
			'search' => '',
			'limit' => 1000,
			'offset' => 0,
			'orderby' => 'd.id',
			'order' => 'DESC',
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$where_conditions = array();
		$where_values = array();
		
		if ( $args['faculty_id'] > 0 ) {
			$where_conditions[] = 'd.faculty_id = %d';
			$where_values[] = $args['faculty_id'];
		}
		
		if ( $args['status'] !== 'all' ) {
			$where_conditions[] = 'd.status = %s';
			$where_values[] = $args['status'];
		}
		
		if ( ! empty( $args['search'] ) ) {
			$where_conditions[] = '(d.department_code LIKE %s OR d.department_name LIKE %s OR f.faculty_name LIKE %s)';
			$search = '%' . $wpdb->esc_like( $args['search'] ) . '%';
			$where_values[] = $search;
			$where_values[] = $search;
			$where_values[] = $search;
		}
		
		$where_clause = '';
		if ( ! empty( $where_conditions ) ) {
			$where_clause = 'WHERE ' . implode( ' AND ', $where_conditions );
		}
		
		$orderby = sanitize_sql_orderby( $args['orderby'] . ' ' . $args['order'] );
		if ( ! $orderby ) {
			$orderby = 'd.id DESC';
		}
		
		$query = "SELECT d.*, f.faculty_code, f.faculty_name 
				  FROM $departments_table d
				  LEFT JOIN $faculties_table f ON d.faculty_id = f.id
				  $where_clause
				  ORDER BY $orderby";
		
		if ( ! empty( $where_values ) ) {
			$query = $wpdb->prepare( $query, $where_values );
		}
		
		if ( $args['limit'] > 0 ) {
			$query .= $wpdb->prepare( ' LIMIT %d OFFSET %d', $args['limit'], $args['offset'] );
		}
		
		return $wpdb->get_results( $query );
	}
	
	/**
	 * Get departments by faculty ID
	 */
	public static function get_by_faculty( $faculty_id, $status = 'active' ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_departments';
		
		if ( $status === 'all' ) {
			return $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM $table WHERE faculty_id = %d ORDER BY department_name ASC",
				$faculty_id
			) );
		}
		
		return $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM $table WHERE faculty_id = %d AND status = %s ORDER BY department_name ASC",
			$faculty_id,
			$status
		) );
	}
	
	/**
	 * Get department by ID
	 */
	public static function get( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_departments';
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ) );
	}
	
	/**
	 * Add department
	 */
	public static function add( $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_departments';
		
		$data = array(
			'department_code' => sanitize_text_field( $data['department_code'] ),
			'department_name' => sanitize_text_field( $data['department_name'] ),
			'faculty_id' => intval( $data['faculty_id'] ),
			'description' => sanitize_textarea_field( $data['description'] ?? '' ),
			'status' => sanitize_text_field( $data['status'] ?? 'active' ),
		);
		
		return $wpdb->insert( $table, $data );
	}
	
	/**
	 * Update department
	 */
	public static function update( $id, $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_departments';
		
		$data = array(
			'department_code' => sanitize_text_field( $data['department_code'] ),
			'department_name' => sanitize_text_field( $data['department_name'] ),
			'faculty_id' => intval( $data['faculty_id'] ),
			'description' => sanitize_textarea_field( $data['description'] ?? '' ),
			'status' => sanitize_text_field( $data['status'] ?? 'active' ),
		);
		
		return $wpdb->update( $table, $data, array( 'id' => $id ) );
	}
	
	/**
	 * Delete department
	 */
	public static function delete( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_departments';
		return $wpdb->delete( $table, array( 'id' => $id ) );
	}
	
	/**
	 * Count departments
	 */
	public static function count( $status = 'all', $faculty_id = 0 ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_departments';
		
		if ( $faculty_id > 0 && $status === 'all' ) {
			return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE faculty_id = %d", $faculty_id ) );
		}
		
		if ( $faculty_id > 0 ) {
			return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE faculty_id = %d AND status = %s", $faculty_id, $status ) );
		}
		
		if ( $status === 'all' ) {
			return $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
		}
		
		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE status = %s", $status ) );
	}
	
	/**
	 * Handle add department
	 */
	public function handle_add_department() {
		check_admin_referer( 'dum_add_department' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$result = self::add( $_POST );
		
		if ( $result ) {
			wp_redirect( admin_url( 'admin.php?page=dum-departments&message=added' ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=dum-departments&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle edit department
	 */
	public function handle_edit_department() {
		check_admin_referer( 'dum_edit_department' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = intval( $_POST['department_id'] );
		$result = self::update( $id, $_POST );
		
		if ( $result !== false ) {
			wp_redirect( admin_url( 'admin.php?page=dum-departments&message=updated' ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=dum-departments&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle delete department
	 */
	public function handle_delete_department() {
		check_admin_referer( 'dum_delete_department' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = intval( $_GET['id'] );
		$result = self::delete( $id );
		
		if ( $result ) {
			wp_redirect( admin_url( 'admin.php?page=dum-departments&message=deleted' ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=dum-departments&message=error' ) );
		}
		exit;
	}
}

