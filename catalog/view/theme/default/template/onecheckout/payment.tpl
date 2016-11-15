<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<table class="form_paymethod "  >
  <?php foreach ($payment_methods as $payment_method) { ?>
  <tr>
    <td style="width: 1px;"><?php if ($payment_method['code'] == $code || !$code) { ?>
      <?php $code = $payment_method['code']; ?>
      <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
      <?php } else { ?>
      <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
      <?php } ?></td>
    <td><label for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></label></td>
  </tr>
  <?php } ?>
  </table>
  <table width="100%" border="0" style="padding-left:15px;">
 <tr>
  <td ><input type="radio" name="pd_FrpId" value="ICBC-NET-B2C"/><img src="images_bank/gonghang.jpg" width="80%"></td>
  <td ><input type="radio" name="pd_FrpId" value="CCB-NET-B2C"/><img src="images_bank/jianhang.jpg" width="80%"></td>
 </tr> 
 <tr>
 <td ><input type="radio" name="pd_FrpId" value="BOC-NET-B2C"/><img src="images_bank/zhonghang.jpg" width="80%"></td>
 
 <td><input type="radio" name="pd_FrpId" value="ABC-NET-B2C"/><img src="images_bank/nonghang.jpg" width="80%"></td>

  </tr>
   <tr>
  <td><input type="radio" name="pd_FrpId" value="CMBCHINA-NET-B2C" /><img src="images_bank/zhaohang.jpg" width="80%"></td>
  <td><input type="radio" name="pd_FrpId" value="POST-NET-B2C"/><img src="images_bank/youzheng.jpg" width="80%"></td>
  </tr> 
<tr>
 <td><input type="radio" name="pd_FrpId" value="BOCO-NET-B2C"/><img src="images_bank/jiaohang.jpg" width="80%"></td>
  <td><input type="radio" name="pd_FrpId" value="CIB-NET-B2C"/><img src="images_bank/xingye.jpg" width="80%"></td>
  </tr>  
  </table>
<?php } ?>
</br></br>
<h2 style="border-bottom:1px #000000 solid; padding-bottom:6px;font-family: Arial, Helvetica, sans-serif;font-weight: bold;font-size: 16px;color: #000000;"><?php echo $text_checkout_comment; ?></h2>

<textarea name="comment" rows="3" style="width: 90%;"><?php echo $comment; ?></textarea>
<br />
<script type="text/javascript"><!--
$(document).ready(function() {
 $("input[name='pd_FrpId']").bind("click",function(){
  $('#instId').val(this.value); 
  $("input[name='payment_method'][value='yeepay']").trigger("click");
  });
});
$('#payment-method textarea[name=\'comment\']').live('blur', function() {
	jQuery.post('index.php?route=onecheckout/payment/savecomment',$('#payment-method textarea[name=\'comment\']'));
});
//--></script> 