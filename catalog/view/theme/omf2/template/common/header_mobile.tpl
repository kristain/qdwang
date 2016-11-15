<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') && strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n" ."<!-- This is here for the Old Opera mobile  -->"; ?>
<!DOCTYPE html>
<!-- OMFramework 2.2.0 Basic www.omframework.com -->
<!--[if IEMobile 7 ]>    <html class="no-js iem7"> <![endif]-->
<!--[if (gt IEMobile 7)|!(IEMobile)]><!--> <html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>"> <!--<![endif]-->
  <head>
    <meta charset="UTF-8" />
    <title><?php echo $title; ?></title>
    <base href="<?php echo $base; ?>" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' name='viewport' />
 	<link href="catalog/view/theme/omf2/stylesheet/home.css" rel="stylesheet" type="text/css" />
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content="<?php echo $keywords; ?>" />
    <?php } ?>
    <?php if ($icon) { ?>
    <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    <?php foreach ($links as $link) { ?>
    <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>
    <?php if (file_exists(DIR_TEMPLATE . $this->config->get('config_mobile_theme') . '/stylesheet/mobile2.scss')) { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo 'catalog/view/theme/' . $this->config->get('config_mobile_theme') ?>/s.php?p=mobile2.scss" >
    <?php } else { ?>
    <link rel="stylesheet" type="text/css" href="catalog/view/theme/omf2/s.php?p=mobile2.scss" >
    <?php } ?>
    <?php foreach ($styles as $style) { ?>
    <link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
    <?php } ?>
    <script>
    document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';
    setTimeout(scrollTo, 0, 0, 1);</script>
    <?php if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '') { ?>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
    <?php } else { ?>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui.min.js"></script>
    <?php } ?>
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<?php if (defined('VERSION') && (version_compare(VERSION, '1.5.5', '<') == true)) { ?>
    <script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/jquery/colorbox/jquery.colorbox.js"></script>
    <link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/colorbox/colorbox.css" media="screen" />
    <script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
<?php } ?>
    <script type="text/javascript" src="catalog/view/javascript/common.js"></script>
    <?php foreach ($scripts as $script) { ?>
    <script type="text/javascript" src="<?php echo $script; ?>"></script>
    <?php } ?>
	<script type="text/javascript">
function lodeSupport(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(updataPosition,locationError,{
        // 指示浏览器获取高精度的位置，默认为false
        enableHighAcuracy: false,
        // 指定获取地理位置的超时时间，默认不限时，单位为毫秒
        timeout: 60000,
        // 最长有效期，在重复获取地理位置时，此参数指定多久再次获取位置。
        maximumAge: 3000
    });
    }
}
function updataPosition(position){
    var latitudeP = position.coords.latitude,
        longitudeP = position.coords.longitude,
        accuracyP = position.coords.accuracy;
		$.cookie('position', longitudeP+','+latitudeP);
}
function locationError(error){
// 执行失败的回调函数，会接受一个error对象作为参数
          // error拥有一个code属性和三个常量属性TIMEOUT、PERMISSION_DENIED、POSITION_UNAVAILABLE
          // 执行失败时，code属性会指向三个常量中的一个，从而指明错误原因
          switch(error.code){
               case error.TIMEOUT:
                    alert('超时');
                    break;
               case error.PERMISSION_DENIED:
                    alert('用户拒绝提供地理位置');
                    break;
               case error.POSITION_UNAVAILABLE:
                    alert('地理位置不可用');
                    break;
               default:
                    break;
          }
}
window.addEventListener('load', lodeSupport , true);
</script>
    <?php echo $google_analytics; ?>
  </head>
  <body>
    <div id="container">
      <div id="header">
        <?php if ($this->config->get('config_mobile_logo') && file_exists(DIR_IMAGE . $this->config->get('config_mobile_logo'))) {
            $mobile_logo = 'image/' . $this->config->get('config_mobile_logo');
        } else {
            $mobile_logo = $logo;
        } ?>
        <div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $mobile_logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
		<?php echo $module; ?>
        <ul>   
        <?php if (defined('VERSION') && (version_compare(VERSION, '1.5.2', '<') == true)) { ?>
                  <li><a href="<?php echo $cart; ?>" tabindex="2" id="cart" ><?php echo $text_cart; ?> (<?php echo $text_items_count; ?>)&#x200E; </a></li>

        <?php } else { ?>
                  <li><a href="<?php echo $shopping_cart; ?>" tabindex="2" id="cart" ><?php echo $text_shopping_cart; ?> (<?php echo $text_items_count; ?>)&#x200E; </a></li>
        <?php } ?>
        <?php if ( is_null($this->config->get('config_wishlist_disabled')) or (bool)$this->config->get('config_wishlist_disabled') == false) { ?>
            <li><a href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a></li>
        <?php } ?>
            <li><a href="#search" tabindex="3" id="search_link"><?php echo $text_search_link; ?></a></li>
            <li> <?php if (!$logged) { ?>
            <?php echo $text_welcome; ?>
            <?php } else { ?>
            <?php echo $text_logged; ?>
           <?php } ?></li>

		
        </ul>
		
      </div>
      <div id="main">
        <div id="notification"></div>
