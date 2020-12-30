<?php


namespace MSPFramework;


/**
 * Class SettingsPageConfig
 *
 * This class contains configurations for SettingsPage
 *
 * @package MSP
 */
class Config {
    /**
     * @var $debug_mod bool whether you are want to enable debug mod.
     */
    public $debug_mod = false;

	/**
	 * @var $page_capability string the capability required to use settings page.
	 */
	public $page_capability = 'manage_options';

	/**
	 * @var $page_slug string the page slug that appears on settings page url.
	 */
	public $page_slug = '';

	/**
	 * @var $page_title string the page title that appears on settings page tab.
	 */
	public $page_title = '';

	/**
	 * @var $page_logo_url string the page logo that appears on the header of settings page.
	 */
	public $page_logo_url;

	/**
	 * @var $menu_title string the text that appears in menu item.
	 */
	public $menu_title = '';

	/**
	 * @var $menu_type string the menu type. allowed values: 'menu', 'submenu', other values equal 'none'.
	 */
	public $menu_type = 'submenu';

	/**
	 * @var $menu_parent_slug string the submenu parent slug.
	 */
	public $menu_parent_slug = 'themes.php';

	/**
	 * @var $menu_icon_url string The URL to the icon to be used for admin menu.
	 *
	 * Pass a base64-encoded SVG using a data URI, which will be colored to match
	 * the color scheme. This should begin with 'data:image/svg+xml;base64,'.
	 * Pass the name of a Dashicons helper class to use a font icon,
	 * e.g. 'dashicons-chart-pie'.
	 * Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
	 */
	public $menu_icon_url = '';

	/**
	 * @var int $menu_positione the position of the settings page in admin menu.
	 */
	public $menu_position = null;

	/**
	 * @var $template string the name of the template that used for showing settings page.
	 */
	public $template = 'default';

	/**
	 * @var $show_backup_restore_section bool whether you want to show transfer settings.
	 * section in your settings page
	 *
	 * if set to true, a section named 'transfer-settings' will be reserved and you should
	 * not use this name in your sections names.
	 */
	public $show_transfer_settings_section = true;
}