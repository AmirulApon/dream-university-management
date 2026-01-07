<?php
/**
 * Faculties frontend view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="dum-frontend-faculties">
	<?php if ( empty( $faculties ) ) : ?>
		<p class="dum-no-data"><?php esc_html_e( 'No faculties found.', 'dream-university-management' ); ?></p>
	<?php else : ?>
		<div class="dum-faculties-grid">
			<?php foreach ( $faculties as $faculty ) : ?>
				<div class="dum-faculty-card">
					<div class="dum-faculty-header">
						<h3 class="dum-faculty-name"><?php echo esc_html( $faculty->faculty_name ); ?></h3>
						<span class="dum-faculty-code"><?php echo esc_html( $faculty->faculty_code ); ?></span>
					</div>
					<?php if ( ! empty( $faculty->description ) ) : ?>
						<div class="dum-faculty-description">
							<?php echo esc_html( $faculty->description ); ?>
						</div>
					<?php endif; ?>
					<div class="dum-faculty-status">
						<span class="dum-status-badge <?php echo esc_attr( $faculty->status ); ?>">
							<?php echo esc_html( ucfirst( $faculty->status ) ); ?>
						</span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

