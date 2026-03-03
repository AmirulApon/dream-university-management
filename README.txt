=== Dream University Management ===
Contributors: dreamscarnival
Tags: university management, school management, student management, course management, cgpa calculator
Requires at least: 6.3
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A comprehensive university management system for WordPress. Manage students, teachers, staff, courses, enrollments, and calculate CGPA.

== Description ==

Dream University Management is a complete solution for managing university operations within WordPress. This plugin provides a full-featured system for educational institutions to manage their day-to-day operations.

= Features =

* **Student Management**: Add, edit, and manage student records with complete information
* **Teacher Management**: Manage teacher profiles, departments, and designations
* **Staff Management**: Handle administrative and support staff records
* **Course Management**: Create and manage courses with credits, departments, and semesters
* **Enrollment System**: Enroll students in courses with enrollment tracking
* **Grade Management**: Record and calculate grades with automatic grade point calculation
* **CGPA Calculator**: Automatic CGPA calculation based on completed courses
* **Reports**: View comprehensive statistics and reports
* **User-Friendly Interface**: Clean and intuitive admin interface

= Grade System =

The plugin uses a standard grading system:
* A+ (4.00) - 90-100%
* A (3.75) - 85-89%
* A- (3.50) - 80-84%
* B+ (3.25) - 75-79%
* B (3.00) - 70-74%
* B- (2.75) - 65-69%
* C+ (2.50) - 60-64%
* C (2.25) - 55-59%
* C- (2.00) - 50-54%
* D (1.75) - 45-49%
* F (0.00) - Below 45%

= Database Tables =

The plugin creates the following database tables:
* wp_dreaunma_students - Student records
* wp_dreaunma_teachers - Teacher records
* wp_dreaunma_staff - Staff records
* wp_dreaunma_courses - Course information
* wp_dreaunma_enrollments - Student course enrollments
* wp_dreaunma_grades - Grade records
* wp_dreaunma_faculties - Faculty records
* wp_dreaunma_departments - Department records

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/dream-university-management` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the University menu in the WordPress admin to start managing your university data.

== Frequently Asked Questions ==

= Does this plugin require any additional setup? =

No, the plugin automatically creates the necessary database tables upon activation.

= Can I customize the grading system? =

The grading system is currently fixed but can be customized by modifying the grade calculation function in the code.

= Is this plugin compatible with multisite? =

The plugin should work with WordPress multisite installations, but each site will have its own separate database tables.

= Can students access their own records? =

Currently, the plugin is admin-only. Future versions may include student and teacher frontend access.

== Screenshots ==

1. Dashboard with statistics overview
2. Student management interface
3. Course management
4. Enrollment system
5. Grade entry and management
6. CGPA calculator with transcript view

== Changelog ==

= 1.0.1 =
* Added Session and Semester filters to the CGPA calculator
* Added Session and Level fields to Student profiles
* Added DataTables integration for CSV and PDF exports across all listing tables
* Fixed UI alignment issues in the viewing areas
* Enhanced overall backend security with reinforced nonce validation

= 1.0.0 =
* Initial release
* Student management
* Teacher management
* Staff management
* Course management
* Enrollment system
* Grade management
* CGPA calculation
* Reports and statistics

== Upgrade Notice ==

= 1.0.1 =
Minor update providing advanced CGPA filters, new data export capabilities, and crucial backend security fixes.

= 1.0.0 =
Initial release of Dream University Management plugin.

