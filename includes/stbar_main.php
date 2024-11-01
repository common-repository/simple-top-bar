<?php

/**
 * main class Simple top bar plugin
 */
if( ! class_exists( 'STBAR_MAIN' ) ) {
    class STBAR_MAIN {

        public function stbar_set_shortcode() {

            $val_general = get_option('stbar_bd');
            $val_tel     = get_option('stbar_bd_tel');
            $val_laptop  = get_option('stbar_bd_laptop');

            if( ! intval($val_general['stbar_active']) ) return;

            if( ( 'stbar_text_option' == sanitize_text_field( $_COOKIE["stbar_cookies"] ) ) && ( ! intval( $val_general['stbar_active_permanently'])) ) return;

            update_option('stbar_view', (get_option('stbar_view')) ? get_option('stbar_view') + 1 : 1);

            $set_shortcode = '
                <div class="stbar_block">
                <div class="stbar_notice';

            if( intval($val_general['stbar_animation'])) {
                $set_shortcode .= ( 'top' == sanitize_text_field( $val_general['stbar_placement'] ) )
                                     ? ' stbar_top_animation'
                                     : ' stbar_bottom_animation';
            }

            $set_shortcode .= '">
                <span class="stbar_span">'.
                    sanitize_text_field( $val_general['stbar_text_option'] ) .
                '</span>';

            $set_shortcode .= '
                <button title="'.esc_html__("Close top bar", 'simple-top-bar').'"
                    type="button" 
                    class="stbar_notice_dismiss" 
                    style="background-color:' . sanitize_text_field($val_general['stbar_background_color']) . '!important; border: 0!important;">
                    <span style="cursor: pointer; color:' . sanitize_text_field($val_general['stbar_font_color']) .';" class="dashicons dashicons-dismiss"></span>
                </button>
                </div></div>';
                
            $set_shortcode .= '
                <style>

                    .stbar_notice {
                        color:' . sanitize_text_field($val_general['stbar_font_color']) .';
                        font-size:' . intval($val_laptop['stbar_font_size']) . 'px;
                        background-color:' . sanitize_text_field($val_general['stbar_background_color']) .';
                        height:' . intval($val_laptop['stbar_block_height']) . 'px;
                        z-index:99999;
                        width:' . intval($val_laptop['stbar_block_width_laptop']) . '%;
                        border-radius:' . intval($val_general['stbar_border_radius']) . 'px;';
                
            $set_shortcode .= 'position:' . sanitize_text_field($val_general['stbar_position']) . ';
                        ';
            if ( 'top' == sanitize_text_field( $val_general['stbar_placement'] ) ) {
                $set_shortcode .= 'top:0;';
                if( is_admin_bar_showing() ) $set_shortcode .= 'margin-top:32px;';
            }else{
                $set_shortcode .= 'bottom:0;';
            }
            $set_shortcode .= '}
                    @media screen and (max-width: 576px) {
                        .stbar_notice {
                            font-size:' . intval($val_tel['stbar_font_size_tel']) . 'px;
                            height:' . intval($val_tel['stbar_block_height_tel']) . 'px;
                            width:' . intval($val_tel['stbar_block_width_tel']) . '%;
                        }
                    }
                    .stbar_notice_dismiss:before {
                        color: ' . sanitize_text_field($val_general['stbar_font_color']) . ';
                    }
                </style>
                ';

            return $set_shortcode;
        }

        public function stbar_style(){
            wp_enqueue_style( 'dashicons' );
            wp_enqueue_style( 'stbar_css', plugins_url( 'public/css/stbar.css', dirname(__FILE__) ) , array(), STBAR_PLUGIN_VERSION);
            wp_enqueue_script( 'stbar_js', plugins_url( 'public/js/stbar.js', dirname(__FILE__) ), array(), STBAR_PLUGIN_VERSION, true );
        }

        public function stbar_run(){
            add_action( 'wp_enqueue_scripts',  array($this, 'stbar_style') );
            add_shortcode('stbar_output', array($this, 'stbar_set_shortcode'));
            add_action("wp_head", array ($this, "stbar_output_shortcode") );
        }
   
        public function stbar_output_shortcode()  {
            echo do_shortcode('[stbar_output]');
        }
    }
}