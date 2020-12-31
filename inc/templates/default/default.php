<?php

namespace MSPFramework;

include_once __DIR__ . '/transfer-settings-section.php';

class Template_default extends Template {
	public function __construct( $settings_page ) {
		parent::__construct( $settings_page );

		add_action( "MSPFramework/$settings_page->option_name/init/class", function () {
			$config = $this->settings_page->config;
			if ( $config->show_transfer_settings_section ) {
				$this->settings_page->sections[ 'update-restore' ] = new TransferSettingsSection( $this->settings_page );
			}
		} );
	}

	public function enqueue_assets() {
		$template_path = Utilities::get_dir_path( __DIR__ );
		$template_url  = Utilities::get_dir_url( __DIR__ );

        $min = $this->settings_page->config->debug_mod ? '' : '.min';

		wp_enqueue_style(
			'msp-default-template',
			$template_url . "default$min.css",
			array(),
			md5( filectime( $template_path . "default$min.css" ) )
		);
		wp_enqueue_script(
			'msp-default-template',
			$template_url . "default$min.js",
			array( 'msp-jquery', 'msp' ),
			md5( filectime( $template_path . "default$min.js" ) ),
			true
		);
	}

	public function render() {
		$settingsPage = $this->settings_page;
		$config       = $settingsPage->config; ?>
        <div id="msp"
		     <?php if ( is_rtl() ) { ?>class="rtl"<?php } ?>
             data-msp-option-name="<?php echo esc_attr( $settingsPage->option_name ); ?>"
             data-msp-version="<?php echo esc_attr( $settingsPage->version ); ?>">
            <script>
                if ( getCookieValue( 'msp-all-sections-view' ) === 'true' ) $( '#msp' ).addClass( 'sections-all-view' );
            </script>
            <div id="sidebar">
                <div id="logo">
                    <img src="<?php echo esc_url( $config->page_logo_url ); ?>" alt="Logo">
                </div>
                <div id="menu">
                    <div id="menu-hide-button-wrapper">
                        <button id="menu-hide-button"
                                class="mdc-icon-button">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"
                                 class="mdc-icon-button__icon">
                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59
                                     19 19 17.59 13.41 12z"/>
                            </svg>
                        </button>
                    </div>
                    <ul>
						<?php
						foreach ( $settingsPage->sections as $section ) {
							if ( $section instanceof Section ) { ?>
                                <li data-section-id="<?php echo esc_attr( $section->id ); ?>"
                                    data-section-name="<?php echo esc_attr( $section->name ); ?>">
									<?php if ( $section->icon instanceof Icon ) { ?>
                                        <span class="msp-menu-section-icon-title">
	                                        <?php $section->icon->render(); ?>
	                                        <span><?php echo esc_html( $section->name ); ?></span>
	                                    </span>
									<?php } else {
										echo esc_html( $section->name );
									}
									?>
                                </li>
							<?php }
						} ?>
                    </ul>
                </div>
            </div>
            <div id="settings">
                <div id="toolbar">
                    <span id="toolbar-start">
	                    <button id="sections-view-toggle"
                                class="mdc-icon-button"
                                aria-label="<?php
	                            esc_attr_e( 'Show all sections', 'msp-framework' ); ?>"
                                data-aria-label-on="<?php
	                            esc_attr_e( 'Show Separated sections', 'msp-framework' ); ?>"
                                data-aria-label-off="<?php
	                            esc_attr_e( 'Show all sections', 'msp-framework' ); ?>">
		                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"
                                 class="mdc-icon-button__icon mdc-icon-button__icon--on">
			                    <path d="M21,2H3L2,3v18l1,1h18l1-1V3L21,2z M4,14v-4V8h11v8H4V14z M20,19l-1,1H5l-1-1v-1h
			                    16V19z M20,10v4v2h-3V8h3V10z M20,6H4V5l1-1h14l1,1V6z"/>
		                    </svg>
		                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"
                                 class="mdc-icon-button__icon">
			                    <path d="M21,2H3L2,3v18l1,1h18l1-1V3L21,2z M5,4h14l1,1v1H4V5L5,4z M20,14v2H4v-2v-4V8h16
			                    v2V14z M19,20H5l-1-1v-1h16v1L19,20z"/>
		                    </svg>
	                    </button>
                        <script>
                            sectionsViewToggle = $( '#sections-view-toggle' ).get( 0 );
                            sectionsViewToggle = new mdc.iconButton.MDCIconButtonToggle( sectionsViewToggle );
                            if ( getCookieValue( 'msp-all-sections-view' ) === 'true' ) sectionsViewToggle.on = true;
                        </script>
	                    <button id="menu-show-button"
                                class="mdc-icon-button">
		                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"
                                 class="mdc-icon-button__icon">
			                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
		                    </svg>
	                    </button>
                    </span>
                    <span id="toolbar-end">
		                <button class="mdc-button mdc-button--unelevated msp-save-button">
		                    <span class="mdc-button__ripple"></span>
							<?php esc_attr_e( 'Save Changes', 'msp-framework' ); ?>
		                </button>
		                <button class="mdc-button mdc-button--outlined msp-reset-section-button">
		                    <span class="mdc-button__ripple"></span>
							<?php esc_attr_e( 'Reset This Section', 'msp-framework' ); ?>
		                </button>
		                <button class="mdc-button mdc-button--outlined msp-reset-all-button">
		                    <span class="mdc-button__ripple"></span>
							<?php esc_attr_e( 'Reset All Sections', 'msp-framework' ); ?>
		                </button>
                    </span>
                </div>
                <div id="settings-options">
                    <div class="mobile-buttons">
                        <button class="mdc-button mdc-button--unelevated msp-save-button">
                            <span class="mdc-button__ripple"></span>
							<?php esc_attr_e( 'Save Changes', 'msp-framework' ); ?>
                        </button>
                        <button class="mdc-button mdc-button--outlined msp-reset-section-button">
                            <span class="mdc-button__ripple"></span>
							<?php esc_attr_e( 'Reset This Section', 'msp-framework' ); ?>
                        </button>
                        <button class="mdc-button mdc-button--outlined msp-reset-all-button">
                            <span class="mdc-button__ripple"></span>
							<?php esc_attr_e( 'Reset All Sections', 'msp-framework' ); ?>
                        </button>
                    </div>
					<?php foreach ( $settingsPage->sections as $section ) {
						if ( $section instanceof Section ) { ?>
                            <section data-section-id="<?php echo esc_attr( $section->id ); ?>">
								<?php if ( is_string( $section->title ) ) { ?>
                                    <h2><?php echo esc_html( $section->title ); ?></h2>
								<?php }
								if ( is_string( $section->subtitle ) ) { ?>
                                    <p><?php echo esc_html( $section->subtitle ); ?></p>
								<?php }
								$section->render(); ?>
                            </section>
						<?php }
					} ?>
                    <div class="mobile-buttons">
                        <button class="mdc-button mdc-button--unelevated msp-save-button">
                            <span class="mdc-button__ripple"></span>
							<?php esc_attr_e( 'Save Changes', 'msp-framework' ); ?>
                        </button>
                        <button class="mdc-button mdc-button--outlined msp-reset-section-button">
                            <span class="mdc-button__ripple"></span>
							<?php esc_attr_e( 'Reset This Section', 'msp-framework' ); ?>
                        </button>
                        <button class="mdc-button mdc-button--outlined msp-reset-all-button">
                            <span class="mdc-button__ripple"></span>
							<?php esc_attr_e( 'Reset All Sections', 'msp-framework' ); ?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mdc-snackbar" id="save-notice">
                <div class="mdc-snackbar__surface">
                    <div class="mdc-snackbar__label"
                         role="status"
                         aria-live="polite">
						<?php esc_html_e( 'Settings have changed, you must save them!', 'msp-framework' ); ?>
                    </div>
                    <div class="mdc-snackbar__actions">
                        <button type="button" class="mdc-button mdc-snackbar__action" id="save-notice-hide-button">
                            <span class="mdc-button__ripple"></span>
                            <span class="mdc-button__label"><?php
								esc_html_e( 'Hide', 'msp-framework' );
								?></span>
                        </button>
                        <button type="button" class="mdc-button mdc-snackbar__action" id="save-notice-save-button">
                            <span class="mdc-button__ripple"></span>
                            <span class="mdc-button__label"><?php
								esc_html_e( 'Save Changes', 'msp-framework' );
								?></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mdc-snackbar" id="save-message">
                <div class="mdc-snackbar__surface">
                    <div class="mdc-snackbar__label"
                         role="status"
                         aria-live="polite"></div>
                </div>
            </div>
            <div class="mdc-dialog" id="save-validation-messages">
                <div class="mdc-dialog__container">
                    <div class="mdc-dialog__surface"
                         role="alertdialog"
                         aria-modal="true"
                         aria-labelledby="save-validation-messages-title"
                         aria-describedby="save-validation-messages-content">
                        <!-- Title cannot contain leading whitespace due to mdc-typography-baseline-top() -->
                        <h2 class="mdc-dialog__title" id="save-validation-messages-title"><?php
							esc_html_e( 'Messages', 'msp-framework' );
							?></h2>
                        <div class="mdc-dialog__content" id="save-validation-messages-content">
                            <ol></ol>
                        </div>
                        <div class="mdc-dialog__actions">
                            <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="close">
                                <span class="mdc-button__ripple"></span>
                                <span class="mdc-button__label"><?php
									esc_html_e( 'OK', 'msp-framework' );
									?></span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mdc-dialog__scrim"></div>
            </div>
        </div>
		<?php
	}
}