<div class="white">
	<h1><?=lang('changepic_header_title')?></h1>
	
	<form method="post" enctype="multipart/form-data" class="form-horizontal">
	
		<div class="control-group">
			<div class="control-label"><strong><?=lang('changepic_currentpic_label')?></strong></div>
			<div class="controls"><img src="<?=get_avatar($P->Picture)?>?>" alt="" class="img-polaroid" /></div>
		</div>
		
		<div class="control-group">
			<div class="control-label"><strong><?=lang('changepic_action_prompt')?></strong></div>
			<div class="controls">
				<label for="pic-replace" class="radio"><input type="radio" name="pic-action" value="pic-replace" id="pic-replace" /> <?=lang('changepic_picreplace_radio')?> <input type="file" name="new-pic" />
				<br /><em><?=lang('changepic_uploadrules_help')?></em></label>
				<label for="pic-delete" class="radio"><input type="radio" name="pic-action" value="pic-delete" id="pic-delete"<?php get_avatar($P->Picture, 'original', false) or print(' disabled="disabled"') ?> /> <?=lang('changepic_picdelete_radio')?></label>
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?=lang('changepic_save_button')?></button> <a href="<?=base_url('/users/'.$P->Username)?>" class="btn"><?=lang('changepic_cancel_button')?></a>
		</div>

		<?=csrf_token_input()?>
	</form>
</div>