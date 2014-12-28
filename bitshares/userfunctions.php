<?php
function sendToCartHelper($url)
{
	global $baseURL;
	$ch = curl_init($baseURL.$url);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, '15');
	$response = curl_exec($ch);
	if ($response === false){
		debuglog('request to opencart failed');
		debuglog('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
	}
			
	curl_close($ch);
	return json_decode($response, true);
}
function isOrderComplete($memo, $order_id)
{
	$response = sendToCartHelper('index.php?route=payment/bitshares/getordercomplete&memo='.$memo.'&order_id='.$order_id);
	if(isset($response['order_id']))
	{
		return $response;
	}
	return FALSE;	
}
function doesOrderExist($memo, $order_id)
{
	$response = sendToCartHelper('index.php?route=payment/bitshares/getorder&memo='.$memo.'&order_id='.$order_id);
	if(isset($response['order_id']))
	{
		return $response;
	}
	return FALSE;
}

function completeOrderUser($memo, $order_id)
{

	global $baseURL;
	$response = sendToCartHelper('index.php?route=payment/bitshares/complete&memo='.$memo.'&order_id='.$order_id);
	if(array_key_exists('error', $response))
	{	
		$response['error'] = 'OpenCart could not complete this order!';	
		return $response;
	}	
	return $response;
}
function cancelOrderUser($memo, $order_id)
{
	global $baseURL;
	$response = sendToCartHelper('index.php?route=payment/bitshares/cancel&memo='.$memo.'&order_id='.$order_id);
	if(array_key_exists('error', $response))
	{	
		$response['error'] = 'OpenCart could not cancel this order!';	
		return $response;
	}	
	return $response;
}
function cronJobUser()
{
	global $cronToken;
	return sendToCartHelper('index.php?route=payment/bitshares/cron&token='.$cronToken);	
}
function createOrderUser()
{

	global $accountName;
	$response = sendToCartHelper('index.php?route=payment/bitshares/create&order_id='.$_REQUEST['order_id']);
	if(array_key_exists('error', $response) || !isset($response['memo']))
	{	
		$response['error'] = 'OpenCart could not create this order!';		
		return $response;
	}	
	$ret = array(
		'accountName'     => $accountName,
		'order_id'     => $_REQUEST['order_id'],
		'memo'     => $response['memo']
	);
	return $ret;	
}
?>