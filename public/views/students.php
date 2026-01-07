<?php
/**
 * Students frontend view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="dum-frontend-students">
	<?php if ( empty( $students ) ) : ?>
		<p class="dum-no-data"><?php esc_html_e( 'No students found.', 'dream-university-management' ); ?></p>
	<?php else : ?>
		<div class="dum-students-grid">
			<?php foreach ( $students as $student ) : ?>
				<?php
				$faculty = ! empty( $student->faculty_id ) ? DUM_Faculty::get( $student->faculty_id ) : null;
				$department = ! empty( $student->department_id ) ? DUM_Department::get( $student->department_id ) : null;
				$image_url = ! empty( $student->image ) ? $student->image : '';
				?>
				<div class="dum-student-card">
					<?php if ( ! empty( $image_url ) ) : ?>
						<div class="dum-student-photo">
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $student->first_name . ' ' . $student->last_name ); ?>">
						</div>
					<?php endif; ?>
					<div class="dum-student-info">
						<h3 class="dum-student-name">
							<?php echo esc_html( $student->first_name . ' ' . $student->last_name ); ?>
						</h3>
						<div class="dum-student-details">
							<p><strong><?php esc_html_e( 'Student ID:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $student->student_id ); ?></p>
							<?php if ( ! empty( $student->email ) ) : ?>
								<p><strong><?php esc_html_e( 'Email:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $student->email ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $student->phone ) ) : ?>
								<p><strong><?php esc_html_e( 'Phone:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $student->phone ); ?></p>
							<?php endif; ?>
							<?php if ( $faculty ) : ?>
								<p><strong><?php esc_html_e( 'Faculty:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $faculty->faculty_name ); ?></p>
							<?php endif; ?>
							<?php if ( $department ) : ?>
								<p><strong><?php esc_html_e( 'Department:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $department->department_name ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $student->semester ) ) : ?>
								<p><strong><?php esc_html_e( 'Semester:', 'dream-university-management' ); ?></strong> <?php echo esc_html( $student->semester ); ?></p>
							<?php endif; ?>
						</div>
						<div class="dum-student-status">
							<span class="dum-status-badge <?php echo esc_attr( $student->status ); ?>">
								<?php echo esc_html( ucfirst( $student->status ) ); ?>
							</span>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

