<?php
if( ! class_exists( 'STBAR_Deactivator' ) ) {
	
	class STBAR_Deactivator {

		public static function deactivate() {

			$val = get_option('stbar_bd');

			if( $val['stbar_delete_option'] ) {
				delete_option( 'stbar_bd' );
				delete_option( 'stbar_bd_tel' );
				delete_option( 'stbar_bd_laptop' );
				delete_option( 'stbar_bd_pro' );
				delete_option( 'stbar_view' );
			}
		}
	}
}