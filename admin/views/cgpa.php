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

// Verify user permissions
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'dream-university-management' ) );
}

// Verify nonce if present in GET parameters
$is_student_action = isset( $_GET['student_id'] );
$has_filter_params = isset( $_GET['filter_faculty'] ) || isset( $_GET['filter_department'] ) || isset( $_GET['filter_course'] ) || isset( $_GET['filter_action'] );

if ( $is_student_action ) {
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'dreaunma-cgpa-view' ) ) {
		wp_die( esc_html__( 'Security check failed. Please refresh the page and try again.', 'dream-university-management' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=dreaunma-cgpa' ) ) . '">' . esc_html__( 'Go Back', 'dream-university-management' ) . '</a>' );
	}
} elseif ( $has_filter_params ) {
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'dreaunma-cgpa-settings' ) ) {
		wp_die( esc_html__( 'Security check failed. Please refresh the page and try again.', 'dream-university-management' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=dreaunma-cgpa' ) ) . '">' . esc_html__( 'Go Back', 'dream-university-management' ) . '</a>' );
	}
}

$student_id = isset( $_GET['student_id'] ) ? intval( $_GET['student_id'] ) : 0;
$students = DREAUNMA_Student::get_all( array( 'status' => 'active' ) );
?>

<div class="wrap">
	<h1><?php esc_html_e( 'CGPA Calculator', 'dream-university-management' ); ?></h1>
	
	<div class="dreaunma-cgpa-calculator">
		<form method="get" action="">
			<input type="hidden" name="page" value="dreaunma-cgpa">
			<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'dreaunma-cgpa-view' ) ); ?>">
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
		
		<hr class="wp-header-end">
		
		<h2><?php esc_html_e( 'All Students CGPA', 'dream-university-management' ); ?></h2>
		
		<?php
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$filter_faculty = isset( $_GET['filter_faculty'] ) ? intval( $_GET['filter_faculty'] ) : 0;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$filter_department = isset( $_GET['filter_department'] ) ? intval( $_GET['filter_department'] ) : 0;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$filter_course = isset( $_GET['filter_course'] ) ? intval( $_GET['filter_course'] ) : 0;
		
		$faculties = DREAUNMA_Faculty::get_all( array( 'status' => 'active', 'limit' => 1000 ) );
		$departments = $filter_faculty > 0 ? DREAUNMA_Department::get_by_faculty( $filter_faculty, 'active' ) : array();
		$courses = DREAUNMA_Course::get_all( array( 'status' => 'active', 'limit' => 1000 ) );
		?>
		
		<div class="dreaunma-filter-box" style="margin-bottom: 20px; padding: 15px; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
			<form method="get" action="">
				<input type="hidden" name="page" value="dreaunma-cgpa">
				<!-- Using a different nonce for list filters to avoid conflict with the student select nonce above -->
				<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'dreaunma-cgpa-settings' ) ); ?>">
				
				<div style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
					<div>
						<label for="filter_faculty" style="display: block; margin-bottom: 5px; font-weight: 600;"><?php esc_html_e( 'Faculty', 'dream-university-management' ); ?></label>
						<select name="filter_faculty" id="filter_faculty" onchange="this.form.submit()">
							<option value="0"><?php esc_html_e( 'All Faculties', 'dream-university-management' ); ?></option>
							<?php foreach ( $faculties as $fac ) : ?>
								<option value="<?php echo esc_attr( $fac->id ); ?>" <?php selected( $filter_faculty, $fac->id ); ?>>
									<?php echo esc_html( $fac->faculty_name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					
					<div>
						<label for="filter_department" style="display: block; margin-bottom: 5px; font-weight: 600;"><?php esc_html_e( 'Department', 'dream-university-management' ); ?></label>
						<select name="filter_department" id="filter_department" onchange="this.form.submit()">
							<option value="0"><?php esc_html_e( 'All Departments', 'dream-university-management' ); ?></option>
							<?php foreach ( $departments as $dept ) : ?>
								<option value="<?php echo esc_attr( $dept->id ); ?>" <?php selected( $filter_department, $dept->id ); ?>>
									<?php echo esc_html( $dept->department_name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					
					<div>
						<label for="filter_course" style="display: block; margin-bottom: 5px; font-weight: 600;"><?php esc_html_e( 'Course', 'dream-university-management' ); ?></label>
						<select name="filter_course" id="filter_course" onchange="this.form.submit()">
							<option value="0"><?php esc_html_e( 'All Courses', 'dream-university-management' ); ?></option>
							<?php foreach ( $courses as $course ) : ?>
								<option value="<?php echo esc_attr( $course->id ); ?>" <?php selected( $filter_course, $course->id ); ?>>
									<?php echo esc_html( $course->course_code . ' - ' . $course->course_name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					
					<div>
						<?php submit_button( __( 'Filter', 'dream-university-management' ), 'secondary', 'filter_action', false ); ?>
						<?php if ( $filter_faculty > 0 || $filter_department > 0 || $filter_course > 0 ) : ?>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=dreaunma-cgpa' ) ); ?>" class="button"><?php esc_html_e( 'Clear Options', 'dream-university-management' ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			</form>
		</div>

		<?php
		$all_cgpa = DREAUNMA_CGPA::get_all_students_cgpa( $filter_faculty, $filter_department, $filter_course );
		
		// If filtering by course, we should display the average course GPA at the top
		if ( $filter_course > 0 && ! empty( $all_cgpa ) ) {
			$total_course_cgpa = 0;
			$student_count = count( $all_cgpa );
			
			foreach ( $all_cgpa as $item ) {
				$total_course_cgpa += $item['cgpa'];
			}
			
			$avg_course_cgpa = $student_count > 0 ? round( $total_course_cgpa / $student_count, 2 ) : 0.00;
			
			$selected_course_name = '';
			foreach ( $courses as $c ) {
				if ( (int) $c->id === $filter_course ) {
					$selected_course_name = $c->course_code . ' - ' . $c->course_name;
					break;
				}
			}
			?>
			<div class="notice notice-info" style="margin-top: 15px; margin-bottom: 20px;">
				<p>
					<strong><?php esc_html_e( 'Course Average:', 'dream-university-management' ); ?></strong> 
					<?php echo esc_html( $selected_course_name ); ?> - 
					<span style="font-size: 16px; font-weight: bold; color: #007cba;"><?php echo esc_html( $avg_course_cgpa ); ?></span>
					(<?php echo esc_html( sprintf( _n( '%s student', '%s students', $student_count, 'dream-university-management' ), number_format_i18n( $student_count ) ) ); ?>)
				</p>
			</div>
			<?php
		}

		if ( ! empty( $all_cgpa ) ) :
			?>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Rank', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Student ID', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Name', 'dream-university-management' ); ?></th>
						<th><?php echo $filter_course > 0 ? esc_html_e( 'Course GPA', 'dream-university-management' ) : esc_html_e( 'CGPA', 'dream-university-management' ); ?></th>
						<th><?php esc_html_e( 'Credits Counted', 'dream-university-management' ); ?></th>
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
			<p><?php esc_html_e( 'No student results found matching the selected criteria.', 'dream-university-management' ); ?></p>
		<?php endif; ?>
	</div>
</div>

