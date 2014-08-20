<div class="white">
	<h1><?=lang('emailad_header_title')?></h1>
	<p><?=lang('emailad_intro_text')?></p>
	
	<form method="post" class="form-horizontal">
		
		<?php if(!User::is_connected()): ?>
			<?php $fn = 'name'; ?>
			<div class="control-group <?php form_error($fn) and print('error'); ?>">
				<label class="control-label" for="<?=$fn?>"><strong><?=lang('emailad_'.$fn.'_prompt')?></strong></label>
				<div class="controls"><input type="text" name="<?=$fn?>" id="<?=$fn?>" value="<?=set_value($fn)?>" class="input-xlarge" />
				<span class="help-inline"><?=form_error($fn)?></span></div>
			</div>
			
			<?php $fn = 'email'; ?>
			<div class="control-group <?php form_error($fn) and print('error'); ?>">
				<label class="control-label" for="<?=$fn?>"><strong><?=lang('emailad_'.$fn.'_prompt')?></strong></label>
				<div class="controls"><input type="email" name="<?=$fn?>" id="<?=$fn?>" value="<?=set_value($fn)?>" class="input-xlarge" />
				<span class="help-inline"><?=form_error($fn)?></span></div>
			</div>
		<?php endif; ?>
		
		<?php $fn = 'message'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('emailad_'.$fn.'_prompt')?></strong></label>
			<div class="controls"><textarea name="<?=$fn?>" id="<?=$fn?>" class="span7 autosize" rows="6"><?=set_value($fn)?></textarea>
			<span class="help-inline"><?=form_error($fn)?></span></div>
		</div>
		
		<?php if(!User::is_connected()): ?>
			<?php $fn = 'captcha'; ?>
			<div class="control-group <?php form_error($fn) and print('error'); ?>">
				<label for="<?=$fn?>" class="control-label"><strong><?=lang('emailad_'.$fn.'_prompt')?></strong></label>
				<div class="controls"><?=$captcha?> <input type="text" class="input-medium" id="<?=$fn?>" name="<?=$fn?>" />
				<span class="help-inline"><?=form_error($fn)?></span>
				<br /><?=lang('emailad_'.$fn.'_helper')?></div>
			</div>
		<?php endif; ?>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?=lang('emailad_send_button')?></button> <a href="<?=base_url('/books/ad/'.$A->AdID)?>" class="btn"><?=lang('emailad_cancel_button')?></a>
		</div>
	
	</form>
</div>