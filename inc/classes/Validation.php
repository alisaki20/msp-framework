<?php


namespace MSPFramework;


interface ValidatableField {
	function hasValidator();

	function validate( $value );
}

abstract class Validator {
	abstract public function validate( $value, $existing_value, $field );
}

class Validation {
	static public $validations_classes = array();

	public static function add_validation_type( $validation_name, $validation_class ) {
		if ( is_string( $validation_name ) || $validation_class instanceof Validator ) {
			self::$validations_classes[ $validation_name ] = new $validation_class();
		}
	}

	public static function validate( $validator, $value, $existing_value, $field ) {
		$return = array();
		if ( is_string( $validator ) && isset( self::$validations_classes[ $validator ] ) ) {
			$validator = self::$validations_classes[ $validator ];
		}
		if ( $validator instanceof Validator ) {
			$return = $validator->validate( $value, $existing_value, $field );
		} else {
			$return[ 'value' ] = $value;
		}

		return $return;
	}
}

class Validator_color extends Validator {
	public function validate( $value, $existing_value, $field ) {
		$return = array();
		if ( preg_match( "/^#([a-f0-9]{3}|[a-f0-9]{4}|[a-f0-9]{6}|[a-f0-9]{8})$/i", $value ) ) {
			$return[ 'value' ] = $value;
		} else {
			$return[ 'value' ] = $existing_value;
			// translators: %s = the given value
			$return[ 'message' ] = sprintf( __( '\'%s\' is invalid hexadecimal color code', 'msp-framework' ), $value );
		}

		return $return;
	}
}

class Validator_email extends Validator {
	public function validate( $value, $existing_value, $field ) {
		$return = array();
		if ( filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
			$return[ 'value' ] = $value;
		} else {
			$return[ 'value' ] = $existing_value;
			// translators: %s = the given value
			$return[ 'message' ] = sprintf( __( '\'%s\' is invalid email', 'msp-framework' ), $value );
		}

		return $return;
	}
}

class Validator_url extends Validator {
	public function validate( $value, $existing_value, $field ) {
		$return = array();
		if ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
			$return[ 'value' ] = $value;
		} else {
			$return[ 'value' ] = $existing_value;
			// translators: %s = the given value
			$return[ 'message' ] = sprintf( __( '\'%s\' is invalid url', 'msp-framework' ), $value );
		}

		return $return;
	}
}

class Validator_numeric extends Validator {
	public function validate( $value, $existing_value, $field ) {
		$return = array();
		if ( is_numeric( $value ) ) {
			$return[ 'value' ] = $value;
		} else {
			$return[ 'value' ] = $existing_value;
			// translators: %s = the given value
			$return[ 'message' ] = sprintf( __( '\'%s\' is not valid number', 'msp-framework' ), $value );
		}

		return $return;
	}
}

class Validator_not_empty extends Validator {
	public function validate( $value, $existing_value, $field ) {
		$return = array();
		if ( empty( $value ) ) {
			$return[ 'value' ]   = $existing_value;
			$return[ 'message' ] = sprintf( __( 'The value should not be empty', 'msp-framework' ), $value );
		} else {
			$return[ 'value' ] = $value;
		}

		return $return;
	}
}

Validation::add_validation_type( 'color', new Validator_color() );
Validation::add_validation_type( 'email', new Validator_email() );
Validation::add_validation_type( 'url', new Validator_url() );
Validation::add_validation_type( 'numeric', new Validator_numeric() );
Validation::add_validation_type( 'not_empty', new Validator_not_empty() );