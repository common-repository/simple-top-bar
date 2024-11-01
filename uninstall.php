<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

delete_option( 'stbar_bd' );
delete_option( 'stbar_bd_tel' );
delete_option( 'stbar_bd_laptop' );
delete_option( 'stbar_bd_pro' );
delete_option( 'stbar_view' );
