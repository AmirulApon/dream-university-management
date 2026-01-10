<?php
/**
 * Staff view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : 'list';
// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
$message = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';

// Show messages
if ( $message === 'added' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Staff added successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'updated' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Staff updated successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'deleted' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Staff deleted successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'error' ) {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'An error occurred. Please try again.', 'dream-university-management' ) . '</p></div>';
}

if ( $action === 'add' || $action === 'edit' ) {
	$staff = null;
	if ( $action === 'edit' && $id > 0 ) {
		$staff = DUM_Staff::get( $id );
		if ( ! $staff ) {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Staff not found.', 'dream-university-management' ) . '</p></div>';
			return;
		}
	}
	?>
	<div class="wrap">
		<h1><?php echo $action === 'add' ? esc_html__( 'Add Staff', 'dream-university-management' ) : esc_html__( 'Edit Staff', 'dream-university-management' ); ?></h1>
		
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( $action === 'add' ? 'dum_add_staff' : 'dum_edit_staff' ); ?>
			<input type="hidden" name="action" value="<?php echo $action === 'add' ? 'dum_add_staff' : 'dum_edit_staff'; ?>">
			<?php if ( $action === 'edit' ) : ?>
				<input type="hidden" name="staff_id" value="<?php echo esc_attr( $staff->id ); ?>">
			<?php endif; ?>
			
			<table class="form-table">
				<tr>
					<th><label for="staff_id"><?php esc_html_e( 'Staff ID', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="text" id="staff_id" name="staff_id" value="<?php echo $staff ? esc_attr( $staff->staff_id ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="first_name"><?php esc_html_e( 'First Name', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="text" id="first_name" name="first_name" value="<?php echo $staff ? esc_attr( $staff->first_name ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="last_name"><?php esc_html_e( 'Last Name', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="text" id="last_name" name="last_name" value="<?php echo $staff ? esc_attr( $staff->last_name ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="email"><?php esc_html_e( 'Email', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="email" id="email" name="email" value="<?php echo $staff ? esc_attr( $staff->email ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="phone"><?php esc_html_e( 'Phone', 'dream-university-management' ); ?></label></th>
					<td><input type="text" id="phone" name="phone" value="<?php echo $staff ? esc_attr( $staff->phone ) : ''; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="image"><?php esc_html_e( 'Photo', 'dream-university-management' ); ?></label></th>
					<td>
						<?php
						$image_id = 0;
						$image_url = '';
						if ( $staff && ! empty( $staff->image ) ) {
							$image_url = $staff->image;
							// Try to find attachment ID from URL
							$attachment_id = attachment_url_to_postid( $image_url );
							if ( $attachment_id ) {
								$image_id = $attachment_id;
							}
						}
						?>
						<div class="dum-image-upload-wrapper">
							<input type="hidden" id="image_id" name="image_id" value="<?php echo esc_attr( $image_id ); ?>">
							<div class="dum-image-preview" style="margin-bottom: 10px;">
								<?php if ( $image_url ) : ?>
									<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'Staff Photo', 'dream-university-management' ); ?>" style="max-width: 150px; height: auto; border: 1px solid #ddd; padding: 5px; display: block; margin-bottom: 10px;" id="image_preview">
								<?php else : ?>
									<img src="" alt="" style="max-width: 150px; height: auto; border: 1px solid #ddd; padding: 5px; display: none; margin-bottom: 10px;" id="image_preview">
								<?php endif; ?>
							</div>
							<button type="button" class="button dum-upload-image-button" data-target="image_id" data-preview="image_preview">
								<?php esc_html_e( 'Select Image from Media Library', 'dream-university-management' ); ?>
							</button>
							<button type="button" class="button dum-remove-image-button" data-target="image_id" data-preview="image_preview" style="<?php echo $image_url ? '' : 'display:none;'; ?>">
								<?php esc_html_e( 'Remove Image', 'dream-university-management' ); ?></button>
							<p class="description"><?php esc_html_e( 'Select an image from WordPress Media Library.', 'dream-university-management' ); ?></p>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="department"><?php esc_html_e( 'Department', 'dream-university-management' ); ?></label></th>
					<td><input type="text" id="department" name="department" value="<?php echo $staff ? esc_attr( $staff->department ) : ''; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="position"><?php esc_html_e( 'Position', 'dream-university-management' ); ?></label></th>
					<td><input type="text" id="position" name="position" value="<?php echo $staff ? esc_attr( $staff->position ) : ''; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="hire_date"><?php esc_html_e( 'Hire Date', 'dream-university-management' ); ?></label></th>
					<td><input type="date" id="hire_date" name="hire_date" value="<?php echo $staff ? esc_attr( $staff->hire_date ) : ''; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="status"><?php esc_html_e( 'Status', 'dream-university-management' ); ?></label></th>
					<td>
						<select id="status" name="status">
							<option value="active" <?php echo $staff && $staff->status === 'active' ? 'selected' : ''; ?>><?php esc_html_e( 'Active', 'dream-university-management' ); ?></option>
							<option value="inactive" <?php echo $staff && $staff->status === 'inactive' ? 'selected' : ''; ?>><?php esc_html_e( 'Inactive', 'dream-university-management' ); ?></option>
						</select>
					</td>
				</tr>
			</table>
			
			<?php submit_button( $action === 'add' ? __( 'Add Staff', 'dream-university-management' ) : __( 'Update Staff', 'dream-university-management' ) ); ?>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-staff' ) ); ?>" class="button"><?php esc_html_e( 'Cancel', 'dream-university-management' ); ?></a>
		</form>
	</div>
	<?php
} else {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
	$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- View file, not processing form data
	$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : 'all';
	
	$staff_members = DUM_Staff::get_all( array( 
		'search' => $search,
		'status' => $status_filter,
		'limit' => 1000 
	) );
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Staff', 'dream-university-management' ); ?></h1>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-staff&action=add' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'dream-university-management' ); ?></a>
		
		<hr class="wp-header-end">
		
		<div class="dum-search-box">
			<form method="get" action="">
				<input type="hidden" name="page" value="dum-staff">
				<p class="search-box">
					<label class="screen-reader-text" for="staff-search-input"><?php esc_html_e( 'Search Staff:', 'dream-university-management' ); ?></label>
					<input type="search" id="staff-search-input" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search by ID, name, email, or department...', 'dream-university-management' ); ?>">
					<select name="status" id="status-filter">
						<option value="all" <?php selected( $status_filter, 'all' ); ?>><?php esc_html_e( 'All Status', 'dream-university-management' ); ?></option>
						<option value="active" <?php selected( $status_filter, 'active' ); ?>><?php esc_html_e( 'Active', 'dream-university-management' ); ?></option>
						<option value="inactive" <?php selected( $status_filter, 'inactive' ); ?>><?php esc_html_e( 'Inactive', 'dream-university-management' ); ?></option>
					</select>
					<?php submit_button( __( 'Search', 'dream-university-management' ), 'button', '', false ); ?>
					<?php if ( ! empty( $search ) || $status_filter !== 'all' ) : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-staff' ) ); ?>" class="button"><?php esc_html_e( 'Clear', 'dream-university-management' ); ?></a>
					<?php endif; ?>
				</p>
			</form>
		</div>
		
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'ID', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Photo', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Staff ID', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Name', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Email', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Department', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Position', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Status', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'dream-university-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $staff_members ) ) : ?>
					<tr>
						<td colspan="9"><?php esc_html_e( 'No staff found.', 'dream-university-management' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $staff_members as $staff ) : ?>
						<?php
						$image_url = ! empty( $staff->image ) ? esc_url( $staff->image ) : '';
						?>
						<tr>
							<td><strong><?php echo esc_html( $staff->id ); ?></strong></td>
							<td>
								<?php if ( $image_url ) : ?>
									<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $staff->first_name . ' ' . $staff->last_name ); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
								<?php else : ?>
									<span class="dashicons dashicons-admin-users" style="font-size: 40px; color: #ccc;"></span>
								<?php endif; ?>
							</td>
							<td><?php echo esc_html( $staff->staff_id ); ?></td>
							<td><?php echo esc_html( $staff->first_name . ' ' . $staff->last_name ); ?></td>
							<td><?php echo esc_html( $staff->email ); ?></td>
							<td><?php echo esc_html( $staff->department ); ?></td>
							<td><?php echo esc_html( $staff->position ); ?></td>
							<td>
								<span class="dum-status <?php echo esc_attr( $staff->status ); ?>">
									<?php echo esc_html( ucfirst( $staff->status ) ); ?>
								</span>
							</td>
							<td>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-staff&action=edit&id=' . $staff->id ) ); ?>"><?php esc_html_e( 'Edit', 'dream-university-management' ); ?></a> |
								<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=dum_delete_staff&id=' . $staff->id ), 'dum_delete_staff' ) ); ?>" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this staff?', 'dream-university-management' ); ?>');"><?php esc_html_e( 'Delete', 'dream-university-management' ); ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php
}

