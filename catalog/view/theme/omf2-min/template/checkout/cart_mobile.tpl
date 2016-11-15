<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
	<?php echo $content_top; ?>
	<div class="breadcrumb">
	  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
	  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	  <?php } ?>
	</div>
	<h1 style="border-bottom:1px solid #666;">
		<?php echo $heading_title; ?>
		<?php if ($weight) { ?>
		&nbsp;(<?php echo $weight; ?>)
		<?php } ?>
	</h1>
	<?php if ($attention) { ?>
	<div class="attention"><?php echo $attention; ?></div>
	<?php } ?>    
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="basket">
		<div class="cart-info">				
			<ul>
				<?php foreach ($products as $product) { ?>
				<li>
					<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>
					<div class="name">
					    <?php $length=(strlen($product['name']) + mb_strlen($product['name'],'UTF8'))/2	   ;?>
					    <?php if( $length<=16) { ?>
				             <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
					    <?php } else{ ?>
					        <a href="<?php echo $product['href']; ?>"><?php echo mb_substr($product['name'],0,17,'utf-8'); ?><?php echo ".." ;?></a>
				 	   <?php }?>
						<?php if (!$product['stock']) { ?>
						<span class="stock">***</span>
						<?php } ?>
						<div>
						<?php foreach ($product['option'] as $option) { ?>
						- <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
						<?php } ?>
						</div>
						<?php if (isset($product['reward'])) { ?>
						<small><?php echo $product['reward']; ?></small>
						<?php } ?>
					</div>
                    </td>
                    <td style="text-align:center;" width="30%">
					<?php if ($direction === "ltr") { ?>
					<span class="price"><?php echo $product['price']; ?></span>
					<span class="quantity">X<?php echo $product['quantity']; ?></span>
					<span class="total" style="display:inline-block;">= <?php echo $product['total']; ?></span>
					<?php } else if ($direction === "rtl") { ?>
					<span class="price">&#x200E;<?php echo $product['price']; ?> X</span>
					<span class="quantity"><?php echo $product['quantity']; ?></span>
					<span class="total" style="display:inline-block;">&#x200E;<?php echo $product['total']; ?> =&#x200E;</span>
					<?php } ?>
                    </td>
                    <td width="20">
	<?php if (defined('VERSION') && (version_compare(VERSION, '1.5.2', '<') == true)) { ?>
					<div><input type="checkbox" name="remove[]" value="<?php echo $product['key']; ?>" /> <image src="catalog/view/theme/default/image/remove.png" width="15" height="15"></div>
	<?php } else { ?>
					<div><a href="<?php echo $product['remove']; ?>"><image src="catalog/view/theme/default/image/remove.png" width="15" height="15"/></a></div>
	<?php } ?>
    				</td>
				</tr>
                </table>
				</li>
				<?php } ?>
				<?php foreach ($vouchers as $voucher) { ?>
				<li>
					<div class="image"></div>
					<div class="name"><?php echo $voucher['description']; ?></div>
					<div class="model"></div>
					<div class="quantity">1</div>
					<div class="price"><?php echo $voucher['amount']; ?></div>
					<div class="total"><?php echo $voucher['amount']; ?></div>
	<?php if (defined('VERSION') && (version_compare(VERSION, '1.5.2', '<') == true)) { ?>
					<div class="remove"><input type="checkbox" name="voucher[]" value="<?php echo $voucher['key']; ?>" /></div>
	<?php } else { ?>
					<div class="remove"><input type="checkbox" name="voucher[]" value="<?php echo $voucher['key']; ?>" /> <?php echo $text_remove; ?></div>
	<?php } ?>
				</li>
				<?php } ?>
			</ul>				
		</div>
		<input style='display:none' type="submit" value="<?php echo $button_update; ?>" />
	</form>

	<div class="cart-module">
	<?php if (defined('VERSION') && (version_compare(VERSION, '1.5.2', '<') == true)) { ?>
	<?php foreach ($modules as $module) { ?>
		<?php echo $module; ?>
	<?php } ?>
	<?php } else { ?>
		<?php if ($coupon_status) { ?>
		<div id="coupon" class="m-cart">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="inline-form">
				<label for="coupon"><?php echo $entry_coupon; ?></label>
				<input type="text" name="coupon" value="<?php echo $coupon; ?>" />
				<input type="hidden" name="next" value="coupon" />				
				<input type="submit" value="<?php echo $button_apply; ?>"/>
			</form>
		</div>
		<?php } ?>
		<?php if ($voucher_status) { ?>
		<div id="voucher" class="m-cart">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="inline-form">
				<label for="voucher"><?php echo $entry_voucher; ?></label>
				<input type="text" name="voucher" value="<?php echo $voucher; ?>" />
				<input type="hidden" name="next" value="voucher" />
				<input type="submit" value="<?php echo $button_apply; ?>"/>
			</form>
		</div>
		<?php } ?>
		<?php if ($reward_status) { ?>
		<div id="reward" class="m-cart">
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="inline-form">
				<label for="reward"><?php echo $entry_reward; ?></label>
				<input type="text" name="reward" value="<?php echo $reward; ?>" />
				<input type="hidden" name="next" value="reward" />
				<input type="submit" value="<?php echo $button_apply; ?>"/>
			</form>
		</div>
		<?php } ?>
	<?php } ?>
	</div>	
	<div  style="border-bottom:1px solid #666;"></div>
	<ul class="cart-total">
		<?php foreach ($totals as $total) { ?>				
		<li>
			<?php echo $total['title']; ?>: <strong><?php echo $total['text']; ?></strong>
		</li>
		<?php } ?>
	</ul>	
		
	<div class="buttons">      
		<a href="<?php echo $continue; ?>" class="button"><?php echo $button_shopping; ?></a>
		<a href="<?php echo $checkout; ?>" class="button"><?php echo $button_checkout; ?></a>		
	</div>
	<?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>