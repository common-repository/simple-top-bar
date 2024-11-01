<?php

/**
 * Plugin Name:       Simple top bar
 * Plugin URI:        https://www.gomgal.lviv.ua/topBar
 * Description:       <code><strong>Simple top bar</strong></code>Customizer for top bar
 * Version:           1.4.6
 * Requires PHP:      7.2
 * Requires at least: 3.8
 * Author:            Yaroslav Livchak
 * Author URI:        https://www.gomgal.lviv.ua/topBar
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       simple-top-bar
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'STBAR_PLUGIN_DIR_PATH' ) ) {
    define( 'STBAR_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__));
}

if ( ! defined( 'STBAR_PLUGIN_VERSION' ) ) {
    define( 'STBAR_PLUGIN_VERSION', '1.4.6');
}

/**
 * Textdomain hook.
 */
function stbar_textdomain() {
    $locale = determine_locale();
    load_plugin_textdomain( 'simple-top-bar', false,  basename( plugin_basename(__FILE__) ) . '/languages/' . determine_locale() . '.mo');
}
add_action('plugins_loaded', 'stbar_textdomain');

/**
 * Deactivation hook.
 */
function stbar_deactivate() {
    require_once STBAR_PLUGIN_DIR_PATH . '/includes/stbar_deactivator.php';
    STBAR_Deactivator::deactivate();
}

function stbar_plugin_add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=stbar_page_slug">' . esc_html__( 'Settings', 'simple-top-bar'  ) . '</a>';
    array_push( $links, $settings_link );
    return $links;
}

if ( is_admin() ) {
    register_deactivation_hook( __FILE__, 'stbar_deactivate' );

    $filter_name = "plugin_action_links_" . plugin_basename( __FILE__ );
    add_filter( $filter_name, 'stbar_plugin_add_settings_link' );

    require_once STBAR_PLUGIN_DIR_PATH . '/includes/stbar_admin_main.php';
    $stbar_admin_run  = new STBAR_ADMIN_MAIN();
    $stbar_admin_run->stbar_admin_run();
}else {
    require_once STBAR_PLUGIN_DIR_PATH . '/includes/stbar_main.php';
    $stbar_front_run  = new STBAR_MAIN();
    $stbar_front_run->stbar_run();
}