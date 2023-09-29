<?php


class CARPRO_HELPERS{









	public static function DEBUG_RATES($_SKU){


		if(get_field('carpro_debug_rates', 'option') && current_user_can('administrator')): ?>

			<div class="DEBUG_VEHICLE_RATES_BLOCK">
				
				<?php foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

					if(trim($_SKU) == trim($_CODE)):

						$_V = $_DATA['vehicle'];

						?>

						<div class="row">
							<div class="col-md-4"><strong>Days:</strong> <?php echo $_V['days']; ?></div>
							<div class="col-md-4"><strong>Tariff:</strong> <?php echo $_V['tariff']; ?></div>
							<div class="col-md-4"><strong>International:</strong> <?php echo $_V['international']; ?></div>
						</div>

						<?php foreach($_V['rates'] as $_KM => $_R): ?>

							<div class="row">
								<div class="col-12 text-center"><h4><?php echo $_KM; ?>KM</h4></div>
								<div class="col-md-3"><strong>RateName:</strong> <?php echo $_R['ratename']; ?></div>
								<div class="col-md-3"><strong>Package:</strong> <?php echo $_R['package']; ?></div>
								<div class="col-md-3"><strong>KM Price:</strong> <?php echo $_R['kmprice']; ?></div>
								<div class="col-md-3"><strong>Days:</strong> <?php echo $_R['days']; ?></div>
								<div class="col-md-3"><strong>Rental:</strong> <?php echo $_R['rental']; ?></div>
								<div class="col-md-3"><strong>Airport:</strong> <?php echo $_R['airport']; ?></div>
								<div class="col-md-3"><strong>Weekend:</strong> <?php echo $_R['weekend']; ?></div>
								<div class="col-md-3"><strong>Dropoff:</strong> <?php echo $_R['dropoff']; ?></div>

								<div class="col-12">
									<?php foreach($_R['rates'] as $_RATE): ?>
										<div class="row RATE_ROW">
											<div class="col-12 text-center"><h6>(<?php echo $_RATE['code'].') '.$_RATE['title']; ?></h6></div>
											<div class="col-md-4"><strong>RateNo:</strong> <?php echo $_RATE['rateno']; ?></div>
											<div class="col-md-4"><strong>RateSrNo:</strong> <?php echo $_RATE['ratesrno']; ?></div>
											<div class="col-md-4"><strong>Per Day:</strong> <?php echo $_RATE['pd']; ?></div>
											<div class="col-md-4"><strong>Total:</strong> <?php echo $_RATE['total']; ?></div>
											<div class="col-md-4"><strong>Deposit:</strong> <?php echo $_RATE['deposit']; ?></div>
											<div class="col-md-4"><strong>Liability:</strong> <?php echo $_RATE['liability']; ?></div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>

						<?php endforeach; ?>

						<?php
					endif;
				endforeach; ?>

			</div>

		<?php endif;


	}









	/* SEARCH RESULTS PAGE */
	public static function IS_SEARCH_RESULTS($_OBJECT_ID = false){

		$_SEARCH = get_field('search_results_page', 'option');

		if($_OBJECT_ID):
			$_QUERY = get_post($_OBJECT_ID);
		else:
			$_QUERY = get_queried_object();
		endif;

		if(is_a($_QUERY, 'WP_Post') && $_QUERY->post_type == 'page'):

			$_CHECK = get_permalink($_QUERY);

			if($_CHECK == $_SEARCH):
				return true;
			endif;

		endif;

		return false;

	}








	/* GET ORDER TOTAL */
	public static function GET_ORDER_TOTAL($_ORDER){


		$_TOTAL = number_format($_ORDER->get_subtotal(), 2, ".", "");

		$_FEES = $_ORDER->get_fees();

		$_FEE_AMT = 0;

		foreach($_FEES as $_FEE):

			$_AMT = $_FEE->get_total();

			if($_AMT > 0):
				$_FEE_AMT += $_AMT;
			endif;

		endforeach;

		return $_TOTAL+$_FEE_AMT;


	}








	/* GET ORDER TOTAL */
	public static function GET_CART_TOTAL(){

		$_TOTAL = number_format(WC()->cart->subtotal, 2, ".", "");

		$_FEE_AMT = 0;

		foreach(WC()->cart->get_fees() as $_FEE):
			
			$_AMT = $_FEE->total;

			if($_AMT > 0):
				$_FEE_AMT += $_AMT;
			endif;
			
		endforeach;

		return $_TOTAL+$_FEE_AMT;


	}









	/* IS VEHICLE ENABLED - SO THAT WE CAN SHOW IT */
	public static function IS_AVAILABLE($_CODE){

		$_ARGS = array(
			'post_type' => 'product',
			'posts_per_page' => '1',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'vehicle_code',
					'value' => $_CODE
				),
				array(
					'key' => 'enabled',
					'value' => 1
				),
			),
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);

		$_AVAILABLE = array();


		$_VEHICLES = get_posts($_ARGS);

		if(count($_VEHICLES) == 1):
			$_VEHICLES = reset($_VEHICLES);
			return $_VEHICLES;
		endif;

		return false;


		
	}









	/* FETCH BRANCHES */
	public static function BRANCH_SELECT(){

		$_PAGE_LIMITATIONS = get_field('limit_branches_on_specific_pages', 'option');

		$_LIMITATIONS = array();

		if(is_array($_PAGE_LIMITATIONS) && count($_PAGE_LIMITATIONS) > 0 && is_singular('page')):

			$_PAGE_ID = get_queried_object_id();

			foreach($_PAGE_LIMITATIONS as $_PL):

				if((int)$_PL['page'] == (int)$_PAGE_ID):
					$_LIMITATIONS = array_merge($_LIMITATIONS, $_PL['branches']);
				endif;

			endforeach;

		endif;
	
		$_ARGS = array(
			'post_type' => 'branch',
			'posts_per_page' => '-1',
			'meta_key' => 'enabled',
			'meta_value' => 1,
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);	

		if(count($_LIMITATIONS) > 0):
			$_ARGS['post__in'] = $_LIMITATIONS;
		endif;

		$_BRANCHES = get_posts($_ARGS);

		$_SELECTION = array();

		foreach($_BRANCHES as $_BRANCH):

			$_CODE = get_field('carpro_branch_code', $_BRANCH);
			$_TITLE = $_BRANCH->post_title;

			$_PROVINCE_ON_BRANCH = wp_get_post_terms($_BRANCH->ID, 'province');
			$_PROVINCE = reset($_PROVINCE_ON_BRANCH);
			$_PROVINCE = $_PROVINCE->name;

			$_SELECTION[$_PROVINCE][$_CODE] = $_TITLE;

		endforeach;

		return $_SELECTION;

	}









	/* BRANCH TIMES */
	public static function BRANCH_TIMES_SELECT($_CODE, $_DATE, $_DAY){

		$_BRANCH = self::GET_BRANCH_FROM_CODE($_CODE);

		if(self::IS_PUBLIC_HOLIDAY($_DATE)):

			$_TIMES = get_field('public_holidays', $_BRANCH);

		else:

			$_TIMES = get_field($_DAY, $_BRANCH);

		endif;

		$_DO_HOUR_LIMIT = false;

		$_LIMIT_NOW = false;
		$_DATE = date('Y-m-d', strtotime($_DATE));

		if($_DATE == date('Y-m-d')):
			$_DO_HOUR_LIMIT = true;
			$_LIMIT = (int)get_field('booking_lead_hours', 'option');
			$_LIMIT_NOW = wp_date("H:i", strtotime("+".$_LIMIT." hours"));
		endif;

		/* CLOSED / BY APPOINTEMENT */
		if(strtolower($_TIMES) == 'closed'):
			return array('' => $_TIMES);
		elseif(strtolower($_TIMES) == 'by appointment'):
			return array('' => $_TIMES);
		else:

			$_TIMES = explode('-', $_TIMES);

			$_START_TIME    = $_TIMES[0];
			$_END_TIME 	    = $_TIMES[1];
			$_START_TIME    = strtotime ($_START_TIME); //change to strtotime
			$_END_TIME      = strtotime ($_END_TIME);

			//30 minutes
			$_ADD = 1800;

			$_SELECTION = array();

			while($_START_TIME <= $_END_TIME):
				$_TIME = date ("H:i", $_START_TIME);

				if($_DO_HOUR_LIMIT):

					if($_TIME >= $_LIMIT_NOW):
						$_SELECTION[$_TIME] = $_TIME;
					endif;

				else:
					$_SELECTION[$_TIME] = $_TIME;
				endif;
	   			$_START_TIME += $_ADD; 

			endwhile;

			return $_SELECTION;

		endif;

	}









	/* IS PARTICULAR DAY A PUBLIC HOLIDAY */
	public static function IS_PUBLIC_HOLIDAY($_DATE){


		$_PH = get_posts(
			array(
				'post_type' => 'publicholiday',
				'posts_per_page' => 1,
				'meta_key' => 'date',
				'meta_value' => wp_date('Y-m-d', strtotime($_DATE))
			)
		);

		if(count($_PH) >= 1):
			return true;
		endif;

		return false;

	}









	/* GET BRANCH FROM CODE */
	public static function GET_BRANCH_FROM_CODE($_CODE){

		$_BRANCH = get_posts(
			array(
				'post_type' => 'branch',
				'posts_per_page' => 1,
				'meta_key' => 'carpro_branch_code',
				'meta_value' => $_CODE
			)
		);

		if(count($_BRANCH) == 1):
			return reset($_BRANCH);
		endif;

		return false;

	}









	/* GET VEHICLE TEXT */
	public static function VEHICLE_TEXT($_SKU){

		$_TEXT = array();
		$_STANDALONE = array();
		$_EXCLUDES = array();

		$_POST_ITEM = false;

		if(WC()->session->get('carpro_availability')):
			foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

				if(trim($_SKU) == trim($_CODE)):

					$_POST_ITEM     = $_DATA['post'];

					$_EXTRAS_ONCE = array();
					$_EXTRAS_DAILY = array();
					$_FEES = array();

					if(isset($_DATA['vehicle']['extras']['once']) && is_array($_DATA['vehicle']['extras']['once'])):
						$_EXTRAS_ONCE 	= $_DATA['vehicle']['extras']['once'];
					endif;

					if(isset($_DATA['vehicle']['extras']['daily']) && is_array($_DATA['vehicle']['extras']['daily'])):
						$_EXTRAS_DAILY 	= $_DATA['vehicle']['extras']['daily'];
					endif;

					if(isset($_DATA['vehicle']['fees']) && is_array($_DATA['vehicle']['fees'])):
						$_FEES 	= $_DATA['vehicle']['fees'];
					endif;

					foreach(get_field('vehicle_text_builder', 'option') as $_VTB):

						foreach($_FEES as $_CODE => $_EXTRA):

							if(trim(strtolower($_VTB['code'])) == trim(strtolower($_CODE))):
								$_VALUE = $_EXTRA['amt'];

								if($_VTB['type'] == 'excludes'):
									$_EXCLUDES[] = str_replace('{{VALUE}}', wc_price($_VALUE), $_VTB['text']);
								else:
									$_STANDALONE[] = str_replace('{{VALUE}}', wc_price($_VALUE), $_VTB['text']);
								endif;
							endif;

						endforeach;

						foreach($_EXTRAS_ONCE as $_CODE => $_EXTRA):

							if(trim(strtolower($_VTB['code'])) == trim(strtolower($_CODE))):
								$_VALUE = $_EXTRA['total'];

								if($_VTB['type'] == 'excludes'):
									$_EXCLUDES[] = str_replace('{{VALUE}}', wc_price($_VALUE), $_VTB['text']);
								else:
									$_STANDALONE[] = str_replace('{{VALUE}}', wc_price($_VALUE), $_VTB['text']);
								endif;
							endif;

						endforeach;

						foreach($_EXTRAS_DAILY as $_CODE => $_EXTRA):

							if(trim(strtolower($_VTB['code'])) == trim(strtolower($_CODE))):
								$_VALUE = $_EXTRA['total'];

								if($_VTB['type'] == 'excludes'):
									$_EXCLUDES[] = str_replace('{{VALUE}}', wc_price($_VALUE), $_VTB['text']);
								else:
									$_STANDALONE[] = str_replace('{{VALUE}}', wc_price($_VALUE), $_VTB['text']);
								endif;
							endif;

						endforeach;

					endforeach;

					$_FIRST_RATE = reset($_DATA['vehicle']['rates']);

					if(isset($_FIRST_RATE['dropoff']) && $_FIRST_RATE['dropoff'] > 0):
						$_EXCLUDES[] = "Once off One Way Fee (".wc_price($_FIRST_RATE['dropoff']).")";
					endif;

					if(count($_EXCLUDES) > 0):

						$_TEXT[] = "<strong>Excludes:</strong> ".implode("; ", $_EXCLUDES).'.';

					endif;

					if(count($_STANDALONE) > 0):

						$_TEXT[] = implode(". ", $_STANDALONE).'.';

					endif;

				endif;

			endforeach;
		endif;

		if(get_field('custom_includesexcludes',$_POST_ITEM)):
			$_TEXT[] = get_field('custom_includesexcludes', $_POST_ITEM);
		elseif(get_field('vehicle_text', 'option')):
			$_TEXT[] = get_field('vehicle_text', 'option');
		endif;

		return implode(" ", $_TEXT);
	}









	/* GET VEHICLE DATA */
	public static function VEHICLE_DATA($_SKU){

		if(WC()->session->get('carpro_availability')):
			foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

				if(trim($_SKU) == trim($_CODE)):
					return $_DATA['vehicle'];

				endif;

			endforeach;
		endif;

		return false;
	}









	/* GET FIRST RATE FOR THRIFTY */
	public static function VEHICLE_FIRST_RATE($_SKU){

		if(WC()->session->get('carpro_availability')):
			foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

				if(trim($_SKU) == trim($_CODE)):

					$_FIRST_RATE = reset($_DATA['vehicle']['rates']);
					$_THE_RATE = reset($_FIRST_RATE['rates']);

					$_COUNT = 0;

					foreach($_DATA['vehicle']['rates'] as $_KM => $_RATE):

						if($_COUNT == 0):
							$_THE_RATE['owf'] = $_RATE['dropoff'];
							$_THE_RATE['km'] = $_KM;
						endif;

						$_COUNT++;

					endforeach;

					return $_THE_RATE;

				endif;

			endforeach;
		endif;
	}









	/* GET VEHICLE INTERNATIONAL CODE */
	public static function VEHICLE_CODE($_SKU){

		if(WC()->session->get('carpro_availability')):
			foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

				if(trim($_SKU) == trim($_CODE)):

					return $_DATA['vehicle']['international'];

				endif;

			endforeach;
		endif;
	}









	/* BUILD RATE OPTIONS */
	public static function VEHICLE_RATE_OPTIONS(){
		global $product;

		$_VEHICLE_CODE = $product->get_sku();

		if(isset(WC()->session) && WC()->session->get('carpro_availability')):

			$_DAYS = WC()->session->get('carpro_days');

			foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

				if(trim($_VEHICLE_CODE) == trim($_CODE)):

					$_COUNTER = 0;

					$_FIRST_RATE = false;

					//echo '<pre>'; print_r($_DATA['vehicle']); echo '</pre>';

					?> <form class="carproVehicleForm"> <?php
					$_RATES = $_DATA['vehicle']['rates'];
					
					foreach($_RATES as $_KM => $_INFO):

						$_OWF = $_INFO['dropoff'];

						$_KM_RATES = $_INFO['rates'];

						?>
						<div class="container carproVehicleRateBlock">
							<div class="row">
								<div class="col-md-4 mobile-center"><h4><?php echo $_KM; ?></h4> per day</div>
							
							
							<?php foreach($_KM_RATES as $_KMR): ?>
								<div class="col-md-4 col-6 jsRateBlock">
									<div class="mobile-width-center">
										<div class="carproInputItem">

										<label class="radio">

										<input name="<?php echo $_VEHICLE_CODE; ?>_rate_choice" class="carproVehicleRateChoice <?php if($_COUNTER == 0): ?> FIRSTRATEITEM <?php endif; ?>" <?php if($_COUNTER == 0): $_FIRST_RATE = $_KMR; ?> checked <?php endif; ?> 
										data-id="<?php echo $product->get_id(); ?>" 
										data-code="<?php echo $_KMR['code']; ?>" 
										data-total="<?php echo $_KMR['total']; ?>" 
										data-pd="<?php echo get_woocommerce_currency_symbol().number_format($_KMR['pd'], 2, ".", ","); ?>" 
										data-km="<?php echo $_KM; ?>"
										data-owf="<?php echo $_OWF; ?>" 
										type="radio" /> <?php echo $_KMR['title']; ?>
										</div>
										

											
											
										</label>

										<small>Liability: </small><?php echo wc_price($_KMR['liability']); ?>
										<div class="carproPriceItem hidden"><?php echo wc_price($_KMR['total']);?></div>
									</div>
								</div>

								<?php $_COUNTER++; ?>
							<?php endforeach; ?>

							</div>
						</div>
						<?php

					endforeach;

					?> </form> 

					<div class="row vehicle_search_action_row">

						<div class="col-12">
							<div class="carproVehicleRateInformation">
								<span class="carpro_perday"><span class="value"><?php echo wc_price($_FIRST_RATE['pd']); ?></span> <span class="text">per day</span></span>
							</div>
						</div>

					<?php

				endif;

			endforeach;

		endif;

	}









	/* CLEAR ALL THE CARPRO STUFF FROM THE SESSION */
	public static function CLEAR_CARPRO(){

		if(isset(WC()->session)):

			WC()->session->__unset('carpro_out_branch');
			WC()->session->__unset('carpro_out_date');
			WC()->session->__unset('carpro_out_time');
			WC()->session->__unset('carpro_in_branch');
			WC()->session->__unset('carpro_in_date');
			WC()->session->__unset('carpro_in_time');
			WC()->session->__unset('carpro_search_start');
			WC()->session->__unset('carpro_availability');
			WC()->session->__unset('carpro_includes');
			WC()->session->__unset('carpro_days');
			WC()->session->__unset('carpro_selected_vehicle');
			WC()->session->__unset('carpro_selected_sku');
			WC()->session->__unset('carpro_selected_km');
			WC()->session->__unset('carpro_selected_code');
			WC()->session->__unset('carpro_selected_rate');
			WC()->session->__unset('carpro_available_extras_daily');
			WC()->session->__unset('carpro_available_extras_once');
			WC()->session->__unset('carpro_fees');		
			WC()->session->__unset('carpro_deposit_percentage');
			WC()->session->__unset('carpro_deposit_amount');
			WC()->session->__unset('carpro_deposit_type');
			WC()->session->__unset('carpro_available_extras');
			WC()->session->__unset('carpro_selected_once_extras');
			WC()->session->__unset('carpro_selected_daily_extras');		
			WC()->session->__unset('carpro_nothing_found');
			WC()->session->__unset('carpro_one_way_fee');
			WC()->session->__unset('carpro_billing_id_type');
			WC()->session->__unset('carpro_billing_id_value');
			WC()->session->__unset('carpro_license_number');
			WC()->session->__unset('carpro_license_expiry');
			WC()->session->__unset('carpro_arrival_flight_number');
			WC()->session->__unset('carpro_payment_type');

			WC()->cart->empty_cart();

			do_action('NL_CARPRO_ACTION_CLEAR_CARPRO');
			
		endif;



	}









	/* CLEAR ALL THE CARPRO STUFF FROM THE SESSION */
	public static function CLEAR_CART(){

		if(!is_admin()):

			WC()->session->__unset('carpro_selected_vehicle');
			WC()->session->__unset('carpro_selected_sku');
			WC()->session->__unset('carpro_selected_km');
			WC()->session->__unset('carpro_selected_code');
			WC()->session->__unset('carpro_selected_rate');
			WC()->session->__unset('carpro_selected_once_extras');
			WC()->session->__unset('carpro_selected_daily_extras');	

			do_action('NL_CARPRO_ACTION_CLEAR_CART');

		endif;	

	}









	/* CHECK SESSION IF AN EXTRA HAS BEEN SELECTED */
	public static function ISEXTRASELECTED($_KEY){

		$_SELECTED = 0;

		if(WC()->session->get('carpro_selected_once_extras')):

			$_EXTRAS = WC()->session->get('carpro_selected_once_extras');
			foreach($_EXTRAS as $_CC => $_DATA):

				if(strtoupper($_KEY) == strtoupper($_CC)):
					$_SELECTED = 1;
				endif;

			endforeach;

		endif;

		if(WC()->session->get('carpro_selected_daily_extras')):
			$_EXTRAS = WC()->session->get('carpro_selected_daily_extras');
			foreach($_EXTRAS as $_CC => $_DATA):

				if(strtoupper($_KEY) == strtoupper($_CC)):
					$_SELECTED = 1;
				endif;

			endforeach;
		endif;


		return $_SELECTED;

	}









	/* CUSTOM BRANCH SORT */
	public static function CUSTOM_BRANCH_SORT(){

		$_PICKUP_BRANCH = WC()->session->get('carpro_out_branch');

		$_THE_BRANCH = self::GET_BRANCH_FROM_CODE($_PICKUP_BRANCH);

		$_LIMITATIONS = get_field('branch_ordering', 'option');

		if(is_array($_LIMITATIONS) && count($_LIMITATIONS) > 0):

			foreach($_LIMITATIONS as $_LIMIT):

				if(in_array($_THE_BRANCH->ID, $_LIMIT['branch'])):

					return $_LIMIT['ordering'];

				endif;

			endforeach;

		endif;


		return false;


	}









	/* BRANCH CUSTOM EMAIL CONTENT */
	public static function BRANCH_CUSTOM_EMAIL_CONTENT($_ORDER){

		$_THE_ITEM_TO_RETURN = false;

		if(get_post_meta($_ORDER->get_id(), 'carpro_out_branch', true)):

			$_BRANCH_CODE = get_post_meta($_ORDER->get_id(), 'carpro_out_branch', true);
			$_BRANCH_OBJ = self::GET_BRANCH_FROM_CODE($_BRANCH_CODE);

			$_OPTIONS = get_field('branch_custom_email_content','option');

			foreach($_OPTIONS as $_OPT):

				 if(is_a($_OPT['branch'], 'WP_Post')):

				    if($_OPT['branch']->ID == $_BRANCH_OBJ->ID):
				      $_THE_ITEM_TO_RETURN = $_OPT['content'];
				    endif;

				  else:

				    if((int)$_OPT['branch'] == $_BRANCH_OBJ->ID):
				      $_THE_ITEM_TO_RETURN = $_OPT['content'];
				    endif;

				  endif;

			endforeach;

		endif;

		return $_THE_ITEM_TO_RETURN;
	}









	/* BRANCH CUSTOM EMAIL RECIPIENT */
	public static function BRANCH_CUSTOM_EMAIL_RECIPIENT($_ORDER){

		$_THE_ITEM_TO_RETURN = false;

		if(get_post_meta($_ORDER->get_id(), 'carpro_out_branch', true)):

			$_BRANCH_CODE = get_post_meta($_ORDER->get_id(), 'carpro_out_branch', true);
			$_BRANCH_OBJ = self::GET_BRANCH_FROM_CODE($_BRANCH_CODE);

			$_OPTIONS = get_field('branch_custom_email_recipients','option');

			foreach($_OPTIONS as $_OPT):

				if(is_a($_OPT['branch'], 'WP_Post')):

				    if($_OPT['branch']->ID == $_BRANCH_OBJ->ID):
				      $_THE_ITEM_TO_RETURN = $_OPT['email'];
				    endif;

				  else:

				    if((int)$_OPT['branch'] == $_BRANCH_OBJ->ID):
				      $_THE_ITEM_TO_RETURN = $_OPT['email'];
				    endif;

				  endif;

			endforeach;

		endif;

		return $_THE_ITEM_TO_RETURN;
	}



}



?>