<?php
/**
 * Departments view
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
$faculty_filter = isset( $_GET['faculty_id'] ) ? intval( $_GET['faculty_id'] ) : 0;

// Show messages
if ( $message === 'added' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Department added successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'updated' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Department updated successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'deleted' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Department deleted successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'error' ) {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'An error occurred. Please try again.', 'dream-university-management' ) . '</p></div>';
}

if ( $action === 'add' || $action === 'edit' ) {
	$department = null;
	if ( $action === 'edit' && $id > 0 ) {
		$department = DUM_Department::get( $id );
		if ( ! $department ) {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Department not found.', 'dream-university-management' ) . '</p></div>';
			return;
		}
	}
	
	$faculties = DUM_Faculty::get_all( array( 'status' => 'active', 'limit' => 1000 ) );
	?>
	<div class="wrap">
		<h1><?php echo $action === 'add' ? esc_html__( 'Add Department', 'dream-university-management' ) : esc_html__( 'Edit Department', 'dream-university-management' ); ?></h1>
		
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( $action === 'add' ? 'dum_add_department' : 'dum_edit_department' ); ?>
			<input type="hidden" name="action" value="<?php echo $action === 'add' ? 'dum_add_department' : 'dum_edit_department'; ?>">
			<?php if ( $action === 'edit' ) : ?>
				<input type="hidden" name="department_id" value="<?php echo esc_attr( $department->id ); ?>">
			<?php endif; ?>
			
			<table class="form-table">
				<tr>
					<th><label for="faculty_id"><?php esc_html_e( 'Faculty', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td>
						<select id="faculty_id" name="faculty_id" required>
							<option value=""><?php esc_html_e( 'Select Faculty', 'dream-university-management' ); ?></option>
							<?php foreach ( $faculties as $faculty ) : ?>
								<option value="<?php echo esc_attr( $faculty->id ); ?>" <?php selected( $department ? $department->faculty_id : $faculty_filter, $faculty->id ); ?>>
									<?php echo esc_html( $faculty->faculty_code . ' - ' . $faculty->faculty_name ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="department_code"><?php esc_html_e( 'Department Code', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="text" id="department_code" name="department_code" value="<?php echo $department ? esc_attr( $department->department_code ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="department_name"><?php esc_html_e( 'Department Name', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="text" id="department_name" name="department_name" value="<?php echo $department ? esc_attr( $department->department_name ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="description"><?php esc_html_e( 'Description', 'dream-university-management' ); ?></label></th>
					<td><textarea id="description" name="description" rows="5" class="large-text"><?php echo $department ? esc_textarea( $department->description ) : ''; ?></textarea></td>
				</tr>
				<tr>
					<th><label for="status"><?php esc_html_e( 'Status', 'dream-university-management' ); ?></label></th>
					<td>
						<select id="status" name="status">
							<option value="active" <?php echo $department && $department->status === 'active' ? 'selected' : ''; ?>><?php esc_html_e( 'Active', 'dream-university-management' ); ?></option>
							<option value="inactive" <?php echo $department && $department->status === 'inactive' ? 'selected' : ''; ?>><?php esc_html_e( 'Inactive', 'dream-university-management' ); ?></option>
						</select>
					</td>
				</tr>
			</table>
			
			<?php submit_button( $action === 'add' ? __( 'Add Department', 'dream-university-management' ) : __( 'Update Department', 'dream-university-management' ) ); ?>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-departments' . ( $faculty_filter ? '&faculty_id=' . $faculty_filter : '' ) ) ); ?>" class="button"><?php esc_html_e( 'Cancel', 'dream-university-management' ); ?></a>
		</form>
	</div>
	<?php
} else {
	$search = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
	$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'all';
	
	$departments = DUM_Department::get_all( array( 
		'search' => $search,
		'status' => $status_filter,
		'faculty_id' => $faculty_filter,
		'limit' => 1000 
	) );
	
	$faculties = DUM_Faculty::get_all( array( 'status' => 'all', 'limit' => 1000 ) );
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Departments', 'dream-university-management' ); ?></h1>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-departments&action=add' . ( $faculty_filter ? '&faculty_id=' . $faculty_filter : '' ) ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'dream-university-management' ); ?></a>
		
		<hr class="wp-header-end">
		
		<div class="dum-search-box">
			<form method="get" action="">
				<input type="hidden" name="page" value="dum-departments">
				<p class="search-box">
					<label class="screen-reader-text" for="department-search-input"><?php esc_html_e( 'Search Departments:', 'dream-university-management' ); ?></label>
					<input type="search" id="department-search-input" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search by code, name, or faculty...', 'dream-university-management' ); ?>">
					<select name="faculty_id" id="faculty-filter">
						<option value="0"><?php esc_html_e( 'All Faculties', 'dream-university-management' ); ?></option>
						<?php foreach ( $faculties as $faculty ) : ?>
							<option value="<?php echo esc_attr( $faculty->id ); ?>" <?php selected( $faculty_filter, $faculty->id ); ?>>
								<?php echo esc_html( $faculty->faculty_code . ' - ' . $faculty->faculty_name ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<select name="status" id="status-filter">
						<option value="all" <?php selected( $status_filter, 'all' ); ?>><?php esc_html_e( 'All Status', 'dream-university-management' ); ?></option>
						<option value="active" <?php selected( $status_filter, 'active' ); ?>><?php esc_html_e( 'Active', 'dream-university-management' ); ?></option>
						<option value="inactive" <?php selected( $status_filter, 'inactive' ); ?>><?php esc_html_e( 'Inactive', 'dream-university-management' ); ?></option>
					</select>
					<?php submit_button( __( 'Search', 'dream-university-management' ), 'button', '', false ); ?>
					<?php if ( ! empty( $search ) || $status_filter !== 'all' || $faculty_filter > 0 ) : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-departments' ) ); ?>" class="button"><?php esc_html_e( 'Clear', 'dream-university-management' ); ?></a>
					<?php endif; ?>
				</p>
			</form>
		</div>
		
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Department Code', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Department Name', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Faculty', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Description', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Status', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'dream-university-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $departments ) ) : ?>
					<tr>
						<td colspan="6"><?php esc_html_e( 'No departments found.', 'dream-university-management' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $departments as $department ) : ?>
						<tr>
							<td><strong><?php echo esc_html( $department->department_code ); ?></strong></td>
							<td><?php echo esc_html( $department->department_name ); ?></td>
							<td><?php echo esc_html( $department->faculty_code . ' - ' . $department->faculty_name ); ?></td>
							<td><?php echo esc_html( $department->description ); ?></td>
							<td>
								<span class="dum-status <?php echo esc_attr( $department->status ); ?>">
									<?php echo esc_html( ucfirst( $department->status ) ); ?>
								</span>
							</td>
							<td>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-departments&action=edit&id=' . $department->id ) ); ?>"><?php esc_html_e( 'Edit', 'dream-university-management' ); ?></a> |
								<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=dum_delete_department&id=' . $department->id ), 'dum_delete_department' ) ); ?>" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this department?', 'dream-university-management' ); ?>');"><?php esc_html_e( 'Delete', 'dream-university-management' ); ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php
}

