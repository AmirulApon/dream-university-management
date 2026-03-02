<?php
/**
 * Students view
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
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'dreaunma-student-view' ) ) {
		wp_die( esc_html__( 'Security check failed. Please refresh the page and try again.', 'dream-university-management' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=dreaunma-students' ) ) . '">' . esc_html__( 'Go Back', 'dream-university-management' ) . '</a>' );
	}
}

$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : 'list';

$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';

// Show messages
if ( $message === 'added' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Student added successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'updated' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Student updated successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'deleted' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Student deleted successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'error' ) {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'An error occurred. Please try again.', 'dream-university-management' ) . '</p></div>';
}

if ( $action === 'add' || $action === 'edit' ) {
	$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
	$student = null;
	if ( $action === 'edit' && $id > 0 ) {
		$student = DREAUNMA_Student::get( $id );
		if ( ! $student ) {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Student not found.', 'dream-university-management' ) . '</p></div>';
			return;
		}
	}
	?>
	<div class="wrap">
		<h1><?php echo $action === 'add' ? esc_html__( 'Add Student', 'dream-university-management' ) : esc_html__( 'Edit Student', 'dream-university-management' ); ?></h1>
		
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( $action === 'add' ? 'dreaunma_add_student' : 'dreaunma_edit_student' ); ?>
			<input type="hidden" name="action" value="<?php echo $action === 'add' ? 'dreaunma_add_student' : 'dreaunma_edit_student'; ?>">
			<?php if ( $action === 'edit' ) : ?>
				<input type="hidden" name="student_id" value="<?php echo esc_attr( $student->id ); ?>">
			<?php endif; ?>
			
			<table class="form-table">
				<tr>
					<th><label for="student_id"><?php esc_html_e( 'Student ID', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="text" id="student_id" name="student_id" value="<?php echo $student ? esc_attr( $student->student_id ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="first_name"><?php esc_html_e( 'First Name', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="text" id="first_name" name="first_name" value="<?php echo $student ? esc_attr( $student->first_name ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="last_name"><?php esc_html_e( 'Last Name', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="text" id="last_name" name="last_name" value="<?php echo $student ? esc_attr( $student->last_name ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="email"><?php esc_html_e( 'Email', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="email" id="email" name="email" value="<?php echo $student ? esc_attr( $student->email ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="phone"><?php esc_html_e( 'Phone', 'dream-university-management' ); ?></label></th>
					<td><input type="text" id="phone" name="phone" value="<?php echo $student ? esc_attr( $student->phone ) : ''; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="date_of_birth"><?php esc_html_e( 'Date of Birth', 'dream-university-management' ); ?></label></th>
					<td><input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $student ? esc_attr( $student->date_of_birth ) : ''; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="gender"><?php esc_html_e( 'Gender', 'dream-university-management' ); ?></label></th>
					<td>
						<select id="gender" name="gender">
							<option value=""><?php esc_html_e( 'Select', 'dream-university-management' ); ?></option>
							<option value="male" <?php echo $student && $student->gender === 'male' ? 'selected' : ''; ?>><?php esc_html_e( 'Male', 'dream-university-management' ); ?></option>
							<option value="female" <?php echo $student && $student->gender === 'female' ? 'selected' : ''; ?>><?php esc_html_e( 'Female', 'dream-university-management' ); ?></option>
							<option value="other" <?php echo $student && $student->gender === 'other' ? 'selected' : ''; ?>><?php esc_html_e( 'Other', 'dream-university-management' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="address"><?php esc_html_e( 'Address', 'dream-university-management' ); ?></label></th>
					<td><textarea id="address" name="address" rows="3" class="large-text"><?php echo $student ? esc_textarea( $student->address ) : ''; ?></textarea></td>
				</tr>
				<tr>
					<th><label for="image"><?php esc_html_e( 'Photo', 'dream-university-management' ); ?></label></th>
					<td>
						<?php
						$image_id = 0;
						$image_url = '';
						if ( $student && ! empty( $student->image ) ) {
							$image_url = $student->image;
							// Try to find attachment ID from URL
							$attachment_id = attachment_url_to_postid( $image_url );
							if ( $attachment_id ) {
								$image_id = $attachment_id;
							}
						}
						?>
						<div class="dreaunma-image-upload-wrapper">
							<input type="hidden" id="image_id" name="image_id" value="<?php echo esc_attr( $image_id ); ?>">
							<div class="dreaunma-image-preview" style="margin-bottom: 10px;">
								<?php if ( $image_url ) : ?>
									<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'Student Photo', 'dream-university-management' ); ?>" style="max-width: 150px; height: auto; border: 1px solid #ddd; padding: 5px; display: block; margin-bottom: 10px;" id="image_preview">
								<?php else : ?>
									<img src="" alt="" style="max-width: 150px; height: auto; border: 1px solid #ddd; padding: 5px; display: none; margin-bottom: 10px;" id="image_preview">
								<?php endif; ?>
							</div>
							<button type="button" class="button dum-upload-image-button" data-target="image_id" data-preview="image_preview">
								<?php esc_html_e( 'Select Image from Media Library', 'dream-university-management' ); ?>
							</button>
							<button type="button" class="button dum-remove-image-button" data-target="image_id" data-preview="image_preview" style="<?php echo $image_url ? '' : 'display:none;'; ?>">
								<?php esc_html_e( 'Remove Image', 'dream-university-management' ); ?>
							</button>
							<p class="description"><?php esc_html_e( 'Select an image from WordPress Media Library.', 'dream-university-management' ); ?></p>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="session"><?php esc_html_e( 'Session', 'dream-university-management' ); ?></label></th>
					<td><input type="text" id="session" name="session" value="<?php echo $student ? esc_attr( $student->session ) : ''; ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g., 2023-2024', 'dream-university-management' ); ?>"></td>
				</tr>
				<tr>
					<th><label for="level"><?php esc_html_e( 'Level', 'dream-university-management' ); ?></label></th>
					<td>
						<select id="level" name="level">
							<option value=""><?php esc_html_e( 'Select Level', 'dream-university-management' ); ?></option>
							<option value="Level 1" <?php echo $student && $student->level === 'Level 1' ? 'selected' : ''; ?>><?php esc_html_e( 'Level 1', 'dream-university-management' ); ?></option>
							<option value="Level 2" <?php echo $student && $student->level === 'Level 2' ? 'selected' : ''; ?>><?php esc_html_e( 'Level 2', 'dream-university-management' ); ?></option>
							<option value="Level 3" <?php echo $student && $student->level === 'Level 3' ? 'selected' : ''; ?>><?php esc_html_e( 'Level 3', 'dream-university-management' ); ?></option>
							<option value="Level 4" <?php echo $student && $student->level === 'Level 4' ? 'selected' : ''; ?>><?php esc_html_e( 'Level 4', 'dream-university-management' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="admission_date"><?php esc_html_e( 'Admission Date', 'dream-university-management' ); ?></label></th>
					<td><input type="date" id="admission_date" name="admission_date" value="<?php echo $student ? esc_attr( $student->admission_date ) : ''; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="faculty_id"><?php esc_html_e( 'Faculty', 'dream-university-management' ); ?></label></th>
					<td>
						<?php
						$faculties = DREAUNMA_Faculty::get_all( array( 'status' => 'active', 'limit' => 1000 ) );
						$selected_faculty_id = $student ? intval( $student->faculty_id ) : 0;
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
								$selected_department_id = $student ? intval( $student->department_id ) : 0;
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
					</td>
				</tr>
				<tr>
					<th><label for="status"><?php esc_html_e( 'Status', 'dream-university-management' ); ?></label></th>
					<td>
						<select id="status" name="status">
							<option value="active" <?php echo $student && $student->status === 'active' ? 'selected' : ''; ?>><?php esc_html_e( 'Active', 'dream-university-management' ); ?></option>
							<option value="inactive" <?php echo $student && $student->status === 'inactive' ? 'selected' : ''; ?>><?php esc_html_e( 'Inactive', 'dream-university-management' ); ?></option>
						</select>
					</td>
				</tr>
			</table>
			
			<?php submit_button( $action === 'add' ? __( 'Add Student', 'dream-university-management' ) : __( 'Update Student', 'dream-university-management' ) ); ?>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=dreaunma-students' ) ); ?>" class="button"><?php esc_html_e( 'Cancel', 'dream-university-management' ); ?></a>
		</form>
	</div>
	<?php
} else {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
	$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
	$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : 'all';
	
	$students = DREAUNMA_Student::get_all( array( 
		'search' => $search,
		'status' => $status_filter,
		'limit' => 1000 
	) );
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Students', 'dream-university-management' ); ?></h1>
		<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=dreaunma-students&action=add' ), 'dreaunma-student-view' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'dream-university-management' ); ?></a>
		
		<hr class="wp-header-end">
		
		<div class="dreaunma-search-box">
			<form method="get" action="">
				<input type="hidden" name="page" value="dreaunma-students">
				<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'dreaunma-student-view' ) ); ?>">
				<p class="search-box">
					<label class="screen-reader-text" for="student-search-input"><?php esc_html_e( 'Search Students:', 'dream-university-management' ); ?></label>
					<input type="search" id="student-search-input" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search by ID, name, or email...', 'dream-university-management' ); ?>">
					<select name="status" id="status-filter">
						<option value="all" <?php selected( $status_filter, 'all' ); ?>><?php esc_html_e( 'All Status', 'dream-university-management' ); ?></option>
						<option value="active" <?php selected( $status_filter, 'active' ); ?>><?php esc_html_e( 'Active', 'dream-university-management' ); ?></option>
						<option value="inactive" <?php selected( $status_filter, 'inactive' ); ?>><?php esc_html_e( 'Inactive', 'dream-university-management' ); ?></option>
					</select>
					<?php submit_button( __( 'Search', 'dream-university-management' ), 'button', '', false ); ?>
					<?php if ( ! empty( $search ) || $status_filter !== 'all' ) : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=dreaunma-students' ) ); ?>" class="button"><?php esc_html_e( 'Clear', 'dream-university-management' ); ?></a>
					<?php endif; ?>
				</p>
			</form>
		</div>
		
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'ID', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Photo', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Student ID', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Name', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Email', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Faculty', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Department', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Session', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Level', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Phone', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Admission Date', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Status', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'dream-university-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $students ) ) : ?>
					<tr>
						<td colspan="13"><?php esc_html_e( 'No students found.', 'dream-university-management' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $students as $student ) : ?>
						<?php
						$faculty_name = '';
						$department_name = '';
						if ( ! empty( $student->faculty_id ) ) {
							$faculty = DREAUNMA_Faculty::get( $student->faculty_id );
							$faculty_name = $faculty ? $faculty->faculty_name : '';
						}
						if ( ! empty( $student->department_id ) ) {
							$department = DREAUNMA_Department::get( $student->department_id );
							$department_name = $department ? $department->department_name : '';
						}
						$image_url = ! empty( $student->image ) ? esc_url( $student->image ) : '';
						?>
						<tr>
							<td><strong><?php echo esc_html( $student->id ); ?></strong></td>
							<td>
								<?php if ( $image_url ) : ?>
									<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $student->first_name . ' ' . $student->last_name ); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
								<?php else : ?>
									<span class="dashicons dashicons-admin-users" style="font-size: 40px; color: #ccc;"></span>
								<?php endif; ?>
							</td>
							<td><?php echo esc_html( $student->student_id ); ?></td>
							<td><?php echo esc_html( $student->first_name . ' ' . $student->last_name ); ?></td>
							<td><?php echo esc_html( $student->email ); ?></td>
							<td><?php echo esc_html( $faculty_name ); ?></td>
							<td><?php echo esc_html( $department_name ); ?></td>
							<td><?php echo esc_html( $student->session ); ?></td>
							<td><?php echo esc_html( $student->level ); ?></td>
							<td><?php echo esc_html( $student->phone ); ?></td>
							<td><?php echo esc_html( $student->admission_date ); ?></td>
							<td>
								<span class="dreaunma-status <?php echo esc_attr( $student->status ); ?>">
									<?php echo esc_html( ucfirst( $student->status ) ); ?>
								</span>
							</td>
							<td>
								<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=dreaunma-students&action=edit&id=' . $student->id ), 'dreaunma-student-view' ) ); ?>"><?php esc_html_e( 'Edit', 'dream-university-management' ); ?></a> |
								<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=dreaunma_delete_student&id=' . $student->id ), 'dreaunma_delete_student' ) ); ?>" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this student?', 'dream-university-management' ); ?>');"><?php esc_html_e( 'Delete', 'dream-university-management' ); ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php
}

