<?php
/**
 * Courses frontend view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="dreaunma-frontend-courses">
	<?php if ( empty( $courses ) ) : ?>
		<p class="dreaunma-no-data"><?php esc_html_e( 'No courses found.', 'dream-university-management' ); ?></p>
	<?php else : ?>
		<table class="dreaunma-frontend-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Course Code', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Course Name', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Credits', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Faculty', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Department', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Semester', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Status', 'dream-university-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $courses as $course ) : ?>
					<?php
					// Get faculty and department names from JOIN or fallback
					$faculty_name = '';
					if ( ! empty( $course->faculty_name ) ) {
						$faculty_name = $course->faculty_name;
					} elseif ( ! empty( $course->faculty_id ) ) {
						$faculty = DREAUNMA_Faculty::get( $course->faculty_id );
						$faculty_name = $faculty ? $faculty->faculty_name : '-';
					}
					
					$department_name = '';
					if ( ! empty( $course->department_name ) ) {
						$department_name = $course->department_name;
					} elseif ( ! empty( $course->department_id ) ) {
						$department = DREAUNMA_Department::get( $course->department_id );
						$department_name = $department ? $department->department_name : '-';
					}
					?>
					<tr>
						<td><?php echo esc_html( $course->course_code ); ?></td>
						<td><strong><?php echo esc_html( $course->course_name ); ?></strong></td>
						<td><?php echo esc_html( $course->credits ); ?></td>
						<td><?php echo esc_html( $faculty_name ? $faculty_name : '-' ); ?></td>
						<td><?php echo esc_html( $department_name ? $department_name : '-' ); ?></td>
						<td><?php
						/* translators: %s: Semester number */
						echo esc_html( $course->semester ? sprintf( __( 'Semester %s', 'dream-university-management' ), $course->semester ) : '-' );
						?></td>
						<td>
							<span class="dreaunma-status-badge <?php echo esc_attr( $course->status ); ?>">
								<?php echo esc_html( ucfirst( $course->status ) ); ?>
							</span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>

