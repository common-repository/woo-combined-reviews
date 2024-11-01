<div id="reviews">
  <div id="comments">
<?php 

    function wcr_have_reviews(){
      return count( get_comments() );
    }

    $comments = get_comments();
    if ( $comments ) : 

     ?>

     <ol class="commentlist">
      <!-- Displays all comments -->
      <?php $this->wcr_combine_reviews(); ?>
    </ol>

          <?php //if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :

         // endif; ?>
         <!-- END: Pagination code that isn't working -->

         <?php else : ?>

          <p class="woocommerce-noreviews"><?php _e( 'There are no reviews yet.', 'woo-combined-reviews' ); ?></p>

        <?php endif; ?>
      </div>

      <?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
      <div id="review_form_wrapper">
        <div id="review_form">
          <?php
          $commenter    = wp_get_current_commenter();
          $comment_form = array(
            /* translators: %s is product title */
            'title_reply'         => wcr_have_reviews() ? esc_html__( 'Add a review', 'woo-combined-reviews' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woo-combined-reviews' ), get_the_title() ),
            /* translators: %s is product title */
            'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'woo-combined-reviews' ),
            'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
            'title_reply_after'   => '</span>',
            'comment_notes_after' => '',
            'label_submit'        => esc_html__( 'Submit', 'woo-combined-reviews' ),
            'logged_in_as'        => '',
            'comment_field'       => '',
          );

          $name_email_required = (bool) get_option( 'require_name_email', 1 );
          $fields              = array(
            'author' => array(
              'label'    => __( 'Name', 'woo-combined-reviews' ),
              'type'     => 'text',
              'value'    => $commenter['comment_author'],
              'required' => $name_email_required,
            ),
            'email' => array(
              'label'    => __( 'Email', 'woo-combined-reviews' ),
              'type'     => 'email',
              'value'    => $commenter['comment_author_email'],
              'required' => $name_email_required,
            ),
          );

          $comment_form['fields'] = array();

          foreach ( $fields as $key => $field ) {
            $field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
            $field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

            if ( $field['required'] ) {
              $field_html .= '&nbsp;<span class="required">*</span>';
            }

            $field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

            $comment_form['fields'][ $key ] = $field_html;
          }

          $account_page_url = wc_get_page_permalink( 'myaccount' );
          if ( $account_page_url ) {
            /* translators: %s opening and closing link tags respectively */
            $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woo-combined-reviews' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
          }

          if ( wc_review_ratings_enabled() ) {
            $comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'woo-combined-reviews' ) . '</label><select name="rating" id="rating" required>
            <option value="">' . esc_html__( 'Rate&hellip;', 'woo-combined-reviews' ) . '</option>
            <option value="5">' . esc_html__( 'Perfect', 'woo-combined-reviews' ) . '</option>
            <option value="4">' . esc_html__( 'Good', 'woo-combined-reviews' ) . '</option>
            <option value="3">' . esc_html__( 'Average', 'woo-combined-reviews' ) . '</option>
            <option value="2">' . esc_html__( 'Not that bad', 'woo-combined-reviews' ) . '</option>
            <option value="1">' . esc_html__( 'Very poor', 'woo-combined-reviews' ) . '</option>
            </select></div>';
          }

          $comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woo-combined-reviews' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>';

          comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
          ?>
        </div>
      </div>
      <?php else : ?>
        <p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woo-combined-reviews' ); ?></p>
      <?php endif; ?>

</div>