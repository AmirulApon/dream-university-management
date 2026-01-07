<?php
/**
 * Dashboard view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$student_count = DUM_Student::count();
$teacher_count = DUM_Teacher::count();
$staff_count = DUM_Staff::count();
$course_count = DUM_Course::count();
?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<div class="dum-dashboard">
		<div class="dum-stats-grid">
			<div class="dum-stat-card">
				<div class="dum-stat-icon students">
					<span class="dashicons dashicons-groups"></span>
				</div>
				<div class="dum-stat-content">
					<h3><?php echo esc_html( number_format_i18n( $student_count ) ); ?></h3>
					<p><?php esc_html_e( 'Total Students', 'dream-university-management' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-students' ) ); ?>" class="dum-stat-link">
						<?php esc_html_e( 'View All', 'dream-university-management' ); ?>
					</a>
				</div>
			</div>
			
			<div class="dum-stat-card">
				<div class="dum-stat-icon teachers">
					<span class="dashicons dashicons-businessman"></span>
				</div>
				<div class="dum-stat-content">
					<h3><?php echo esc_html( number_format_i18n( $teacher_count ) ); ?></h3>
					<p><?php esc_html_e( 'Total Teachers', 'dream-university-management' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-teachers' ) ); ?>" class="dum-stat-link">
						<?php esc_html_e( 'View All', 'dream-university-management' ); ?>
					</a>
				</div>
			</div>
			
			<div class="dum-stat-card">
				<div class="dum-stat-icon staff">
					<span class="dashicons dashicons-admin-users"></span>
				</div>
				<div class="dum-stat-content">
					<h3><?php echo esc_html( number_format_i18n( $staff_count ) ); ?></h3>
					<p><?php esc_html_e( 'Total Staff', 'dream-university-management' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-staff' ) ); ?>" class="dum-stat-link">
						<?php esc_html_e( 'View All', 'dream-university-management' ); ?>
					</a>
				</div>
			</div>
			
			<div class="dum-stat-card">
				<div class="dum-stat-icon courses">
					<span class="dashicons dashicons-book"></span>
				</div>
				<div class="dum-stat-content">
					<h3><?php echo esc_html( number_format_i18n( $course_count ) ); ?></h3>
					<p><?php esc_html_e( 'Total Courses', 'dream-university-management' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-courses' ) ); ?>" class="dum-stat-link">
						<?php esc_html_e( 'View All', 'dream-university-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<div class="dum-quick-actions">
			<h2><?php esc_html_e( 'Quick Actions', 'dream-university-management' ); ?></h2>
			<div class="dum-action-buttons">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-students&action=add' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Add Student', 'dream-university-management' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-teachers&action=add' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Add Teacher', 'dream-university-management' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-courses&action=add' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Add Course', 'dream-university-management' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-enrollments&action=add' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Enroll Student', 'dream-university-management' ); ?>
				</a>
			</div>
		</div>
		
		<div class="dum-dashboard-shortcodes">
			<h2>
				<?php esc_html_e( 'Frontend Shortcodes', 'dream-university-management' ); ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-shortcodes' ) ); ?>" class="button button-secondary">
					<?php esc_html_e( 'View All Shortcodes', 'dream-university-management' ); ?>
				</a>
			</h2>
			<p><?php esc_html_e( 'Use these shortcodes to display university data on your website. Click the copy button to copy any shortcode.', 'dream-university-management' ); ?></p>
			<div class="dum-dashboard-shortcodes-grid">
				<div class="dum-dashboard-shortcode-item">
					<h4><?php esc_html_e( 'Faculties', 'dream-university-management' ); ?></h4>
					<div class="dum-shortcode-box">
						<code id="dash-shortcode-faculties">[dum_faculties]</code>
						<button class="dum-copy-btn" data-copy="dash-shortcode-faculties" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
				</div>
				<div class="dum-dashboard-shortcode-item">
					<h4><?php esc_html_e( 'Students', 'dream-university-management' ); ?></h4>
					<div class="dum-shortcode-box">
						<code id="dash-shortcode-students">[dum_students]</code>
						<button class="dum-copy-btn" data-copy="dash-shortcode-students" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
				</div>
				<div class="dum-dashboard-shortcode-item">
					<h4><?php esc_html_e( 'Teachers', 'dream-university-management' ); ?></h4>
					<div class="dum-shortcode-box">
						<code id="dash-shortcode-teachers">[dum_teachers]</code>
						<button class="dum-copy-btn" data-copy="dash-shortcode-teachers" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
				</div>
				<div class="dum-dashboard-shortcode-item">
					<h4><?php esc_html_e( 'Courses', 'dream-university-management' ); ?></h4>
					<div class="dum-shortcode-box">
						<code id="dash-shortcode-courses">[dum_courses]</code>
						<button class="dum-copy-btn" data-copy="dash-shortcode-courses" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
				</div>
				<div class="dum-dashboard-shortcode-item">
					<h4><?php esc_html_e( 'Grades', 'dream-university-management' ); ?></h4>
					<div class="dum-shortcode-box">
						<code id="dash-shortcode-grades">[dum_grades]</code>
						<button class="dum-copy-btn" data-copy="dash-shortcode-grades" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
				</div>
				<div class="dum-dashboard-shortcode-item">
					<h4><?php esc_html_e( 'CGPA', 'dream-university-management' ); ?></h4>
					<div class="dum-shortcode-box">
						<code id="dash-shortcode-cgpa">[dum_cgpa student_id="1"]</code>
						<button class="dum-copy-btn" data-copy="dash-shortcode-cgpa" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
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

