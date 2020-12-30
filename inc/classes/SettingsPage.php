<?php


namespace MSPFramework;


/**
 * Class SettingsPage
 *
 * This class create settings page for wordpress themes/plugins
 *
 * @package MSPFramework
 */
class SettingsPage {
	/**
	 * @var array array of name pair class name of available field types
	 */
	public $registered_field_types = array();
	/**
	 * @var array array of name pair class name of used field types
	 */
	public $used_field_types = array();
	/**
	 * @var array array of name pair class name of available templates
	 */
	public $available_template = array();
	/**
	 * @var Template the template instance that used to show settings page
	 */
	public $template;
	/**
	 * @var Config Configurations for this settings page
	 */
	public $config;
	/**
	 * @var array array of section pair it's array of fields
	 */
	public $sections = array();
	/**
	 * @var array array of fields by it's names
	 */
	public $fields = array();
	/**
	 * @var string the name of option the settings stored on it
	 */
	public $option_name;
	/**
	 * @var int the version of the settings
	 */
	public $version;
	/**
	 * @var array the fields values array
	 */
	public $saved_values = array();
	/**
	 * @var string the path of the framework in the file system
	 */
	public $framework_path;
	/**
	 * @var string the url of the framework directory
	 */
	public $framework_url;

	/**
	 * SettingsPage constructor.
	 *
	 * @param Config $config settings page config
	 * @param string $option_name the name of option the settings stored on it
	 * @param int $version the version of the settings increase only if there are changes in fields or sections
	 */
	public function __construct( $option_name, $version, $config ) {
		$this->framework_path = Utilities::get_dir_path( __DIR__ . '/../../' );
		$this->framework_url = Utilities::get_dir_url( __DIR__ . '/../../' );

		$this->option_name = $option_name;
		$this->version     = $version;

		if ( ! ( $config instanceof Config ) ) {
			$config = new Config();
		}
		$this->config = $config;

		$this->load_text_domain();
		$this->load_field_types();
		$this->load_saved_values();
		$this->load_templates();

		if ( isset( $this->available_template[ $config->template ] ) ) {
			$this->template = new $this->available_template[ $config->template ]( $this );
		} else {
			$this->template = new Template( $this );
		}
	}

	/**
	 * load msp-framework textdomain from languages directory
	 */
	private function load_text_domain() {
		if ( is_textdomain_loaded( 'msp-framework' ) ) {
			return;
		}

		$basename = basename( $this->framework_path );
		$basepath = plugin_basename( $this->framework_path );
		$basepath = str_replace( $basename, '', $basepath );

		$basepath = apply_filters( "MSPFramework/$this->option_name/textdomain/basepath", $basepath );

		$loaded = load_plugin_textdomain(
			'msp-framework',
			false,
			$basepath . 'languages'
		);

		if ( ! $loaded ) {
			$loaded = load_muplugin_textdomain( 'msp-framework', $basepath . 'languages' );
		}

		if ( ! $loaded ) {
			$loaded = load_theme_textdomain( 'msp-framework', $basepath . 'languages' );
		}

		if ( ! $loaded ) {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'msp-framework' );
			$mofile = $this->framework_path . 'languages/msp-framework-' . $locale . '.mo';
			load_textdomain( 'msp-framework', $mofile );
		}
	}

	/**
	 * load available field types from 'fields' folder
	 * @noinspection PhpIncludeInspection
	 */
	private function load_field_types() {
		foreach ( scandir( $this->framework_path . 'inc/fields/' ) as $field ) {
			$field_path = "{$this->framework_path}inc/fields/$field/$field.php";
			if ( $field !== '.' && $field !== '..' && $field_path ) {
				include_once $field_path;
				$class_name = '\MSPFramework\Field_' . $field;
				if ( class_exists( $class_name ) && is_subclass_of( $class_name, '\MSPFramework\Field' ) ) {
					$this->registered_field_types[ $field ] = $class_name;
				}
			}
		}
	}

	/**
	 * load fields saved values from database to 'SettingsPage' instance
	 */
	private function load_saved_values() {
		if ( ! empty( $this->option_name ) ) {
			$values = get_option( $this->option_name );
			if ( is_array( $values ) ) {
				$this->saved_values = $values;
			}
		}
	}

	/**
	 * load available templates from 'templates' folder
	 */
	private function load_templates() {
		foreach ( scandir( $this->framework_path . 'inc/templates/' ) as $template ) {
			$template_path = "{$this->framework_path}inc/templates/$template/$template.php";
			if ( $template !== '.' && $template !== '..' && file_exists( $template_path ) ) {
				/** @noinspection PhpIncludeInspection */
				include_once $template_path;
				$class_name = '\MSPFramework\Template_' . $template;
				if ( class_exists( $class_name ) && is_subclass_of( $class_name, '\MSPFramework\Template' ) ) {
					$this->available_template[ $template ] = $class_name;
				}
			}
		}
	}

	/**
	 * get fields values from database
	 *
	 * @return array return the values array
	 */
	public function get_saved_values() {
		$values = array();
		foreach ( $this->sections as $section ) {
			if ( $section instanceof FieldsSection ) {
				foreach ( $section->fields as $field ) {
					if ( $field instanceof Field ) {
						if ( isset( $this->saved_values[ $field->id ] ) ) {
							$values[ $field->id ] = $this->saved_values[ $field->id ];
						} else {
							$values[ $field->id ] = $field->default_value;
						}
					}
				}
			}
		}

		return $values;
	}

	/**
	 * convert array to 'FieldSection' array and add it to the settings page
	 *
	 * @param array $sections the section array
	 */
	public function add_fields_sections_array( $sections ) {
		foreach ( $sections as $section ) {
			$this->add_fields_section_array( $section );
		}
	}

	/**
	 * convert array to 'FieldSection' and add it to the settings page
	 *
	 * @param array $section_array the section array
	 */
	public function add_fields_section_array( $section_array = array() ) {
		if ( isset( $section_array[ 'id' ] ) && is_string( $section_array[ 'id' ] ) &&
		     isset( $section_array[ 'name' ] ) && is_string( $section_array[ 'name' ] ) ) {
			$section = new FieldsSection( $this, $section_array[ 'id' ], $section_array[ 'name' ] );
			if ( isset( $section_array[ 'icon' ] ) && $section_array[ 'icon' ] instanceof Icon ) {
				$section->icon = $section_array[ 'icon' ];
			}
			if ( isset( $section_array[ 'title' ] ) && is_string( $section_array[ 'title' ] ) ) {
				$section->title = $section_array[ 'title' ];
			}
			if ( isset( $section_array[ 'subtitle' ] ) && is_string( $section_array[ 'subtitle' ] ) ) {
				$section->subtitle = $section_array[ 'subtitle' ];
			}

			$this->add_section( $section );

			if ( isset( $section_array[ 'fields' ] ) && is_array( $section_array[ 'fields' ] ) ) {
				$fields = $section_array[ 'fields' ];
				foreach ( $fields as $field_name => $field ) {
					if ( is_array( $field ) ) {
						$fields[ $field_name ][ 'section_id' ] = $section->id;
					}
				}
				$this->add_fields_array( $fields );
			}
		}
	}

	/**
	 * convert section to the settings page
	 *
	 * @param Section $section the section array
	 */
	public function add_section( $section ) {
		$this->sections[ $section->id ] = $section;
	}

	/**
	 * convert array to fields array and add it to the settings page
	 *
	 * @param array $fields the field array
	 */
	public function add_fields_array( $fields ) {
		foreach ( $fields as $field ) {
			$this->add_field_array( $field );
		}
	}

	/**
	 * convert array to field and add it to the settings page
	 *
	 * @param array $field the field array
	 */
	public function add_field_array( $field = array() ) {
		if ( isset( $field[ 'section_id' ] ) && isset( $field[ 'type' ] ) && isset( $field[ 'id' ] ) &&
		     isset( $this->registered_field_types[ $field[ 'type' ] ] ) ) {
			$field = wp_parse_args( $field, array(
				'title'         => '',
				'subtitle'      => '',
				'options'       => array(),
				'default_value' => false
			) );

			if ( isset( $this->saved_values[ $field[ 'id' ] ] ) ) {
				$field[ 'value' ] = $this->saved_values[ $field[ 'id' ] ];
			} else {
				unset( $field[ 'value' ] );
			}

			if ( isset( $this->registered_field_types[ $field[ 'type' ] ] ) ) {
				$field_class_name = $this->registered_field_types[ $field[ 'type' ] ];
				$this->add_field( new $field_class_name(
					$this,
					$field[ 'type' ],
					$field[ 'id' ],
					$field[ 'section_id' ],
					$field[ 'title' ],
					$field[ 'subtitle' ],
					$field[ 'options' ],
					$field[ 'default_value' ],
					isset( $field[ 'value' ] ) ? $field[ 'value' ] : $field[ 'default_value' ]
				) );
			}
		}
	}

	/**
	 * add field to the settings page
	 *
	 * @param Field $field the field
	 */
	public function add_field( Field $field ) {
		// add field type of the current field to used field type if it is not set
		if ( ! isset( $this->used_field_types[ $field->type ] ) ) {
			$this->used_field_types[ $field->type ] = $this->registered_field_types[ $field->type ];
		}

		if ( isset( $this->sections[ $field->section_id ] ) ) {
			$this->sections[ $field->section_id ]->fields[ $field->id ] = $field;
			$this->fields[ $field->id ]                                 = $field;
		}
	}

	/**
	 * Register settings page to the admin menu
	 *
	 * @return bool Whether settings page registered
	 */
	public function register_page() {
		$config = $this->config;
		switch ( $config->menu_type ) {
			case 'menu':
				add_menu_page(
					$config->page_title,
					$config->menu_title,
					$config->page_capability,
					$config->page_slug,
					array( $this->template, 'render' ),
					$config->menu_icon_url,
					$config->menu_position
				);

				return true;
			case 'submenu':
				add_submenu_page(
					$config->menu_parent_slug,
					$config->page_title,
					$config->menu_title,
					$config->page_capability,
					$config->page_slug,
					array( $this->template, 'render' ),
					$config->menu_position
				);

				return true;
		}

		return false;
	}

	/**
	 * Enqueues the assets needed for the settings page.
	 */
	public function enqueue_assets() {
		wp_deregister_style( 'dashicons' );
		wp_deregister_style( 'admin-bar' );
		wp_deregister_style( 'forms' );
		wp_deregister_style( 'admin-menu' );
		wp_deregister_style( 'dashboard' );
		wp_deregister_style( 'list-tables' );
		wp_deregister_style( 'edit' );
		wp_deregister_style( 'revisions' );
		wp_deregister_style( 'themes' );
		wp_deregister_style( 'about' );
		wp_deregister_style( 'nav-menus' );
		wp_deregister_style( 'wp-pointer' );
		wp_deregister_style( 'widgets');

		wp_enqueue_media();

		$min = $this->config->debug_mod ? '.min' : '';

		wp_enqueue_script(
			'msp-jquery',
			$this->framework_url . "assets/js/jquery-3.5.1$min.js"
		);

		wp_enqueue_style(
			'font-awesome',
			$this->framework_url . "assets/icons/font-awesome/css/all$min.css"
		);
		wp_enqueue_style(
			'material-icons',
			$this->framework_url . "assets/icons/material-icons/material-icons$min.css"
		);

		wp_enqueue_script(
			'msp-download-js',
			$this->framework_url . "assets/js/download$min.js"
		);
		wp_enqueue_style(
			'mdc',
			$this->framework_url . "assets/css/material-components-web$min.css"
		);
		wp_enqueue_script(
			'mdc',
			$this->framework_url . "assets/js/material-components-web$min.js"
		);
		wp_enqueue_style(
			'msp',
			$this->framework_url . "assets/css/msp$min.css"
		);
		wp_enqueue_script(
			'msp',
			$this->framework_url . "assets/js/msp$min.js",
			array( 'msp-jquery', 'mdc', 'msp-download-js' )
		);
		wp_localize_script( 'msp', 'msp_strings', array(
			'ajax_url'             => admin_url( 'admin-ajax.php' ),
			'saving'               => __( 'Saving ...', 'msp-framework' ),
			'unexpected-error'     => __( 'Unexpected error.', 'msp-framework' ),
			'connection-timeout'   => __( 'connection timeout.', 'msp-framework' ),
			'save-alert'           => __(
				"Your changes have not been saved\nAre you sure you want to leave?", 'msp-framework' ),
			'backup-not-valid'     => __( 'The backup data is not valid.', 'msp-framework' ),
			'other-setting-backup' => __(
				'This backup is from other settings and it\'s not compatible with this settings.', 'msp-framework' ),
			'success-restore'      => __(
				'Your backup successfully restored.', 'msp-framework' ),
		) );

		$this->template->enqueue_assets();

		foreach ( $this->sections as $section ) {
		    if ($section instanceof Section)
			$section::enqueue_assets( $this );
		}

		foreach ( $this->used_field_types as $field_type ) {
		    if ($field_type instanceof Field)
			$field_type::enqueue_assets( $this );
		}

		do_action( "MSPFramework/{$this->option_name}/enqueue_assets" );
	}

	/**
	 * Initialize settings page after setting up configurations and register fields
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'register_page' ) );
		add_action( "wp_ajax_msp-framework-$this->option_name-set-values", array( $this, 'ajax_set_values' ) );
		add_action( "wp_ajax_nopriv_msp-framework-$this->option_name-set-values", array( $this, 'ajax_set_values' ) );

		do_action( "MSPFramework/{$this->option_name}/init/class" );

		if ( $this->is_settings_page()) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

			add_action( 'admin_head', function () {
				do_action( "MSPFramework/{$this->option_name}/head" );
			} );

			add_action( 'admin_footer', function () {
				do_action( "MSPFramework/{$this->option_name}/footer" );
			} );

			do_action( "MSPFramework/{$this->option_name}/init/page" );
		}
	}

	/**
	 * Check whether this page is settings page
	 *
	 * @return bool Whether this page is settings page
	 */
	public function is_settings_page() {
		return ( filter_input( INPUT_GET, 'page' ) === $this->config->page_slug );
	}

	/**
	 * set given ajax field values to wp option
	 */
	public function ajax_set_values() {
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( __( 'You are not logged in, please login and try again.', 'msp-framework' ) );
		}
		if ( ! current_user_can( $this->config->page_capability ) ) {
			wp_send_json_error(
				array( 'message' => __( 'You do not have permission to modify these options.', 'msp-framework' ) )
			);
		}
		$received_values = null;
		$request         = $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ? $_POST : $_GET;
		if ( isset( $request[ 'values' ] ) ) {
			$received_values = json_decode( stripcslashes( $request[ 'values' ] ), true );
		}
		if ( $received_values == null ) {
			wp_send_json_error(
				array( 'message' => __( 'The values have not been sent for save.', 'msp-framework' ) )
			);
		}
		$values          = array();
		$response_values = array();
		foreach ( $received_values as $field_name => $value ) {
			if ( isset( $this->fields[ $field_name ] ) ) {
				$field = $this->fields[ $field_name ];
				if ( $field instanceof Field and
                    $field instanceof ValidatableField and $field->hasValidator() ) {
					$response_values[ $field_name ] = $field->validate( $value );
					if ( ! isset( $response_values[ $field_name ][ 'value' ] ) ) {
						$response_values[ $field_name ][ 'value' ] = $value;
					}
					$response_values[ $field_name ][ 'title' ]   = $field->title;
					$response_values[ $field_name ][ 'section' ] = $this->sections[ $field->section_id ]->name;
					$values[ $field_name ]                       = $response_values[ $field_name ][ 'value' ];
				} else {
					$response_values[ $field_name ][ 'value' ] = $value;
					$values[ $field_name ]                     = $value;
				}
			}
		}
		$this->save_values( $values );
		wp_send_json_success(
			array(
				'message' => __( 'Your changes have been saved successfully.', 'msp-framework' ),
				'values'  => $response_values
			)
		);
	}

	/**
	 * Set given fields values to wp option
	 *
	 * @param array $saved_values the values to set
	 */
	public function save_values( $saved_values ) {
		update_option( $this->option_name, $saved_values );
	}
}