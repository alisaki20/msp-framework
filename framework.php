<?php
/**
 * Plugin Name:       Material Settings Page
 * Description:       Material Settings Page for Themes\Plugins.
 * Version:           1.0
 * Author:            Ali Saki
 * Text Domain:       msp-framework
 * Domain Path:       /languages
 */

namespace MSPFramework;

if ( ! class_exists( '\MSPFramework\Utilities' ) ) {
	require_once __DIR__ . "/inc/classes/Utilities.php";
}
if ( ! class_exists( '\MSPFramework\Icon' ) ) {
	require_once __DIR__ . "/inc/classes/Icon.php";
}
if ( ! class_exists( '\MSPFramework\Field' ) ) {
	require_once __DIR__ . "/inc/classes/Field.php";
}
if ( ! class_exists( '\MSPFramework\Section' ) ) {
	require_once __DIR__ . "/inc/classes/Section.php";
}
if ( ! class_exists( '\MSPFramework\FieldsSection' ) ) {
	require_once __DIR__ . "/inc/classes/FieldsSection.php";
}
if ( ! class_exists( '\MSPFramework\Template' ) ) {
	require_once __DIR__ . "/inc/classes/Template.php";
}
if ( ! class_exists( '\MSPFramework\Validation' ) ) {
	require_once __DIR__ . "/inc/classes/Validation.php";
}
if ( ! class_exists( '\MSPFramework\Config' ) ) {
	require_once __DIR__ . "/inc/classes/Config.php";
}
if ( ! class_exists( '\MSPFramework\SettingsPage' ) ) {
	require_once __DIR__ . "/inc/classes/SettingsPage.php";
}