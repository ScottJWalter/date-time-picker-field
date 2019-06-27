<?php
/**
 * WordPress settings link for Date Time Picker in plugins page
 *
 * @package date-time-picker-field
 *
 * @author Carlos Moreira
 */

namespace CMoreira\Plugins\DateTimePicker\Admin;

/**
 * Adds link to settings in plugin entry on plugins page.
 */
class SettingsLink {

	public $basename = null;

	/**
	 * Register hooks.
	 */
	public function __construct() {

		$this->basename = plugin_basename( dirname( __FILE__, 3 ) );
		$this->register();
	}

	/**
	 * Register class - adds link
	 *
	 * @return void
	 */
	public function register() {
		add_filter( 'plugin_action_links_date-time-picker-field/date-time-picker-field.php', array( $this, 'settings_link' ) );
	}

	/**
	 * Adds settings link
	 *
	 * @param [type] $links
	 * @return void
	 */
	public function settings_link( $links ) {

		$settings_link = sprintf(
			'<a href="options-general.php?page=dtp_settings">%s</a>',
			__( 'Settings', 'date-time-picker-field' )
		);
		array_unshift( $links, $settings_link );
		return $links;
	}
}
