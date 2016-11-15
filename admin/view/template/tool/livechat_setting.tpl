<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
			<tr>
				<td><?php echo $text_title; ?></td>
				<td><input type="text" name="title" value="<?php echo $setting['title']; ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $text_skin; ?></td>
				<td><input type="text" name="skin" value="<?php echo $setting['skin']; ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $text_posx; ?></td>
				<td><input type="text" name="posx" value="<?php echo $setting['posx']; ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $text_posy; ?></td>
				<td><input type="text" name="posy" value="<?php echo $setting['posy']; ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $column_status; ?></td>
				<td>
					<select name="enabled">
						<option value="1"<?php echo ($setting['enabled']==1)? ' selected' : ''; ?>><?php echo $text_enabled?></option>
						<option value="0"<?php echo ($setting['enabled']==0)? ' selected' : ''; ?>><?php echo $text_disabled?></option>
					</select>
				</td>
			</tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>