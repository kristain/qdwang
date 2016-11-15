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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_username; ?></td>
            <td><input type="text" name="username" value="<?php echo $username; ?>" />
              <?php if ($error_username) { ?>
              <span class="error"><?php echo $error_username; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
            <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
              <?php if ($error_firstname) { ?>
              <span class="error"><?php echo $error_firstname; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
            <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
              <?php if ($error_lastname) { ?>
              <span class="error"><?php echo $error_lastname; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_email; ?></td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_user_group; ?></td>
            <td><select name="user_group_id">
                <?php foreach ($user_groups as $user_group) { ?>
                <?php if ($user_group['user_group_id'] == $user_group_id) { ?>
                <option value="<?php echo $user_group['user_group_id']; ?>" selected="selected"><?php echo $user_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_password; ?></td>
            <td><input type="password" name="password" value="<?php echo $password; ?>"  />
              <?php if ($error_password) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php  } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_confirm; ?></td>
            <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
              <?php if ($error_confirm) { ?>
              <span class="error"><?php echo $error_confirm; ?></span>
              <?php  } ?></td>
          </tr>

			<tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
				<?php if ($status != 5) { ?>
					<?php if ($status) { ?>
						<option value="0"><?php echo $text_disabled; ?></option>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="5"><?php echo $txt_pending_approval; ?></option>
					<?php } else { ?>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="5"><?php echo $txt_pending_approval; ?></option>
					<?php } ?>
				<?php } else { ?>
					<option value="0"><?php echo $text_disabled; ?></option>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="5" selected="selected"><?php echo $txt_pending_approval; ?></option>
				<?php } ?>
              </select></td>
			</tr>
			<tr>
				<td><?php if (!$folder_path) { echo $entry_folder_path; } else { echo $entry_folder_path_remove; } ?>
				</td>
				<td><?php if (!$folder_path) { ?>
					<input type="checkbox" name="generate_path" value="1" />
					<?php } else { ?>
					<input type="checkbox" name="remove_path" value="1" /><font color="red"><?php echo '<b>( ' . $text_remove . ' ' . $folder_path . ' )</b>'; ?></font>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_vendor; ?></td>
				<td><select name="vendor_product">
					<option value="" selected="selected"><?php echo $text_none; ?></option>
					<?php foreach ($vendor_List as $vendor) { ?>
					<?php if ($vendor['vendor_id'] == $vendor_product) { ?>
					<option value="<?php echo $vendor['vendor_id']; ?>" selected="selected"><?php echo $vendor['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $vendor['vendor_id']; ?>"><?php echo $vendor['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select>
			</tr>
			<tr>
				<td><?php echo $entry_category; ?></td>
				<td><div class="scrollbox">
					<?php $class = 'odd'; ?>
					  <?php foreach ($categories as $category) { ?>
					  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
					  <div class="<?php echo $class; ?>">
						<?php if ($vendor_category) { ?>
							<?php if (in_array($category['category_id'], $vendor_category)) { ?>
								<input type="checkbox" name="vendor_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" /><?php echo $category['name']; ?>
							<?php } else { ?>
								<input type="checkbox" name="vendor_category[]" value="<?php echo $category['category_id']; ?>" /><?php echo $category['name']; ?>
							<?php } ?>
						<?php } else { ?>
							<input type="checkbox" name="vendor_category[]" value="<?php echo $category['category_id']; ?>" /><?php echo $category['name']; ?>
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
						<?php if ($product_store) { ?>
							<?php if (in_array(0, $product_store)) { ?>
								<input type="checkbox" name="product_store[]" value="0" checked="checked" />
								<?php echo $text_default; ?>
							<?php } else { ?>
								<input type="checkbox" name="product_store[]" value="0" />
								<?php echo $text_default; ?>
							<?php } ?>
						<?php } else { ?>
							<input type="checkbox" name="product_store[]" value="0" />
							<?php echo $text_default; ?>
						<?php } ?>
					  </div>
					 <?php foreach ($stores as $store) { ?>
					  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
					  <div class="<?php echo $class; ?>">
						<?php if ($product_store) { ?>
							<?php if (in_array($store['store_id'], $product_store)) { ?>
								<input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" /><?php echo $store['name']; ?>
							<?php } else { ?>
								<input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" /><?php echo $store['name']; ?>
							<?php } ?>
						<?php } else { ?>
							<input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" /><?php echo $store['name']; ?>
						<?php } ?>
					  </div>
					  <?php } ?>
					</div>
					<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
			</tr>
			

			<!--
			
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>

			-->
			
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 