<div class="white">
	<h1><?=lang('changemail_header_title')?></h1>
	
	<form method="post" class="form-horizontal">
	
		<div class="control-group <?php form_error('newmail') and print('error'); ?>">
			<label for="newmail" class="control-label"><strong><?=lang('changemail_newmail_prompt')?></strong></label>
			<div class="controls"><input type="email" value="<?=set_value('newmail')?>" class="input-xlarge" name="newmail" id="newmail" />
			<span class="help-inline"><?=form_error('newmail')?></span></div>
		</div>
		
		<?php if(User::id($P->UserID)): ?>
			<div class="control-group <?php form_error('password') and print('error'); ?>">
				<label for="password" class="control-label"><strong><?=lang('changemail_password_prompt')?></strong></label>
				<div class="controls"><input type="password" value="" class="input-xlarge" name="password" id="password" />
				<span class="help-inline"><?=form_error('password')?></span></div>
			</div>
		<?php endif; ?>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?=lang('changemail_save_button')?></button> <a href="<?=base_url('/users/'.$P->Username)?>" class="btn"><?=lang('changemail_cancel_button')?></a>
		</div>

		<?=csrf_token_input()?>
	</form>
</div>