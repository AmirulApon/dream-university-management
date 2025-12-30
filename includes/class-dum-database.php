<?php
/**
 * Database class for Dream University Management
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Database class
 */
class DUM_Database {
	
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
	 * Create database tables
	 */
	public static function create_tables() {
		global $wpdb;
		
		$charset_collate = $wpdb->get_charset_collate();
		
		// Students table
		$table_students = $wpdb->prefix . 'dum_students';
		$sql_students = "CREATE TABLE IF NOT EXISTS $table_students (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			student_id varchar(50) NOT NULL,
			first_name varchar(100) NOT NULL,
			last_name varchar(100) NOT NULL,
			email varchar(100) NOT NULL,
			phone varchar(20) DEFAULT NULL,
			date_of_birth date DEFAULT NULL,
			gender varchar(10) DEFAULT NULL,
			address text DEFAULT NULL,
			image varchar(255) DEFAULT NULL,
			admission_date date DEFAULT NULL,
			status varchar(20) DEFAULT 'active',
			user_id bigint(20) UNSIGNED DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY student_id (student_id),
			UNIQUE KEY email (email),
			KEY user_id (user_id)
		) $charset_collate;";
		
		// Teachers table
		$table_teachers = $wpdb->prefix . 'dum_teachers';
		$sql_teachers = "CREATE TABLE IF NOT EXISTS $table_teachers (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			teacher_id varchar(50) NOT NULL,
			first_name varchar(100) NOT NULL,
			last_name varchar(100) NOT NULL,
			email varchar(100) NOT NULL,
			phone varchar(20) DEFAULT NULL,
			date_of_birth date DEFAULT NULL,
			gender varchar(10) DEFAULT NULL,
			address text DEFAULT NULL,
			image varchar(255) DEFAULT NULL,
			department varchar(100) DEFAULT NULL,
			designation varchar(100) DEFAULT NULL,
			hire_date date DEFAULT NULL,
			status varchar(20) DEFAULT 'active',
			user_id bigint(20) UNSIGNED DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY teacher_id (teacher_id),
			UNIQUE KEY email (email),
			KEY user_id (user_id)
		) $charset_collate;";
		
		// Staff table
		$table_staff = $wpdb->prefix . 'dum_staff';
		$sql_staff = "CREATE TABLE IF NOT EXISTS $table_staff (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			staff_id varchar(50) NOT NULL,
			first_name varchar(100) NOT NULL,
			last_name varchar(100) NOT NULL,
			email varchar(100) NOT NULL,
			phone varchar(20) DEFAULT NULL,
			date_of_birth date DEFAULT NULL,
			gender varchar(10) DEFAULT NULL,
			address text DEFAULT NULL,
			image varchar(255) DEFAULT NULL,
			department varchar(100) DEFAULT NULL,
			position varchar(100) DEFAULT NULL,
			hire_date date DEFAULT NULL,
			status varchar(20) DEFAULT 'active',
			user_id bigint(20) UNSIGNED DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY staff_id (staff_id),
			UNIQUE KEY email (email),
			KEY user_id (user_id)
		) $charset_collate;";
		
		// Courses table
		$table_courses = $wpdb->prefix . 'dum_courses';
		$sql_courses = "CREATE TABLE IF NOT EXISTS $table_courses (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			course_code varchar(50) NOT NULL,
			course_name varchar(200) NOT NULL,
			description text DEFAULT NULL,
			credits decimal(3,2) DEFAULT 0.00,
			department varchar(100) DEFAULT NULL,
			semester varchar(20) DEFAULT NULL,
			teacher_id bigint(20) UNSIGNED DEFAULT NULL,
			status varchar(20) DEFAULT 'active',
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY course_code (course_code),
			KEY teacher_id (teacher_id)
		) $charset_collate;";
		
		// Enrollments table
		$table_enrollments = $wpdb->prefix . 'dum_enrollments';
		$sql_enrollments = "CREATE TABLE IF NOT EXISTS $table_enrollments (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			student_id bigint(20) UNSIGNED NOT NULL,
			course_id bigint(20) UNSIGNED NOT NULL,
			enrollment_date date DEFAULT NULL,
			status varchar(20) DEFAULT 'enrolled',
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY student_course (student_id, course_id),
			KEY student_id (student_id),
			KEY course_id (course_id)
		) $charset_collate;";
		
		// Grades table
		$table_grades = $wpdb->prefix . 'dum_grades';
		$sql_grades = "CREATE TABLE IF NOT EXISTS $table_grades (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			enrollment_id bigint(20) UNSIGNED NOT NULL,
			student_id bigint(20) UNSIGNED NOT NULL,
			course_id bigint(20) UNSIGNED NOT NULL,
			midterm_marks decimal(5,2) DEFAULT 0.00,
			final_marks decimal(5,2) DEFAULT 0.00,
			assignment_marks decimal(5,2) DEFAULT 0.00,
			total_marks decimal(5,2) DEFAULT 0.00,
			grade varchar(5) DEFAULT NULL,
			grade_point decimal(3,2) DEFAULT 0.00,
			status varchar(20) DEFAULT 'pending',
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY enrollment_id (enrollment_id),
			KEY student_id (student_id),
			KEY course_id (course_id)
		) $charset_collate;";
		
		// Faculties table
		$table_faculties = $wpdb->prefix . 'dum_faculties';
		$sql_faculties = "CREATE TABLE IF NOT EXISTS $table_faculties (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			faculty_code varchar(50) NOT NULL,
			faculty_name varchar(200) NOT NULL,
			description text DEFAULT NULL,
			status varchar(20) DEFAULT 'active',
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY faculty_code (faculty_code)
		) $charset_collate;";
		
		// Departments table
		$table_departments = $wpdb->prefix . 'dum_departments';
		$sql_departments = "CREATE TABLE IF NOT EXISTS $table_departments (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			department_code varchar(50) NOT NULL,
			department_name varchar(200) NOT NULL,
			faculty_id bigint(20) UNSIGNED NOT NULL,
			description text DEFAULT NULL,
			status varchar(20) DEFAULT 'active',
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY department_code (department_code),
			KEY faculty_id (faculty_id)
		) $charset_collate;";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		dbDelta( $sql_students );
		dbDelta( $sql_teachers );
		dbDelta( $sql_staff );
		dbDelta( $sql_courses );
		dbDelta( $sql_enrollments );
		dbDelta( $sql_grades );
		dbDelta( $sql_faculties );
		dbDelta( $sql_departments );
		
		// Update existing tables to add faculty_id and department_id
		// Check if columns exist before adding
		$students_table = $wpdb->prefix . 'dum_students';
		$teachers_table = $wpdb->prefix . 'dum_teachers';
		$courses_table = $wpdb->prefix . 'dum_courses';
		
		// Add faculty_id and department_id to students table
		$column_exists = $wpdb->get_results( "SHOW COLUMNS FROM $students_table LIKE 'faculty_id'" );
		if ( empty( $column_exists ) ) {
			$wpdb->query( "ALTER TABLE $students_table ADD faculty_id bigint(20) UNSIGNED DEFAULT NULL AFTER address, ADD department_id bigint(20) UNSIGNED DEFAULT NULL AFTER faculty_id" );
			$wpdb->query( "ALTER TABLE $students_table ADD KEY faculty_id (faculty_id), ADD KEY department_id (department_id)" );
		}
		
		// Add image column to students table
		$image_column_exists = $wpdb->get_results( "SHOW COLUMNS FROM $students_table LIKE 'image'" );
		if ( empty( $image_column_exists ) ) {
			$wpdb->query( "ALTER TABLE $students_table ADD image varchar(255) DEFAULT NULL AFTER address" );
		}
		
		// Add faculty_id and department_id to teachers table
		$column_exists = $wpdb->get_results( "SHOW COLUMNS FROM $teachers_table LIKE 'faculty_id'" );
		if ( empty( $column_exists ) ) {
			$wpdb->query( "ALTER TABLE $teachers_table ADD faculty_id bigint(20) UNSIGNED DEFAULT NULL AFTER address, ADD department_id bigint(20) UNSIGNED DEFAULT NULL AFTER faculty_id" );
			$wpdb->query( "ALTER TABLE $teachers_table ADD KEY faculty_id (faculty_id), ADD KEY department_id (department_id)" );
		}
		
		// Add image column to teachers table
		$image_column_exists = $wpdb->get_results( "SHOW COLUMNS FROM $teachers_table LIKE 'image'" );
		if ( empty( $image_column_exists ) ) {
			$wpdb->query( "ALTER TABLE $teachers_table ADD image varchar(255) DEFAULT NULL AFTER address" );
		}
		
		// Add faculty_id and department_id to courses table
		$column_exists = $wpdb->get_results( "SHOW COLUMNS FROM $courses_table LIKE 'faculty_id'" );
		if ( empty( $column_exists ) ) {
			$wpdb->query( "ALTER TABLE $courses_table ADD faculty_id bigint(20) UNSIGNED DEFAULT NULL AFTER department, ADD department_id bigint(20) UNSIGNED DEFAULT NULL AFTER faculty_id" );
			$wpdb->query( "ALTER TABLE $courses_table ADD KEY faculty_id (faculty_id), ADD KEY department_id (department_id)" );
		}
	}
}

