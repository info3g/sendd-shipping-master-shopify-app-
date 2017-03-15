<?php

require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token=$_REQUEST['access_token'];
$order_count=$_REQUEST['order_count'];
$shopify = shopify\client($_REQUEST['shop'], SHOPIFY_APP_API_KEY, $access_token );
try
{
	      
			$orders = $shopify('GET /admin/orders.json', array('limit'=>$_REQUEST['limit'],'page'=>$_REQUEST['page_id'],'fulfillment_status'=>'unshipped','order'=>'created_at asc'));
			if($orders){
			require __DIR__.'/popupcontent.php'; //popup content
			echo '<table class="table-hover expanded">';
			 echo "<thead><tr>";
			 echo '<th>&nbsp;</th><th><span>Order</span></th>
								  <th class="is-sortable">
									<span>Date</span>
								  </th>
								  <th class="is-sortable">
									<span>Customer</span>
								  </th>
								  <th class="is-sortable ">
									<span>Payment status</span>
								  </th>
								  <th class="is-sortable sorted-desc">
									<span>Fulfillment status</span>
								  </th>
								  <th class="type--right is-sortable ">
									<span>Total</span>
								  </th>
								   <th class="type--right is-sortable ">
									<span>Tracking Code </span>
								  </th>
								</tr>
							  </thead>';
							  echo '<tbody>';
							  
			foreach($orders as $singleorder)
			{
				$quantity_total=0;
				 $id =$singleorder['id'];
				 $name =$singleorder['name'];
				 $created_at =$singleorder['created_at'];
				$total_weight =$singleorder['total_weight'];
				 $gateway =$singleorder['gateway'];
				 $financial_status=$singleorder['financial_status'];
				 $total_price=$singleorder['total_price']; 
				 $email=$singleorder['email'];
				 $address=$singleorder['shipping_address']['address1'];
				 $address2=$singleorder['shipping_address']['address2'];
				 $city=$singleorder['shipping_address']['city'];
				 $zip=$singleorder['shipping_address']['zip'];
				 $province=$singleorder['shipping_address']['province'];
				 $country=$singleorder['shipping_address']['country'];
				 $customer_name=$singleorder['shipping_address']['name'];
				$customer_phone=$singleorder['shipping_address']['phone'];
				$note_attributes=$singleorder['note_attributes'];
				$note_name=$note_attributes[0]['value'];
				$note_value=$note_attributes[1]['value'];
				$full_address = $address ." ". $address2 .",city:".$city .",province:".$province.",country:".$country."-zip:".$zip;
				if($singleorder['fulfillment_status'] == '')
				{
					$fulfillment_status = 'Unfulfilled';
				}
				else 
				{
					$fulfillment_status = $singleorder['fulfillment_status'];
				}
				$disabled1='';
				if($note_value!=''){
					$disabled1="";
				}
				$line_items=$singleorder['line_items'];
				foreach($line_items as $line_items){
						$quantity_total=$quantity_total+ $line_items['quantity']; //Image Source
					}
				
				echo "<tr>";
				echo '<td><input  type="checkbox" $disabled1 class="select_box" name="order_ids_'.$id.'"  value="'.$id.'"  data-financial_status="'.$financial_status.'" data-total_weight="'.$total_weight.'" data-quantity_total="'.$quantity_total.'" data-customer_total-price="'.$total_price.'" data-customer_email="'.$email.'" data-customer_name="'.$customer_name.'" data-fulladdress="'.$full_address.'" data-gateway="'.$gateway.'" data-customer_phone="'.$customer_phone.'"></td>';
				echo "<td>".$name."</td>";
				echo "<td>".$created_at."</td>";
				echo "<td>".$customer_name."</td>";
				echo "<td>".$financial_status."</td>";
				echo "<td>".$fulfillment_status."</td>";
				echo "<td>".$total_price."</td>";
				echo "<td>".$note_value."<a href='javascript:void(0);' data-id='$id' data-tracking_code='$note_value' class='put_track'>Apply Tracking Code</a>"."</td>";
				echo "</tr>";
					
			}
			 echo '</tbody>';
			  echo '</table>';
			}
	else{
	echo "<div class='no-result'>No Order</div>";
	}
}
catch (shopify\ApiException $e)
{
	# HTTP status code was >= 400 or response contained the key 'errors'
	echo $e;
	print_r($e->getRequest());
	print_r($e->getResponse());
}


echo "<a href='#popup_content' class='fancybox_btn'>Submit</a>";


?>
