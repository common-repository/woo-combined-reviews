<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class WCR_Main_Class{

    public $wcr_options;
    public $plugin_version;

	public function __construct(){
        $this->plugin_version = '1.0.0';
        $this->wcr_options = get_option( 'wcr_options' );
        add_action( 'admin_menu', array( $this, 'wcr_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        if( $this->wcr_options['combine-reviews'] == 1 ){
        add_action( 'plugins_loaded', array( $this, 'wcr_remove_default_rating' ) );
        add_action( 'plugins_loaded', array( $this, 'wcr_filter_update_review_tab' ) );
    }
    }

    // Admin Page Script
    public function admin_scripts($hook){
        if($hook != 'toplevel_page_woo-combined-reviews') {
               return;
        }
        wp_enqueue_style( 'wcr-admin-css', plugins_url('/assets/css/admin.css', __FILE__), '', $this->plugin_version );
    }

    // Remove Default product rating on shop page and single product page
    public function wcr_remove_default_rating(){
        remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
    }

    // This finds the star rating for the current product. Extend the search to find all of them
    public function wcr_get_products_ratings(){
        global $wpdb;

        return $wpdb->get_results("
            SELECT t.slug, tt.count
            FROM {$wpdb->prefix}terms as t
            JOIN {$wpdb->prefix}term_taxonomy as tt ON tt.term_id = t.term_id
            WHERE t.slug LIKE 'rated-%' AND tt.taxonomy LIKE 'product_visibility'
            ORDER BY t.slug
            ");
    }

    // Star Rating
    public function wcr_get_total_reviews_count(){
        return get_comments(array(
            'status'   => 'approve',
            'post_status' => 'publish',
            'post_type'   => 'product',
            'count' => true
        ));
    }

    //Combine all reviews
    public function wcr_combine_reviews(){
        require plugin_dir_path( __FILE__ ) . '/frontend/wcr-combine-reviews.php';
    }

    // Single Product Reviews Tab Content
    public function wcr_single_product_reviews_tab_content( $tabs ){
        $tabs['reviews']['callback'] = array( $this, 'wcr_single_product_reviews_tab_callback' );

        $stars = 1;
        $average = 0;
        $total_count = 0;

        $tabs['reviews']['title'] = __( 'Reviews (' . $this->wcr_get_total_reviews_count() . ')' );                // Rename the reviews tab
        return $tabs;
    }

    // Update Filter to display combined reviews in review tab
    public function wcr_filter_update_review_tab(){
       add_filter( 'woocommerce_product_tabs', array( $this, 'wcr_single_product_reviews_tab_content' ), 98 );
    }

    // Single Product Reviews Tab Callback
    public function wcr_single_product_reviews_tab_callback(){
        require plugin_dir_path( __FILE__ ) . '/frontend/wcr-single-product-reviews-tab-content.php';
    }

    // Adds admin menu page 
    public function wcr_admin_menu(){
    	add_menu_page(
            __( 'Woo Combined Reviews', 'woo-combined-reviews' ),
            __( 'Woo Combined Reviews', 'woo-combined-reviews' ),
            'manage_options',
            'woo-combined-reviews',
            array( $this, 'wcr_admin_menu_callback' ),
            'dashicons-admin-generic',
            59
        );
    }

    // Admin menu callback
    public function wcr_admin_menu_callback(){
        require plugin_dir_path( __FILE__ ) . 'admin/wcr_admin.php';
    }
}