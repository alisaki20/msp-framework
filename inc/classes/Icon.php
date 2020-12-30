<?php


namespace MSPFramework;


/**
 * Class Icon
 *
 * this class used for icons in settings page
 *
 * @package MSPFramework
 */
class Icon {
	/**
	 * @var string The type of the icon
	 */
	public $type;
	/**
	 * @var string The code of the icon
	 */
	public $code;
	/**
	 * @var int The icon width
	 */
	public $width = 24;
	/**
	 * @var int The icon height
	 */
	public $height = 24;

	/**
	 * Icon constructor.
	 *
	 * @param $type string The type of the icon, possible types: 'font-awesome', 'material-icons', 'svg'
	 * @param $code string If use 'font-awesome' or 'material-icons' the code is the name of icon
	 *                     or if use 'svg' the code is the svg xml code
	 * @param $height int The icon height
	 * @param $width int The icon width
	 */
	public function __construct( $type, $code, $height = 24, $width = 24 ) {
		$this->type   = $type;
		$this->code   = $code;
		$this->height = $height;
		$this->width  = $width;
	}

	/**
	 * Render icon to HTML
	 * it's should only render one 'i' icon
	 *
	 * @param $classes array Optional, the class you want add
	 */
	public function render( $classes = array() ) {
		switch ( $this->type ) {
			case 'font-awesome':
				$classes[] = $this->code; ?>
                <i style="font-size: <?php echo $this->height ?>px;
                        width: <?php echo $this->width ?>px;
                        user-select: none;"
                   class="<?php echo esc_attr( join( ' ', $classes ) ); ?>"></i>
				<?php break;
			case 'material-icons':
				$classes[] = 'material-icons'; ?>
                <i style="font-size: <?php echo $this->height ?>px;
                        width: <?php echo $this->width ?>px;
                        user-select: none;"
                   class="<?php echo esc_attr( join( ' ', $classes ) ); ?>"><?php
					echo esc_html( $this->code );
					?></i>
				<?php break;
			case 'svg': ?>
                <i style="height: <?php echo $this->height ?>px;
                        width: <?php echo $this->width ?>px;"
                   class="<?php echo esc_attr( join( ' ', $classes ) ); ?>">
					<?php echo $this->code; ?>
                </i>
				<?php break;
		}
	}
}