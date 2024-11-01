<?php

/**
 * class for input type text, number
 */

class Stbar_class_settings_TENCDRT {

	function __construct (array $value_options) {
		$this->value_options = $value_options;
	}

    /**
     * get attribute value for input
     * @param $attr string name attribute
     * @return string attribute value
     * 
     */ 
	private function stbar_get_attribute (string $attr, string $id_option) :string{
		foreach ( $this->value_options as $option ) {
			if ( sanitize_key($option['id_option']) == $id_option) {
				foreach ($option as $key1 => $value1) {					
					if ( 'validate' == $key1 ){
						foreach ($value1 as $key2 => $value2) {
							if ($key2 == $attr) return (string) sanitize_text_field($value2);
						}
					}
				}
			}
		}
		return (string) "";
	}

    /**
     * get helper value
     * @param $id_option string id_option option
     * @return string helper text
     * 
     */ 
	private function stbar_get_helper( string $id_option) :string {
		foreach ( $this->value_options as $option ) {
			if ( sanitize_key($option['id_option']) == $id_option) {
				foreach ($option as $key => $value) {
					if ( 'helper' == $key ) {
						return (string) sanitize_text_field($value);
					}
				}
			}
		}
		return (string) "";
	}

    /**
     * get value atribute min max 
     * @param $type string  type for input
     * @return value atribute min max
     */ 
	private function stbar_get_min_max( string $type) :string{
		$min = intval($this->stbar_get_attribute('check_min', $type));
		$max = intval($this->stbar_get_attribute('check_max', $type));

		$input  = (($min) || ($min === 0) ) ? " min=" . $min : "";
		$input .= (($max) || ($max === 0) ) ? " max=" . $max : "";
		return (string) $input;
	}

    /**
     * get required atribute
     * @param $type for input
     * @return string required value for atribute
     */ 
	private function stbar_get_required( string $type) :string{
		return (string) ($this->stbar_get_attribute('required', $type)) ? " required " : ""; 
	}

    /**
     * get atribute class
     * @param $type for input
     * @return string value atribute class
     */ 
	private function stbar_get_class( string $type) :string{
		$class = sanitize_html_class($this->stbar_get_attribute('class', $type));
		return (string) ($class) ? " class='" . $class . "'" : "";
	}
	
     /**
     * input for type TENCDRT (Text Email Number Checkbox Date Range Tel)
     * @param $val array options
     * @param $index string name option, id for input
     * @param $type string input type
     * @return void
     * 
     */ 
	public function stbar_input_TENCDRT(array $val, string $index, string $type, string $bd) :string{
		$tmp = sprintf(
			"<input type='%s' id='%s' name='%s[%s]' ",
			$type,
			$index,
			$bd,
			$index
		);
		if ( 'checkbox' == $type ) {
			$tmp .= "value='1' ".checked( 1, $val[$index], false );
			$tag  = "span";
			$padding  = "20px";
		}else {
			$tmp .= "value='". $val[$index]."' ";
			$tag  = "div";
			$padding  = "0";	
		}

		if (   ( 'number' == $type ) 
			|| ( 'date'   == $type )
			|| ( 'range'  == $type ) ) $tmp .= $this->stbar_get_min_max( $index );

		$tmp .= $this->stbar_get_required($index);
		$tmp .= $this->stbar_get_class($index);

		$tmp .= sprintf(
			" /><%s style='font-size:12px;padding-left:%s'>%s</%s>",
			$tag,
			$padding,
			$this->stbar_get_helper($index),
			$tag
		);
		return (string) $tmp;
	}
}