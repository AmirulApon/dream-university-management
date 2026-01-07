<?php
/**
 * Frontend class for displaying university data
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend class
 */
class DUM_Frontend {
	
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
		// Register shortcodes
		add_shortcode( 'dum_faculties', array( $this, 'shortcode_faculties' ) );
		add_shortcode( 'dum_departments', array( $this, 'shortcode_departments' ) );
		add_shortcode( 'dum_students', array( $this, 'shortcode_students' ) );
		add_shortcode( 'dum_teachers', array( $this, 'shortcode_teachers' ) );
		add_shortcode( 'dum_staff', array( $this, 'shortcode_staff' ) );
		add_shortcode( 'dum_courses', array( $this, 'shortcode_courses' ) );
		add_shortcode( 'dum_grades', array( $this, 'shortcode_grades' ) );
		add_shortcode( 'dum_cgpa', array( $this, 'shortcode_cgpa' ) );
		
		// Enqueue frontend assets
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
	}
	
	/**
	 * Enqueue frontend assets
	 */
	public function enqueue_frontend_assets() {
		wp_enqueue_style(
			'dum-frontend-style',
			DUM_PLUGIN_URL . 'assets/css/public.css',
			array(),
			DUM_VERSION
		);
	}
	
	/**
	 * Faculties shortcode
	 */
	public function shortcode_faculties( $atts ) {
		$atts = shortcode_atts( array(
			'status' => 'active',
			'limit' => 0,
			'columns' => 3,
		), $atts, 'dum_faculties' );
		
		$args = array(
			'status' => sanitize_text_field( $atts['status'] ),
			'limit' => intval( $atts['limit'] ),
		);
		
		$faculties = DUM_Faculty::get_all( $args );
		
		ob_start();
		include DUM_PLUGIN_DIR . 'public/views/faculties.php';
		return ob_get_clean();
	}
	
	/**
	 * Departments shortcode
	 */
	public function shortcode_departments( $atts ) {
		$atts = shortcode_atts( array(
			'faculty_id' => 0,
			'status' => 'active',
			'limit' => 0,
		), $atts, 'dum_departments' );
		
		$faculty_id = intval( $atts['faculty_id'] );
		
		if ( $faculty_id > 0 ) {
			$departments = DUM_Department::get_by_faculty( $faculty_id, sanitize_text_field( $atts['status'] ) );
		} else {
			$args = array(
				'status' => sanitize_text_field( $atts['status'] ),
				'limit' => intval( $atts['limit'] ),
			);
			$departments = DUM_Department::get_all( $args );
		}
		
		ob_start();
		include DUM_PLUGIN_DIR . 'public/views/departments.php';
		return ob_get_clean();
	}
	
	/**
	 * Students shortcode
	 */
	public function shortcode_students( $atts ) {
		$atts = shortcode_atts( array(
			'faculty_id' => 0,
			'department_id' => 0,
			'status' => 'active',
			'limit' => 0,
			'columns' => 3,
		), $atts, 'dum_students' );
		
		$args = array(
			'status' => sanitize_text_field( $atts['status'] ),
			'limit' => intval( $atts['limit'] ),
		);
		
		if ( intval( $atts['faculty_id'] ) > 0 ) {
			$args['faculty_id'] = intval( $atts['faculty_id'] );
		}
		
		if ( intval( $atts['department_id'] ) > 0 ) {
			$args['department_id'] = intval( $atts['department_id'] );
		}
		
		$students = DUM_Student::get_all( $args );
		
		ob_start();
		include DUM_PLUGIN_DIR . 'public/views/students.php';
		return ob_get_clean();
	}
	
	/**
	 * Teachers shortcode
	 */
	public function shortcode_teachers( $atts ) {
		$atts = shortcode_atts( array(
			'faculty_id' => 0,
			'department_id' => 0,
			'status' => 'active',
			'limit' => 0,
			'columns' => 3,
		), $atts, 'dum_teachers' );
		
		$args = array(
			'status' => sanitize_text_field( $atts['status'] ),
			'limit' => intval( $atts['limit'] ),
		);
		
		if ( intval( $atts['faculty_id'] ) > 0 ) {
			$args['faculty_id'] = intval( $atts['faculty_id'] );
		}
		
		if ( intval( $atts['department_id'] ) > 0 ) {
			$args['department_id'] = intval( $atts['department_id'] );
		}
		
		$teachers = DUM_Teacher::get_all( $args );
		
		ob_start();
		include DUM_PLUGIN_DIR . 'public/views/teachers.php';
		return ob_get_clean();
	}
	
	/**
	 * Staff shortcode
	 */
	public function shortcode_staff( $atts ) {
		$atts = shortcode_atts( array(
			'status' => 'active',
			'limit' => 0,
			'columns' => 3,
		), $atts, 'dum_staff' );
		
		$args = array(
			'status' => sanitize_text_field( $atts['status'] ),
			'limit' => intval( $atts['limit'] ),
		);
		
		$staff = DUM_Staff::get_all( $args );
		
		ob_start();
		include DUM_PLUGIN_DIR . 'public/views/staff.php';
		return ob_get_clean();
	}
	
	/**
	 * Courses shortcode
	 */
	public function shortcode_courses( $atts ) {
		$atts = shortcode_atts( array(
			'faculty_id' => 0,
			'department_id' => 0,
			'status' => 'active',
			'limit' => 0,
		), $atts, 'dum_courses' );
		
		$args = array(
			'status' => sanitize_text_field( $atts['status'] ),
			'limit' => intval( $atts['limit'] ),
		);
		
		if ( intval( $atts['faculty_id'] ) > 0 ) {
			$args['faculty_id'] = intval( $atts['faculty_id'] );
		}
		
		if ( intval( $atts['department_id'] ) > 0 ) {
			$args['department_id'] = intval( $atts['department_id'] );
		}
		
		$courses = DUM_Course::get_all( $args );
		
		ob_start();
		include DUM_PLUGIN_DIR . 'public/views/courses.php';
		return ob_get_clean();
	}
	
	/**
	 * Grades shortcode
	 */
	public function shortcode_grades( $atts ) {
		$atts = shortcode_atts( array(
			'student_id' => 0,
			'course_id' => 0,
			'limit' => 0,
		), $atts, 'dum_grades' );
		
		$args = array();
		
		if ( intval( $atts['student_id'] ) > 0 ) {
			$args['student_id'] = intval( $atts['student_id'] );
		}
		
		if ( intval( $atts['course_id'] ) > 0 ) {
			$args['course_id'] = intval( $atts['course_id'] );
		}
		
		if ( intval( $atts['limit'] ) > 0 ) {
			$args['limit'] = intval( $atts['limit'] );
		}
		
		$grades = DUM_Grade::get_all( $args );
		
		ob_start();
		include DUM_PLUGIN_DIR . 'public/views/grades.php';
		return ob_get_clean();
	}
	
	/**
	 * CGPA shortcode
	 */
	public function shortcode_cgpa( $atts ) {
		$atts = shortcode_atts( array(
			'student_id' => 0,
		), $atts, 'dum_cgpa' );
		
		$student_id = intval( $atts['student_id'] );
		
		if ( $student_id <= 0 ) {
			return '<p>' . esc_html__( 'Please provide a valid student ID.', 'dream-university-management' ) . '</p>';
		}
		
		$student = DUM_Student::get( $student_id );
		
		if ( ! $student ) {
			return '<p>' . esc_html__( 'Student not found.', 'dream-university-management' ) . '</p>';
		}
		
		$cgpa_data = DUM_CGPA::calculate( $student_id );
		$transcript = DUM_CGPA::get_transcript( $student_id );
		
		ob_start();
		include DUM_PLUGIN_DIR . 'public/views/cgpa.php';
		return ob_get_clean();
	}
}

