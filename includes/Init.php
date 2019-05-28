<?php
/**
 * @package date-time-picker-field
 */

namespace CMoreira\Plugins\DateTimePicker;

if ( ! class_exists( 'Init' ) ) {
	class Init {

		public static function init(){

			// Creates Settings Page.
			new Admin\SettingsPage();

			// Create Date Picker Instance
			new DateTimePicker();
		}
	}
}