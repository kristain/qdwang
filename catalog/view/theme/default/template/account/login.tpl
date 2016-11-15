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
          <b>手机号码/<?php echo $entry_email; ?></b><br />
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
		  <tbody><tr><td colspan="2" ></td></tr></tbody>
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