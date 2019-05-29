<?php

/**
 * WordPress settings for Date Time Picker plugin
 *
 * @package date-time-picker-field
 *
 * @author Carlos Moreira
 */

namespace CMoreira\Plugins\DateTimePicker\Admin;

if ( ! class_exists( 'SettingsPage' ) ) {
	class SettingsPage {

		private $settings_api;

		public function __construct() {
			$this->settings_api = new SettingsAPI();

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		public function admin_init() {

			// set the settings.
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_fields( $this->get_settings_fields() );

			// initialize settings.
			$this->settings_api->admin_init();
		}

		public function admin_menu() {
			$title = __( 'Date & Time Picker', 'date-time-picker-field' );
			add_options_page( $title, $title, 'manage_options', 'dtp_settings', array( $this, 'plugin_page' ) );
		}

		public function get_settings_sections() {
			$sections = array(
				array(
					'id'    => 'dtpicker',
					'title' => __( 'Basic Settings', 'date-time-picker-field' ),
				),

				array(
					'id'    => 'dtpicker_advanced',
					'title' => __( 'Advanced Settings', 'date-time-picker-field' ),
				),
			);
			return $sections;
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		public function get_settings_fields() {

			global $wp_locale;

			$tzone = get_option('timezone_string');
			if( $tzone !== '') {
				date_default_timezone_set( $tzone );
			} else {
				$offset        = get_option('gmt_offset');
				$timezone_name = timezone_name_from_abbr( '', $offset * 3600, false );
				date_default_timezone_set( $timezone_name );
			}

			// existing languages in datetime jquery script.
			$available = $this->available_lang_codes();
			$langs     = array_keys( $available );

			$languages = array();
			$languages['auto'] = __( 'Default - Detect page language', 'date-time-picker-field' );

			require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
			$translations = wp_get_available_translations();
			foreach ( $langs as $locale ) {
				if ( isset( $translations[ $locale ] ) ) {
					$translation = $translations[ $locale ];
					$languages[ $available[ $locale ] ] = $translation[ 'native_name' ];
				} else {
					if( $locale === 'en_US' ) {
						// we don't translate this string, since we are displaying in native name.
						$languages[ 'en' ] = 'English (US)';
					}
				}
			}

			/* translators: %s is a day of the week */
			$allowed_string = __( 'Allowed times for %s', 'date-time-picker-field' );

			$settings_fields = array(
				'dtpicker_advanced' => array(
					array(
						'name'    => 'disabled_days',
						'label'   => __( 'Disable Week Days', 'date-time-picker-field' ),
						'desc'    => __( 'Select days you want to <strong>disable</strong>.', 'date-time-picker-field' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'0' => $wp_locale->get_weekday( 0 ),
							'1' => $wp_locale->get_weekday( 1 ),
							'2' => $wp_locale->get_weekday( 2 ),
							'3' => $wp_locale->get_weekday( 3 ),
							'4' => $wp_locale->get_weekday( 4 ),
							'5' => $wp_locale->get_weekday( 5 ),
							'6' => $wp_locale->get_weekday( 6 ),
						),
					),

					array(
						'name'    => 'disabled_calendar_days',
						'label'   => __( 'Disable specific dates', 'date-time-picker-field' ),
						'desc'    => __( 'Add the dates you want to disable divided by commas, in the format you have selected. Useful to disable holidays for example.', 'date-time-picker-field' ),
						'default' => '',
					),

					array(
						'name'    => 'allowed_times',
						'label'   => __( 'Default list of allowed times', 'date-time-picker-field' ),
						'desc'    => __( 'Write the allowed times to <strong>override</strong> the time step and serve as default if you use the options below.<br> Values still need to be within minimum and maximum times defined in the basic settings.<br> Use the time format separated by commas. Example: 09:00,11:00,12:00,21:00<br>You need to list all the options.', 'date-time-picker-field' ),
						'default' => '',
					),

					array(
						'name'    => 'sunday_times',
						'label'   => sprintf( $allowed_string, $wp_locale->get_weekday( 0 ) ),
						'default' => '',
					),

					array(
						'name'    => 'monday_times',
						'label'   => sprintf( $allowed_string, $wp_locale->get_weekday( 1 ) ),
						'default' => '',
					),

					array(
						'name'    => 'tuesday_times',
						'label'   => sprintf( $allowed_string, $wp_locale->get_weekday( 2 ) ),
						'default' => '',
					),

					array(
						'name'    => 'wednesday_times',
						'label'   => sprintf( $allowed_string, $wp_locale->get_weekday( 3 ) ),
						'default' => '',
					),
					array(
						'name'    => 'thursday_times',
						'label'   => sprintf( $allowed_string, $wp_locale->get_weekday( 4 ) ),
						'default' => '',
					),
					array(
						'name'    => 'friday_times',
						'label'   => sprintf( $allowed_string, $wp_locale->get_weekday( 5 ) ),
						'default' => '',
					),
					array(
						'name'    => 'saturday_times',
						'label'   => sprintf( $allowed_string, $wp_locale->get_weekday( 6 ) ),
						'default' => '',
						'desc'    => __( 'The allowed times options above will only work if you set the default list of allowed times.', 'date-time-picker-field' ),
					),
				),

				'dtpicker'          => array(
					array(
						'name'              => 'selector',
						'label'             => __( 'CSS Selector', 'date-time-picker-field' ),
						'desc'              => __( 'Selector of the input field you want to target and transform into a picker. You can enter multiple selectors separated by commas.', 'date-time-picker-field' ),
						'placeholder'       => __( '.class_name or #field_id', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					),
					array(
						'name'    => 'locale',
						'label'   => __( 'Language', 'date-time-picker-field' ),
						'desc'    => __( 'Language to display the month and day labels.', 'date-time-picker-field' ),
						'type'    => 'select',
						'default' => 'auto',
						'options' => $languages,
					),

					array(
						'name'    => 'theme',
						'label'   => __( 'Theme', 'date-time-picker-field' ),
						'desc'    => __( 'Calendar visual style.', 'date-time-picker-field' ),
						'type'    => 'select',
						'default' => 'default',
						'options' => array(
							'default' => __( 'Default', 'date-time-picker-field' ),
							'dark'    => __( 'Dark', 'date-time-picker-field' ),
						),
					),

					array(
						'name'    => 'datepicker',
						'label'   => __( 'Display Calendar', 'date-time-picker-field' ),
						'desc'    => __( 'Display date picker calendar.', 'date-time-picker-field' ),
						'type'    => 'checkbox',
						'value'   => '1',
						'default' => 'on',
					),

					array(
						'name'    => 'timepicker',
						'label'   => __( 'Display Time', 'date-time-picker-field' ),
						'desc'    => __( 'Display time picker.', 'date-time-picker-field' ),
						'type'    => 'checkbox',
						'value'   => '1',
						'default' => 'on',
					),

					array(
						'name'    => 'placeholder',
						'label'   => __( 'Keep Placeholder', 'date-time-picker-field' ),
						'desc'    => __( 'If enabled, original placeholder will be kept. If disabled it will be replaced with current date or next available time depending on your settings.', 'date-time-picker-field' ),
						'type'    => 'checkbox',
						'value'   => '1',
						'default' => 'off',
					),

					array(
						'name'    => 'preventkeyboard',
						'label'   => __( 'Prevent Keyboard Edit', 'date-time-picker-field' ),
						'desc'    => __( 'If enabled, it wont be possible to edit the text. This will also prevent the keyboard on mobile devices to display when selecting the date.', 'date-time-picker-field' ),
						'type'    => 'checkbox',
						'value'   => 'on',
						'default' => 'off',
					),

					array(
						'name'    => 'minDate',
						'label'   => __( 'Disable Past Dates', 'date-time-picker-field' ),
						'desc'    => sprintf(
							// translators: the %s will be a timezone name
								__( 'If enabled, past dates (and times) can\'t be selected. Consider the plugin will use the timezone you have in your general settings to perform this calculation. Your current timezone is %s.', 'date-time-picker-field' ),
								$tzone
								),
						'type'    => 'checkbox',
						'value'   => 'on',
						'default' => 'off',
					),

					array(
						'name'              => 'step',
						'label'             => __( 'Time Step', 'date-time-picker-field' ),
						'desc'              => __( 'Time interval in minutes for time picker options.', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '60',
						'sanitize_callback' => 'sanitize_text_field',
					),

					array(
						'name'              => 'minTime',
						'label'             => __( 'Minimum Time', 'date-time-picker-field' ),
						'desc'              => __( 'Time options will start from this. Leave empty for none. Use the format you selected for the time. For example: 08:00 AM.', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					),

					array(
						'name'              => 'maxTime',
						'label'             => __( 'Maximum Time', 'date-time-picker-field' ),
						'desc'              => __( 'Time options will not be later than this specified time. Leave empty for none. Use the format you selected for the time. For example: 08:00 PM.', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					),

					array(
						'name'              => 'offset',
						'label'             => __( 'Offset for available times', 'date-time-picker-field' ),
						'desc'              => __( 'Time interval in minutes to advance next available time. For example, set "45" if you only want time entries 45m from now to be available. Works better when option to disable past dates is also enabled.', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '0',
						'sanitize_callback' => 'sanitize_text_field',
					),

					array(
						'name'              => 'max_date',
						'label'             => __( 'Maximum Date', 'date-time-picker-field' ),
						'desc'              => __( 'Use the European day-month-year format or an english string that is accepted by the <a target="_blank" href="https://php.net/manual/en/function.strtotime.php">strtotime PHP function</a>. (Ex: "+5 days")<br> Leave empty to set no limit.', 'date-time-picker-field' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					),

					array(
						'name'    => 'dateformat',
						'label'   => __( 'Date Format', 'date-time-picker-field' ),
						'desc'    => '',
						'type'    => 'radio',
						'options' => array(
							'YYYY-MM-DD' => __( 'Year-Month-Day', 'date-time-picker-field' ) . ' ' . date( 'Y-m-d' ),
							'YYYY/MM/DD' => __( 'Year/Month/Day', 'date-time-picker-field' ) . ' ' . date( 'Y/m/d' ),
							'DD-MM-YYYY' => __( 'Day-Month-Year', 'date-time-picker-field' ) . ' ' . date( 'd-m-Y' ),
							'DD/MM/YYYY' => __( 'Day/Month/Year', 'date-time-picker-field' ) . ' ' . date( 'd/m/Y' ),
							'MM-DD-YYYY' => __( 'Month-Day-Year', 'date-time-picker-field' ) . ' ' . date( 'm-d-Y' ),
							'MM/DD/YYYY' => __( 'Month/Day/Year', 'date-time-picker-field' ) . ' ' . date( 'm/d/Y' ),
							'DD.MM.YYYY' => __( 'Day.Month.Year', 'date-time-picker-field' ) . ' ' . date( 'd.m.Y' ),
						),
						'default' => 'YYYY-MM-DD',
					),

					array(
						'name'    => 'hourformat',
						'label'   => __( 'Hour Format', 'date-time-picker-field' ),
						'desc'    => '',
						'type'    => 'radio',
						'options' => array(
							'HH:mm'   => 'H:M ' . date( 'H:i' ),
							'hh:mm A' => 'H:M AM/PM ' . date( 'h:i A' ),
						),
						'default' => 'hh:mm A',
					),
					array(
						'name'    => 'load',
						'label'   => __( 'When to Load', 'date-time-picker-field' ),
						'desc'    => __( 'Choose to search for the css selector across the website or only when the shortcode [datetimepicker] exists on a page.<br> Use the shortcode to prevent the script from loading across all pages.', 'date-time-picker-field' ),
						'type'    => 'radio',
						'options' => array(
							'full'      => __( 'Across the full website', 'date-time-picker-field' ),
							'admin'     => __( 'Admin panel only', 'date-time-picker-field' ),
							'fulladmin' => __( 'Full website including admin panel', 'date-time-picker-field' ),
							'shortcode' => __( 'Only when shortcode [datetimepicker] exists on a page.', 'date-time-picker-field' ),
						),
						'default' => 'full',
					),
				),
			);

			return $settings_fields;
		}

		public function plugin_page() {
			echo '<div class="wrap">';

			echo '<h2>' . __( 'Date & Time Picker Settings', 'dtp' ) . '</h2>';

			$this->settings_api->show_navigation();
			$this->settings_api->show_forms();

			echo '</div>';
		}

		/**
		 * Get all the pages
		 *
		 * @return array page names with key value pairs
		 */
		public function get_pages() {
			$pages         = get_pages();
			$pages_options = array();
			if ( $pages ) {
				foreach ( $pages as $page ) {
					$pages_options[ $page->ID ] = $page->post_title;
				}
			}

			return $pages_options;
		}

		/**
		 * Get array with available languages where key is the WordPress lang code and value is the jquery script lang code.
		 *
		 * @return array of language codes
		 */
		public function available_lang_codes() {

			$available = array(
				'ar'    => 'ar',
				'az'    => 'az',
				'bg_BG' => 'bg',
				'bs_BG' => 'bs',
				'ca'    => 'ca',
				'zh_CN' => 'ch',
				'cz_CZ' => 'cs',
				'da_DK' => 'da',
				'de_DE' => 'de',
				'el'    => 'el',
				'en_US' => 'en',
				'en_GB' => 'en-GB',
				'es_ES' => 'es',
				'et'    => 'et',
				'eu'    => 'eu',
				'fa_IR' => 'fa',
				'fi'    => 'fi',
				'fr_FR' => 'fr',
				'gl_ES' => 'gl',
				'he_IL' => 'he',
				'hr'    => 'hr',
				'hu_HU' => 'hu',
				'id_ID' => 'id',
				'it_IT' => 'it',
				'ja   ' => 'ja',
				'ko_KO' => 'ko',
				'kr_KR' => 'kr',
				'lt_LT' => 'lt',
				'lv'    => 'lv',
				'mk_MK' => 'mk',
				'mn'    => 'mn',
				'nl_NL' => 'nl',
				'nb_NO' => 'no',
				'pl_PL' => 'pl',
				'pt_PT' => 'pt',
				'pt_BR' => 'pt-BR',
				'ro_RO' => 'ro',
				'ru_RU' => 'ru',
				'sv_SE' => 'se',
				'sk_SK' => 'sk',
				'sl_SL' => 'sl',
				'sq'    => 'sq',
				'sr_RS' => 'sr',
				'sr_YU' => 'sr-YU',
				'sv_SE' => 'sv',
				'th'    => 'th',
				'tr_TR' => 'tr',
				'uk'    => 'uk',
				'vi'    => 'vi',
				'zh_ZH' => 'zh',
				'zh_TW' => 'zh-TW',
			);

			return $available;

		}

	}
}