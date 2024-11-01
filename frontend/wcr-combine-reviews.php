<?php

$limit = get_option( 'comments_per_page' );
$page = (get_query_var('page')) ? get_query_var('page') : 1;
$offset = ($page * $limit) - $limit;
$total_comments = get_comments( array
  (
  'orderby'   => 'post_date',
  'order'     => 'DESC',
  'post_type' => 'product',
  'status'    => 'approve',
  'parent'    =>0
  )
);

$pages = ceil(count($total_comments)/$limit);
$args = array (
  'post_type' => 'product',
  'number' => $limit,
  'offset' => $offset,
  'status' => 'approve'
);

$comments = get_comments( $args );

wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ), $comments );

if( is_singular( 'product' ) ){
  $fragment = '#reviews';
}

else{
  $fragment = '';
}

echo '<nav class="woocommerce-pagination">';
$args = array(
  'base'      => @add_query_arg('page','%#%'),
  'format'    => '?page=%#%',
  'total'     => $pages,
  'current'   => $page,
  'show_all'  => False,
  'end_size'  => 1,
  'mid_size'  => 2,
  'prev_next' => True,
  'prev_text' => __( 'Previous', 'woo-combined-reviews' ),
  'next_text' => __( 'Next', 'woo-combined-reviews' ),
  'type'      => 'plain',
  'add_fragment' => $fragment
);

echo paginate_comments_links( $args );

echo '</nav>';

if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
  echo '<nav class="woocommerce-pagination">';
paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', $args ) );
echo '</nav>';
endif;