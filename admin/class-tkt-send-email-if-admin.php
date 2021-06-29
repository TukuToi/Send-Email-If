<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.tukutoi.com/
 * @since      1.0.0
 *
 * @package    Tkt_Send_Email_If
 * @subpackage Tkt_Send_Email_If/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, plugin shortname, and adds the metabox to posts, as well as handles the save process.
 *
 * @package    Tkt_Send_Email_If
 * @subpackage Tkt_Send_Email_If/admin
 * @author     TukuToi <hello@tukutoi.com>
 */
class Tkt_Send_Email_If_Admin {

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
	 * @var      string    $plugin_short    The shortname of this plugin, used to prefix technical functions.
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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $plugin_short      The Shortname of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $plugin_short, $version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_short = $plugin_short;
		$this->version = $version;

	}

	/**
	 * Register the metabox for email address in edit screens.
	 *
	 * @since    1.0.0
	 */
	public function register_meta_box() {

		$title = apply_filters( $this->plugin_short . '_metabox_title', __( 'Email Address', 'tkt-send-email-if' ) );
		$screen = apply_filters( $this->plugin_short . '_metabox_location', array() );

		if ( ! empty( $screen ) ) {
			add_meta_box( 'tkt_sei_email_address', esc_html( $title ), array( $this, 'metabox_display_template' ), array_map( 'esc_html', $screen ), 'side', 'low' );
		}

	}

	/**
	 * Load the Metabox Template File.
	 *
	 * @param   object $post the currnet post being edited.
	 * @since   1.0.0
	 */
	public function metabox_display_template( $post ) {

		wp_nonce_field( $this->plugin_short . '_metabox', $this->plugin_short . '_nonce' );

		$path = apply_filters( $this->plugin_short . '_metabox_template_path', plugin_dir_path( __FILE__ ) . 'partials/tkt-send-email-if-admin-display.php' );

		include $path;

	}

	/**
	 * Save metabox contents to post.
	 *
	 * @param   int $post_id the currnet post ID being edited.
	 * @since   1.0.0
	 */
	public function save_metabox( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST[ $this->plugin_short . '_nonce' ] ) ) {
			return $post_id;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST[ $this->plugin_short . '_nonce' ] ) );

		if ( ! wp_verify_nonce( $nonce, $this->plugin_short . '_metabox' ) ) {
			return $post_id;
		}

		$parent_id = wp_is_post_revision( $post_id );
		if ( $parent_id ) {
			$post_id = $parent_id;
		}

		$fields = array(
			'tkt_sei_email_address',
		);

		foreach ( $fields as $field ) {
			if ( array_key_exists( $field, $_POST ) ) {
				update_post_meta( $post_id, $field, sanitize_email( wp_unslash( $_POST[ $field ] ) ) );
			}
		}

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tkt-send-email-if-admin.css', array(), $this->version, 'all' );

	}

}
