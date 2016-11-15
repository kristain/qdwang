<div style="font-family: Helvetica, STHeiti STXihei, Microsoft JhengHei, Microsoft YaHei, Tohoma, Arial; font-size: 10px; line-height: 1.5;margin-bottom:20px;">
		<div class="links" align="center">
		     <?php foreach ($categories as $key =>$category) { ?>
		     	 <a href="<?php echo $category['href']; ?>"><image src="catalog/view/theme/omf2/images/<?php echo $key; ?>.png" width="45" height="45"><span><?php echo $category['name']; ?></span></a>
		     <?php } ?>
			 <span class="border border1"></span> <span class="border border2"></span>
			<span class="border border3"></span>
		</div>
</div>
<h1>&nbsp;&nbsp;</h1>
<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-product">
      <?php foreach ($products as $product) { ?>
      <div>
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
        <?php if ($product['price']) { ?>
        <div class="price" id= "left " style= "float:left; " >
          <?php if (!$product['special']) { ?>
         &nbsp; <?php echo $product['price']; ?>/<?php echo $product['sku']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php } ?>
        </div>
        <?php } ?>
	   <div class="model"  id= "right " style= "float:right; ">
		 <?php echo $product['model']; ?> 
		  </div> 
	   <?php echo "<br><br>" ;?>
	   <?php // 商品分二行显示	;?>
		<?php if ($product['name']) { ?>
	   <div class="name">
        <?php $length=(strlen($product['name']) + mb_strlen($product['name'],'UTF8'))/2	   ;?>
	    
		<?php if( $length<=28) { ?>
        <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><?php echo "<br>"; ?>
		<?php } elseif ($length>=29 && $length<56){ ?>
       <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
	   <?php } else{ ?>
	   <a href="<?php echo $product['href']; ?>"><?php echo substr($product['name'],0,56); ?><?php echo "..." ;?></a>
 	   <?php }?>
	   </div>
	   <?php } ?>
	   
	   <div class="name">
	   <?php if ($product['vendorName']) { ?>
	 	   <?php echo $product['vendorName']; ?>
		  <?php } else{ ?> 
		  自营
		  <?php } ?>
		  </div>
		  
		<div class="name">
		<?php if ($product['address']) { ?>
	 	   <?php echo $product['address']; ?><?php if ($product['distance'] <>'10000000') { ?> (距离:<?php echo $product['distance']; ?>)<?php } ?>
		  <?php }else{ ?>
		  <?php if ($product['distance'] <>'10000000') { ?> (距离:<?php echo $product['distance']; ?>)<?php } ?>
		  <?php } ?>
	 	   </div>
		   
        <?php if ($product['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
        <?php } ?>
        <p class="tip" style="display: none;" id="<?php echo "lastest".$product['product_id']; ?>">商品已添加至购物车</p>
        <div class="cart" style="float:right"><input type="button" title="最小订购量:<?php echo $product['minimum']; ?>" value="<?php echo $button_cart; ?>" <?php if (!array_key_exists($product['product_id'],$this->session->data['cart'])) { ?> onclick="addToCart('<?php echo $product['product_id']; ?>','<?php echo $product['minimum']; ?>');changeForAddCartOnce(this);"<?php }else{ ?>onclick="alert('你已购买。如修改数量，请点击购物车');"<?php } ?> class="button33" /></div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<script type="text/javascript">
function changeForAddCartOnce(obj)
{
  $(obj).attr('onclick','');
  $(obj).click(function(){
		alert('你已购买。如修改数量，请点击购物车');
  });
}
</script>
