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
class DREAUNMA_Department {
	
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
		add_action( 'admin_post_dreaunma_add_department', array( $this, 'handle_add_department' ) );
		add_action( 'admin_post_dreaunma_edit_department', array( $this, 'handle_edit_department' ) );
		add_action( 'admin_post_dreaunma_delete_department', array( $this, 'handle_delete_department' ) );
	}
	
	/**
	 * Get all departments
	 */
	public static function get_all( $args = array() ) {
		global $wpdb;
		$departments_table = $wpdb->prefix . 'dreaunma_departments';
		$faculties_table = $wpdb->prefix . 'dreaunma_faculties';
		
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
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table names are safe (from $wpdb->prefix), $where_values are sanitized via placeholders
			$query = $wpdb->prepare( $query, $where_values );
		}
		
		if ( $args['limit'] > 0 ) {
			$query .= $wpdb->prepare( ' LIMIT %d OFFSET %d', $args['limit'], $args['offset'] );
		}
		
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Query is prepared above if needed, table names safe, $where_clause uses prepared placeholders, $orderby is sanitized
		return $wpdb->get_results( $query );
	}
	
	/**
	 * Get departments by faculty ID
	 */
	public static function get_by_faculty( $faculty_id, $status = 'active' ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dreaunma_departments';
		
		if ( $status === 'all' ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table name is safe (from $wpdb->prefix), $faculty_id is sanitized via %d placeholder
			return $wpdb->get_results( $wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name is safe (from $wpdb->prefix)
				"SELECT * FROM $table WHERE faculty_id = %d ORDER BY department_name ASC",
				$faculty_id
			) );
		}
		
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table name is safe (from $wpdb->prefix), $faculty_id and $status are sanitized via placeholders
		return $wpdb->get_results( $wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name is safe (from $wpdb->prefix)
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
		$table = $wpdb->prefix . 'dreaunma_departments';
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table name is safe (from $wpdb->prefix), $id is sanitized via %d placeholder
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ) );
	}
	
	/**
	 * Add department
	 */
	public static function add( $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dreaunma_departments';
		
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
		$table = $wpdb->prefix . 'dreaunma_departments';
		
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
		$table = $wpdb->prefix . 'dreaunma_departments';
		return $wpdb->delete( $table, array( 'id' => $id ) );
	}
	
	/**
	 * Count departments
	 */
	public static function count( $status = 'all', $faculty_id = 0 ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dreaunma_departments';
		
		if ( $faculty_id > 0 && $status === 'all' ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table name is safe (from $wpdb->prefix), $faculty_id is sanitized via %d placeholder
			return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE faculty_id = %d", $faculty_id ) );
		}
		
		if ( $faculty_id > 0 ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table name is safe (from $wpdb->prefix), $faculty_id and $status are sanitized via placeholders
			return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE faculty_id = %d AND status = %s", $faculty_id, $status ) );
		}
		
		if ( $status === 'all' ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table name is safe (from $wpdb->prefix), no user input in query
			return $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
		}
		
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table name is safe (from $wpdb->prefix), $status is sanitized via %s placeholder
		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE status = %s", $status ) );
	}
	
	/**
	 * Handle add department
	 */
	public function handle_add_department() {
		check_admin_referer( 'dreaunma_add_department' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		// Extract and sanitize only required fields
		$data = array(
			'department_code' => isset( $_POST['department_code'] ) ? sanitize_text_field( wp_unslash( $_POST['department_code'] ) ) : '',
			'department_name' => isset( $_POST['department_name'] ) ? sanitize_text_field( wp_unslash( $_POST['department_name'] ) ) : '',
			'faculty_id' => isset( $_POST['faculty_id'] ) ? intval( $_POST['faculty_id'] ) : 0,
			'description' => isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '',
			'status' => isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : 'active',
		);
		
		$result = self::add( $data );
		
		if ( $result ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dreaunma-departments&message=added' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dreaunma-departments&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle edit department
	 */
	public function handle_edit_department() {
		check_admin_referer( 'dreaunma_edit_department' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = isset( $_POST['department_id'] ) ? intval( $_POST['department_id'] ) : 0;
		
		// Extract and sanitize only required fields
		$data = array(
			'department_code' => isset( $_POST['department_code'] ) ? sanitize_text_field( wp_unslash( $_POST['department_code'] ) ) : '',
			'department_name' => isset( $_POST['department_name'] ) ? sanitize_text_field( wp_unslash( $_POST['department_name'] ) ) : '',
			'faculty_id' => isset( $_POST['faculty_id'] ) ? intval( $_POST['faculty_id'] ) : 0,
			'description' => isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '',
			'status' => isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : 'active',
		);
		
		$result = self::update( $id, $data );
		
		if ( $result !== false ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dreaunma-departments&message=updated' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dreaunma-departments&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle delete department
	 */
	public function handle_delete_department() {
		check_admin_referer( 'dreaunma_delete_department' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
		$result = self::delete( $id );
		
		if ( $result ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dreaunma-departments&message=deleted' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dreaunma-departments&message=error' ) );
		}
		exit;
	}
}

