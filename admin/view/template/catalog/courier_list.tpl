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
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
   </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="center"><?php echo $column_image; ?></td>
		
            <td class="left"><?php if ($sort == 'courier_name') { ?>
              <a href="<?php echo $sort_courier_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_courier_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_courier_name; ?>"><?php echo $column_courier_name; ?></a>
              <?php } ?></td>
			 <td class="right"><?php echo $column_total_products; ?></td>
            <td class="right"><?php if ($sort == 'sort_order') { ?>
              <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
            <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($couriers) { ?>
          <?php foreach ($couriers as $courier) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($courier['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $courier['courier_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $courier['courier_id']; ?>" />
              <?php } ?></td>
			<td class="center"><img src="<?php echo $courier['image']; ?>" alt="<?php echo $courier['image']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
			<td class="left"><?php echo $courier['courier_name']; ?></td>
			<td class="right"><?php echo $courier['total_products']; ?></td>
			<td class="right"><?php echo $courier['sort_order']; ?></td>
            <td class="right"><?php foreach ($courier['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
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
<?php echo $footer; ?>