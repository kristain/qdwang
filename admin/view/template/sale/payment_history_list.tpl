<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
	  </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list" id="history">
          <thead>
            <tr>
	            <td></td>
                <td class="left" width=150><?php echo $column_vendor_name; ?></td>
                <td class="left" width=900><?php echo $column_order_product; ?></td>
                <td class="right"><?php echo $column_payment_amount; ?> (<?php echo $this->config->get('config_currency'); ?>)</td>
                <td class="left"><?php echo $column_payment_date; ?></td>
            </tr>
          </thead>
          <tbody>
             <?php if ($histories) { ?>
              <?php foreach ($histories as $payment_history) { ?>
                <tbody id="history_<?php echo $payment_history['payment_id']; ?>">
				  <tr>
					<td class="left" style="width:3px;"><span style="cursor:pointer;padding-right:20px;display:inline-block;-moz-background-origin:padding;-moz-background-inline-policy:continuous;-moz-background-clip:border;color:#000000;background:transparent url(view/image/delete.png) no-repeat scroll right center;" onclick="removeHistory('<?php echo $payment_history['payment_id']; ?>');">&nbsp;</span></td>
					<td class="left"><?php echo $payment_history['name']; ?></td>
					<td class="left" >
					<?php foreach ($payment_history['details'] AS $orders) { ?>
					[<?php echo $orders['order_id']; ?> - <?php echo $orders['product_name']; ?>]
					<?php } ?>
					</td>
					<td class="right">-<?php echo $payment_history['amount']; ?></td>
					<td class="left"><?php echo $payment_history['date']; ?></td>
				  </tr>
				</tbody>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
             <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
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
<?php echo $footer; ?>