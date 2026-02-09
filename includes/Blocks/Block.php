<?php

namespace MovieCraft\Blocks;

class Block
{
	/**
	 * Undocumented variable
	 *
	 * @var [type]
	 */
	private static $instance;
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public static function init()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	public $movie_list_callback_instance;
	/**
	 * Initiate Class
	 */
	public function __construct()
	{
		add_action('init', array($this, 'register_block'));
		/**
		 * Initiniating Movie List Callback File
		 */
		$this->movie_list_callback_instance = Inc\Class_Movie_List_Callback::init();
	}
	/**
	 * Register Block
	 *
	 * @return void
	 */
	public function register_block()
	{
		// Movies and API
		register_block_type_from_metadata(MOVIE_CRAFT_STARTER_PATH . '/build/blocks/theatres-movies');
		register_block_type_from_metadata(MOVIE_CRAFT_STARTER_PATH . '/build/blocks/upcoming-movies');
		register_block_type_from_metadata(MOVIE_CRAFT_STARTER_PATH . '/build/blocks/upcoming-movie-slider');
		register_block_type_from_metadata(MOVIE_CRAFT_STARTER_PATH . '/build/blocks/top-rated-movie-lists');
		register_block_type_from_metadata(
			MOVIE_CRAFT_STARTER_PATH . '/build/blocks/movie-lists',
			array(
				'render_callback' => array($this, 'movie_lists_render_frontend_callback'),
			)
		);
	}
}
