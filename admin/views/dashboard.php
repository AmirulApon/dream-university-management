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
	</div>
</div>

