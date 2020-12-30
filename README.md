## MSP-Framework

A Wordpress Theme\Plugin framework For Create Settings Page

## How To Use

#### 1. Download the latest version of this repository and store it in a folder in your theme\plugin folder

for example: git clone https://github.com/alisaki20/msp-framework.git options-framework

#### 2. Add the include code at the top of file:

Go to your main php file (functions.php in themes)

	include_once 'options-framework/framework.php';
	use MSPFramework\Config;
	use MSPFramework\SettingsPage;
	use MSPFramework\Icon;

#### 3. Create config object and set the configurations you want to apply

	$config = new Config();

for example:

	$config->page_slug = 'settings_page';
	$config->page_title = __( 'Example Settings Page', 'text-domain' );
	$config->page_logo_url = get_template_directory_uri() . '/assets/img/logo.svg';
	$config->menu_title = __( 'Settings Page', 'text-domain' );

#### 4. Set option name:

the option name it should be a unique key. all your settings are store in a database using that.

	$option_name = "example_settings_page";

#### 5. Create settings page object:

Now we should create an object that contain all settings page data.

	$settingsPage = new SettingsPage( $option_name, 1, $config );

##### Parameters:

1. The first parameter is the unique option name your chosen that for your settings page.
2. The second parameter is the version of settings pages. You should increase that in every time you are added or delete fields or sections, or you can simply provide the release version of your Plugin\Theme.
3. The third parameter is the configuration object that we created in the previous steps.

#### 6. **Only of you are using default template**: Customize Your Settings Page

The default theme is based on "google material components web" you should customize your settings page color and add some changes.
for example:

	add_action( "MSPFramework/$option_name/head", function () {
		// Set settings page favicon
		$favicon_url = esc_url( get_template_directory_uri() . '/assets/img/favicon' ); ?>
		<link rel="icon" type="image/png" sizes="196x196" href="<?php echo "$favicon_url-192x192.png"; ?>">
		<link rel="icon" type="image/png" sizes="150x150" href="<?php echo "$favicon_url-150x150.png"; ?>">
		<link rel="icon" type="image/png" sizes="96x96" href="<?php echo "$favicon_url-96x96.png"; ?>">
		<link rel="icon" type="image/png" sizes="64x64" href="<?php echo "$favicon_url-64x64.png"; ?>">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo "$favicon_url-32x32.png"; ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo "$favicon_url-16x16.png"; ?>">
		// include font face of the font you want to use in settings page
		// if you are not use custom font you delete this line
		<link rel="stylesheet" type="text/css" href="<?php
			echo esc_url( get_template_directory_uri() . '/assets/font/font-face.css' ); ?>">
		<style>
	        	:root {
				/* set primary and secendary colors */
				--mdc-theme-primary: #2196f3;
				--mdc-theme-secondary: #FFC107;
				/* set font family name
				if you are not use custom font you delete this variable */
				--mdc-typography-font-family: /* Font Family */;
				/* fix letter spacing for some languages
				if you are only support english you can delete this lins */
				--mdc-typography-button-letter-spacing: normal;
				--mdc-typography-headline1-letter-spacing: normal;
				--mdc-typography-headline2-letter-spacing: normal;
				--mdc-typography-headline3-letter-spacing: normal;
				--mdc-typography-headline4-letter-spacing: normal;
				--mdc-typography-headline5-letter-spacing: normal;
				--mdc-typography-headline6-letter-spacing: normal;
			}
		</style>
	<?php } );

#### 7. Add the sections to settings page:

	$settingsPage->add_fields_section_array(array(
		'id' => 'general',
		'name' => __( 'General', 'text-domain' ),
		'title' => __( 'General Settings', 'text-domain' ),
		'subtitle' => __( 'Here you can find general options. These settings apply to the entire plugin\theme.', 'text-domain' ),
		'icon'     => new Icon( 'material-icons', 'home' ),
	))

##### Parameters:

1. the "id" parameter is required. if it's not provided the section will be not registered
2. the "name" parameter is required. it's the name that are should appear in the settings sections menu
3. the "icon" parameter is optional. it's the icon that are should appear  in the settings sections menu
4. the "title" parameter is optional. it's the title that are should appear on the top of fields when the section are selected
5. the "subtitle" parameter is optional. it's the subtitle that are should appear on the top of fields when the section are selected
6. the "fields" parameter is optional. by the parameter you can send an array of fields to set im the section instead of set them separated

##### Note:

you can use "add_fields_section**s**_array" function to set an array of section. every section array should have same parameters as described above.

#### 8. Add the fields to sections:

	add_field_array(array(
		'id' => 'example-switch',
		'type' => 'switch',
		'title' => __( 'Example Switch', 'text-domain' ),
		'subtitle' => __( 'This is a Example Switch', 'text-domain' ),
		'default_value' => false
	));

or

	add_field_array(array(
		'id' => 'example-text',
		'type' => 'text',
		'title' => __( 'Example Text', 'text-domain' ),
		'subtitle' => __( 'Please write example text', 'text-domain' ),
		'default_value' => __( 'Text', 'text-domain' )
	));

##### Parameters:

1. the "section_id" parameter is required. it's the id of the section this field should appear in that.
2. the "id" parameter is required. it is the id of the field. if it's not provided the field will be not registered
3. the "type" parameter is required. it is the type of the field. if it's not provided the field will be not registered
4. the "title" parameter is optional. it's the title that are should appear on the near of field
5. the "subtitle" parameter is optional. it's the subtitle that are should appear on the near of field
6. the "default_value" parameter is optional. this parameter determines the default value of the field
7. the "options" parameter is optional. this parameter is used to send extra options to the field controller

##### Notes:

1. the "section_id" parameter well set automatically if you are setting the fields by section array "fields" parameter
2. you can use "add_field**s**_array" function to set an array of fields. every field array should have same parameters as described above.

#### 9. Initialize settings page

at this point we should call the initialize method of the settings page object to run and render settings page when that is opened.

	$settingsPage->init();

#### 10. Get the options

Now you are done you should use the options on somewhere.
You can use this code to get options:

	$options = $settingsPage->get_saved_values();

## License

This Framework is license under MIT
