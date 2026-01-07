<?php
/**
 * Admin class for Dream University Management
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin class
 */
class DUM_Admin {
	
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
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}
	
	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		// Main menu
		add_menu_page(
			__( 'University Management', 'dream-university-management' ),
			__( 'University', 'dream-university-management' ),
			'manage_options',
			'dream-university',
			array( $this, 'dashboard_page' ),
			'dashicons-welcome-learn-more',
			30
		);
		
		// Dashboard submenu
		add_submenu_page(
			'dream-university',
			__( 'Dashboard', 'dream-university-management' ),
			__( 'Dashboard', 'dream-university-management' ),
			'manage_options',
			'dream-university',
			array( $this, 'dashboard_page' )
		);
		
		// Faculties submenu
		add_submenu_page(
			'dream-university',
			__( 'Faculties', 'dream-university-management' ),
			__( 'Faculties', 'dream-university-management' ),
			'manage_options',
			'dum-faculties',
			array( $this, 'faculties_page' )
		);
		
		// Departments submenu
		add_submenu_page(
			'dream-university',
			__( 'Departments', 'dream-university-management' ),
			__( 'Departments', 'dream-university-management' ),
			'manage_options',
			'dum-departments',
			array( $this, 'departments_page' )
		);
		
		// Students submenu
		add_submenu_page(
			'dream-university',
			__( 'Students', 'dream-university-management' ),
			__( 'Students', 'dream-university-management' ),
			'manage_options',
			'dum-students',
			array( $this, 'students_page' )
		);
		
		// Teachers submenu
		add_submenu_page(
			'dream-university',
			__( 'Teachers', 'dream-university-management' ),
			__( 'Teachers', 'dream-university-management' ),
			'manage_options',
			'dum-teachers',
			array( $this, 'teachers_page' )
		);
		
		// Staff submenu
		add_submenu_page(
			'dream-university',
			__( 'Staff', 'dream-university-management' ),
			__( 'Staff', 'dream-university-management' ),
			'manage_options',
			'dum-staff',
			array( $this, 'staff_page' )
		);
		
		// Courses submenu
		add_submenu_page(
			'dream-university',
			__( 'Courses', 'dream-university-management' ),
			__( 'Courses', 'dream-university-management' ),
			'manage_options',
			'dum-courses',
			array( $this, 'courses_page' )
		);
		
		// Enrollments submenu
		add_submenu_page(
			'dream-university',
			__( 'Enrollments', 'dream-university-management' ),
			__( 'Enrollments', 'dream-university-management' ),
			'manage_options',
			'dum-enrollments',
			array( $this, 'enrollments_page' )
		);
		
		// Grades submenu
		add_submenu_page(
			'dream-university',
			__( 'Grades', 'dream-university-management' ),
			__( 'Grades', 'dream-university-management' ),
			'manage_options',
			'dum-grades',
			array( $this, 'grades_page' )
		);
		
		// CGPA submenu
		add_submenu_page(
			'dream-university',
			__( 'CGPA Calculator', 'dream-university-management' ),
			__( 'CGPA Calculator', 'dream-university-management' ),
			'manage_options',
			'dum-cgpa',
			array( $this, 'cgpa_page' )
		);
		
		// Reports submenu
		add_submenu_page(
			'dream-university',
			__( 'Reports', 'dream-university-management' ),
			__( 'Reports', 'dream-university-management' ),
			'manage_options',
			'dum-reports',
			array( $this, 'reports_page' )
		);
		
		// Shortcodes submenu
		add_submenu_page(
			'dream-university',
			__( 'Shortcodes', 'dream-university-management' ),
			__( 'Shortcodes', 'dream-university-management' ),
			'manage_options',
			'dum-shortcodes',
			array( $this, 'shortcodes_page' )
		);
		
		// Settings submenu
		add_submenu_page(
			'dream-university',
			__( 'Settings', 'dream-university-management' ),
			__( 'Settings', 'dream-university-management' ),
			'manage_options',
			'dum-settings',
			array( $this, 'settings_page' )
		);
	}
	
	/**
	 * Enqueue admin assets
	 */
	public function enqueue_admin_assets( $hook ) {
		// Only load on our plugin pages
		if ( strpos( $hook, 'dream-university' ) === false && strpos( $hook, 'dum-' ) === false ) {
			return;
		}
		
		wp_enqueue_style(
			'dum-admin-style',
			DUM_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			DUM_VERSION
		);
		
		wp_enqueue_script(
			'dum-admin-script',
			DUM_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery' ),
			DUM_VERSION,
			true
		);
		
		// Enqueue WordPress media uploader
		wp_enqueue_media();
		
		wp_localize_script( 'dum-admin-script', 'dumAdmin', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'dum-admin-nonce' ),
		) );
		
		// Make ajaxurl available globally for older scripts
		wp_add_inline_script( 'dum-admin-script', 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '";' );
	}
	
	/**
	 * Dashboard page
	 */
	public function dashboard_page() {
		include DUM_PLUGIN_DIR . 'admin/views/dashboard.php';
	}
	
	/**
	 * Faculties page
	 */
	public function faculties_page() {
		include DUM_PLUGIN_DIR . 'admin/views/faculties.php';
	}
	
	/**
	 * Departments page
	 */
	public function departments_page() {
		include DUM_PLUGIN_DIR . 'admin/views/departments.php';
	}
	
	/**
	 * Students page
	 */
	public function students_page() {
		include DUM_PLUGIN_DIR . 'admin/views/students.php';
	}
	
	/**
	 * Teachers page
	 */
	public function teachers_page() {
		include DUM_PLUGIN_DIR . 'admin/views/teachers.php';
	}
	
	/**
	 * Staff page
	 */
	public function staff_page() {
		include DUM_PLUGIN_DIR . 'admin/views/staff.php';
	}
	
	/**
	 * Courses page
	 */
	public function courses_page() {
		include DUM_PLUGIN_DIR . 'admin/views/courses.php';
	}
	
	/**
	 * Enrollments page
	 */
	public function enrollments_page() {
		include DUM_PLUGIN_DIR . 'admin/views/enrollments.php';
	}
	
	/**
	 * Grades page
	 */
	public function grades_page() {
		include DUM_PLUGIN_DIR . 'admin/views/grades.php';
	}
	
	/**
	 * CGPA page
	 */
	public function cgpa_page() {
		include DUM_PLUGIN_DIR . 'admin/views/cgpa.php';
	}
	
	/**
	 * Reports page
	 */
	public function reports_page() {
		include DUM_PLUGIN_DIR . 'admin/views/reports.php';
	}
	
	/**
	 * Shortcodes page
	 */
	public function shortcodes_page() {
		include DUM_PLUGIN_DIR . 'admin/views/shortcodes.php';
	}
	
	/**
	 * Settings page
	 */
	public function settings_page() {
		include DUM_PLUGIN_DIR . 'admin/views/settings.php';
	}
}

