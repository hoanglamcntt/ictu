<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( $max_value && $min_value === $max_value ) {
	?>
    <div class="quantity hidden">
        <input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>"/>
    </div>
<?php } else { ?>
    <div class="quantity">
        <label for="<?php echo esc_attr( $input_id ); ?>" class="quantity-label">Số Lượng</label>
        <div class="control">
            <a class="btn-number qty-minus quantity-minus" href="#"><i class="fa fa-caret-down" aria-hidden="true"></i></a>
            <input
                    type="text"
                    id="<?php echo esc_attr( $input_id ); ?>"
                    class="input-qty <?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
                    step="<?php echo esc_attr( $step ); ?>"
                    min="<?php echo esc_attr( $min_value ); ?>"
                    max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
                    name="<?php echo esc_attr( $input_name ); ?>"
                    value="<?php echo esc_attr( $input_value ); ?>"
                    title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'ictu' ); ?>"
                    size="4"
                    placeholder="<?php echo esc_attr( $placeholder ); ?>"
                    inputmode="<?php echo esc_attr( $inputmode ); ?>"
            />
            <a class="btn-number qty-plus quantity-plus" href="#"><i class="fa fa-caret-up" aria-hidden="true"></i></a>
        </div>
    </div>
	<?php
}
