<div class="white tiny">
	<h1><?=lang('login_header_title')?></h1>
	
	<form method="post">
			
		<p><?=lang('login_email_prompt')?><br />
		<input type="text" name="log_email" autofocus="autofocus" class="input-block-level" /></p>
		
		<p><?=lang('login_password_prompt')?><br />
		<input type="password" name="log_password" class="input-block-level" /></p>
		
		<p><label for="keep_me_in" class="checkbox"><input type="checkbox" name="keep_me_in" id="keep_me_in" /> <?=lang('login_keepmein_check')?></label></p>
		
		<p><button type="submit" class="btn btn-primary"><?=lang('login_login_button')?></button></p>
		
		<p><a href="<?=BASE_URL?>/login/recover"><?=lang('login_lostmypassword_link')?></a><br />
		<a href="<?=BASE_URL?>/signup"><?=lang('login_signup_link')?></a><br />
		<a href="<?=BASE_URL?>/activate/resend"><?=lang('login_activate_link')?></a></p>
		
	</form>
</div>