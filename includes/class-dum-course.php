<?php
/**
 * Course management class
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Course class
 */
class DUM_Course {
	
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
		add_action( 'admin_post_dum_add_course', array( $this, 'handle_add_course' ) );
		add_action( 'admin_post_dum_edit_course', array( $this, 'handle_edit_course' ) );
		add_action( 'admin_post_dum_delete_course', array( $this, 'handle_delete_course' ) );
	}
	
	/**
	 * Get all courses
	 */
	public static function get_all( $args = array() ) {
		global $wpdb;
		$courses_table = $wpdb->prefix . 'dum_courses';
		$faculties_table = $wpdb->prefix . 'dum_faculties';
		$departments_table = $wpdb->prefix . 'dum_departments';
		
		$defaults = array(
			'status' => 'all',
			'search' => '',
			'limit' => 20,
			'offset' => 0,
			'orderby' => 'c.id',
			'order' => 'DESC',
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$where_conditions = array();
		$where_values = array();
		
		if ( $args['status'] !== 'all' ) {
			$where_conditions[] = 'c.status = %s';
			$where_values[] = $args['status'];
		}
		
		if ( ! empty( $args['search'] ) ) {
			$where_conditions[] = '(c.course_code LIKE %s OR c.course_name LIKE %s OR c.department LIKE %s OR f.faculty_name LIKE %s OR d.department_name LIKE %s)';
			$search = '%' . $wpdb->esc_like( $args['search'] ) . '%';
			$where_values[] = $search;
			$where_values[] = $search;
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
			$orderby = 'c.id DESC';
		}
		
		$query = "SELECT c.*, 
				  f.faculty_code, f.faculty_name,
				  d.department_code, d.department_name
				  FROM $courses_table c
				  LEFT JOIN $faculties_table f ON c.faculty_id = f.id
				  LEFT JOIN $departments_table d ON c.department_id = d.id
				  $where_clause
				  ORDER BY $orderby";
		
		if ( ! empty( $where_values ) ) {
			$query = $wpdb->prepare( $query, $where_values );
		}
		
		// Only add LIMIT if limit is set and greater than 0
		if ( $args['limit'] > 0 ) {
			$query .= $wpdb->prepare( ' LIMIT %d OFFSET %d', $args['limit'], $args['offset'] );
		}
		
		return $wpdb->get_results( $query );
	}
	
	/**
	 * Get course by ID
	 */
	public static function get( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_courses';
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ) );
	}
	
	/**
	 * Add course
	 */
	public static function add( $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_courses';
		
		$data = array(
			'course_code' => sanitize_text_field( $data['course_code'] ),
			'course_name' => sanitize_text_field( $data['course_name'] ),
			'description' => sanitize_textarea_field( $data['description'] ?? '' ),
			'credits' => floatval( $data['credits'] ?? 0 ),
			'faculty_id' => intval( $data['faculty_id'] ?? 0 ),
			'department_id' => intval( $data['department_id'] ?? 0 ),
			'department' => sanitize_text_field( $data['department'] ?? '' ),
			'semester' => sanitize_text_field( $data['semester'] ?? '' ),
			'teacher_id' => intval( $data['teacher_id'] ?? 0 ),
			'status' => sanitize_text_field( $data['status'] ?? 'active' ),
		);
		
		return $wpdb->insert( $table, $data );
	}
	
	/**
	 * Update course
	 */
	public static function update( $id, $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_courses';
		
		$data = array(
			'course_code' => sanitize_text_field( $data['course_code'] ),
			'course_name' => sanitize_text_field( $data['course_name'] ),
			'description' => sanitize_textarea_field( $data['description'] ?? '' ),
			'credits' => floatval( $data['credits'] ?? 0 ),
			'faculty_id' => intval( $data['faculty_id'] ?? 0 ),
			'department_id' => intval( $data['department_id'] ?? 0 ),
			'department' => sanitize_text_field( $data['department'] ?? '' ),
			'semester' => sanitize_text_field( $data['semester'] ?? '' ),
			'teacher_id' => intval( $data['teacher_id'] ?? 0 ),
			'status' => sanitize_text_field( $data['status'] ?? 'active' ),
		);
		
		return $wpdb->update( $table, $data, array( 'id' => $id ) );
	}
	
	/**
	 * Delete course
	 */
	public static function delete( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_courses';
		return $wpdb->delete( $table, array( 'id' => $id ) );
	}
	
	/**
	 * Count courses
	 */
	public static function count( $status = 'all' ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_courses';
		
		if ( $status === 'all' ) {
			return $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
		}
		
		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE status = %s", $status ) );
	}
	
	/**
	 * Handle add course
	 */
	public function handle_add_course() {
		check_admin_referer( 'dum_add_course' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$result = self::add( $_POST );
		
		if ( $result ) {
			wp_redirect( admin_url( 'admin.php?page=dum-courses&message=added' ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=dum-courses&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle edit course
	 */
	public function handle_edit_course() {
		check_admin_referer( 'dum_edit_course' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = intval( $_POST['course_id'] );
		$result = self::update( $id, $_POST );
		
		if ( $result !== false ) {
			wp_redirect( admin_url( 'admin.php?page=dum-courses&message=updated' ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=dum-courses&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle delete course
	 */
	public function handle_delete_course() {
		check_admin_referer( 'dum_delete_course' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = intval( $_GET['id'] );
		$result = self::delete( $id );
		
		if ( $result ) {
			wp_redirect( admin_url( 'admin.php?page=dum-courses&message=deleted' ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=dum-courses&message=error' ) );
		}
		exit;
	}
}

