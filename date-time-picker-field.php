<?php
/**
 * Plugin Name:     Date Time Picker Field
 * Plugin URI:      https://cmoreira.net/date-and-time-picker-for-wordpress/
 * Description:     Allows you to enable date and time picker fields in your website using css selectors.
 * Author:          Carlos Moreira
 * Author URI:      https://cmoreira.net
 * Text Domain:     date-time-picker-field
 * Domain Path:     /lang
 * Version:         1.7.6
 *
 * @package date-time-picker-field
 */

/**
 * Version Log
 *  * v.1.7.6 - 24.04.2019
 * - option to disable specific dates
 * - improved time handling - it will now consider the site timezone
 *
 *  * v.1.7.5 - 17.04.2019
 * - improved default time value
 *
 *  * v.1.7.4.1 - 08.04.2019
 * - fixed get_plugin_data() error
 *
 *  * v.1.7.4 - 06.04.2019
 * - language files
 * - add version to loaded scrips and styles
 * - remove unused files
 * - fixed bug on AM/PM time format
 *
 *  * v.1.7.3 - 03.04.2019
 * - Fixed data format issue in some languages
 * - Removed moment library in favour of custom formatter
 *
 * v.1.7.2 - 03.04.2019
 * - Fix IE11 issue
 *
 * v.1.7.1 - 02.04.2019
 * - Added advanced options to better control time options for individual days
 *
 *  * v.1.6 - 16.01.2019
 * - Start of the week now follows general settings option
 * - Added new Day.Month.Year format
 *
 * v.1.5 - 04.10.2018
 * - Option to add minimum and maximum time entries
 * - Option to disable past dates
 *
 * v.1.4 - 05.09.2018
 * - Option to add script also in admin
 *
 * v.1.3 - 24.07.2018
 * - PHP Error "missing file" solved
 *
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

function dtp_load_plugin_textdomain() {
	load_plugin_textdomain( 'date-time-picker-field', '', basename( dirname( __FILE__ ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'dtp_load_plugin_textdomain' );

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

	$tzone = get_option('timezone_string');
	date_default_timezone_set( $tzone );

	$version = dtp_get_version();
	wp_enqueue_script( 'dtp-moment', plugins_url( 'vendor/moment/moment.js', __FILE__ ), array( 'jquery' ), $version, true );
	wp_enqueue_style( 'dtpicker', plugins_url( 'vendor/datetimepicker/jquery.datetimepicker.min.css', __FILE__ ), array(), $version, 'all' );
	wp_enqueue_script( 'dtpicker', plugins_url( 'vendor/datetimepicker/jquery.datetimepicker.min.js', __FILE__ ), array( 'jquery' ), $version, true );
	wp_enqueue_script( 'dtpicker-build', plugins_url( 'assets/js/dtpicker.js', __FILE__ ), array( 'dtpicker','dtp-moment' ), $version, true );

	$opts    = get_option( 'dtpicker' );
	$optsadv = get_option( 'dtpicker_advanced' );
	// merge advanced options
	if ( is_array( $opts ) && is_array( $optsadv ) ) {
		$opts = array_merge( $opts, $optsadv );
	}

	// day of start of week
	$opts['dayOfWeekStart'] = get_option( 'start_of_week' );

	// sanitize disabled days
	$opts['disabled_days']   = isset( $opts['disabled_days'] ) && is_array( $opts['disabled_days'] ) ? array_values( array_map( 'intval', $opts['disabled_days'] ) ) : '';
	$opts['disabled_calendar_days']   = isset( $opts['disabled_calendar_days'] ) && '' !== $opts['disabled_calendar_days'] ? explode( ',', $opts['disabled_calendar_days'] ) : '';
	$opts['allowed_times']   = isset( $opts['allowed_times'] ) && '' !== $opts['allowed_times'] ? array_map( 'dtp_24_time', explode( ',', $opts['allowed_times'] ) ) : '';
	$opts['sunday_times']    = isset( $opts['sunday_times'] ) && '' !== $opts['sunday_times'] ? array_map( 'dtp_24_time',explode( ',', $opts['sunday_times'] ) ) : '';
	$opts['monday_times']    = isset( $opts['monday_times'] ) && '' !== $opts['monday_times'] ? array_map( 'dtp_24_time',explode( ',', $opts['monday_times'] ) ) : '';
	$opts['tuesday_times']   = isset( $opts['tuesday_times'] ) && '' !== $opts['tuesday_times'] ? array_map( 'dtp_24_time',explode( ',', $opts['tuesday_times'] ) ) : '';
	$opts['wednesday_times'] = isset( $opts['wednesday_times'] ) && '' !== $opts['wednesday_times'] ? array_map( 'dtp_24_time',explode( ',', $opts['wednesday_times'] ) ) : '';
	$opts['thursday_times']  = isset( $opts['thursday_times'] ) && '' !== $opts['thursday_times'] ? array_map( 'dtp_24_time',explode( ',', $opts['thursday_times'] ) ) : '';
	$opts['friday_times']    = isset( $opts['friday_times'] ) && '' !== $opts['friday_times'] ? array_map( 'dtp_24_time',explode( ',', $opts['friday_times'] ) ) : '';
	$opts['saturday_times']  = isset( $opts['saturday_times'] ) && '' !== $opts['saturday_times'] ? array_map( 'dtp_24_time', explode( ',', $opts['saturday_times'] ) ) : '';

	// offset
	$opts['offset'] = isset( $opts['offset'] ) ? intval( $opts['offset'] ) : 0;

	// other variables
	$format       = '';
	$clean_format = '';
	$value        = '';

	// fix format
	// $opts['hourformat'] = dtp_format( $opts['hourformat'] );
	// $opts['dateformat'] = dtp_format( $opts['dateformat'] );

	$opts['minTime'] = isset( $opts['minTime'] ) && $opts['minTime'] !== '' ? $opts['minTime'] : '00:00';
	$opts['maxTime'] = isset( $opts['maxTime'] ) && $opts['maxTime'] !== '' ? $opts['maxTime'] : '23:59';

	// workaround AM/PM because of offset issues
	$opts['minTime'] = dtp_24_time( $opts['minTime'] );
	$opts['maxTime'] = dtp_24_time( $opts['maxTime'] );

	if ( isset( $opts['datepicker'] ) && 'on' === $opts['datepicker'] ) {
		$format       .= $opts['dateformat'];
		$clean_format .= dtp_format( $opts['dateformat'] );
	}

	if ( isset( $opts['timepicker'] ) && 'on' === $opts['timepicker'] ) {
		$hformat       = $opts['hourformat'];
		$format       .= ' ' . $hformat;
		$clean_format .= ' H:i';
	}

	$opts['format']       = $format;
	$opts['clean_format'] = $clean_format;

	if ( isset( $opts['placeholder'] ) && 'on' === $opts['placeholder'] ) {
		$opts['value'] = '';
	} else {
		$opts['value'] = dtp_get_next_available_time( $opts );
	}

	$tzone              = get_option('timezone_string');
	$opts['timezone']   = $tzone;
	$toffset            = get_option('gmt_offset');
	$opts['utc_offset'] = $toffset;
	$now                = new DateTime();
	$opts['now']        = $now->format( $opts['clean_format'] );

	wp_localize_script( 'dtpicker-build', 'datepickeropts', $opts );
}

// Enqueue scripts according to options
add_action( 'init', 'dtp_enqueue_scripts' );
function dtp_enqueue_scripts() {
	$opts = get_option( 'dtpicker' );
	if ( isset( $opts['load'] ) && 'full' === $opts['load'] ) {
		add_action( 'wp_enqueue_scripts', 'dtpicker_scripts' );
	} elseif ( isset( $opts['load'] ) && 'admin' === $opts['load'] ) {
		add_action( 'admin_enqueue_scripts', 'dtpicker_scripts' );
	} elseif ( isset( $opts['load'] ) && 'fulladmin' === $opts['load'] ) {
		add_action( 'admin_enqueue_scripts', 'dtpicker_scripts' );
		add_action( 'wp_enqueue_scripts', 'dtpicker_scripts' );
	} else {
		add_shortcode( 'datetimepicker', 'dtpicker_scripts' );
	}
}

// Adds link to settings page
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'dtp_add_action_links' );

function dtp_add_action_links( $links ) {
	$mylinks = array(
		'<a href="' . admin_url( 'options-general.php?page=dtp_settings' ) . '">' . __( 'Settings', 'dtpicker' ) . '</a>',
	);

	return array_merge( $mylinks, $links );
}

function dtp_get_version() {

	$plugin_version = '1.7.5';

	if ( function_exists( 'get_file_data' ) ) {

		$plugin_data = get_file_data(
			__FILE__,
			array(
				'Version' => 'Version',
			)
		);

		if ( $plugin_data ) {
			$plugin_version = $plugin_data['Version'];
		}
	}

	return $plugin_version;
}

/**
 * Format javascript date time format to PHP format. Needed for backwards compatibility.
 *
 * @param [string] $string
 * @return string converted datetime format
 */
function dtp_format( $string ) {
	$replace   = array(
		'hh',
		'HH',
		'mm',
		'A',
		'YYYY',
		'MM',
		'DD',
	);
	$replaceby = array(
		'h',
		'H',
		'i',
		'A',
		'Y',
		'm',
		'd',
	);

	return str_replace( $replace, $replaceby, $string );
}

/**
 * Get next available time based on provided data
 *
 * @param array $opts - get_options('dtpicker') and get_options('dtpicker_advanced')
 * @return string timespamp
 */
function dtp_get_next_available_time( $opts ) {

	// set timezone
	$tzone = get_option('timezone_string');
	date_default_timezone_set( $tzone );

	// setup variables
	$min_time = $opts['minTime'];
	$max_time = $opts['maxTime'];
	$step     = $opts['step'];
	$allowed  = $opts['allowed_times'];
	$offset   = isset( $opts['offset'] ) ? intval( $opts['offset'] ) : 0;

	$value = '';
	$now   = new DateTime();
	$next  = new DateTime();

	// add offset minutes
	$now->modify( '+' . $offset . 'minutes' );

	// use allowed dates
	if ( is_array( $opts['allowed_times'] ) && count( $opts['allowed_times'] ) > 0 ) {

		$found = false;

		while( ! $found ) {

			// if weekday is disabled, skip
			$wday = intval( $next->format( 'w' ) );
			if( is_array( $opts['disabled_days'] ) && in_array( $wday, $opts['disabled_days'] ) ){
				$next->modify( '+1 day' );
				continue;
			}

			$week_day = strtolower( $next->format( 'l' ) );

			// if there's a defined number of allowed hours for this day
			if( is_array( $opts[ $week_day . '_times' ] ) ) {

				foreach ( $opts[ $week_day . '_times' ] as $hour ) {

					$dtime  = DateTime::createFromFormat( 'H:i', trim( $hour ) );

					if( ! $dtime ) {
						return '';
					}

					$hour   = intval( $dtime->format('H') );
					$minute = intval( $dtime->format('i') );

					$next->setTime( $hour, $minute );

					if ( $next > $now ) {
						$found = true;
						$value = $next->format( $opts['clean_format'] );
						break;
					}

				}

			}
			// use default allowed times
			else {
				foreach ( $opts[ 'allowed_times' ] as $hour ) {

					$dtime  = DateTime::createFromFormat( 'H:i', trim( $hour ) );
					$hour   = intval( $dtime->format('H') );
					$minute = intval( $dtime->format('i') );

					$next->setTime( $hour, $minute );

					if ( $next > $now ) {
						$found = true;
						$value = $next->format( $opts['clean_format'] );
						break;
					}

				}

			}

			$next->modify( '+1 day' );

		}

		return $value;

	}

	// if there's no default allowed times, we calculate them with min/max and step values
	$min  = isset( $opts['minTime'] ) && $opts['minTime'] !== '' ? $opts['minTime'] : '00:00';
	$max  = isset( $opts['maxTime'] ) && $opts['maxTime'] !== '' ? $opts['maxTime'] : '23:59';

	$range = dtp_hours_range( $min, $max, $opts['step'], $opts['hourformat'] );

	// if weekday is disabled, skip to next enabled day
	$included = false;

	while( ! $included ) {

		$wday = intval( $next->format( 'w' ) );

		if( is_array( $opts['disabled_days'] ) && in_array( $wday, $opts['disabled_days'] ) ){
			$next->modify( '+1 day' );
			$next->setTime( 0, 0 );
			continue;
		}

		$included = true;
	}

	$found = false;

	while ( ! $found ) {

		foreach ( $range as $hour ) {

			$dtime  = DateTime::createFromFormat( 'H:i', trim( $hour ) );
			$hour   = intval( $dtime->format('H') );
			$minute = intval( $dtime->format('i') );

			$next->setTime( $hour, $minute );

			if ( $next > $now ) {
				$found = true;
				$value= $next->format( $opts['clean_format'] );
				break;
			}

		}

		$next->modify( '+1 day' );

	}

	return $value;

}


function dtp_hours_range( $min = '00:00', $max = '23:59', $step = '60', $format = 'H:i' ) {

	// timezone
	$tzone = get_option('timezone_string');
	date_default_timezone_set( $tzone );

	$times    = array();
	$step     = intval( $step ) <= 60 ? intval( $step ) : 60;
	$date     = DateTime::createFromFormat( 'H:i', $min );
	$max_hour = DateTime::createFromFormat( 'H:i', $max );

	if( ! $date ){
		return $times;
	}

	while ( $date <= $max_hour ) {

		array_push( $times, $date->format( 'H:i' ) );
		// increment date - only if it doesn't jump to next hour - we do this because that's what the jquery datetime plugin does

		$minutes = intval( $date->format( 'i' ) );

		if ( ( $minutes + $step ) > 60 ) {
			$date->modify( '+ 1 hour' );
			$date->setTime( $date->format( 'H' ), 0 );
		} else {
			$date->modify( '+' . $step . ' minutes' );
		}
	}

	return $times;
}

/**
 * Convert hour to 24h format
 *
 * @param string $hour
 * @return string 24h formatted hour
 */
function dtp_24_time( $hour = '' ) {

	return date( 'H:i', strtotime( $hour ) );

}