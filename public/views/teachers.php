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

<div class="dum-frontend-teachers">
	<?php if ( empty( $teachers ) ) : ?>
		<p class="dum-no-data"><?php esc_html_e( 'No teachers found.', 'dream-university-management' ); ?></p>
	<?php else : ?>
		<div class="dum-teachers-grid">
			<?php foreach ( $teachers as $teacher ) : ?>
				<?php
				$faculty = ! empty( $teacher->faculty_id ) ? DUM_Faculty::get( $teacher->faculty_id ) : null;
				$department = ! empty( $teacher->department_id ) ? DUM_Department::get( $teacher->department_id ) : null;
				$image_url = ! empty( $teacher->image ) ? $teacher->image : '';
				?>
				<div class="dum-teacher-card">
					<?php if ( ! empty( $image_url ) ) : ?>
						<div class="dum-teacher-photo">
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $teacher->first_name . ' ' . $teacher->last_name ); ?>">
						</div>
					<?php endif; ?>
					<div class="dum-teacher-info">
						<h3 class="dum-teacher-name">
							<?php echo esc_html( $teacher->first_name . ' ' . $teacher->last_name ); ?>
						</h3>
						<div class="dum-teacher-details">
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
						<div class="dum-teacher-status">
							<span class="dum-status-badge <?php echo esc_attr( $teacher->status ); ?>">
								<?php echo esc_html( ucfirst( $teacher->status ) ); ?>
							</span>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

