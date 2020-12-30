<?php


namespace MSPFramework;


class FieldsSection extends Section {
	/**
	 * @var array the section fields
	 */
	public $fields = array();

	/**
	 * Render the fields table
	 */
	public function render() {
		?>
        <div class="msp-fields-table">
			<?php foreach ( $this->fields as $field ) {
				if ( $field instanceof Field ) { ?>
                    <div class="field-info">
                        <div class="field-title"><?php echo esc_html( $field->title ); ?></div>
                        <div class="field-subtitle"><?php echo esc_html( $field->subtitle ); ?></div>
                    </div>
                    <div class="field-content">
						<?php $field->render(); ?>
                    </div>
				<?php }
			} ?>
        </div>
		<?php
	}
}