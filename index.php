<?php
session_start();
// Required File Start.........
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/connection.php'; //DB connectivity
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
// Required File END...........
error_reporting(E_ALL);
ini_set('display_errors', 1);

if((isset($_REQUEST['shop'])) && (isset($_REQUEST['code'])) && $_REQUEST['shop']!='' && $_REQUEST['code']!='' )
{
	$_SESSION['shop']=$_REQUEST['shop'];
	$_SESSION['code']=$_REQUEST['code'];
}
$access_token = shopify\access_token($_REQUEST['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);


?>
 <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,700" rel="stylesheet"> 
 
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/988a7dc35f.js"></script>
<link rel="stylesheet" href="css/style.css" type="text/css">
<link href="css/slick.css"  rel="stylesheet" type="text/css"/>  
<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"  rel="stylesheet" type="text/css"/>  
<script src="js/slick.js" type="text/javascript"></script>
 <script src="js/jquery.twbsPagination.js" type="text/javascript"></script>
<div class="background_overlay" style="display:none"></div>
<div class="page"></div>
<div class="content-container"></div>

<script>
	// Get orders
	function getorders(page,limit){
         console.log('test122');
		var access_token='<?php echo $access_token ?>';
		var shop='<?php echo $_REQUEST['shop'] ?>';
		$.ajax({
			url: '/orders.php?access_token='+access_token+'&shop='+shop+'&limit='+limit+'&page_id='+page,
			success: function(data){
				$('.content-container').html(data);	
			}
								
			
		});
	}
	// get order count
	function order_count()	{
		var access_token='<?php echo $access_token ?>';
		var shop='<?php echo $_REQUEST['shop'] ?>';
		 $.ajax({
			url: '/order_count.php?access_token='+access_token+'&shop='+shop,
			success: function(data){
				console.log(data);
				var noofPages = $.trim(data);
				$('.page_inner').remove();
				if(noofPages>0){
					
					$('.page').append('<div class="page_inner"></div>');
					var obj = $('.page_inner').twbsPagination({
							totalPages: noofPages,
							visiblePages:3,
							onPageClick: function (event, page) {
								getorders(page,250);
								}
							//console.log(page);
						   
						});
				}
				else{
					$('.content-container').html( "<div class='no-result'>No Order</div>");	
				}
			}
								
			
		});
	}
	function cutAtfirstpart(text, n) {
		if(text.length > n){
			for (; " .,".indexOf(text[n]) !== 0; n--){
			}
			return text.substr(0, n);
		}
		return text;
	}
	function cutAtlast(text, n,totallen) {
		if(text.length > n){
			for (; " .,".indexOf(text[n]) !== 0; n--){
			}
			return text.substr(n, totallen);
		}
		return text;
	}
	function closepopup(){
		if(jQuery('#popup_content').is(':visible')){	
			jQuery('#popup_content').fadeOut(800);
			jQuery(".background_overlay").fadeOut(800);
			order_count();
		}
	}
// Initial Page Load
(function($) {
	var access_token='<?php echo $access_token ?>';
	var shop='<?php echo $_REQUEST['shop'] ?>';
	order_count();
	
	$('body').on('click', 'a.fancybox_btn', function(e) {
		e.preventDefault();
		$('.popupcontent_inner').remove();
		$('#popup_content').append('<div class="popupcontent_inner"></div>');
		var content ;
		 var len = $('.select_box:checkbox:checked').length;
		 if(len > 0){
			$('.select_box:checkbox:checked').each(function(index){
				    var order_id = $(this).val(); 
					var customer_email = $(this).attr('data-customer_email');
					var customer_name = $(this).attr('data-customer_name');
					var customer_address = $(this).attr("data-fulladdress");
					console.log("customer_address ="+customer_address);
					var payment_method = $(this).attr('data-gateway');
					var customer_phone = $(this).attr('data-customer_phone');
					var customer_total_price = $(this).attr('data-customer_total-price');
					var total_weight = $(this).attr('data-total_weight');
					var quantity_total = $(this).attr('data-quantity_total');
					var financial_status = $(this).attr('data-financial_status');
					content ='<div class="item"><div class="item_inner"><h3>Shipping information</h3><input type="hidden" value="'+total_weight+'" data-order_id="'+order_id+'" data-quantity_total="'+quantity_total+'" data-financial_status="'+financial_status+'" class="total_weight"> <div class="fhalf"><h5>Pickup Address*</h5><label>Pickup Company Name:</label><input type="text" class="p_company_name" value="Lomdi Outfitters"><label>Pickup address:</label><select name="pickup_address" class="pickup_address"><option value="4-2-913 Ramkote,Hyderabad,Telangana" selected>Abhishek Enterprises:4-2-913 Ramkote,Hyderabad,Telangana_500001::9885907319</option><option value="D-606, West Vinod Nagar, Behind Ras Vihar Appt" data-address2="IP Extn, Patparganj,East Delhi,Delhi">Lomdi Outfitters:D-606, West Vinod Nagar, Behind Ras Vihar Appt IP Extn, Patparganj, East Delhi,Delhi_110092::9711789367</option></select></div>';
					content = content + '<div class="shalf"><label>Customer Name:</label><input type="text" class="customer_name" value="'+customer_name+'"><br><label>Customer Email:</label><input type="text" class="customer_email" value="'+customer_email+'"><br><label>Customer phone:</label><input type="text" class="customer_phone" value="'+customer_phone+'"><br><label>Customer Address:</label><textarea class="customer_address" value="'+customer_address+'">'+customer_address+'</textarea><label>Total amount pay:</label><input type="text" class="customer_total_price" value="'+customer_total_price+'">';
					content = content + '<br><label>Payment Type: <p class="payment_method">"'+payment_method+'"</p></label>';
					content = content + '<br><div class="c_type"><label>Content</label><span><input type="radio" checked value="P" name="content_type" class="content_type">Product</span> <span><input type="radio" value="D" name="content_type" class="content_type">Documents</span></div></div></div>';

					$('.popupcontent_inner').append(content);
		
			});
				var content_last ="<div class='item'><div class='item_inner last'><a href='#' class='Create_order'>Create Order</a></div></div>";
				$('.popupcontent_inner').append(content_last);
				// Define data for the popup
					function sliderInit(){
						$('.popupcontent_inner').slick({
							slidesToShow: 1,
							dots: true,
							infinite: false,
						});
		 
					};
					sliderInit(); // call slider function
					jQuery('#popup_content').show();
					$('.popupcontent_inner').get(0).slick.setPosition();
					jQuery(".background_overlay").fadeIn(800);
	
    
		}
	
		else {
		  alert("First Select the orders");
		}
	
	}); 
	

	
	$('body').on('click', 'a.Create_order', function(e) {
	$('.response_msg').remove();
		$( '<div class="load_outer"> <img src="images/loading3.gif" class="loadimg"></div>' ).insertAfter('.Create_order');
	var leng = $('.popupcontent_inner .item').length;
	var i=0;
	$('.popupcontent_inner .item').each(function(index){
		if(index < leng-1){
			
		  /* pickup address detail*/
		   var pickup_address = $('.pickup_address option:selected',this).text();
		   var address2 = $('.pickup_address option:selected',this).attr('data-address2');
		   var pickup_address1 = $('.pickup_address option:selected',this).val();
		   var content_type = $('.content_type:checked',this).val();
		   var customer_total_price = $('.customer_total_price',this).val();
		   var p_company_name = $('.p_company_name',this).val();
		   var p_contact_person = pickup_address.split(':')[0];
		   //var p_contact_person = $('.p_contact_person',this).val();
		   var p_phone = pickup_address.split('::')[1];
		   var p_emailid = $('.p_emailid',this).val();
		   var p_zipcode = pickup_address.split('_')[1].split('::')[0];		   
		   //var p_zipcode = $('.p_zipcode',this).val();
			//alert("p_zipcode" +p_zipcode);
		 /* pickup address detail*/
		  /*customer  detail*/
		   var customer_name = $('.customer_name',this).val();
		   var customer_phone = $('.customer_phone',this).val();
		   customer_phone=customer_phone.replace(/\s/g, ""); 
		   var customer_email = $('.customer_email',this).val();
		   var customer_address = $('.customer_address',this).val();
		   var total_weight = $('.total_weight',this).val();
		   if(total_weight == '')
		   {
		   total_weight =0.001;
		   }
		   var total_qty = $('.total_weight',this).attr('data-quantity_total');
		   var order_id = $('.total_weight',this).attr('data-order_id');
		   var financial_status = $('.total_weight',this).attr('data-financial_status');
		   customer_address1 = customer_address.split(',city');
				
			var c_address = customer_address1[0];
	          c_address_count =c_address.length;
			  if(c_address_count > 60){
				var c_address1=cutAtfirstpart(c_address, 60);
				var c_address2=cutAtlast(c_address, 60,c_address_count);
			  }
			  else{
				  var c_address1=c_address;
			  }
			var c_city = customer_address.split('city:')[1].split(',')[0];
			var c_state = customer_address.split('province:')[1].split(',')[0];
			var c_country = customer_address.split('country:')[1].split(',')[0];	
			var c_zipcode = customer_address.split('zip:')[1];	
			console.log("c_city"+c_city+"c_state"+c_state+"c_address"+c_address);
			var payment_method = $('.payment_method').text();
			if(payment_method == '"Cash on Delivery (COD)"' || payment_method =='"manual"' || payment_method == '"cash_on_delivery"' || financial_status == 'pending')
			{
			var collectable_value= customer_total_price;
			payment_method = true;
			}
			else{
			payment_method = false;
			}
			console.log("c_address1="+ c_address1);
			console.log("c_address2="+ c_address2);
			/* customer detail*/
	   
		       	var request = new XMLHttpRequest();
               /* live api */
			 request.open('POST', 'https://api.sendd.co/core/api/v1/order/');
			request.setRequestHeader('Content-Type', 'application/json');
			request.setRequestHeader('Authorization', 'Token 0eb688db8076a89861b3885a9cccdcc30edc7a0e');
			/* live api */
			/* test api */
			 /*request.open('POST', 'https://api-staging.sendd.co/core/api/v1/order/');
			request.setRequestHeader('Content-Type', 'application/json');
			request.setRequestHeader('Authorization', 'Token 39757c4c7867f048ed452812df9f4d7395842de8');*/ 
			/* test api */
             request.onreadystatechange = function () {
			  if (this.readyState === 4) {
				console.log('Status:', this.status);
				console.log('Headers:', this.getAllResponseHeaders());
				console.log('Body:', this.responseText);
				var json = JSON.parse(this.responseText);
				console.log(json);
				if(json['shipments']){
					var tracking_no= json['shipments'][0]['partner_tracking_detail']['tracking_number'];
					 var company= json['shipments'][0]['partner_tracking_detail']['company'];
					  /* add the tracking code in order note */
						$.ajax({
							url: '/order_note.php?access_token='+access_token+'&shop='+shop+'&trackingcode='+tracking_no+'&trackingcompany='+company+'&order_id='+order_id,
								success: function(data){
									console.log(data);
									
								}
						});
					 /* add the tracking code in order note */
					$('.item_inner.last').append("<div class='response_msg'>Order id ="+order_id+" Message = Successfully Shipped</div>");
				}
				else if(json['detail']){
					$('.item_inner.last').append("<div class='response_msg'>Order id ="+order_id+" Message ="+json['detail']+"</div>");
				}
				else{
					$('.item_inner.last').append("<div class='response_msg'>Order id ="+order_id+" Message = Invalid Detail</div>");
				}
				
				if(i == leng-1){
					$('.load_outer').remove();
				}
			}
			};

			var body = {
			  'customer_reference_id':order_id,
			  'shipments': [
				{
				  'item_detail': {
					'content': content_type,
					'purpose': 'C',
					'value': customer_total_price,
					'qty':total_qty,
					'weight':0.5,
					'fragile': false,
					collectable_value,
					'description': 'Phone Accessories'
					
				  }
				}
			  ],
			  'pickup_detail': {
			   'address_type': 'O',
				'company_name': p_company_name,
				'contact_person': p_contact_person,
				'phone': p_phone,
				'address_1': pickup_address1,
				'address_2': address2,
				'pincode': p_zipcode,
				 'country': 'IN',
				
			  },
			  'delivery_detail': {
				'address_type': 'H',
				'contact_person': customer_name,
				'phone': customer_phone,
				'email': customer_email,
				'address_1': c_address1,
				'address_2': c_address2,
				'pincode': c_zipcode,
				'city': c_city,
				'state': c_state,
				'country': c_country,
				
			  },
			  'shipping_type': 'S',
			  'cod': payment_method,
			  'insurance': false,
			  'process': true,
			  'notifications':true,
			};

			request.send(JSON.stringify(body)); 
			i++;
		}
		
	});
});
	$('body').on('click', '.put_track', function() {
		
		var order_id= $(this).attr('data-id');
		var tracking_code= $(this).attr('data-tracking_code');
		if(tracking_code){
			tracking_no = tracking_code.split(',')[0];
			tracking_company = tracking_code.split(',')[1];
			$.ajax({
					url: '/trackingcode.php?access_token='+access_token+'&shop='+shop+'&trackingcode='+tracking_no+'&trackingcompany='+tracking_company+'&order_id='+order_id,
					success: function(data){
						console.log(data);
						alert('Tracking Code Added Successfully!');
						order_count(); // call order function 
					}
				}); 
		}
		else
		{
			alert('No tracking no is available');
		}
	});
})(jQuery);
</script>
