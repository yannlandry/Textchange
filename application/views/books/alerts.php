<div class="white block top">
	<h1><?=lang('alerts_header_title')?></h1>
	<p><?=lang('alerts_intro_text')?></p>
</div>

<form class="no-margin" method="post">
	<div class="gray-head block">
		<div class="row">
			<div class="pull-right">
				<button type="submit" name="unwatch-button" class="btn btn-danger"><i class="icon icon-remove icon-white"></i> <?=lang('alerts_delete_button')?></button>
			</div>
		</div>
	</div>
	
	<div class="white block">
		<table class="table table-striped">
			<tr>
				<th><?=lang('alerts_booktitle_th')?></th>
				<th><?=lang('alerts_isbn_th')?></th>
				<th></th>
			</tr>
		
			<?php foreach($alerts as $A): ?>
				<tr>
					<td><?=$A->BookTitle?></td>
					<td><a href="<?=base_url('/books/isbn/'.$A->ISBN)?>"><?=$A->ISBN?></a></td>
					<td><label class="checkbox" for="unwatch-isbn-<?=$A->ISBN?>"><input type="checkbox" name="unwatch[]" id="unwatch-isbn-<?=$A->ISBN?>" value="<?=$A->ISBN?>" /></label></td>
				</tr>
			<?php endforeach; ?>
			
			<tr>
				<td><input type="text" name="watch-title" class="span5" placeholder="<?=lang('alerts_booktitle_placeholder')?>" /></td>
				<td><input type="text" name="watch-isbn" class="span5" placeholder="<?=lang('alerts_isbn_placeholder')?>" /></td>
				<td><button type="submit" name="watch-button" class="btn btn-primary"><i class="icon icon-ok icon-white"></i></button></td>
			</tr>
		</table>
	</div>
	
	<div class="gray-head block bottom pagination">
		<div class="row">
			<div class="pull-right">
				<button type="submit" name="unwatch-button" class="btn btn-danger"><i class="icon icon-remove icon-white"></i> <?=lang('alerts_delete_button')?></button>
			</div>
			<div class="span7"><?=$pagination?></div>
		</div>
	</div>
</form>