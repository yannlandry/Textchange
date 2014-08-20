<div class="white">
	<h1><?=lang('editprofile_header_title')?></h1>
	
	<form method="post" class="form-horizontal">
	
		<div class="control-group">
			<div class="control-label"><strong><?=lang('editprofile_username_prompt')?></strong></div>
			<div class="controls"><input type="text" value="<?=$P->Username?>" class="input-xlarge" disabled="disabled" /></div>
		</div>
	
		<div class="control-group">
			<div class="control-label"><strong><?=lang('editprofile_password_prompt')?></strong></div>
			<div class="controls"><input type="password" value="********" class="input-xlarge" disabled="disabled" />
			<a href="<?=base_url('/users/'.$P->Username.'/edit/password')?>" class="btn"><i class="icon-pencil"></i> <?=lang('editprofile_change_button')?></a></div>
		</div>
	
		<div class="control-group">
			<div class="control-label"><strong><?=lang('editprofile_email_prompt')?></strong></div>
			<div class="controls"><input type="email" value="<?=$P->Email?>" class="input-xlarge" disabled="disabled" />
			<a href="<?=base_url('/users/'.$P->Username.'/edit/email')?>" class="btn"><i class="icon-pencil"></i> <?=lang('editprofile_change_button')?></a>
			<br /><label for="displayemail" class="checkbox"><input type="checkbox" value="true" name="displayemail" id="displayemail" <?=set_checkbox('displayemail', 'on', $P->DisplayEmail == '1')?> /> <?=lang('editprofile_displayemail_prompt')?></label></div>
		</div>
	
		<div class="control-group <?php form_error('realname') and print('error'); ?>">
			<label for="realname" class="control-label"><strong><?=lang('editprofile_realname_prompt')?></strong></label>
			<div class="controls"><input type="text" value="<?=set_value('realname', $P->RealName)?>" class="input-xlarge" id="realname" name="realname" />
			<span class="help-inline"><?=form_error('realname')?></span></div>
		</div>
	
		<div class="control-group <?php form_error('school') and print('error'); ?>">
			<label for="school" class="control-label"><strong><?=lang('editprofile_school_prompt')?></strong></label>
			<div class="controls">
				<select name="school" id="school" style="width:auto">
					<option value="0" <?=set_select('school', 0, $P->SchoolID == 0)?>><?=lang('editprofile_noschool_option')?></option>
					<?php foreach($Schools as $ID => $Name): ?>
						<option value="<?=$ID?>" <?=set_select('school', $ID, $P->SchoolID == $ID)?>><?=$Name?></option>
					<?php endforeach; ?>
				</select>
				<span class="help-inline"><?=form_error('school')?></span>
			</div>
		</div>
	
		<div class="control-group <?php form_error('phone') and print('error'); ?>">
			<label for="phone_area" class="control-label"><strong><?=lang('editprofile_phone_prompt')?></strong></label>
			<div class="controls"><input type="text" value="<?=set_value('phone_area', $P->PhoneNumber['area'])?>" size="3" style="width:auto;" maxlength="3" id="phone_area" name="phone_area" />
			<input type="text" value="<?=set_value('phone_prefix', $P->PhoneNumber['prefix'])?>" size="3" style="width:auto;" maxlength="3" id="phone_prefix" name="phone_prefix" />
			<input type="text" value="<?=set_value('phone_suffix', $P->PhoneNumber['suffix'])?>" size="4" style="width:auto;" maxlength="4" id="phone_suffix" name="phone_suffix" />
			<span class="help-inline"><?=form_error('phone')?></span></div>
		</div>
	
		<div class="control-group <?php form_error('information') and print('error'); ?>">
			<label for="information" class="control-label"><strong><?=lang('editprofile_information_prompt')?></strong></label>
			<div class="controls"><textarea class="span6 autosize" rows="4" id="information" name="information" /><?=set_value('information', $P->Information)?></textarea>
			<span class="help-inline"><?=form_error('information')?></span></div>
		</div>
	
		<div class="control-group">
			<label class="control-label"><strong><?=lang('editprofile_avatar_prompt')?></strong></label>
			<div class="controls"><p><img src="<?=get_avatar($P->Picture)?>" class="img-polaroid" alt="" /></p>
			<p><a href="<?=base_url('/users/'.$P->Username.'/edit/avatar')?>" class="btn"><i class="icon-pencil"></i> <?=lang('editprofile_change_button')?></a></p></div>
		</div>
		
		<h3><?=lang('editprofile_preferences_section')?></h3>
		
		<div class="control-group">
			<label class="control-label"><strong><?=lang('editprofile_newpm_prompt')?></strong></label>
			<div class="controls"><label for="newpm-yes" class="radio"><input type="radio" name="newpm" id="newpm-yes" value="true" <?=set_radio('newpm', 'true', $P->NotifyPM == '1')?> /><?=lang('editprofile_newpm_yes')?></label>
			<label for="newpm-no" class="radio"><input type="radio" name="newpm" id="newpm-no" value="false" <?=set_radio('newpm', 'false', $P->NotifyPM == '0')?> /><?=lang('editprofile_newpm_no')?></label></div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><strong><?=lang('editprofile_unreg_prompt')?></strong></label>
			<div class="controls"><label for="unreg-yes" class="radio"><input type="radio" name="unreg" id="unreg-yes" value="true" <?=set_radio('unreg', 'true', $P->UnregContact == '1')?> /><?=lang('editprofile_unreg_yes')?></label>
			<label for="unreg-no" class="radio"><input type="radio" name="unreg" id="unreg-no" value="false" <?=set_radio('unreg', 'false', $P->UnregContact == '0')?> /><?=lang('editprofile_unreg_no')?></label></div>
		</div>
		
		<div class="control-group">
			<label for="language" class="control-label"><strong><?=lang('editprofile_language_prompt')?></strong></label>
			<div class="controls">
				<select name="language" id="language" style="width:auto">
					<option value="french" <?=set_select('language', 'french', $P->Language == 'french')?>><?=lang('lang_french')?></option>
					<option value="english" <?=set_select('language', 'english', $P->Language == 'english')?>><?=lang('lang_english')?></option>
				</select>
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?=lang('editprofile_save_button')?></button> <a href="<?=base_url('/users/'.$P->Username)?>" class="btn"><?=lang('editprofile_cancel_button')?></a>
		</div>

		<?=csrf_token_input()?>
	</form>
</div>
