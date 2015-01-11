<?php
/**
 * The MIT License (MIT)
 * 
 * Copyright (c) 2011-2014 BitShares
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class ControllerPaymentBitShares extends Controller
{

    // below is the url that can take you do the order information
    // http://127.0.0.1/~spair/store/index.php?route=account/order/info&order_id=35

    /**
     * @var string
     */
    private $payment_module_name  = 'bitshares';

    /**
     */
    public function index()
    {
        $this->language->load('payment/'.$this->payment_module_name);

    	$data['button_bitshares_confirm'] = $this->language->get('button_bitshares_confirm');
    	
		
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bitshares.tpl'))
        {
			$this->template = $this->config->get('config_template') . '/template/payment/bitshares.tpl';
        }
        else
        {
			$this->template = 'default/template/payment/bitshares.tpl';
		}	
		
		return $this->load->view($this->template, $data);
	}

    /**
     * @param string $contents
     */
    function log($contents)
    {
		error_log($contents);
	}
	public function complete()
	{
		$response = array();
		if(isset($this->request->get['memo']) && isset($this->request->get['order_id']))
		{
			require DIR_APPLICATION.'../bitshares/bts_lib.php';
			require DIR_APPLICATION.'../bitshares/config.php';
			$this->language->load('payment/'.$this->payment_module_name);	
			$this->load->model('checkout/order');
			$this->load->model('payment/bitshares');	
			$order_id = $this->request->get['order_id'];
			$memo = $this->request->get['memo'];
			
			
			$orders = $this->model_payment_bitshares->getOpenOrder($order_id);
			foreach ($orders as $order) {			
			
				$total    = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false);
				$asset    = btsCurrencyToAsset($order['currency_code']);
				$hash = btsCreateEHASH($accountName, $order_id, $total,$asset, $hashSalt);
				$memoSanity = btsCreateMemo($hash);
				if($memoSanity !== $memo)
				{
				  $ret = array();
				  $ret['error'] = 'error';
				  die(json_encode($ret));
				}
				$demo = FALSE;
				if($demoMode == "1" || $demoMode == 1 || $demoMode == "true" || $demoMode == TRUE)
				{
					$demo = TRUE;
				}
				$myorder = array();
				$myorder['order_id'] = $order_id;
				$myorder['total'] = $total;
				$myorder['asset'] = $asset;
				$myorder['memo'] = $memo;
				$myorder['date_added'] = 0;
				$orderArray = array();
				array_push($orderArray, $myorder);
				// sync up orders with your blockchain wallet
				$response   = btsVerifyOpenOrders($orderArray, $accountName, $rpcUser, $rpcPass, $rpcPort,$hashSalt, $demo);
				if(array_key_exists('error', $response))
				{
				  $ret = array();
				  $ret['error'] = 'error';
				  die(json_encode($ret));
				}			
				foreach ($response as $responseOrder) {
					// update the order based on response status (processing for partial funds and complete for full funds)	
					switch($responseOrder['status'])
					{
						case 'complete':
							$this->model_checkout_order->addOrderHistory($responseOrder['order_id'], $this->config->get($this->payment_module_name.'_confirmed_status_id'), $this->language->get('text_confirmed'), true);
							break;
						case 'overpayment':
							$comment = $this->language->get('text_confirmed'). '. There was an overpayment of '.$responseOrder['amountOverpaid'].' '.$responseOrder['asset']. ' Please contact us for a refund of the overpayment';
							$this->model_checkout_order->addOrderHistory($responseOrder['order_id'], $this->config->get($this->payment_module_name.'_confirmed_status_id'), $comment, true);
							break;
						default:
							break;							
							
					}
					 
				}
			}	
		}
		$response['url'] = $this->url->link('checkout/success', '', 'SSL');	
		die(json_encode($response));
	}
	public function getorders()
	{
		$response = array();
		
		require DIR_APPLICATION.'../bitshares/config.php';
		require DIR_APPLICATION.'../bitshares/bts_lib.php';	
		$this->load->model('payment/bitshares');
		
		$orders = $this->model_payment_bitshares->getOpenOrders();
		foreach ($orders as $order) {
			$myorder = array();
			$myorder['order_id'] = $order['order_id'];
			$myorder['asset'] = btsCurrencyToAsset($order['currency_code']);
			$myorder['total'] = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false);
			$myorder['date_added'] = 0;
			array_push($response,$myorder);
		}			
		die(json_encode($response));
	}	
		
	public function getorder()
	{
		$response = array();
		if(isset($this->request->get['memo']) && isset($this->request->get['order_id']))
			{	
			require DIR_APPLICATION.'../bitshares/config.php';
			require DIR_APPLICATION.'../bitshares/bts_lib.php';	
			$this->load->model('payment/bitshares');
			
			$orders = $this->model_payment_bitshares->getOpenOrder($this->request->get['order_id']);
			foreach ($orders as $order) {
				$myorder = array();
				$myorder['order_id'] = $this->request->get['order_id'];
				$myorder['asset'] = btsCurrencyToAsset($order['currency_code']);
				$myorder['total'] = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false);
				$hash = btsCreateEHASH($accountName,$myorder['order_id'],  $myorder['total'], $myorder['asset'], $hashSalt);
				$memo = btsCreateMemo($hash);
				$myorder['memo'] = $memo;
				$myorder['date_added'] = 0;
				if($memo === $this->request->get['memo'])
				{
					die(json_encode($myorder));
				}
			}			
					
			
		}
		die(json_encode($response));
	}	
	public function getordercomplete()
	{
		$response = array();
		if(isset($this->request->get['memo']) && isset($this->request->get['order_id']))
			{	
			require DIR_APPLICATION.'../bitshares/config.php';
			require DIR_APPLICATION.'../bitshares/bts_lib.php';
			$this->language->load('payment/'.$this->payment_module_name);			
			$this->load->model('payment/bitshares');

			$completeorders    = $this->model_payment_bitshares->getCompleteOrder($this->request->get['order_id']);
			foreach ($completeorders as $order) {
				$myorder = array();
				$myorder['order_id'] = $this->request->get['order_id'];
				$myorder['asset'] = btsCurrencyToAsset($order['currency_code']);
				$myorder['total'] = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false);
				$hash = btsCreateEHASH($accountName,$myorder['order_id'],  $myorder['total'], $myorder['asset'], $hashSalt);
				$memo = btsCreateMemo($hash);
				$myorder['memo'] = $memo;
				if($memo === $this->request->get['memo'])
				{
					die(json_encode($myorder));
				}
			}			
					
			
		}
		die(json_encode($response));
	}	
	
	public function cancel()
	{
		$res = array();
		if(isset($this->request->get['order_id']) && isset($this->request->get['memo']))
		{
			require DIR_APPLICATION.'../bitshares/config.php';
			require DIR_APPLICATION.'../bitshares/bts_lib.php';
			$this->language->load('payment/'.$this->payment_module_name);	
			$this->load->model('checkout/order');
			$this->load->model('payment/bitshares');
			
			$order_id = $this->request->get['order_id'];
			$memo = $this->request->get['memo'];
			$orders = $this->model_payment_bitshares->getOpenOrder($order_id);
			foreach ($orders as $order) {
				$total    = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false);
				$asset    = btsCurrencyToAsset($order['currency_code']);
				$hash = btsCreateEHASH($accountName, $order_id, $total,$asset, $hashSalt);
				$memoSanity = btsCreateMemo($hash);
				if($memoSanity === $memo)
				{			
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get($this->payment_module_name.'_invalid_status_id'), $this->language->get('text_cancelled'), true);	
					$res['url'] = $this->url->link('checkout/failure', '', 'SSL');
					die(json_encode($res));		
				}
				else
				{
					$res['error'] = 'error';
				}					
			}
		}

		$res['error'] = 'error';	
		die(json_encode($res));					
	}
    /**
     */
    public function send()
    {
		$ret = array();
		$ret['order_id']= $this->session->data['order_id'];
		die(json_encode($ret));
    }
    /**
     */
    public function create()
    {
		$ret = array(); 
		if (isset($this->request->get['order_id']))
			{
			require DIR_APPLICATION.'../bitshares/config.php';
			require DIR_APPLICATION.'../bitshares/bts_lib.php';
			$this->language->load('payment/'.$this->payment_module_name);	
			$this->load->model('checkout/order');
			 
			$order_id = $this->request->get['order_id'];
			$order    = $this->model_checkout_order->getOrder($order_id);

			$total    = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false);
			$asset    = btsCurrencyToAsset($order['currency_code']);
			
			$hash = btsCreateEHASH($accountName, $order_id, $total,$asset, $hashSalt);
			$memo = btsCreateMemo($hash);
			$invoiceURL = btsCreateInvoice($accountName, $order_id, $memo);
			if($order['order_status_id'] !== $this->config->get($this->payment_module_name.'_processing_status_id')
			&&  $order['order_status_id'] !== $this->config->get($this->payment_module_name.'_confirmed_status_id'))
			{				
				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get($this->payment_module_name.'_processing_status_id'), $this->language->get('text_waiting').'. <a href="'.$invoiceURL.'">Click here</a> to pay and complete your transaction.', true);
				
			}
			$ret['memo'] = $memo;
		}		
        die(json_encode($ret));
    }
}
