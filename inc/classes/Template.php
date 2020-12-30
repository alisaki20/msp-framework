<?php


namespace MSPFramework;


/**
 * Class Template
 *
 * @package MSPFramework
 */
class Template {
	/**
	 * @var SettingsPage The settings page where this template will be used
	 */
	public $settings_page;

	/**
	 * Template constructor.
	 *
	 * @param SettingsPage $settings_page The settings page where this template will be used
	 */
	public function __construct( $settings_page ) {
		$this->settings_page = $settings_page;
	}

	/**
	 * Enqueues the assets needed for the template.
	 */
	public function enqueue_assets() {
	}

	/**
	 * Render settings page template content
	 */
	public function render() {
	}
}