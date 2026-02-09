<?php

namespace MovieCraft\Admin;

/**
 * Plugin Option Panel.
 *
 * @since Version 3 digits
 */
class Options
{
	/**
	 * self instance
	 * of this shortcode
	 *
	 * @var [type]
	 */
	private static $instance;

	public static function init()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Construct of Class
	 */
	public function __construct()
	{
		if (is_admin()) {
			add_action('admin_menu', array($this, 'moviecraft_plugin_settings_page'));
			add_action('admin_init', array($this, 'moviecraft_register_settings'));
		}
	}

	/**
	 * Register plugin settings
	 */
	public function moviecraft_register_settings()
	{
		// Movie Lists Block API Key
		register_setting(
			'moviecraft_settings',
			'moviecraft_movie_api_key',
			array(
				'type'              => 'string',
				'description'       => __('The Movie Database API Key', 'moviecraft'),
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => false, // Don't expose in REST API for security.
				'default'           => '',
			)
		);

		// Add more settings here as needed for other blocks
	}

	/**
	 * Add settings page to admin menu
	 */
	public function moviecraft_plugin_settings_page()
	{
		add_options_page(
			__('Movie Craft Settings', 'moviecraft'),
			__('Movie Craft', 'moviecraft'),
			'manage_options',
			'moviecraft_settings',
			array($this, 'moviecraft_handle_plugin_setting_page_callback'),
			null
		);
	}

	/**
	 * Settings page callback
	 */
	public function moviecraft_handle_plugin_setting_page_callback()
	{
		// Get the API key.
		$api_key = get_option('moviecraft_movie_api_key', '');
?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

			<form method="post" action="options.php">
				<?php settings_fields('moviecraft_settings'); ?>
				<?php do_settings_sections('moviecraft_settings'); ?>

				<h2><?php esc_html_e('Movie Lists Block Settings', 'moviecraft'); ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="moviecraft_movie_api_key">
								<?php esc_html_e('The Movie Database API Key', 'moviecraft'); ?>
							</label>
						</th>
						<td>
							<input type="text"
								id="moviecraft_movie_api_key"
								name="moviecraft_movie_api_key"
								value="<?php echo esc_attr($api_key); ?>"
								class="regular-text" />
							<p class="description">
								<?php esc_html_e('Enter your API key from The Movie Database (TMDb). Get one at https://www.themoviedb.org/settings/api.', 'moviecraft'); ?>
							</p>
						</td>
					</tr>
				</table>

				<!-- Add more settings sections here for other blocks -->

				<?php submit_button(); ?>
			</form>
		</div>
<?php
	}
}
