<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}


$_THE_OBJECT = get_post($product->get_id());
$_SKU = $product->get_sku();
$_DAYS = WC()->session->get('carpro_days');


$_COLUMN_CLASS = 'col-lg-6';
$_LI_CLASS = '';

if(is_product_category() || is_shop()):
	$_DO_MINIMAL = true;
	$_COLUMN_CLASS = '';
	$_LI_CLASS = 'vehicle_listing_only';
endif;


?>
<li <?php wc_product_class( $_LI_CLASS, $product ); ?>>
	<div class="vehicle-item-container">
	<div class="row no-gutters">
		<div class="<?php echo $_COLUMN_CLASS; ?> col-12 vehicle-border">
			<div class="vehicle_title">
				<span class="vehicle_group"><span class="group">GROUP</span><span class="actual"><?php echo $_SKU; ?></span> <span class="vehicle_name"><h2><?php the_title(); ?></h2></span>  
			</div>
			<div class="vehicle_image">
				<img src="<?php the_field('image', $_THE_OBJECT); ?>" />
				<?php do_action('NL_CARPRO_ACTION_AFTER_IMAGE', $_THE_OBJECT); ?>
			</div>
			<div class="vehicle_data MOBILESHOW">
				<div class="row text-center">
					<div class="col block-item">
						<span class="vehicle_data_transmission"><?php the_field('transmission', $_THE_OBJECT); ?></span>
					</div>
					<?php if(get_field('seats', $_THE_OBJECT)): ?>
						<div class="col block-item">
							<span class="vehicle_data_seats"><?php the_field('seats', $_THE_OBJECT); ?> seats</span>
						</div>
					<?php endif; ?>
					<?php if(get_field('air_conditioning', $_THE_OBJECT)): ?>
					<div class="col block-item">
						<span class="vehicle_data_aircon">Aircon</span>
					</div>
					<?php endif; ?>
					<?php if(get_field('airbags', $_THE_OBJECT)): ?>
					<div class="col block-item">
						<span class="vehicle_data_airbags">Airbags</span>
					</div>
					<?php endif; ?>
					<div class="col block-item">
						<span class="vehicle_data_fuel"><?php the_field('fuel', $_THE_OBJECT); ?></span>
					</div>
				</div>
			</div>			
		</div>
		<?php if(!$_DO_MINIMAL): ?>
			<div class="col-lg-6 col-12">
				<div class="vehicle_rates">
					<?php CARPRO_HELPERS::VEHICLE_RATE_OPTIONS(); ?>
				</div>			
			</div>
		<?php endif; ?>
	</div>

	<div class="row no-gutters align-items-end ">
		<div class="<?php echo $_COLUMN_CLASS; ?> col-12 vehicle-border MOBILEHIDE">
			
			<?php if(!$_DO_MINIMAL): ?>
				<div class="vehicle_text vehicle_group_text ">
					<?php echo CARPRO_HELPERS::VEHICLE_TEXT($_SKU); ?>
				</div>
			<?php endif; ?>

			<div class="vehicle_data">
				<div class="row text-center">
					<div class="col block-item">
						<span class="vehicle_data_transmission"><?php the_field('transmission', $_THE_OBJECT); ?></span>
					</div>
					<?php if(get_field('seats', $_THE_OBJECT)): ?>
						<div class="col block-item">
							<span class="vehicle_data_seats"><?php the_field('seats', $_THE_OBJECT); ?> seats</span>
						</div>
					<?php endif; ?>
					<?php if(get_field('air_conditioning', $_THE_OBJECT)): ?>
					<div class="col block-item">
						<span class="vehicle_data_aircon">Aircon</span>
					</div>
					<?php endif; ?>
					<?php if(get_field('airbags', $_THE_OBJECT)): ?>
					<div class="col block-item">
						<span class="vehicle_data_airbags">Airbags</span>
					</div>
					<?php endif; ?>
					<div class="col block-item">
						<span class="vehicle_data_fuel"><?php the_field('fuel', $_THE_OBJECT); ?></span>
					</div>
				</div>
			</div>
		</div>

		<?php if(!$_DO_MINIMAL): ?>

			<?php $_DAY_TEXT = ' days'; if($_DAYS == 1): $_DAY_TEXT = ' day'; endif; ?>

			<div class="col-lg-6 col-12">
				<div class="vehicle_actions">
					<div class="row no-gutters">
					<div class="col-lg-8 col-sm-6 col-12 align-right"><span class="vehicle_price"><?php woocommerce_template_loop_price(); ?></span> <span class="vehicle_length">for <?php echo $_DAYS.$_DAY_TEXT; ?></span></div>
					<div class="col-lg-4 col-sm-6  col-12"><?php woocommerce_template_loop_add_to_cart(); ?></div>
					</div>
				</div>
			</div>

			<div class="col-12 MOBILESHOW">
				<div class="vehicle_text vehicle_group_text">
					<?php echo CARPRO_HELPERS::VEHICLE_TEXT($_SKU); ?>
				</div>
			</div>

		<?php endif; ?>
	</div>


	<?php CARPRO_HELPERS::DEBUG_RATES($_SKU); ?>

	</div>
	
</li>
