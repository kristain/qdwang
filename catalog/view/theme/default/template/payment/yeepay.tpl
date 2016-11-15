<div class="buttons">
 <div class="right">
   <a id="yeepaylocation" onclick="window.location ='<?php echo $action; ?>';" class="button" style="display:none"><span><?php echo $button_confirm; ?></span></a>
  
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
 $("#yeepaylocation").bind("click",function(){
  var action = '<?php echo $action; ?>'+$('#instId').val();
  window.location = action;
  });
  $('#yeepaylocation').trigger("click");
});
//--></script> 