<h4><?=lang('domains_header_title')?></h4>

<table class="table table-striped">
	<tr>
		<th class="wide-cell">
			<div class="row">
				<div class="span4"><?=lang('domains_frenchname_th')?></div>
				<div class="span4"><?=lang('domains_englishname_th')?></div>
			</div>
		</th>
	</tr>
	
	<?php $domains[] = (object)array(
		'FrenchName' => '',
		'EnglishName' => '',
		'DomainID' => '0'
	);
	foreach($domains as $D): ?>
		<tr>
			<td class="wide-cell">
				<form method="post" class="form-inline no-margin">
					<div class="row">
						<div class="span4"><input type="text" name="french-name" class="input-xlarge" value="<?=$D->FrenchName?>" placeholder="<?=lang('domains_new_placeholder')?>" /></div>
						<div class="span4"><input type="text" name="english-name" class="input-xlarge" value="<?=$D->EnglishName?>" placeholder="<?=lang('domains_new_placeholder')?>" /></div>
						<div class="span1">
							<input type="hidden" name="domain-id" value="<?=$D->DomainID?>">
							<?=csrf_token_input()?>
							<button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i></button>
						</div>
					</div>
				</form>
			</td>
		</tr>
	<?php endforeach; ?>
	
</table>

<ul class="pagination text-center">
	<?=$pagination?>
</ul>