<?php
/**
 * Courses view
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
$has_filter_params = isset( $_GET['s'] ) || ( isset( $_GET['status'] ) && $_GET['status'] !== 'all' );

if ( $is_action || $has_filter_params ) {
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'dreaunma-courses-view' ) ) {
		wp_die( esc_html__( 'Security check failed. Please refresh the page and try again.', 'dream-university-management' ) );
	}
}

$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : 'list';

$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';

// Show messages
if ( $message === 'added' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Course added successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'updated' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Course updated successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'deleted' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Course deleted successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'error' ) {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'An error occurred. Please try again.', 'dream-university-management' ) . '</p></div>';
}

if ( $action === 'add' || $action === 'edit' ) {
	$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
	$course = null;
	if ( $action === 'edit' && $id > 0 ) {
		$course = DREAUNMA_Course::get( $id );
		if ( ! $course ) {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Course not found.', 'dream-university-management' ) . '</p></div>';
			return;
		}
	}
	
	$teachers = DREAUNMA_Teacher::get_all( array( 'status' => 'active' ) );
	?>
	<div class="wrap">
		<h1><?php echo $action === 'add' ? esc_html__( 'Add Course', 'dream-university-management' ) : esc_html__( 'Edit Course', 'dream-university-management' ); ?></h1>
		
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( $action === 'add' ? 'dreaunma_add_course' : 'dreaunma_edit_course' ); ?>
			<input type="hidden" name="action" value="<?php echo $action === 'add' ? 'dreaunma_add_course' : 'dreaunma_edit_course'; ?>">
			<?php if ( $action === 'edit' ) : ?>
				<input type="hidden" name="course_id" value="<?php echo esc_attr( $course->id ); ?>">
			<?php endif; ?>
			
			<table class="form-table">
				<tr>
					<th><label for="course_code"><?php esc_html_e( 'Course Code', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="text" id="course_code" name="course_code" value="<?php echo $course ? esc_attr( $course->course_code ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="course_name"><?php esc_html_e( 'Course Name', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="text" id="course_name" name="course_name" value="<?php echo $course ? esc_attr( $course->course_name ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="description"><?php esc_html_e( 'Description', 'dream-university-management' ); ?></label></th>
					<td><textarea id="description" name="description" rows="5" class="large-text"><?php echo $course ? esc_textarea( $course->description ) : ''; ?></textarea></td>
				</tr>
				<tr>
					<th><label for="credits"><?php esc_html_e( 'Credits', 'dream-university-management' ); ?></label></th>
					<td><input type="number" id="credits" name="credits" value="<?php echo $course ? esc_attr( $course->credits ) : '0'; ?>" step="0.5" min="0" max="10" class="small-text"></td>
				</tr>
				<tr>
					<th><label for="faculty_id"><?php esc_html_e( 'Faculty', 'dream-university-management' ); ?></label></th>
					<td>
						<?php
						$faculties = DREAUNMA_Faculty::get_all( array( 'status' => 'active', 'limit' => 1000 ) );
						$selected_faculty_id = $course ? intval( $course->faculty_id ) : 0;
						?>
						<select id="faculty_id" name="faculty_id">
							<option value="0"><?php esc_html_e( 'Select Faculty', 'dream-university-management' ); ?></option>
							<?php foreach ( $faculties as $faculty ) : ?>
								<option value="<?php echo esc_attr( $faculty->id ); ?>" <?php selected( $selected_faculty_id, $faculty->id ); ?>>
									<?php echo esc_html( $faculty->faculty_code . ' - ' . $faculty->faculty_name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="department_id"><?php esc_html_e( 'Department', 'dream-university-management' ); ?></label></th>
					<td>
						<select id="department_id" name="department_id">
							<option value="0"><?php esc_html_e( 'Select Department', 'dream-university-management' ); ?></option>
							<?php
							if ( $selected_faculty_id > 0 ) {
								$departments = DREAUNMA_Department::get_by_faculty( $selected_faculty_id, 'active' );
								$selected_department_id = $course ? intval( $course->department_id ) : 0;
								foreach ( $departments as $dept ) :
									?>
									<option value="<?php echo esc_attr( $dept->id ); ?>" <?php selected( $selected_department_id, $dept->id ); ?>>
										<?php echo esc_html( $dept->department_code . ' - ' . $dept->department_name ); ?>
									</option>
									<?php
								endforeach;
							}
							?>
						</select>
						<input type="text" id="department" name="department" value="<?php echo $course ? esc_attr( $course->department ) : ''; ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Optional: Old department field', 'dream-university-management' ); ?>">
					</td>
				</tr>
				<tr>
					<th><label for="semester"><?php esc_html_e( 'Semester', 'dream-university-management' ); ?></label></th>
					<td>
						<select id="semester" name="semester">
							<option value=""><?php esc_html_e( 'Select', 'dream-university-management' ); ?></option>
							<?php for ( $i = 1; $i <= 12; $i++ ) : ?>
								<option value="<?php echo esc_attr( $i ); ?>" <?php echo $course && $course->semester == $i ? 'selected' : ''; ?>>
									<?php
									/* translators: %d: Semester number */
									echo esc_html( sprintf( __( 'Semester %d', 'dream-university-management' ), $i ) );
									?>
								</option>
							<?php endfor; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="teacher_id"><?php esc_html_e( 'Teacher', 'dream-university-management' ); ?></label></th>
					<td>
						<select id="teacher_id" name="teacher_id">
							<option value="0"><?php esc_html_e( 'Select Teacher', 'dream-university-management' ); ?></option>
							<?php foreach ( $teachers as $teacher ) : ?>
								<option value="<?php echo esc_attr( $teacher->id ); ?>" <?php echo $course && $course->teacher_id == $teacher->id ? 'selected' : ''; ?>>
									<?php echo esc_html( $teacher->first_name . ' ' . $teacher->last_name . ' (' . $teacher->teacher_id . ')' ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="status"><?php esc_html_e( 'Status', 'dream-university-management' ); ?></label></th>
					<td>
						<select id="status" name="status">
							<option value="active" <?php echo $course && $course->status === 'active' ? 'selected' : ''; ?>><?php esc_html_e( 'Active', 'dream-university-management' ); ?></option>
							<option value="inactive" <?php echo $course && $course->status === 'inactive' ? 'selected' : ''; ?>><?php esc_html_e( 'Inactive', 'dream-university-management' ); ?></option>
						</select>
					</td>
				</tr>
			</table>
			
			<?php submit_button( $action === 'add' ? __( 'Add Course', 'dream-university-management' ) : __( 'Update Course', 'dream-university-management' ) ); ?>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=dreaunma-courses' ) ); ?>" class="button"><?php esc_html_e( 'Cancel', 'dream-university-management' ); ?></a>
		</form>
	</div>
	<?php
} else {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
	$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
	$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : 'all';
	
	$courses = DREAUNMA_Course::get_all( array( 
		'search' => $search,
		'status' => $status_filter,
		'limit' => 1000 
	) );
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Courses', 'dream-university-management' ); ?></h1>
		<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=dreaunma-courses&action=add' ), 'dreaunma-courses-view' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'dream-university-management' ); ?></a>
		
		<hr class="wp-header-end">
		
		<div class="dreaunma-search-box">
			<form method="get" action="">
				<input type="hidden" name="page" value="dreaunma-courses">
				<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'dreaunma-courses-view' ) ); ?>">
				<p class="search-box">
					<label class="screen-reader-text" for="course-search-input"><?php esc_html_e( 'Search Courses:', 'dream-university-management' ); ?></label>
					<input type="search" id="course-search-input" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search by course code or name...', 'dream-university-management' ); ?>">
					<select name="status" id="status-filter">
						<option value="all" <?php selected( $status_filter, 'all' ); ?>><?php esc_html_e( 'All Status', 'dream-university-management' ); ?></option>
						<option value="active" <?php selected( $status_filter, 'active' ); ?>><?php esc_html_e( 'Active', 'dream-university-management' ); ?></option>
						<option value="inactive" <?php selected( $status_filter, 'inactive' ); ?>><?php esc_html_e( 'Inactive', 'dream-university-management' ); ?></option>
					</select>
					<?php submit_button( __( 'Search', 'dream-university-management' ), 'button', '', false ); ?>
					<?php if ( ! empty( $search ) || $status_filter !== 'all' ) : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=dreaunma-courses' ) ); ?>" class="button"><?php esc_html_e( 'Clear', 'dream-university-management' ); ?></a>
					<?php endif; ?>
				</p>
			</form>
		</div>
		
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'ID', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Course Code', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Course Name', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Credits', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Faculty', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Department', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Semester', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Status', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'dream-university-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $courses ) ) : ?>
					<tr>
						<td colspan="9"><?php esc_html_e( 'No courses found.', 'dream-university-management' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $courses as $course ) : ?>
						<?php
						// Get faculty name from JOIN or fallback
						$faculty_name = '';
						if ( ! empty( $course->faculty_name ) ) {
							$faculty_name = $course->faculty_name;
						} elseif ( ! empty( $course->faculty_id ) ) {
							$faculty = DREAUNMA_Faculty::get( $course->faculty_id );
							$faculty_name = $faculty ? $faculty->faculty_name : '';
						}
						
						// Get department name from JOIN or fallback
						$department_name = '';
						if ( ! empty( $course->department_name ) ) {
							$department_name = $course->department_name;
						} elseif ( ! empty( $course->department_id ) ) {
							$department = DREAUNMA_Department::get( $course->department_id );
							$department_name = $department ? $department->department_name : '';
						}
						
						// Fallback to old department field if new one is empty
						if ( empty( $department_name ) && ! empty( $course->department ) ) {
							$department_name = $course->department;
						}
						?>
						<tr>
							<td><strong><?php echo esc_html( $course->id ); ?></strong></td>
							<td><?php echo esc_html( $course->course_code ); ?></td>
							<td><?php echo esc_html( $course->course_name ); ?></td>
							<td><?php echo esc_html( $course->credits ); ?></td>
							<td><?php echo esc_html( $faculty_name ? $faculty_name : '-' ); ?></td>
							<td><?php echo esc_html( $department_name ? $department_name : '-' ); ?></td>
							<td><?php
							/* translators: %s: Semester number */
							echo esc_html( $course->semester ? sprintf( __( 'Semester %s', 'dream-university-management' ), $course->semester ) : '-' );
							?></td>
							<td>
								<span class="dreaunma-status <?php echo esc_attr( $course->status ); ?>">
									<?php echo esc_html( ucfirst( $course->status ) ); ?>
								</span>
							</td>
							<td>
								<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=dreaunma-courses&action=edit&id=' . $course->id ), 'dreaunma-courses-view' ) ); ?>"><?php esc_html_e( 'Edit', 'dream-university-management' ); ?></a> |
								<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=dreaunma_delete_course&id=' . $course->id ), 'dreaunma_delete_course' ) ); ?>" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this course?', 'dream-university-management' ); ?>');"><?php esc_html_e( 'Delete', 'dream-university-management' ); ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php
}

