<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>





<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>



		<div id="checkoutTabs" class="row">

			  <div class="col-4 checkoutTabListItem">
			  	<a data-tab="1" data-back="no" data-next="yes" class="FIRSTCHECKOUTAB checkoutTabLink" id="checkoutNavOptions" href="#checkoutTabOptions">Options</a>
			  </div>

			  <div class="col-4 checkoutTabListItem">
			  	<a data-tab="2" data-back="yes" data-next="yes" class="checkoutTabLink" id="checkoutNavDetails" href="#checkoutTabDetails">Details</a>
			  </div>

			  <div class="col-4 checkoutTabListItem">
			  	<a data-tab="3" data-back="yes" data-next="no" class="checkoutTabLink" id="checkoutNavPayment" href="#checkoutTabPayment">Payment</a>
			  </div>

		</div>

		<div class="row no-gutters CHECKOUTCONTAINER">

			<div class="col-12 nextleveltimercontianer">
				<?php echo do_shortcode('[carpro_timer]'); ?>
			</div>
			
			<div class="checkout-left-column col-md-7 col-sm-6 col-12">

				<div id="checkoutContentTabs"  class="tab-content">

					<div class="checkoutTab" id="checkoutTabOptions">

						<div class="checkoutHeading"><h3>OPTIONS</h3></div>
						
						<?php do_action( 'carpro_extra_option_fields', $checkout ); ?>

						<?php do_action( 'carpro_extra_sections'); ?>

					</div>

					<div class="checkoutTab" id="checkoutTabDetails">

						<div class="checkoutHeading"><h3>DETAILS</h3></div>
						
						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

						<div class="col2-set mobilecheckoutblock" id="customer_details">
							<div class="col-1">
								<?php do_action( 'woocommerce_checkout_billing' ); ?>								
							</div>
						</div>

						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

						<?php do_action( 'carpro_extra_detail_fields', $checkout ); ?>

					</div>

					<div class="checkoutTab" id="checkoutTabPayment">

						<div class="checkoutHeading"><h3>PAYMENT</h3></div>
						
						<?php do_action('carpro_extra_payment_fields', $checkout ); ?>

						<?php do_action('woocommerce_credit_card_details'); ?>

						<?php do_action('carpro_before_payment'); ?>
						
						<?php woocommerce_checkout_payment(); ?>

					</div>

				</div>
				
			</div>


			<div class="checkout-right-column col-md-5 col-sm-6 col-12">
				<div class="checkoutReview">
				<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

				<div class="checkoutHeading"><h3>YOUR RENTAL</h3></div>
				
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				</div>
			</div>


		</div>

		

		<div id="checkoutNavigation">
			<div class="row">
				
				<div class="col-6 textleft">
					<a class="checkoutNavButton" id="checkoutPrevious">Back</a>
				</div>
				<div class="col-6 textright">
					<a class="checkoutNavButton" id="checkoutNext">Next</a>
				</div>

			</div>
		</div>

		

	<?php endif; ?>
	
	

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
