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
	 * Render setting page main html code
	 *
	 * You should not override any thing here and instead override
	 * render_content function and you should only override
	 * this function if there is important thing to change
	 */
	public function render_html() {
		$config = $this->settings_page->config; ?>
        <!DOCTYPE html>
        <!--suppress HtmlRequiredLangAttribute -->
        <html <?php language_attributes(); ?>>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title><?php echo esc_html( $config->page_title ); ?></title>
			<?php
			do_action( 'admin_enqueue_scripts' );
			wp_print_head_scripts();
			do_action( 'admin_print_styles' );
			do_action( 'admin_head' );
			do_action( "MSPFramework/{$this->settings_page->option_name}/head" );
			?>
        </head>
        <body <?php body_class(); ?>>
		<?php
		wp_print_media_templates();
		wp_print_footer_scripts();
		do_action( "MSPFramework/{$this->settings_page->option_name}/footer" );
		?>
        </body>
        </html>
		<?php
	}

	/**
	 * Render settings page template content
	 */
	public function render() {
	}
}