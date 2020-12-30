<?php


namespace MSPFramework;


/**
 * Class SettingsPageSection
 *
 * This class contains settings page section data
 *
 * @package MSP
 */
abstract class Section {
	/**
	 * @var SettingsPage The settings page where this template will be used
	 */
	public $settings_page;
	/**
	 * @var string the section id
	 */
	public $id = '';
	/**
	 * @var string the section menu name
	 */
	public $name = '';
	/**
	 * @var Icon the section menu icon
	 */
	public $icon;
	/**
	 * @var string the section title
	 */
	public $title;
	/**
	 * @var string the section subtitle
	 */
	public $subtitle;

	/**
	 * SettingsPageSection constructor.
	 *
	 * @param SettingsPage $settings_page The settings page where this template will be used
	 * @param string $id the section id
	 * @param string $name the section display name
	 */
	public function __construct( $settings_page, $id, $name ) {
		$this->settings_page = $settings_page;
		$this->id            = $id;
		$this->name          = $name;
	}

	/**
	 * Enqueues the assets needed for the section.
     *
     * @param SettingsPage $settings_page
     */
    public static function enqueue_assets( $setting_page )
    {
    }

	/**
	 * Render the section contents
	 */
	abstract public function render();
}