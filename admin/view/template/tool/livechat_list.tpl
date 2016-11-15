<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a><a onclick="$('#form').submit();" class="button"><?php echo $button_delete; ?></a><a onclick="location = '<?php echo $setting; ?>'" class="button"><?php echo $button_setting; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
			  <td class="right" width="60"><?php echo $column_listorder; ?></td>			  
              <td class="left" width="120"><?php echo $column_name; ?></td>
              <td class="left" width="150"><?php echo $column_label; ?></td>
			  <td class="left" width="130"><?php echo $column_type; ?></td>
			  <td class="left"><?php echo $column_image; ?></td>
			  <td class="left"><?php echo $column_code; ?></td>
			  <td class="left" width="30"><?php echo $column_status; ?></td>
              <td class="right" width="30"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($livechats) { ?>
            <?php foreach ($livechats as $livechat) { ?>
            <tr>
              <td style="text-align: center;">
                <input type="checkbox" name="selected[]" value="<?php echo $livechat['chatid']; ?>" />
              </td>
              <td class="left"><input type="text" name="listorder[<?php echo $livechat['chatid']; ?>]" value="<?php echo $livechat['listorder'];?>" size="3"></td>
			  <td class="left"><?php echo $livechat['name'];?></td>
			  <td class="left"><?php echo $livechat['label'];?></td>
			  <td class="left"><?php echo $livechat['type'];?></td>
			  <td class="left">
				<?php if ($livechat['skin']) { ?>
					<img src="<?php echo $livechat['skin']; ?>" />
				<?php } ?>
			  </td>
			  <td class="left"><?php echo $livechat['code'];?></td>
			  <td class="left"><?php echo $livechat['status'] == 1 ? $text_enabled : $text_disabled;?></td>
              <td class="right"><?php foreach ($livechat['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
	  <div class="buttons">
		<a onclick="$('#form').attr('action', '<?php echo $update;?>');$('#form').submit();" class="button"><?php echo $button_update; ?></a>
	  </div>
    </div>
  </div>
  <div style="text-align:right;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
	<input type="hidden" name="cmd" value="_donations">
	<input type="hidden" name="business" value="holly.wu@yahoo.com">
	<input type="hidden" name="lc" value="US">
	<input type="hidden" name="no_note" value="0">
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
	Livechat is free software. Please <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
</div>
</div>
<?php echo $footer; ?>