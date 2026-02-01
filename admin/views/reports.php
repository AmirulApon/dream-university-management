<?php
/**
 * Reports view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Verify user permissions
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'dream-university-management' ) );
}

$student_count = DREAUNMA_Student::count();
$teacher_count = DREAUNMA_Teacher::count();
$staff_count = DREAUNMA_Staff::count();
$course_count = DREAUNMA_Course::count();
$active_students = DREAUNMA_Student::count( 'active' );
$active_teachers = DREAUNMA_Teacher::count( 'active' );
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Reports', 'dream-university-management' ); ?></h1>
	
	<div class="dreaunma-reports">
		<h2><?php esc_html_e( 'Overview Statistics', 'dream-university-management' ); ?></h2>
		
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Category', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Total', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Active', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Inactive', 'dream-university-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><strong><?php esc_html_e( 'Students', 'dream-university-management' ); ?></strong></td>
					<td><?php echo esc_html( $student_count ); ?></td>
					<td><?php echo esc_html( $active_students ); ?></td>
					<td><?php echo esc_html( $student_count - $active_students ); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Teachers', 'dream-university-management' ); ?></strong></td>
					<td><?php echo esc_html( $teacher_count ); ?></td>
					<td><?php echo esc_html( $active_teachers ); ?></td>
					<td><?php echo esc_html( $teacher_count - $active_teachers ); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Staff', 'dream-university-management' ); ?></strong></td>
					<td><?php echo esc_html( $staff_count ); ?></td>
					<td><?php echo esc_html( DREAUNMA_Staff::count( 'active' ) ); ?></td>
					<td><?php echo esc_html( $staff_count - DREAUNMA_Staff::count( 'active' ) ); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Courses', 'dream-university-management' ); ?></strong></td>
					<td><?php echo esc_html( $course_count ); ?></td>
					<td><?php echo esc_html( DREAUNMA_Course::count( 'active' ) ); ?></td>
					<td><?php echo esc_html( $course_count - DREAUNMA_Course::count( 'active' ) ); ?></td>
				</tr>
			</tbody>
		</table>
		
		<h2><?php esc_html_e( 'Top Students by CGPA', 'dream-university-management' ); ?></h2>
		<?php
		$top_students = DREAUNMA_CGPA::get_all_students_cgpa();
		$top_students = array_slice( $top_students, 0, 10 );
		if ( ! empty( $top_students ) ) :
			?>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Rank', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Student ID', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Name', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'CGPA', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Total Credits', 'dream-university-management' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $top_students as $index => $item ) : ?>
						<tr>
							<td><?php echo esc_html( $index + 1 ); ?></td>
							<td><?php echo esc_html( $item['student']->student_id ); ?></td>
							<td><?php echo esc_html( $item['student']->first_name . ' ' . $item['student']->last_name ); ?></td>
							<td><strong><?php echo esc_html( $item['cgpa'] ); ?></strong></td>
							<td><?php echo esc_html( $item['total_credits'] ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p><?php esc_html_e( 'No students with completed courses found.', 'dream-university-management' ); ?></p>
		<?php endif; ?>
	</div>
</div>

