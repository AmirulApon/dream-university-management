<?php
/**
 * Enrollment management class
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enrollment class
 */
class DUM_Enrollment {
	
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
		add_action( 'admin_post_dum_add_enrollment', array( $this, 'handle_add_enrollment' ) );
		add_action( 'admin_post_dum_delete_enrollment', array( $this, 'handle_delete_enrollment' ) );
	}
	
	/**
	 * Get all enrollments
	 */
	public static function get_all( $args = array() ) {
		global $wpdb;
		$enrollments_table = $wpdb->prefix . 'dum_enrollments';
		$students_table = $wpdb->prefix . 'dum_students';
		$courses_table = $wpdb->prefix . 'dum_courses';
		
		$defaults = array(
			'status' => 'all',
			'student_id' => 0,
			'course_id' => 0,
			'limit' => 20,
			'offset' => 0,
			'orderby' => 'e.id',
			'order' => 'DESC',
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$where_conditions = array();
		$where_values = array();
		
		if ( $args['status'] !== 'all' ) {
			$where_conditions[] = 'e.status = %s';
			$where_values[] = $args['status'];
		}
		
		if ( $args['student_id'] > 0 ) {
			$where_conditions[] = 'e.student_id = %d';
			$where_values[] = $args['student_id'];
		}
		
		if ( $args['course_id'] > 0 ) {
			$where_conditions[] = 'e.course_id = %d';
			$where_values[] = $args['course_id'];
		}
		
		$where_clause = '';
		if ( ! empty( $where_conditions ) ) {
			$where_clause = 'WHERE ' . implode( ' AND ', $where_conditions );
		}
		
		$orderby = sanitize_sql_orderby( $args['orderby'] . ' ' . $args['order'] );
		if ( ! $orderby ) {
			$orderby = 'e.id DESC';
		}
		
		// Build query with LEFT JOIN to show all enrollments even if student/course is missing
		$query = "SELECT e.id, e.student_id as enrollment_student_id, e.course_id as enrollment_course_id, 
				  e.enrollment_date, e.status, e.created_at, e.updated_at,
				  s.student_id, s.first_name as student_first_name, s.last_name as student_last_name, 
				  c.course_code, c.course_name, c.credits
				  FROM $enrollments_table e
				  LEFT JOIN $students_table s ON e.student_id = s.id
				  LEFT JOIN $courses_table c ON e.course_id = c.id
				  $where_clause
				  ORDER BY $orderby";
		
		// Prepare query with values if we have where conditions
		if ( ! empty( $where_values ) ) {
			$query = $wpdb->prepare( $query, $where_values );
		}
		
		// Only add LIMIT if limit is set and greater than 0
		if ( $args['limit'] > 0 ) {
			$limit_query = $wpdb->prepare( ' LIMIT %d OFFSET %d', $args['limit'], $args['offset'] );
			$query .= $limit_query;
		}
		
		$results = $wpdb->get_results( $query, OBJECT );
		
		// If query failed or returned null, return empty array
		if ( $wpdb->last_error ) {
			// Log error for debugging (remove in production)
			// error_log( 'Enrollment Query Error: ' . $wpdb->last_error );
			// error_log( 'Enrollment Query: ' . $query );
			return array();
		}
		
		// Ensure we return an array even if empty
		return $results ? $results : array();
	}
	
	/**
	 * Get enrollment by ID
	 */
	public static function get( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_enrollments';
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ) );
	}
	
	/**
	 * Add enrollment
	 */
	public static function add( $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_enrollments';
		
		$student_id = intval( $data['student_id'] );
		$course_id = intval( $data['course_id'] );
		
		// Check if already enrolled
		if ( self::is_enrolled( $student_id, $course_id ) ) {
			return false;
		}
		
		$insert_data = array(
			'student_id' => $student_id,
			'course_id' => $course_id,
			'enrollment_date' => sanitize_text_field( $data['enrollment_date'] ?? date( 'Y-m-d' ) ),
			'status' => sanitize_text_field( $data['status'] ?? 'enrolled' ),
		);
		
		return $wpdb->insert( $table, $insert_data );
	}
	
	/**
	 * Delete enrollment
	 */
	public static function delete( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_enrollments';
		return $wpdb->delete( $table, array( 'id' => $id ) );
	}
	
	/**
	 * Check if student is enrolled in course
	 */
	public static function is_enrolled( $student_id, $course_id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_enrollments';
		$count = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM $table WHERE student_id = %d AND course_id = %d",
			$student_id,
			$course_id
		) );
		return $count > 0;
	}
	
	/**
	 * Handle add enrollment
	 */
	public function handle_add_enrollment() {
		check_admin_referer( 'dum_add_enrollment' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$student_id = intval( $_POST['student_id'] );
		$course_id = intval( $_POST['course_id'] );
		
		// Check if already enrolled
		if ( self::is_enrolled( $student_id, $course_id ) ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-enrollments&message=duplicate' ) );
			exit;
		}
		
		$result = self::add( $_POST );
		
		if ( $result ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-enrollments&message=added' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-enrollments&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle delete enrollment
	 */
	public function handle_delete_enrollment() {
		check_admin_referer( 'dum_delete_enrollment' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = intval( $_GET['id'] );
		$result = self::delete( $id );
		
		if ( $result ) {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-enrollments&message=deleted' ) );
		} else {
			wp_safe_redirect( admin_url( 'admin.php?page=dum-enrollments&message=error' ) );
		}
		exit;
	}
}

