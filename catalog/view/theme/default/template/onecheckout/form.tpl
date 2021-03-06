  <div class="divclear">
  	<span class="required">*</span> <?php echo $entry_firstname; ?><br />
  	<input type="text" name="firstname" value="<?php echo $firstname; ?>" class="large-field" /><br />
  </div>

  <div class="right"  style="display: none;">
  	<span class="required">*</span> <?php echo $entry_lastname; ?><br />
  	<input type="text" name="lastname" value="default" class="small-field" /><br />
  </div>  
  <div class="divclear">
  	<!-- span class="required">*</span> <?php echo $entry_email; ?><br /> -->
  	<input type="text" id="email" name="email" value="" class="large-field" style="display: none;"/>
   <div   style="display: none;">
	<?php echo $entry_company; ?>
  	<input type="text" name="company" value="default" class="large-field" />
	
	<?php if($version_int >= 1530) { ?>
	<div style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;">
    	<br />
		<?php echo $entry_account; ?><br />
		<select name="customer_group_id" class="large-field">
      	<?php foreach ($customer_groups as $customer_group) { ?>
      	<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
      	<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
      	<?php } else { ?>
      	<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
      	<?php } ?>
      	<?php } ?>
    	</select><br />    	
  	</div>
  	<div id="company-id-display" >
    	<br />
		<span id="company-id-required" class="required">*</span> <?php echo $entry_company_id; ?><br />
		<input type="text" name="company_id" value="<?php echo $company_id; ?>" class="large-field" /><br />
  	</div>
  	<div id="tax-id-display">
    	<br />
		<span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?><br />
		<input type="text" name="tax_id" value="<?php echo $tax_id; ?>" class="large-field" /><br />    	
  	</div>
  	<?php } ?>
    </div>
  	<br />
	<span class="required">*</span> <?php echo $entry_address_1; ?><br />
  	<input type="text" name="address_1" value="<?php echo $address_1; ?>" class="large-field" />
  	<br />
  	<br />
    <div   style="display: none;">
  	<?php echo $entry_address_2; ?><br />
  	<input type="text" name="address_2" value="default" class="large-field" />
	 </div>
  </div>
	
  <div class="left">
  	<span class="required">*</span> <?php echo $entry_telephone; ?><br />
  	<input type="text" id="telephone" name="telephone" value="<?php echo $telephone; ?>" class="small-field" /><br />
  </div>

   <div class="right"  style="display: none;">
  	<?php echo $entry_fax; ?><br />
  	<input type="text" name="fax" value="default" class="small-field" /><br />
   </div>
  </div>
  <br />

  <div class="divclear"></div>
  <br />
  <div class="left" style="display: none;">
  	<span class="required">*</span> <?php echo $entry_city; ?><br />
  	<input type="text" name="city" value="default" class="small-field" /><br />
  </div>
  <div class="right" style="display: none;">
  	<span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?><br />
  	<input type="text" name="postcode" value="default" class="small-field" /><br />
  </div>
  
  <div class="divclear" style="display:none;">
  	<br />
  	<span class="required">*</span> <?php echo $entry_country; ?><br />
  	<select name="country_id" class="large-field">
    	<option value=""><?php echo $text_select; ?></option>
    	<?php foreach ($countries as $country) { ?>
    	<?php if ($country['country_id'] == $country_id) { ?>
    	<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
    	<?php } else { ?>
    	<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
    	<?php } ?>
    	<?php } ?>
  	</select>
  	<br />
  	<br />
  	<span class="required">*</span> <?php echo $entry_zone; ?><br />
  	<select name="zone_id" class="large-field">
  	</select>
  	<br />
  	<br />
	<?php if ($guest_checkout) { ?>
	<input type="checkbox" name="account" value="1" id="account" checked="checked" style="display:none;"/>
	<label style="display:none;" for="account"><?php echo $text_reg; ?></label>
	<br />
	<br />
	<?php } else { ?>
	<input type="checkbox" name="account" value="1" id="account" checked="checked" style="display:none;" />
	<?php } ?>	
  </div>
<?php if($version_int >= 1530) { ?>
<script type="text/javascript"><!--
$('#payment-address select[name=\'customer_group_id\']').live('change', function() {
	var customer_group = [];
	
<?php foreach ($customer_groups as $customer_group) { ?>
	customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';
<?php } ?>	

	if (customer_group[this.value]) {
		if (customer_group[this.value]['company_id_display'] == '1') {
			$('#company-id-display').show();
		} else {
			$('#company-id-display').hide();
		}
		
		if (customer_group[this.value]['company_id_required'] == '1') {
			$('#company-id-required').show();
		} else {
			$('#company-id-required').hide();
		}
		
		if (customer_group[this.value]['tax_id_display'] == '1') {
			$('#tax-id-display').show();
		} else {
			$('#tax-id-display').hide();
		}
		
		if (customer_group[this.value]['tax_id_required'] == '1') {
			$('#tax-id-required').show();
		} else {
			$('#tax-id-required').hide();
		}	
	}
});

$('#payment-address select[name=\'customer_group_id\']').trigger('change');
//--></script> 
<?php } ?>	
<script type="text/javascript"><!--
$('#payment-address select[name=\'country_id\']').bind('change', function() {
	getzone('payment','<?php echo $zone_id; ?>');
});
getzone('payment','<?php echo $zone_id; ?>');

$('#payment-address select[name=\'zone_id\']').live('change', function() {
	if($('#payment-address input[name=\'shipping_address\']:checked').attr('value')){
		shippingmethod($('#payment-address select[name=\'country_id\']').val(), $('#payment-address select[name=\'zone_id\']').val(), 1 , $('#payment-address input[name=\'city\']').val(),$('#payment-address input[name=\'postcode\']').val());		
	}
	paymentmethod($('#payment-address select[name=\'country_id\']').val(), $('#payment-address select[name=\'zone_id\']').val(), 1 , $('#payment-address input[name=\'city\']').val(),$('#payment-address input[name=\'postcode\']').val());
});

$('#shipping-address select[name=\'zone_id\']').live('change', function() {
	shippingmethod($('#shipping-address select[name=\'country_id\']').val(), $('#shipping-address select[name=\'zone_id\']').val(), 1 , $('#shipping-address input[name=\'city\']').val(),$('#shipping-address input[name=\'postcode\']').val());
});

$('#payment-address input[name=\'firstname\']').live('blur', function() {
	valiform("payment","firstname","");
});

$('#payment-address input[name=\'firstname\']').live('focus', function() {
	errorremove("payment","firstname");
});

$('#payment-address input[name=\'lastname\']').live('blur', function() {
	valiform("payment","lastname","");
});

$('#payment-address input[name=\'lastname\']').live('focus', function() {
	errorremove("payment","lastname");
});

//$('#payment-address input[name=\'email\']').live('blur', function() {
//	valiform("payment","email","");
//});

//$('#payment-address input[name=\'email\']').live('focus', function() {
//	errorremove("payment","email");
//});

$('#payment-address input[name=\'company_id\']').live('blur', function() {
	valiform("payment","company_id",", #payment-address select");
});

$('#payment-address input[name=\'company_id\']').live('focus', function() {
	errorremove("payment","company_id");
});

$('#payment-address input[name=\'tax_id\']').live('blur', function() {
	valiform("payment","tax_id",", #payment-address select");
});

$('#payment-address input[name=\'tax_id\']').live('focus', function() {
	errorremove("payment","tax_id");
});

$('#payment-address input[name=\'address_1\']').live('blur', function() {
	valiform("payment","address_1","");
});

$('#payment-address input[name=\'address_1\']').live('focus', function() {
	errorremove("payment","address_1");
});

$('#payment-address input[name=\'telephone\']').live('blur', function() {
	valiform("payment","telephone","");
});

$('#payment-address input[name=\'telephone\']').live('focus', function() {
	errorremove("payment","telephone");
});

$('#payment-address input[name=\'city\']').live('blur', function() {
	valiform("payment","city","");
	if($('#payment-address input[name=\'shipping_address\']:checked').attr('value')){
		shippingmethod($('#payment-address select[name=\'country_id\']').val(), $('#payment-address select[name=\'zone_id\']').val(), 1 , $('#payment-address input[name=\'city\']').val(),$('#payment-address input[name=\'postcode\']').val());		
	}
	paymentmethod($('#payment-address select[name=\'country_id\']').val(), $('#payment-address select[name=\'zone_id\']').val(), 1 , $('#payment-address input[name=\'city\']').val(),$('#payment-address input[name=\'postcode\']').val());
});

$('#payment-address input[name=\'city\']').live('focus', function() {
	errorremove("payment","city");
});

$('#payment-address input[name=\'postcode\']').live('blur', function() {
	valiform("payment","postcode",", #payment-address select");
	if($('#payment-address input[name=\'shipping_address\']:checked').attr('value')){
		shippingmethod($('#payment-address select[name=\'country_id\']').val(), $('#payment-address select[name=\'zone_id\']').val(), 1 , $('#payment-address input[name=\'city\']').val(),$('#payment-address input[name=\'postcode\']').val());		
	}
	paymentmethod($('#payment-address select[name=\'country_id\']').val(), $('#payment-address select[name=\'zone_id\']').val(), 1 , $('#payment-address input[name=\'city\']').val(),$('#payment-address input[name=\'postcode\']').val());
});

$('#payment-address input[name=\'postcode\']').live('focus', function() {
	errorremove("payment","postcode");
});

$('#payment-address select[name=\'zone_id\']').live('focus', function() {
	errorremove("payment","zone_id");
});

$('#payment-address select[name=\'country_id\']').live('focus', function() {
	errorremove("payment","country_id");
});

$('#shipping-address input[name=\'firstname\']').live('blur', function() {
	valiform("shipping","firstname","");
});

$('#shipping-address input[name=\'firstname\']').live('focus', function() {
	errorremove("shipping","firstname");
});

$('#shipping-address input[name=\'lastname\']').live('blur', function() {
	valiform("shipping","lastname","");
});

$('#shipping-address input[name=\'lastname\']').live('focus', function() {
	errorremove("shipping","lastname");
});

$('#shipping-address input[name=\'address_1\']').live('blur', function() {
	valiform("shipping","address_1","");
});

$('#shipping-address input[name=\'address_1\']').live('focus', function() {
	errorremove("shipping","address_1");
});

$('#shipping-address input[name=\'city\']').live('blur', function() {
	valiform("shipping","city","");
	shippingmethod($('#shipping-address select[name=\'country_id\']').val(), $('#shipping-address select[name=\'zone_id\']').val(), 1 , $('#shipping-address input[name=\'city\']').val(),$('#shipping-address input[name=\'postcode\']').val());
});

$('#shipping-address input[name=\'city\']').live('focus', function() {
	errorremove("shipping","city");
});

$('#shipping-address input[name=\'postcode\']').live('blur', function() {
	valiform("shipping","postcode",", #shipping-address select");
	shippingmethod($('#shipping-address select[name=\'country_id\']').val(), $('#shipping-address select[name=\'zone_id\']').val(), 1 , $('#shipping-address input[name=\'city\']').val(),$('#shipping-address input[name=\'postcode\']').val());
});

$('#shipping-address input[name=\'postcode\']').live('focus', function() {
	errorremove("shipping","postcode");
});

$('#shipping-address select[name=\'zone_id\']').live('focus', function() {
	errorremove("shipping","zone_id");
});

$('#shipping-address select[name=\'country_id\']').live('focus', function() {
	errorremove("shipping","country_id");
});

function valiform(layout, vname, othername){
	$.ajax({
		url: 'index.php?route=onecheckout/form/validate',
		type: 'post',
		data: $('#'+layout+'-address input[name=\''+vname+'\']'+othername),
		dataType: 'json',
		success: function(json) {						
			if (json['error'][vname]) {
				errorremove(layout, vname);
				$('#'+layout+'-address input[name=\''+vname+'\'] + br').after('<span id="error_'+vname+'" class="error">' + json['error'][vname] + '</span>');
			}
		}
	});	
}

function errorremove(layout, vname) {
	if($('#'+layout+'-address #error_'+vname)){
		$('#'+layout+'-address #error_'+vname).remove();
	}
}
//--></script> 