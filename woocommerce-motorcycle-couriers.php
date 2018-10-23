<?php
/*
Plugin Name:  WooCommerce Motorcycle Couriers
Plugin URI:   https://
Description:  WooCommerce Motorcycle Courier plugin.
Version:      0.0.1
Author:       Jandeilson De Sousa (aka JDeS)
Author URI:   https://jandeilson.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  woocommerce-motorcycle-couriers
Domain Path:  /languages
*/


if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

define( 'WC_MOTORCYCLE_COURIERS_VERSION', '0.0.1' );
define( 'WC_MOTORCYCLE_COURIERS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( WC_MOTORCYCLE_COURIERS__PLUGIN_DIR . 'woocommerce-motorcycle-couriers-reports.php' );

/** 

WordPress Admin 

**/

// custom post type motorcycle couriers
function WP_admin_motorcycle_couriers_post_type() {

	$labels = array(
		'name' => _x('Motorcycle Couriers', 'Motorcycle Couriers'),
        'singular_name' => _x('Motorcycle Courier', 'Motorcycle Courier'),
        'all_items' => 'All Motorcycle Couriers',
        'menu_name' => 'Motorcycle Couriers'
    );

    $args = array(
    	'labels' => $labels,
        'public' => true,
        'public_queryable' => true,
        'show_ui' => true,           
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'menu_icon' => 'dashicons-groups',     
        'supports' => array('title', 'editor')
    );

    register_post_type( 'motorcycle_courier', $args );
}

add_action( 'init', 'WP_admin_motorcycle_couriers_post_type' );



/**

WooCommerce Order 

**/

// meta box motorcycle couriers
add_action( 'add_meta_boxes', 'WC_order_motorcycle_couriers_box' );

function WC_order_motorcycle_couriers_box() {
	add_meta_box( 
		'woocommerce-order-motorcycle_couriers', __( 'Motorcycle Courier' ), 
        'WC_order_motorcycle_couriers_select',
        'shop_order',
        'side',
        'default'
    );
}

//  select motorcycle couriers
function WC_order_motorcycle_couriers_select() {
	$args = array('post_type' => 'motorcycle_courier', 'orderby' => 'title', 'order' => 'ASC');
    $query = new WP_Query($args);
    $motorcycle_couriers = $query->posts;
    $motorcycle_courier_selected = get_post_meta( get_the_ID(), 'motorcycle_courier', true);

    ?><select id="motorcycle_courier" name="motorcycle_courier" style="width:100%">
    	<option value="">Select a Motorcycle Courier</option><?php
    	if (!empty($query)) {

    		foreach ($motorcycle_couriers as $motorcycle_courier) {
            $selected = '';

            if ($motorcycle_courier->post_title == $motorcycle_courier_selected) $selected= 'selected="selected"';

            echo '<option value="'.esc_attr($motorcycle_courier->post_title).'" '.$selected.'>'.$motorcycle_courier->post_title.'</option>';
            }
        }
    ?></select><?php
}

// save selected motorcycle courier
add_action( 'woocommerce_process_shop_order_meta', 'motorcycle_couriers_save_details' );

function motorcycle_couriers_save_details( $ord_id ){
    update_post_meta( $ord_id, 'motorcycle_courier', wc_clean( $_POST[ 'motorcycle_courier' ] ) );
}



/**

WooCommerce Motorcycle Couriers Reports

**/

// admin menu reports
add_action('admin_menu', 'motorcycle_couriers_reports_admin_menu');

function motorcycle_couriers_reports_admin_menu() {
    add_submenu_page( 'edit.php?post_type=motorcycle_courier','Reports', 'Reports', 'manage_options', 'woocommerce-motorcycle-couriers-reports', 'motorcycle_couriers_reports' ); 
}



}