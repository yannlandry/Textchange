<div class="white">
	<h1><?=lang('resend_header_title')?></h1>
	<p><?=lang('resend_intro_text')?></p>
	<p class="alert alert-info"><?=lang('resend_alreadysent_alert')?></p>
	
	<form method="post" class="form-horizontal">
		<div class="control-group <?php form_error('accEmail') and print('error'); ?>">
			<label for="accEmail" class="control-label"><strong><?=lang('resend_email_prompt')?></strong></label>
			<div class="controls"><input type="email" value="<?=set_value('accEmail')?>" class="input-xlarge" id="accEmail" name="accEmail" />
			<span class="help-inline"><?=form_error('accEmail')?></span></div>
		</div>
		<div class="control-group <?php form_error('chgEmail') and print('error'); ?>">
			<label for="chgEmail" class="control-label"><strong><?=lang('resend_newemail_prompt')?></strong></label>
			<div class="controls"><input type="email" value="<?=set_value('chgEmail')?>" class="input-xlarge" id="chgEmail" name="chgEmail" />
			<span class="help-inline"><?=form_error('chgEmail')?></span>
			<br /><?=lang('resend_newemail_help')?></div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?=lang('resend_send_button')?></button>
		</div>
	</form>
</div>