<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Addons configuration
 *
 * @filter us_config_addons
 */

global $us_template_directory;

return array(
	array(
		'name' => 'Visual Composer',
		'description' => __( 'Most popular drag & drop WordPress page builder. Save tons of time working on your website content.', 'us' ),
		'slug' => 'js_composer',
		'source' => $us_template_directory . '/vendor/plugins/js_composer.zip',
		'version' => '5.1.1',
		'free' => FALSE,
		'changelog_url' => 'https://wpbakery.atlassian.net/wiki/display/VC/Release+Notes',
	),
	array(
		'name' => 'Header Builder',
		'description' => __( 'Unique addon that allows to modify website header using a special drag & drop builder. Create any header layout you can imagine!', 'us' ),
		'slug' => 'us-header-builder',
		'source' => '',
		'version' => '',
		'free' => FALSE,
		'changelog_url' => 'https://help.us-themes.com/' . strtolower( US_THEMENAME ) . '/changelog/',
	),
	array(
		'name' => 'Slider Revolution',
		'description' => __( 'Most advanced responsive WordPress slider plugin, which allows to create beautiful and interactive sliders and presentations.', 'us' ),
		'slug' => 'revslider',
		'source' => '',
		'version' => '',
		'free' => FALSE,
		'changelog_url' => 'http://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380',
	),
	array(
		'name' => 'Ultimate Addons for&nbsp;Visual&nbsp;Composer',
		'description' => __( 'Powerful addon to Visual Composer, which includes many unique content elements.', 'us' ),
		'slug' => 'Ultimate_VC_Addons',
		'source' => '',
		'version' => '',
		'free' => FALSE,
		'changelog_url' => 'http://codecanyon.net/item/ultimate-addons-for-visual-composer/6892199',
	),
	array(
		'name' => 'WooCommerce',
		'description' => __( 'Most popular eCommerce plugin that allows you to sell anything.', 'us' ),
		'slug' => 'woocommerce',
		'source' => 'https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip',
		'version' => '',
		'free' => TRUE,
		'changelog_url' => '',
	),
	array(
		'name' => 'CodeLights Widgets and&nbsp;Elements',
		'description' => __( 'Flexible high-end easy-to-use widgets and content elements for Visual Composer.', 'us' ),
		'slug' => 'codelights-shortcodes-and-widgets',
		'source' => 'https://downloads.wordpress.org/plugin/codelights-shortcodes-and-widgets.latest-stable.zip',
		'version' => '',
		'free' => TRUE,
		'changelog_url' => '',
	),
	array(
		'name' => 'Contact Form 7',
		'description' => __( 'Most popular plugin that allows you to manage multiple contact forms and flexibly customize them and the mail contents.', 'us' ),
		'slug' => 'contact-form-7',
		'source' => 'https://downloads.wordpress.org/plugin/contact-form-7.latest-stable.zip',
		'version' => '',
		'free' => TRUE,
		'changelog_url' => '',
	),
	array(
		'name' => 'The Events Calendar',
		'description' => __( 'Extensible plugin that lets you easily create and manage your calendar of events.', 'us' ),
		'slug' => 'the-events-calendar',
		'source' => 'https://downloads.wordpress.org/plugin/the-events-calendar.latest-stable.zip',
		'version' => '',
		'free' => TRUE,
		'changelog_url' => '',
	),
);
