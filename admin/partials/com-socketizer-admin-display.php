<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://socketizer.com
 * @since      1.0.0
 *
 * @package    Com_Socketizer
 * @subpackage Com_Socketizer/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?> </h2>

	<form method="post" name="socketizer_options" action="options.php">

		<?php
		// get settings
		$options    = get_option( $this->plugin_name );
		$secret_key = $options['secret_key'];
		?>

		<?php
		settings_fields( $this->plugin_name );
		do_settings_sections( $this->plugin_name );
		?>

		<label for="<?php echo $this->plugin_name; ?>-secret-key">Secret Key</label>
		<input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-secret-key"
		       name="<?php echo $this->plugin_name; ?>[secret_key]" value="<?php echo $secret_key; ?>"/><br>
		<span class="description"><?php esc_attr_e( 'Your Socketizer secret key', 'wp_admin_style' ); ?></span><br>

		<?php submit_button( 'Save', 'primary', 'submit', true ); ?>
	</form>
</div>
