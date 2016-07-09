<?php
if (!defined('ABSPATH')) exit;
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
	<img src=" <?php echo plugin_dir_url( __FILE__ ) ;?> ../../../img/socketizer_full.png"
	     style="width: 200px;height: auto;border-radius:4px;"
	     title="Realtime on the fly!" alt="socketizer logo">

	<h1><?php echo esc_html( get_admin_page_title() ); ?> </h1>

	<form method="post" name="socketizer_options" action="options.php">

		<?php
		$defaults = array(
			'api_key' => '',
			'enabled_posts' => 1,
			'enabled_comments' => 1,
			'enabled_product_stock' => 0,
			'enabled_bb_reply' => 0,
			'enabled_bb_topic' => 0,
		);
		// get settings
		$options = get_option( $this->plugin_name, $defaults );
		$api_key = $options['api_key'];
		$enabled_posts = $options['enabled_posts'];
		$enabled_comments = $options['enabled_comments'];
		$enabled_product_stock = $options['enabled_product_stock'];
		$enabled_bb_reply = $options['enabled_bb_reply'];
		$enabled_bb_topic = $options['enabled_bb_topic'];
		?>

		<?php
		settings_fields( $this->plugin_name );
		do_settings_sections( $this->plugin_name );
		?>
		<p>Get your key from the <a href="https://socketizer.com" target="_blank">Socketizer service</a> and paste it bellow in the <strong>API Key</strong> field</p>
		<p>You can regenerate your key visiting your Socketizer account</p>
		<label for="<?php echo $this->plugin_name; ?>-api-key">API Key</label>
		<input type="text" class="large-text"  style="font-family: monospace;" id="<?php echo $this->plugin_name; ?>-api-key"
		       name="<?php echo $this->plugin_name; ?>[api_key]" value="<?php echo $api_key; ?>"/><br>
		<span class="description"><?php esc_attr_e( 'Your Socketizer API key. Keep it secret!', 'wp_admin_style' ); ?></span><br>
		<hr>
		<h3>WordPress Posts & Comments</h3>
		<fieldset>
			<label for="<?php echo $this->plugin_name; ?>-enabled-posts">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-enabled-posts" name="<?php echo $this->plugin_name; ?>[enabled_posts]" value="1" <?php checked($enabled_posts, 1); ?>/>
				<span><?php esc_attr_e('Enable Socketizer service when new posts are posted', 'wp_admin_style'); ?></span>
			</label>
		</fieldset>
		<fieldset>
			<label for="<?php echo $this->plugin_name; ?>-enabled-comments">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-enabled-comments" name="<?php echo $this->plugin_name; ?>[enabled_comments]" value="1" <?php checked($enabled_comments, 1); ?>/>
				<span><?php esc_attr_e('Enable Socketizer service when new comments are posted', 'wp_admin_style'); ?></span>
			</label>
		</fieldset>
		<hr>
		<h3>WooCommerce</h3>
		<fieldset>
			<label for="<?php echo $this->plugin_name; ?>-enabled-product-stock">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-enabled-product-stock" name="<?php echo $this->plugin_name; ?>[enabled_product_stock]" value="1" <?php checked($enabled_product_stock, 1) ; ?>/>
				<span><?php esc_attr_e('Enable Socketizer service when a product\'s stock changed', 'wp_admin_style'); ?></span>
			</label>
		</fieldset>
		<hr>
		<h3>bbPress</h3>
		<fieldset>
			<label for="<?php echo $this->plugin_name; ?>-enabled-bb-topic">
				<label for="<?php echo $this->plugin_name; ?>-enabled-bb-topic">
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-enabled-bb-topic" name="<?php echo $this->plugin_name; ?>[enabled_bb_topic]" value="1" <?php checked($enabled_bb_topic, 1); ?>/>
					<span><?php esc_attr_e('Enable Socketizer service when a new topic has been posted', 'wp_admin_style'); ?></span>
				</label>
			</label>
		</fieldset>
		<fieldset>
			<label for="<?php echo $this->plugin_name; ?>-enabled-bb-reply">
				<label for="<?php echo $this->plugin_name; ?>-enabled-bb-reply">
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-enabled-bb-reply" name="<?php echo $this->plugin_name; ?>[enabled_bb_reply]" value="1" <?php checked($enabled_bb_reply, 1); ?>/>
					<span><?php esc_attr_e('Enable Socketizer service when a new reply to topic has been posted', 'wp_admin_style'); ?></span>
				</label>
			</label>
		</fieldset>
		<?php submit_button( 'Save', 'primary', 'submit', true ); ?>
	</form>
</div>
