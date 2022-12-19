<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-checkout-review-order-table">
	<div class="orderReviewProduct">
		
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

				$_PROD_ITEM = get_post($_product->get_id());

				?>

				<div class="orderReviewProductContainer">
										
					<div class="orderReviewProductTitle">
						<h4><?php echo $_product->get_name(); ?></h4>
					</div>
					<div class="orderReviewProductImage">
						<img alt="<?php echo $_PROD_ITEM->post_title; ?>" src="<?php the_field('image', $_PROD_ITEM); ?>" />
					</div>
					<div class="orderReviewProductData">
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
					</div>

				</div>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>	

	</div>

	<div class="orderReviewData">

		<div class="row no-gutters checkoutSubtotal">
			<div class="col-12 col-sm-6"><strong><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></strong>:</div>
			<div class="col-12 col-sm-6"><?php wc_cart_totals_subtotal_html(); ?></div>
		</div>
			
		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<?php if(!stristr($fee->name, 'deposit')): ?>
				<div class="row no-gutters fee fee-<?php echo sanitize_title($fee->name); ?>">
					<div class="col-12 col-sm-6"><strong><?php echo esc_html( $fee->name ); ?></strong>:</div>
					<div class="col-12 col-sm-6"><?php wc_cart_totals_fee_html( $fee ); ?></div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php $_CART_AMOUNT = CARPRO_HELPERS::GET_CART_TOTAL(); ?>


		<?php if(WC()->session->get('carpro_deposit_title')): ?>

			<?php 

				$_DEP_PERCENT = WC()->session->get('carpro_deposit_type');
				$_DEP_AMOUNT = 100 - (int)$_DEP_PERCENT;
				$_DEP_TITLE = WC()->session->get('carpro_deposit_title'); 
				if(WC()->session->get('carpro_deposit_amount')):
					$_DEP_VALUE = WC()->session->get('carpro_deposit_amount'); 
				else:
					$_DEP_VALUE = 0;
				endif;
			?>

				<?php $_TOTAL_TITLE = 'Balance Due'; ?>

				<div class="row no-gutters fee fee-<?php echo sanitize_title('Rental Amount'); ?>">
					<div class="col-12 col-sm-6"><strong><?php echo esc_html( 'Rental Amount' ); ?></strong>:</div>
					<div class="col-12 col-sm-6"><?php echo wc_price(CARPRO_HELPERS::GET_CART_TOTAL()); ?></div>
				</div>
				<div class="row no-gutters fee fee-<?php echo sanitize_title($_DEP_TITLE); ?>">
					<div class="col-12 col-sm-6"><strong><?php echo esc_html( $_DEP_TITLE ); ?></strong>:</div>
					<div class="col-12 col-sm-6"><?php echo wc_price( $_DEP_VALUE ); ?></div>
				</div>
				
		<?php else: ?>

			<?php $_TOTAL_TITLE = 'Rental Amount'; ?>

		<?php endif; ?>

		

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="row no-gutters cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<div class="col-12 col-sm-6"><strong><?php wc_cart_totals_coupon_label( $coupon ); ?></strong>:</div>
				<div class="col-12 col-sm-6"><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
			</div>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<div class="row no-gutters tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<div class="col-12 col-sm-6"><strong><?php echo esc_html( $tax->label ); ?></strong>:</div>
						<div class="col-12 col-sm-6"><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="row no-gutters tax-total">
					<div class="col-12 col-sm-6"><strong><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></strong>:</div>
					<div class="col-12 col-sm-6"><?php wc_cart_totals_taxes_total_html(); ?></div>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<?php $_BALANCE_DUE = (float)$_CART_AMOUNT - (float)$_DEP_VALUE; ?>

		<div class="row no-gutters order-total">
			<div class="col-12 col-sm-6"><strong><?php esc_html_e( $_TOTAL_TITLE, 'woocommerce' ); ?></strong>:</div>
			<div class="col-12 col-sm-6"><?php echo wc_price($_BALANCE_DUE); ?></div>
		</div>

		

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</div>
</div>