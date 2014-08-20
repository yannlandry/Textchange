<div class="white">
	<h1><?=lang('contact_header_title')?></h1>
	<p><?=lang('contact_intro_text')?><br />
	<strong><?=lang('contact_reqfiels_text')?></strong></p>
	
	<form method="post" class="form-horizontal">
		<div class="control-group <?php form_error('name') and print('error'); ?>">
			<label for="name" class="control-label"><strong><?=lang('contact_name_prompt')?></strong></label>
			<div class="controls"><input type="text" value="<?=set_value('name')?>" class="input-xlarge" id="name" name="name" />
			<span class="help-inline"><?=form_error('name')?></span></div>
		</div>
		<div class="control-group <?php form_error('email') and print('error'); ?>">
			<label for="email" class="control-label"><strong><?=lang('contact_email_prompt')?></strong></label>
			<div class="controls"><input type="email" value="<?=set_value('email')?>" class="input-xlarge" id="email" name="email" />
			<span class="help-inline"><?=form_error('email')?></span>
			<br /><?=lang('contact_email_helper')?></div>
		</div>
		<div class="control-group <?php form_error('subject') and print('error'); ?>">
			<label for="subject" class="control-label"><strong>*<?=lang('contact_subject_prompt')?></strong></label>
			<div class="controls"><input type="text" value="<?=set_value('subject')?>" class="input-xlarge" id="subject" name="subject" />
			<span class="help-inline"><?=form_error('subject')?></span></div>
		</div>
		<div class="control-group <?php form_error('message') and print('error'); ?>">
			<label for="message" class="control-label"><strong>*<?=lang('contact_message_prompt')?></strong></label>
			<div class="controls"><textarea id="message" name="message" class="span6 autosize" rows="6"><?=set_value('message')?></textarea>
			<span class="help-inline"><?=form_error('message')?></span></div>
		</div>
		<?php if(!User::is_connected()): ?>
			<div class="control-group <?php form_error('captcha') and print('error'); ?>">
				<label for="captcha" class="control-label"><strong>*<?=lang('contact_captcha_prompt')?></strong></label>
				<div class="controls"><?=$captcha?> <input type="text" class="input-medium" id="captcha" name="captcha" />
				<span class="help-inline"><?=form_error('captcha')?></span>
				<br /><?=lang('contact_captcha_helper')?></div>
			</div>
		<?php endif; ?>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?=lang('contact_send_button')?></button> <button type="reset" class="btn"><?=lang('contact_reset_button')?></button>
		</div>
	</form>
</div>