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
			<td class="left"><?php if ($sort == 'package_name') { ?>
              <a href="<?php echo $sort_package_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_package_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'product_limit') { ?>
              <a href="<?php echo $sort_product_limit; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_product_limit; ?>"><?php echo $column_quantity; ?></a>
              <?php } ?></td>
			<td class="right"><?php echo $column_total_vendors; ?></td>
            <td class="right"><?php if ($sort == 'sort_order') { ?>
              <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
            <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($product_limits) { ?>
          <?php foreach ($product_limits as $product_limit) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($product_limit['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $product_limit['product_limit_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $product_limit['product_limit_id']; ?>" />
              <?php } ?></td>
			<td class="left"><?php echo $product_limit['package_name']; ?></td>
			<td class="left"><?php echo $product_limit['product_limit']; ?></td>
			<td class="right"><?php echo $product_limit['total_vendors']; ?></td>
			<td class="right"><?php echo $product_limit['sort_order']; ?></td>
            <td class="right"><?php foreach ($product_limit['action'] as $action) { ?>
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