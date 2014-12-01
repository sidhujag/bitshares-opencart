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

class ModelPaymentBitShares extends Model
{
	// invalid status was defined for each order as it was submitted(default), if it is overrided by user we don't processs it. (ie: admin cancelled the order for some reason)
	// also if order is being processed already (partial transfer) then we want to proceed to check to see if user has completed full payment now
	public function getOpenOrders(){
		$order_query = $this->db->query("SELECT order_id, total, currency_code, currency_value, date_added FROM `" . DB_PREFIX . "order` WHERE `order_status_id` = " . $this->config->get('bitshares_invalid_status_id') . " OR `order_status_id` = " . $this->config->get('bitshares_processing_status_id'));
		return $order_query->rows;
	}
	public function findOrderComment($id, $comment)
	{
		$order_query = $this->db->query("SELECT `comment` FROM `" . DB_PREFIX . "order_history` WHERE `order_id` = '".$id."' AND `comment` = '".$comment."' LIMIT 1");
		if($order_query->num_rows) {
			return $order_query->row['comment'];
		}
		return "";
	}
	public function updateCronJobRunTime() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `group` = 'bitshares_checkout' AND `key` = 'bitshares_last_cron_job_run'");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`store_id`, `group`, `key`, `value`, `serialized`) VALUES (0, 'bitshares_checkout', 'bitshares_last_cron_job_run', NOW(), 0)");
	}
    /**
     * @param string $address
     *
     * @return array
     */
    public function getMethod($address)
    {
        $this->load->language('payment/bitshares');

        if ($this->config->get('bitshares_status'))
        {
            $status = TRUE;
        }
        else
        {
            $status = FALSE;
        }

        $method_data = array();

        if ($status)
        { 
            $method_data = array( 
                'code'       => 'bitshares',
                'title'      => $this->language->get('text_title'),
                'sort_order' => ''
            );
        }

        return $method_data;
    }
}
