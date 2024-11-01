<?php

/**
 * class for input type radio
 */
class Stbar_class_settings_select {

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

	private function stbar_select_option($val, $index, $options) {
		$tmp = "";
		foreach ($options as $value) {
			$tmp .= sprintf(
				"<option value = '%s' %s>%s</option>",
				$value['id_select'],
				($val[$index] == $value['id_select']) ? "selected='selected'" : '',
				$value['name_select']
			);
		}
		return $tmp;
	}

	public function stbar_input_select($val, $index, $settings_bd){
		foreach ($this->value_options as $option) {
			if ( $index == $option['id_option']) {
				return (string) sprintf(
					"<select id='%s' name='%s[%s]'>%s</select>
					<span class='stbar_select_helper'>%s</span>",
					$index,
					$settings_bd,
					$index,
					$this->stbar_select_option($val, $index, $option['select_options']),
					$this->stbar_get_helper($index)
				);
			}
		}
	}
}