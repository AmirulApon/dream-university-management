<?php
/**
 * Grades frontend view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="dum-frontend-grades">
	<?php if ( empty( $grades ) ) : ?>
		<p class="dum-no-data"><?php esc_html_e( 'No grades found.', 'dream-university-management' ); ?></p>
	<?php else : ?>
		<table class="dum-frontend-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Student', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Course', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Total Marks', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Obtained Marks', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Grade', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Grade Point', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Date', 'dream-university-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $grades as $grade ) : ?>
					<?php
					$student_name = '-';
					if ( ! empty( $grade->student_first_name ) && ! empty( $grade->student_last_name ) ) {
						$student_name = $grade->student_first_name . ' ' . $grade->student_last_name;
					} elseif ( ! empty( $grade->student_id ) ) {
						$student = DUM_Student::get( $grade->student_id );
						if ( $student ) {
							$student_name = $student->first_name . ' ' . $student->last_name;
						}
					}
					
					$course_name = '-';
					if ( ! empty( $grade->course_name ) ) {
						$course_name = $grade->course_name;
					} elseif ( ! empty( $grade->course_id ) ) {
						$course = DUM_Course::get( $grade->course_id );
						if ( $course ) {
							$course_name = $course->course_name;
						}
					}
					?>
					<tr>
						<td><?php echo esc_html( $student_name ); ?></td>
						<td><?php echo esc_html( $course_name ); ?></td>
						<td><?php echo esc_html( $grade->total_marks ); ?></td>
						<td><?php echo esc_html( $grade->obtained_marks ); ?></td>
						<td>
							<span class="dum-grade-badge"><?php echo esc_html( $grade->grade ); ?></span>
						</td>
						<td><?php echo esc_html( $grade->grade_point ); ?></td>
						<td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $grade->created_at ) ) ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>

