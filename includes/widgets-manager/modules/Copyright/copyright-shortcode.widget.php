<?php
if (! defined('ABSPATH')) {
	exit();
}

/**
 * Helper class for the Copyright.
 *
 * @since 1.2.0
 */
class Copyright_Shortcode
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{

		add_shortcode('phf_current_year', [$this, 'display_current_year']);
		add_shortcode('phf_site_title', [$this, 'display_site_title']);
	}

	/**
	 * Get the phf_current_year Details.
	 *
	 * @return array | string | void $phf_current_year Get Current Year Details.
	 */
	public function display_current_year()
	{

		$phf_current_year = gmdate('Y');
		$phf_current_year = do_shortcode(shortcode_unautop($phf_current_year));
		if (! empty($phf_current_year)) {
			return $phf_current_year;
		}
	}

	/**
	 * Get site title of Site.
	 *
	 * @return string | void.
	 */
	public function display_site_title()
	{

		$phf_site_title = get_bloginfo('name');

		if (! empty($phf_site_title)) {
			return $phf_site_title;
		}
	}
}

new Copyright_Shortcode();
