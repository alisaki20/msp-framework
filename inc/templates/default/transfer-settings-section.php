<?php


namespace MSPFramework;


class TransferSettingsSection extends Section {
	public function __construct( $settings_page ) {
		parent::__construct(
			$settings_page,
			'transfer-settings',
			__( 'Transferring settings', 'msp-framework' )
		);
		$this->icon     = new Icon(
			'svg',
			'<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">' .
			'<path d="M19 8l-4 4h3c0 3.31-2.69 6-6 6-1.01 0-1.97-.25-2.8-.7l-1.46 1.46C8.97 19.54 10.43 20 12 20c4.42' .
			' 0 8-3.58 8-8h3l-4-4zM6 12c0-3.31 2.69-6 6-6 1.01 0 1.97.25 2.8.7l1.46-1.46C15.03 4.46 13.57 4 12 4c-4.4' .
			'2 0-8 3.58-8 8H1l4 4 4-4H6z"/></svg>'
		);
		$this->title    = __( 'Transferring settings', 'msp-framework' );
		$this->subtitle = __( 'In this section you can transferring your settings to other site by getting backup' .
		                      ' file or copy data to clipboard', 'msp-framework' );
	}

    public static function enqueue_assets( $settings_page )
    {
        $template_path = Utilities::get_dir_path( __DIR__ );
        $template_url = Utilities::get_dir_url( __DIR__ );

        $min = $settings_page->config->debug_mod ? '' : '.min';

		wp_enqueue_style(
			'msp-default-template-transfer-settings-section',
			$template_url . "transfer-settings-section$min.css",
			array(),
			md5( filectime( $template_path . "transfer-settings-section$min.css" ) )
		);
		wp_enqueue_script(
			'msp-default-template-transfer-settings-section',
			$template_url . "transfer-settings-section$min.js",
			array( 'msp-jquery', 'msp', 'msp-default-template' ),
			md5( filectime( $template_path . "transfer-settings-section$min.js" ) ),
			true
		);
	}

	public function render() { ?>
        <h4><?php esc_html_e( 'Restore Settings', 'msp-framework' ); ?></h4>
        <p><?php esc_html_e( 'Here you can select the backup file to import and apply the settings to your site.',
				'msp-framework' ); ?></p>
        <p class="msp-restore-buttons">
            <input type="file" id="msp-restore-from-file-input" hidden="hidden">
            <button class="mdc-button mdc-button--unelevated" id="msp-restore-from-file-button">
                <span class="mdc-button__ripple"></span>
                <span class="mdc-button__label"><?php
					esc_html_e( 'Restore from file', 'msp-framework' ); ?></span>
            </button>
            <button class="mdc-button mdc-button--outlined" id="msp-restore-from-clipboard-button">
                <span class="mdc-button__ripple"></span>
                <span class="mdc-button__label"><?php
					esc_html_e( 'Restore from clipboard', 'msp-framework' ); ?></span>
            </button>
        </p>
        <div id="msp-restore-from-clipboard-box" class="msp-clipboard-box-hidden">
            <p>
                <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea
                mdc-text-field--fullwidth"
                       id="msp-restore-from-clipboard-json-textarea">
					<span class="mdc-text-field__resizer">
						<textarea class="mdc-text-field__input" aria-labelledby="msp-restore-textarea-label"
                                  rows="6"></textarea>
					</span>
                    <span class="mdc-notched-outline">
						<span class="mdc-notched-outline__leading"></span>
						<span class="mdc-notched-outline__notch">
							<span class="mdc-floating-label" id="msp-restore-textarea-label"><?php
								esc_html_e( 'Backup data', 'msp-framework' ); ?></span>
						</span>
						<span class="mdc-notched-outline__trailing"></span>
					</span>
                </label>
            </p>
            <p>
                <button class="mdc-button mdc-button--outlined" id="msp-restore-from-clipboard-restore-button">
                    <span class="mdc-button__ripple"></span>
                    <span class="mdc-button__label"><?php
						esc_html_e( 'Restore', 'msp-framework' ); ?></span>
                </button>
            </p>
        </div>
        <br>
        <h4><?php esc_html_e( 'Backup Settings', 'msp-framework' ); ?></h4>
        <p><?php esc_html_e( 'Here you can download the backup file of the last applied settings and import it' .
		                     ' to your other site from the settings restore section.', 'msp-framework' ); ?></p>
        <p class="msp-backup-buttons">
            <button class="mdc-button mdc-button--unelevated" id="msp-backup-to-file-button">
                <span class="mdc-button__ripple"></span>
                <span class="mdc-button__label"><?php
					esc_html_e( 'Create backup file', 'msp-framework' ); ?></span>
            </button>
            <button class="mdc-button mdc-button--outlined" id="msp-backup-to-clipboard-button">
                <span class="mdc-button__ripple"></span>
                <span class="mdc-button__label"><?php
					esc_html_e( 'Copy backup data', 'msp-framework' ); ?></span>
            </button>
        </p>
        <div id="msp-backup-to-clipboard-box" class="msp-clipboard-box-hidden">
            <p>
                <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea
                mdc-text-field--fullwidth"
                       id="msp-backup-to-clipboard-json-textarea">
					<span class="mdc-text-field__resizer">
						<textarea class="mdc-text-field__input" aria-labelledby="msp-backup-textarea-label"
                                  rows="6"></textarea>
					</span>
                    <span class="mdc-notched-outline">
						<span class="mdc-notched-outline__leading"></span>
						<span class="mdc-notched-outline__notch">
							<span class="mdc-floating-label" id="msp-backup-textarea-label"><?php
								esc_html_e( 'Backup data', 'msp-framework' ); ?></span>
						</span>
						<span class="mdc-notched-outline__trailing"></span>
					</span>
                </label>
            </p>
            <p>
                <button class="mdc-button mdc-button--outlined" id="msp-backup-to-clipboard-copy-button">
                    <span class="mdc-button__ripple"></span>
                    <span class="mdc-button__label"><?php
						esc_html_e( 'Copy backup data', 'msp-framework' ); ?></span>
                </button>
            </p>
        </div>

        <div class="mdc-snackbar" id="restore-message">
            <div class="mdc-snackbar__surface">
                <div class="mdc-snackbar__label"
                     role="status"
                     aria-live="polite"></div>
            </div>
        </div>
        <div class="mdc-dialog" id="restore-version-warning-dialog">
            <div class="mdc-dialog__container">
                <div class="mdc-dialog__surface"
                     role="alertdialog"
                     aria-modal="true"
                     aria-describedby="restore-version-warning-dialog-content">
                    <div class="mdc-dialog__content" id="restore-version-warning-dialog-content"><?php
						esc_html_e(
							'This is a backup of various settings versions and may not be compatible with'
							. ' this version.',
							'msp-framework'
						); ?><br><?php
						esc_html_e(
							'Do you want to Restore anyway?',
							'msp-framework'
						);
						?></div>
                    <div class="mdc-dialog__actions">
                        <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="close">
                            <span class="mdc-button__ripple"></span>
                            <span class="mdc-button__label"><?php
								esc_html_e( 'Cancel', 'msp-framework' );
								?></span>
                        </button>
                        <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="restore"
                                data-mdc-dialog-button-default data-mdc-dialog-initial-focus>
                            <span class="mdc-button__ripple"></span>
                            <span class="mdc-button__label"><?php
								esc_html_e( 'Restore', 'msp-framework' );
								?></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mdc-dialog__scrim"></div>
        </div>
        <div class="mdc-dialog" id="restore-dialog">
            <div class="mdc-dialog__container">
                <div class="mdc-dialog__surface"
                     role="alertdialog"
                     aria-modal="true"
                     aria-labelledby="restore-dialog-title"
                     aria-describedby="restore-dialog-content">
                    <!-- Title cannot contain leading whitespace due to mdc-typography-baseline-top() -->
                    <h2 class="mdc-dialog__title" id="restore-dialog-title"><?php
						esc_html_e( 'Please select the sections you want to restore', 'msp-framework' );
						?></h2>
                    <div class="mdc-dialog__content" id="restore-dialog-content">
                        <div class="mdc-data-table" id="restore-dialog-sections-list" style="width: 100%;">
                            <div class="mdc-data-table__table-container">
                                <table class="mdc-data-table__table">
                                    <thead>
                                    <tr class="mdc-data-table__header-row">
                                        <th class="mdc-data-table__header-cell
                                         mdc-data-table__header-cell--checkbox"
                                            role="columnheader" scope="col">
                                            <div class="mdc-checkbox mdc-data-table__header-row-checkbox
                                             mdc-checkbox--selected">
                                                <input type="checkbox" class="mdc-checkbox__native-control"
                                                       checked="checked"
                                                       aria-label="<?php
												       esc_attr_e( 'Toggle all sections', 'msp-framework' );
												       ?>"/>
                                                <div class="mdc-checkbox__background">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                                                        <path class="mdc-checkbox__checkmark-path" fill="none"
                                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                                    </svg>
                                                    <div class="mdc-checkbox__mixedmark"></div>
                                                </div>
                                                <div class="mdc-checkbox__ripple"></div>
                                            </div>
                                        </th>
                                        <th class="mdc-data-table__header-cell" role="columnheader" scope="col">
											<?php esc_html_e( 'Section Name', 'msp-framework' ); ?>
                                        </th>
                                        <th class="mdc-data-table__header-cell
                                             mdc-data-table__header-cell--numeric" role="columnheader" scope="col">
											<?php esc_html_e( 'Fields Count', 'msp-framework' ); ?>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="mdc-data-table__content">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-dialog__actions">
                        <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="close">
                            <span class="mdc-button__ripple"></span>
                            <span class="mdc-button__label"><?php
								esc_html_e( 'Cancel', 'msp-framework' );
								?></span>
                        </button>
                        <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="restore"
                                data-mdc-dialog-button-default data-mdc-dialog-initial-focus>
                            <span class="mdc-button__ripple"></span>
                            <span class="mdc-button__label"><?php
								esc_html_e( 'Restore', 'msp-framework' );
								?></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mdc-dialog__scrim"></div>
        </div>
        <div class="mdc-dialog" id="backup-dialog">
            <div class="mdc-dialog__container">
                <div class="mdc-dialog__surface"
                     role="alertdialog"
                     aria-modal="true"
                     aria-labelledby="backup-dialog-title"
                     aria-describedby="backup-dialog-content">
                    <!-- Title cannot contain leading whitespace due to mdc-typography-baseline-top() -->
                    <h2 class="mdc-dialog__title" id="backup-dialog-title"><?php esc_html( trim(
							_e( 'Please select the sections you want to back up', 'msp-framework' )
						) ); ?></h2>
                    <div class="mdc-dialog__content" id="backup-dialog-content">
                        <div class="mdc-data-table" id="backup-dialog-sections-list" style="width: 100%;">
                            <div class="mdc-data-table__table-container">
                                <table class="mdc-data-table__table">
                                    <thead>
                                    <tr class="mdc-data-table__header-row">
                                        <th class="mdc-data-table__header-cell
                                         mdc-data-table__header-cell--checkbox"
                                            role="columnheader" scope="col">
                                            <div class="mdc-checkbox mdc-data-table__header-row-checkbox
                                             mdc-checkbox--selected">
                                                <input type="checkbox" class="mdc-checkbox__native-control"
                                                       checked="checked"
                                                       aria-label="<?php
												       esc_attr_e( 'Toggle all sections', 'msp-framework' );
												       ?>"/>
                                                <div class="mdc-checkbox__background">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                                                        <path class="mdc-checkbox__checkmark-path" fill="none"
                                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                                    </svg>
                                                    <div class="mdc-checkbox__mixedmark"></div>
                                                </div>
                                                <div class="mdc-checkbox__ripple"></div>
                                            </div>
                                        </th>
                                        <th class="mdc-data-table__header-cell" role="columnheader" scope="col">
											<?php esc_html_e( 'Section Name', 'msp-framework' ); ?>
                                        </th>
                                        <th class="mdc-data-table__header-cell
                                             mdc-data-table__header-cell--numeric" role="columnheader" scope="col">
											<?php esc_html_e( 'Fields Count', 'msp-framework' ); ?>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="mdc-data-table__content">
									<?php foreach ( $this->settings_page->sections as $section ) {
										if ( $section instanceof FieldsSection ) {
											$id = trim( "backup-dialog-section-{$section->id}", '-' ); ?>
                                            <tr data-row-id="<?php echo esc_attr( $section->id ); ?>"
                                                class="mdc-data-table__row mdc-data-table__row--selected"
                                                aria-selected="true">
                                                <td class="mdc-data-table__cell mdc-data-table__cell--checkbox">
                                                    <div class="mdc-checkbox mdc-data-table__row-checkbox
                                             mdc-checkbox--selected">
                                                        <!--suppress HtmlFormInputWithoutLabel -->
                                                        <input type="checkbox" class="mdc-checkbox__native-control"
                                                               checked="checked"
                                                               id="<?php echo $id; ?>"
                                                               aria-labelledby="<?php echo $id; ?>-label"/>
                                                        <div class="mdc-checkbox__background">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                                                                <path class="mdc-checkbox__checkmark-path" fill="none"
                                                                      d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                                            </svg>
                                                            <div class="mdc-checkbox__mixedmark"></div>
                                                        </div>
                                                        <div class="mdc-checkbox__ripple"></div>
                                                    </div>
                                                </td>
                                                <th class="mdc-data-table__cell" scope="row"
                                                    id="<?php echo $id; ?>-label"
                                                    for="<?php echo $id; ?>"><?php
													echo esc_html( $section->name ); ?></th>
                                                <td class="mdc-data-table__cell mdc-data-table__cell--numeric"><?php
													echo count( $section->fields ); ?></td>
                                            </tr>
										<?php }
									} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-dialog__actions">
                        <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="close">
                            <span class="mdc-button__ripple"></span>
                            <span class="mdc-button__label"><?php
								esc_html_e( 'Cancel', 'msp-framework' );
								?></span>
                        </button>
                        <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="backup"
                                data-mdc-dialog-button-default data-mdc-dialog-initial-focus>
                            <span class="mdc-button__ripple"></span>
                            <span class="mdc-button__label"><?php
								esc_html_e( 'Back up', 'msp-framework' );
								?></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mdc-dialog__scrim"></div>
        </div>
	<?php }

}