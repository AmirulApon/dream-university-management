<?php
/**
 * Shortcodes view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<div class="dreaunma-shortcodes-page">
		<div class="dreaunma-shortcodes-intro">
			<p><?php esc_html_e( 'Use these shortcodes to display university data on your website. Copy any shortcode and paste it into posts, pages, or widgets.', 'dream-university-management' ); ?></p>
		</div>
		
		<div class="dreaunma-shortcodes-grid">
			<!-- Faculties Shortcode -->
			<div class="dreaunma-shortcode-card">
				<div class="dreaunma-shortcode-header">
					<h3><?php esc_html_e( 'Faculties', 'dream-university-management' ); ?></h3>
					<span class="dreaunma-shortcode-badge">Grid Layout</span>
				</div>
				<div class="dreaunma-shortcode-content">
					<p class="dreaunma-shortcode-description"><?php esc_html_e( 'Display all faculties in a grid layout.', 'dream-university-management' ); ?></p>
					<div class="dreaunma-shortcode-box">
						<code id="shortcode-faculties">[dum_faculties]</code>
						<button class="dreaunma-copy-btn" data-copy="shortcode-faculties" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dreaunma-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>status</code> - active, inactive, all (default: active)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
							<li><code>columns</code> - Number of columns (default: 3)</li>
						</ul>
					</div>
					<div class="dreaunma-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_faculties status="active" limit="10" columns="3"]</code>
					</div>
				</div>
			</div>
			
			<!-- Departments Shortcode -->
			<div class="dreaunma-shortcode-card">
				<div class="dreaunma-shortcode-header">
					<h3><?php esc_html_e( 'Departments', 'dream-university-management' ); ?></h3>
					<span class="dreaunma-shortcode-badge">Table Layout</span>
				</div>
				<div class="dreaunma-shortcode-content">
					<p class="dreaunma-shortcode-description"><?php esc_html_e( 'Display departments in a table format.', 'dream-university-management' ); ?></p>
					<div class="dreaunma-shortcode-box">
						<code id="shortcode-departments">[dum_departments]</code>
						<button class="dreaunma-copy-btn" data-copy="shortcode-departments" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dreaunma-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>faculty_id</code> - Filter by faculty ID (default: 0 = all)</li>
							<li><code>status</code> - active, inactive, all (default: active)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
						</ul>
					</div>
					<div class="dreaunma-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_departments faculty_id="1" status="active"]</code>
					</div>
				</div>
			</div>
			
			<!-- Students Shortcode -->
			<div class="dreaunma-shortcode-card">
				<div class="dreaunma-shortcode-header">
					<h3><?php esc_html_e( 'Students', 'dream-university-management' ); ?></h3>
					<span class="dreaunma-shortcode-badge">Card Grid</span>
				</div>
				<div class="dreaunma-shortcode-content">
					<p class="dreaunma-shortcode-description"><?php esc_html_e( 'Display students with photos in a card grid layout.', 'dream-university-management' ); ?></p>
					<div class="dreaunma-shortcode-box">
						<code id="shortcode-students">[dum_students]</code>
						<button class="dreaunma-copy-btn" data-copy="shortcode-students" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dreaunma-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>faculty_id</code> - Filter by faculty ID (default: 0 = all)</li>
							<li><code>department_id</code> - Filter by department ID (default: 0 = all)</li>
							<li><code>status</code> - active, inactive, all (default: active)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
							<li><code>columns</code> - Number of columns (default: 3)</li>
						</ul>
					</div>
					<div class="dreaunma-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_students faculty_id="1" department_id="2" status="active" limit="20"]</code>
					</div>
				</div>
			</div>
			
			<!-- Teachers Shortcode -->
			<div class="dreaunma-shortcode-card">
				<div class="dreaunma-shortcode-header">
					<h3><?php esc_html_e( 'Teachers', 'dream-university-management' ); ?></h3>
					<span class="dreaunma-shortcode-badge">Card Grid</span>
				</div>
				<div class="dreaunma-shortcode-content">
					<p class="dreaunma-shortcode-description"><?php esc_html_e( 'Display teachers with photos in a card grid layout.', 'dream-university-management' ); ?></p>
					<div class="dreaunma-shortcode-box">
						<code id="shortcode-teachers">[dum_teachers]</code>
						<button class="dreaunma-copy-btn" data-copy="shortcode-teachers" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dreaunma-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>faculty_id</code> - Filter by faculty ID (default: 0 = all)</li>
							<li><code>department_id</code> - Filter by department ID (default: 0 = all)</li>
							<li><code>status</code> - active, inactive, all (default: active)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
							<li><code>columns</code> - Number of columns (default: 3)</li>
						</ul>
					</div>
					<div class="dreaunma-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_teachers faculty_id="1" status="active"]</code>
					</div>
				</div>
			</div>
			
			<!-- Staff Shortcode -->
			<div class="dreaunma-shortcode-card">
				<div class="dreaunma-shortcode-header">
					<h3><?php esc_html_e( 'Staff', 'dream-university-management' ); ?></h3>
					<span class="dreaunma-shortcode-badge">Card Grid</span>
				</div>
				<div class="dreaunma-shortcode-content">
					<p class="dreaunma-shortcode-description"><?php esc_html_e( 'Display staff members with photos in a card grid layout.', 'dream-university-management' ); ?></p>
					<div class="dreaunma-shortcode-box">
						<code id="shortcode-staff">[dum_staff]</code>
						<button class="dreaunma-copy-btn" data-copy="shortcode-staff" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dreaunma-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>status</code> - active, inactive, all (default: active)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
							<li><code>columns</code> - Number of columns (default: 3)</li>
						</ul>
					</div>
					<div class="dreaunma-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_staff status="active" limit="10"]</code>
					</div>
				</div>
			</div>
			
			<!-- Courses Shortcode -->
			<div class="dreaunma-shortcode-card">
				<div class="dreaunma-shortcode-header">
					<h3><?php esc_html_e( 'Courses', 'dream-university-management' ); ?></h3>
					<span class="dreaunma-shortcode-badge">Table Layout</span>
				</div>
				<div class="dreaunma-shortcode-content">
					<p class="dreaunma-shortcode-description"><?php esc_html_e( 'Display courses in a table format.', 'dream-university-management' ); ?></p>
					<div class="dreaunma-shortcode-box">
						<code id="shortcode-courses">[dum_courses]</code>
						<button class="dreaunma-copy-btn" data-copy="shortcode-courses" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dreaunma-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>faculty_id</code> - Filter by faculty ID (default: 0 = all)</li>
							<li><code>department_id</code> - Filter by department ID (default: 0 = all)</li>
							<li><code>status</code> - active, inactive, all (default: active)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
						</ul>
					</div>
					<div class="dreaunma-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_courses faculty_id="1" department_id="2" status="active"]</code>
					</div>
				</div>
			</div>
			
			<!-- Grades Shortcode -->
			<div class="dreaunma-shortcode-card">
				<div class="dreaunma-shortcode-header">
					<h3><?php esc_html_e( 'Grades', 'dream-university-management' ); ?></h3>
					<span class="dreaunma-shortcode-badge">Table Layout</span>
				</div>
				<div class="dreaunma-shortcode-content">
					<p class="dreaunma-shortcode-description"><?php esc_html_e( 'Display grades in a table format.', 'dream-university-management' ); ?></p>
					<div class="dreaunma-shortcode-box">
						<code id="shortcode-grades">[dum_grades]</code>
						<button class="dreaunma-copy-btn" data-copy="shortcode-grades" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dreaunma-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>student_id</code> - Filter by student ID (default: 0 = all)</li>
							<li><code>course_id</code> - Filter by course ID (default: 0 = all)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
						</ul>
					</div>
					<div class="dreaunma-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_grades student_id="1"]</code>
					</div>
				</div>
			</div>
			
			<!-- CGPA Shortcode -->
			<div class="dreaunma-shortcode-card">
				<div class="dreaunma-shortcode-header">
					<h3><?php esc_html_e( 'CGPA / Transcript', 'dream-university-management' ); ?></h3>
					<span class="dreaunma-shortcode-badge">Student View</span>
				</div>
				<div class="dreaunma-shortcode-content">
					<p class="dreaunma-shortcode-description"><?php esc_html_e( 'Display student CGPA and transcript. Student ID is required.', 'dream-university-management' ); ?></p>
					<div class="dreaunma-shortcode-box">
						<code id="shortcode-cgpa">[dum_cgpa student_id="1"]</code>
						<button class="dreaunma-copy-btn" data-copy="shortcode-cgpa" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dreaunma-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>student_id</code> - <strong>Required</strong>. The ID of the student</li>
						</ul>
					</div>
					<div class="dreaunma-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_cgpa student_id="1"]</code>
					</div>
				</div>
			</div>
		</div>
		
		<div class="dreaunma-shortcodes-usage">
			<h2><?php esc_html_e( 'How to Use', 'dream-university-management' ); ?></h2>
			<div class="dreaunma-usage-steps">
				<div class="dreaunma-usage-step">
					<h3><?php esc_html_e( '1. In Posts/Pages', 'dream-university-management' ); ?></h3>
					<p><?php esc_html_e( 'Edit any post or page, add the shortcode where you want the content to appear.', 'dream-university-management' ); ?></p>
				</div>
				<div class="dreaunma-usage-step">
					<h3><?php esc_html_e( '2. In Widgets', 'dream-university-management' ); ?></h3>
					<p><?php esc_html_e( 'Go to Appearance > Widgets, add a "Shortcode" widget, and enter your shortcode.', 'dream-university-management' ); ?></p>
				</div>
				<div class="dreaunma-usage-step">
					<h3><?php esc_html_e( '3. In Theme Templates', 'dream-university-management' ); ?></h3>
					<p><?php esc_html_e( 'Use the do_shortcode() function: <?php echo do_shortcode(\'[dum_faculties]\'); ?>', 'dream-university-management' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</div>

