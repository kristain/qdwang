<?php echo $header;  
if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="login-content">
    <div class="left">
      <h2><?php echo $text_new_customer; ?></h2>
      <div class="content">
        <p><b><?php echo $text_register; ?></b></p>
        <p><?php echo $text_register_account; ?></p>
        <a href="<?php echo $register; ?>" class="button"><?php echo $button_continue; ?></a></div>
    </div>
    <div class="right">
      <h2><?php echo $text_returning_customer; ?></h2>      
        <div class="content">
          <p><?php echo $text_i_am_returning_customer; ?></p>
		  
		  <table width="100%" height="50">
		  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
		  <tbody>		  
		  <tr><td>		  
          <b><?php echo $entry_email; ?></b><br />
          <input type="text" name="email" value="<?php echo $email; ?>" />
          </td><td>
          <b><?php echo $entry_password; ?></b><br />
          <input type="password" name="password" value="<?php echo $password; ?>" />
          </td></tr>
		  </tbody><tbody><tr><td colspan="2" >          
          <input type="submit" value="<?php echo $button_login; ?>" class="button" />&nbsp;&nbsp;&nbsp;&nbsp;
		  <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
		  <?php if ($redirect) { ?>
          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
          <?php } ?>
		  </td></tr></tbody>
		  </form>
		  		
		  <tbody><tr><td colspan="2" valign="middle"><?=$text_third_authen ?>	
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
			
		  </table>		  
		  
		  
        </div>
 
  </div>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#login input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#login').submit();
	}
});
//--></script> 
<?php echo $footer; ?>