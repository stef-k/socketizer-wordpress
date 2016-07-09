<?php
if (!defined('ABSPATH')) exit;
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://socketizer.com
 * @since      1.0.0
 *
 * @package    Com_Socketizer
 * @subpackage Com_Socketizer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Com_Socketizer
 * @subpackage Com_Socketizer/public
 * @author     Stef Kariotidis <stef.kariotidis@gmail.com>
 */
class Com_Socketizer_Public {

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
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/com-socketizer-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/com-socketizer-public.js', array( 'jquery' ), $this->version, false );
		// socketizer library for wordpress
		wp_enqueue_script( 'socketizer-lib', 'https://service.socketizer.com/service/static/wordpress/socketizer.min.js', array( 'jquery' ), '1.0.0', true );

		$socketizer = array(
			'postsPage' => get_permalink( get_option( 'page_for_posts' ) ),
			'host' => preg_replace("(^https?://)", "", get_home_url() ),
		);
		// this must be called after our lib
		wp_localize_script( 'socketizer-lib', 'socketizer', $socketizer );
	}
	
}
