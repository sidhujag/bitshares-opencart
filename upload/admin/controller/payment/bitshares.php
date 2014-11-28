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

    /**
     * @var array
     */
    private $error = array();

    /**
     * @var string
     */
    private $payment_module_name  = 'bitshares';

    /**
     * @return boolean
     */
    private function validate()
    {
		require DIR_APPLICATION.'../bitshares/bts_lib.php';
		$walletName = $this->request->post[$this->payment_module_name.'_user_wallet'];
		$account    = $this->request->post[$this->payment_module_name.'_user_account'];
		$rpcUser    = $this->request->post[$this->payment_module_name.'_rpc_user'];
		$rpcPass    = $this->request->post[$this->payment_module_name.'_rpc_pass'];
		$rpcPort    = $this->request->post[$this->payment_module_name.'_rpc_port'];
		$response = btsValidateRPC($walletName, $account, $rpcUser, $rpcPass,$rpcPort);

        if (!$this->user->hasPermission('modify', 'payment/'.$this->payment_module_name))
        {
            $this->error['warning'] = $this->language->get('error_permission');
        }
		if(array_key_exists('error', $response))
		{
			
				$this->error['error'] = $this->language->get('error') . $response['error'];
			
		}

	
        if (!$this->error)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }	
    }
	public function getLastCronJobRunTime() {
		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `group` = 'bitshares_checkout' AND `key` = 'bitshares_last_cron_job_run'")->row;
	}
    /**
     */
    public function index()
    {
        $this->load->language('payment/'.$this->payment_module_name);
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate()))
        {
            $this->model_setting_setting->editSetting($this->payment_module_name, $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
        }
        if (isset($this->error['warning']))
        {
            $this->data['error_warning'] = $this->error['warning'];
        }
        else
        {
            $this->data['error_warning'] = '';
        }
        if (isset($this->error['error']))
        {
            $this->data['error'] = $this->error['error'];
        }
        else
        {
            $this->data['error'] = '';
        }
        //$this->document->title = $this->language->get('heading_title'); // for 1.4.9
        $this->document->setTitle($this->language->get('heading_title')); // for 1.5.0 thanks rajds 

        $this->data['heading_title']           = $this->language->get('heading_title');
        $this->data['text_enabled']            = $this->language->get('text_enabled');
        $this->data['text_disabled']           = $this->language->get('text_disabled');
        $this->data['text_high']               = $this->language->get('text_high');
        $this->data['text_medium']             = $this->language->get('text_medium');
        $this->data['text_low']                = $this->language->get('text_low');
        $this->data['entry_confirmed_status']  = $this->language->get('entry_confirmed_status');
        $this->data['entry_processing_status'] = $this->language->get('entry_processing_status');
        $this->data['entry_invalid_status']    = $this->language->get('entry_invalid_status');
        $this->data['entry_status']            = $this->language->get('entry_status');
        $this->data['button_save']             = $this->language->get('button_save');
        $this->data['button_cancel']           = $this->language->get('button_cancel');
        $this->data['tab_general']             = $this->language->get('tab_general');
		$this->data['text_cron_job_token'] = $this->language->get('text_cron_job_token');
		$this->data['help_cron_job_token'] = $this->language->get('help_cron_job_token');
		$this->data['text_cron_job_url'] = $this->language->get('text_cron_job_url');
		$this->data['help_cron_job_url'] = $this->language->get('help_cron_job_url');
		$this->data['text_last_cron_job_run'] = $this->language->get('text_last_cron_job_run');
		$this->data['text_user_wallet'] = $this->language->get('text_user_wallet');
		$this->data['text_user_account'] = $this->language->get('text_user_account');
		$this->data['text_rpc_user'] = $this->language->get('text_rpc_user');
		$this->data['text_rpc_pass'] = $this->language->get('text_rpc_pass');
		$this->data['text_rpc_port'] = $this->language->get('text_rpc_port');
		
		$this->data['help_user_wallet'] = $this->language->get('help_user_wallet');
		$this->data['help_user_account'] = $this->language->get('help_user_account');
		$this->data['help_rpc_user'] = $this->language->get('help_rpc_user');
		$this->data['help_rpc_pass'] = $this->language->get('help_rpc_pass');
		$this->data['help_rpc_port'] = $this->language->get('help_rpc_port');		

        $this->data['breadcrumbs']   = array();
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_payment'),
            'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('payment/bitshares', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/'.$this->payment_module_name.'&token=' . $this->session->data['token'];
        $this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];	

        $this->load->model('localisation/order_status');
        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        
        if (isset($this->request->post[$this->payment_module_name.'_processing_status_id']))
        {
            $this->data[$this->payment_module_name.'_processing_status_id'] = $this->request->post[$this->payment_module_name.'_processing_status_id'];
        }
        else
        {
            $this->data[$this->payment_module_name.'_processing_status_id'] = $this->config->get($this->payment_module_name.'_processing_status_id'); 
        }
        if (isset($this->request->post[$this->payment_module_name.'_confirmed_status_id']))
        {
            $this->data[$this->payment_module_name.'_confirmed_status_id'] = $this->request->post[$this->payment_module_name.'_confirmed_status_id'];
        }
        else
        {
            $this->data[$this->payment_module_name.'_confirmed_status_id'] = $this->config->get($this->payment_module_name.'_confirmed_status_id'); 
        } 

        if (isset($this->request->post[$this->payment_module_name.'_invalid_status_id']))
        {
            $this->data[$this->payment_module_name.'_invalid_status_id'] = $this->request->post[$this->payment_module_name.'_invalid_status_id'];
        }
        else
        {
            $this->data[$this->payment_module_name.'_invalid_status_id'] = $this->config->get($this->payment_module_name.'_invalid_status_id'); 
        } 


        if (isset($this->request->post[$this->payment_module_name.'_status']))
        {
            $this->data[$this->payment_module_name.'_status'] = $this->request->post[$this->payment_module_name.'_status'];
        }
        else
        {
            $this->data[$this->payment_module_name.'_status'] = $this->config->get($this->payment_module_name.'_status');
        }

   
		if (isset($this->request->post[$this->payment_module_name.'_cron_job_token'])) {
			$this->data[$this->payment_module_name.'_cron_job_token'] = $this->request->post[$this->payment_module_name.'_cron_job_token'];
		} elseif ($this->config->get($this->payment_module_name.'_cron_job_token')) {
			$this->data[$this->payment_module_name.'_cron_job_token'] = $this->config->get($this->payment_module_name.'_cron_job_token');
		} else {
			$this->data[$this->payment_module_name.'_cron_job_token'] = sha1(uniqid(mt_rand(), 1));
		}
		
		if (isset($this->request->post[$this->payment_module_name.'_user_wallet'])) {
			$this->data[$this->payment_module_name.'_user_wallet'] = $this->request->post[$this->payment_module_name.'_user_wallet'];
		} elseif ($this->config->get($this->payment_module_name.'_user_wallet')) {
			$this->data[$this->payment_module_name.'_user_wallet'] = $this->config->get($this->payment_module_name.'_user_wallet');
		} else {
			$this->data[$this->payment_module_name.'_user_wallet'] = 'default';
		}			
        if (isset($this->request->post[$this->payment_module_name.'_user_account']))
        {
            $this->data[$this->payment_module_name.'_user_account'] = $this->request->post[$this->payment_module_name.'_user_account'];
        }
        else
        {
            $this->data[$this->payment_module_name.'_user_account'] = $this->config->get($this->payment_module_name.'_user_account');
        }
        if (isset($this->request->post[$this->payment_module_name.'_rpc_user']))
        {
            $this->data[$this->payment_module_name.'_rpc_user'] = $this->request->post[$this->payment_module_name.'_rpc_user'];
        }
        else
        {
            $this->data[$this->payment_module_name.'_rpc_user'] = $this->config->get($this->payment_module_name.'_rpc_user');
        }
        if (isset($this->request->post[$this->payment_module_name.'_rpc_pass']))
        {
            $this->data[$this->payment_module_name.'_rpc_pass'] = $this->request->post[$this->payment_module_name.'_rpc_pass'];
        }
        else
        {
            $this->data[$this->payment_module_name.'_rpc_pass'] = $this->config->get($this->payment_module_name.'_rpc_pass');
        }
        if (isset($this->request->post[$this->payment_module_name.'_rpc_port']))
        {
            $this->data[$this->payment_module_name.'_rpc_port'] = $this->request->post[$this->payment_module_name.'_rpc_port'];
        }
        else
        {
            $this->data[$this->payment_module_name.'_rpc_port'] = $this->config->get($this->payment_module_name.'_rpc_port');
        }                      		
		$this->data[$this->payment_module_name.'_cron_job_url'] = HTTPS_CATALOG . 'index.php?route=payment/bitshares/cron&token=' . $this->data[$this->payment_module_name.'_cron_job_token'];

		$this->load->model('payment/bitshares'); 
		$timeObj = $this->model_payment_bitshares->getLastCronJobRunTime();
		if(isset($timeObj) && isset($timeObj['value']))
		{
			$this->data[$this->payment_module_name.'_last_cron_job_run'] = $timeObj['value']; 
		}
		else
		{
			$this->data[$this->payment_module_name.'_last_cron_job_run'] = 'Never';
		}
        $this->template = 'payment/'.$this->payment_module_name.'.tpl';
        $this->children = array(
            'common/header',	
            'common/footer'	
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }
}
