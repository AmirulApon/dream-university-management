<?php
/**
 * CGPA view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
$student_id = isset( $_GET['student_id'] ) ? intval( $_GET['student_id'] ) : 0;
$students = DREAUNMA_Student::get_all( array( 'status' => 'active' ) );
?>

<div class="wrap">
	<h1><?php esc_html_e( 'CGPA Calculator', 'dream-university-management' ); ?></h1>
	
	<div class="dreaunma-cgpa-calculator">
		<form method="get" action="">
			<input type="hidden" name="page" value="dreaunma-cgpa">
			<table class="form-table">
				<tr>
					<th><label for="student_id"><?php esc_html_e( 'Select Student', 'dream-university-management' ); ?></label></th>
					<td>
						<select id="student_id" name="student_id" onchange="this.form.submit()">
							<option value="0"><?php esc_html_e( 'Select Student', 'dream-university-management' ); ?></option>
							<?php foreach ( $students as $student ) : ?>
								<option value="<?php echo esc_attr( $student->id ); ?>" <?php selected( $student_id, $student->id ); ?>>
									<?php echo esc_html( $student->student_id . ' - ' . $student->first_name . ' ' . $student->last_name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
			</table>
		</form>
		
		<?php if ( $student_id > 0 ) : ?>
			<?php
			$cgpa_data = DREAUNMA_CGPA::calculate( $student_id );
			$transcript = DREAUNMA_CGPA::get_transcript( $student_id );
			$student = DREAUNMA_Student::get( $student_id );
			?>
			
			<div class="dreaunma-cgpa-result">
				<h2><?php esc_html_e( 'CGPA Result', 'dream-university-management' ); ?></h2>
				<?php if ( $student ) : ?>
					<p><strong><?php esc_html_e( 'Student:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $student->student_id . ' - ' . $student->first_name . ' ' . $student->last_name ); ?></p>
				<?php endif; ?>
				<p><strong><?php esc_html_e( 'CGPA:', 'dream-university-management' ); ?></strong> <span class="dreaunma-cgpa-value"><?php echo esc_html( $cgpa_data['cgpa'] ); ?></span></p>
				<p><strong><?php esc_html_e( 'Total Credits:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $cgpa_data['total_credits'] ); ?></p>
			</div>
			
			<?php if ( ! empty( $transcript ) ) : ?>
				<h2><?php esc_html_e( 'Transcript', 'dream-university-management' ); ?></h2>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Course Code', 'dream-university-management' ); ?></th>
							<th><?php esc_html_e( 'Course Name', 'dream-university-management' ); ?></th>
							<th><?php esc_html_e( 'Credits', 'dream-university-management' ); ?></th>
							<th><?php esc_html_e( 'Semester', 'dream-university-management' ); ?></th>
							<th><?php esc_html_e( 'Total Marks', 'dream-university-management' ); ?></th>
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
								<td><strong><?php echo esc_html( $record->grade ); ?></strong></td>
								<td><strong><?php echo esc_html( $record->grade_point ); ?></strong></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else : ?>
				<p><?php esc_html_e( 'No completed courses found for this student.', 'dream-university-management' ); ?></p>
			<?php endif; ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Please select a student to view CGPA.', 'dream-university-management' ); ?></p>
		<?php endif; ?>
		
		<h2><?php esc_html_e( 'All Students CGPA', 'dream-university-management' ); ?></h2>
		<?php
		$all_cgpa = DREAUNMA_CGPA::get_all_students_cgpa();
		if ( ! empty( $all_cgpa ) ) :
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
					<?php foreach ( $all_cgpa as $index => $item ) : ?>
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

