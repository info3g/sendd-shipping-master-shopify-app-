<?php 
// Required File Start.........
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/connection.php'; //DB connectivity
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
// Required File END...........
error_reporting(E_ALL);
ini_set('display_errors', 1);
$access_token=$_REQUEST['access_token'];
$shopify = shopify\client($_REQUEST['shop'], SHOPIFY_APP_API_KEY, $access_token );
$order_count = $shopify('GET /admin/orders/count.json?fulfillment_status=unshipped');
	$limit=20; // Number of order per page
	$noofPages=$order_count/$limit;
	 echo $noofPages=ceil($noofPages);
?>
