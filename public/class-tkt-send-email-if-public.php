<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.tukutoi.com/
 * @since      1.0.0
 *
 * @package    Tkt_Send_Email_If
 * @subpackage Tkt_Send_Email_If/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, plugin shortname, and builds, triggers and sends the email
 *
 * @package    Tkt_Send_Email_If
 * @subpackage Tkt_Send_Email_If/public
 * @author     TukuToi <hello@tukutoi.com>
 */
class Tkt_Send_Email_If_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The shortname of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_short    The shortname of this plugin used to prefix technical functions.
	 */
	private $plugin_short;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $plugin_short       The shortname of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $plugin_short, $version ) {

		$this->plugin_name  = $plugin_name;
		$this->plugin_short = $plugin_short;
		$this->version      = $version;

	}

	/**
	 * Build the email.
	 *
	 * @param object $post The post triggering the email.
	 * @param object $current_user The current User triggering the email.
	 * @param email  $receiver_email The email address to which to send the notification.
	 * @since    1.0.0
	 */
	private function build_email( $post, $current_user, $receiver_email ) {

		// Translators: %s represents a dynamic Post Title, and thus should not be translated.
		$default_message = sprintf( __( 'Hello, the following post was visited: %s', 'tkt-send-email-if' ), sanitize_title( $post->post_title ) );

		$content = apply_filters( $this->plugin_short . '_notification_content', $default_message, $post, $current_user, $receiver_email );

		return $content;

	}

	/**
	 * Send the email.
	 *
	 * @param email $receiver_email the email address to be notified.
	 * @param mixed $content the Email content.
	 * @since    1.0.0
	 */
	private function send_email( $receiver_email, $content ) {

		$subject        = apply_filters( $this->plugin_short . '_email_subject', __( 'Visit detected!', 'tkt-send-email-if' ) );
		$receiver_email = sanitize_email( apply_filters( $this->plugin_short . '_receiver_email', $receiver_email ) );

		wp_mail( $receiver_email, esc_html( $subject ), wp_kses( $content, 'post' ) );

	}

	/**
	 * Trigger notificiation.
	 *
	 * @since    1.0.0
	 */
	public function trigger_notification() {

		$post_type  = apply_filters( $this->plugin_short . '_metabox_location', array( 'post' ) );
		$role       = apply_filters( $this->plugin_short . '_notify_on_role_visit', array() );

		if (
			is_singular()
			&& in_array( get_post_type(), (array) $post_type )
			&& get_option( 'permalink_structure' )
		) {

			global $post;
			$current_user   = wp_get_current_user();
			$receiver_email = sanitize_email( get_post_meta( $post->ID, 'tkt_sei_email_address', true ) );

			if (
				! empty( $role )
				&& ( array_intersect( $role, (array) $current_user->roles ) || in_array( 0, $role, true ) )
				&& ! empty( $receiver_email )
			) {

				$content = $this->build_email( $post, $current_user, $receiver_email );

				$this->send_email( $receiver_email, $content );

			}
		}

	}

}
