<?php
/**
 * Faculties view
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
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Faculty added successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'updated' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Faculty updated successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'deleted' ) {
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Faculty deleted successfully!', 'dream-university-management' ) . '</p></div>';
} elseif ( $message === 'error' ) {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'An error occurred. Please try again.', 'dream-university-management' ) . '</p></div>';
}

if ( $action === 'add' || $action === 'edit' ) {
	$faculty = null;
	if ( $action === 'edit' && $id > 0 ) {
		$faculty = DUM_Faculty::get( $id );
		if ( ! $faculty ) {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Faculty not found.', 'dream-university-management' ) . '</p></div>';
			return;
		}
	}
	?>
	<div class="wrap">
		<h1><?php echo $action === 'add' ? esc_html__( 'Add Faculty', 'dream-university-management' ) : esc_html__( 'Edit Faculty', 'dream-university-management' ); ?></h1>
		
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( $action === 'add' ? 'dum_add_faculty' : 'dum_edit_faculty' ); ?>
			<input type="hidden" name="action" value="<?php echo $action === 'add' ? 'dum_add_faculty' : 'dum_edit_faculty'; ?>">
			<?php if ( $action === 'edit' ) : ?>
				<input type="hidden" name="faculty_id" value="<?php echo esc_attr( $faculty->id ); ?>">
			<?php endif; ?>
			
			<table class="form-table">
				<tr>
					<th><label for="faculty_code"><?php esc_html_e( 'Faculty Code', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="text" id="faculty_code" name="faculty_code" value="<?php echo $faculty ? esc_attr( $faculty->faculty_code ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="faculty_name"><?php esc_html_e( 'Faculty Name', 'dream-university-management' ); ?> <span class="required">*</span></label></th>
					<td><input type="text" id="faculty_name" name="faculty_name" value="<?php echo $faculty ? esc_attr( $faculty->faculty_name ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="description"><?php esc_html_e( 'Description', 'dream-university-management' ); ?></label></th>
					<td><textarea id="description" name="description" rows="5" class="large-text"><?php echo $faculty ? esc_textarea( $faculty->description ) : ''; ?></textarea></td>
				</tr>
				<tr>
					<th><label for="status"><?php esc_html_e( 'Status', 'dream-university-management' ); ?></label></th>
					<td>
						<select id="status" name="status">
							<option value="active" <?php echo $faculty && $faculty->status === 'active' ? 'selected' : ''; ?>><?php esc_html_e( 'Active', 'dream-university-management' ); ?></option>
							<option value="inactive" <?php echo $faculty && $faculty->status === 'inactive' ? 'selected' : ''; ?>><?php esc_html_e( 'Inactive', 'dream-university-management' ); ?></option>
						</select>
					</td>
				</tr>
			</table>
			
			<?php submit_button( $action === 'add' ? __( 'Add Faculty', 'dream-university-management' ) : __( 'Update Faculty', 'dream-university-management' ) ); ?>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-faculties' ) ); ?>" class="button"><?php esc_html_e( 'Cancel', 'dream-university-management' ); ?></a>
		</form>
	</div>
	<?php
} else {
	$search = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
	$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'all';
	
	$faculties = DUM_Faculty::get_all( array( 
		'search' => $search,
		'status' => $status_filter,
		'limit' => 1000 
	) );
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Faculties', 'dream-university-management' ); ?></h1>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-faculties&action=add' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'dream-university-management' ); ?></a>
		
		<hr class="wp-header-end">
		
		<div class="dum-search-box">
			<form method="get" action="">
				<input type="hidden" name="page" value="dum-faculties">
				<p class="search-box">
					<label class="screen-reader-text" for="faculty-search-input"><?php esc_html_e( 'Search Faculties:', 'dream-university-management' ); ?></label>
					<input type="search" id="faculty-search-input" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search by code or name...', 'dream-university-management' ); ?>">
					<select name="status" id="status-filter">
						<option value="all" <?php selected( $status_filter, 'all' ); ?>><?php esc_html_e( 'All Status', 'dream-university-management' ); ?></option>
						<option value="active" <?php selected( $status_filter, 'active' ); ?>><?php esc_html_e( 'Active', 'dream-university-management' ); ?></option>
						<option value="inactive" <?php selected( $status_filter, 'inactive' ); ?>><?php esc_html_e( 'Inactive', 'dream-university-management' ); ?></option>
					</select>
					<?php submit_button( __( 'Search', 'dream-university-management' ), 'button', '', false ); ?>
					<?php if ( ! empty( $search ) || $status_filter !== 'all' ) : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-faculties' ) ); ?>" class="button"><?php esc_html_e( 'Clear', 'dream-university-management' ); ?></a>
					<?php endif; ?>
				</p>
			</form>
		</div>
		
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'ID', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Faculty Code', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Faculty Name', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Description', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Status', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'dream-university-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $faculties ) ) : ?>
					<tr>
						<td colspan="6"><?php esc_html_e( 'No faculties found.', 'dream-university-management' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $faculties as $faculty ) : ?>
						<?php
						$department_count = DUM_Department::count( 'all', $faculty->id );
						?>
						<tr>
							<td><strong><?php echo esc_html( $faculty->id ); ?></strong></td>
							<td><strong><?php echo esc_html( $faculty->faculty_code ); ?></strong></td>
							<td><?php echo esc_html( $faculty->faculty_name ); ?></td>
							<td><?php echo esc_html( $faculty->description ); ?></td>
							<td>
								<span class="dum-status <?php echo esc_attr( $faculty->status ); ?>">
									<?php echo esc_html( ucfirst( $faculty->status ) ); ?>
								</span>
								<br>
								<small><?php
								/* translators: %d: Number of departments */
								echo esc_html( sprintf( __( '%d departments', 'dream-university-management' ), $department_count ) );
								?></small>
							</td>
							<td>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-departments&faculty_id=' . $faculty->id ) ); ?>"><?php esc_html_e( 'View Departments', 'dream-university-management' ); ?></a> |
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=dum-faculties&action=edit&id=' . $faculty->id ) ); ?>"><?php esc_html_e( 'Edit', 'dream-university-management' ); ?></a> |
								<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=dum_delete_faculty&id=' . $faculty->id ), 'dum_delete_faculty' ) ); ?>" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this faculty?', 'dream-university-management' ); ?>');"><?php esc_html_e( 'Delete', 'dream-university-management' ); ?></a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php
}

