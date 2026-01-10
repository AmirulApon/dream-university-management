<?php
/**
 * Settings view
 *
 * @package Dream_University_Management
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get saved grade settings or use defaults
$default_grades = array(
	array( 'grade' => 'A+', 'grade_point' => 4.0, 'min_percentage' => 80, 'max_percentage' => 100 ),
	array( 'grade' => 'A', 'grade_point' => 3.75, 'min_percentage' => 75, 'max_percentage' => 79 ),
	array( 'grade' => 'A-', 'grade_point' => 3.5, 'min_percentage' => 70, 'max_percentage' => 74 ),
	array( 'grade' => 'B+', 'grade_point' => 3.25, 'min_percentage' => 65, 'max_percentage' => 69 ),
	array( 'grade' => 'B', 'grade_point' => 3.0, 'min_percentage' => 60, 'max_percentage' => 64 ),
	array( 'grade' => 'B-', 'grade_point' => 2.75, 'min_percentage' => 55, 'max_percentage' => 59 ),
	array( 'grade' => 'C+', 'grade_point' => 2.5, 'min_percentage' => 50, 'max_percentage' => 54 ),
	array( 'grade' => 'C', 'grade_point' => 2.25, 'min_percentage' => 45, 'max_percentage' => 49 ),
	array( 'grade' => 'D', 'grade_point' => 2.0, 'min_percentage' => 40, 'max_percentage' => 44 ),
	array( 'grade' => 'F', 'grade_point' => 0.0, 'min_percentage' => 0, 'max_percentage' => 39 ),
);

$grade_settings = get_option( 'dum_grade_settings', $default_grades );

// Handle form submission
if ( isset( $_POST['dum_save_grade_settings'] ) && check_admin_referer( 'dum_save_grade_settings' ) ) {
	$saved_grades = array();
	
	if ( isset( $_POST['grades'] ) && is_array( $_POST['grades'] ) ) {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Grades array is unslashed and sanitized in the loop below
		$grades_array = wp_unslash( $_POST['grades'] );
		foreach ( $grades_array as $index => $grade_data ) {
			$grade = sanitize_text_field( $grade_data['grade'] ?? '' );
			$grade_point = floatval( $grade_data['grade_point'] ?? 0 );
			$min_percentage = floatval( $grade_data['min_percentage'] ?? 0 );
			$max_percentage = floatval( $grade_data['max_percentage'] ?? 0 );
			
			if ( ! empty( $grade ) ) {
				$saved_grades[] = array(
					'grade' => $grade,
					'grade_point' => $grade_point,
					'min_percentage' => $min_percentage,
					'max_percentage' => $max_percentage,
				);
			}
		}
		
		// Sort by min_percentage descending
		usort( $saved_grades, function( $a, $b ) {
			return $b['min_percentage'] <=> $a['min_percentage'];
		} );
		
		update_option( 'dum_grade_settings', $saved_grades );
		$grade_settings = $saved_grades;
		
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Grade settings saved successfully!', 'dream-university-management' ) . '</p></div>';
	}
}

// Ensure we have grade settings
if ( empty( $grade_settings ) ) {
	$grade_settings = $default_grades;
}
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Settings', 'dream-university-management' ); ?></h1>
	
	<h2><?php esc_html_e( 'General Settings', 'dream-university-management' ); ?></h2>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e( 'Plugin Version', 'dream-university-management' ); ?></th>
			<td><?php echo esc_html( DUM_VERSION ); ?></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Database Version', 'dream-university-management' ); ?></th>
			<td><?php echo esc_html( get_option( 'dum_db_version', '1.0' ) ); ?></td>
		</tr>
	</table>
	
	<h2><?php esc_html_e( 'Grade Settings', 'dream-university-management' ); ?></h2>
	<p><?php esc_html_e( 'Customize the grade calculation scale. The ranges should be in descending order (highest to lowest).', 'dream-university-management' ); ?></p>
	
	<form method="post" action="">
		<?php wp_nonce_field( 'dum_save_grade_settings' ); ?>
		
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th style="width: 15%;"><?php esc_html_e( 'Grade', 'dream-university-management' ); ?></th>
					<th style="width: 15%;"><?php esc_html_e( 'Grade Point', 'dream-university-management' ); ?></th>
					<th style="width: 25%;"><?php esc_html_e( 'Min Percentage', 'dream-university-management' ); ?></th>
					<th style="width: 25%;"><?php esc_html_e( 'Max Percentage', 'dream-university-management' ); ?></th>
					<th style="width: 20%;"><?php esc_html_e( 'Range Display', 'dream-university-management' ); ?></th>
				</tr>
			</thead>
			<tbody id="grade-settings-tbody">
				<?php foreach ( $grade_settings as $index => $grade_setting ) : ?>
					<tr>
						<td>
							<input type="text" 
							       name="grades[<?php echo esc_attr( $index ); ?>][grade]" 
							       value="<?php echo esc_attr( $grade_setting['grade'] ); ?>" 
							       class="regular-text" 
							       required 
							       style="width: 100%;">
						</td>
						<td>
							<input type="number" 
							       name="grades[<?php echo esc_attr( $index ); ?>][grade_point]" 
							       value="<?php echo esc_attr( $grade_setting['grade_point'] ); ?>" 
							       step="0.01" 
							       min="0" 
							       max="4" 
							       class="small-text" 
							       required 
							       style="width: 100%;">
						</td>
						<td>
							<input type="number" 
							       name="grades[<?php echo esc_attr( $index ); ?>][min_percentage]" 
							       value="<?php echo esc_attr( $grade_setting['min_percentage'] ); ?>" 
							       step="0.01" 
							       min="0" 
							       max="100" 
							       class="small-text" 
							       required 
							       style="width: 100%;">
						</td>
						<td>
							<input type="number" 
							       name="grades[<?php echo esc_attr( $index ); ?>][max_percentage]" 
							       value="<?php echo esc_attr( $grade_setting['max_percentage'] ); ?>" 
							       step="0.01" 
							       min="0" 
							       max="100" 
							       class="small-text" 
							       required 
							       style="width: 100%;">
						</td>
						<td>
							<strong class="range-display">
								<?php
								if ( $grade_setting['max_percentage'] >= 100 ) {
									echo esc_html( $grade_setting['min_percentage'] . '% and above' );
								} elseif ( $grade_setting['min_percentage'] == 0 ) {
									echo esc_html( 'Below ' . ( $grade_setting['max_percentage'] + 1 ) . '%' );
								} else {
									echo esc_html( $grade_setting['min_percentage'] . '-' . $grade_setting['max_percentage'] . '%' );
								}
								?>
							</strong>
							<?php if ( count( $grade_settings ) > 1 ) : ?>
								<br><button type="button" class="button remove-grade-row" style="margin-top: 5px;"><?php esc_html_e( 'Remove', 'dream-university-management' ); ?></button>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<p class="submit">
			<?php submit_button( __( 'Save Grade Settings', 'dream-university-management' ), 'primary', 'dum_save_grade_settings', false ); ?>
			<button type="button" class="button" id="add-grade-row" style="margin-left: 10px;">
				<?php esc_html_e( 'Add Grade', 'dream-university-management' ); ?>
			</button>
		</p>
	</form>
</div>

<script type="text/javascript">
(function($) {
	$(document).ready(function() {
		var gradeIndex = <?php echo count( $grade_settings ); ?>;
		
		// Add new grade row
		$('#add-grade-row').on('click', function() {
			var newRow = '<tr>' +
				'<td><input type="text" name="grades[' + gradeIndex + '][grade]" class="regular-text" required style="width: 100%;"></td>' +
				'<td><input type="number" name="grades[' + gradeIndex + '][grade_point]" step="0.01" min="0" max="4" class="small-text" required style="width: 100%;"></td>' +
				'<td><input type="number" name="grades[' + gradeIndex + '][min_percentage]" step="0.01" min="0" max="100" class="small-text min-percent-input" required style="width: 100%;"></td>' +
				'<td><input type="number" name="grades[' + gradeIndex + '][max_percentage]" step="0.01" min="0" max="100" class="small-text max-percent-input" required style="width: 100%;"></td>' +
				'<td><strong class="range-display">-</strong><br><button type="button" class="button remove-grade-row" style="margin-top: 5px;"><?php echo esc_js( __( 'Remove', 'dream-university-management' ) ); ?></button></td>' +
				'</tr>';
			
			$('#grade-settings-tbody').append(newRow);
			gradeIndex++;
		});
		
		// Remove grade row
		$(document).on('click', '.remove-grade-row', function() {
			if ($('#grade-settings-tbody tr').length > 1) {
				$(this).closest('tr').remove();
			} else {
				alert('<?php echo esc_js( __( 'You must have at least one grade setting.', 'dream-university-management' ) ); ?>');
			}
		});
		
		// Update range display on input change
		$(document).on('input', 'input[name*="[min_percentage]"], input[name*="[max_percentage]"]', function() {
			var row = $(this).closest('tr');
			var minPercent = parseFloat(row.find('input[name*="[min_percentage]"]').val()) || 0;
			var maxPercent = parseFloat(row.find('input[name*="[max_percentage]"]').val()) || 0;
			var rangeDisplay = row.find('.range-display');
			var removeButton = row.find('.remove-grade-row');
			
			var rangeText = '';
			if (maxPercent >= 100) {
				rangeText = minPercent + '% and above';
			} else if (minPercent == 0) {
				rangeText = 'Below ' + (maxPercent + 1) + '%';
			} else {
				rangeText = minPercent + '-' + maxPercent + '%';
			}
			
			if (removeButton.length) {
				rangeDisplay.html(rangeText);
			} else {
				if ($('#grade-settings-tbody tr').length > 1) {
					rangeDisplay.parent().html('<strong class="range-display">' + rangeText + '</strong><br><button type="button" class="button remove-grade-row" style="margin-top: 5px;"><?php echo esc_js( __( 'Remove', 'dream-university-management' ) ); ?></button>');
				} else {
					rangeDisplay.html(rangeText);
				}
			}
		});
	});
})(jQuery);
</script>

