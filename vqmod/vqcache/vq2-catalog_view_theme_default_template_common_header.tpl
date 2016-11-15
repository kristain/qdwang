<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
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
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/popup.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/colorbox/jquery.colorbox.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/colorbox/colorbox.css" media="screen" />
<script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<script type="text/javascript" src="catalog/view/theme/default/popup.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<?php echo $google_analytics; ?>

				
<script type="text/javascript">
// Fixed Header
$(document).ready(function(){
	// Copy our header id and place it in the headerFixed id
	var header = "<div id='logoFixed'>"+$("#header").find("#logo").html()+"</div>";
	//header += "<div class='links'>"+$("#header").find(".links").html()+"</div>";
	header += "<div id='cartFixed'><h4>"+$("#header").find(".heading > h4").html()+"</h4> - "+$("#header").find(".heading > a").html()+"</div>";
	$("#fixedHeader").html("<div>"+header+"</div>");
	
	// Set our click for cart
	$("#cartFixed").on('click', function(){
		//window.location.href ="index.php?route=checkout/cart";
	});
	
	//Set our transistion type
	var showFixedHeader = function (trans, scrollValue){
		var elem = $("#fixedHeader");
		if(trans == "fade"){
			elem.fadeIn();
		}else if(trans == "animate"){
			elem.show();
			elem.stop().animate({top: "0"}, 250);
		}else{
			elem.show();
		}
	}
	
	var hideFixedHeader = function (trans){
		var elem = $("#fixedHeader");
		if(trans == "fade"){
			elem.fadeOut();
		}else if(trans == "animate"){
			elem.stop().animate({top: "-50px"}, 250);
		}else{
			elem.hide();
		}
	}
	
	// Show our fixed header
	var position = $("#header").position();
	var height = $("#header").height();
	var scrollValue = height+position.top;
	$(window).scroll(function() {
		if ($(this).scrollTop() > scrollValue) {
			showFixedHeader("animate");
		} else {
			hideFixedHeader("animate");
		}
	});
	
	$("#cartFixed").on("click", function(){
		window.location.replace('index.php?route=checkout/cart');
	});
});
</script>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/fixedHeader.css" />
</head>
<div id="fixedHeader"></div>
				
			
<body>
<div id="topnav">
  <div id="welcome">
  
    <?php if (!$logged) { ?>
     		
		  <tbody><tr><td colspan="2" valign="middle">&nbsp;&nbsp;<?=$text_third_authen ?>	
<?php if($this->config->get('qq_connect') == 1) { ?>		  
			<form action="<?php echo $this->url->link('account/qq_connect');?>" id="qq_login_account" method="post">
				<span id="qqLoginBtn"></span>
				<input type="hidden" name="openid" id="openid" value="" />
				<input type="hidden" name="accesstoken" id="accesstoken" value="" />
			</form>
			<script type="text/javascript" src="//qzonestyle.gtimg.cn/qzone/openapi/qc_loader.js" data-appid="<?php echo $this->config->get('qq_appid'); ?>" charset="utf-8" ></script>
			<script type="text/javascript">
				QC.Login.signOut(); 
				QC.Login({//自定义登陆按钮
					btnId : "qqLoginBtn",
					size : "B_M",
					scope : "get_user_info",
					display : "pc"
				},function(){
					var dom = document.getElementById('openid');
					var dom1 = document.getElementById('accesstoken');
					var c_loginaccount = document.getElementById('qq_login_account');
					QC.Login.getMe(function(openId, accessToken){
						dom.value = openId;
						dom1.value = accessToken;
						c_loginaccount.submit();
					});
				},function(){
				});
			</script> 
<?php }  
if($this->config->get('weibo_connect') == 1) { 
include_once( DIR_APPLICATION.'ext/weibo_saetv2.ex.class.php' );
$o = new SaeTOAuthV2( $this->config->get('weibo_appkey') , $this->config->get('weibo_secret'));

$weibo_code_url = $o->getAuthorizeURL( $this->url->link('account/weibo_connect') );
?>
<a href="<?=$weibo_code_url?>"><img src="/catalog/view/theme/default/image/weibo_button.png" /></a>	
<?php } ?>
		  </td></tr></tbody>				
			&nbsp;&nbsp;
	<?php echo $text_welcome; ?>
	<?php if(isset($_COOKIE["email"])&&isset($_COOKIE["password"])&&$_COOKIE["email"]!=""&&$_COOKIE["password"]!=""){ ?>
        <form id="loginForm" action="<?php echo $this->url->link('account/login', '', 'SSL'); ?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="email" value="<?php echo $_COOKIE["email"]; ?>" />
			<input type="hidden" name="password" value="<?php echo $_COOKIE["password"]; ?>" />
			</form>
			<script type="text/javascript">
			   $('#loginForm').submit();
			</script>
    <?php } ?>
	<?php if(isset($_COOKIE["qq_openid"])&&$_COOKIE["qq_openid"]!=""){ ?>
        <form id="loginQQForm" action="<?php echo $this->url->link('account/qq_connect', '', 'SSL'); ?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="openid" value="<?php echo $_COOKIE["qq_openid"]; ?>" />
			</form>
			<script type="text/javascript">
			   $('#loginQQForm').submit();
			</script>
    <?php } ?>
	    <?php } else { ?>
    <?php echo $text_logged; ?>
    <?php } ?>
  </div>
  <div class="links"><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a><?php if ($this->config->get('sign_up')) { ?><a href="<?php echo $signup; ?>"><?php echo $txt_signup; ?></a><?php } ?> | <a href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a> | <a href="<?php echo $account; ?>"><?php echo $text_account; ?></a> | <a href="<?php echo $shopping_cart; ?>"><?php echo $text_shopping_cart; ?></a> | <a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></div>
</div>
<div id="container">
<div id="header">
  <?php if ($logo) { ?>
  <div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
  <?php } ?>
  <?php echo $language; ?>
  <?php echo $currency; ?>
  <?php echo $cart; ?>
  <div id="search">
    <div class="button-search"></div>
    <?php if ($filter_name) { ?>
    <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
    <?php } else { ?>
    <input type="text" name="filter_name" value="<?php echo $text_search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '#000000';" />
    <?php } ?>
  </div>
</div>
<?php if ($categories) { ?>
<div id="menu">
  <ul>
    <?php foreach ($categories as $category) { ?>
    <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
      <?php if ($category['children']) { ?>
      <div>
        <?php for ($i = 0; $i < count($category['children']);) { ?>
        <ul>
          <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($category['children'][$i])) { ?>
          <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
      </div>
      <?php } ?>
    </li>
    <?php } ?>
  </ul>
</div>
<?php } ?>
<div id="notification"></div>
