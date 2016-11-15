<?php 
$qc = new QC($accesstoken,$openid);
$arr = $qc->get_user_info();
$length=6;
$output='';
for ($a = 0; $a < $length; $a++) {
$output .= mt_rand(33, 126);    //生成php随机数
}
?>
<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
	<div class="content">
	<div style="float:left; clear:left; width:49%;">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="create">
    <h2><?php echo $entry_create; ?></h2>
      <table class="form" style="border:1px #ccc solid;">
        <tr>
			  <td colspan="2" style="padding:10px; background:#E4E4E4; color:#333;"><?php echo $text_create; ?></td>
		</tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="create_email" id="create_email" value="<?php echo $create_email; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" name="create_password" id="create_password" value="<?php echo $create_password; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
          <td><input type="password" name="create_confirm" id="create_confirm" value="<?php echo $create_confirm; ?>" /></td>
        </tr>
       
        <tr>
          <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="create_firstname" id="create_firstname" value="<?php echo $create_firstname; ?>" /></td>
        </tr>
       
        <tr>
          <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
          <td><input type="text" name="create_telephone" id="create_telephone" value="<?php echo $create_telephone; ?>" />
		    <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
    <!--       
	   <tr>
          <td><?php echo $entry_fax; ?></td>
          <td><input type="hidden" name="create_fax" value="" /></td>
        </tr>
		 <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="hidden" name="create_lastname" value="" /></td>
        </tr>
    -->       
	   <tr>
			  <td colspan="2" height="10">
		<input type="hidden" name="nickname" value="<?php echo $arr["nickname"]; ?>" />
		<input type="hidden" name="accessToken" value="<?php echo $accesstoken; ?>" />
		<input type="hidden" name="openid" value="<?php echo $openid; ?>" />
			  </td>
		</tr>
        <tr>
			  <td colspan="2" style="padding:5px; border-top:1px #ccc dotted;"><a onclick="doQQRegister();" class="button"><span><?php echo $button_continue; ?></span></a></td>
		</tr>
      </table>
	</form>
	</div>
	 <!--  
	<div style="float:right; clear:right; width:49%;">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="bind">
    <h2><?php echo $entry_binding; ?></h2>
      <table class="form" style="border:1px #ccc solid;">
        <tr>
			  <td colspan="2" style="padding:10px; background:#E4E4E4; color:#333;"><?php echo $text_binding; ?></td>
		</tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="bind_email" value="" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" name="bind_password" value="" /></td>
        </tr> 
        <tr>
       <input type="hidden" name="nickname" value="<?php echo $arr["nickname"]; ?>" />
		<input type="hidden" name="accessToken" value="<?php echo $accesstoken; ?>" />
		<input type="hidden" name="openid" value="<?php echo $openid; ?>" />	
	</tr>
        <tr>
			  <td colspan="2" style="padding:5px; border-top:1px #ccc dotted;"><a onclick="$('#bind').submit()" class="button"><span><?php echo $button_continue; ?></span></a></td>
		</tr>
      </table>
	</form>
	</div>
--> 
	
	</div>
	</div>
    <div class="buttons">&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $back; ?>" class="button"><span><?php echo $button_back; ?></span></a></div>
<script type="text/javascript"><!--
$('#create input').keydown(function(e) {
	if (e.keyCode == 13) {
	  if(checkForm()){
        $('#create').submit();
      }
	}
});
$('#bind input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#bind').submit();
	}
});
function doQQRegister(){
if(checkForm()){
  $('#create').submit();
  }
}
function checkForm(){
if($('#create_email').val()==''){
  alert('请填写QQ邮箱');
  return false;
}
if($('#create_password').val()==''){
  alert('请填写密码');
  return false;
}
if($('#create_confirm').val()==''){
  alert('请填写确认密码');
  return false;
}
if($('#create_confirm').val()!=$('#create_password').val()){
  alert('密码不一致');
  return false;
}
if($('#create_firstname').val()==''){
  alert('请填写真实姓名');
  return false;
}
if($('#create_telephone').val()==''){
  alert('请填写手机号码');
  return false;
}
return true;
}
//--></script> 
<?php echo $footer; ?>