<?php
/**
 * Enrollments view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : 'list';
$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
$message = isset( $_GET['message'] ) ? sanitize_text_field( $_GET['message'] ) : '';

// Show messages
if ( $message === 'added' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Student enrolled successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'deleted' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Enrollment deleted successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'duplicate' ) {
	echo '<div class="notice notice-warning"><p>' . esc_html__( 'This student is already enrolled in this course.', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'error' ) {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'An error occurred. Please try again.', 'dream-university-management' ) . '</p></div>';
}

if ( $action === 'add' ) {
	$students = DUM_Student::get_all( array( 'status' => 'active' ) );
	$courses = DUM_Course::get_all( array( 'status' => 'active' ) );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Enroll Student', 'dream-university-management' ); ?></h1>
		
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'dum_add_enrollment' ); ?>
			<input type="hidden" name="action" value="dum_add_enrollment">
			
			<table class="form-table">
				<tr>
					<th><label for="student_id"><?php esc_html_e( 'Student', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td>
						<select id="student_id" name="student_id" required>
							<option value=""><?php esc_html_e( 'Select Student', 'dream-university-management' ); ?></option>
							<?php foreach ( $students as $student ) : ?>
								<option value="<?php echo esc_attr( $student->id ); ?>">
									<?php echo esc_html( $student->student_id . ' - ' . $student->first_name . ' ' . $student->last_name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="course_id"><?php esc_html_e( 'Course', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td>
						<select id="course_id" name="course_id" required>
							<option value=""><?php esc_html_e( 'Select Course', 'dream-university-management' ); ?></option>
							<?php foreach ( $courses as $course ) : ?>
								<option value="<?php echo esc_attr( $course->id ); ?>">
									<?php echo esc_html( $course->course_code . ' - ' . $course->course_name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="enrollment_date"><?php esc_html_e( 'Enrollment Date', 'dream-university-management' ); ?></label></th>
					<td><input type="date" id="enrollment_date" name="enrollment_date" value="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>" class="regular-text"></td>
				</tr>
			</table>
			
			<?php submit_button( __( 'Enroll Student', 'dream-university-management' ) ); ?>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-enrollments' ) ); ?>" class="button"><?php esc_html_e( 'Cancel', 'dream-university-management' ); ?></a>
		</form>
	</div>
	<?php
} else {
	$search = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
	$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'all';
	$student_filter = isset( $_GET['student_id'] ) ? intval( $_GET['student_id'] ) : 0;
	$course_filter = isset( $_GET['course_id'] ) ? intval( $_GET['course_id'] ) : 0;
	
	// Get all enrollments without limit
	$enrollments = DUM_Enrollment::get_all( array( 
		'limit' => 1000, 
		'offset' => 0,
		'status' => $status_filter,
		'student_id' => $student_filter,
		'course_id' => $course_filter
	) );
	
	// Filter by search if provided
	if ( ! empty( $search ) && ! empty( $enrollments ) ) {
		$filtered_enrollments = array();
		foreach ( $enrollments as $enrollment ) {
			$search_lower = strtolower( $search );
			if ( 
				stripos( $enrollment->student_id, $search ) !== false ||
				stripos( $enrollment->student_first_name, $search ) !== false ||
				stripos( $enrollment->student_last_name, $search ) !== false ||
				stripos( $enrollment->course_code, $search ) !== false ||
				stripos( $enrollment->course_name, $search ) !== false
			) {
				$filtered_enrollments[] = $enrollment;
			}
		}
		$enrollments = $filtered_enrollments;
	}
	
	// Debug: Check if we got results
	if ( ! is_array( $enrollments ) ) {
		$enrollments = array();
	}
	
	// If no enrollments with joins, try getting raw enrollment data
	if ( empty( $enrollments ) ) {
		global $wpdb;
		$enrollments_table = $wpdb->prefix . 'dum_enrollments';
		$raw_enrollments = $wpdb->get_results( "SELECT * FROM $enrollments_table ORDER BY id DESC", OBJECT );
		
		if ( $raw_enrollments ) {
			// If we have raw data but no joined data, there might be missing student/course records
			// Let's try to get the data with proper joins one more time, but with error handling
			$enrollments = DUM_Enrollment::get_all( array( 'limit' => 0, 'offset' => 0 ) );
			
			// If still empty, use raw data and we'll show what we can
			if ( empty( $enrollments ) && $raw_enrollments ) {
				// Convert raw data to format expected by view
				foreach ( $raw_enrollments as $raw ) {
					$student = DUM_Student::get( $raw->student_id );
					$course = DUM_Course::get( $raw->course_id );
					
					$enrollment = new stdClass();
					$enrollment->id = $raw->id;
					$enrollment->student_id = $student ? $student->student_id : 'N/A';
					$enrollment->student_first_name = $student ? $student->first_name : '';
					$enrollment->student_last_name = $student ? $student->last_name : '';
					$enrollment->course_code = $course ? $course->course_code : 'N/A';
					$enrollment->course_name = $course ? $course->course_name : 'N/A';
					$enrollment->credits = $course ? $course->credits : '0';
					$enrollment->enrollment_date = $raw->enrollment_date;
					$enrollment->status = $raw->status;
					
					$enrollments[] = $enrollment;
				}
			}
		}
	}
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Enrollments', 'dream-university-management' ); ?></h1>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-enrollments&action=add' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Enroll Student', 'dream-university-management' ); ?></a>
		
		<hr class="wp-header-end">
		
		<div class="dum-search-box">
			<form method="get" action="">
				<input type="hidden" name="page" value="dum-enrollments">
				<p class="search-box">
					<label class="screen-reader-text" for="enrollment-search-input"><?php esc_html_e( 'Search Enrollments:', 'dream-university-management' ); ?></label>
					<input type="search" id="enrollment-search-input" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search by student, course...', 'dream-university-management' ); ?>">
					<select name="status" id="status-filter">
						<option value="all" <?php selected( $status_filter, 'all' ); ?>><?php esc_html_e( 'All Status', 'dream-university-management' ); ?></option>
						<option value="enrolled" <?php selected( $status_filter, 'enrolled' ); ?>><?php esc_html_e( 'Enrolled', 'dream-university-management' ); ?></option>
						<option value="completed" <?php selected( $status_filter, 'completed' ); ?>><?php esc_html_e( 'Completed', 'dream-university-management' ); ?></option>
					</select>
					<?php submit_button( __( 'Search', 'dream-university-management' ), 'button', '', false ); ?>
					<?php if ( ! empty( $search ) || $status_filter !== 'all' ) : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-enrollments' ) ); ?>" class="button"><?php esc_html_e( 'Clear', 'dream-university-management' ); ?></a>
					<?php endif; ?>
				</p>
			</form>
		</div>
		
		<?php if ( empty( $enrollments ) ) : ?>
			<div class="notice notice-info">
				<p><?php esc_html_e( 'No enrollments found. Please enroll a student in a course.', 'dream-university-management' ); ?></p>
			</div>
		<?php else : ?>
			<table class="wp-list-table widefat fixed striped table-view-list">
				<thead>
					<tr>
						<th scope="col" class="manage-column column-student"><?php esc_html_e( 'Student ID', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-student-name"><?php esc_html_e( 'Student Name', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-course-code"><?php esc_html_e( 'Course Code', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-course-name"><?php esc_html_e( 'Course Name', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-credits"><?php esc_html_e( 'Credits', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-date"><?php esc_html_e( 'Enrollment Date', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-status"><?php esc_html_e( 'Status', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-actions"><?php esc_html_e( 'Actions', 'dream-university-management' ); ?></th>
					</tr>
				</thead>
				<tbody id="the-list">
					<?php foreach ( $enrollments as $enrollment ) : ?>
						<?php
						// Handle null values
						$student_id = ! empty( $enrollment->student_id ) ? esc_html( $enrollment->student_id ) : __( 'N/A', 'dream-university-management' );
						$student_name = '';
						if ( ! empty( $enrollment->student_first_name ) && ! empty( $enrollment->student_last_name ) ) {
							$student_name = esc_html( $enrollment->student_first_name . ' ' . $enrollment->student_last_name );
						} else {
							$student_name = __( 'N/A', 'dream-university-management' );
						}
						$course_code = ! empty( $enrollment->course_code ) ? esc_html( $enrollment->course_code ) : __( 'N/A', 'dream-university-management' );
						$course_name = ! empty( $enrollment->course_name ) ? esc_html( $enrollment->course_name ) : __( 'N/A', 'dream-university-management' );
						$credits = ! empty( $enrollment->credits ) ? esc_html( $enrollment->credits ) : '0';
						$enrollment_date = ! empty( $enrollment->enrollment_date ) ? esc_html( date_i18n( get_option( 'date_format' ), strtotime( $enrollment->enrollment_date ) ) ) : __( 'N/A', 'dream-university-management' );
						$status = ! empty( $enrollment->status ) ? esc_attr( $enrollment->status ) : 'enrolled';
						$enrollment_id = ! empty( $enrollment->id ) ? intval( $enrollment->id ) : 0;
						?>
						<tr>
							<td class="column-student" data-colname="<?php esc_attr_e( 'Student ID', 'dream-university-management' ); ?>">
								<strong><?php echo $student_id; ?></strong>
							</td>
							<td class="column-student-name" data-colname="<?php esc_attr_e( 'Student Name', 'dream-university-management' ); ?>">
								<?php echo $student_name; ?>
							</td>
							<td class="column-course-code" data-colname="<?php esc_attr_e( 'Course Code', 'dream-university-management' ); ?>">
								<strong><?php echo $course_code; ?></strong>
							</td>
							<td class="column-course-name" data-colname="<?php esc_attr_e( 'Course Name', 'dream-university-management' ); ?>">
								<?php echo $course_name; ?>
							</td>
							<td class="column-credits" data-colname="<?php esc_attr_e( 'Credits', 'dream-university-management' ); ?>">
								<?php echo $credits; ?>
							</td>
							<td class="column-date" data-colname="<?php esc_attr_e( 'Enrollment Date', 'dream-university-management' ); ?>">
								<?php echo $enrollment_date; ?>
							</td>
							<td class="column-status" data-colname="<?php esc_attr_e( 'Status', 'dream-university-management' ); ?>">
								<span class="dum-status <?php echo $status; ?>">
									<?php echo esc_html( ucfirst( $status ) ); ?>
								</span>
							</td>
							<td class="column-actions" data-colname="<?php esc_attr_e( 'Actions', 'dream-university-management' ); ?>">
								<?php if ( $enrollment_id > 0 ) : ?>
									<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=dum_delete_enrollment&id=' . $enrollment_id ), 'dum_delete_enrollment' ) ); ?>" class="delete" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this enrollment?', 'dream-university-management' ); ?>');"><?php esc_html_e( 'Delete', 'dream-university-management' ); ?></a>
								<?php else : ?>
									<span class="na"><?php esc_html_e( 'N/A', 'dream-university-management' ); ?></span>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th scope="col" class="manage-column column-student"><?php esc_html_e( 'Student ID', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-student-name"><?php esc_html_e( 'Student Name', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-course-code"><?php esc_html_e( 'Course Code', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-course-name"><?php esc_html_e( 'Course Name', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-credits"><?php esc_html_e( 'Credits', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-date"><?php esc_html_e( 'Enrollment Date', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-status"><?php esc_html_e( 'Status', 'dream-university-management' ); ?></th>
						<th scope="col" class="manage-column column-actions"><?php esc_html_e( 'Actions', 'dream-university-management' ); ?></th>
					</tr>
				</tfoot>
			</table>
		<?php endif; ?>
	</div>
	<?php
}

