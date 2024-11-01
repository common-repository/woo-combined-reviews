<?php
/**
 * Plugin Name:       Combine Reviews for WooCommerce
 * Plugin URI:        https://ahmadshyk.com/item/woocommerce-combined-reviews-pro/
 * Description:       The simple plugin that combine reviews of all products on your WooCommerce website and display all combined reviews for each product.
 * Version:           1.0.0
 * Author:            Ahmad Shyk
 * Author URI:        https://ahmadshyk.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-combined-reviews
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Activation Hook.
 */
register_activation_hook( __FILE__, 'wcr_activate' );
function wcr_activate(){
	$default = array(
		'combine-reviews'        => 1, 
	);
	add_option( 'wcr_options', $default, '', 'yes' );
}

/**
 * Deactivation Hook.
 */
register_deactivation_hook( __FILE__, 'wcr_deactivate' );
function wcr_deactivate(){
}

/**
 * Admin notice if WooCommerce not installed and activated.
 */
function wcr_no_woocommerce(){ ?>
		<div class="error">
				<p><?php _e( 'WooCommerce Combined Reviews is activated but not effective. It requires WooCommerce in order to work.', 'woo-combined-reviews' ); ?></p>
			</div>
<?php	
}

/**
 *  Main Class
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

require plugin_dir_path( __FILE__ ) . 'class-woo-combined-reviews.php';

new WCR_Main_Class();

}

else{
	add_action( 'admin_notices', 'wcr_no_woocommerce' );
}

//Add settings link on plugin page
function wcr_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=woo-combined-reviews">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'wcr_settings_link' );