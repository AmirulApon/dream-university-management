<?php
/**
 * Teacher management class
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Teacher class
 */
class DUM_Teacher {
	
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
		add_action( 'admin_post_dum_add_teacher', array( $this, 'handle_add_teacher' ) );
		add_action( 'admin_post_dum_edit_teacher', array( $this, 'handle_edit_teacher' ) );
		add_action( 'admin_post_dum_delete_teacher', array( $this, 'handle_delete_teacher' ) );
	}
	
	/**
	 * Get all teachers
	 */
	public static function get_all( $args = array() ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_teachers';
		
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
			$where .= $wpdb->prepare( ' AND (teacher_id LIKE %s OR first_name LIKE %s OR last_name LIKE %s OR email LIKE %s OR department LIKE %s OR designation LIKE %s)', $search, $search, $search, $search, $search, $search );
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
		
		return $wpdb->get_results( $query );
	}
	
	/**
	 * Get teacher by ID
	 */
	public static function get( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_teachers';
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ) );
	}
	
	/**
	 * Add teacher
	 */
	public static function add( $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_teachers';
		
		// Handle image from WordPress Media Library
		$image_url = '';
		if ( ! empty( $data['image_id'] ) ) {
			$image_url = self::handle_image( $data['image_id'] );
		} elseif ( ! empty( $data['image_url'] ) ) {
			$image_url = esc_url_raw( $data['image_url'] );
		}
		
		$insert_data = array(
			'teacher_id' => sanitize_text_field( $data['teacher_id'] ),
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
			'department' => sanitize_text_field( $data['department'] ?? '' ),
			'designation' => sanitize_text_field( $data['designation'] ?? '' ),
			'hire_date' => sanitize_text_field( $data['hire_date'] ?? '' ),
			'status' => sanitize_text_field( $data['status'] ?? 'active' ),
		);
		
		return $wpdb->insert( $table, $insert_data );
	}
	
	/**
	 * Update teacher
	 */
	public static function update( $id, $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_teachers';
		
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
			'teacher_id' => sanitize_text_field( $data['teacher_id'] ),
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
			'department' => sanitize_text_field( $data['department'] ?? '' ),
			'designation' => sanitize_text_field( $data['designation'] ?? '' ),
			'hire_date' => sanitize_text_field( $data['hire_date'] ?? '' ),
			'status' => sanitize_text_field( $data['status'] ?? 'active' ),
		);
		
		return $wpdb->update( $table, $update_data, array( 'id' => $id ) );
	}
	
	/**
	 * Delete teacher
	 */
	public static function delete( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_teachers';
		return $wpdb->delete( $table, array( 'id' => $id ) );
	}
	
	/**
	 * Count teachers
	 */
	public static function count( $status = 'all' ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_teachers';
		
		if ( $status === 'all' ) {
			return $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
		}
		
		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE status = %s", $status ) );
	}
	
	/**
	 * Handle add teacher
	 */
	public function handle_add_teacher() {
		check_admin_referer( 'dum_add_teacher' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$result = self::add( $_POST );
		
		if ( $result ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-teachers&message=added' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-teachers&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle edit teacher
	 */
	public function handle_edit_teacher() {
		check_admin_referer( 'dum_edit_teacher' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = intval( $_POST['teacher_id'] );
		$result = self::update( $id, $_POST );
		
		if ( $result !== false ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-teachers&message=updated' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-teachers&message=error' ) );
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
	 * Handle delete teacher
	 */
	public function handle_delete_teacher() {
		check_admin_referer( 'dum_delete_teacher' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = intval( $_GET['id'] );
		$result = self::delete( $id );
		
		if ( $result ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-teachers&message=deleted' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-teachers&message=error' ) );
		}
		exit;
	}
}

