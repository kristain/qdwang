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

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
	<div class="content">
	<div style="float:right; clear:right; width:49%;">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="create">
    <h2><?php echo $entry_create; ?></h2>
      <table class="form" style="border:0px #ccc solid;">
      <tr>
		     <td colspan="1" style="padding:5px; border-top:0px #ccc dotted;"align="right">请点击“继续”，即完成登录，并跳转到特价商品页&nbsp;&nbsp;<a onclick="$('#create').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
		</tr>       
	   <tr>
			  <td colspan="1" style="padding:10px; background:#E4E4E4; color:#333;"><?php echo $text_create; ?></td>
		</tr>
        <tr>
          
          <td><input  type="hidden"  name="create_email" value="<?php echo $output; ?>@163.com" /></td>
        </tr>
        <tr>
          
          <td><input type="hidden"  name="create_password" value="123456" /></td>
        </tr>
        <tr>
         
          <td><input type="hidden"  name="create_confirm" value="123456" /></td>
        </tr>
       
        <tr>
         
         <td><input type="hidden"  name="create_firstname" value="guest" /></td>    
   
	   </tr>
        <tr>
       
        <td><input type="hidden" name="create_lastname" value="guest" /></td>  
		   
        </tr>
        <tr>
       
          <td><input type="hidden" name="create_telephone" value="13305810610" /></td>
        </tr>
        <tr>
        
          <td><input type="hidden" name="create_fax" value="88083456" /></td>
        </tr>
        <tr>
		<input type="hidden" name="nickname" value="<?php echo $arr["nickname"]; ?>" />
		<input type="hidden" name="accessToken" value="<?php echo $accesstoken; ?>" />
		<input type="hidden" name="openid" value="<?php echo $openid; ?>" />
			  </td>
		</tr>
       
      </table>
	</form>
	</div>
	<script type="text/javascript"><!--
	
	$(document).ready(function() {
	   $('#create').submit();
	});
	
$('#create input').keydown(function(e) {
	
		$('#create').submit();
	}
);
$('#bind input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#bind').submit();
	}
});
//--></script> 