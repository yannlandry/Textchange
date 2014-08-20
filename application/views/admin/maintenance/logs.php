<form method="get" name="dateform" class="form-inline pull-right">
	<?=lang('logs_fordate_prompt')?>
	<select name="date" onchange="dateform.submit()">
		<?php foreach($logslist as $date => $csdate): ?>
			<option value="<?=$date?>" <?php $logdate == $date and print('selected="selected"'); ?>><?=(date('Y-m-d')==$date?lang('logs_today'):$csdate)?></option>
		<?php endforeach; ?>
	</select>
	<button type="submit" class="btn btn-primary"><?=lang('logs_select_button')?></button>
</form>

<h4><?=lang('logs_header_title')?> <?=($logdate == date('Y-m-d') ? lang('logs_today') : csdate($logdate, true, true, true))?></h4>

<?php if(empty($content) || !is_array($content)): ?>
	<p class="no-result"><?=lang('logs_noresult')?></p>

<?php else: ?>
	<table class="table display-log">
		<?php foreach($content as $L):
			if(!empty($L)):
				switch($L->Type) {
					case 'ERROR': $trclass = 'error'; break;
					case 'INFO': $trclass = 'info'; break;
					case 'DEBUG': $trclass = 'warning'; break;
					default: $trclass = '';
				} ?>
				<tr class="<?=$trclass?>">
					<td><?=$L->Type?></td>
					<td><?=$L->Time->format('H:i:s')?></td>
					<td><em><?=$L->Message?></em></td>
				</tr>
			<?php endif;
		endforeach; ?>
	</table>

<?php endif; ?>

<form method="post" class="text-right">
	<?=csrf_token_input()?>
	<button type="submit" name="flush-one-log" class="btn" onclick="return confirm('<?=lang('logs_flushone_confirm')?>')">
		<i class="icon-trash"></i> <?=lang('logs_flushone_button')?></button>
	<button type="submit" name="flush-all-logs" class="btn btn-danger" onclick="return confirm('<?=lang('logs_flushall_confirm')?>')">
		<i class="icon-fire icon-white"></i> <?=lang('logs_flushall_button')?></button>
</form>