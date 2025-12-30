<?php
/**
 * CGPA calculation class
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CGPA class
 */
class DUM_CGPA {
	
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
		// Constructor
	}
	
	/**
	 * Calculate CGPA for a student
	 */
	public static function calculate( $student_id ) {
		global $wpdb;
		$grades_table = $wpdb->prefix . 'dum_grades';
		$courses_table = $wpdb->prefix . 'dum_courses';
		
		$query = "SELECT g.grade_point, c.credits
				  FROM $grades_table g
				  INNER JOIN $courses_table c ON g.course_id = c.id
				  WHERE g.student_id = %d AND g.status = 'completed'";
		
		$results = $wpdb->get_results( $wpdb->prepare( $query, $student_id ) );
		
		if ( empty( $results ) ) {
			return array(
				'cgpa' => 0.00,
				'total_credits' => 0,
				'total_grade_points' => 0,
			);
		}
		
		$total_grade_points = 0;
		$total_credits = 0;
		
		foreach ( $results as $result ) {
			$grade_point = floatval( $result->grade_point );
			$credits = floatval( $result->credits );
			
			$total_grade_points += ( $grade_point * $credits );
			$total_credits += $credits;
		}
		
		$cgpa = $total_credits > 0 ? ( $total_grade_points / $total_credits ) : 0.00;
		
		return array(
			'cgpa' => round( $cgpa, 2 ),
			'total_credits' => $total_credits,
			'total_grade_points' => $total_grade_points,
		);
	}
	
	/**
	 * Get student transcript
	 */
	public static function get_transcript( $student_id ) {
		global $wpdb;
		$grades_table = $wpdb->prefix . 'dum_grades';
		$courses_table = $wpdb->prefix . 'dum_courses';
		$students_table = $wpdb->prefix . 'dum_students';
		
		$query = "SELECT g.*, c.course_code, c.course_name, c.credits, c.semester,
				  s.student_id, s.first_name, s.last_name
				  FROM $grades_table g
				  INNER JOIN $courses_table c ON g.course_id = c.id
				  INNER JOIN $students_table s ON g.student_id = s.id
				  WHERE g.student_id = %d AND g.status = 'completed'
				  ORDER BY c.semester, c.course_code";
		
		return $wpdb->get_results( $wpdb->prepare( $query, $student_id ) );
	}
	
	/**
	 * Get all students with CGPA
	 */
	public static function get_all_students_cgpa() {
		global $wpdb;
		$students_table = $wpdb->prefix . 'dum_students';
		
		$students = $wpdb->get_results( "SELECT * FROM $students_table WHERE status = 'active'" );
		
		$results = array();
		foreach ( $students as $student ) {
			$cgpa_data = self::calculate( $student->id );
			$results[] = array(
				'student' => $student,
				'cgpa' => $cgpa_data['cgpa'],
				'total_credits' => $cgpa_data['total_credits'],
			);
		}
		
		// Sort by CGPA descending
		usort( $results, function( $a, $b ) {
			return $b['cgpa'] <=> $a['cgpa'];
		} );
		
		return $results;
	}
}

