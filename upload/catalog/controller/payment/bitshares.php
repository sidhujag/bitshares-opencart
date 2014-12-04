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
    	$data['button_bitshares_complete'] = $this->language->get('button_bitshares_complete');
    	
		$data['continue']              = $this->url->link('checkout/success', '', 'SSL');
		
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

    /**
     */
    public function send()
    {
		require DIR_APPLICATION.'../bitshares/bts_lib.php';
		$this->language->load('payment/'.$this->payment_module_name);	
        $this->load->model('checkout/order');

        $order    = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $price    = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false);
        $currency    = $order['currency_code'];
        $account  = $this->config->get($this->payment_module_name.'_user_account');

        $response = btsCreateInvoice($account, $order['order_id'], $price, $currency);

        $this->model_checkout_order->addOrderHistory($order['order_id'], $this->config->get($this->payment_module_name.'_invalid_status_id'), $this->language->get('text_waiting').'. Please use the code <a href="'.$response['url'].'"><b>'.$response['orderEHASH'].'</b></a> in the memo of your transaction so we can track payments towards your order', true);
        if(array_key_exists('error', $response))
        {
            $this->log("communication error");
			$this->log(var_export($response['error'], true));
            echo "{\"error\": \"Error: Problem communicating with payment provider.\\nPlease try again later.\"}";
        }
        else
        {
            echo "{\"url\": \"" . $response['url'] . "\"}";
          
        }
    }
    // todo add autodelete orders based on a time setting in config
	public function cron() {
		// use server token for cron jobs for security reasons.. sanity against a ddos
		if (isset($this->request->get['token']) && $this->request->get['token'] == $this->config->get($this->payment_module_name.'_cron_job_token') && $this->config->get($this->payment_module_name.'_status') == 1) {
			$log = new Log('bitshares.log');
			
			$this->load->model('checkout/order');
			$this->load->model('payment/bitshares');
			$this->language->load('payment/'.$this->payment_module_name);
			$openOrderList = array();
			$openOrders = $this->model_payment_bitshares->getOpenOrders();
			foreach ($openOrders as $order) {
				$order['total'] = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false);
				
				array_push($openOrderList,$order);
				echo 'Order: ' . $order['order_id'] . '<br>';
			}
			require DIR_APPLICATION.'../bitshares/bts_lib.php';

			$account  = $this->config->get($this->payment_module_name.'_user_account');
			$rpcUser    = $this->config->get($this->payment_module_name.'_rpc_user');
			$rpcPass    = $this->config->get($this->payment_module_name.'_rpc_pass');
			$rpcPort    = $this->config->get($this->payment_module_name.'_rpc_port');
			// sync up orders with your blockchain wallet
			$response   = btsVerifyOpenOrders($openOrderList, $account, $rpcUser, $rpcPass, $rpcPort, $this->config->get($this->payment_module_name.'_demo'));
			if(array_key_exists('error', $response))
			{
				$log->write('CrobJob error: ' .$response['error']);
				return;
			}
			foreach ($response as $responseOrder) {
				// update the order based on response status (processing for partial funds and complete for full funds)	
				switch($responseOrder['status'])
				{
					case 'complete':
						$order_id = $responseOrder['order_id'];
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get($this->payment_module_name.'_confirmed_status_id'), $this->language->get('text_confirmed'), true);
						break;
					case 'processing':
						$order_id = $responseOrder['order_id'];
						
						
						$comment = $responseOrder['amountReceived'].' '.$responseOrder['asset'] . ' received, '. ($responseOrder['total']-$responseOrder['amountReceived']).' '.$responseOrder['asset'] . ' remaining. Reminder, use code <a href="'.$responseOrder['url'].'"><b>'.$responseOrder['orderEHASH'].'</b></a> in the memo of your transactions.';
						$orderComment = $this->model_payment_bitshares->findOrderComment($order_id, $comment);
						if(strlen($orderComment) <= 0)
						{
							$this->model_checkout_order->addOrderHistory($order_id, $this->config->get($this->payment_module_name.'_processing_status_id'), $comment, true);
						}
						break;
					case 'overpayment':
						$order_id = $responseOrder['order_id'];
						$comment = $this->language->get('text_confirmed'). '. There was an overpayment of '.$responseOrder['amountOverpaid'].' '.$responseOrder['asset']. ' Please contact us for a refund of the overpayment';
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get($this->payment_module_name.'_confirmed_status_id'), $comment, true);
						break;						
						
				}
				 
			}
			
			$this->model_payment_bitshares->updateCronJobRunTime();
		}
	}	
}
