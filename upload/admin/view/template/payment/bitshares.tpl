<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="bitshares-checkout" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
     <?php if ($error) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>   
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_bitshares_join; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="bitshares-checkout" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="bitshares_status" id="input-status" class="form-control">
                <?php if ($bitshares_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>        
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="bitshares_user_account"><span data-toggle="tooltip" title="<?php echo $help_user_account; ?>"><?php echo $text_user_account; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="bitshares_user_account" value="<?php echo $bitshares_user_account; ?>" placeholder="<?php echo $text_user_account; ?>" id="input-user-account" class="form-control" />
              </div>
          </div>
           <div class="form-group required">
            <label class="col-sm-2 control-label" for="bitshares_rpc_user"><span data-toggle="tooltip" title="<?php echo $help_rpc_user; ?>"><?php echo $text_rpc_user; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="bitshares_rpc_user" value="<?php echo $bitshares_rpc_user; ?>" placeholder="<?php echo $text_rpc_user; ?>" id="input-rpc-user" class="form-control" />
              </div>
          </div> 
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="bitshares_rpc_pass"><span data-toggle="tooltip" title="<?php echo $help_rpc_pass; ?>"><?php echo $text_rpc_pass; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="bitshares_rpc_pass" value="<?php echo $bitshares_rpc_pass; ?>" placeholder="<?php echo $text_rpc_pass; ?>" id="input-rpc-pass" class="form-control" />
              </div>
          </div> 
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="bitshares_rpc_port"><span data-toggle="tooltip" title="<?php echo $help_rpc_port; ?>"><?php echo $text_rpc_port; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="bitshares_rpc_port" value="<?php echo $bitshares_rpc_port; ?>" placeholder="<?php echo $text_rpc_port; ?>" id="input-rpc-port" class="form-control" />
              </div>
          </div>           
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mode"><span data-toggle="tooltip" title="<?php echo $help_demo; ?>"><?php echo $entry_demo; ?></span></label>
            <div class="col-sm-10">
              <select name="bitshares_demo" id="input-mode" class="form-control">
                <?php if ($bitshares_demo) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_processing_status; ?></label>
            <div class="col-sm-10">
              <select name="bitshares_processing_status_id" id="input-order-status" class="form-control">
                <?php foreach($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $bitshares_processing_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-confirmed-status"><?php echo $entry_confirmed_status; ?></label>
            <div class="col-sm-10">
              <select name="bitshares_confirmed_status_id" id="input-confirmed-status" class="form-control">
                <?php foreach($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $bitshares_confirmed_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-invalid-status"><?php echo $entry_invalid_status; ?></label>
            <div class="col-sm-10">
              <select name="bitshares_invalid_status_id" id="input-invalid-status" class="form-control">
                <?php foreach($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $bitshares_invalid_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cron-job-token"><span data-toggle="tooltip" title="<?php echo $help_cron_job_token; ?>"><?php echo $text_cron_job_url; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="bitshares_cron_job_token" value="<?php echo $bitshares_cron_job_token; ?>" id="input-cron-job-token" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cron-job-url"><?php echo $text_cron_job_url; ?></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"><i class="fa fa-link"></i></span>
                <input type="text" readonly="readonly" value="<?php echo $cron_job_url; ?>" id="input-cron-job-url" class="form-control" />
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cron-job-last-run"><?php echo $text_last_cron_job_run; ?></label>
            <div class="col-sm-10">
              <input type="text" readonly="readonly" value="<?php echo $cron_job_last_run; ?>" id="input-cron-job-last-run" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript">
  <!--
  $('input[name=\'bitshares_cron_job_token\']').on('keyup', function() {
    $('#input-cron-job-url').val('<?php echo HTTPS_CATALOG ?>index.php?route=payment/bitshares/cron&token=' + $(this).val());
});
//--></script></div>
<?php echo $footer; ?>