<?php
/**
 * A drop-in for themes to help prevent them from completely breaking
 * or loading additional menus, assets, filter, etc. when the
 * AudioTheme framework plugin is deactivated.
 *
 * @package twentyfourteen-audiotheme
 */

/**
 * Functionality to load if the framework isn't active.
 */
if ( ! twentyfourteen_is_audiotheme_active() ) {
	add_action( 'admin_notices', 'twentyfourteen_audiotheme_framework_required_notice' );
	add_action( 'admin_init', 'twentyfourteen_audiotheme_framework_required_notice_dismiss' );
}

/**
 * Check if the AudioTheme framework is active.
 *
 * AudioTheme won't be active yet while it's being activated, which can cause
 * errors while trying to redeclare functions, so check the activation nonce
 * if it exists.
 *
 * @return bool
 */
function twentyfourteen_is_audiotheme_active() {
	$is_active = in_array( 'audiotheme/audiotheme.php', (array) get_option( 'active_plugins', array() ) );

	$is_activation_routine = isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'activate-plugin_audiotheme/audiotheme.php' );

	return ( $is_active || $is_activation_routine );
}

/**
 * Display a notice if the AudioTheme framework isn't activated.
 */
function twentyfourteen_audiotheme_framework_required_notice() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_file = 'audiotheme/audiotheme.php';

	// Return early if user has already dismissed the notice.
	if ( get_user_meta( wp_get_current_user()->ID, 'twentyfourteen_audiotheme_framework_required_notice' ) ) {
		return;
	}
	?>
	<div class="updated">
		<p>
			<?php
			_e( 'The AudioTheme Framework plugin is required to be installed and activated for this theme to display properly.', 'twentyfourteen' );

			if ( 0 === validate_plugin( 'audiotheme/audiotheme.php' ) ) {
				$activate_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin_file, 'activate-plugin_' . $plugin_file );

				printf( ' <a href="%s"><strong>%s &rarr;</strong></a>',
					esc_url( $activate_url ),
					__( 'Activate Plugin', 'twentyfourteen' )
				);
			} else {
				printf( ' <a href="http://audiotheme.com/try/" target="_blank"><strong>%s &rarr;</strong></a>',
					__( 'Download Free Trial', 'twentyfourteen' )
				);
			}

			printf( ' <a href="%s" style="float: right;">%s</a>',
				esc_url( add_query_arg( 'twentyfourteen_audiotheme_framework_required_notice_dismiss', 0, admin_url() ) ),
				__( 'Dismiss', 'twentyfourteen' )
			);
			?>
		</p>
	</div>
	<?php
}

/**
 * Dismiss AudioTheme framework notice
 */
function twentyfourteen_audiotheme_framework_required_notice_dismiss() {
	// If user clicks to ignore the notice, add that to their user meta.
	if ( isset( $_GET['twentyfourteen_audiotheme_framework_required_notice_dismiss'] ) && '0' == $_GET['twentyfourteen_audiotheme_framework_required_notice_dismiss'] ) {
		add_user_meta( wp_get_current_user()->ID, 'twentyfourteen_audiotheme_framework_required_notice', '1', true );
	}
}