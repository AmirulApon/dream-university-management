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
class DREAUNMA_Admin {
	
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
			'dreaunma-faculties',
			array( $this, 'faculties_page' )
		);
		
		// Departments submenu
		add_submenu_page(
			'dream-university',
			__( 'Departments', 'dream-university-management' ),
			__( 'Departments', 'dream-university-management' ),
			'manage_options',
			'dreaunma-departments',
			array( $this, 'departments_page' )
		);
		
		// Students submenu
		add_submenu_page(
			'dream-university',
			__( 'Students', 'dream-university-management' ),
			__( 'Students', 'dream-university-management' ),
			'manage_options',
			'dreaunma-students',
			array( $this, 'students_page' )
		);
		
		// Teachers submenu
		add_submenu_page(
			'dream-university',
			__( 'Teachers', 'dream-university-management' ),
			__( 'Teachers', 'dream-university-management' ),
			'manage_options',
			'dreaunma-teachers',
			array( $this, 'teachers_page' )
		);
		
		// Staff submenu
		add_submenu_page(
			'dream-university',
			__( 'Staff', 'dream-university-management' ),
			__( 'Staff', 'dream-university-management' ),
			'manage_options',
			'dreaunma-staff',
			array( $this, 'staff_page' )
		);
		
		// Courses submenu
		add_submenu_page(
			'dream-university',
			__( 'Courses', 'dream-university-management' ),
			__( 'Courses', 'dream-university-management' ),
			'manage_options',
			'dreaunma-courses',
			array( $this, 'courses_page' )
		);
		
		// Enrollments submenu
		add_submenu_page(
			'dream-university',
			__( 'Enrollments', 'dream-university-management' ),
			__( 'Enrollments', 'dream-university-management' ),
			'manage_options',
			'dreaunma-enrollments',
			array( $this, 'enrollments_page' )
		);
		
		// Grades submenu
		add_submenu_page(
			'dream-university',
			__( 'Grades', 'dream-university-management' ),
			__( 'Grades', 'dream-university-management' ),
			'manage_options',
			'dreaunma-grades',
			array( $this, 'grades_page' )
		);
		
		// CGPA submenu
		add_submenu_page(
			'dream-university',
			__( 'CGPA Calculator', 'dream-university-management' ),
			__( 'CGPA Calculator', 'dream-university-management' ),
			'manage_options',
			'dreaunma-cgpa',
			array( $this, 'cgpa_page' )
		);
		
		// Reports submenu
		add_submenu_page(
			'dream-university',
			__( 'Reports', 'dream-university-management' ),
			__( 'Reports', 'dream-university-management' ),
			'manage_options',
			'dreaunma-reports',
			array( $this, 'reports_page' )
		);
		
		// Shortcodes submenu
		add_submenu_page(
			'dream-university',
			__( 'Shortcodes', 'dream-university-management' ),
			__( 'Shortcodes', 'dream-university-management' ),
			'manage_options',
			'dreaunma-shortcodes',
			array( $this, 'shortcodes_page' )
		);
		
		// Settings submenu
		add_submenu_page(
			'dream-university',
			__( 'Settings', 'dream-university-management' ),
			__( 'Settings', 'dream-university-management' ),
			'manage_options',
			'dreaunma-settings',
			array( $this, 'settings_page' )
		);
	}
	
	/**
	 * Enqueue admin assets
	 */
	public function enqueue_admin_assets( $hook ) {
		// Only load on our plugin pages
		if ( strpos( $hook, 'dream-university' ) === false && strpos( $hook, 'dreaunma-' ) === false ) {
			return;
		}
		
		// DataTables CSS
		wp_enqueue_style( 'datatables', 'https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.css', array(), '1.13.6' );

		wp_enqueue_style(
			'dreaunma-admin-style',
			DREAUNMA_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			DREAUNMA_VERSION
		);
		
		// DataTables JS
		wp_enqueue_script( 'pdfmake', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js', array(), '0.2.7', true );
		wp_enqueue_script( 'vfs_fonts', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js', array('pdfmake'), '0.2.7', true );
		wp_enqueue_script( 'datatables', 'https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.js', array('jquery'), '1.13.6', true );

		wp_enqueue_script(
			'dreaunma-admin-script',
			DREAUNMA_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery', 'datatables' ),
			DREAUNMA_VERSION,
			true
		);
		
		// Enqueue WordPress media uploader
		wp_enqueue_media();
		
		wp_localize_script( 'dreaunma-admin-script', 'dreaunmaAdmin', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'dreaunma-admin-nonce' ),
			'i18n' => array(
				'loading' => __( 'Loading...', 'dream-university-management' ),
				'selectDepartment' => __( 'Select Department', 'dream-university-management' ),
				'remove' => __( 'Remove', 'dream-university-management' ),
				'minOneGrade' => __( 'You must have at least one grade setting.', 'dream-university-management' ),
			),
		) );
		
		// Make ajaxurl available globally for older scripts
		wp_add_inline_script( 'dreaunma-admin-script', 'var ajaxurl = "' . esc_js( admin_url( 'admin-ajax.php' ) ) . '";', 'before' );
		
		// Add page-specific inline scripts
		$this->add_page_specific_scripts( $hook );
	}
	
	/**
	 * Add page-specific inline scripts
	 */
	private function add_page_specific_scripts( $hook ) {
		// Dashboard copy-to-clipboard script
		if ( strpos( $hook, 'dream-university' ) !== false || strpos( $hook, 'dreaunma-shortcodes' ) !== false ) {
			$copy_script = "
(function($) {
	$(document).ready(function() {
		$('.dum-copy-btn').on('click', function(e) {
			e.preventDefault();
			var targetId = $(this).data('copy');
			var codeElement = $('#' + targetId);
			var text = codeElement.text();
			
			// Create temporary textarea to copy text
			var temp = $('<textarea>');
			$('body').append(temp);
			temp.val(text).select();
			document.execCommand('copy');
			temp.remove();
			
			// Show feedback
			var btn = $(this);
			var originalHtml = btn.html();
			btn.html('<span class=\"dashicons dashicons-yes-alt\"></span>');
			btn.css('color', '#00a32a');
			
			setTimeout(function() {
				btn.html(originalHtml);
				btn.css('color', '');
			}, 2000);
		});
	});
})(jQuery);
			";
			wp_add_inline_script( 'dreaunma-admin-script', $copy_script );
		}
		
		// Settings page grade management script
		if ( strpos( $hook, 'dreaunma-settings' ) !== false ) {
			// Get current grade settings count
			$grade_settings = get_option( 'dreaunma_grade_settings', array() );
			$grade_count = count( $grade_settings );
			
			$settings_script = "
(function($) {
	$(document).ready(function() {
		var gradeIndex = " . intval( $grade_count ) . ";
		
		// Add new grade row
		$('#add-grade-row').on('click', function() {
			var newRow = '<tr>' +
				'<td><input type=\"text\" name=\"grades[' + gradeIndex + '][grade]\" class=\"regular-text\" required style=\"width: 100%;\"></td>' +
				'<td><input type=\"number\" name=\"grades[' + gradeIndex + '][grade_point]\" step=\"0.01\" min=\"0\" max=\"4\" class=\"small-text\" required style=\"width: 100%;\"></td>' +
				'<td><input type=\"number\" name=\"grades[' + gradeIndex + '][min_percentage]\" step=\"0.01\" min=\"0\" max=\"100\" class=\"small-text min-percent-input\" required style=\"width: 100%;\"></td>' +
				'<td><input type=\"number\" name=\"grades[' + gradeIndex + '][max_percentage]\" step=\"0.01\" min=\"0\" max=\"100\" class=\"small-text max-percent-input\" required style=\"width: 100%;\"></td>' +
				'<td><strong class=\"range-display\">-</strong><br><button type=\"button\" class=\"button remove-grade-row\" style=\"margin-top: 5px;\">' + dreaunmaAdmin.i18n.remove + '</button></td>' +
				'</tr>';
			
			$('#grade-settings-tbody').append(newRow);
			gradeIndex++;
		});
		
		// Remove grade row
		$(document).on('click', '.remove-grade-row', function() {
			if ($('#grade-settings-tbody tr').length > 1) {
				$(this).closest('tr').remove();
			} else {
				alert(dreaunmaAdmin.i18n.minOneGrade);
			}
		});
		
		// Update range display on input change
		$(document).on('input', 'input[name*=\"[min_percentage]\"], input[name*=\"[max_percentage]\"]', function() {
			var row = $(this).closest('tr');
			var minPercent = parseFloat(row.find('input[name*=\"[min_percentage]\"]').val()) || 0;
			var maxPercent = parseFloat(row.find('input[name*=\"[max_percentage]\"]').val()) || 0;
			var rangeDisplay = row.find('.range-display');
			var removeButton = row.find('.remove-grade-row');
			
			var rangeText = '';
			if (maxPercent >= 100) {
				rangeText = minPercent + '% and above';
			} else if (minPercent == 0) {
				rangeText = 'Below ' + (maxPercent + 1) + '%';
			} else {
				rangeText = minPercent + '-' + maxPercent + '%';
			}
			
			if (removeButton.length) {
				rangeDisplay.html(rangeText);
			} else {
				if ($('#grade-settings-tbody tr').length > 1) {
					rangeDisplay.parent().html('<strong class=\"range-display\">' + rangeText + '</strong><br><button type=\"button\" class=\"button remove-grade-row\" style=\"margin-top: 5px;\">' + dreaunmaAdmin.i18n.remove + '</button>');
				} else {
					rangeDisplay.html(rangeText);
				}
			}
		});
	});
})(jQuery);
			";
			wp_add_inline_script( 'dreaunma-admin-script', $settings_script );
		}
	}
	
	/**
	 * Dashboard page
	 */
	public function dashboard_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/dashboard.php';
	}
	
	/**
	 * Faculties page
	 */
	public function faculties_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/faculties.php';
	}
	
	/**
	 * Departments page
	 */
	public function departments_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/departments.php';
	}
	
	/**
	 * Students page
	 */
	public function students_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/students.php';
	}
	
	/**
	 * Teachers page
	 */
	public function teachers_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/teachers.php';
	}
	
	/**
	 * Staff page
	 */
	public function staff_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/staff.php';
	}
	
	/**
	 * Courses page
	 */
	public function courses_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/courses.php';
	}
	
	/**
	 * Enrollments page
	 */
	public function enrollments_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/enrollments.php';
	}
	
	/**
	 * Grades page
	 */
	public function grades_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/grades.php';
	}
	
	/**
	 * CGPA page
	 */
	public function cgpa_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/cgpa.php';
	}
	
	/**
	 * Reports page
	 */
	public function reports_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/reports.php';
	}
	
	/**
	 * Shortcodes page
	 */
	public function shortcodes_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/shortcodes.php';
	}
	
	/**
	 * Settings page
	 */
	public function settings_page() {
		include DREAUNMA_PLUGIN_DIR . 'admin/views/settings.php';
	}
}

