<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.tukutoi.com/
 * @since      1.0.0
 *
 * @package    Tkt_Send_Email_If
 * @subpackage Tkt_Send_Email_If/admin/partials
 */

?>

<?php
/**
 * $meta_slug Custom Meta Field Slug
 * $email_address Custom Meta Field Value
 */
$meta_slug      = 'tkt_sei_email_address';
$email_address  = get_post_meta( get_the_ID(), $meta_slug, true );
?>

<div class="tkt_sei_box">
	<p class="meta-options tkt_sei_field">
		<label for="tkt_sei_email_address">Email</label>
		<input id="tkt_sei_email_address" type="email" name="tkt_sei_email_address"
		value="<?php echo esc_attr( $email_address ); ?>">
	</p>
</div>
