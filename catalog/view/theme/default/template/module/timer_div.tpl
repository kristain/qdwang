		<!-- Начало панели счетчика -->
		<div id="countdown_dashboard-<?php echo $random; ?>" class="countdown_dashboard">
		<?php if ($show_weeks > 0) { ?>
			<div class="dash weeks_dash">
				<span class="dash_title"><?php echo $text_weeks; ?></span>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>
		<?php } ?>

			<div class="dash <?php echo ($show_weeks == 0 ? 'dig3 ' : ''); ?>days_dash">
				<span class="dash_title"><?php echo $text_days; ?></span>
        		<?php if ($show_weeks == 0) { ?>
				<div class="digit">0</div>
        		<?php } ?>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>

			<div class="dash hours_dash">
				<span class="dash_title"><?php echo $text_hours; ?></span>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>

			<div class="dash minutes_dash">
				<span class="dash_title"><?php echo $text_min; ?></span>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>

			<div class="dash seconds_dash">
				<span class="dash_title"><?php echo $text_sec; ?></span>
				<div class="digit">0</div>
				<div class="digit">0</div>
			</div>

    	</div>
<!-- Завершение панели счетчика -->
<script language="javascript" type="text/javascript">
	jQuery(document).ready(function() {
		$('#countdown_dashboard-<?php echo $random; ?>').countDown({
			targetDate: {
				'day': 		<?php echo $day; ?>,
				'month': 	<?php echo $month; ?>,
				'year': 	<?php echo $year; ?>,
				'hour': 	<?php echo $hour; ?>,
				'min': 		<?php echo $min; ?>,
				'sec': 		<?php echo $sec; ?>
			}
    		<?php if ($show_weeks == 0) { ?>
    		, omitWeeks : true
    		<?php } ?>
		});
		
	});
</script>