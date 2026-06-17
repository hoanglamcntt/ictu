<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}
$thumbnail_size = array( 180, 180 );
$thumbnail_id   = $product->get_image_id();
$arr_img        = $thumbnail_id ? wp_get_attachment_image_src( $thumbnail_id, $thumbnail_size ) : [];
$thumbnail_src  = ! empty( $arr_img ) && $arr_img[0] ? $arr_img[0] : 'https://via.placeholder.com/180';
?>
<div id="reviews" class="woocommerce-Reviews">
	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
        <div class="review--form-wrap theme-single-review__form-wrap">
			<?php theme_display_review_average() ?>
            <div id="review_form_wrapper">
                <div class="review-form-wrapper__inner">
                    <div id="review_form">
						<?php
						$commenter    = wp_get_current_commenter();
						$comment_form = array(
							/* translators: %s is product title */
							'title_reply'         => have_comments() ? esc_html__( 'Submit your review', 'ictu' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'ictu' ), get_the_title() ),
							/* translators: %s is product title */
							'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'ictu' ),
							'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
							'title_reply_after'   => '</span>',
							'comment_notes_after' => '',
							'label_submit'        => esc_html__( 'Submit Review', 'ictu' ),
							'logged_in_as'        => '',
							'comment_field'       => '',
						);

						$name_email_required    = (bool) get_option( 'require_name_email', 1 );
						$fields                 = array(
							'author' => array(
								'label'    => __( 'Name', 'ictu' ),
								'type'     => 'text',
								'value'    => $commenter['comment_author'],
								'required' => $name_email_required,
							),
							'email'  => array(
								'label'    => __( 'Email', 'ictu' ),
								'type'     => 'email',
								'value'    => $commenter['comment_author_email'],
								'required' => $name_email_required,
							),
						);
						$comment_form['fields'] = array();
						$comment_lbl            = 'Viết nhận xét của bạn vào bên dưới:';
						if ( wc_review_ratings_enabled() ) {
							$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">1. Đánh giá của bạn về sản phẩm này:' . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
						<option value="">' . esc_html__( 'Rate&hellip;', 'ictu' ) . '</option>
						<option value="5">' . esc_html__( 'Perfect', 'ictu' ) . '</option>
						<option value="4">' . esc_html__( 'Good', 'ictu' ) . '</option>
						<option value="3">' . esc_html__( 'Average', 'ictu' ) . '</option>
						<option value="2">' . esc_html__( 'Not that bad', 'ictu' ) . '</option>
						<option value="1">' . esc_html__( 'Very poor', 'ictu' ) . '</option>
						</select></div>';
							$comment_lbl                   = is_user_logged_in() ? '2.Viết nhận xét của bạn vào bên dưới:' : '2.Nhập thông tin vào nhận xét bên dưới:';
						}

						$account_page_url = wc_get_page_permalink( 'myaccount' );
						if ( $account_page_url ) {
							/* translators: %s opening and closing link tags respectively */
							$comment_form['must_log_in'] = '<div class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'ictu' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</div>';
						}
						if ( is_user_logged_in() ) {
							$comment_form['comment_field'] .= '<div class="comment-form-comment"><label for="comment">' . $comment_lbl . '</label><textarea placeholder="' . esc_html__( 'Write your review here...', 'ictu' ) . '" id="comment" name="comment" cols="45" rows="8" required></textarea></div>';
							foreach ( $fields as $key => $field ) {
								$comment_form['fields'][ $key ] = '<div class="comment-form-' . esc_attr( $key ) . '"><input placeholder="' . esc_html( $field['label'] ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></div>';
							}
						} else {
							$comment_form['fields']['title'] = '<div class="comment-form-title"><label class="form-label--seconds">' . $comment_lbl . '</label></div>';
							foreach ( $fields as $key => $field ) {
								$comment_form['fields'][ $key ] = '<div class="comment-form-' . esc_attr( $key ) . '"><input placeholder="' . esc_html( $field['label'] ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></div>';
							}
							$comment_form['fields']['comment_field'] = '<div class="comment-form-comment"><textarea placeholder="' . esc_html__( 'Write your review here...', 'ictu' ) . '" id="comment" name="comment" cols="45" rows="8" required></textarea></div>';
						}
						comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
						?>
                        <i class="review-form__approve-condition">* Để nhận xét được duyệt, quý khách lưu ý tham khảo tiêu chí duyệt nhận xét của chúng tôi</i>
                    </div>
                    <div class="review-form__product-detail">
                        <div class="review-form__product-detail__head">
                            <div class="review-form__product-detail__product-media">
                                <img src="<?php echo esc_url( $thumbnail_src ) ?>" width="180" height="180" alt="<?php echo esc_html( get_the_title() ) ?>">
                            </div>
							<?php the_title( '<b class="review-form__product-detail__product-title">', '</b>' ); ?>
                        </div>
                        <p class="review-form__product-detail__desc">Quý khách có thắc mắc về sản phẩm hoặc dịch vụ của chúng tôi? Quý khách đang muốn khiếu nại hay phản hồi về đơn hàng đã mua?</p>
                    </div>
                </div>
            </div>
        </div>
	<?php else : ?>
        <p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'ictu' ); ?></p>
	<?php endif; ?>
    <div id="comments">
        <h2 class="woocommerce-Reviews-title">
			<?php
			$count = $product->get_review_count();
			if ( $count && wc_review_ratings_enabled() ) {
				/* translators: 1: reviews count 2: product name */
				$reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'ictu' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
				echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product ); // WPCS: XSS ok.
			} else {
				esc_html_e( 'Reviews', 'ictu' );
			}
			?>
        </h2>

		<?php if ( have_comments() ) : ?>
            <ol class="commentlist">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
            </ol>
			<?php
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links(
					apply_filters(
						'woocommerce_comment_pagination_args',
						array(
							'prev_text' => '&larr;',
							'next_text' => '&rarr;',
							'type'      => 'list',
						)
					)
				);
				echo '</nav>';
			endif;
			?>
		<?php else : ?>
            <p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'ictu' ); ?></p>
		<?php endif; ?>
    </div>
    <div class="clear"></div>
</div>
