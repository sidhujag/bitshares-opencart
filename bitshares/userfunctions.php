<?php
function sendToCart($url)
{
	$ch = curl_init(baseURL.$url);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, '15');
	$response = curl_exec($ch);
	if ($response === false){
		debuglog('request to opencart failed');
		debuglog('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
    $res = array();
    $res['error'] = curl_error($ch);
    curl_close($ch);
    return $res;
  }
			
	curl_close($ch);
	return json_decode($response, true);
}
function getOpenOrdersUser()
{
	return sendToCart('index.php?route=payment/bitshares/getorders');
}

function isOrderCompleteUser($memo, $order_id)
{
	$response = sendToCart('index.php?route=payment/bitshares/getordercomplete&memo='.$memo.'&order_id='.$order_id);
	if(isset($response['order_id']))
	{
		return $response;
	}
	return FALSE;	
}
function doesOrderExistUser($memo, $order_id)
{
	$response = sendToCart('index.php?route=payment/bitshares/getorder&memo='.$memo.'&order_id='.$order_id);
	if(isset($response['order_id']))
	{
		return $response;
	}
	return FALSE;
}

function completeOrderUser($order)
{

	$response = sendToCart('index.php?route=payment/bitshares/complete&memo='.$order['memo'].'&order_id='.$order['order_id']);
	if(array_key_exists('error', $response))
	{	
		$response['error'] = 'OpenCart could not complete this order!';
		$response['url'] = NULL;
		return $response;
	}	
	return $response;
}
function cancelOrderUser($order)
{
	$response = sendToCart('index.php?route=payment/bitshares/cancel&memo='.$order['memo'].'&order_id='.$order['order_id']);
	if(array_key_exists('error', $response))
	{	
		$response['error'] = 'OpenCart could not cancel this order!';
		$response['url'] = NULL;	
		return $response;
	}	
	return $response;
}
function cronJobUser()
{
	return 'Success!';
}
function createOrderUser()
{

	$response = sendToCart('index.php?route=payment/bitshares/create&order_id='.$_REQUEST['order_id']);
	if(array_key_exists('error', $response) || !isset($response['memo']))
	{	
		$response['error'] = 'OpenCart could not create this order!';		
		return $response;
	}	
	$ret = array(
		'accountName'     => accountName,
		'order_id'     => $_REQUEST['order_id'],
		'memo'     => $response['memo']
	);
	return $ret;	
}
?>