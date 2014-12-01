<?php echo $header; ?>
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
?>

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($error) { ?>
<div class="error"><?php echo $error; ?></div>
<?php } ?>  
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="bitshares_status"> 
              <?php if ($bitshares_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>       
        </tr> 
        <tr>
          <td><?php echo $demo_status; ?><br />
                <span class="help"><?php echo $help_demo ?></span></td>        
           <td><select name="bitshares_demo"> 
              <?php if ($bitshares_demo) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td> 
          </tr>
          <tr>
            <td><?php echo $entry_processing_status; ?></td>
            <td><select name="bitshares_processing_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $bitshares_processing_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>        
          <tr>
            <td><?php echo $entry_confirmed_status; ?></td>
            <td><select name="bitshares_confirmed_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $bitshares_confirmed_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_invalid_status; ?></td>
            <td><select name="bitshares_invalid_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $bitshares_invalid_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
         
        <tr>
            <td>
                <label for="bitshares_cron_job_token"><?php echo $text_cron_job_token ?></label><br />
                <span class="help"><?php echo $help_cron_job_token ?></span>
            </td>
            <td><input name="bitshares_cron_job_token" value="<?php echo $bitshares_cron_job_token ?>" id="bitshares_cron_job_token" /></td>
          </tr>
          <tr>
            <td>
                <?php echo $text_cron_job_url ?><br />
                <span class="help"><?php echo $help_cron_job_url ?></span>
            </td>
            <td>
                <span id="cron-job-url"><?php echo $bitshares_cron_job_url ?></span>
            </td>
          </tr>
          <tr>
            <td><?php echo $text_last_cron_job_run ?></td>
            <td><label name="bitshares_last_cron_job_run" id="bitshares_last_cron_job_run"><?php echo $bitshares_last_cron_job_run ?></label></td>
          </tr>
        <tr>
            <td>
                <label for="bitshares_user_wallet"><?php echo $text_user_wallet ?></label><br />
                <span class="help"><?php echo $help_user_wallet ?></span>
            </td>
            <td><input name="bitshares_user_wallet" value="<?php echo $bitshares_user_wallet ?>" id="bitshares_user_wallet" /></td>
       </tr> 
        <tr>
            <td>
                <label for="bitshares_user_account"><?php echo $text_user_account ?></label><br />
                <span class="help"><?php echo $help_user_account ?></span>
            </td>
            <td><input name="bitshares_user_account" value="<?php echo $bitshares_user_account ?>" id="bitshares_user_account" /></td>
       </tr>
         <tr>
            <td>
                <label for="bitshares_rpc_user"><?php echo $text_rpc_user ?></label><br />
                <span class="help"><?php echo $help_rpc_user ?></span>
            </td>
            <td><input name="bitshares_rpc_user" value="<?php echo $bitshares_rpc_user ?>" id="bitshares_rpc_user" /></td>
       </tr>
          <tr>
            <td>
                <label for="bitshares_rpc_pass"><?php echo $text_rpc_pass ?></label><br />
                <span class="help"><?php echo $help_rpc_pass ?></span>
            </td>
            <td><input name="bitshares_rpc_pass" value="<?php echo $bitshares_rpc_pass ?>" id="bitshares_rpc_pass" /></td>
       </tr>
          <tr>
            <td>
                <label for="bitshares_rpc_port"><?php echo $text_rpc_port ?></label><br />
                <span class="help"><?php echo $help_rpc_port ?></span>
            </td>
            <td><input name="bitshares_rpc_port" value="<?php echo $bitshares_rpc_port ?>" id="bitshares_rpc_port" /></td>
       </tr>        
      </table>
    </form>
  </div>
</div>
</div>
<script type="text/javascript">
  <!--
  
$('input[name="bitshares_cron_job_token"]').keyup(function(){
    $('#cron-job-url').html('<?php echo HTTPS_CATALOG ?>index.php?route=payment/bitshares/cron&token=' + $(this).val());
});

//-->
</script>
<?php echo $footer; ?>
