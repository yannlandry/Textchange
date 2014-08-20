<div class="white">
	<h1><?=lang('recover_header_title')?></h1>
	<p><?=lang('recover_intro_text')?></p>
	
	<form method="post" class="form-horizontal">
		<div class="control-group <?php form_error('accEmail') and print('error'); ?>">
			<label for="accEmail" class="control-label"><strong><?=lang('recover_email_prompt')?></strong></label>
			<div class="controls"><input type="email" value="<?=set_value('accEmail')?>" class="input-xlarge" id="accEmail" name="accEmail" />
			<span class="help-inline"><?=form_error('accEmail')?></span></div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?=lang('recover_send_button')?></button>
		</div>
	</form>
</div>