<div class="white">
	<h1><?=lang('signup_header_title')?></h1>
	<p><?=lang('signup_intro_text')?></p>
	<p class="alert alert-info"><?=lang('signup_allrequired_alert')?></p>
	
	<form method="post" class="form-horizontal">
		<div class="control-group <?php form_error('subsUsername') and print('error'); ?>">
			<label for="subsUsername" class="control-label"><strong><?=lang('signup_username_prompt')?></strong></label>
			<div class="controls"><input type="text" value="<?=set_value('subsUsername')?>" class="input-xlarge" id="subsUsername" name="subsUsername" />
			<span class="help-inline"><?=form_error('subsUsername')?></span>
			<br /><?=lang('signup_username_helper')?></div>
		</div>
		<div class="control-group <?php form_error('subsEmail') and print('error'); ?>">
			<label for="subsEmail" class="control-label"><strong><?=lang('signup_email_prompt')?></strong></label>
			<div class="controls"><input type="email" value="<?=set_value('subsEmail')?>" class="input-xlarge" id="subsEmail" name="subsEmail" />
			<span class="help-inline"><?=form_error('subsEmail')?></span>
			<br /><?=lang('signup_email_helper_p1')?> <a href="<?=base_url('/schools')?>"><?=lang('signup_email_helper_p2')?></a><?=lang('signup_email_helper_p3')?></div>
		</div>
		<div class="control-group <?php form_error('subsPass') and print('error'); ?>">
			<label for="subsPass" class="control-label"><strong><?=lang('signup_password_prompt')?></strong></label>
			<div class="controls"><input type="password" value="" class="input-xlarge" id="subsPass" name="subsPass" />
			<span class="help-inline"><?=form_error('subsPass')?></span>
			<br /><?=lang('signup_password_helper')?></div>
		</div>
		<div class="control-group <?php form_error('subsPassConf') and print('error'); ?>">
			<label for="subsPassConf" class="control-label"><strong><?=lang('signup_passwordconf_prompt')?></strong></label>
			<div class="controls"><input type="password" value="" class="input-xlarge" id="subsPassConf" name="subsPassConf" />
			<span class="help-inline"><?=form_error('subsPassConf')?></span>
			<br /><?=lang('signup_passwordconf_helper')?></div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?=lang('signup_signup_button')?></button> <button type="reset" class="btn"><?=lang('signup_reset_button')?></button>
		</div>
	</form>
</div>