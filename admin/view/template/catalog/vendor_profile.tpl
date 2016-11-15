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
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title_profile; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
            <?php if ($error_vendor_firstname) { ?>
            <span class="error"><?php echo $error_vendor_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
            <?php if ($error_vendor_lastname) { ?>
            <span class="error"><?php echo $error_vendor_lastname; ?></span>
            <?php } ?></td>
        </tr>
		<tr>
          <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
          <td><input name="telephone" value="<?php echo $telephone; ?>" size="25" />
		  <?php if ($error_vendor_telephone) { ?>
            <span class="error"><?php echo $error_vendor_telephone; ?></span>
            <?php } ?>
		  </td>
        </tr>
		<tr>
          <td><?php echo $entry_fax; ?></td>
          <td><input name="fax" value="<?php echo $fax; ?>" size="25" /></td>
        </tr>
		<tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input name="email" value="<?php echo $email; ?>" size="25" />
		   <?php if ($error_vendor_email) { ?>
            <span class="error"><?php echo $error_vendor_email; ?></span>
           <?php } ?>
		  </td>
        </tr>
		<tr>
          <td><?php echo $entry_paypal_email; ?></td>
          <td><input name="paypal_email" value="<?php echo $paypal_email; ?>" size="25" />
		  <?php if ($error_vendor_paypal_email) { ?>
            <span class="error"><?php echo $error_vendor_paypal_email; ?></span>
          <?php } ?>
		  </td>
        </tr>
		  <tr>
          <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
          <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" />
            <?php if ($error_vendor_address_1) { ?>
            <span class="error"><?php echo $error_vendor_address_1; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_address_2; ?></td>
          <td><input type="text" name="address_2" value="<?php echo $address_2; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_city; ?></td>
          <td><input type="text" name="city" value="<?php echo $city; ?>" />
            <?php if ($error_vendor_city) { ?>
            <span class="error"><?php echo $error_vendor_city; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" />
            <?php if ($error_vendor_postcode) { ?>
            <span class="error"><?php echo $error_vendor_postcode; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_country; ?></td>
          <td><select name="country_id" onchange="$('select[name=\'zone_id\']').load('index.php?route=catalog/vendor_profile/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');">
              <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
			  <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
            <?php if ($error_vendor_country) { ?>
            <span class="error"><?php echo $error_vendor_country; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
          <td><select name="zone_id">
            </select>
            <?php if ($error_vendor_zone) { ?>
            <span class="error"><?php echo $error_vendor_zone; ?></span>
            <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_description; ?></td>
          <td><textarea name="vendor_description" cols="68" rows="3" ><?php echo $vendor_description; ?></textarea></td>
        </tr>
		<tr>
          <td><?php echo $entry_store_url; ?></td>
          <td><textarea name="store_url" cols="68" rows="3" ><?php echo $store_url; ?></textarea></td>
        </tr>
        <tr>
		  <td><?php echo $entry_image; ?></td>
          <td><div class="image"><img src="<?php echo $thumb; ?>" alt="" id="thumb" /><br />
              <input type="hidden" name="vendor_image" value="<?php echo $vendor_image; ?>" id="image" />
              <a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a></div></td>
        </tr>
		</table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'zone_id\']').load('index.php?route=catalog/vendor_profile/zone&token=<?php echo $token; ?>&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<?php echo $footer; ?>