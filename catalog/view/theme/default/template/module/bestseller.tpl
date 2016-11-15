<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content" >
    <div class="box-product" style="overflow:hidden;overflow-y:hidden;text-align:center;margin:0 auto;">
      <?php foreach ($products as $product) { ?>
      <div>
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
		
		<?php if ($product['vendorName']) { ?>
	 	   <?php echo $product['vendorName']; ?>
		  <?php } else{ ?> 
		  自营
		  <?php } ?>
		
        <?php if ($product['price']) { ?>
        <div class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php } ?>
        </div>
        <?php } ?>
        <?php if ($product['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
        <?php } ?>
        <div class="cart"><input type="button" title="最小订购量:<?php echo $product['minimum']; ?>" value="<?php echo $button_cart; ?>" <?php if (!array_key_exists($product['product_id'],$this->session->data['cart'])) { ?> onclick="addToCart('<?php echo $product['product_id']; ?>','<?php echo $product['minimum']; ?>');changeForAddCartOnce(this);"<?php }else{ ?>onclick="alert('你已购买。如修改数量，请点击进货清单');"<?php } ?> class="button33" /></div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
