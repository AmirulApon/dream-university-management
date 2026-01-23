<?php
/**
 * CGPA frontend view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="dreaunma-frontend-cgpa">
	<div class="dreaunma-cgpa-header">
		<h2><?php esc_html_e( 'Student Transcript', 'dream-university-management' ); ?></h2>
		<div class="dreaunma-student-info">
			<h3><?php echo esc_html( $student->first_name . ' ' . $student->last_name ); ?></h3>
			<p><strong><?php esc_html_e( 'Student ID:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $student->student_id ); ?></p>
		</div>
	</div>
	
	<div class="dreaunma-cgpa-summary">
		<div class="dreaunma-cgpa-card">
			<h4><?php esc_html_e( 'CGPA', 'dream-university-management' ); ?></h4>
			<div class="dreaunma-cgpa-value"><?php echo esc_html( number_format( $cgpa_data['cgpa'], 2 ) ); ?></div>
			<p class="dreaunma-cgpa-details">
				<?php esc_html_e( 'Total Credits:', 'dream-university-management' ); ?> <?php echo esc_html( $cgpa_data['total_credits'] ); ?>
			</p>
		</div>
	</div>
	
	<?php if ( ! empty( $transcript ) ) : ?>
		<div class="dreaunma-transcript">
			<h3><?php esc_html_e( 'Course Details', 'dream-university-management' ); ?></h3>
			<table class="dreaunma-frontend-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Course Code', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Course Name', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Credits', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Semester', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Total Marks', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Obtained Marks', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Grade', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Grade Point', 'dream-university-management' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $transcript as $record ) : ?>
						<tr>
							<td><?php echo esc_html( $record->course_code ); ?></td>
							<td><?php echo esc_html( $record->course_name ); ?></td>
							<td><?php echo esc_html( $record->credits ); ?></td>
							<td><?php
							/* translators: %s: Semester number */
							echo esc_html( $record->semester ? sprintf( __( 'Semester %s', 'dream-university-management' ), $record->semester ) : '-' );
							?></td>
							<td><?php echo esc_html( $record->total_marks ); ?></td>
							<td><?php echo esc_html( $record->obtained_marks ); ?></td>
							<td>
								<span class="dreaunma-grade-badge"><?php echo esc_html( $record->grade ); ?></span>
							</td>
							<td><?php echo esc_html( $record->grade_point ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else : ?>
		<p class="dreaunma-no-data"><?php esc_html_e( 'No transcript data available.', 'dream-university-management' ); ?></p>
	<?php endif; ?>
</div>

