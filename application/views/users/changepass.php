<div class="white">
	<h1><?=lang('changepass_header_title')?></h1>
	
	<form method="post" class="form-horizontal">
	
		<div class="control-group <?php form_error('newpass') and print('error'); ?>">
			<label for="newpass" class="control-label"><strong><?=lang('changepass_newpass_prompt')?></strong></label>
			<div class="controls"><input type="password" value="" class="input-xlarge" name="newpass" id="newpass" />
			<span class="help-inline"><?=form_error('newpass')?></span></div>
		</div>
	
		<div class="control-group <?php form_error('confpass') and print('error'); ?>">
			<label for="confpass" class="control-label"><strong><?=lang('changepass_confpass_prompt')?></strong></label>
			<div class="controls"><input type="password" value="" class="input-xlarge" name="confpass" id="confpass" />
			<span class="help-inline"><?=form_error('confpass')?></span></div>
		</div>
		
		<?php if(User::id($P->UserID)): ?>
			<div class="control-group <?php form_error('oldpass') and print('error'); ?>">
				<label for="oldpass" class="control-label"><strong><?=lang('changepass_oldpass_prompt')?></strong></label>
				<div class="controls"><input type="password" value="" class="input-xlarge" name="oldpass" id="oldpass" />
				</div>
			<span class="help-inline"><?=form_error('oldpass')?></span></div>
		<?php endif; ?>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?=lang('changepass_save_button')?></button> <a href="<?=base_url('/users/'.$P->Username)?>" class="btn"><?=lang('changepass_cancel_button')?></a>
		</div>

		<?=csrf_token_input()?>
	</form>
</div>