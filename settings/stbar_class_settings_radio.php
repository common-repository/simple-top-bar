<?php

/**
 * class for input type radio
 */
class Stbar_class_settings_radio {

	function __construct (array $value_options) {
		$this->value_options = $value_options;
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
     * input for type radio
     * @param $val array options
     * @param $index string name option, id for input
     * @return void
     * 
     */ 
	public function stbar_input_radio( array $val, string $index, string $settings_bd) :string{
		foreach ($this->value_options as $option) {				
			if ($index == $option['id_option']) {
				$vals = $option['radio_options'];
			}
		}		
		$vals = ($vals) ? $vals : [];
		$tmp  ="";
		foreach ($vals as $value) {
			$value_id = esc_attr($value['id_radio']);
			$tmp .= sprintf(
				"<div class='stbar_input_radio'>
				<input type='radio' id='%s' name='%s[%s]' value='%s' %s><label for='%s'>%s</label>
				</div>",
				$value_id,
				$settings_bd,
				$index,
				$value_id,
				($val[$index] == $value_id) ? "checked " : '',
				$value_id,
				esc_attr($value['name_radio'])
			);
		}
		$helper = $this->stbar_get_helper( $index);
		return (string)( $helper == "") ? $tmp : $tmp . sprintf(
														"<span class='stbar_select_helper'>%s</span>",
														$helper
														);
	}
}