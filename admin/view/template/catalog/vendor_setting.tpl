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
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <div id="tabs" class="htabs"><a href="#tab-email"><?php echo $tab_mail_setting; ?></a><a href="#tab-catalog"><?php echo $tab_catalog; ?></a><a href="#tab-sales"><?php echo $tab_sales; ?></a><a href="#tab-signup"><?php echo $tab_signup; ?></a></div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	  <div id="tab-email">
      <table class="form">
	    <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="vendor_email_status">
              <?php if ($vendor_email_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
		<tr>
            <td><?php echo $entry_checkout_order_status; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
					<?php if ($vendor_checkout_order_status) { ?>
						<?php if (in_array($order_status['order_status_id'], $vendor_checkout_order_status)) { ?>
							<input type="checkbox" name="vendor_checkout_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" /><?php echo $order_status['name']; ?>
						<?php } else { ?>
							<input type="checkbox" name="vendor_checkout_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" /><?php echo $order_status['name']; ?>
						<?php } ?>
					<?php } else { ?>
						<input type="checkbox" name="vendor_checkout_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" /><?php echo $order_status['name']; ?>
					<?php } ?>
                  </div>
                  <?php } ?>
                </div>
            <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
        </tr>
		<tr>
            <td><?php echo $entry_history_order_status; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
					<?php if ($vendor_history_order_status) { ?>
						<?php if (in_array($order_status['order_status_id'], $vendor_history_order_status)) { ?>
							<input type="checkbox" name="vendor_history_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" /><?php echo $order_status['name']; ?>
						<?php } else { ?>
							<input type="checkbox" name="vendor_history_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" /><?php echo $order_status['name']; ?>
						<?php } ?>
					<?php } else { ?>
						<input type="checkbox" name="vendor_history_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" /><?php echo $order_status['name']; ?>
					<?php } ?>
                  </div>
                  <?php } ?>
                </div>
            <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
        </tr>
		<tr>
            <td><?php echo $entry_multivendor_order_status; ?></td>
			 <td><select name="multivendor_order_status">
               <?php foreach ($order_statuses as $order_status) { ?>
               <?php if ($order_status['order_status_id'] == $multivendor_order_status) { ?>
               <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
               <?php } else { ?>
               <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
               <?php } ?>
               <?php } ?>
           </select></td>
        </tr>
		<tr>
          <td><span class="required">*</span> <?php echo $entry_message; ?></td>
          <td><textarea name="vendor_email_message" cols="100" rows="5"><?php echo $vendor_email_message; ?></textarea>
		  <?php if ($error_code_message) { ?>
          <span class="error"><?php echo $error_code_message; ?></span>
          <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_order_id; ?></td>
          <td><?php if ($vendor_cust_order_id) { ?>
            <input type="radio" name="vendor_cust_order_id" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_cust_order_id" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_cust_order_id" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_cust_order_id" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><?php if ($vendor_cust_order_status) { ?>
            <input type="radio" name="vendor_cust_order_status" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_cust_order_status" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_cust_order_status" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_cust_order_status" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_payment_method; ?></td>
          <td><?php if ($vendor_cust_payment_method) { ?>
            <input type="radio" name="vendor_cust_payment_method" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_cust_payment_method" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_cust_payment_method" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_cust_payment_method" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_cust_email; ?></td>
          <td><?php if ($vendor_cust_email) { ?>
            <input type="radio" name="vendor_cust_email" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_cust_email" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_cust_email" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_cust_email" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_cust_telephone; ?></td>
          <td><?php if ($vendor_cust_telephone) { ?>
            <input type="radio" name="vendor_cust_telephone" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_cust_telephone" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_cust_telephone" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_cust_telephone" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_shipping_address; ?></td>
          <td><?php if ($vendor_cust_shipping_address) { ?>
            <input type="radio" name="vendor_cust_shipping_address" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_cust_shipping_address" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_cust_shipping_address" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_cust_shipping_address" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_vendor_address; ?></td>
          <td><?php if ($vendor_address) { ?>
            <input type="radio" name="vendor_address" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_address" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_address" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_address" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_vendor_email; ?></td>
          <td><?php if ($vendor_email) { ?>
            <input type="radio" name="vendor_email" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_email" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_email" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_email" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_vendor_telephone; ?></td>
          <td><?php if ($vendor_telephone) { ?>
            <input type="radio" name="vendor_telephone" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_telephone" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_telephone" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_telephone" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
      </table>
	  </div>
	  <div id="tab-catalog">
          <table class="form">
		  <tr>
          <td><?php echo $entry_vendor_product_approval; ?></td>
          <td><?php if ($vendor_product_approval) { ?>
            <input type="radio" name="vendor_product_approval" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_product_approval" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_product_approval" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_product_approval" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		   <tr>
          <td><?php echo $entry_vendor_tab; ?></td>
          <td><?php if ($vendor_tab) { ?>
            <input type="radio" name="vendor_tab" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_tab" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_tab" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_tab" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		   <tr>
		  <tr>
          <td><?php echo $entry_desgin_tab; ?></td>
          <td><?php if ($vendor_desgin_tab) { ?>
            <input type="radio" name="vendor_desgin_tab" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_desgin_tab" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_desgin_tab" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_desgin_tab" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		   <tr>
          <td><?php echo $entry_reward_points; ?></td>
          <td><?php if ($vendor_reward_points) { ?>
            <input type="radio" name="vendor_reward_points" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_reward_points" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_reward_points" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_reward_points" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		   <tr>
		   <tr>
          <td><?php echo $entry_category_menu; ?></td>
          <td><?php if ($vendor_category_menu) { ?>
            <input type="radio" name="vendor_category_menu" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_category_menu" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_category_menu" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_category_menu" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		   <tr>
          <td><?php echo $entry_menu_bar; ?></td>
          <td><?php if ($vendor_menu_bar) { ?>
            <input type="radio" name="vendor_menu_bar" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_menu_bar" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_menu_bar" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_menu_bar" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		  </table>
	  </div>
	  <div id="tab-sales">
          <table class="form">
		  <tr>
          <td><?php echo $entry_vendor_invoice_address; ?></td>
          <td><?php if ($vendor_invoice_address) { ?>
            <input type="radio" name="vendor_invoice_address" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_invoice_address" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="vendor_invoice_address" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="vendor_invoice_address" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		  <tr>
		  <tr>
          <td><?php echo $entry_order_detail; ?></td>
          <td><?php if ($sales_order_detail) { ?>
            <input type="radio" name="sales_order_detail" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sales_order_detail" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="sales_order_detail" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sales_order_detail" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		   <tr>
          <td><?php echo $entry_payment_detail; ?></td>
          <td><?php if ($sales_payment_detail) { ?>
            <input type="radio" name="sales_payment_detail" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sales_payment_detail" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="sales_payment_detail" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sales_payment_detail" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		   <tr>
          <td><?php echo $entry_shipping_detail; ?></td>
          <td><?php if ($sales_shipping_detail) { ?>
            <input type="radio" name="sales_shipping_detail" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sales_shipping_detail" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="sales_shipping_detail" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sales_shipping_detail" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		   <tr>
          <td><?php echo $entry_product; ?></td>
          <td><?php if ($sales_product) { ?>
            <input type="radio" name="sales_product" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sales_product" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="sales_product" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sales_product" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		   <tr>
          <td><?php echo $entry_order_history; ?></td>
          <td><?php if ($sales_order_history) { ?>
            <input type="radio" name="sales_order_history" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sales_order_history" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="sales_order_history" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sales_order_history" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		   <td><?php echo $entry_order_history_update; ?></td>
          <td><?php if ($sales_order_history_update) { ?>
            <input type="radio" name="sales_order_history_update" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sales_order_history_update" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="sales_order_history_update" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sales_order_history_update" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		  </table>
	  </div>
	  <div id="tab-signup">
          <table class="form">
		  <tr>
          <td><?php echo $entry_sign_up; ?></td>
          <td><?php if ($sign_up) { ?>
            <input type="radio" name="sign_up" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sign_up" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="sign_up" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="sign_up" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
		    <tr>
          <td><?php echo $entry_vendor_approval; ?></td>
          <td><?php if ($signup_auto_approval) { ?>
            <input type="radio" name="signup_auto_approval" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="signup_auto_approval" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="signup_auto_approval" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="signup_auto_approval" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
           </tr>
	      <tr>
          <td><?php echo $entry_policy; ?></td>
          <td><select name="signup_policy">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($informations as $information) { ?>
                  <?php if ($information['information_id'] == $signup_policy) { ?>
                  <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
           </tr>
		  <tr>
          <td><?php echo $entry_commission; ?></td>
          <td><select name="signup_commission">
		  <option value="0"><?php echo $text_disabled; ?></option>
		  <?php foreach ($signup_commissions as $commission) { ?>
			<?php if ($signup_commission == $commission['commission_id']) { ?>
			<option value="<?php echo $commission['commission_id']; ?>" selected="selected">
			<?php if ($commission['commission_type'] == '0') { ?>
				<?php echo $commission['commission_name'] . ' (' . $commission['commission'] . '%)'; ?>
			<?php } elseif ($commission['commission_type'] == '1') { ?>
				<?php echo $commission['commission_name'] . ' (' . $commission['commission'] . ')'; ?>
			<?php } elseif ($commission['commission_type'] == '2') { ?>
				<?php $data = explode(':',$commission['commission']); ?>
				<?php echo $commission['commission_name'] . ' (' . $data[0] . '% + ' . $data[1] . ')'; ?>
			<?php } else { ?>
				<?php $data = explode(':',$commission['commission']); ?>
				<?php echo $commission['commission_name'] . ' (' . $data[0] . ' + ' . $data[1] . '%)'; ?>
			<?php } ?>
			</option> 
			<?php } else { ?>
			<option value="<?php echo $commission['commission_id']; ?>">
			<?php if ($commission['commission_type'] == '0') { ?>
				<?php echo $commission['commission_name'] . ' (' . $commission['commission'] . '%)'; ?>
			<?php } elseif ($commission['commission_type'] == '1') { ?>
				<?php echo $commission['commission_name'] . ' (' . $commission['commission'] . ')'; ?>
			<?php } elseif ($commission['commission_type'] == '2') { ?>
				<?php $data = explode(':',$commission['commission']); ?>
				<?php echo $commission['commission_name'] . ' (' . $data[0] . '% + ' . $data[1] . ')'; ?>
			<?php } else { ?>
				<?php $data = explode(':',$commission['commission']); ?>
				<?php echo $commission['commission_name'] . ' (' . $data[0] . ' + ' . $data[1] . '%)'; ?>
			<?php } ?>
			</option> 
			<?php } ?>
          <?php } ?>
		  </select></td>
           </tr>
		  <tr>
          <td><?php echo $entry_product_limit; ?></td>
          <td><select name="signup_product_limit">
		  <option value="0"><?php echo $text_disabled; ?></option>
		  <?php foreach ($prolimits as $prolimit) { ?>
			 <?php if ($prolimit['product_limit_id'] == $signup_product_limit) { ?>
			   <option value="<?php echo $prolimit['product_limit_id']; ?>" selected="selected"><?php echo $prolimit['package_name'] . ' (' . $prolimit['product_limit'] . ')' ?></option>
			 <?php } else { ?>
			   <option value="<?php echo $prolimit['product_limit_id']; ?>"><?php echo $prolimit['package_name'] . ' (' . $prolimit['product_limit'] . ')' ?></option>
			 <?php } ?>
          <?php } ?>
		  </select></td>
          </tr>
		  <tr>
            <td><?php echo $entry_category; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                  <?php foreach ($categories as $category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
					<?php if ($signup_category) { ?>
						<?php if (in_array($category['category_id'], $signup_category)) { ?>
							<input type="checkbox" name="signup_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" /><?php echo $category['name']; ?>
						<?php } else { ?>
							<input type="checkbox" name="signup_category[]" value="<?php echo $category['category_id']; ?>" /><?php echo $category['name']; ?>
						<?php } ?>
					<?php } else { ?>
						<input type="checkbox" name="signup_category[]" value="<?php echo $category['category_id']; ?>" /><?php echo $category['name']; ?>
					<?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
          </tr>
		  
		  <tr>
            <td><?php echo $entry_store; ?></td>
            <td><div class="scrollbox">
				  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
					<?php if ($signup_store) { ?>
						<?php if (in_array(0, $signup_store)) { ?>
							<input type="checkbox" name="signup_store[]" value="0" checked="checked" />
							<?php echo $text_default; ?>
						<?php } else { ?>
							<input type="checkbox" name="signup_store[]" value="0" />
							<?php echo $text_default; ?>
						<?php } ?>
					<?php } else { ?>
						<input type="checkbox" name="signup_store[]" value="0" />
						<?php echo $text_default; ?>
					<?php } ?>
                  </div>
				 <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
					<?php if ($signup_store) { ?>
						<?php if (in_array($store['store_id'], $signup_store)) { ?>
							<input type="checkbox" name="signup_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" /><?php echo $store['name']; ?>
						<?php } else { ?>
							<input type="checkbox" name="signup_store[]" value="<?php echo $store['store_id']; ?>" /><?php echo $store['name']; ?>
						<?php } ?>
					<?php } else { ?>
						<input type="checkbox" name="signup_store[]" value="<?php echo $store['store_id']; ?>" /><?php echo $store['name']; ?>
					<?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
          </tr>
		  </table>
	  </div>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs(); 
$('#vtab-option a').tabs();
//--></script> 
