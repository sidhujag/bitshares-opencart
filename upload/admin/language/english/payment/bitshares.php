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

$_['heading_title'] = 'BitShares';

// Text
$_['text_edit']        = 'Edit Bitshares Module';
$_['text_payment'] = 'Payment';
$_['text_success'] = 'Success: You have modified the bitshares payment module!';
$_['text_bitshares']  = '<img src="view/image/payment/bitshares.png" alt="bitshares" title="bitshares" style="border: 0px solid #EEEEEE;" />';
$_['text_bitshares_join']  = 'You must have a Bitshares client to install this plugin, <a href="http://bitshares.org" target="_blank" title="Click here to download the Bitshares client" class="alert-link">click here</a> to download.';
$_['text_last_cron_job_run'] = "Last cron job's run time:";
$_['text_cron_job_url'] = "Cron Job's URL:";
$_['help_cron_job_url'] = "Set a cron job to call this URL. Click Save to use this URL.";
$_['text_cron_job_token'] = "Secret Token";
$_['help_cron_job_token'] = "Make this long and hard to guess, don't give it to anyone";
$_['help_user_wallet'] = "Leave empty for 'default'";
$_['help_user_account'] = "ie: 'bobsmith'";
$_['help_rpc_user'] = "Set this in your Bitshares config or via console in your Bitshares Wallet(--rpcuser)";
$_['help_rpc_pass'] = "Set this in your Bitshares config or via console in your Bitshares Wallet(--rpcpassword)";
$_['help_rpc_port'] = "Set this in your Bitshares config or via console in your Bitshares Wallet(--httpport)";
$_['help_demo'] = "Demo mode allows you to pay for items in any asset ie: 100 BTS for items sold in $100 USD/EUR/GBP etc, do not use in real sites. Enable to demo/test plugin functionality. ";
$_['text_user_wallet'] = "BTS wallet name:";
$_['text_user_account'] = "BTS account name:";
$_['text_rpc_user'] = "RPC user:";
$_['text_rpc_pass'] = "RPC password:";
$_['text_rpc_port'] = "HTTP RPC port:";

// Entry
$_['entry_confirmed_status']  = 'Confirmed Status:';
$_['entry_processing_status'] = 'Processing Status:';
$_['entry_invalid_status']    = 'Post-Payment Status:';
$_['entry_status']            = 'Status:';
$_['entry_demo']             = 'Demo Mode:';

// Error
$_['error_permission'] = 'Warning: You do not have permission to modify Bitshares module.';
$_['error_user_wallet'] = 'Error: You must define a Bitshares user wallet so customers can pay you.';
$_['error_user_account'] = 'Error: You must define a Bitshares user account so customers can pay you.';
$_['error_rpc_user'] = 'Error: You must define a Bitshares RPC user name so this module can authenticate against your running Bitshares client.';
$_['error_rpc_pass'] = 'Error: You must define a Bitshares RPC password so this module can authenticate against your running Bitshares client.';
$_['error_rpc_port'] = 'Error: You must define a Bitshares RPC port so this module can authenticate against your running Bitshares client.';
$_['error'] = 'Validation Error! ';
