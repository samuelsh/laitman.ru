<?php
/*
Plugin Name: Unfancy Quote
Plugin URI: http://www.semiologic.com/software/unfancy-quote/
Description: RETIRED - Removes WordPress fancy quotes, which is very useful if you post code snippets to your site.
Version: 3.2.1
Author: Denis de Bernardy, Mike Koepke
Author URI: http://www.getsemiologic.com
Text Domain: sem-unfancy-quote
Domain Path: /lang
License: Dual licensed under the MIT and GPLv2 licenses
*/

/*
Terms of use
------------

This software is copyright Denis de Bernardy & Mike Koepke, and is distributed under the terms of the MIT and GPLv2 licenses.
**/


class sem_unfancy_quote {
	/**
	 * Plugin instance.
	 *
	 * @see get_instance()
	 * @type object
	 */
	protected static $instance = NULL;

	/**
	 * URL to this plugin's directory.
	 *
	 * @type string
	 */
	public $plugin_url = '';

	/**
	 * Path to this plugin's directory.
	 *
	 * @type string
	 */
	public $plugin_path = '';

	/**
	 * Access this plugin’s working instance
	 *
	 * @wp-hook plugins_loaded
	 * @return  object of this class
	 */
	public static function get_instance()
	{
		NULL === self::$instance and self::$instance = new self;

		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 *
	 */
	public function __construct() {
		$this->plugin_url    = plugins_url( '/', __FILE__ );
		$this->plugin_path   = plugin_dir_path( __FILE__ );

		add_action( 'plugins_loaded', array ( $this, 'init' ) );
	}


	/**
	 * init()
	 *
	 * @return void
	 **/

	function init() {
		// more stuff: register actions and filters
		add_filter('category_description', array($this, 'strip_fancy_quotes'), 20);
		add_filter('list_cats', array($this, 'strip_fancy_quotes'), 20);
		add_filter('comment_author', array($this, 'strip_fancy_quotes'), 20);
		add_filter('comment_text', array($this, 'strip_fancy_quotes'), 20);
		add_filter('single_post_title', array($this, 'strip_fancy_quotes'), 20);
		add_filter('the_title', array($this, 'strip_fancy_quotes'), 20);
		add_filter('the_content', array($this, 'strip_fancy_quotes'), 20);
		add_filter('the_excerpt', array($this, 'strip_fancy_quotes'), 20);
		add_filter('bloginfo', array($this, 'strip_fancy_quotes'), 20);
		add_filter('widget_text', array($this, 'strip_fancy_quotes'), 20);
	}

	/**
	 * strip_fancy_quotes()
	 *
	 * @param string $text
	 * @return string $text
	 **/

	function strip_fancy_quotes($text = '') {
		$text = str_replace(array("&#8216;", "&#8217;", "&#8242;"), "&#039;", $text);
		$text = str_replace(array("&#8220;", "&#8221;", "&#8243;"), "&#034;", $text);

		return $text;
	} # strip_fancy_quotes()
}

$sem_unfancy_quote = sem_unfancy_quote::get_instance();