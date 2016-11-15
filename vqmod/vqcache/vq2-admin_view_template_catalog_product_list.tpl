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
      <h1><?php echo $text_mass_discount;?></h1>
    </div>
    <div class="content" style="min-height:60px!important;margin-bottom:25px">
      <form id="frm-mass-discount" action="<?php echo $discount; ?>" method="post">
      <table>
      <thead>
        <tr>
        <td><?php echo $text_customer_group;?></td>
        <td><?php echo $text_start_date;?></td>
        <td><?php echo $text_end_date;?></td>
        <td><?php echo $text_discount;?></td>
        <td><?php echo $text_type;?></td>
        <td><?php echo $text_category;?></td>
        <td></td>
        </tr>
      </thead>
      <tr>
      <td>
        <select name="customer_group_id"  >        
          <?php 
            foreach($customer_groups as $cus){         
              echo '<option value="'.$cus['customer_group_id'].'">'.$cus['name'].'</option>';
            }
          ?>        
        </select>
      </td>
      <td><input type="text" name="date_start" value="0000-00-00" class="date"></td>
          <td><input type="text" name="date_end" value="0000-00-00" class="date"></td>
          <td><input type="text" name="discount" id="discount" value="0"/></td>
          <td><select name="type">
            <option value="1"><?php echo $text_value;?></option>
            <option value="2"><?php echo $text_percentage;?></option>
          </select></td>
      <td>
        <select name="category_id"  >        
          <option value="-1"><?php echo $text_all;?></option>
          <?php 
            foreach($categories as $cat){         
              echo '<option value="'.$cat['category_id'].'">'.$cat['name'].'</option>';
            }
          ?>        
        </select>
      </td>
          <td><a class="button" onclick="submitDiscount();"><?php echo $text_ok;?></a></td>
      </tr>     
      </table>
        </form>
    </div>
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a><a onclick="$('#form').attr('action', '<?php echo $copy; ?>'); $('#form').submit();" class="button"><?php echo $button_copy; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="center"><?php echo $column_image; ?></td>
              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>

			<td class="left"><?php if ($sort == 'vds.vendor_name') { ?>
            <a href="<?php echo $sort_vendor_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_vendor; ?></a>
            <?php } else { ?>
            <a href="<?php echo $sort_vendor_name; ?>"><?php echo $column_vendor; ?></a>
            <?php } ?></td>
			
              <td class="left"><?php if ($sort == 'p.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                <?php } ?></td>

			<td class="left"><?php if ($sort == 'p.sku') { ?>
            <a href="<?php echo $sort_sku; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sku; ?></a>
            <?php } else { ?>
            <a href="<?php echo $sort_sku; ?>"><?php echo $column_sku; ?></a>
            <?php } ?></td>
			
              <td class="left"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.quantity') { ?>
                <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td></td>
              <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>

			<td><select name="filter_vendor_name">
		        <option value="*"></option>
				<?php if (!is_null($filter_vendor_name) && !$filter_vendor_name) { ?>
                <?php } else { ?>
				<?php } ?>
                <?php foreach ($vendors as $vendor) { ?>
					<?php if (!is_null($filter_vendor_name) && !$filter_vendor_name) { ?>
					  <option value="<?php echo $vendor['vendor_id']; ?>"><?php echo $vendor['vendor_name']; ?></option>
						<?php } else { ?>
					  <option value="<?php echo $vendor['vendor_id']; ?>"><?php echo $vendor['vendor_name']; ?></option>
					<?php } ?>
				<?php } ?>
                </select>				
			</select></td>
			
              <td><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" /></td>

			<td><input type="text" name="filter_sku" value="<?php echo $filter_sku; ?>" /></td>
			
              <td align="left"><input type="text" name="filter_price" value="<?php echo $filter_price; ?>" size="8"/></td>
              <td align="right"><input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" style="text-align: right;" /></td>
              <td><select name="filter_status">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_status) && !$filter_status) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>

			<?php if ($filter_status == 5) { ?>
                <option value="5" selected="selected"><?php echo $txt_pending_approval; ?></option>
            <?php } else { ?>
                <option value="5"><?php echo $txt_pending_approval; ?></option>
            <?php } ?>
			
                </select></td>
              <td align="right"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
            </tr>
            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                <?php } ?></td>
              <td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
              <td class="left"><?php echo $product['name']; ?></td>

			<td class="left"><?php echo $product['vendor_name']; ?></td>
			
              <td class="left"><?php echo $product['model']; ?></td>

			<td class="left"><?php echo $product['sku']; ?></td>
			
              <td class="left"><?php if ($product['special']) { ?>
                <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                <span style="color: #b00;"><?php echo $product['special']; ?></span>
                <?php } else { ?>
                <?php echo $product['price']; ?>
                <?php } ?></td>
              <td class="right"><?php if ($product['quantity'] <= 0) { ?>
                <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
                <?php } elseif ($product['quantity'] <= 5) { ?>
                <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
                <?php } else { ?>
                <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
                <?php } ?></td>
              <td class="left"><?php echo $product['status']; ?></td>
              <td class="right"><?php foreach ($product['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              
			<td class="center" colspan="10"><?php echo $text_no_results; ?></td>
			

            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_model = $('input[name=\'filter_model\']').attr('value');
	
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
	

			var filter_vendor_name = $('select[name=\'filter_vendor_name\']').attr('value');
	
			if (filter_vendor_name != '*') {
				url += '&filter_vendor_name=' + encodeURIComponent(filter_vendor_name);
			}	
			
			var filter_sku = $('input[name=\'filter_sku\']').attr('value');
			
			if (filter_sku) {
				url += '&filter_sku=' + encodeURIComponent(filter_sku);
			}
			
	var filter_price = $('input[name=\'filter_price\']').attr('value');
	
	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}
	
	var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');
	
	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}	

	location = url;
}
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);
						
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('input[name=\'filter_model\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.model,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_model\']').val(ui.item.label);
						
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script> 
<script type="text/javascript">
      $(document).ready(function(){
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});  
      })
      function submitDiscount() {
        if(confirm('<?php echo $text_sure;?>')){
          $('#frm-mass-discount').submit();
        }
        
      }
      

			$('input[name=\'filter_sku\']').autocomplete({
				delay: 0,
				source: function(request, response) {
					$.ajax({
						url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_sku=' +  encodeURIComponent(request.term),
						dataType: 'json',
						success: function(json) {		
							response($.map(json, function(item) {
								return {
									label: item.sku,
									value: item.product_id
								}
							}));
						}
					});
				}, 
				select: function(event, ui) {
					$('input[name=\'filter_sku\']').val(ui.item.label);
									
					return false;
				}
			});
			
    </script>
<?php echo $footer; ?>