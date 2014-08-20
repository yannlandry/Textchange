<div class="white">
	<h1><?=$headerTitle?></h1>
	<p><?=$reportIntro?></p>
	
	<form method="post" class="form-horizontal">
	
		<?php $fn = 'reason'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=$reasonPrompt?></strong></label>
			<div class="controls"><textarea name="<?=$fn?>" id="<?=$fn?>" class="span6" rows="6"><?=set_value($fn)?></textarea>
			<span class="help-inline"><?=form_error($fn)?></span></div>
		</div>
		
		<?php if(!User::is_connected()): ?>
			<div class="control-group <?php form_error('captcha') and print('error'); ?>">
				<label for="captcha" class="control-label"><strong><?=$captchaPrompt?></strong></label>
				<div class="controls"><?=$captcha?> <input type="text" class="input-medium" id="captcha" name="captcha" />
				<span class="help-inline"><?=form_error('captcha')?></span>
				<br /><?=$captchaHelper?></div>
			</div>
		<?php endif; ?>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?=$reportButton?></button> <a href="<?=$cancelUrl?>" class="btn"><?=$cancelButton?></a>
		</div>
		
	</form>
</div>