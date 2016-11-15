<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
	  <form method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
		<?php foreach ($store_revenue AS $income) { ?>
			<?php if (!$this->user->getVP()) { ?>
			  <td><span title="<?php echo $title_gross_revenue . $income['revenue_shipping']; ?>" style="padding-right:25px;padding-top:4px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/revenue.png) no-repeat scroll right center;">&nbsp;</span>&nbsp;<font color="#FDD017">[ </font><b><?php echo $text_gross_incomes; ?> :</b> <?php echo $income['gross_amount']; ?><font color="#FDD017"> ]</font></td>
			  <td><span style="padding-right:25px;padding-top:4px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/commission.png) no-repeat scroll right center;">&nbsp;</span>&nbsp;<font color="#4AA02C">[ </font><b><?php echo $text_commission; ?> :</b> <?php echo $income['commission']; ?><font color="#4AA02C"> ]</font></td>
			  <td><span style="padding-right:25px;padding-top:4px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/vendor.png) no-repeat scroll right center;">&nbsp;</span>&nbsp;<font color="#FF0000">[ </font><b><?php echo $text_vendor_earning; ?> :</b> <?php echo $income['vendor_amount']; ?></td>
			  <td><span style="padding-right:25px;padding-top:4px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/shipping_fee.png) no-repeat scroll right center;">&nbsp;</span><font color="#FDD017">&nbsp;[ </font><b><?php echo $text_shipping; ?> :</b> <?php echo $income['shipping_charged']; ?><font color="#FDD017"> ] </font></td>
			  <td><span style="padding-right:25px;padding-top:4px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/amount_to_pay.png) no-repeat scroll right center;">&nbsp;</span>&nbsp;<b><?php echo $text_amount_pay_vendor; ?> :</b> <?php echo $income['amount_pay_vendor']; ?><font color="#FF0000"> ]</font></td>
			  <?php if ($filter_paid_status != 1 && $filter_vendor_group !=0 && !empty($orders)) { ?>
			  <td style="width:60px"><div class="buttons"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&no_shipping=1&business=<?php echo $income['paypal_email']; ?>&item_name=<?php echo $income['company']; ?>&amount=<?php echo $income['paypal_amount']; ?>&currency_code=<?php echo $this->config->get('config_currency'); ?>" target="_blank" class="button"><span><?php echo $button_Paypal; ?></span></a></div></td>
			  <td style="width:120px"><div class="buttons"><a onclick=" $('#form').submit();" name="submit_add_payment" id="submit_add_payment" class="button"><span><?php echo $button_addPayment; ?></span></a></div></td>
			  <input type="hidden" name="paypal_standard" id="paypal_standard" value="<?php echo $addPayment . '&payment_option=paypal_standard'; ?>" />
			  <input type="hidden" name="pay_cheque" id="pay_cheque" value="<?php echo $addPayment . '&payment_option=pay_cheque&chequeno='; ?>" />
			  <input type="hidden" name="other_payment_method" id="other_payment_method" value="<?php echo $addPayment . '&payment_option=other_payment_method&opm='; ?>" />
			  <?php } else { ?>
			  <td><div></div></td>
			  <td><div></div></td>
			  <?php } ?>
			<?php } else { ?>
			  <td><span style="padding-right:25px;padding-top:4px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/vendor.png) no-repeat scroll right center;">&nbsp;</span>&nbsp;<b><?php echo $text_vendor_earning; ?></b>   <?php echo $income['vendor_amount']; ?></td>
			  <td><span style="padding-right:25px;padding-top:4px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/shipping_fee.png) no-repeat scroll right center;">&nbsp;</span><b><?php echo $text_shipping; ?> :</b> <?php echo $income['shipping_charged']; ?></td>
			  <td><span style="padding-right:25px;padding-top:4px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/amount_to_pay.png) no-repeat scroll right center;">&nbsp;</span>&nbsp;<b><?php echo $text_vendor_revenue; ?> :</b> <?php echo $income['amount_pay_vendor']; ?></td>
			  <td></td>
			  <td></td>
			  <td></td>
			  <td></td>
			<?php } ?>
		<?php } ?>
		</tr>
		  <tr>
          <td><?php echo $entry_date_start; ?>
            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" /></td>
          <td><?php echo $entry_date_end; ?>
            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" /></td>
		  <td><?php echo $entry_group; ?>
            <select name="filter_vendor_group">
			  <?php if (!$this->user->getVP()) { ?>
			  <option value="0"><?php echo $text_all_vendors; ?></option>
			  <?php } ?>
              <?php foreach ($vendors_name as $vendors_name) { ?>
              <?php if ($vendors_name['vendor_id'] == $filter_vendor_group) { ?>
              <option value="<?php echo $vendors_name['vendor_id']; ?>" selected="selected"><?php echo $vendors_name['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $vendors_name['vendor_id']; ?>"><?php echo $vendors_name['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td><?php echo $entry_order_status; ?>
            <select name="filter_order_status_id">
              <option value="0"><?php echo $text_all_status; ?></option>
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
			<td><?php echo $entry_status; ?>
            <select name="filter_paid_status">
			<?php if ($filter_paid_status) { ?>
			  <option value="0"><?php echo $text_no; ?></option>
              <option value="1" selected="selected"><?php echo $text_yes; ?></option>
			<?php } else { ?>
			  <option value="0" selected="selected"><?php echo $text_no; ?></option>
              <option value="1"><?php echo $text_yes; ?></option>
			<?php } ?>
            </select></td>
			<td></td>
			<td style="text-align: right;"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
        </tr>
      </table>
	  </form>
      <table class="list">
        <thead>
          <tr>
            <td class="right"><?php echo $column_order_id; ?></td>
            <td class="left"><?php echo $column_product_name; ?></td>
			<td class="left"><?php echo $column_date_added; ?></td>
			<td class="left"><?php echo $column_transaction_status; ?></td>
            <td class="right"><?php echo $column_unit_price; ?><?php echo ' (' . $this->config->get('config_currency') . ')'; ?></td>
            <td class="right"><?php echo $column_quantity; ?></td>
			<td class="right"><?php echo $column_total; ?><?php echo ' (' . $this->config->get('config_currency') . ')'; ?></td>
			<td class="right"><?php echo $column_commission; ?><?php echo ' (' . $this->config->get('config_currency') . ')'; ?></td>
    		<td class="right"><?php echo $column_amount; ?><?php echo ' (' . $this->config->get('config_currency') . ')'; ?></td>
			<td class="left"><?php echo $column_paid_status; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($orders) { ?>
          <?php foreach ($orders as $order) { ?>
          <tr>
		    <td class="right"><?php echo $order['order_id']; ?></td>
			<td class="left"><?php echo $order['product_name']; ?></td>
            <td class="left"><?php echo $order['date']; ?></td>
            <td class="left">
			<?php foreach ($order_statuses as $order_status) { ?>
			<?php if ($order_status['order_status_id'] == $order['order_status']) { ?>
				<?php echo $order_status['name']; ?>
			<?php } ?>
			<?php } ?></td>
            <td class="right"><?php echo $order['price']; ?></td>
            <td class="right"><?php echo $order['quantity']; ?></td>
			<td class="right"><?php echo $order['total']; ?><br />
							  <?php foreach ($commission_data as $commissiont) { ?>
								<?php if ($order['vendor_id'] == $commissiont['vendor_id']) { ?>
									<?php if ($commissiont['commission_type'] == '0') { ?>
										<?php echo ' (' . $commissiont['commission'] . '% ' . $column_commission . ')'; ?>
									<?php } elseif ($commissiont['commission_type'] == '1') { ?>
										<?php echo ' (' . $commissiont['commission'] . ' ' . $column_commission . ')'; ?>
									<?php } elseif ($commissiont['commission_type'] == '2') { ?>
										<?php $data = explode(':',$commissiont['commission']); ?>
										<?php echo ' (' . $data[0] . '% + ' . $data[1] . ' ' . $column_commission . ')'; ?>
									<?php } else { ?>
										<?php $data = explode(':',$commissiont['commission']); ?>
										<?php echo ' (' . $data[0] . ' + ' . $data[1] . '%' . $column_commission . ')'; ?>
									<?php } ?>
								<?php } ?>
							<?php } ?></td>
			<td class="right"><?php echo $order['commission']; ?></td>
			<td class="right"><?php echo $order['amount']; ?></td>
			<td class="left">
			<?php if ($order['paid_status']) { ?>
			<span style="padding-right:20px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/success.png) no-repeat scroll right center;">&nbsp;</span>
			<?php } else { ?>
			<?php if (!$this->user->getVP()) { ?>
			<div class="buttons"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&no_shipping=1&business=<?php echo $order['paypal_email']; ?>&item_name=<?php echo $order['product_name']; ?>&amount=<?php echo $order['paypal_amount']; ?>&invoice=<?php echo $order['order_id']; ?>&currency_code=<?php echo $this->config->get('config_currency'); ?>" target="_blank" class="button"><span><?php echo $button_Paypal; ?></span></a><span style="cursor:pointer;padding-right:20px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/add.png) no-repeat scroll right center;" onclick="$('#form').attr('action', '<?php echo 'index.php?route=report/vendor_transaction/addPaymentRecord&token=' . $token . '&oid=' . $order['order_id'] . '&pid=' . $order['product_id'] . '&opid=' . $order['order_product_id']; ?>'); $('#form').submit();">&nbsp;</span></div>	 		 
			<?php } else { ?>
			<span style="padding-right:20px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/not_paid.png) no-repeat scroll right center;">&nbsp;</span>
			<?php } ?>
			<?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
	<div class="content">
	<form method="post" enctype="multipart/form-data" id="form">
	<tr><td>
	</td></tr>

	 <div class="latest">
        <div class="dashboard-heading">
		<?php if (!$this->user->getVP()) { ?>
			<?php echo $text_payment_history; ?>
		<?php } else { ?>
			<?php echo $text_vendor_payment_history; ?>
		<?php } ?>
		</div>
        <div class="dashboard-content">
          <table class="list" id="history">
            <thead>
              <tr>
				<?php if (!$this->user->getVP()) { echo '<td></td>'; } ?>
                <td class="left" width="150"><?php echo $column_vendor_name; ?></td>
                <td class="left" width="650"><?php echo $column_order_product; ?></td>
				<td class="left" width="120"><?php echo $column_payment_type; ?></td>
                <td class="right" width="130"><?php echo $column_payment_amount; ?> (<?php echo $this->config->get('config_currency'); ?>)</td>
                <td class="left" width="100"><?php echo $column_payment_date; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($histories) { ?>
              <?php foreach ($histories as $payment_history) { ?>
                <tbody id="history_<?php echo $payment_history['payment_id']; ?>">
				  <tr>
				    <?php if (!$this->user->getVP()) { ?>
					<td class="left" style="width:3px;"><span style="cursor:pointer;padding-right:20px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/delete.png) no-repeat scroll right center;" onclick="removeHistory('<?php echo $payment_history['payment_id']; ?>');">&nbsp;</span></td>
					<?php } ?>
					<td class="left"><?php echo $payment_history['name']; ?></td>
					<td class="left" >
					<?php foreach ($payment_history['details'] AS $orders) { ?>
					[<?php echo $orders['order_id']; ?> - <?php echo $orders['product_name']; ?>]
					<?php } ?>
					</td>
					<td class="left"><?php echo $payment_history['payment_type']; ?></td>
					<td class="right">-<?php echo $payment_history['amount']; ?></td>
					<td class="left"><?php echo $payment_history['date']; ?></td>
				  </tr>
				</tbody>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="center" colspan="<?php if (!$this->user->getVP()) { echo '6'; } else { echo '5'; } ?>"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

	</form>
	</div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=report/vendor_transaction&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_vendor_group = $('select[name=\'filter_vendor_group\']').attr('value');
	
	if (filter_vendor_group) {
		url += '&filter_vendor_group=' + encodeURIComponent(filter_vendor_group);
	}
	
	var filter_paid_status = $('select[name=\'filter_paid_status\']').attr('value');
	
	if (filter_paid_status) {
		url += '&filter_paid_status=' + encodeURIComponent(filter_paid_status);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id) {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	location = url;
}
//--></script> 
<script type="text/javascript"><!--
$('#submit_add_payment').click(function() {
    var $dialog = $('<div></div>')
    .load('view/template/report/choose_payment_type.tpl')
    .dialog({
        autoOpen: false,
        title: 'Select Payment Methods',
        buttons: {
            "Add Payment": function() {
				var pay_type = $("input[name=RadioGroup]:checked").val();
				var paypal_standard = $("input[name=paypal_standard]").val();
				var pay_cheque = $("input[name=pay_cheque]").val() + $("input[name=cheque_no]").val();
				var other_payment_method = $("input[name=other_payment_method]").val() + $("input[name=other_payment]").val();
				
				switch(pay_type) {
					case 'paypal_direct':
					$('#form').attr('action', paypal_standard); $('#form').submit();
					break;
					
					case 'cheque':
					$('#form').attr('action', pay_cheque); $('#form').submit();
					break;
					
					case 'other':
					$('#form').attr('action', other_payment_method); $('#form').submit();
					break;
				}
	
            },
            "Cancel": function() {
                $(this).dialog("close");
            }
        }
    });
    $dialog.dialog('open');
});
//--></script>
<script type="text/javascript"><!--
function removeHistory(id) {
	$.ajax({
		url: 'index.php?route=report/vendor_transaction/removeHistory&token=<?php echo $token; ?>&payment_id=' + id,
		dataType: 'json',
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#history').before('<div class="attention"><img src="view/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		
		complete: function() {
			$('.attention').remove();
		},
		
		success: function(data) {
			$('#history_' + id).remove();
			$('#history').before('<div class="success">' + data.success + '</div>');
		}
			
	});
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<?php echo $footer; ?>