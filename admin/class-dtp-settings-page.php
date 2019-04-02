<?php

/**
 * WordPress settings
 *
 * @author Carlos Moreira
 */
if ( ! class_exists( 'dtpicker_Settings_API_Test' ) ) :
	class DTP_Settings_Page {

		private $settings_api;

		public function __construct() {
			$this->settings_api = new WeDevs_Settings_API();

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		public function admin_init() {

			// set the settings
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_fields( $this->get_settings_fields() );

			// initialize settings
			$this->settings_api->admin_init();
		}

		public function admin_menu() {
			add_options_page( __( 'DateTime Picker', 'dtpicker' ), __( 'DateTime Picker', 'dtpicker' ), 'manage_options', 'dtp_settings', array( $this, 'plugin_page' ) );
		}

		public function get_settings_sections() {
			$sections = array(
				array(
					'id'    => 'dtpicker',
					'title' => __( 'Basic Settings', 'dtpicker' ),
				),

				array(
					'id'    => 'dtpicker_advanced',
					'title' => __( 'Advanced Settings', 'dtpicker' ),
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

			$settings_fields = array(
				'dtpicker_advanced' => array(
					array(
						'name'    => 'disabled_days',
						'label'   => __( 'Disable Days', 'dtpicker' ),
						'desc'    => __( 'Select days you want to <strong>disable</strong>', 'dtpicker' ),
						'type'    => 'multicheck',
						'default' => array(),
						'options' => array(
							'0' => __( 'Sunday', 'dtpicker' ),
							'1' => __( 'Monday', 'dtpicker' ),
							'2' => __( 'Tuesday', 'dtpicker' ),
							'3' => __( 'Wednesday', 'dtpicker' ),
							'4' => __( 'Thursday', 'dtpicker' ),
							'5' => __( 'Friday', 'dtpicker' ),
							'6' => __( 'Saturday', 'dtpicker' ),
						),
					),

					array(
						'name'    => 'allowed_times',
						'label'   => __( 'Default List of allowed times' ),
						'desc'    => __( 'Write the allowed times to <strong>override</strong> the time step and serve as default if you use the options below.<br> Values still need to be within minimum and maximum times defined in the basic settings.<br> Use the time format separated by commas. Example: 09:00,11:00,12:00,21:00<br>You need to list all the options', 'dtpicker' ),
						'default' => '',
					),

					array(
						'name'    => 'sunday_times',
						'label'   => __( 'Allowed times for Sunday' ),

						'default' => '',
					),

					array(
						'name'    => 'monday_times',
						'label'   => __( 'Allowed times for Monday' ),

						'default' => '',
					),

					array(
						'name'    => 'tuesday_times',
						'label'   => __( 'Allowed times for Tuesday' ),

						'default' => '',
					),

					array(
						'name'    => 'wednesday_times',
						'label'   => __( 'Allowed times for Wednesday' ),

						'default' => '',
					),
					array(
						'name'    => 'thursday_times',
						'label'   => __( 'Allowed times for Thursday' ),

						'default' => '',
					),
					array(
						'name'    => 'friday_times',
						'label'   => __( 'Allowed times for Friday' ),

						'default' => '',
					),
					array(
						'name'    => 'saturday_times',
						'label'   => __( 'Allowed times for Saturday' ),

						'default' => '',
					),

				),

				'dtpicker'          => array(
					array(
						'name'              => 'selector',
						'label'             => __( 'CSS Selector', 'dtpicker' ),
						'desc'              => __( 'Selector of the input field you want to target and transform into a picker. You can enter multiple selectors separated by commas.', 'dtpicker' ),
						'placeholder'       => __( '.class_name or #field_id', 'dtpicker' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					),
					array(
						'name'    => 'locale',
						'label'   => __( 'Language', 'dtpicker' ),
						'desc'    => __( 'Language to display the month and day labels', 'dtpicker' ),
						'type'    => 'select',
						'default' => 'en',
						'options' => array(
							'ar'    => __( 'Arabic', 'dtpicker' ),
							'az'    => __( 'Azerbaijanian (Azeri)', 'dtpicker' ),
							'bg'    => __( 'Bulgarian', 'dtpicker' ),
							'bs'    => __( 'Bosanski', 'dtpicker' ),
							'ca'    => __( 'Català', 'dtpicker' ),
							'ch'    => __( 'Simplified Chinese', 'dtpicker' ),
							'cs'    => __( 'Čeština', 'dtpicker' ),
							'da'    => __( 'Dansk', 'dtpicker' ),
							'de'    => __( 'German', 'dtpicker' ),
							'el'    => __( 'Ελληνικά', 'dtpicker' ),
							'en'    => __( 'English', 'dtpicker' ),
							'en-GB' => __( 'English (British)', 'dtpicker' ),
							'es'    => __( 'Spanish', 'dtpicker' ),
							'et'    => __( 'Eesti', 'dtpicker' ),
							'eu'    => __( 'Euskara', 'dtpicker' ),
							'fa'    => __( 'Persian', 'dtpicker' ),
							'fi'    => __( 'Finnish (Suomi)', 'dtpicker' ),
							'fr'    => __( 'French', 'dtpicker' ),
							'gl'    => __( 'Galego', 'dtpicker' ),
							'he'    => __( 'Hebrew (עברית)', 'dtpicker' ),
							'hr'    => __( 'Hrvatski', 'dtpicker' ),
							'hu'    => __( 'Hungarian', 'dtpicker' ),
							'id'    => __( 'Indonesian', 'dtpicker' ),
							'it'    => __( 'Italian', 'dtpicker' ),
							'ja'    => __( 'Japanese', 'dtpicker' ),
							'ko'    => __( 'Korean (한국어)', 'dtpicker' ),
							'kr'    => __( 'Korean', 'dtpicker' ),
							'lt'    => __( 'Lithuanian (lietuvių)', 'dtpicker' ),
							'lv'    => __( 'Latvian (Latviešu)', 'dtpicker' ),
							'mk'    => __( 'Macedonian (Македонски)', 'dtpicker' ),
							'mn'    => __( 'Mongolian (Монгол)', 'dtpicker' ),
							'nl'    => __( 'Dutch', 'dtpicker' ),
							'no'    => __( 'Norwegian', 'dtpicker' ),
							'pl'    => __( 'Polish', 'dtpicker' ),
							'pt'    => __( 'Portuguese', 'dtpicker' ),
							'pt-BR' => __( 'Português(Brasil)', 'dtpicker' ),
							'ro'    => __( 'Romanian', 'dtpicker' ),
							'ru'    => __( 'Russian', 'dtpicker' ),
							'se'    => __( 'Swedish', 'dtpicker' ),
							'sk'    => __( 'Slovenčina', 'dtpicker' ),
							'sl'    => __( 'Slovenščina', 'dtpicker' ),
							'sq'    => __( 'Albanian (Shqip)', 'dtpicker' ),
							'sr'    => __( 'Serbian Cyrillic (Српски)', 'dtpicker' ),
							'sr-YU' => __( 'Serbian (Srpski)', 'dtpicker' ),
							'sv'    => __( 'Svenska', 'dtpicker' ),
							'th'    => __( 'Thai', 'dtpicker' ),
							'tr'    => __( 'Turkish', 'dtpicker' ),
							'uk'    => __( 'Ukrainian', 'dtpicker' ),
							'vi'    => __( 'Vietnamese', 'dtpicker' ),
							'zh'    => __( 'Simplified Chinese (简体中文)', 'dtpicker' ),
							'zh-TW' => __( 'Traditional Chinese (繁體中文)', 'dtpicker' ),
						),
					),

					array(
						'name'    => 'theme',
						'label'   => __( 'Theme', 'dtpicker' ),
						'desc'    => __( 'calendar visual style', 'dtpicker' ),
						'type'    => 'select',
						'default' => 'default',
						'options' => array(
							'default' => __( 'Default', 'dtpicker' ),
							'dark'    => __( 'Dark', 'dtpicker' ),
						),
					),

					array(
						'name'    => 'datepicker',
						'label'   => __( 'Display Calendar', 'dtpicker' ),
						'desc'    => __( 'Display date picker', 'dtpicker' ),
						'type'    => 'checkbox',
						'value'   => '1',
						'default' => 'on',
					),

					array(
						'name'    => 'timepicker',
						'label'   => __( 'Display Time', 'dtpicker' ),
						'desc'    => __( 'Display time picker', 'dtpicker' ),
						'type'    => 'checkbox',
						'value'   => '1',
						'default' => 'on',
					),

					array(
						'name'    => 'placeholder',
						'label'   => __( 'Keep Placeholder', 'dtpicker' ),
						'desc'    => __( 'If enabled, original placeholder will be kept. If disabled it will be replaced with current date.', 'dtpicker' ),
						'type'    => 'checkbox',
						'value'   => '1',
						'default' => 'off',
					),

					array(
						'name'    => 'preventkeyboard',
						'label'   => __( 'Prevent Keyboard Edit', 'dtpicker' ),
						'desc'    => __( 'If enabled, it wont be possible to edit the text. This will also prevent the keyboard on mobile devices to display when selecting the date.', 'dtpicker' ),
						'type'    => 'checkbox',
						'value'   => 'on',
						'default' => 'off',
					),

					array(
						'name'    => 'minDate',
						'label'   => __( 'Disable Past Dates', 'dtpicker' ),
						'desc'    => __( 'If enabled, past dates can\'t be selected', 'dtpicker' ),
						'type'    => 'checkbox',
						'value'   => 'on',
						'default' => 'off',
					),

					array(
						'name'              => 'step',
						'label'             => __( 'Time Step', 'dtpicker' ),
						'desc'              => __( 'Time interval in minutes for time picker options', 'dtpicker' ),
						'type'              => 'text',
						'default'           => '60',
						'sanitize_callback' => 'sanitize_text_field',
					),

					array(
						'name'              => 'minTime',
						'label'             => __( 'Minimum Time', 'dtpicker' ),
						'desc'              => __( 'Time options will start from this. Leave empty for none. Use the format you selected for the time. For example: 08:00 AM', 'dtpicker' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					),

					array(
						'name'              => 'maxTime',
						'label'             => __( 'Maximum Time', 'dtpicker' ),
						'desc'              => __( 'Time options will not be later than this specified time. Leave empty for none. Use the format you selected for the time. For example: 08:00 PM', 'dtpicker' ),
						'type'              => 'text',
						'default'           => '',
						'sanitize_callback' => 'sanitize_text_field',
					),

					array(
						'name'    => 'dateformat',
						'label'   => __( 'Date Format', 'dtpicker' ),
						'desc'    => __( 'Date format', 'dtpicker' ),
						'type'    => 'radio',
						'options' => array(
							'YYYY-MM-DD' => __( 'Year-Month-Day', 'dtpicker' ) . ' ' . date( 'Y-m-d' ),
							'YYYY/MM/DD' => __( 'Year/Month/Day', 'dtpicker' ) . ' ' . date( 'Y/m/d' ),
							'DD-MM-YYYY' => __( 'Day-Month-Year', 'dtpicker' ) . ' ' . date( 'd-m-Y' ),
							'DD/MM/YYYY' => __( 'Day/Month/Year', 'dtpicker' ) . ' ' . date( 'd/m/Y' ),
							'MM-DD-YYYY' => __( 'Month-Day-Year', 'dtpicker' ) . ' ' . date( 'm-d-Y' ),
							'MM/DD/YYYY' => __( 'Month/Day/Year', 'dtpicker' ) . ' ' . date( 'm/d/Y' ),
							'DD.MM.YYYY' => __( 'Day.Month.Year', 'dtpicker' ) . ' ' . date( 'd.m.Y' ),
						),
						'default' => 'YYYY-MM-DD',
					),

					array(
						'name'    => 'hourformat',
						'label'   => __( 'Hour Format', 'dtpicker' ),
						'desc'    => __( 'Hour format', 'dtpicker' ),
						'type'    => 'radio',
						'options' => array(
							'HH:mm'   => 'H:M ' . date( 'H:i' ),
							'hh:mm A' => 'H:M AM/PM ' . date( 'h:i A' ),
						),
						'default' => 'hh:mm A',
					),
					array(
						'name'    => 'load',
						'label'   => __( 'When to Load', 'dtpicker' ),
						'desc'    => __( 'Choose to search for the selector across the website or only when the shortcode [datetimepicker] exists on a page.<br> Use the shortcode to prevent the script from loading across all pages', 'dtpicker' ),
						'type'    => 'radio',
						'options' => array(
							'full'      => __( 'Across the full website', 'dtpicker' ),
							'admin'     => __( 'Admin panel only', 'dtpicker' ),
							'fulladmin' => __( 'Full website including admin panel', 'dtpicker' ),
							'shortcode' => __( 'Only when shortcode [datetimepicker] exists on a page.', 'dtpicker' ),
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

	}
endif;
