<?php


namespace MSPFramework;


/**
 * Class SettingsPageField
 *
 * This class contains settings page field data
 *
 * @package MSP
 */
abstract class Field {
    /**
     * @var int the last field number
     */
    static public $last_field_number = 0;
    /**
     * @var SettingsPage the 'SettingsPage' instance
     */
    public $settings_page;
    /**
     * @var string the field type that registered in 'SettingsPage' instance
     */
    public $type;
    /**
     * @var string the field id
     */
    public $id = '';
    /**
     * @var string the section id
     */
    public $section_id = '';
    /**
     * @var string the field title
     */
    public $title = '';
    /**
     * @var string the field subtitle
     */
    public $subtitle = '';
    /**
     * @var array the field options
     */
    public $options = array();
    /**
     * @var bool the field value
     */
    public $value;
    /**
     * @var bool the field default value
     */
    public $default_value;
    /**
     * @var int the field number
     */
    public $number = 0;

    /**
     * SettingsPageField constructor.
     *
     * @param SettingsPage $settings_page
     * @param string $type the field type that registered in 'SettingsPage' instance
     * @param string $id the field id
     * @param string $section_id the field section id
     * @param string $title the field title
     * @param string $subtitle the field subtitle
     * @param array $options the field options
     * @param mixed $default_value the field default value
     * @param mixed $value the field value
     */
    public function __construct( $settings_page, $type, $id, $section_id, $title, $subtitle, array $options, $default_value, $value )
    {
        $this->settings_page = $settings_page;
        $this->type = $type;
        $this->id = $id;
        $this->section_id = $section_id;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->options = $options;
        $this->default_value = $this->check_value( $default_value );
        $this->value = $this->check_value( $value );
        $this->number = ++$this::$last_field_number;
    }

    /**
     * check if the value is valid for this field and if it's not valid return a valid value
     *
     * @param mixed $value
     *
     * @return mixed
     */
    abstract function check_value( $value );

    /**
     * Enqueues the assets needed for the field.
     *
     * this function is called only if this field type is use in one or more fields
     *
     * @param SettingsPage $settings_page
     */
    public static function enqueue_assets( $settings_page )
    {
    }

    /**
     * Constructs name attributes for use in render() fields
     *
     * This function should be used in render() methods to create name attributes for fields.
     *
     * @param string $field_name Field name.
     *
     * @return string Name attribute for $field_name
     */
    public function get_field_name( $field_name = '' )
    {
        if ( is_string( $field_name ) && !empty( $field_name ) ) {
            return "field[$this->id][$this->number][$field_name]";
        } else {
            return "field[$this->id][$this->number]";
        }
    }

    /**
     * Constructs id attributes for use in render() fields.
     *
     * This function should be used in render() methods to create id attributes for fields.
     *
     * @param string $field_name Field name.
     *
     * @return string ID attribute for $field_name.
     */
    public function get_field_id( $field_name )
    {
        return 'field-' . $this->id . '-' . $this->number . '-' . trim( str_replace( array( '[]', '[', ']' ), array( '', '-', '' ), $field_name ), '-' );
    }

    /**
     * render the field html output
     *
     * @return void
     */
    abstract function render();
}