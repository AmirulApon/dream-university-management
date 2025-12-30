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

if ( isset( $_POST['dum_save_settings'] ) && check_admin_referer( 'dum_settings' ) ) {
	// Save settings here
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Settings saved successfully!', 'dream-university-management' ) . '</p></div>';
}
?>

<div class="wrap">
	<h1><?php esc_html_e( 'Settings', 'dream-university-management' ); ?></h1>
	
	<form method="post" action="">
		<?php wp_nonce_field( 'dum_settings' ); ?>
		
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
		<p><?php esc_html_e( 'Grade calculation is based on the following scale:', 'dream-university-management' ); ?></p>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Grade', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Grade Point', 'dream-university-management' ); ?></th>
					<th><?php esc_html_e( 'Percentage Range', 'dream-university-management' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr><td><strong>A+</strong></td><td><strong>4.0</strong></td><td><strong>80% and above</strong></td></tr>
				<tr><td>A</td><td>3.75</td><td>75-79%</td></tr>
				<tr><td>A-</td><td>3.5</td><td>70-74%</td></tr>
				<tr><td>B+</td><td>3.25</td><td>65-69%</td></tr>
				<tr><td>B</td><td>3.0</td><td>60-64%</td></tr>
				<tr><td>B-</td><td>2.75</td><td>55-59%</td></tr>
				<tr><td>C+</td><td>2.5</td><td>50-54%</td></tr>
				<tr><td>C</td><td>2.25</td><td>45-49%</td></tr>
				<tr><td>D</td><td>2.0</td><td>40-44%</td></tr>
				<tr><td>F</td><td>0.0</td><td>Below 40%</td></tr>
			</tbody>
		</table>
		
		<?php submit_button( __( 'Save Settings', 'dream-university-management' ), 'primary', 'dum_save_settings' ); ?>
	</form>
</div>

