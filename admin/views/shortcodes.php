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
	
	<div class="dum-shortcodes-page">
		<div class="dum-shortcodes-intro">
			<p><?php esc_html_e( 'Use these shortcodes to display university data on your website. Copy any shortcode and paste it into posts, pages, or widgets.', 'dream-university-management' ); ?></p>
		</div>
		
		<div class="dum-shortcodes-grid">
			<!-- Faculties Shortcode -->
			<div class="dum-shortcode-card">
				<div class="dum-shortcode-header">
					<h3><?php esc_html_e( 'Faculties', 'dream-university-management' ); ?></h3>
					<span class="dum-shortcode-badge">Grid Layout</span>
				</div>
				<div class="dum-shortcode-content">
					<p class="dum-shortcode-description"><?php esc_html_e( 'Display all faculties in a grid layout.', 'dream-university-management' ); ?></p>
					<div class="dum-shortcode-box">
						<code id="shortcode-faculties">[dum_faculties]</code>
						<button class="dum-copy-btn" data-copy="shortcode-faculties" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dum-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>status</code> - active, inactive, all (default: active)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
							<li><code>columns</code> - Number of columns (default: 3)</li>
						</ul>
					</div>
					<div class="dum-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_faculties status="active" limit="10" columns="3"]</code>
					</div>
				</div>
			</div>
			
			<!-- Departments Shortcode -->
			<div class="dum-shortcode-card">
				<div class="dum-shortcode-header">
					<h3><?php esc_html_e( 'Departments', 'dream-university-management' ); ?></h3>
					<span class="dum-shortcode-badge">Table Layout</span>
				</div>
				<div class="dum-shortcode-content">
					<p class="dum-shortcode-description"><?php esc_html_e( 'Display departments in a table format.', 'dream-university-management' ); ?></p>
					<div class="dum-shortcode-box">
						<code id="shortcode-departments">[dum_departments]</code>
						<button class="dum-copy-btn" data-copy="shortcode-departments" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dum-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>faculty_id</code> - Filter by faculty ID (default: 0 = all)</li>
							<li><code>status</code> - active, inactive, all (default: active)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
						</ul>
					</div>
					<div class="dum-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_departments faculty_id="1" status="active"]</code>
					</div>
				</div>
			</div>
			
			<!-- Students Shortcode -->
			<div class="dum-shortcode-card">
				<div class="dum-shortcode-header">
					<h3><?php esc_html_e( 'Students', 'dream-university-management' ); ?></h3>
					<span class="dum-shortcode-badge">Card Grid</span>
				</div>
				<div class="dum-shortcode-content">
					<p class="dum-shortcode-description"><?php esc_html_e( 'Display students with photos in a card grid layout.', 'dream-university-management' ); ?></p>
					<div class="dum-shortcode-box">
						<code id="shortcode-students">[dum_students]</code>
						<button class="dum-copy-btn" data-copy="shortcode-students" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dum-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>faculty_id</code> - Filter by faculty ID (default: 0 = all)</li>
							<li><code>department_id</code> - Filter by department ID (default: 0 = all)</li>
							<li><code>status</code> - active, inactive, all (default: active)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
							<li><code>columns</code> - Number of columns (default: 3)</li>
						</ul>
					</div>
					<div class="dum-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_students faculty_id="1" department_id="2" status="active" limit="20"]</code>
					</div>
				</div>
			</div>
			
			<!-- Teachers Shortcode -->
			<div class="dum-shortcode-card">
				<div class="dum-shortcode-header">
					<h3><?php esc_html_e( 'Teachers', 'dream-university-management' ); ?></h3>
					<span class="dum-shortcode-badge">Card Grid</span>
				</div>
				<div class="dum-shortcode-content">
					<p class="dum-shortcode-description"><?php esc_html_e( 'Display teachers with photos in a card grid layout.', 'dream-university-management' ); ?></p>
					<div class="dum-shortcode-box">
						<code id="shortcode-teachers">[dum_teachers]</code>
						<button class="dum-copy-btn" data-copy="shortcode-teachers" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dum-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>faculty_id</code> - Filter by faculty ID (default: 0 = all)</li>
							<li><code>department_id</code> - Filter by department ID (default: 0 = all)</li>
							<li><code>status</code> - active, inactive, all (default: active)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
							<li><code>columns</code> - Number of columns (default: 3)</li>
						</ul>
					</div>
					<div class="dum-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_teachers faculty_id="1" status="active"]</code>
					</div>
				</div>
			</div>
			
			<!-- Staff Shortcode -->
			<div class="dum-shortcode-card">
				<div class="dum-shortcode-header">
					<h3><?php esc_html_e( 'Staff', 'dream-university-management' ); ?></h3>
					<span class="dum-shortcode-badge">Card Grid</span>
				</div>
				<div class="dum-shortcode-content">
					<p class="dum-shortcode-description"><?php esc_html_e( 'Display staff members with photos in a card grid layout.', 'dream-university-management' ); ?></p>
					<div class="dum-shortcode-box">
						<code id="shortcode-staff">[dum_staff]</code>
						<button class="dum-copy-btn" data-copy="shortcode-staff" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dum-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>status</code> - active, inactive, all (default: active)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
							<li><code>columns</code> - Number of columns (default: 3)</li>
						</ul>
					</div>
					<div class="dum-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_staff status="active" limit="10"]</code>
					</div>
				</div>
			</div>
			
			<!-- Courses Shortcode -->
			<div class="dum-shortcode-card">
				<div class="dum-shortcode-header">
					<h3><?php esc_html_e( 'Courses', 'dream-university-management' ); ?></h3>
					<span class="dum-shortcode-badge">Table Layout</span>
				</div>
				<div class="dum-shortcode-content">
					<p class="dum-shortcode-description"><?php esc_html_e( 'Display courses in a table format.', 'dream-university-management' ); ?></p>
					<div class="dum-shortcode-box">
						<code id="shortcode-courses">[dum_courses]</code>
						<button class="dum-copy-btn" data-copy="shortcode-courses" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dum-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>faculty_id</code> - Filter by faculty ID (default: 0 = all)</li>
							<li><code>department_id</code> - Filter by department ID (default: 0 = all)</li>
							<li><code>status</code> - active, inactive, all (default: active)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
						</ul>
					</div>
					<div class="dum-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_courses faculty_id="1" department_id="2" status="active"]</code>
					</div>
				</div>
			</div>
			
			<!-- Grades Shortcode -->
			<div class="dum-shortcode-card">
				<div class="dum-shortcode-header">
					<h3><?php esc_html_e( 'Grades', 'dream-university-management' ); ?></h3>
					<span class="dum-shortcode-badge">Table Layout</span>
				</div>
				<div class="dum-shortcode-content">
					<p class="dum-shortcode-description"><?php esc_html_e( 'Display grades in a table format.', 'dream-university-management' ); ?></p>
					<div class="dum-shortcode-box">
						<code id="shortcode-grades">[dum_grades]</code>
						<button class="dum-copy-btn" data-copy="shortcode-grades" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dum-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>student_id</code> - Filter by student ID (default: 0 = all)</li>
							<li><code>course_id</code> - Filter by course ID (default: 0 = all)</li>
							<li><code>limit</code> - Maximum number to display (default: 0 = unlimited)</li>
						</ul>
					</div>
					<div class="dum-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_grades student_id="1"]</code>
					</div>
				</div>
			</div>
			
			<!-- CGPA Shortcode -->
			<div class="dum-shortcode-card">
				<div class="dum-shortcode-header">
					<h3><?php esc_html_e( 'CGPA / Transcript', 'dream-university-management' ); ?></h3>
					<span class="dum-shortcode-badge">Student View</span>
				</div>
				<div class="dum-shortcode-content">
					<p class="dum-shortcode-description"><?php esc_html_e( 'Display student CGPA and transcript. Student ID is required.', 'dream-university-management' ); ?></p>
					<div class="dum-shortcode-box">
						<code id="shortcode-cgpa">[dum_cgpa student_id="1"]</code>
						<button class="dum-copy-btn" data-copy="shortcode-cgpa" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
					<div class="dum-shortcode-params">
						<strong><?php esc_html_e( 'Parameters:', 'dream-university-management' ); ?></strong>
						<ul>
							<li><code>student_id</code> - <strong>Required</strong>. The ID of the student</li>
						</ul>
					</div>
					<div class="dum-shortcode-example">
						<strong><?php esc_html_e( 'Example:', 'dream-university-management' ); ?></strong>
						<code>[dum_cgpa student_id="1"]</code>
					</div>
				</div>
			</div>
		</div>
		
		<div class="dum-shortcodes-usage">
			<h2><?php esc_html_e( 'How to Use', 'dream-university-management' ); ?></h2>
			<div class="dum-usage-steps">
				<div class="dum-usage-step">
					<h3><?php esc_html_e( '1. In Posts/Pages', 'dream-university-management' ); ?></h3>
					<p><?php esc_html_e( 'Edit any post or page, add the shortcode where you want the content to appear.', 'dream-university-management' ); ?></p>
				</div>
				<div class="dum-usage-step">
					<h3><?php esc_html_e( '2. In Widgets', 'dream-university-management' ); ?></h3>
					<p><?php esc_html_e( 'Go to Appearance > Widgets, add a "Shortcode" widget, and enter your shortcode.', 'dream-university-management' ); ?></p>
				</div>
				<div class="dum-usage-step">
					<h3><?php esc_html_e( '3. In Theme Templates', 'dream-university-management' ); ?></h3>
					<p><?php esc_html_e( 'Use the do_shortcode() function: <?php echo do_shortcode(\'[dum_faculties]\'); ?>', 'dream-university-management' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
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
			btn.html('<span class="dashicons dashicons-yes-alt"></span>');
			btn.css('color', '#00a32a');
			
			setTimeout(function() {
				btn.html(originalHtml);
				btn.css('color', '');
			}, 2000);
		});
	});
})(jQuery);
</script>

