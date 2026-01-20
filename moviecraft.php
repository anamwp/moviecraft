<?php

/**
 * Plugin Name: Movie Craft
 * Plugin URI: https://anam.rocks
 * Description: A starter plugin to start your big idea.
 * Version: 1.0
 * Author: Anam
 * Author URI: https://anam.rocks
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: 'moviecraft'
 */
// If this file is called directly, abort.
if (! defined('ABSPATH')) {
	exit;
}
/**
 * Autoload vendor folder
 */
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

/**
 * Include API configuration for Movie Lists Block
 */
require_once __DIR__ . '/src/blocks/movie-lists/api-config.php';


final class Movie_Craft_Starter
{

	/**
	 * plugin version
	 */
	const MOVIE_CRAFT_STARTER_VERSION = '1.0';
	/**
	 * construction of this plugin
	 */
	private function __construct()
	{
		$this->define_constants();
		register_activation_hook(__FILE__, array($this, 'activate'));
		add_action('plugins_loaded', array($this, 'moviecraft_load_plugin_resources'));
	}
	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public static function init()
	{
		static $instance = false;
		if (! $instance) {
			$instance = new self();
		}
		return $instance;
	}
	/**
	 * Load plugin text domain
	 *
	 * @return void
	 */
	public function load_text_domain()
	{
		load_plugin_textdomain('moviecraft');
	}
	/**
	 * Define plugin
	 * default constants
	 *
	 * @return void
	 */
	public function define_constants()
	{
		/**
		 * return plugin version
		 */
		define('MOVIE_CRAFT_STARTER_VERSION', self::MOVIE_CRAFT_STARTER_VERSION);
		/**
		 * return the main file name
		 * C:\xampp\htdocs\devplugin\wp-content\plugins\gutenberg-starter\gutenberg-starter.php
		 */
		define('MOVIE_CRAFT_STARTER_FILE', __FILE__);
		define('MOVIE_CRAFT_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
		/**
		 * return the plugin director
		 * C:\xampp\htdocs\devplugin\wp-content\plugins\gutenberg-starter
		 */
		define('MOVIE_CRAFT_STARTER_PATH', __DIR__);
		/**
		 * return the plugin directory with host
		 * http://localhost/devplugin/wp-content/plugins/gutenberg-starter
		 */
		define('MOVIE_CRAFT_STARTER_URL', plugins_url('', MOVIE_CRAFT_STARTER_FILE));
		define('MOVIE_CRAFT_STARTER_DIR_URL', plugin_dir_url(__FILE__));
		/**
		 * return the asset folder director
		 * http://localhost/devplugin/wp-content/plugins/gutenberg-starter/assets
		 */
		define('MOVIE_CRAFT_STARTER_ASSETS', MOVIE_CRAFT_STARTER_URL . '/build');
		define('MOVIE_CRAFT_STARTER_DIR_ASSETS', MOVIE_CRAFT_STARTER_DIR_URL . 'build');
	}
	/**
	 * Add installation time
	 * and plugin version
	 * while active the plugin
	 *
	 * @return void
	 */
	public function activate()
	{
		if (! get_option('movie_craft_starter_installed')) {
			update_option('movie_craft_starter_installed', time());
		}
		update_option('movie_craft_starter_version', MOVIE_CRAFT_STARTER_VERSION);
	}
	/**
	 * Load plugin resources
	 *
	 * @return void
	 */
	public function moviecraft_load_plugin_resources()
	{
		new MovieCraft\Init();
	}
}

/**
 * Load the .env file if it exists
 * and set the environment variables
 */
if (file_exists(__DIR__ . '/.env') && class_exists('Dotenv\Dotenv')) {
	$gs_dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
	$gs_dotenv->load();
}


/**
 * Manage fonts in the editor
 * 
 * ref: https://developer.wordpress.org/block-editor/how-to-guides/enqueueing-assets-in-the-editor/
 *
 * @return void
 */
function moviecraft_handle_google_fonts()
{
	wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap', array(), null);
}
add_action('enqueue_block_editor_assets', 'moviecraft_handle_google_fonts');
add_action('wp_enqueue_scripts', 'moviecraft_handle_google_fonts');

/**
 * Enqueue script for ajax pagination
 *
 * @return void
 */
function moviecraft_enqueue_ajax_pagination_script()
{
	wp_enqueue_script('jquery');
	wp_localize_script(
		'jquery',
		'anamajaxpagination',
		array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'moviecraftajax_nonce'   => wp_create_nonce('moviecraft_ajax_nonce')
		)
	);
	wp_localize_script(
		'jquery',
		'envVars',
		array(
			'GS_SITE_URL'        => getenv('GS_SITE_URL'),
			'WC_CONSUMER_KEY'    => getenv('WC_CONSUMER_KEY'),
			'WC_CONSUMER_SECRET' => getenv('WC_CONSUMER_SECRET'),
			'MOVIE_BEARER_TOKEN' => getenv('MOVIE_BEARER_TOKEN'),
		)
	);
}
add_action('wp_enqueue_scripts', 'moviecraft_enqueue_ajax_pagination_script');
add_action('enqueue_block_editor_assets', 'moviecraft_enqueue_ajax_pagination_script');

/**
 * Enquque build/css/index.css file
 */
function moviecraft_enqueue_block_assets()
{
	wp_enqueue_style(
		'gs-plugin-style',
		plugins_url('dist/css/main.css', __FILE__),
		array(),
		'1.0'
	);
}
add_action('enqueue_block_assets', 'moviecraft_enqueue_block_assets');

/**
 * Initilize the main plugin
 *
 * @return \Guest_Post_Submission
 */
function movie_craft_starter()
{
	return Movie_Craft_Starter::init();
}
/**
 * kick start the plugin
 */
movie_craft_starter();

/**
 * Create custom category of CGL block in gutenberg editor
 *
 * @param [type] $categories Custom category name.
 * @return Array
 */
function moviecraft_register_layout_category_handler($categories)
{
	$categories[] = array(
		'slug'  => 'movie-craft',
		'title' => 'Movie Craft',
	);
	return $categories;
}

if (version_compare(get_bloginfo('version'), '5.8', '>=')) {
	add_filter('block_categories_all', 'moviecraft_register_layout_category_handler');
} else {
	add_filter('block_categories', 'moviecraft_register_layout_category_handler');
}
