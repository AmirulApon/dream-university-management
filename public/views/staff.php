<?php
/**
 * Staff frontend view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="dreaunma-frontend-staff">
	<?php if ( empty( $staff ) ) : ?>
		<p class="dreaunma-no-data"><?php esc_html_e( 'No staff found.', 'dream-university-management' ); ?></p>
	<?php else : ?>
		<div class="dreaunma-staff-grid">
			<?php foreach ( $staff as $staff_member ) : ?>
				<?php
				$image_url = ! empty( $staff_member->image ) ? $staff_member->image : '';
				?>
				<div class="dreaunma-staff-card">
					<?php if ( ! empty( $image_url ) ) : ?>
						<div class="dreaunma-staff-photo">
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $staff_member->first_name . ' ' . $staff_member->last_name ); ?>">
						</div>
					<?php endif; ?>
					<div class="dreaunma-staff-info">
						<h3 class="dreaunma-staff-name">
							<?php echo esc_html( $staff_member->first_name . ' ' . $staff_member->last_name ); ?>
						</h3>
						<div class="dreaunma-staff-details">
							<?php if ( ! empty( $staff_member->position ) ) : ?>
								<p><strong><?php esc_html_e( 'Position:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $staff_member->position ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $staff_member->email ) ) : ?>
								<p><strong><?php esc_html_e( 'Email:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $staff_member->email ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $staff_member->phone ) ) : ?>
								<p><strong><?php esc_html_e( 'Phone:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $staff_member->phone ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $staff_member->department ) ) : ?>
								<p><strong><?php esc_html_e( 'Department:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $staff_member->department ); ?></p>
							<?php endif; ?>
						</div>
						<div class="dreaunma-staff-status">
							<span class="dreaunma-status-badge <?php echo esc_attr( $staff_member->status ); ?>">
								<?php echo esc_html( ucfirst( $staff_member->status ) ); ?>
							</span>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

