<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( !current_user_can( 'activate_plugins' ) )  {
	wp_die( _e( 'You do not have sufficient permissions to access this page.', 'woo-combined-reviews' ) );
}

$plugin_version  = '1.0.0';

if ( ! empty( $_POST ) && check_admin_referer( 'wcr-afs', 'wcr-admin-nonce' ) ){
	$data = array(
		'combine-reviews'   => (isset($_POST['combine-reviews'])?'1':'0')
	);
	update_option( 'wcr_options', $data );
}

$current_options = get_option('wcr_options');
//print_r($current_options);

?>
<div class="wrap wcr-wrap">
	<h1 class="hidden-h1"></h1>
	<?php if ( isset( $_POST['wcr_option_submit'] ) ){ ?>
		<div class="notice notice-success"> 
			<p><strong>Settings saved.</strong></p>
		</div>
	<?php } ?>
	<div class="wcr-admin-page-title">
		<h1 class="wcr-admin-title"><?php echo get_admin_page_title(); ?></h1>
		<span class="wcr-version"><?php echo $plugin_version; ?></span>
	</div>
	<form method="POST" class="options-form">

		<?php wp_nonce_field( 'wcr-afs', 'wcr-admin-nonce' ); ?>

		<div class="block">
			<fieldset>
				<legend class="screen-reader-text"><span>
					<?php _e( 'Combine Reviews', 'woo-combined-reviews' ); ?>
				</span></legend>
				<label for="combine-reviews">
					<input name="combine-reviews" type="checkbox" <?php if( $current_options['combine-reviews'] == 1 ) : ?> checked <?php endif; ?> />
					<span><?php _e( 'Combine Reviews', 'woo-combined-reviews' ); ?></span>
				</label>
			</fieldset>
		</div>

		<input class="button-primary wcr-submit" type="submit" name="wcr_option_submit" value="<?php esc_attr_e( 'Save Changes', 'wcr-options-submit' ); ?>" />

		<?php 
		$pro_notice = __( '<h3><a class="pro-only-link" href="https://ahmadshyk.com/item/woocommerce-combined-reviews-pro/" target="_blank">Go Pro</a></h3><h4>What is included in Pro version?</h4><ol class="pro-details-list"><li>An Option to Add Total Rating under Product Title on Shop Page.</li><li>An Option to Add Total Rating under Product Title on Single Product Page.</li><li>Shortcode to Display Total Rating anywhere on your site.</li><li>Shortcode to List all your customer reviews anywhere on your site.</li><li>Preferred Support.</li><li>Lifetime Updates.</li></ol><h2>Interested in Pro Version?</h2><h4><a href="https://ahmadshyk.com/item/woocommerce-combined-reviews-pro/" target="_blank">Click here</a> to get pro version now.</h4>', 'woo-combined-reviews' );
		echo $pro_notice;
		?>

	</form>
	<div class="pro-notice">
		<!--<div class="review-request">
			<h3>
				<?php// _e( 'Rate this Plugin' ) ?>
			</h3>
			<p>
				<?php// _e( 'If you have a moment, I would very much appreciate if you could quickly rate the plugin on <a href="https://wordpress.org/support/plugin//reviews/#new-post" target="_blank">wordpress.org</a>, just to help us spread the word.' ) ?>
			</p>
		</div>-->
		<h4 class="wcr-contact-info">
			In case of any problem, question, idea or any WordPress related work, reach me at <a href="mailto:a.hassan@ahmadshyk.com">a.hassan@ahmadshyk.com</a>
		</h4>
	</div>
</div>