<?php
/*
Plugin Name: Responsible Customiser
Plugin URI: https://github.com/cftp/responsible-customiser
Description: Viewport resizing comes to the WordPress customiser
Version: 0.7.0
Author: Code For The People
Author URI: http://codeforthepeople.com
Text Domain: responsible-customiser
Domain Path: /assets/languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright © 2014 Code for the People ltd

                _____________
               /      ____   \
         _____/       \   \   \
        /\    \        \___\   \
       /  \    \                \
      /   /    /          _______\
     /   /    /          \       /
    /   /    /            \     /
    \   \    \ _____    ___\   /
     \   \    /\    \  /       \
      \   \  /  \____\/    _____\
       \   \/        /    /    / \
        \           /____/    /___\
         \                        /
          \______________________/


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

// Don't be a douche, eh!
if ( ! defined( 'ABSPATH' ) ) exit;

// Allow for reuse
if ( ! class_exists( 'responsible_customiser' ) ) :
	add_action( 'init', array( 'responsible_customiser', 'instance' ) );

/**
 * Allows for responsiveness in the customiser.
 *
 * @since 0.1.0
 */
class responsible_customiser {

	/**
	 * @since 0.7.0
	 * @static
	 * @var    \responsible_customiser Reusable object instance.
	 */
	protected static $instance = null;


	/**
	 * Creates a new instance. Called on 'after_setup_theme'.
	 * May be used to access class methods from outside.
	 *
	 * @since 0.7.0
	 * @see    __construct()
	 * @static
	 * @return \wp_less
	 */
	public static function instance() {
		null === self::$instance AND self::$instance = new self;
		return self::$instance;
	}

	/**
	 * Constructor - registers any hooks.
	 *
	 * @since 0.1.0
	 * @return \responsible_customiser
	 */
	public function __construct() {

		// Queue up the main script that adds the responsive controls
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ) );

	}

	/**
	 * Queue up scripts we need within the WordPress customiser
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function customize_controls_enqueue_scripts() {

		// Our main customiser JS
		wp_register_script(
			'responsible-customiser',
			plugin_dir_url( __FILE__ ) . 'assets/js/responsible.js',
			array('jquery', 'jquery-ui-core'),
			filemtime( plugin_dir_path( __FILE__ ) . '/assets/js/responsible.js' ) // clears caches when modified
		);

		// Send the device sizes and HTML as variables to the script for use client-side
		wp_localize_script( 'responsible-customiser', 'responsible', array(
			'html' => self::customiser_control(),
			// @todo Filter/configure this:
			'sizes' => array(
				'mobile' => array( 'width' => 320, 'height' => 568, 'alt' => 'mobile-landscape' ),
				'mobile-landscape' => array( 'width' => 568, 'height' => 320, 'alt' => 'mobile' ),
				'small-tablet' => array( 'width' => 600, 'height' => 800, 'alt' => 'small-tablet-landscape' ),
				'small-tablet-landscape' => array( 'width' => 800, 'height' => 600, 'alt' => 'small-tablet' ),
				'tablet' => array( 'width' => 768, 'height' => 1024, 'alt' => 'tablet-landscape' ),
				'tablet-landscape' => array( 'width' => 1024, 'height' => 768, 'alt' => 'tablet' ),
				'laptop' => array( 'width' => 1280, 'height' => 800 ),
				'desktop' => array( 'width' => 1920, 'height' => 1080 ),
			),
		) );
		wp_enqueue_script('responsible-customiser');

		// Font awesome for pretty icons
		wp_register_style(
			'font-awesome',
			plugin_dir_url( __FILE__ ) . 'assets/css/font-awesome.min.css',
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . '/assets/css/font-awesome.min.css' ),
			'screen'
		);
		wp_enqueue_style('font-awesome');

		// Styling our "control"
		wp_register_style(
			'responsible-customiser-css',
			plugin_dir_url( __FILE__ ) . 'assets/css/customiser.css',
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . '/assets/css/customiser.css' ),
			'screen'
		);
		wp_enqueue_style('responsible-customiser-css');

	}

	/**
	 * Construct the HTML to insert into the customiser
	 *
	 * @since 0.1.0
	 * @access protected
	 * @return string
	 */
	protected function customiser_control() {

		/*
		 * @todo Switch to a filtered array of devices and sizes to construct this
		 */
		$html = '<div id="responsible">
  <ul>
    <li><a class="responsible-size mobile" href="mobile"><span class="fa fa-mobile"></span></a></li>
    <li><a class="responsible-size small-tablet" href="small-tablet"><span class="fa fa-tablet"></span></a></li>
    <li><a class="responsible-size tablet" href="tablet"><span class="fa fa-tablet"></span></a></li>
    <li><a class="responsible-size laptop" href="laptop"><span class="fa fa-laptop"></span></a></li>
    <li><a class="responsible-size desktop" href="desktop"><span class="fa fa-desktop"></span></a></li>
  </ul>
</div>';
		return $html;

	}

}

endif; // instance