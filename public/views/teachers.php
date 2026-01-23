<?php
/**
 * Teachers frontend view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="dreaunma-frontend-teachers">
	<?php if ( empty( $teachers ) ) : ?>
		<p class="dreaunma-no-data"><?php esc_html_e( 'No teachers found.', 'dream-university-management' ); ?></p>
	<?php else : ?>
		<div class="dreaunma-teachers-grid">
			<?php foreach ( $teachers as $teacher ) : ?>
				<?php
				$faculty = ! empty( $teacher->faculty_id ) ? DREAUNMA_Faculty::get( $teacher->faculty_id ) : null;
				$department = ! empty( $teacher->department_id ) ? DREAUNMA_Department::get( $teacher->department_id ) : null;
				$image_url = ! empty( $teacher->image ) ? $teacher->image : '';
				?>
				<div class="dreaunma-teacher-card">
					<?php if ( ! empty( $image_url ) ) : ?>
						<div class="dreaunma-teacher-photo">
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $teacher->first_name . ' ' . $teacher->last_name ); ?>">
						</div>
					<?php endif; ?>
					<div class="dreaunma-teacher-info">
						<h3 class="dreaunma-teacher-name">
							<?php echo esc_html( $teacher->first_name . ' ' . $teacher->last_name ); ?>
						</h3>
						<div class="dreaunma-teacher-details">
							<?php if ( ! empty( $teacher->designation ) ) : ?>
								<p><strong><?php esc_html_e( 'Designation:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $teacher->designation ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $teacher->email ) ) : ?>
								<p><strong><?php esc_html_e( 'Email:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $teacher->email ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $teacher->phone ) ) : ?>
								<p><strong><?php esc_html_e( 'Phone:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $teacher->phone ); ?></p>
							<?php endif; ?>
							<?php if ( $faculty ) : ?>
								<p><strong><?php esc_html_e( 'Faculty:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $faculty->faculty_name ); ?></p>
							<?php endif; ?>
							<?php if ( $department ) : ?>
								<p><strong><?php esc_html_e( 'Department:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $department->department_name ); ?></p>
							<?php endif; ?>
						</div>
						<div class="dreaunma-teacher-status">
							<span class="dreaunma-status-badge <?php echo esc_attr( $teacher->status ); ?>">
								<?php echo esc_html( ucfirst( $teacher->status ) ); ?>
							</span>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

