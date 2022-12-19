(function( $ ) {
	//'use strict';



var map;





/* MAP FUNCTIONS */
var infoWindows = [];

function initMarker( $marker, map ) {

    // Get position from marker.
    var lat = $marker.data('lat');
    var lng = $marker.data('lng');

    myLatLng = new google.maps.LatLng(parseFloat( lat ), parseFloat( lng )); 

    var marker = new HTMLMapMarker({
      position: myLatLng,
      latlng: myLatLng,
      map: map,
      html: '<div class="carpro_map_marker"><img src="'+carpro_params.map_marker+'" /></div>'
    });

    // Append to reference for later use.
    map.markers.push( marker );

    // If marker contains HTML, add it to an infoWindow.
    if( $marker.html() ){

        // Create info window.
        var infowindow = new google.maps.InfoWindow({
            content: $marker.html()
        });

        // Show info window when marker is clicked.
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open( map, marker );
        });
    }
}


/**
 * centerMap
 *
 * Centers the map showing all markers in view.
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   object The map instance.
 * @return  void
 */
function centerMap( map ) {

    // Create map boundaries from all map markers.
    
    var bounds = new google.maps.LatLngBounds();
    map.markers.forEach(function( marker ){
        bounds.extend({
            lat: marker.position.lat(),
            lng: marker.position.lng()
        });
    });
    
    // Case: Single marker.
    if( map.markers.length === 1 ){
        map.setCenter( bounds.getCenter() );

    // Case: Multiple markers.
    } else{
        map.fitBounds( bounds );
    }
}


  function initMap( $el ) {

    // Find marker elements within map.
    var $markers = $el.find('.marker');
    var $coords = null;

    if($el.data('lat') && $el.data('lng') ){
        $coords = new google.maps.LatLng($el.data('lat'), $el.data('lng'));
    }else{
        $coords = new google.maps.LatLng(0, 0);
    }
    
    mapArgs = {
        zoom        : $el.data('zoom') || 12,
        center: $coords,
          scaleControl: true,
          streetViewControl: false,
          mapTypeControl: false,
          panControl: false,
          zoomControl: true,
          scrollwheel: false,
          draggable: true,
          zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
          }
    };

    var map = new google.maps.Map( $el[0], mapArgs );

    // Add markers.
    map.markers = [];
    $markers.each(function(){
        initMarker( $(this), map );
    });

    // Center map based on markers.
    centerMap( map );

    // Return map instance.
    return map;
}









  var $_TIMER;

  var $_NOW;

  $(document).ready(function(){


      $('.checkoutTabLink').on('click', function(e){
        e.preventDefault();

        $('#carproLoader').addClass('SHOWING');

        $('.checkoutTabLink').removeClass('ACTIVE');
        $(this).addClass('ACTIVE');

        var $_TARGET = $(this).attr('href');
        $('.checkoutTab').removeClass('ACTIVE');
        $($_TARGET).addClass('ACTIVE');

        var $_BACK = $(this).attr('data-back');
        var $_NEXT = $(this).attr('data-next');

        if($_BACK == 'yes'){
          $('#checkoutPrevious').removeClass('disabled');
        }else{
          $('#checkoutPrevious').addClass('disabled');
        }

        if($_NEXT == 'yes'){
          $('#checkoutNext').removeClass('disabled');
        }else{
          $('#checkoutNext').addClass('disabled');
        }


        $('html, body').animate({
          scrollTop: $('#checkoutTabs').offset().top - $('#THEHEADER').height()
        }, 1000);

        $('#carproLoader').removeClass('SHOWING');


      });


      $('#checkoutPrevious').on('click', function(e){

        e.preventDefault();

        $('.checkoutTabLink.ACTIVE').closest('.checkoutTabListItem').prev().find('.checkoutTabLink').trigger('click');

      });

      $('#checkoutNext').on('click', function(e){
        e.preventDefault();

        var $_VALID = true;
        if($('.checkoutTab.ACTIVE .validate-required').length){

          $('.checkoutTab.ACTIVE .validate-required').each(function(){

              if(jQuery(this).find('input').length){

                if(jQuery(this).find('input').hasClass('input-text')){

                  if(!jQuery(this).find('input').val() || jQuery(this).find('input').val() == ''){
                   $_VALID = false;
                  }
                  
                }
              }


              if(jQuery(this).find('select').length){
                
                 if(!jQuery(this).find('select').val() || jQuery(this).find('select').val() == ''){
                  $_VALID = false;
                }
              }
                

          });

        }
        


        if($_VALID){
          $('.checkoutTabLink.ACTIVE').closest('.checkoutTabListItem').next().find('.checkoutTabLink').trigger('click');
        }else{
          jQuery.confirm({
            title: 'Check your information',
            content: 'There seems to be some required information you have left out.<br/><br/>Kindly make sure all required fields are filled out.',
            theme: 'black',
            buttons: {
                ok: {
                    text: 'OK',
                    action: function(){
                      $('html, body').animate({
                        scrollTop: $('#checkoutTabs').offset().top - $('#THEHEADER').height()
                      }, 1000);
                    }
                }
            },
            autoClose: 'ok|5000',
          });
        }
        
        
        
      });

      if($('.checkoutTabLink').length){
        $('.FIRSTCHECKOUTAB').trigger('click');
      }


      $('#carproOutBranch').select2();
      $('#carproInBranch').select2();
      $('#carproOutTime').select2();
      $('#carproInTime').select2();




      var LICENSEARGS = {
            minDate: 1,
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
        };


      $('#license_expiry').datepicker(LICENSEARGS);




      var DROPOFFARGS = {
            minDate: parseInt(carpro_params.booking_lead_time) + parseInt(carpro_params.booking_minimum),
            startDate: parseInt(carpro_params.booking_lead_time) + parseInt(carpro_params.booking_minimum),
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            onSelect: function(date){

              nextlevelBranchTimes('InTime', $('#carproInBranch').val(), date);  
              
            }
        };

      $('#carproInDate').datepicker( DROPOFFARGS );


      var PICKUPARGS = {
          minDate: parseInt(carpro_params.booking_lead_time),
          dateFormat: 'yy-mm-dd',
          changeMonth: true,
          changeYear: true,
          onSelect: function(date){
            $_DATE_START = new Date(date); 
            $_DATE_START.setDate($_DATE_START.getDate() + parseInt(carpro_params.booking_minimum));
            $_DATE_START_VAL = $_DATE_START.toISOString().substr(0, 10);
            $('#carproInDate').datepicker( "option", "minDate", $_DATE_START );
            nextlevelBranchTimes('OutTime', $('#carproOutBranch').val(), date);
            if(carpro_params.booking_maximum > 0){
              $_DATE_END = new Date($_DATE_START_VAL); 
              $_DATE_END.setDate($_DATE_END.getDate() + parseInt(carpro_params.booking_maximum));
              $_DATE_END_VAL = $_DATE_END.toISOString().substr(0, 10);
              $('#carproInDate').datepicker( "option", "maxDate", $_DATE_END );
            }

            nextlevelBranchTimes('InTime', $('#carproInBranch').val(), $_DATE_START_VAL);

            $('#carproInDate').val($_DATE_START_VAL).trigger('change'); 
                 
          }
        };

      $('#carproOutDate').datepicker( PICKUPARGS );
      

      if(carpro_params.is_search == 'yes'){
      


        if(carpro_params.search_carpro_out_branch && carpro_params.search_carpro_in_branch){


          if(carpro_params.search_carpro_out_branch !== carpro_params.search_carpro_in_branch){
            setTimeout(function(){ $('#carpro_different_location').trigger('click'); }, 1000);
          }
          
        }

        if(carpro_params.search_carpro_out_date && carpro_params.search_carpro_out_date != ''){
          var $PICKDATE = new Date(carpro_params.search_carpro_out_date);
          $('#carproOutDate').datepicker('setDate', $PICKDATE).trigger('onSelect');
        }

        if(carpro_params.search_carpro_in_date && carpro_params.search_carpro_in_date != ''){
          var $DROPDATE = new Date(carpro_params.search_carpro_in_date);          
          $('#carproInDate').datepicker('setDate', $DROPDATE).trigger('onSelect');
        }

      }else{

        $('#carproOutDate').datepicker('setDate', parseInt(carpro_params.booking_default));
        $('#carproInDate').datepicker('setDate', parseInt(carpro_params.booking_default) + parseInt(carpro_params.booking_minimum));

      }


      nextlevelBranchTimes('OutTime', $('#carproOutBranch').val(), $('#carproOutDate').val());
      nextlevelBranchTimes('InTime', $('#carproInBranch').val(), $('#carproInDate').val());


      
      $('#carproOutBranch').on('change', function(){

        $_CHECKBOX = $('#carpro_different_location');

        if($_CHECKBOX.is(':checked')){
          $('#carproInBranch').val($('#carproOutBranch').val()).trigger('update');
        }

      });


      /* SET TIMER IF A SEARCH HAS HAPPENED */
      if(carpro_params.enable_timer == 'yes'){
        if(carpro_params.search_start_num !="" && carpro_params.search_end_num !=""){
          $_TIMER = setInterval(carproOrderTimeout, 1000);
        }
      }




    
      
      $('#SEARCHFORMCLOSECHECKBOX').on('click', function(){
          $('#carpro_different_location').trigger('click');
      });

      $('#SEARCHFORMOPENBOX').on('click', function(){
          $('#carpro_different_location').trigger('click');
      });




    
      /* RETURN TO DIFFERENT LOCATION HANDLER */

      $('#carpro_different_location').on('change', function(){



          $_CHECKBOX = $('#carpro_different_location');

            if($_CHECKBOX.is(':checked')){

              $('#SEARCHFORMDROPLABEL').removeClass('show');

              setTimeout(
                function(){
                  $('#SEARCHFORMLOCATIONDROPOFF').switchClass('col-xl-6', 'col-xl-4'); 
                  setTimeout(function(){
                    $('#SEARCHFORMLOCATIONCONTAINER').switchClass('col-xl-6', 'col-xl-8');
                  }, 250);
                }, 
                250
              );
              
              $('#SEARCHFORMLOCATIONCHECKBOX').addClass('active');              
              $('#SEARCHFORMLOCATIONDROPOFF').removeClass('open');

              setTimeout(
                function(){
                  $('#SEARCHFORMLABEL').html($('#SEARCHFORMLABEL').data('drop'));
                }, 
                500
              );
              
            }else{
              $('#SEARCHFORMLABEL').html($('#SEARCHFORMLABEL').data('pick'));

              setTimeout(
                function(){
                  $('#SEARCHFORMLOCATIONCONTAINER').switchClass('col-xl-8', 'col-xl-6');

                  setTimeout(function(){
                    $('#SEARCHFORMLOCATIONDROPOFF').switchClass('col-xl-4', 'col-xl-6');
                  }, 250);
                }, 
                250
              );

              
              $('#SEARCHFORMLOCATIONCHECKBOX').removeClass('active');
              $('#SEARCHFORMLOCATIONDROPOFF').addClass('open');
              setTimeout(function(){ $('#SEARCHFORMDROPLABEL').addClass('show'); }, 500);

            }


        setTimeout(function(){ $('#SEARCHFORMACTION').fadeIn('slow'); }, 1000);
        

      });





      if($('#billing_phone').length){
        var input = document.querySelector("#billing_phone");

        window.intlTelInput(input, {
          preferredCountries: ['za'],
          formatOnDisplay: true,
          separateDialCode: true,
          utilsScript: carpro_params.telephoneutil,
          initialCountry: 'za',
          hiddenInput: 'phone_number_full'
        });
      }




    
      /* SEARCH FUNCTION */
      $('#carproPerformSearch').on('click', function(){

        var $_ERRORS = '';

        if(!jQuery('#carproOutBranch').val()){
           $_ERRORS+= '<br/><br/><em>No pick-up branch selected';
        }

        if(!jQuery('#carproInBranch').val()){
           $_ERRORS+= '<br/><br/><em>No drop-off branch selected';
        }

        if(!jQuery('#carproOutDate').val() || jQuery('#carproOutDate').val() == ''){
           $_ERRORS+= '<br/><br/><em>No pick-up date selected';
        }

        if(!jQuery('#carproInDate').val() || jQuery('#carproInDate').val() == ''){
           $_ERRORS+= '<br/><br/><em>No drop-off date selected';
        }

        if(!jQuery('#carproOutTime').val()){ 
          $_OUT_BRANCH = $("#carproOutBranch option:selected").text();
          $_ERRORS+= '<br/><br/><em><strong>'+$_OUT_BRANCH +'</strong></em> is not available on that Pick-up Date';
        }

        if(!jQuery('#carproInTime').val()){
          $_IN_BRANCH = $("#carproInBranch option:selected").text();
          $_ERRORS+= '<br/><br/><em><strong>'+$_IN_BRANCH +  '</strong></em> is not available on that Pick-up Date';
        }




        if($_ERRORS.length > 0){


          jQuery.confirm({
            title: 'Search Error',
            content: 'Apologies, there seems to be some issues with your search: '+$_ERRORS,
            //type: 'blue',
            theme: 'black',
            buttons: {
                ok: {
                    text: "Ok, I Understand"
                }
            }
        });



        }else{

          var ajax_data = {
            action: 'carpro_ajax_do_search',
            data: $('#carpro_search_form').serialize()
          };

          

          jQuery.ajax({
              url: carpro_params.ajax_url,
              type:'POST',
              data: ajax_data,
              beforeSend:function(){
                jQuery('#carproLoader').addClass('SHOWING');
              },
              success: function (url) {
                window.location.href = url;
              }
          });

        }


      });






      /* CHECKOUT TOOLTIPS */


      $('.carpro-extra-checkbox .description').each(function(){

        $_ITEM = $(this).closest('.woocommerce-input-wrapper').find('label');
        $('<span class="extra-has-description">i</span>').insertAfter($_ITEM);

      });

      $('.carpro-extra-text .description').each(function(){

        $_ITEM = $(this).closest('.woocommerce-input-wrapper').find('label');
        $('<span class="extra-has-description">i</span>').insertAfter($_ITEM);

      });


      $('#license_number, #arrival_flight_number, #billing_id_passport').on('keyup', function(){

        $_NEW_VAL = $(this).val().replace(/[^A-Z0-9]+/i, '');
        $(this).val($_NEW_VAL).text($_NEW_VAL).trigger('change');
      });




    
      /* CUSTOM ADD TO CART ACTION */
      $('.carpro_add_to_cart').on('click', function(){

        $_VEHICLE   = $(this).data('vehicle');
        $_ID        = $(this).data('id');
        $_KM        = $(this).data('km');
        $_CODE      = $(this).data('code');
        $_SKU      = $(this).data('sku');
        $_OWF      = $(this).data('owf');


        var ajax_data = {
          action: 'carpro_ajax_do_add_to_cart',
          vehicle: $_VEHICLE,
          id: $_ID,
          km: $_KM,
          code: $_CODE,
          sku: $_SKU,
          owf: $_OWF
        };

        jQuery.ajax({
            url: carpro_params.ajax_url,
            type:'POST',
            data: ajax_data,
            beforeSend:function(){
              jQuery('#carproLoader').addClass('SHOWING');
            },
            success: function (url) {
              window.location.href = url;
            }
        });


      });




    
      /* CHECKOUT CUSTOM CHECKBOX: TRIGGER UPDATE */
      $('.carpro-extra-checkbox').find('.input-checkbox').on('change', function(){

         $(document.body).trigger("update_checkout")

      });




    
      /* PAYMENT % CHANGE: TRIGGER UPDATE */
      $('#payment_type').on('change', function(){

         $(document.body).trigger("update_checkout")

      });




    
      /* UPDATE PRODUCT/VEHICLE PRICE BASED ON RATE SELECTED */
      $('.carproVehicleRateChoice').on('change', function(){


        var $_PRODUCT   = $(this).closest('li.product');
        var $_NEW_PRICE = $(this).closest('.jsRateBlock').find('.carproPriceItem').html();
        var $_NEW_PD    = $(this).attr('data-pd');
        var $_PRICE     = $_PRODUCT.find('.vehicle_price');
        var $_BUTTON    = $_PRODUCT.find('.carpro_add_to_cart');
        var $_PERDAY    = $_PRODUCT.find('.carpro_perday').find('.value');


        $_BUTTON.attr('data-km', $(this).data('km'));
        $_BUTTON.attr('data-code', $(this).data('code'));
        $_BUTTON.attr('data-owf', $(this).data('owf'));
        
        $_PRICE.html($_NEW_PRICE).trigger('update');
        $_PERDAY.html($_NEW_PD).trigger('update');

      });
      /* SET BASE TIMES */
      //nextlevelBranchTimes('OutTime', $('#carproOutBranch').val(), $('#carproOutDate').val());
      //nextlevelBranchTimes('InTime', $('#carproInBranch').val(), $('#carproInDate').val());




    
      /* CHANGE PICKUP BRANCH */
      $('#carproOutBranch').on('change', function(){

        //nextlevelUpdateRestriction('Out', $(this).val());

        nextlevelBranchTimes('OutTime', $(this).val(), $('#carproOutDate').val());


        if($('#carpro_different_location').is('checked')){
          $('#carproInBranch').val($(this).val()).trigger('change');
        }


      });




    
      /* CHANGE DROPOFF BRANCH */
      $('#carproInBranch').on('change', function(){

        //nextlevelUpdateRestriction('In', $(this).val());

        nextlevelBranchTimes('InTime', $(this).val(), $('#carproInDate').val());

      });




      $('.extra-has-description').each(function(){

        $(this).on('click', function(){
            $(this).next().slideToggle('medium', function() {
                if ($(this).is(':visible'))
                    $(this).css('display','inline-block');
          });

        });
      });






  });

  

  $(window).load(function(){

      


      if($('.FIRSTRATEITEM').length){
        $('.FIRSTRATEITEM').each(function(){ 
          $(this).trigger('click').trigger('change').trigger('update');
        });
      }


      if($('#single_map_branch').length){
        initMap( $('#single_map_branch') );
      }

      if($('#global_map_branch').length){
        initMap( $('#global_map_branch') );
      }




  });




    
  /* UPDATE BRANCH TIMES */
  function nextlevelBranchTimes($_SELECT, $_BRANCH, $_DATE){
     

     var ajax_data = {
        action: 'carpro_ajax_branch_times',
        branch: $_BRANCH,
        date: $_DATE,
        type: $_SELECT
      };

      jQuery.ajax({
          url: carpro_params.ajax_url,
          type:'POST',
          data: ajax_data,
          beforeSend:function(){
            jQuery('#carproLoader').addClass('SHOWING');
          },
          success: function (items) {
            jQuery('#carpro'+$_SELECT).html(items);
            jQuery('#carproLoader').removeClass('SHOWING');


                if(carpro_params.is_search){


                 if($_SELECT == 'OutTime' && carpro_params.search_carpro_out_time){
                  jQuery('#carpro'+$_SELECT).val(carpro_params.search_carpro_out_time);
                 }

                 if($_SELECT == 'InTime' && carpro_params.search_carpro_in_time){
                  jQuery('#carpro'+$_SELECT).val(carpro_params.search_carpro_in_time);
                 }


               }


          }
      });





  }




    
  /* ORDER TIMEOUT */
  function carproOrderTimeout(){

    if(jQuery('body').hasClass('woocommerce-order-received') || carpro_params.clear_search == "yes"){
      clearInterval($_TIMER);
      jQuery('#carproTimerText').html('');
      jQuery('#topBarTimer').html('');
      jQuery('#carproTimer').removeClass('showing');

      var ajax_data = {
        action: 'carpro_ajax_reset_search',
      };

      jQuery.ajax({
        url: carpro_params.ajax_url,
        type:'POST',
        data: ajax_data
      });

    }else{

      if(typeof(carpro_params.search_end_dt) != "undefined" && carpro_params.search_end_dt !== null && carpro_params.search_end_dt != ''){

        $_END = new Date(carpro_params.search_end_dt.replace(/-/g, '/'));
        $_NOW = new Date();
        var $_LEFT = $_END - $_NOW;

        var days = Math.floor($_LEFT / (1000 * 60 * 60 * 24));
        var hours = Math.floor(($_LEFT % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor(($_LEFT % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor(($_LEFT % (1000 * 60)) / 1000);

        var orig_minute = minutes;
        var orig_seconds = seconds;

        if(minutes < 10){ minutes = '0'+minutes; }
        if(seconds < 10){ seconds = '0'+seconds; }

        $_TIMETEXT = minutes+':'+seconds;

        jQuery('#carproTimerText').html($_TIMETEXT);
        jQuery('#topBarTimer').html($_TIMETEXT);
        jQuery('#carproTimer').addClass('showing');


        if((orig_minute < 0 && orig_seconds < 0) || (parseInt(orig_minute) <= 0 && parseInt(orig_seconds) <= 0) ){

          clearInterval($_TIMER);
          jQuery('#carproTimer').removeClass('showing');

          var ajax_data = {
            action: 'carpro_ajax_reset_search',
          };

           jQuery.ajax({
              url: carpro_params.ajax_url,
              type:'POST',
              data: ajax_data
          });

          jQuery.confirm({
              title: carpro_params.booking_session_title,
              content: carpro_params.booking_session_text,
              theme: 'black',
              buttons: {
                  ok: {
                      text: carpro_params.booking_session_button,
                      action: function(){

                        window.location.href = carpro_params.booking_session_link;

                      }
                  }
              },
              autoClose: 'ok|'+carpro_params.booking_session_time,
          });
          
        }

      }

    }


  }

})( jQuery );
