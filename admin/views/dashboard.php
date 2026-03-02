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

$student_count = DREAUNMA_Student::count();
$teacher_count = DREAUNMA_Teacher::count();
$staff_count = DREAUNMA_Staff::count();
$course_count = DREAUNMA_Course::count();
?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<div class="dreaunma-dashboard">
		<div class="dreaunma-stats-grid">
			<div class="dreaunma-stat-card">
				<div class="dreaunma-stat-icon students">
					<span class="dashicons dashicons-groups"></span>
				</div>
				<div class="dreaunma-stat-content">
					<h3><?php echo esc_html( number_format_i18n( $student_count ) ); ?></h3>
					<p><?php esc_html_e( 'Total Students', 'dream-university-management' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=dreaunma-students' ) ); ?>" class="dreaunma-stat-link">
						<?php esc_html_e( 'View All', 'dream-university-management' ); ?>
					</a>
				</div>
			</div>
			
			<div class="dreaunma-stat-card">
				<div class="dreaunma-stat-icon teachers">
					<span class="dashicons dashicons-businessman"></span>
				</div>
				<div class="dreaunma-stat-content">
					<h3><?php echo esc_html( number_format_i18n( $teacher_count ) ); ?></h3>
					<p><?php esc_html_e( 'Total Teachers', 'dream-university-management' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=dreaunma-teachers' ) ); ?>" class="dreaunma-stat-link">
						<?php esc_html_e( 'View All', 'dream-university-management' ); ?>
					</a>
				</div>
			</div>
			
			<div class="dreaunma-stat-card">
				<div class="dreaunma-stat-icon staff">
					<span class="dashicons dashicons-admin-users"></span>
				</div>
				<div class="dreaunma-stat-content">
					<h3><?php echo esc_html( number_format_i18n( $staff_count ) ); ?></h3>
					<p><?php esc_html_e( 'Total Staff', 'dream-university-management' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=dreaunma-staff' ) ); ?>" class="dreaunma-stat-link">
						<?php esc_html_e( 'View All', 'dream-university-management' ); ?>
					</a>
				</div>
			</div>
			
			<div class="dreaunma-stat-card">
				<div class="dreaunma-stat-icon courses">
					<span class="dashicons dashicons-book"></span>
				</div>
				<div class="dreaunma-stat-content">
					<h3><?php echo esc_html( number_format_i18n( $course_count ) ); ?></h3>
					<p><?php esc_html_e( 'Total Courses', 'dream-university-management' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=dreaunma-courses' ) ); ?>" class="dreaunma-stat-link">
						<?php esc_html_e( 'View All', 'dream-university-management' ); ?>
					</a>
				</div>
			</div>
		</div>
		
		<div class="dreaunma-quick-actions">
			<h2><?php esc_html_e( 'Quick Actions', 'dream-university-management' ); ?></h2>
			<div class="dreaunma-action-buttons">
				<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=dreaunma-students&action=add' ), 'dreaunma-student-view' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Add Student', 'dream-university-management' ); ?>
				</a>
				<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=dreaunma-teachers&action=add' ), 'dreaunma-teacher-view' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Add Teacher', 'dream-university-management' ); ?>
				</a>
				<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=dreaunma-courses&action=add' ), 'dreaunma-course-view' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Add Course', 'dream-university-management' ); ?>
				</a>
				<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=dreaunma-enrollments&action=add' ), 'dreaunma-enrollments-view' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Enroll Student', 'dream-university-management' ); ?>
				</a>
			</div>
		</div>
		
		<div class="dreaunma-dashboard-shortcodes">
			<h2>
				<?php esc_html_e( 'Frontend Shortcodes', 'dream-university-management' ); ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=dreaunma-shortcodes' ) ); ?>" class="button button-secondary">
					<?php esc_html_e( 'View All Shortcodes', 'dream-university-management' ); ?>
				</a>
			</h2>
			<p><?php esc_html_e( 'Use these shortcodes to display university data on your website. Click the copy button to copy any shortcode.', 'dream-university-management' ); ?></p>
			<div class="dreaunma-dashboard-shortcodes-grid">
				<div class="dreaunma-dashboard-shortcode-item">
					<h4><?php esc_html_e( 'Faculties', 'dream-university-management' ); ?></h4>
					<div class="dreaunma-shortcode-box">
						<code id="dash-shortcode-faculties">[dum_faculties]</code>
						<button class="dreaunma-copy-btn" data-copy="dash-shortcode-faculties" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
				</div>
				<div class="dreaunma-dashboard-shortcode-item">
					<h4><?php esc_html_e( 'Students', 'dream-university-management' ); ?></h4>
					<div class="dreaunma-shortcode-box">
						<code id="dash-shortcode-students">[dum_students]</code>
						<button class="dreaunma-copy-btn" data-copy="dash-shortcode-students" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
				</div>
				<div class="dreaunma-dashboard-shortcode-item">
					<h4><?php esc_html_e( 'Teachers', 'dream-university-management' ); ?></h4>
					<div class="dreaunma-shortcode-box">
						<code id="dash-shortcode-teachers">[dum_teachers]</code>
						<button class="dreaunma-copy-btn" data-copy="dash-shortcode-teachers" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
				</div>
				<div class="dreaunma-dashboard-shortcode-item">
					<h4><?php esc_html_e( 'Courses', 'dream-university-management' ); ?></h4>
					<div class="dreaunma-shortcode-box">
						<code id="dash-shortcode-courses">[dum_courses]</code>
						<button class="dreaunma-copy-btn" data-copy="dash-shortcode-courses" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
				</div>
				<div class="dreaunma-dashboard-shortcode-item">
					<h4><?php esc_html_e( 'Grades', 'dream-university-management' ); ?></h4>
					<div class="dreaunma-shortcode-box">
						<code id="dash-shortcode-grades">[dum_grades]</code>
						<button class="dreaunma-copy-btn" data-copy="dash-shortcode-grades" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
				</div>
				<div class="dreaunma-dashboard-shortcode-item">
					<h4><?php esc_html_e( 'CGPA', 'dream-university-management' ); ?></h4>
					<div class="dreaunma-shortcode-box">
						<code id="dash-shortcode-cgpa">[dum_cgpa student_id="1"]</code>
						<button class="dreaunma-copy-btn" data-copy="dash-shortcode-cgpa" title="<?php esc_attr_e( 'Copy to clipboard', 'dream-university-management' ); ?>">
							<span class="dashicons dashicons-clipboard"></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

