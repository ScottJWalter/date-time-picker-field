<?php
/**
 * Plugin Name:     Date Time Picker Field
 * Plugin URI:      https://cmoreira.net/date-and-time-picker-for-wordpress/
 * Description:     Allows you to enable date and time picker fields in your website using css selectors.
 * Author:          Carlos Moreira
 * Author URI:      https://cmoreira.net
 * Text Domain:     date-time-picker-field
 * Domain Path:     /lang
 * Version:         1.7.8.2
 * License: GPL v2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @package date-time-picker-field
 *
 * Version Log
 *  * v.1.7.8.2 - 06.06.2019
 * - default values fix
 *
 *  * v.1.7.8.1 - 29.05.2019
 * - Refractor code
 * - Language Improvements
 *
 *  * v.1.7.7 - 23.05.2019
 * - Option to set maximum date
 * - Option to detect language automatically
 *
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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// composer autoload.
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

// check if class init exists and load static method.
if ( class_exists( 'CMoreira\\Plugins\\DateTimePicker\\Init' ) ) {
	CMoreira\Plugins\DateTimePicker\Init::init();
}
