<form method="post">
	<h4><?=lang('adsreports_header_title')?></h4>
	
	<table class="table">
		<tr>
			<th><?=lang('adsreports_titlereason_th')?></th>
			<th><?=lang('adsreports_reportedby_th')?></th>
			<th><?=lang('adsreports_date_th')?></th>
			<th></th>
		</tr>
		<?php if(empty($reports)): ?>
			</table>
			<p class="no-result"><?=lang('adsreports_noresult')?></p>
		<?php else:
			foreach($reports as $R): ?>
				<tr class="<?=$R->IsNew == 1 ? 'error' : 'success'?>">
					<td>
						<strong><a href="<?=base_url('/books/ad/'.$R->AdID)?>"><?=$R->Title?></a></strong><br />
						<small><?=$R->Reason?></small>
					</td>
					<td>
						<?php if(empty($R->ByUserID)): ?>
							<?=lang('adsreports_anonymous')?>
						<?php else: ?>
							<a href="<?=base_url('/users/'.$R->Username)?>"><?=coalesce($R->RealName, $R->Username)?></a>
						<?php endif; ?>
					</td>
					<td><?=csdate($R->Date)?></td>
					<td class="text-right"><input type="checkbox" name="sel-reports[]" value="<?=$R->AdReportID?>" /></td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</table>


	<ul class="pagination text-center">
		<?=$pagination?>
	</ul>
	
	<hr />
	
	<p class="pull-right">
		<?=lang('adsreports_forsel')?>
		<?=csrf_token_input()?>
		<button type="submit" name="mark-ads" class="btn btn-success"><i class="icon-ok icon-white"></i> <?=lang('adsreports_mark_button')?></button>
		<button type="submit" name="unmark-ads" class="btn btn-danger"><i class="icon-remove icon-white"></i> <?=lang('adsreports_unmark_button')?></button>
	</p>
</form>