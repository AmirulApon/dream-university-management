<?php
/**
 * Plugin Name: Dream University Management
 * Plugin URI: https://wordpress.org/plugins/dream-university-management
 * Description: A comprehensive university management system for WordPress. Manage students, teachers, staff, courses, enrollments, and calculate CGPA.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dream-university-management
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'DUM_VERSION', '1.0.0' );
define( 'DUM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DUM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DUM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

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
		// Load plugin textdomain
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		
		// Include required files
		$this->includes();
		
		// Initialize classes
		$this->init_classes();
		
		// Register activation and deactivation hooks
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	}
	
	/**
	 * Load plugin textdomain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'dream-university-management', false, dirname( DUM_PLUGIN_BASENAME ) . '/languages' );
	}
	
	/**
	 * Include required files
	 */
	private function includes() {
		require_once DUM_PLUGIN_DIR . 'includes/class-dum-database.php';
		require_once DUM_PLUGIN_DIR . 'includes/class-dum-admin.php';
		require_once DUM_PLUGIN_DIR . 'includes/class-dum-faculty.php';
		require_once DUM_PLUGIN_DIR . 'includes/class-dum-department.php';
		require_once DUM_PLUGIN_DIR . 'includes/class-dum-student.php';
		require_once DUM_PLUGIN_DIR . 'includes/class-dum-teacher.php';
		require_once DUM_PLUGIN_DIR . 'includes/class-dum-staff.php';
		require_once DUM_PLUGIN_DIR . 'includes/class-dum-course.php';
		require_once DUM_PLUGIN_DIR . 'includes/class-dum-enrollment.php';
		require_once DUM_PLUGIN_DIR . 'includes/class-dum-grade.php';
		require_once DUM_PLUGIN_DIR . 'includes/class-dum-cgpa.php';
	}
	
	/**
	 * Initialize classes
	 */
	private function init_classes() {
		// Initialize database
		DUM_Database::get_instance();
		
		// Initialize admin
		if ( is_admin() ) {
			DUM_Admin::get_instance();
		}
		
		// Initialize management classes
		DUM_Faculty::get_instance();
		DUM_Department::get_instance();
		DUM_Student::get_instance();
		DUM_Teacher::get_instance();
		DUM_Staff::get_instance();
		DUM_Course::get_instance();
		DUM_Enrollment::get_instance();
		DUM_Grade::get_instance();
		DUM_CGPA::get_instance();
		
		// Add AJAX handlers
		add_action( 'wp_ajax_dum_get_departments', array( $this, 'ajax_get_departments' ) );
	}
	
	/**
	 * AJAX handler to get departments by faculty
	 */
	public function ajax_get_departments() {
		check_ajax_referer( 'dum_get_departments', 'nonce' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'dream-university-management' ) ) );
		}
		
		$faculty_id = intval( $_POST['faculty_id'] ?? 0 );
		
		if ( $faculty_id <= 0 ) {
			wp_send_json_error( array( 'message' => __( 'Invalid faculty ID.', 'dream-university-management' ) ) );
		}
		
		$departments = DUM_Department::get_by_faculty( $faculty_id, 'active' );
		
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
		DUM_Database::create_tables();
		
		// Set default options
		add_option( 'dum_version', DUM_VERSION );
		add_option( 'dum_db_version', '1.0' );
		
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
function dum_init() {
	return Dream_University_Management::get_instance();
}

// Start the plugin
dum_init();

