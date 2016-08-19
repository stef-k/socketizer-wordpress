<?php
if (!defined('ABSPATH')) exit;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://socketizer.com
 * @since      1.0.0
 *
 * @package    Com_Socketizer
 * @subpackage Com_Socketizer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Com_Socketizer
 * @subpackage Com_Socketizer/admin
 * @author     Stef Kariotidis <stef.kariotidis@gmail.com>
 */
class Com_Socketizer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name            = $plugin_name;
		$this->version                = $version;
		$this->socketizer_service_url = 'https://service.socketizer.com/service/api/v1/wordpress/';
		$this->host                   = preg_replace( "(^https?://)", "", get_home_url() );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Com_Socketizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Com_Socketizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/com-socketizer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Com_Socketizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Com_Socketizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/com-socketizer-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the admin menu for the plugin
	 */
	public function add_plugin_admin_menu() {
		add_options_page( 'Settings', 'Socketizer', 'manage_options', $this->plugin_name, array(
			$this,
			'display_settings_setup_page'
		) );
	}

	/**
	 * Add settings action link at plugins page
	 *
	 * @param $links
	 *
	 * @since 1.0.0
	 */
	public function add_action_links( $links ) {

		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>',
		);

		return array_merge( $settings_link, $links );
	}


	/**
	 * Render settings page
	 * @since 1.0.0
	 */
	public function display_settings_setup_page() {
		include_once( 'partials/com-socketizer-admin-display.php' );
	}

	/**
	 * Validate plugin's settings
	 *
	 * @param $input
	 *
	 * @return bool
	 */
	public function validate( $input ) {
		$settings = array();

		if ( ! empty( $input['api_key'] ) ) {
			$settings['api_key'] = sanitize_text_field( $input['api_key'] );
		} else {
			$settings['api_key'] = 'Your API key';
		}

		$settings['enabled_posts']         = ( isset( $input['enabled_posts'] ) && ! empty( $input['enabled_posts'] ) ) ? 1 : 0;
		$settings['enabled_comments']      = ( isset( $input['enabled_comments'] ) && ! empty( $input['enabled_comments'] ) ) ? 1 : 0;
		$settings['enabled_product_stock'] = ( isset( $input['enabled_product_stock'] ) && ! empty( $input['enabled_product_stock'] ) ) ? 1 : 0;
		$settings['enabled_bb_reply']      = ( isset( $input['enabled_bb_reply'] ) && ! empty( $input['enabled_bb_reply'] ) ) ? 1 : 0;
		$settings['enabled_bb_topic']      = ( isset( $input['enabled_bb_topic'] ) && ! empty( $input['enabled_bb_topic'] ) ) ? 1 : 0;

		return $settings;
	}

	/**
	 * Update plugin's options
	 */
	public function options_update() {
		register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate' ) );
	}

	/**
	 * Get the URL of posts page, skip reading settings confusion
	 * @return false|string|void
	 */
	private function get_post_page_url() {

		if ( 'page' == get_option( 'show_on_front' ) ) {
			return get_permalink( get_option( 'page_for_posts' ) );
		} else {
			return home_url();
		}
	}


	/**
	 * Call Socketizer service when a post has been published
	 *
	 * @param $post_id
	 */
	public function post_published( $post_id ) {
		$options = get_option( $this->plugin_name );
		$enabled = $options['enabled_posts'];
		if ( $enabled == 1 ) {
			$api_key = $options['api_key'];
			$postUrl = esc_url( get_permalink( $post_id ) );
			$args    = array(
				'host'         => $this->host,
				'apiKey'       => $api_key,
				'postUrl'      => $postUrl,
				'postId'       => (string) $post_id,
				'pageForPosts' => $this->get_post_page_url(),
				'what'         => 'post',
				'commentUrl'   => '',
				'commentId'    => '',
			);
			$url     = $this->socketizer_service_url . 'cmd/client/refresh/post/';
			wp_remote_post( $url, array(
				'httpversion' => '1.1',
				'blocking' => false,
				'body' => json_encode( $args )
			) );
		}
	}

	/**
	 * Call Socketizer service when a comment has been posted
	 *
	 * @param $comment_id int comment's primary key
	 * @param  $approved int comment passes moderation
	 */
	public function comment_published( $comment_id, $approved ) {
		$options = get_option( $this->plugin_name );
		$enabled = $options['enabled_comments'];
		if ( $enabled == 1 ) {
			if ( $approved == 1 ) {
				$options = get_option( $this->plugin_name );
				$api_key = $options['api_key'];
				$comment = get_comment( $comment_id );
				$postUrl = esc_url( get_permalink( $comment->comment_post_ID ) );
				$url     = $this->socketizer_service_url . 'cmd/client/refresh/post/';
				$args    = array(
					'host'         => $this->host,
					'apiKey'       => $api_key,
					'postUrl'      => $postUrl,
					'postId'       => (string) $comment->comment_post_ID,
					'pageForPosts' => $this->get_post_page_url(),
					'what'         => 'comment',
					'commentUrl'   => get_comment_link( $comment_id ),
					'commentId'    => (string) $comment_id,
				);
				wp_remote_post( $url, array(
					'httpversion' => '1.1',
					'blocking' => false,
					'body' => json_encode( $args )
				) );
			}
		}
	}

	/**
	 * Call Socketizer service when a product's stock has changed
	 *
	 * @param $instance  WC_Product the product it's stock changed
	 */
	public function woo_product_stock_changed( $instance ) {

		$options = get_option( $this->plugin_name );
		$enabled = $options['enabled_product_stock'];
		if ( $enabled == 1 ) {
			$api_key = $options['api_key'];
			$postUrl = esc_url( get_permalink( $instance->id ) );
			$args    = array(
				'host'         => $this->host,
				'apiKey'       => $api_key,
				'postUrl'      => $postUrl,
				'postId'       => (string) $instance->id,
				'pageForPosts' => $this->get_post_page_url(),
				'what'         => 'product',
				'commentUrl'   => '',
				'commentId'    => '',
			);
			$url     = $this->socketizer_service_url . 'cmd/client/refresh/post/';
			wp_remote_post( $url, array(
				'httpversion' => '1.1',
				'blocking' => false,
				'body' => json_encode( $args )
			) );
		}
	}

	/**
	 * Call Socketizer service when a new reply has been posted
	 *
	 * @param $reply_id
	 * @param $topic_id
	 * @param $forum_id
	 * @param $anonymous_data
	 * @param $reply_author
	 * @param $false
	 * @param $reply_to
	 */
	public function bbpress_new_reply( $reply_id, $topic_id, $forum_id, $anonymous_data, $reply_author, $false, $reply_to ) {
		$options = get_option( $this->plugin_name );
		$enabled = $options['enabled_bb_reply'];
		if ( $enabled == 1 ) {
			$api_key = $options['api_key'];
			$postUrl = esc_url( get_permalink( $topic_id ) );
			$args    = array(
				'host'         => $this->host,
				'apiKey'       => $api_key,
				'postUrl'      => $postUrl,
				'postId'       => (string) $topic_id,
				'pageForPosts' => $this->get_post_page_url(),
				'what'         => 'bb_reply',
				'commentUrl'   => '',
				'commentId'    => '',
			);
			$url     = $this->socketizer_service_url . 'cmd/client/refresh/post/';
			wp_remote_post( $url, array(
				'httpversion' => '1.1',
				'blocking' => false,
				'body' => json_encode( $args )
			) );
		}
	}

	/**
	 * Call Socketizer service when a new topic has been posted
	 *
	 * @param $topic_id
	 * @param $forum_id
	 * @param $anonymous_data
	 * @param $topic_author
	 */
	public function bbpress_new_topic( $topic_id, $forum_id, $anonymous_data, $topic_author ) {
		$options = get_option( $this->plugin_name );
		$enabled = $options['enabled_bb_topic'];
		if ( $enabled == 1 ) {
			$api_key = $options['api_key'];
			$postUrl = esc_url( get_permalink( $forum_id ) );
			$args    = array(
				'host'         => $this->host,
				'apiKey'       => $api_key,
				'postUrl'      => $postUrl,
				'postId'       => (string) $forum_id,
				'pageForPosts' => $this->get_post_page_url(),
				'what'         => 'bb_topic',
				'commentUrl'   => '',
				'commentId'    => '',
			);
			$url     = $this->socketizer_service_url . 'cmd/client/refresh/post/';
			wp_remote_post( $url, array(
				'httpversion' => '1.1',
				'blocking' => false,
				'body' => json_encode( $args )
			) );
		}
	}
}
