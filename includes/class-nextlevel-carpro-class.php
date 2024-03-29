<?php


class CARPRO{










	/*
	DO CALL TO API
	*/
	public static function DOCALL($_ENDPOINT, $_PARAMS = array()){

		$_URL = get_field('carpro_nextlevel_url','option');
		$_KEY = get_field('carpro_nextlevel_key','option');

		$_URL = trailingslashit(trailingslashit($_URL).$_ENDPOINT);

		//echo '<pre>'; print_r($_URL); echo '</pre>';

		$_ARGS = array(
		    'method' => 'GET',
		    'timeout' => 6000,
		    'headers' => array(
		        'Content-Type' => 'application/json',
		        'Accept' => 'application/json',
		        'THEONERINGTORULETHEMALL' => $_KEY
		    )
		);

		if(count($_PARAMS) > 0):
			$_ARGS['method'] = 'POST';
			$_ARGS['body'] = json_encode($_PARAMS);
		endif;

		$_RETURN = wp_remote_post($_URL, $_ARGS);

		$_RESPONSE = json_decode($_RETURN['body'], true);

		return $_RESPONSE;


	}










	/*
	DO RESERVATION
	*/
	public static function DORESERVATION($_ORDER_ID){

		$_ENDPOINT = 'carpro/reservation';

		$_IS_ACTUALLY_INSURANCE = array( 'TWC');

		/* GET BASE INFORMATION */
		$_ORDER = new WC_Order($_ORDER_ID);
		$_OBJ = get_post($_ORDER_ID);

		$_RATE = get_post_meta($_ORDER_ID, 'carpro_selected_rate', true);
		$_RATE = maybe_unserialize($_RATE);

		$_SKU = false;

		foreach($_ORDER->get_items() as $_ID => $_ITEM):

			if($_ITEM['variation_id'] > 0 ):
				$_PRODUCT_ID = $_ITEM['variation_id'];
			else:
				$_PRODUCT_ID = $_ITEM['product_id'];
			endif;

			$_PROD = wc_get_product($_PRODUCT_ID);

			$_SKU = $_PROD->get_sku();

		endforeach;

		$_PARAMS = array();

		$_PARAMS['CarGroup']			= get_post_meta($_ORDER_ID, 'carpro_selected_vehicle', true);
        $_PARAMS['FirstName'] 			= $_ORDER->get_billing_first_name();
		$_PARAMS['LastName'] 			= $_ORDER->get_billing_last_name();
        $_PARAMS['PostCode'] 			= $_ORDER->get_billing_postcode();
        $_PARAMS['Address'] 			= $_ORDER->get_billing_address_1();
        $_PARAMS['District'] 			= $_ORDER->get_billing_address_2();
        $_PARAMS['City'] 				= $_ORDER->get_billing_city();
        $_PARAMS['phoneno'] 			= $_ORDER->get_billing_phone();
        $_PARAMS['EmailID'] 			= $_ORDER->get_billing_email();
        $_PARAMS['Country'] 			= $_ORDER->get_billing_country();
        $_PARAMS['OutBranch'] 			= get_post_meta($_ORDER_ID, 'carpro_out_branch', true);
        $_PARAMS['InBranch'] 			= get_post_meta($_ORDER_ID, 'carpro_in_branch', true);
        $_PARAMS['OutDate'] 			= get_post_meta($_ORDER_ID, 'carpro_out_date', true);
        $_PARAMS['OutTime'] 			= get_post_meta($_ORDER_ID, 'carpro_out_time', true);
        $_PARAMS['InDate'] 				= get_post_meta($_ORDER_ID, 'carpro_in_date', true);
        $_PARAMS['InTime'] 				= get_post_meta($_ORDER_ID, 'carpro_in_time', true);
        $_PARAMS['RateNo'] 				= $_RATE['rateno'];
		$_PARAMS['RateSrNo'] 			= $_RATE['ratesrno'];
        $_PARAMS['RentalSum'] 			= CARPRO_HELPERS::GET_ORDER_TOTAL($_ORDER);
        $_PARAMS['ReservationNo'] 		= $_ORDER_ID;
        $_PARAMS['ReservationStatus'] 	= 'N';
        $_PARAMS['DrvPhone'] 			= $_ORDER->get_billing_phone();
        $_PARAMS['DrvEmail'] 			= $_ORDER->get_billing_email();
        $_PARAMS['SelectedKM']			= get_post_meta($_ORDER_ID, 'carpro_selected_km', true);
        $_PARAMS['SelectedCover']		= get_post_meta($_ORDER_ID, 'carpro_selected_code', true);
        $_PARAMS['SelectedSKU']			= $_SKU;

        if(get_field('custom_field_key_for_credit_card_number', 'option')):
        	$_FIELD = get_field('custom_field_key_for_credit_card_number', 'option');
	        if(get_post_meta($_ORDER_ID, $_FIELD, true)):
				$_PARAMS['CreditCardNo'] = get_post_meta($_ORDER_ID, $_FIELD, true);
				delete_post_meta($_ORDER_ID, $_FIELD);
	        endif;
	    endif;

	    if(get_field('custom_field_key_for_credit_card_auth_code', 'option')):
        	$_FIELD = get_field('custom_field_key_for_credit_card_auth_code', 'option');
	        if(get_post_meta($_ORDER_ID, $_FIELD, true)):
				$_PARAMS['AuthCode'] = get_post_meta($_ORDER_ID, $_FIELD, true);
				delete_post_meta($_ORDER_ID, $_FIELD);
	        endif;
	    endif;

        if(get_field('custom_field_key_for_credit_card_number', 'option') && get_field('custom_field_key_for_credit_card_number', 'option')):

        	$_FIELD_one = get_field('custom_field_key_for_credit_card_expiry_month', 'option');
        	$_FIELD_two = get_field('custom_field_key_for_credit_card_expiry_year', 'option');

	        if(get_post_meta($_ORDER_ID, $_FIELD_one, true) 
	        	&& get_post_meta($_ORDER_ID, $_FIELD_two, true)):
				$_PARAMS['CreditCardExpiry'] = trim(get_post_meta($_ORDER_ID, $_FIELD_one, true)).trim(get_post_meta($_ORDER_ID, $_FIELD_two, true));


				delete_post_meta($_ORDER_ID, $_FIELD_one);				
				delete_post_meta($_ORDER_ID, $_FIELD_two);
	        endif;      

	    endif;

	    if(get_field('custom_field_key_for_credit_card_cvv', 'option')):

	    	$_FIELD = get_field('custom_field_key_for_credit_card_cvv', 'option');
			delete_post_meta($_ORDER_ID, $_FIELD);

		endif;

	    if(get_field('custom_field_key_for_credit_card_name', 'option')):

	    	$_FIELD = get_field('custom_field_key_for_credit_card_name', 'option');
			delete_post_meta($_ORDER_ID, $_FIELD);

		endif;


        if(get_post_meta($_ORDER_ID, 'carpro_deposit_amount', true)):
	        $_PARAMS['DepositAmount']= floatVal(number_format(get_post_meta($_ORDER_ID, 'carpro_deposit_amount', true), 2, ".",""));
	    endif;

        if($_ORDER->get_customer_note()):
	        $_PARAMS['Remarks'] 		= $_ORDER->get_customer_note();
	    endif;
        $_PARAMS['IDPassportNo']		= get_post_meta($_ORDER_ID, '_billing_id_passport', true);

        if(get_post_meta($_ORDER_ID, 'license_number', true)):
        	$_PARAMS['DrvLicenseNo'] = get_post_meta($_ORDER_ID, 'license_number', true);
        endif;

        if(get_post_meta($_ORDER_ID, 'license_expiry', true)):
        	$_PARAMS['DrvLicExpDate'] = wp_date('d/m/Y', strtotime(get_post_meta($_ORDER_ID, 'license_expiry', true)));
        endif;

        if(get_post_meta($_ORDER_ID, 'arrival_flight_number', true)):
        	$_PARAMS['FlightNo'] = get_post_meta($_ORDER_ID, 'arrival_flight_number', true);
        endif;

        $_EXTRAS = array();
        $_INSURANCE = array();

        $_RATE = get_post_meta($_ORDER_ID, 'carpro_selected_rate', true);
    	$_RATE = maybe_unserialize($_RATE);

    	$_INSURANCE[$_RATE['code']] = $_RATE['title'];


    	$_DAILY = get_post_meta($_ORDER_ID, 'carpro_selected_daily_extras', true);
    	$_DAILY = maybe_unserialize($_DAILY);

    	if(!is_array($_DAILY)): $_DAILY = array(); endif;

    	//echo '<pre>'; print_r($_DAILY); echo '</pre>';

    	foreach($_DAILY as $_CODE => $_DATA):
    		if(!in_array($_CODE, $_IS_ACTUALLY_INSURANCE)):
        		$_EXTRAS[$_CODE] = $_DATA['title'];
        	else:
        		$_INSURANCE[$_CODE] = $_DATA['title'];
        	endif;
    	endforeach;


    	$_ONCE = get_post_meta($_ORDER_ID, 'carpro_selected_once_extras', true);
    	$_ONCE = maybe_unserialize($_ONCE);

    	if(!is_array($_ONCE)): $_ONCE = array(); endif;

    	//echo '<pre>'; print_r($_ONCE); echo '</pre>';

    	foreach($_ONCE as $_CODE => $_DATA):
    		if(!in_array($_CODE, $_IS_ACTUALLY_INSURANCE)):
    			$_EXTRAS[$_CODE] = $_DATA['title'];
        	else:
        		$_INSURANCE[$_CODE] = $_DATA['title'];
        	endif;
    	endforeach;

        if(count($_EXTRAS) > 0):
        	$_PARAMS['Extra'] = $_EXTRAS;
        endif;

        if(count($_INSURANCE) > 0):
        	$_PARAMS['Insurance'] = $_INSURANCE;
        endif;


        update_field('rental_amount', $_PARAMS['RentalSum'], $_OBJ);


        $_DATA_TO_POST = $_PARAMS; 

        if(isset($_PARAMS['CreditCardNo'])):

        	unset($_PARAMS['CreditCardNo']);

        endif;

        if(isset($_PARAMS['CreditCardExpiry'])):

        	unset($_PARAMS['CreditCardExpiry']);

        endif;

        $_PARAMS = apply_filters('NL_CARPRO_FILTER_RESERVATION_PARAMS', $_PARAMS, $_ORDER_ID);
        
        update_post_meta($_ORDER_ID, 'carpro_reservation_data', $_PARAMS);

        if(!get_field('carpro_dont_send_orders','option')):

	        $_DATA = self::DOCALL($_ENDPOINT, $_DATA_TO_POST);

	        $_FAILURE = false;

	        if(isset($_DATA['status']) && $_DATA['status'] == 'success'):
	        	
	        	update_post_meta($_ORDER_ID, 'carpro_reservation_number', $_DATA['resno']);

	        	CARPRO_LOG::log('CARPRO RES SUCCESS: '.$_DATA['resno'].' ('.$_ORDER_ID.')');

	        else:

	        	update_post_meta($_ORDER_ID, 'carpro_error', $_DATA['error']);

	        	$_FAILURE = true;

	        	CARPRO_LOG::log('CARPRO RES ERROR: '.$_DATA['error'].' ('.$_ORDER_ID.')');

	        endif;

	        update_post_meta($_ORDER_ID, 'carpro_reservation_return', $_DATA);


	        /* DO WOO EMAIL NOTIFICATIONS */
	        $_WOO_EMAILS = WC()->mailer()->get_emails();
	        $_WOO_EMAILS['WC_Email_New_Order']->trigger( $_ORDER_ID );
	        $_WOO_EMAILS['WC_Email_Customer_Completed_Order']->trigger( $_ORDER_ID );

	        if($_FAILURE):

	        	self::DOFAILUREEMAIL($_ORDER_ID, $_PARAMS, $_DATA['error']);

	        endif;

	    endif;

        CARPRO_HELPERS::CLEAR_CARPRO();

	}










	/*
	DO AVAILABILITY
	*/
	public static function DOFAILUREEMAIL($_ORDER_ID, $_DATA, $_ERROR, $_TYPE = 'admin' ){

		switch($_TYPE):
			case "admin":

				if(is_array($_ERROR)): $_ERROR = implode(" ", $_ERROR); endif;

				$_TITLE = 'CARPRO ERROR';
				$_SUBJECT = 'CARPRO RESERVATION ERROR ('.$_ORDER_ID.'): '.$_ERROR;

				$_FROM_E = get_option('woocommerce_email_from_address');
				$_FROM_N = get_bloginfo('name');

				//$_TO = WC()->mailer()->get_emails()['WC_Email_New_Order']->recipient;

				$_TO = get_field('booking_failure_email_recipients', 'option');

				$_HEADERS = array(
			    	'From: '.$_FROM_N.' <'.$_FROM_E.'>'
				);

				$_BCC = get_field('booking_failure_email_recipients_bcc', 'option');

				if(get_field('booking_failure_email_recipients_bcc', 'option')):
					$_HEADERS[] = 'Bcc: '.get_field('booking_failure_email_recipients_bcc', 'option');
				endif;

				$_MAILER = WC()->mailer();
				$_CONTENT = '<h2>ORDER: '.$_ORDER_ID.'</h2>';
				$_CONTENT .= '<h2>ERROR: '.$_ERROR.'</h2>';
				foreach($_DATA as $_KEY => $_VALUE):

					if(is_array($_VALUE)):
						$_VALUE = implode(", ", $_VALUE);
					endif;

					$_CONTENT .= '<strong>'.$_KEY.'</strong> : '.$_VALUE.'<br/>';
					
				endforeach;
			break;

			case "customer":
			break;

		endswitch;





		$_FILE = 'emails/carproemail.php';

		$_FORMATTED =  wc_get_template_html( $_FILE, 
			array(
				'email_heading' => $_TITLE,
				'msg_copy'		=> $_CONTENT,
				'sent_to_admin' => false,
				'plain_text'    => false,
				'email'         => $_MAILER
			) 
		);

		$_MAILER->send( $_TO, $_SUBJECT, $_FORMATTED, $_HEADERS );
	}










	/*
	DO AVAILABILITY
	*/
	public static function DOAVAILABLILITY($_PARAMS, $_USER = false){

		CARPRO_HELPERS::CLEAR_CARPRO();

		$_ENDPOINT = 'carpro/availability';

		$_AVAILABLE = array();

		if(!isset($_PARAMS['InBranch'])):
			$_PARAMS['InBranch'] = $_PARAMS['OutBranch'];
		endif;

		$_PARAMS = apply_filters('NL_CARPRO_FILTER_AVAILABILITY_PARAMS', $_PARAMS, $_USER);

		$_DATA = self::DOCALL($_ENDPOINT, $_PARAMS);

		$_INCLUDES = array();

		$_SOLD_DAYS = 0;

		if($_DATA['status'] == 'success'):

			$_VEHICLES = $_DATA['vehicles'];

			foreach($_VEHICLES as $_CODE => $_VEHICLE):

				if($_VEHICLE['available'] == 'Available'):

					$_HAS_RATES = false;

					if(isset($_VEHICLE['rates']) && is_array($_VEHICLE['rates']) && count($_VEHICLE['rates']) > 0):

						foreach($_VEHICLE['rates'] as $_KM => $_R):

							if(isset($_R['rates']) && is_array($_R['rates']) && count($_R['rates']) > 0):
								$_HAS_RATES = true;
							endif;

						endforeach;

					endif;

					$_HAS_RATES = apply_filters('NL_CARPRO_FILTER_VEHICLE_HAS_RATES', $_HAS_RATES, $_CODE, $_VEHICLE);

					$_ONLINE_ITEM_AVAILABLE = CARPRO_HELPERS::IS_AVAILABLE($_CODE);

					if($_ONLINE_ITEM_AVAILABLE && $_HAS_RATES):
						$_AVAILABLE[$_CODE]['post'] = $_ONLINE_ITEM_AVAILABLE;
						$_AVAILABLE[$_CODE]['vehicle'] = $_VEHICLE;
						$_INCLUDES[] = $_CODE;

						if((int)$_VEHICLE['days'] > 0):
							$_SOLD_DAYS = $_VEHICLE['days'];
						endif;
					endif;

				endif;

			endforeach;


		endif;


		if(count($_INCLUDES) > 0):
			/*
			$_DATE_1 = new DateTime(implode("-", array_reverse(explode("/", $_PARAMS['OutDate']))).' '.$_PARAMS['OutTime'].':00');
			$_DATE_2 = new DateTime(implode("-", array_reverse(explode("/", $_PARAMS['InDate']))).' '.$_PARAMS['InTime'].':00');
			$_INTERVAL = $_DATE_1->diff($_DATE_2);
			$_DAY = $_INTERVAL->d;
			*/
			WC()->session->set_customer_session_cookie(true);

			WC()->session->set('carpro_out_branch', $_PARAMS['OutBranch']);
		  	WC()->session->set('carpro_in_branch', $_PARAMS['InBranch']);
		  	WC()->session->set('carpro_out_date', $_PARAMS['OutDate']);
		  	WC()->session->set('carpro_out_time', $_PARAMS['OutTime']);
		  	WC()->session->set('carpro_in_date', $_PARAMS['InDate']);
		  	WC()->session->set('carpro_in_time', $_PARAMS['InTime']);
			WC()->session->set('carpro_search_start', wp_date('Y-m-d H:i:s'));
			WC()->session->set('carpro_availability', $_AVAILABLE);
			WC()->session->set('carpro_includes', $_INCLUDES);
			WC()->session->set('carpro_days', $_SOLD_DAYS);

			WC()->session->__unset('carpro_nothing_found');

			do_action('NL_CARPRO_ACTION_DOAVAILABLILITY_AFTER', $_PARAMS);

		else:

			WC()->session->set('carpro_nothing_found', true);

		endif;

	}










	/*
	DO BRANCH UPDATE
	*/
	public static function DOBRANCHES(){


		$_ENDPOINT = 'branches';

		$_DATA = self::DOCALL($_ENDPOINT);

		if($_DATA['code'] == 'success'):


			if($_DATA['count'] > 0):

				$_INSERTED_UPDATED = array();

				foreach($_DATA['items'] as $_ITEM):
					self::DOINSERTUPDATEPOST($_ITEM, 'branch');
				endforeach;


			endif;

			self::DOCLEANUPPOST($_DATA['keys'], 'branch');

		else:

			CARPRO_LOG::log('BRANCH UPDATE FAILED');


		endif;

		$_NOW_DATE = wp_date('Y-m-d');
		$_NOW_TIME = wp_date('H:i:s');

		update_option('carpro_branch_sync_date', $_NOW_DATE);
		update_option('carpro_branch_sync_time', $_NOW_TIME);



	}










	/*
	DO VEHICLE UPDATE
	*/
	public static function DOVEHICLES(){


		$_ENDPOINT = 'vehicles';

		$_DATA = self::DOCALL($_ENDPOINT);

		if($_DATA['code'] == 'success'):

			if($_DATA['count'] > 0):

				$_INSERTED_UPDATED = array();

				foreach($_DATA['items'] as $_ITEM):
					self::DOINSERTUPDATEPRODUCT($_ITEM);
				endforeach;

			endif;

			self::DOCLEANUPPOST($_DATA['keys'], 'product');

		else:

			CARPRO_LOG::log('VEHICLE UPDATE FAILED');

			//ERROR OCCURRED

		endif;

		$_NOW_DATE = wp_date('Y-m-d');
		$_NOW_TIME = wp_date('H:i:s');

		update_option('carpro_vehicle_sync_date', $_NOW_DATE);
		update_option('carpro_vehicle_sync_time', $_NOW_TIME);

	}










	/*
	DO PUBLICHOLIDAY UPDATE
	*/
	public static function DOPUBLICHOLIDAYS(){

		$_ENDPOINT = 'publicholidays';

		$_DATA = self::DOCALL($_ENDPOINT);

		if($_DATA['code'] == 'success'):

			if($_DATA['count'] > 0):

				$_INSERTED_UPDATED = array();

				foreach($_DATA['items'] as $_ITEM):
					self::DOINSERTUPDATEPOST($_ITEM, 'publicholiday');
				endforeach;

			endif;

			self::DOCLEANUPPOST($_DATA['keys'], 'publicholiday');

		else:

			CARPRO_LOG::log('PUBLIC HOLIDAY UPDATE FAILED');

			//ERROR OCCURRED

		endif;

		$_NOW_DATE = wp_date('Y-m-d');
		$_NOW_TIME = wp_date('H:i:s');

		update_option('carpro_publicholiday_sync_date', $_NOW_DATE);
		update_option('carpro_publicholiday_sync_time', $_NOW_TIME);


	}










	/*
	DO INSERT UPDATE PRODUCT
	*/
	public static function DOINSERTUPDATEPRODUCT($_DATA){

		$_SKU = $_DATA['fields']['vehicle_code'];

		$_KEY = $_DATA['key'];

		$_ID = self::DOLOCATEPOST($_KEY, 'product');

		$_NEW = false;

		$_MODIFIED = false;

		if($_ID == 0):

			$_PROD = new WC_Product();

			$_PROD->save();

			$_ID = $_PROD->get_id();	

			$_OBJ = get_post($_ID);	

			update_post_meta($_ID, 'nextlevel_item_id', $_KEY);

			$_NEW = true;

		else:

			$_PROD = wc_get_product($_ID);

			$_OBJ = get_post($_ID);

		endif;

		if(get_post_meta($_ID, 'nextlevel_modified', true)):
			$_MODIFIED = get_post_meta($_ID, 'nextlevel_modified', true);
		else:
			$_MODIFIED = false;
		endif;

		if($_NEW || (!$_MODIFIED || strtotime($_MODIFIED) < strtotime($_DATA['modified']))):

			$_PROD->set_sku($_DATA['fields']['vehicle_code']);
			$_PROD->set_name($_DATA['title']);
			$_PROD->set_description($_DATA['content']);
			$_PROD->set_sold_individually(true);
			$_PROD->set_manage_stock(false);
			$_PROD->set_virtual(true);


			foreach($_DATA['fields'] as $_NAME => $_VALUE):

				update_field($_NAME, $_VALUE, $_OBJ);

			endforeach;

			$_PROD->save();		

			$_ITEM_TYPE = strtoupper('vehicle');

			$_UPDATES = array();


			if($_OBJ->post_status != $_DATA['status']):

				$_UPDATES['post_status'] = $_DATA['status'];

			endif;

			if($_OBJ->post_name != 'group-'.$_DATA['fields']['vehicle_code']):

				$_UPDATES['post_name'] = 'group-'.$_DATA['fields']['vehicle_code'];

			endif;

			if($_OBJ->post_content != $_DATA['content']):

				$_UPDATES['post_content'] = $_DATA['content'];

			endif;

			if($_OBJ->menu_order != $_DATA['order']):

				$_UPDATES['menu_order'] = $_DATA['order'];

			endif;

			if(count($_UPDATES) > 0):

				$_UPDATES['ID'] = $_OBJ->ID;

				wp_update_post($_UPDATES);

			endif;

			if($_NEW):
				CARPRO_LOG::log($_ITEM_TYPE.' INSERTED: '.$_DATA['title'] );
			else:
				CARPRO_LOG::log($_ITEM_TYPE.' UPDATED: '.$_DATA['title'] );
			endif;

			foreach($_DATA['terms'] as $_TAX => $_TERMS):

				$_ARRAY_OF_TERMS = array();

				foreach($_TERMS as $_TERM):

					$_TERM_ID = self::DOLOCATETAX($_TAX, $_TERM['key']);

					if($_TERM_ID == 0):
						$_TERM_ID = self::DOINSERTUPDATETERM($_TERM, $_TAX);
					endif;

					if($_TERM_ID != 0):
						$_ARRAY_OF_TERMS[] = $_TERM_ID;
					endif;

				endforeach;

				if(count($_ARRAY_OF_TERMS) > 0):
					
					wp_set_object_terms($_OBJ->ID, $_ARRAY_OF_TERMS, $_TAX, false);

				endif;

			endforeach;

			update_post_meta($_OBJ->ID, 'nextlevel_modified', $_DATA['modified']);

		endif;

			

	}










	/*
	REMOVE UNWANTED OR TRASHED POSTS
	*/
	public static function DOCLEANUPPRODUCT($_ITEMS){

		$_DELETE = get_posts(
			array(
				'post_type' => 'product',
				'posts_per_page' => '-1',
				'post__not_in' => $_ITEMS,
				'post_status' => 'any'
			)
		);

		if(count($_DELETE) > 0):

			foreach($_DELETE as $_D):

				$_PROD = wc_get_product($_D->ID);
				$_PROD->delete(true);

			endforeach;

		endif;

	}










	/*
	DO INSERT UPDATE POST
	*/
	public static function DOINSERTUPDATEPOST($_DATA, $_TYPE){


		$_KEY = $_DATA['key'];

		$_ID = self::DOLOCATEPOST($_KEY, $_TYPE);

		$_NEW = false;

		if($_ID == 0):

			$_THE_ITEM = wp_insert_post(
				array(
					'post_title' => $_DATA['title'],
					'post_type' => $_TYPE,
					'post_status' => $_DATA['status']
				)
			);

			if(!is_wp_error($_THE_ITEM)):

				if(!is_a($_THE_ITEM, 'WP_Post')):
					$_THE_ITEM = get_post($_THE_ITEM);
				endif;

				update_post_meta($_THE_ITEM->ID, 'nextlevel_item_id', $_KEY);


			endif;

			$_NEW = true;

		else:

			$_THE_ITEM = get_post($_ID);

		endif;

		if(get_post_meta($_THE_ITEM->ID, 'nextlevel_modified', true)):
			$_MODIFIED = get_post_meta($_THE_ITEM->ID, 'nextlevel_modified', true);
		else:
			$_MODIFIED = false;
		endif;

		if(!$_MODIFIED || strtotime($_MODIFIED) < strtotime($_DATA['modified'])):

			foreach($_DATA['fields'] as $_NAME => $_VALUE):

				update_field($_NAME, $_VALUE, $_THE_ITEM);

			endforeach;

			$_ITEM_TYPE = strtoupper($_TYPE);

			$_POST_UPDATE = array();

			if($_THE_ITEM->post_title != $_DATA['title']):

				$_POST_UPDATE['post_title'] = $_DATA['title'];

			endif;

			if($_THE_ITEM->post_status != $_DATA['status']):

				$_POST_UPDATE['post_status'] = $_DATA['status'];

			endif;

			if($_THE_ITEM->menu_order != $_DATA['order']):

				$_POST_UPDATE['menu_order'] = $_DATA['order'];

			endif;

			if(count($_POST_UPDATE) > 0):
				$_POST_UPDATE['ID'] = $_ID;
				wp_update_post($_POST_UPDATE);
			endif;


			if($_NEW):
				CARPRO_LOG::log($_ITEM_TYPE.' INSERTED: '.$_DATA['title'] );
			else:
				CARPRO_LOG::log($_ITEM_TYPE.' UPDATED: '.$_DATA['title'] );
			endif;


			foreach($_DATA['terms'] as $_TAX => $_TERMS):

				$_ARRAY_OF_TERMS = array();

				foreach($_TERMS as $_TERM):

					$_TERM_ID = self::DOLOCATETAX($_TAX, $_TERM['key']);

					if($_TERM_ID == 0):
						$_TERM_ID = self::DOINSERTUPDATETERM($_TERM, $_TAX);
					endif;

					if($_TERM_ID != 0):
						$_ARRAY_OF_TERMS[] = $_TERM_ID;
					endif;

				endforeach;

				wp_set_object_terms($_THE_ITEM->ID, $_ARRAY_OF_TERMS, $_TAX, false);

			endforeach;

			update_post_meta($_THE_ITEM->ID, 'nextlevel_modified', $_DATA['modified']);

			return $_ID;

		endif;

		return 0;

	}










	/*
	REMOVE UNWANTED OR TRASHED POSTS
	*/
	public static function DOCLEANUPPOST($_ITEMS, $_PT){

		$_DELETE = get_posts(
			array(
				'post_type' => $_PT,
				'post_status' => array('publish', 'draft', 'trash'),
				'posts_per_page' => '-1',
				'meta_key' => 'nextlevel_item_id',
				'meta_value' => $_ITEMS,
				'meta_compare' => 'NOT IN'
			)
		);

		if(count($_DELETE) > 0):

			foreach($_DELETE as $_D):
				wp_delete_post($_D->ID, true);
			endforeach;

		endif;

	}










	/*
	DO INSERT UPDATE TERM
	*/
	public static function DOINSERTUPDATETERM($_DATA, $_TYPE){

		$_THE_TERM = wp_insert_term(
			$_DATA['name'],
			$_TYPE,
			array(
				'slug' => $_DATA['slug']
			)
		);

		if(!is_wp_error($_THE_TERM)):

			$_THE_TERM_ID = $_THE_TERM['term_id'];
			update_term_meta($_THE_TERM_ID, 'nextlevel_item_id', $_DATA['key']);

		else:

			$_THE_TERM_ID = 0;

		endif;

		return $_THE_TERM_ID;

	}










	/*
	LOCATE POST ITEM
	*/
	public static function DOLOCATEPOST($_KEY, $_TYPE){

		$_ITEMS = get_posts(
			array(
				'post_type' => $_TYPE,
				'post_status' => array('publish', 'draft', 'trash'),
				'posts_per_page' => 1,
				'meta_query' => array(
					array(
						'key' => 'nextlevel_item_id',
						'value' => $_KEY
					)
				)
			)
		);

		if(is_array($_ITEMS) && count($_ITEMS) == 1):
			return $_ITEMS[0]->ID;
		else:
			return 0;
		endif;

	}










	/*
	LOCATE TAX ITEM
	*/
	public static function DOLOCATETAX($_TYPE, $_KEY){

		$_ARGS = array(
			'taxonomy' => $_TYPE,
			'hide_empty' => false,
			'meta_query' => array(
				array(
					'key' => 'nextlevel_item_id',
					'value' => $_KEY
				)
			)
		);

		$_TERM = get_terms(
			$_ARGS
		);

		if(is_array($_TERM) && count($_TERM) == 1):
			return $_TERM[0]->term_id;
		else:
			return 0;
		endif;

	}



}



?>