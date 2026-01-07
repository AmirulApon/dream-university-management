<?php
/**
 * Departments frontend view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="dum-frontend-departments">
	<?php if ( empty( $departments ) ) : ?>
		<p class="dum-no-data"><?php esc_html_e( 'No departments found.', 'dream-university-management' ); ?></p>
	<?php else : ?>
		<table class="dum-frontend-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Department Code', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Department Name', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Faculty', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Status', 'dream-university-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $departments as $department ) : ?>
					<?php
					$faculty = DUM_Faculty::get( $department->faculty_id );
					$faculty_name = $faculty ? $faculty->faculty_name : '-';
					?>
					<tr>
						<td><?php echo esc_html( $department->department_code ); ?></td>
						<td>
							<strong><?php echo esc_html( $department->department_name ); ?></strong>
							<?php if ( ! empty( $department->description ) ) : ?>
								<br><small><?php echo esc_html( $department->description ); ?></small>
							<?php endif; ?>
						</td>
						<td><?php echo esc_html( $faculty_name ); ?></td>
						<td>
							<span class="dum-status-badge <?php echo esc_attr( $department->status ); ?>">
								<?php echo esc_html( ucfirst( $department->status ) ); ?>
							</span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>

