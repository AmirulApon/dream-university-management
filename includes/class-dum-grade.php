<?php
/**
 * Grade management class
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Grade class
 */
class DUM_Grade {
	
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
		add_action( 'admin_post_dum_add_grade', array( $this, 'handle_add_grade' ) );
		add_action( 'admin_post_dum_edit_grade', array( $this, 'handle_edit_grade' ) );
		add_action( 'admin_post_dum_delete_grade', array( $this, 'handle_delete_grade' ) );
	}
	
	/**
	 * Get all grades
	 */
	public static function get_all( $args = array() ) {
		global $wpdb;
		$grades_table = $wpdb->prefix . 'dum_grades';
		$students_table = $wpdb->prefix . 'dum_students';
		$courses_table = $wpdb->prefix . 'dum_courses';
		
		$defaults = array(
			'student_id' => 0,
			'course_id' => 0,
			'status' => 'all',
			'limit' => 20,
			'offset' => 0,
			'orderby' => 'g.id',
			'order' => 'DESC',
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		$where_conditions = array();
		$where_values = array();
		
		if ( $args['student_id'] > 0 ) {
			$where_conditions[] = 'g.student_id = %d';
			$where_values[] = $args['student_id'];
		}
		
		if ( $args['course_id'] > 0 ) {
			$where_conditions[] = 'g.course_id = %d';
			$where_values[] = $args['course_id'];
		}
		
		if ( $args['status'] !== 'all' ) {
			$where_conditions[] = 'g.status = %s';
			$where_values[] = $args['status'];
		}
		
		$where_clause = '';
		if ( ! empty( $where_conditions ) ) {
			$where_clause = 'WHERE ' . implode( ' AND ', $where_conditions );
		}
		
		$orderby = sanitize_sql_orderby( $args['orderby'] . ' ' . $args['order'] );
		if ( ! $orderby ) {
			$orderby = 'g.id DESC';
		}
		
		// Select specific columns to avoid student_id conflict - use LEFT JOIN to show all grades
		$query = "SELECT g.id, g.enrollment_id, g.student_id as grade_student_id, g.course_id as grade_course_id,
				  g.midterm_marks, g.final_marks, g.assignment_marks, g.total_marks, 
				  g.grade, g.grade_point, g.status as grade_status, g.created_at, g.updated_at,
				  s.student_id, s.first_name as student_first_name, s.last_name as student_last_name,
				  c.course_code, c.course_name, c.credits
				  FROM $grades_table g
				  LEFT JOIN $students_table s ON g.student_id = s.id
				  LEFT JOIN $courses_table c ON g.course_id = c.id
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
			// error_log( 'Grade Query Error: ' . $wpdb->last_error );
			// error_log( 'Grade Query: ' . $query );
			return array();
		}
		
		// Ensure we return an array even if empty
		return $results ? $results : array();
	}
	
	/**
	 * Get grade by ID
	 */
	public static function get( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_grades';
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ) );
	}
	
	/**
	 * Get grade by enrollment ID
	 */
	public static function get_by_enrollment( $enrollment_id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_grades';
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE enrollment_id = %d", $enrollment_id ) );
	}
	
	/**
	 * Calculate total marks and grade
	 * Updated grade system: 80 and above = A+
	 */
	private static function calculate_grade( $midterm, $final, $assignment ) {
		$total = floatval( $midterm ) + floatval( $final ) + floatval( $assignment );
		
		// Updated grade calculation based on new scale
		// 80 and above = A+ (4.0)
		if ( $total >= 80 ) {
			$grade = 'A+';
			$grade_point = 4.0;
		} elseif ( $total >= 75 ) {
			$grade = 'A';
			$grade_point = 3.75;
		} elseif ( $total >= 70 ) {
			$grade = 'A-';
			$grade_point = 3.5;
		} elseif ( $total >= 65 ) {
			$grade = 'B+';
			$grade_point = 3.25;
		} elseif ( $total >= 60 ) {
			$grade = 'B';
			$grade_point = 3.0;
		} elseif ( $total >= 55 ) {
			$grade = 'B-';
			$grade_point = 2.75;
		} elseif ( $total >= 50 ) {
			$grade = 'C+';
			$grade_point = 2.5;
		} elseif ( $total >= 45 ) {
			$grade = 'C';
			$grade_point = 2.25;
		} elseif ( $total >= 40 ) {
			$grade = 'D';
			$grade_point = 2.0;
		} else {
			$grade = 'F';
			$grade_point = 0.0;
		}
		
		return array(
			'total' => $total,
			'grade' => $grade,
			'grade_point' => $grade_point,
		);
	}
	
	/**
	 * Add grade
	 */
	public static function add( $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_grades';
		$enrollments_table = $wpdb->prefix . 'dum_enrollments';
		
		$enrollment_id = intval( $data['enrollment_id'] );
		
		// Get student_id and course_id from enrollment
		$enrollment = $wpdb->get_row( $wpdb->prepare(
			"SELECT student_id, course_id FROM $enrollments_table WHERE id = %d",
			$enrollment_id
		) );
		
		if ( ! $enrollment ) {
			return false;
		}
		
		$midterm = floatval( $data['midterm_marks'] ?? 0 );
		$final = floatval( $data['final_marks'] ?? 0 );
		$assignment = floatval( $data['assignment_marks'] ?? 0 );
		
		$calculated = self::calculate_grade( $midterm, $final, $assignment );
		
		$insert_data = array(
			'enrollment_id' => $enrollment_id,
			'student_id' => intval( $enrollment->student_id ),
			'course_id' => intval( $enrollment->course_id ),
			'midterm_marks' => $midterm,
			'final_marks' => $final,
			'assignment_marks' => $assignment,
			'total_marks' => $calculated['total'],
			'grade' => $calculated['grade'],
			'grade_point' => $calculated['grade_point'],
			'status' => sanitize_text_field( $data['status'] ?? 'completed' ),
		);
		
		return $wpdb->insert( $table, $insert_data );
	}
	
	/**
	 * Update grade
	 */
	public static function update( $id, $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_grades';
		
		$midterm = floatval( $data['midterm_marks'] ?? 0 );
		$final = floatval( $data['final_marks'] ?? 0 );
		$assignment = floatval( $data['assignment_marks'] ?? 0 );
		
		$calculated = self::calculate_grade( $midterm, $final, $assignment );
		
		$data = array(
			'midterm_marks' => $midterm,
			'final_marks' => $final,
			'assignment_marks' => $assignment,
			'total_marks' => $calculated['total'],
			'grade' => $calculated['grade'],
			'grade_point' => $calculated['grade_point'],
			'status' => sanitize_text_field( $data['status'] ?? 'completed' ),
		);
		
		return $wpdb->update( $table, $data, array( 'id' => $id ) );
	}
	
	/**
	 * Delete grade
	 */
	public static function delete( $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'dum_grades';
		return $wpdb->delete( $table, array( 'id' => $id ) );
	}
	
	/**
	 * Handle add grade
	 */
	public function handle_add_grade() {
		check_admin_referer( 'dum_add_grade' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$result = self::add( $_POST );
		
		if ( $result ) {
			wp_redirect( admin_url( 'admin.php?page=dum-grades&message=added' ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=dum-grades&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle edit grade
	 */
	public function handle_edit_grade() {
		check_admin_referer( 'dum_edit_grade' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = intval( $_POST['grade_id'] );
		$result = self::update( $id, $_POST );
		
		if ( $result !== false ) {
			wp_redirect( admin_url( 'admin.php?page=dum-grades&message=updated' ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=dum-grades&message=error' ) );
		}
		exit;
	}
	
	/**
	 * Handle delete grade
	 */
	public function handle_delete_grade() {
		check_admin_referer( 'dum_delete_grade' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions.', 'dream-university-management' ) );
		}
		
		$id = intval( $_GET['id'] );
		$result = self::delete( $id );
		
		if ( $result ) {
			wp_redirect( admin_url( 'admin.php?page=dum-grades&message=deleted' ) );
		} else {
			wp_redirect( admin_url( 'admin.php?page=dum-grades&message=error' ) );
		}
		exit;
	}
}

