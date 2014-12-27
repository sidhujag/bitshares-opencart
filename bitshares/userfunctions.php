<?php

function isOrderCompleteHelper($memo, $order_id)
{
	$response = sendToCart('index.php?route=payment/bitshares/getordercomplete&memo='.$memo.'&order_id='.$order_id);
	if(isset($response['order_id']))
	{
		return $response;
	}
	return FALSE;	
}
function doesOrderExistHelper($memo, $order_id)
{
	$response = sendToCart('index.php?route=payment/bitshares/getorder&memo='.$memo.'&order_id='.$order_id);
	if(isset($response['order_id']))
	{
		return $response;
	}
	return FALSE;
}

function getOrderComplete($memo, $order_id)
{	
  $orders = array();
  $myorder = isOrderCompleteHelper($memo, $order_id);
  if($myorder !== FALSE)
  {
    array_push($orders, $myorder);
  }
  return $orders;
}

function getOrder($memo, $order_id)
{
  $orders = array();
  $myorder = doesOrderExistHelper($memo, $order_id);
  if($myorder !== FALSE)
  {
    array_push($orders, $myorder);
  }
  return $orders;
}
function completeOrderUser($memo, $order_id)
{
	global $baseURL;
	$response = sendToCart('index.php?route=payment/bitshares/complete&memo='.$memo.'&order_id='.$order_id);
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
	$response = sendToCart('index.php?route=payment/bitshares/cancel&memo='.$memo.'&order_id='.$order_id);
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
	return sendToCart('index.php?route=payment/bitshares/cron&token='.$cronToken);	
}
function createOrderUser()
{

	global $accountName;
	$response = sendToCart('index.php?route=payment/bitshares/create&order_id='.$_REQUEST['order_id']);
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

function sendToCart($url)
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
?>