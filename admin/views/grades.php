<?php
/**
 * Grades view
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

// Security check: Verify nonce for actions or filters
$is_action = isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'add', 'edit' ), true );
$has_filter_params = isset( $_GET['s'] ) || ( isset( $_GET['status'] ) && $_GET['status'] !== 'all' ) || ( isset( $_GET['student_id'] ) && $_GET['student_id'] != 0 ) || ( isset( $_GET['course_id'] ) && $_GET['course_id'] != 0 );

if ( $is_action || $has_filter_params ) {
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'dreaunma-grades' ) ) {
		wp_die( esc_html__( 'Security check failed. Please refresh the page and try again.', 'dream-university-management' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=dreaunma-grades' ) ) . '">' . esc_html__( 'Go Back', 'dream-university-management' ) . '</a>' );
	}
}

$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : 'list';

$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';

// Show messages
if ( $message === 'added' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Grade added successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'updated' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Grade updated successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'deleted' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Grade deleted successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'error' ) {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'An error occurred. Please try again.', 'dream-university-management' ) . '</p></div>';
}

if ( $action === 'add' || $action === 'edit' ) {
	$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
	$grade = null;
	if ( $action === 'edit' && $id > 0 ) {
		$grade = DREAUNMA_Grade::get( $id );
		if ( ! $grade ) {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Grade not found.', 'dream-university-management' ) . '</p></div>';
			return;
		}
	}
	
	$enrollments = DREAUNMA_Enrollment::get_all( array( 'status' => 'enrolled' ) );
	?>
	<div class="wrap">
		<h1><?php echo $action === 'add' ? esc_html__( 'Add Grade', 'dream-university-management' ) : esc_html__( 'Edit Grade', 'dream-university-management' ); ?></h1>
		
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( $action === 'add' ? 'dreaunma_add_grade' : 'dreaunma_edit_grade' ); ?>
			<input type="hidden" name="action" value="<?php echo $action === 'add' ? 'dreaunma_add_grade' : 'dreaunma_edit_grade'; ?>">
			<?php if ( $action === 'edit' ) : ?>
				<input type="hidden" name="grade_id" value="<?php echo esc_attr( $grade->id ); ?>">
			<?php endif; ?>
			
			<table class="form-table">
				<?php if ( $action === 'add' ) : ?>
					<tr>
						<th><label for="enrollment_id"><?php esc_html_e( 'Enrollment', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
						<td>
							<select id="enrollment_id" name="enrollment_id" required>
								<option value=""><?php esc_html_e( 'Select Enrollment', 'dream-university-management' ); ?></option>
								<?php foreach ( $enrollments as $enrollment ) : ?>
									<?php
									// Check if grade already exists for this enrollment
									$existing_grade = DREAUNMA_Grade::get_by_enrollment( $enrollment->id );
									if ( $existing_grade ) {
										continue; // Skip if grade already exists
									}
									?>
									<option value="<?php echo esc_attr( $enrollment->id ); ?>">
										<?php echo esc_html( $enrollment->student_id . ' - ' . $enrollment->student_first_name . ' ' . $enrollment->student_last_name . ' | ' . $enrollment->course_code . ' - ' . $enrollment->course_name ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
				<?php else : ?>
					<input type="hidden" name="enrollment_id" value="<?php echo esc_attr( $grade->enrollment_id ); ?>">
					<input type="hidden" name="student_id" value="<?php echo esc_attr( $grade->student_id ); ?>">
					<input type="hidden" name="course_id" value="<?php echo esc_attr( $grade->course_id ); ?>">
				<?php endif; ?>
				<tr>
					<th><label for="midterm_marks"><?php esc_html_e( 'Midterm Marks', 'dream-university-management' ); ?></label></th>
					<td><input type="number" id="midterm_marks" name="midterm_marks" value="<?php echo $grade ? esc_attr( $grade->midterm_marks ) : '0'; ?>" step="0.01" min="0" max="100" class="small-text"></td>
				</tr>
				<tr>
					<th><label for="final_marks"><?php esc_html_e( 'Final Marks', 'dream-university-management' ); ?></label></th>
					<td><input type="number" id="final_marks" name="final_marks" value="<?php echo $grade ? esc_attr( $grade->final_marks ) : '0'; ?>" step="0.01" min="0" max="100" class="small-text"></td>
				</tr>
				<tr>
					<th><label for="assignment_marks"><?php esc_html_e( 'Assignment Marks', 'dream-university-management' ); ?></label></th>
					<td><input type="number" id="assignment_marks" name="assignment_marks" value="<?php echo $grade ? esc_attr( $grade->assignment_marks ) : '0'; ?>" step="0.01" min="0" max="100" class="small-text"></td>
				</tr>
				<?php if ( $action === 'edit' && $grade ) : ?>
					<tr>
						<th><?php esc_html_e( 'Total Marks', 'dream-university-management' ); ?></th>
						<td><strong><?php echo esc_html( $grade->total_marks ); ?></strong></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Grade', 'dream-university-management' ); ?></th>
						<td><strong><?php echo esc_html( $grade->grade ); ?></strong></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Grade Point', 'dream-university-management' ); ?></th>
						<td><strong><?php echo esc_html( $grade->grade_point ); ?></strong></td>
					</tr>
				<?php endif; ?>
			</table>
			
			<?php submit_button( $action === 'add' ? __( 'Add Grade', 'dream-university-management' ) : __( 'Update Grade', 'dream-university-management' ) ); ?>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=dreaunma-grades' ) ); ?>" class="button"><?php esc_html_e( 'Cancel', 'dream-university-management' ); ?></a>
		</form>
	</div>
	
	<?php
} else {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
	$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
	$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : 'all';
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
	$student_filter = isset( $_GET['student_id'] ) ? intval( $_GET['student_id'] ) : 0;
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
	$course_filter = isset( $_GET['course_id'] ) ? intval( $_GET['course_id'] ) : 0;
	
	// Get all grades without limit
	$grades = DREAUNMA_Grade::get_all( array( 
		'limit' => 1000, 
		'offset' => 0,
		'status' => $status_filter,
		'student_id' => $student_filter,
		'course_id' => $course_filter
	) );
	
	// Filter by search if provided
	if ( ! empty( $search ) && ! empty( $grades ) ) {
		$filtered_grades = array();
		foreach ( $grades as $grade ) {
			$search_lower = strtolower( $search );
			if ( 
				stripos( $grade->student_id, $search ) !== false ||
				stripos( $grade->student_first_name, $search ) !== false ||
				stripos( $grade->student_last_name, $search ) !== false ||
				stripos( $grade->course_code, $search ) !== false ||
				stripos( $grade->course_name, $search ) !== false ||
				stripos( $grade->grade, $search ) !== false
			) {
				$filtered_grades[] = $grade;
			}
		}
		$grades = $filtered_grades;
	}
	
	// Debug: Check if we got results
	if ( ! is_array( $grades ) ) {
		$grades = array();
	}
	
	// If no grades with joins, try getting raw grade data
	if ( empty( $grades ) ) {
		global $wpdb;
		$grades_table = $wpdb->prefix . 'dreaunma_grades';
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Table name is safe (from $wpdb->prefix), fallback query with no user input
		$raw_grades = $wpdb->get_results( "SELECT * FROM $grades_table ORDER BY id DESC", OBJECT );
		
		if ( $raw_grades ) {
			// If we have raw data but no joined data, there might be missing student/course records
			// Let's try to get the data with proper joins one more time
			$grades = DREAUNMA_Grade::get_all( array( 'limit' => 0, 'offset' => 0 ) );
			
			// If still empty, use raw data and we'll show what we can
			if ( empty( $grades ) && $raw_grades ) {
				// Convert raw data to format expected by view
				foreach ( $raw_grades as $raw ) {
					$student = DREAUNMA_Student::get( $raw->student_id );
					$course = DREAUNMA_Course::get( $raw->course_id );
					
					$grade = new stdClass();
					$grade->id = $raw->id;
					$grade->student_id = $student ? $student->student_id : 'N/A';
					$grade->student_first_name = $student ? $student->first_name : '';
					$grade->student_last_name = $student ? $student->last_name : '';
					$grade->course_code = $course ? $course->course_code : 'N/A';
					$grade->course_name = $course ? $course->course_name : 'N/A';
					$grade->midterm_marks = $raw->midterm_marks;
					$grade->final_marks = $raw->final_marks;
					$grade->assignment_marks = $raw->assignment_marks;
					$grade->total_marks = $raw->total_marks;
					$grade->grade = $raw->grade;
					$grade->grade_point = $raw->grade_point;
					
					$grades[] = $grade;
				}
			}
		}
	}
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Grades', 'dream-university-management' ); ?></h1>
		<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=dreaunma-grades&action=add' ), 'dreaunma-grades' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add Grade', 'dream-university-management' ); ?></a>
		
		<hr class="wp-header-end">
		
		<div class="dreaunma-search-box">
			<form method="get" action="">
				<input type="hidden" name="page" value="dreaunma-grades">
				<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'dreaunma-grades' ) ); ?>">
				<p class="search-box">
					<label class="screen-reader-text" for="grade-search-input"><?php esc_html_e( 'Search Grades:', 'dream-university-management' ); ?></label>
					<input type="search" id="grade-search-input" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search by student, course, or grade...', 'dream-university-management' ); ?>">
					<select name="status" id="status-filter">
						<option value="all" <?php selected( $status_filter, 'all' ); ?>><?php esc_html_e( 'All Status', 'dream-university-management' ); ?></option>
						<option value="completed" <?php selected( $status_filter, 'completed' ); ?>><?php esc_html_e( 'Completed', 'dream-university-management' ); ?></option>
						<option value="pending" <?php selected( $status_filter, 'pending' ); ?>><?php esc_html_e( 'Pending', 'dream-university-management' ); ?></option>
					</select>
					<?php submit_button( esc_html__( 'Search', 'dream-university-management' ), 'button', '', false ); ?>
					<?php if ( ! empty( $search ) || $status_filter !== 'all' ) : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=dreaunma-grades' ) ); ?>" class="button"><?php esc_html_e( 'Clear', 'dream-university-management' ); ?></a>
					<?php endif; ?>
				</p>
			</form>
		</div>
		
		<?php if ( empty( $grades ) ) : ?>
			<div class="notice notice-info">
				<p><?php esc_html_e( 'No grades found. Please add grades for enrolled students.', 'dream-university-management' ); ?></p>
			</div>
		<?php else : ?>
			<table class="wp-list-table widefat fixed striped table-view-list">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'ID', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Student', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Course', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Midterm', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Final', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Assignment', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Total', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Grade', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Grade Point', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Actions', 'dream-university-management' ); ?></th>
					</tr>
				</thead>
				<tbody id="the-list">
				<?php if ( empty( $grades ) ) : ?>
					<tr>
						<td colspan="10"><?php esc_html_e( 'No grades found.', 'dream-university-management' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $grades as $grade ) : ?>
						<?php
						// Handle column names properly
						$student_id = ! empty( $grade->student_id ) ? esc_html( $grade->student_id ) : esc_html__( 'N/A', 'dream-university-management' );
						$student_name = '';
						if ( ! empty( $grade->student_first_name ) && ! empty( $grade->student_last_name ) ) {
							$student_name = esc_html( $grade->student_first_name . ' ' . $grade->student_last_name );
						} else {
							$student_name = esc_html__( 'N/A', 'dream-university-management' );
						}
						$course_code = ! empty( $grade->course_code ) ? esc_html( $grade->course_code ) : esc_html__( 'N/A', 'dream-university-management' );
						$course_name = ! empty( $grade->course_name ) ? esc_html( $grade->course_name ) : esc_html__( 'N/A', 'dream-university-management' );
						$midterm = ! empty( $grade->midterm_marks ) ? esc_html( $grade->midterm_marks ) : '0';
						$final = ! empty( $grade->final_marks ) ? esc_html( $grade->final_marks ) : '0';
						$assignment = ! empty( $grade->assignment_marks ) ? esc_html( $grade->assignment_marks ) : '0';
						$total = ! empty( $grade->total_marks ) ? esc_html( $grade->total_marks ) : '0';
						$grade_letter = ! empty( $grade->grade ) ? esc_html( $grade->grade ) : esc_html__( 'N/A', 'dream-university-management' );
						$grade_point = ! empty( $grade->grade_point ) ? esc_html( $grade->grade_point ) : '0.00';
						$grade_id = ! empty( $grade->id ) ? intval( $grade->id ) : 0;
						?>
						<tr>
							<td><strong><?php echo esc_html( $grade_id ); ?></strong></td>
							<td><?php echo esc_html( $student_id . ' - ' . $student_name ); ?></td>
							<td><?php echo esc_html( $course_code . ' - ' . $course_name ); ?></td>
							<td><?php echo esc_html( $midterm ); ?></td>
							<td><?php echo esc_html( $final ); ?></td>
							<td><?php echo esc_html( $assignment ); ?></td>
							<td><strong><?php echo esc_html( $total ); ?></strong></td>
							<td><strong><?php echo esc_html( $grade_letter ); ?></strong></td>
							<td><strong><?php echo esc_html( $grade_point ); ?></strong></td>
							<td>
								<?php if ( $grade_id > 0 ) : ?>
									<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=dreaunma-grades&action=edit&id=' . $grade_id ), 'dreaunma-grades' ) ); ?>"><?php esc_html_e( 'Edit', 'dream-university-management' ); ?></a> |
									<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=dreaunma_delete_grade&id=' . $grade_id ), 'dreaunma_delete_grade' ) ); ?>" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this grade?', 'dream-university-management' ); ?>');"><?php esc_html_e( 'Delete', 'dream-university-management' ); ?></a>
								<?php else : ?>
									<span class="na"><?php esc_html_e( 'N/A', 'dream-university-management' ); ?></span>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
				<tfoot>
					<tr>
						<th scope="col"><?php esc_html_e( 'ID', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Student', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Course', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Midterm', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Final', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Assignment', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Total', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Grade', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Grade Point', 'dream-university-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Actions', 'dream-university-management' ); ?></th>
					</tr>
				</tfoot>
			</table>
		<?php endif; ?>
	</div>
	<?php
}

