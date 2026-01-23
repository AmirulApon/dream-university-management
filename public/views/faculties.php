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

<div class="dreaunma-frontend-faculties">
	<?php if ( empty( $faculties ) ) : ?>
		<p class="dreaunma-no-data"><?php esc_html_e( 'No faculties found.', 'dream-university-management' ); ?></p>
	<?php else : ?>
		<div class="dreaunma-faculties-grid">
			<?php foreach ( $faculties as $faculty ) : ?>
				<div class="dreaunma-faculty-card">
					<div class="dreaunma-faculty-header">
						<h3 class="dreaunma-faculty-name"><?php echo esc_html( $faculty->faculty_name ); ?></h3>
						<span class="dreaunma-faculty-code"><?php echo esc_html( $faculty->faculty_code ); ?></span>
					</div>
					<?php if ( ! empty( $faculty->description ) ) : ?>
						<div class="dreaunma-faculty-description">
							<?php echo esc_html( $faculty->description ); ?>
						</div>
					<?php endif; ?>
					<div class="dreaunma-faculty-status">
						<span class="dreaunma-status-badge <?php echo esc_attr( $faculty->status ); ?>">
							<?php echo esc_html( ucfirst( $faculty->status ) ); ?>
						</span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

