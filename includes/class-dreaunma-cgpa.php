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
class DREAUNMA_CGPA {
	
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
		$grades_table = $wpdb->prefix . 'dreaunma_grades';
		$courses_table = $wpdb->prefix . 'dreaunma_courses';
		
		$query = "SELECT g.grade_point, c.credits
				  FROM $grades_table g
				  INNER JOIN $courses_table c ON g.course_id = c.id
				  WHERE g.student_id = %d AND g.status = 'completed'";
		
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Query is prepared below, table names are safe (from $wpdb->prefix)
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
		$grades_table = $wpdb->prefix . 'dreaunma_grades';
		$courses_table = $wpdb->prefix . 'dreaunma_courses';
		$students_table = $wpdb->prefix . 'dreaunma_students';
		
		$query = "SELECT g.*, c.course_code, c.course_name, c.credits, c.semester,
				  s.student_id, s.first_name, s.last_name
				  FROM $grades_table g
				  INNER JOIN $courses_table c ON g.course_id = c.id
				  INNER JOIN $students_table s ON g.student_id = s.id
				  WHERE g.student_id = %d AND g.status = 'completed'
				  ORDER BY c.semester, c.course_code";
		
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Query is prepared below, table names are safe (from $wpdb->prefix)
		return $wpdb->get_results( $wpdb->prepare( $query, $student_id ) );
	}
	
	/**
	 * Get all students with CGPA, optionally filtered by faculty, department, course, session, or semester
	 */
	public static function get_all_students_cgpa( $faculty_id = 0, $department_id = 0, $course_id = 0, $session = '', $semester = '' ) {
		global $wpdb;
		$students_table = $wpdb->prefix . 'dreaunma_students';
		
		$where = array( "status = 'active'" );
		$params = array();
		
		if ( $faculty_id > 0 ) {
			$where[] = 'faculty_id = %d';
			$params[] = $faculty_id;
		}
		
		if ( $department_id > 0 ) {
			$where[] = 'department_id = %d';
			$params[] = $department_id;
		}

		if ( ! empty( $session ) ) {
			$where[] = 'session = %s';
			$params[] = $session;
		}
		
		$where_clause = implode( ' AND ', $where );
		$query = "SELECT * FROM $students_table WHERE $where_clause";
		
		if ( ! empty( $params ) ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared -- The query string is safely built and then prepared.
			$students = $wpdb->get_results( $wpdb->prepare( $query, $params ) );
		} else {
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table name is safe (from $wpdb->prefix), no user input in query
			$students = $wpdb->get_results( $query );
		}
		
		$results = array();
		foreach ( $students as $student ) {
			if ( $course_id > 0 || ! empty( $semester ) ) {
				// If filtering by course or semester, calculate GPA only for those specific conditions
				$grades_table = $wpdb->prefix . 'dreaunma_grades';
				$courses_table = $wpdb->prefix . 'dreaunma_courses';
				
				$course_where = array( "g.student_id = %d", "g.status = 'completed'" );
				$course_params = array( $student->id );
				
				if ( $course_id > 0 ) {
					$course_where[] = "g.course_id = %d";
					$course_params[] = $course_id;
				}
				
				if ( ! empty( $semester ) ) {
					$course_where[] = "c.semester = %s";
					$course_params[] = $semester;
				}

				$course_where_clause = implode( ' AND ', $course_where );

				$course_query = "SELECT g.grade_point, c.credits
						  FROM $grades_table g
						  INNER JOIN $courses_table c ON g.course_id = c.id
						  WHERE $course_where_clause";
				
				// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$course_results = $wpdb->get_results( $wpdb->prepare( $course_query, ...$course_params ) );
				
				if ( empty( $course_results ) ) {
					continue; // Skip student if they haven't completed any relevant courses
				}
				
				$total_grade_points = 0;
				$total_credits = 0;
				
				foreach ( $course_results as $result ) {
					$grade_point = floatval( $result->grade_point );
					$credits = floatval( $result->credits );
					
					$total_grade_points += ( $grade_point * $credits );
					$total_credits += $credits;
				}
				
				$cgpa = $total_credits > 0 ? ( $total_grade_points / $total_credits ) : 0.00;
				$cgpa_data = array(
					'cgpa' => round( $cgpa, 2 ),
					'total_credits' => $total_credits,
				);
			} else {
				$cgpa_data = self::calculate( $student->id );
			}

			// Only include students who have some credits
			if ( $cgpa_data['total_credits'] > 0 ) {
				$results[] = array(
					'student' => $student,
					'cgpa' => $cgpa_data['cgpa'],
					'total_credits' => $cgpa_data['total_credits'],
				);
			}
		}
		
		// Sort by CGPA descending
		usort( $results, function( $a, $b ) {
			return $b['cgpa'] <=> $a['cgpa'];
		} );
		
		return $results;
	}
}

