<?php
if (!defined('ABSPATH')) exit;
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://socketizer.com
 * @since      1.0.0
 *
 * @package    Com_Socketizer
 * @subpackage Com_Socketizer/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Com_Socketizer
 * @subpackage Com_Socketizer/includes
 * @author     Stef Kariotidis <stef.kariotidis@gmail.com>
 */
class Com_Socketizer {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Com_Socketizer_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'com-socketizer';
		$this->version     = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Com_Socketizer_Loader. Orchestrates the hooks of the plugin.
	 * - Com_Socketizer_i18n. Defines internationalization functionality.
	 * - Com_Socketizer_Admin. Defines all hooks for the admin area.
	 * - Com_Socketizer_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-com-socketizer-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-com-socketizer-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-com-socketizer-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-com-socketizer-public.php';

		$this->loader = new Com_Socketizer_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Com_Socketizer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Com_Socketizer_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Com_Socketizer_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// add settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// Save plugin's settings
		$this->loader->add_action( 'admin_init', $plugin_admin, 'options_update' );

		// set up our content based hooks
		// post published
		$this->loader->add_action( 'publish_post', $plugin_admin, 'post_published', 10, 2 );
		// comment posted
		$this->loader->add_action( 'comment_post', $plugin_admin, 'comment_published', 10, 2 );
		$this->loader->add_action( 'edit_comment', $plugin_admin, 'comment_published', 10, 2 );
		// catch any moderation uppon comments
		$this->loader->add_action( 'comment_unapproved_to_approved', $plugin_admin, 'comment_published', 10, 2 );
		$this->loader->add_action( 'comment_approved_to_unapproved', $plugin_admin, 'comment_published', 10, 2 );
		$this->loader->add_action( 'comment_spam_to_approved', $plugin_admin, 'comment_published', 10, 2 );
		$this->loader->add_action( 'comment_approved_to_spam', $plugin_admin, 'comment_published', 10, 2 );
		$this->loader->add_action( 'comment_approved_to_trash', $plugin_admin, 'comment_published', 10, 2 );
		$this->loader->add_action( 'comment_trash_to_approved', $plugin_admin, 'comment_published', 10, 2 );
		// WooCommerce
		$this->loader->add_action('woocommerce_product_set_stock', $plugin_admin, 'woo_product_stock_changed', 10, 1);
		// bbPress
		$this->loader->add_action('bbp_new_reply', $plugin_admin, 'bbpress_new_reply', 10, 7);
		$this->loader->add_action('bbp_new_topic', $plugin_admin, 'bbpress_new_topic', 10, 4);
		$this->loader->add_action('bbp_new_forum', $plugin_admin, 'bbpress_new_forum', 10, 1);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Com_Socketizer_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Com_Socketizer_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
