<?php
/**
 * A drop-in for themes to help prevent them from completely breaking
 * or loading additional menus, assets, filters, etc. when the
 * AudioTheme framework plugin is inactive.
 *
 * @package AudioThemeFramework\Loader
 * @since 1.0.0
 */

if ( ! class_exists( 'Audiotheme_Loader' ) ) :
/**
 * Class to load the AudioTheme Framework or display a notice.
 *
 * @package AudioThemeFramework\Loader
 * @since 1.0.0
 */
class Audiotheme_Loader {
	/**
	 * Check if the AudioTheme Framework is active.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_audiotheme_active() {
		return function_exists( 'audiotheme_load' );
	}

	/**
	 * Load the AudioTheme Framework or display a notice if it's not active.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		$audiotheme_functions = get_stylesheet_directory() . '/audiotheme/functions.php';

		if ( $this->is_audiotheme_active() && file_exists( $audiotheme_functions ) ) {
			include( $audiotheme_functions );
		} elseif ( is_admin() && current_user_can( 'activate_plugins' ) ) {
			add_action( 'admin_notices', array( $this, 'display_notice' ) );
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'wp_ajax_' . $this->dismiss_notice_action(), array( $this, 'ajax_dismiss_notice' ) );
		}
	}

	/**
	 * Dismiss the Framework notice.
	 *
	 * This is a fallback in case the AJAX method doesn't work.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$slug = $this->theme();

		if ( isset( $_GET[ $slug ] ) && 'dismiss-notice' == $_GET[ $slug ] && wp_verify_nonce( $_GET['_wpnonce'], $this->dismiss_notice_action() ) ) {
			$this->dismiss_notice();

			$redirect = remove_query_arg( array( $this->theme(), '_wpnonce' ) );
			wp_safe_redirect( esc_url_raw( $redirect ) );
		}
	}

	/**
	 * Display a notice if the AudioTheme framework isn't active.
	 *
	 * @since 1.0.0
	 */
	public function display_notice() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$user_id = get_current_user_id();

		// Return early if user already dismissed the notice.
		if ( 'dismissed' == get_user_meta( $user_id, $this->notice_key(), true ) ) {
			return;
		}
		?>
		<div id="audiotheme-framework-required-notice" class="error">
			<p>
				<?php
				_e( 'Progeny MMXIV is designed to integrate with the AudioTheme Framework plugin.', 'progeny-mmxiv' );

				if ( 0 === validate_plugin( 'audiotheme/audiotheme.php' ) ) {
					$activate_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=audiotheme/audiotheme.php', 'activate-plugin_audiotheme/audiotheme.php' );
					printf( ' <a href="%s"><strong>%s</strong></a>',
						esc_url( $activate_url ),
						__( 'Activate now', 'progeny-mmxiv' )
					);
				} else {
					printf( ' <a href="http://audiotheme.com/try/" target="_blank"><strong>%s</strong></a>',
						__( 'Download Free Trial', 'progeny-mmxiv' )
					);
				}

				$dismiss_url = wp_nonce_url( add_query_arg( get_template(), 'dismiss-notice' ), $this->dismiss_notice_action() );
				printf( ' <a href="%s" class="dismiss" style="float: right">%s</a>',
					esc_url( $dismiss_url ),
					__( 'Dismiss', 'progeny-mmxiv' )
				);
				?>
			</p>
		</div>
		<script type="text/javascript">
		jQuery( '#audiotheme-framework-required-notice' ).on( 'click', '.dismiss', function( e ) {
			var $notice = jQuery( this ).closest( '.error' );

			e.preventDefault();

			jQuery.get( ajaxurl, {
				action: '<?php echo $this->dismiss_notice_action(); ?>',
				_wpnonce: '<?php echo wp_create_nonce( $this->dismiss_notice_action() ); ?>'
			}, function() {
				$notice.slideUp();
			});
		});
		</script>
		<?php
	}

	/**
	 * AJAX callback to dismiss the Framework required notice.
	 *
	 * @since 1.0.0
	 */
	public function ajax_dismiss_notice() {
		check_admin_referer( $this->dismiss_notice_action() );
		$this->dismiss_notice();
		wp_die( 1 );
	}

	/**
	 * Add the notice status to the current user's meta.
	 *
	 * @since 1.0.0
	 */
	protected function dismiss_notice() {
		$user_id = get_current_user_id();
		update_user_meta( $user_id, $this->notice_key(), 'dismissed', true );
	}

	/**
	 * User meta key for the notice status.
	 *
	 * @access protected
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function notice_key() {
		return $this->theme() . '_audiotheme_framework_required_notice';
	}

	/**
	 * Name of the dismiss action.
	 *
	 * @access protected
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function dismiss_notice_action() {
		return $this->theme() . '-dismiss-audiotheme-framework-required-notice';
	}

	/**
	 * Get the name of the current parent theme.
	 *
	 * @access protected
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function theme() {
		return get_template();
	}
}
endif;
