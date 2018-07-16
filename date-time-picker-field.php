<?php
/**
 * Plugin Name:     Date Time Picker Field
 * Plugin URI:      https://cmoreira.net/date-and-time-picker-for-wordpress/
 * Description:     Allows you to enable date and time picker fields in your website using css selectors.
 * Author:          Carlos Moreira
 * Author URI:      https://cmoreira.net
 * Text Domain:     date-time-picker-field
 * Domain Path:     /languages
 * Version:         1.2.2
 * Text Domain:     dtpicker
 *
 * @package date-time-picker-field
 */

/**
 * Version Log
 * v.1.2.2 - 16.07.2018
 * - Included option to prevent keyboard edit
 *
 * v.1.2.1 - 16.07.2018
 * - Added option to allow original placeholder to be kept
 *
 * v.1.2 - 26.06.2018
 * - Solved bug on date and hour format
 *
 * V.1.1 - 26.06.2018
 * - Improved options handling
 * - Added direct link to settings page from plugins screen
 *
 * v.1.0
 * - Initial Release
 */

// Add Settings Page.
require_once dirname( __FILE__ ) . '/includes/class.settings-api.php';
require_once dirname( __FILE__ ) . '/admin/class-dtp-settings-page.php';

// Creates Settings Page.
new DTP_Settings_Page();

/**
 * Function to load necessary files
 *
 * @return void
 */
function dtpicker_scripts() {
	wp_enqueue_style( 'dtpicker', plugins_url( 'vendor/datetimepicker/jquery.datetimepicker.min.css', __FILE__ ) );
	wp_enqueue_script( 'dtpicker-moment', plugins_url( 'vendor/moment/moment.js', __FILE__ ), array(), '1.0.0', true );
	wp_enqueue_script( 'dtpicker', plugins_url( 'vendor/datetimepicker/jquery.datetimepicker.min.js', __FILE__ ), array( 'jquery', 'dtpicker-moment' ), '1.0.0', true );
	wp_enqueue_script( 'dtpicker-build', plugins_url( 'assets/js/dtpicker.js', __FILE__ ), array( 'dtpicker' ), '1.0.0', true );

	$opts = get_option( 'dtpicker' );

	$format = '';

	if ( isset( $opts['datepicker'] ) && 'on' === $opts['datepicker'] ) {
		$format .= $opts['dateformat'];
	}

	if ( isset( $opts['timepicker'] ) && 'on' === $opts['timepicker'] ) {
		$format .= ' ' . $opts['hourformat'];
	}

	$opts['value']  = date( 'Y-m-d' );
	$opts['format'] = $format;

	if ( isset( $opts['placeholder'] ) && 'on' === $opts['placeholder'] ) {
		$opts['value'] = '';
	}

	wp_localize_script( 'dtpicker-build', 'datepickeropts', $opts );
}

//Enqueue scripts according to options
add_action( 'init', 'dtp_enqueue_scripts' );
function dtp_enqueue_scripts() {
	$opts = get_option( 'dtpicker' );
	if ( isset( $opts['load'] ) && 'full' === $opts['load'] ) {
		add_action( 'wp_enqueue_scripts', 'dtpicker_scripts' );
	} else {
		add_shortcode( 'datetimepicker', 'dtpicker_scripts' );
	}
}

//Adds link to settings page
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'dtp_add_action_links' );

function dtp_add_action_links( $links ) {
	$mylinks = array(
		'<a href="' . admin_url( 'options-general.php?page=dtp_settings' ) . '">' . __( 'Settings', 'dtpicker' ) . '</a>',
	);

	return array_merge( $mylinks, $links );
}
