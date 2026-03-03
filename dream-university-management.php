<?php
/**
 * Plugin Name: Dream University Management
 * Plugin URI: https://wordpress.org/plugins/dream-university-management
 * Description: A comprehensive university management system for WordPress. Manage students, teachers, staff, courses, enrollments, and calculate CGPA.
 * Version: 1.0.1
 * Author: Dream Carnival
 * Author URI: https://profiles.wordpress.org/dreamscarnival/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dream-university-management
 * Domain Path: /languages
 * Requires at least: 6.3
 * Tested up to: 6.9
 * Requires PHP: 7.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'DREAUNMA_VERSION', '1.0.1' );
define( 'DREAUNMA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DREAUNMA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DREAUNMA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main plugin class
 */
class Dream_University_Management {
	
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
		$this->init();
	}
	
	/**
	 * Initialize plugin
	 */
	private function init() {
		
		// Include required files
		$this->includes();
		
		// Initialize classes
		$this->init_classes();
		
		// Register activation and deactivation hooks
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	}
	
	/**
	 * Include required files
	 */
	private function includes() {
		require_once DREAUNMA_PLUGIN_DIR . 'includes/class-dreaunma-database.php';
		require_once DREAUNMA_PLUGIN_DIR . 'includes/class-dreaunma-admin.php';
		require_once DREAUNMA_PLUGIN_DIR . 'includes/class-dreaunma-faculty.php';
		require_once DREAUNMA_PLUGIN_DIR . 'includes/class-dreaunma-department.php';
		require_once DREAUNMA_PLUGIN_DIR . 'includes/class-dreaunma-student.php';
		require_once DREAUNMA_PLUGIN_DIR . 'includes/class-dreaunma-teacher.php';
		require_once DREAUNMA_PLUGIN_DIR . 'includes/class-dreaunma-staff.php';
		require_once DREAUNMA_PLUGIN_DIR . 'includes/class-dreaunma-course.php';
		require_once DREAUNMA_PLUGIN_DIR . 'includes/class-dreaunma-enrollment.php';
		require_once DREAUNMA_PLUGIN_DIR . 'includes/class-dreaunma-grade.php';
		require_once DREAUNMA_PLUGIN_DIR . 'includes/class-dreaunma-cgpa.php';
		require_once DREAUNMA_PLUGIN_DIR . 'includes/class-dreaunma-frontend.php';
	}
	
	/**
	 * Initialize classes
	 */
	private function init_classes() {
		// Initialize database
		DREAUNMA_Database::get_instance();
		
		// Initialize admin
		if ( is_admin() ) {
			DREAUNMA_Admin::get_instance();
		}
		
		// Initialize frontend
		DREAUNMA_Frontend::get_instance();
		
		// Initialize management classes
		DREAUNMA_Faculty::get_instance();
		DREAUNMA_Department::get_instance();
		DREAUNMA_Student::get_instance();
		DREAUNMA_Teacher::get_instance();
		DREAUNMA_Staff::get_instance();
		DREAUNMA_Course::get_instance();
		DREAUNMA_Enrollment::get_instance();
		DREAUNMA_Grade::get_instance();
		DREAUNMA_CGPA::get_instance();
		
		// Add AJAX handlers
		add_action( 'wp_ajax_dreaunma_get_departments', array( $this, 'ajax_get_departments' ) );
	}
	
	/**
	 * AJAX handler to get departments by faculty
	 */
	public function ajax_get_departments() {
		check_ajax_referer( 'dreaunma-admin-nonce', 'nonce' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'dream-university-management' ) ) );
		}
		
		$faculty_id = isset( $_POST['faculty_id'] ) ? intval( $_POST['faculty_id'] ) : 0;
		
		if ( $faculty_id <= 0 ) {
			wp_send_json_error( array( 'message' => __( 'Invalid faculty ID.', 'dream-university-management' ) ) );
		}
		
		$departments = DREAUNMA_Department::get_by_faculty( $faculty_id, 'active' );
		
		$data = array();
		foreach ( $departments as $dept ) {
			$data[] = array(
				'id' => $dept->id,
				'department_code' => $dept->department_code,
				'department_name' => $dept->department_name,
			);
		}
		
		wp_send_json_success( $data );
	}
	
	/**
	 * Plugin activation
	 */
	public function activate() {
		// Create database tables
		DREAUNMA_Database::create_tables();
		
		// Set default options
		add_option( 'dreaunma_version', DREAUNMA_VERSION );
		add_option( 'dreaunma_db_version', '1.0' );
		
		// Flush rewrite rules
		flush_rewrite_rules();
	}
	
	/**
	 * Plugin deactivation
	 */
	public function deactivate() {
		// Flush rewrite rules
		flush_rewrite_rules();
	}
}

/**
 * Initialize the plugin
 */
function dreaunma_init() {
	return Dream_University_Management::get_instance();
}

// Start the plugin
dreaunma_init();

