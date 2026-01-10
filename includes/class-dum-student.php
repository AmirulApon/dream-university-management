<?php
/**
 * Student management class
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Student class
 */
class DUM_Student {
	
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
		add_action( 'admin_post_dum_add_student', array( $this, 'handle_add_student' ) );
		add_action( 'admin_post_dum_edit_student', array( $this, 'handle_edit_student' ) );
		add_action( 'admin_post_dum_delete_student', array( $this, 'handle_delete_student' ) );
	}
	
	/**
	 * Get all students
	 */
	public static function get_all( $args = array() ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_students';
		
		$defaults = array(
			'status' => 'all',
			'search' => '',
			'limit' => 20,
			'offset' => 0,
			'orderby' => 'id',
			'order' => 'DESC',
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$where = '1=1';
		
		if ( $args['status'] !== 'all' ) {
			$where .= $wpdb->prepare( ' AND status = %s', $args['status'] );
		}
		
		if ( ! empty( $args['search'] ) ) {
			$search = '%' . $wpdb->esc_like( $args['search'] ) . '%';
			$where .= $wpdb->prepare( ' AND (student_id LIKE %s OR first_name LIKE %s OR last_name LIKE %s OR email LIKE %s)', $search, $search, $search, $search );
		}
		
		$orderby = sanitize_sql_orderby( $args['orderby'] . ' ' . $args['order'] );
		if ( ! $orderby ) {
			$orderby = 'id DESC';
		}
		
		$query = "SELECT * FROM $table WHERE $where ORDER BY $orderby";
		
		// Only add LIMIT if limit is set and greater than 0
		if ( $args['limit'] > 0 ) {
			$query .= $wpdb->prepare( ' LIMIT %d OFFSET %d', $args['limit'], $args['offset'] );
		}
		
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Query uses prepared WHERE clause ($where uses $wpdb->prepare), $orderby is sanitized, table name safe
		return $wpdb->get_results( $query );
	}
	
	/**
	 * Get student by ID
	 */
	public static function get( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_students';
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table name is safe (from $wpdb->prefix), $id is sanitized via %d placeholder
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ) );
	}
	
	/**
	 * Add student
	 */
	public static function add( $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_students';
		
		// Handle image from WordPress Media Library
		$image_url = '';
		if ( ! empty( $data['image_id'] ) ) {
			$image_url = self::handle_image( $data['image_id'] );
		} elseif ( ! empty( $data['image_url'] ) ) {
			$image_url = esc_url_raw( $data['image_url'] );
		}
		
		$insert_data = array(
			'student_id' => sanitize_text_field( $data['student_id'] ),
			'first_name' => sanitize_text_field( $data['first_name'] ),
			'last_name' => sanitize_text_field( $data['last_name'] ),
			'email' => sanitize_email( $data['email'] ),
			'phone' => sanitize_text_field( $data['phone'] ?? '' ),
			'date_of_birth' => sanitize_text_field( $data['date_of_birth'] ?? '' ),
			'gender' => sanitize_text_field( $data['gender'] ?? '' ),
			'address' => sanitize_textarea_field( $data['address'] ?? '' ),
			'image' => $image_url,
			'faculty_id' => intval( $data['faculty_id'] ?? 0 ),
			'department_id' => intval( $data['department_id'] ?? 0 ),
			'admission_date' => sanitize_text_field( $data['admission_date'] ?? '' ),
			'status' => sanitize_text_field( $data['status'] ?? 'active' ),
		);
		
		return $wpdb->insert( $table, $insert_data );
	}
	
	/**
	 * Update student
	 */
	public static function update( $id, $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_students';
		
		// Handle image from WordPress Media Library
		$image_url = '';
		if ( ! empty( $data['image_id'] ) ) {
			$image_url = self::handle_image( $data['image_id'] );
		} elseif ( ! empty( $data['image_url'] ) ) {
			$image_url = esc_url_raw( $data['image_url'] );
		} else {
			// Keep existing image if no new one selected
			$existing = self::get( $id );
			$image_url = $existing ? $existing->image : '';
		}
		
		$update_data = array(
			'student_id' => sanitize_text_field( $data['student_id'] ),
			'first_name' => sanitize_text_field( $data['first_name'] ),
			'last_name' => sanitize_text_field( $data['last_name'] ),
			'email' => sanitize_email( $data['email'] ),
			'phone' => sanitize_text_field( $data['phone'] ?? '' ),
			'date_of_birth' => sanitize_text_field( $data['date_of_birth'] ?? '' ),
			'gender' => sanitize_text_field( $data['gender'] ?? '' ),
			'address' => sanitize_textarea_field( $data['address'] ?? '' ),
			'image' => $image_url,
			'faculty_id' => intval( $data['faculty_id'] ?? 0 ),
			'department_id' => intval( $data['department_id'] ?? 0 ),
			'admission_date' => sanitize_text_field( $data['admission_date'] ?? '' ),
			'status' => sanitize_text_field( $data['status'] ?? 'active' ),
		);
		
		return $wpdb->update( $table, $update_data, array( 'id' => $id ) );
	}
	
	/**
	 * Delete student
	 */
	public static function delete( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_students';
		return $wpdb->delete( $table, array( 'id' => $id ) );
	}
	
	/**
	 * Count students
	 */
	public static function count( $status = 'all' ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_students';
		
		if ( $status === 'all' ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table name is safe (from $wpdb->prefix), no user input in query
			return $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
		}
		
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table name is safe (from $wpdb->prefix), $status is sanitized via %s placeholder
		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE status = %s", $status ) );
	}
	
	/**
	 * Handle add student
	 */
	public function handle_add_student() {
		check_admin_referer( 'dum_add_student' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$result = self::add( $_POST );
		
		if ( $result ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-students&message=added' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-students&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle edit student
	 */
	public function handle_edit_student() {
		check_admin_referer( 'dum_edit_student' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = isset( $_POST['student_id'] ) ? intval( $_POST['student_id'] ) : 0;
		$result = self::update( $id, $_POST );
		
		if ( $result !== false ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-students&message=updated' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-students&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle image from WordPress Media Library
	 */
	private static function handle_image( $image_id ) {
		if ( empty( $image_id ) ) {
			return '';
		}
		
		$image_id = intval( $image_id );
		$image_url = wp_get_attachment_image_url( $image_id, 'full' );
		
		return $image_url ? $image_url : '';
	}
	
	/**
	 * Handle delete student
	 */
	public function handle_delete_student() {
		check_admin_referer( 'dum_delete_student' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
		$result = self::delete( $id );
		
		if ( $result ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-students&message=deleted' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-students&message=error' ) );
		}
		exit;
	}
}

