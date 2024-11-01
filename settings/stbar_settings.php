<?php

if( ! class_exists( 'STBAR_option_settings' ) ) {
	class STBAR_option_settings {

	    /**
	     * @param stbar_data  array data in tabs   
	     * @param $tabs array plugin's tabs
	     */	      	
		function __construct( array $stbar_data, array $tabs) {
			$this->stbar_data            = $stbar_data;
			$this->tabs                  = $tabs;			
			$this->stbar_page_slug       = 'stbar_page_slug';
			$this->stbar_settings_errors = 'stbar_settings_errors';

			add_action( 'admin_init',            array( $this, 'stbar_register_options' ));
			add_action( 'admin_menu',            array( $this, 'stbar_register_menu') );
			add_action( 'admin_notices',         array( $this, 'stbar_display_notice' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'stbar_color_picker' ) );			
	    }


	    /**
	     * register smenu
	     * @return void
	     */ 
		public function stbar_register_menu() :void{
            add_menu_page(esc_html__( 'Settings', 'simple-top-bar' ), esc_html__( 'Simple top bar', 'simple-top-bar' ), 'manage_options', 'stbar_page_slug',  array( $this, 'stbar_tabs_display' ) ,"dashicons-align-center",5);
        }

	    /**
	     * register script and style for color picker
	     * @return void
	     */ 
	    public function stbar_color_picker( $hook ) :void{
	        wp_enqueue_script( 'wp-color-picker' );
	        wp_enqueue_style ( 'wp-color-picker' );
	    }

	    /**
	     * display some text for option section
	     * @return void
	     */ 
		public function stbar_display_section() :void{
			//echo "<div class='stbar_section'>$title</div>";
		}	    

	    /**
	     * register all options
	     * @return void
	     */
		public function stbar_register_options() :void{
			foreach ($this->stbar_data as $option) {
				$this->stbar_register_settings(
					$option['group'],
					$option['bd_pro'],
					$option['id_section'],
					'stbar_display_section',
					$option['page_slug'],
					$option['value_options'],
					'stbar_display_input_options',
					$option['title_section']
				);
			}
		}

	    /**
	     * register each option
	     * @return void
	     */ 
		private function stbar_register_settings(string $option_group, string $settings_bd, string $section_id, string $display_section, string $page_slug,  $value_options, string $display_input_options, string $title_section) :void{

			register_setting( $option_group, $settings_bd, 	array($this,'stbar_options_validate') );
			add_settings_section( $section_id, $title_section, array($this, $display_section), $page_slug );

			foreach ($value_options as $option) {
				$this->stbar_add_field($page_slug, $section_id, $display_input_options, $option['id_option'], $option['label_option'], $option['type_option']);
			}		
		}

		/**
	     * add setting field
	     * @param $page_slug string page slug
		 * @param $section_id string section id
		 * @param $callback string function callback
		 * @param $name_field string name field
	     * @param $label string label for input
	     * @param $type  string type for input
	     * @param $args string 
	     * @return void
	     * 
	     */ 
		private function stbar_add_field(string $page_slug, string $section_id, string $callback, string $name_field, string $label, string $type, string $args = "") :void {
			add_settings_field(
				$name_field,
				$label,
				array($this, $callback),
				$page_slug,
				$section_id,
				array( 
					'label_for'  => $name_field,
					'class'      => 'stbar_class',
					'name_field' => $name_field,
					'type_field' => $type,
					'vals'       => $args,
					'id'         => $name_field
				)
			);
		}

		/**
	     * display input data
	     * @param $field array option input
	     * @param $settings_bd string name option in database
	     * @param $value_options array options
	     * @return void
	     * 
	     */
		private function get_data(array $field, string $settings_bd, array $value_options) {
			$index = esc_attr( $field['name_field'] );
			$type  = esc_attr( $field['type_field'] );
			$val   = get_option($settings_bd);
			$val   = ($val) ? $val : [];
			if( 'radio' == $type ) {
				require_once STBAR_PLUGIN_DIR_PATH . 'settings/stbar_class_settings_radio.php';
				$tmp = new Stbar_class_settings_radio( $value_options );
				echo $tmp->stbar_input_radio( $val, $index, esc_attr( $settings_bd ) );
			}else{
				require_once STBAR_PLUGIN_DIR_PATH . 'settings/stbar_class_settings_TENCDRT.php';
				$tmp = new Stbar_class_settings_TENCDRT( $value_options );
				echo $tmp->stbar_input_TENCDRT($val, $index, $type, esc_attr($settings_bd));
			}
		}

	    /**
	     * display option for input options for screen < 576px
	     * @param $field array options
	     * @return void
	     * 
	     */
		public function stbar_display_input_options(array $field) :void{
			foreach ($this->stbar_data as $option){
				foreach ($option['value_options'] as $value) {			
					if ($value['id_option'] == $field['id']) {
						$this->get_data($field, $option['bd_pro'], $option['value_options']);
					}
				}
			}
		}

	    /**
	     * display options for tab
	     * @param $option_group string option group
	     * @param $page_slug string page slug
	     * @return void
	     * 
	     */
		public function display_tab(string $option_group, string $page_slug) :void{
			?>
			<div class="">		
				<form method="post" action="options.php">
					<?php			
						settings_errors( $this->stbar_settings_errors );
						settings_fields( $option_group);
						do_settings_sections( $page_slug );
						submit_button(); 
					?>
				</form>
			</div>
			<?php
		}

	    /**
	     * set tabs line
	     * $curreht string current tab
	     * @return string
	     */ 

		private function stbar_list_tabs(string $current) :string{
			$html = "";
			foreach( $this->tabs as $tab => $value ){
		        $html .= sprintf(
		        	"<a class='stbar_tabs nav-tab %s' href='?page=%s&tab=%s'>%s</a>",
		        	( $tab == $current ) ? 'stbar_active nav-tab-active' : '',
		        	$this->stbar_page_slug,
		        	$tab,
		        	sanitize_text_field($value['title'])
		        );          
		    }
		    return (string) $html;
		}

	    /**
	     * display tabs
	     * @return void
	     */ 
		public function stbar_tabs_display() :void{
		    $current = ( ! empty( esc_attr( $_GET['tab'] ) ) ) ? esc_attr( $_GET['tab'] ) : 'general';
		    
		    printf(
		    	"<h2>%s</h2><h3 class='nav-tab-wrapper'>%s</h3>",
		    	esc_html__( 'Bar settings', 'simple-top-bar' ),
		    	$this->stbar_list_tabs($current)
		    );

		    foreach( $this->tabs as $tab => $value ) {
		    	if ($tab == $current) $this->display_tab($value['group'], $value['page_slug']);
		    }
		}

	    /**
	     * display notice
	     * @return void
	     * 
	     */ 	
		public function stbar_display_notice() :void {

			if ( ! empty( get_settings_errors( $this->stbar_settings_errors ) ) ) return;
			
			if( isset( $_GET[ 'page' ] )
				&& $this->stbar_page_slug == sanitize_text_field( $_GET[ 'page' ] )
				&& isset( $_GET[ 'settings-updated' ] )
				&& true == sanitize_text_field( $_GET[ 'settings-updated' ] )

			) {
				?>
					<div class="notice notice-success is-dismissible">
						<p><?php esc_html_e( 'Settings updated', 'simple-top-bar'  )?></p>
					</div>
				<?php
			}
		}

	    /**
	     * Check valid HEX code.
	     * 
	     * @param  $color string HEX code
	     * @return bool true if $color is #000 or #000000
	     */

	    private function stbar_check_color( string $color ) :bool { 
	    	return ( ( preg_match( '/^#[a-f0-9]{6}$/i', $color )) || (preg_match( '/^#[a-f0-9]{3}$/i', $color ))  ) ? true : false;
	    }

	    /**
	     * validate input data
	     * @param $input array 
	     * @return array
	     */ 
		public function stbar_options_validate( array $input ) :array {
			foreach( $input as $name_option => & $val ){
				if( is_array( $this->stbar_value_options )) {
					foreach ( $this->stbar_value_options as $option ) {	
						if (esc_html($name_option) == esc_html( $option['id_option'] ) ) {
							if ( 'stbar_text_option'  == esc_html( $option['id_option']) ) {
								$val = sanitize_text_field($val);
							}
							if ( $option['validate'] && is_array( $option['validate'])) {
								foreach ( $option['validate'] as $key_validate => $value ) {
									if ( 'check_color' == $key_validate) {
										if(!$this->stbar_check_color($val)) {
											$input[$option['id_option']] = $this->stbar_add_error( esc_html($name_option),esc_html($option['label_option'])  . ': '.
											sprintf(
												esc_html__('wrong color code %s ','simple-top-bar' ),
												esc_html( $val )
											) );
										}
									}
								}
							}
						}
					}
				}
			}
			
			return (array) $input;
		}

	    /**
	     * add eroor message and return field value
	     * @param $name_field string 
	     * @param $message string error message 
	     * @return 
	     */ 
		private function stbar_add_error( string $name_field, string $message ) {
			add_settings_error(
				$this->stbar_settings_errors,
				$name_field,
				$message,
				'error'
			);

			foreach ($this->stbar_data as $option){
				if ( get_option($option['bd_pro'] )) {
					if ( is_array( get_option($option['bd_pro']))) {
						foreach (get_option($option['bd_pro']) as $key => $value) {
							if ($key == $name_field) return $value;
						}
					}
				}
			}			
			return "";			
		}
	}
}