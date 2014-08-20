<div class="white">
	<h1><?=$pageTitle?></h1>

	<form method="post" class="form-horizontal" enctype="multipart/form-data">
	
		<?php $fn = 'frenchname'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('editschool_'.$fn.'_prompt')?></strong></label>
			<div class="controls"><input type="text" name="<?=$fn?>" id="<?=$fn?>" value="<?=set_value($fn, $prf[$fn])?>" class="input-xlarge" />
			<span class="help-inline"><?=form_error($fn)?></span></div>
		</div>
		
		<?php $fn = 'englishname'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('editschool_'.$fn.'_prompt')?></strong></label>
			<div class="controls"><input type="text" name="<?=$fn?>" id="<?=$fn?>" value="<?=set_value($fn, $prf[$fn])?>" class="input-xlarge" />
			<span class="help-inline"><?=form_error($fn)?></span></div>
		</div>
		
		<?php $fn = 'town'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('editschool_'.$fn.'_prompt')?></strong></label>
			<div class="controls"><input type="text" name="<?=$fn?>" id="<?=$fn?>" value="<?=set_value($fn, $prf[$fn])?>" class="input-xlarge" />
			<span class="help-inline"><?=form_error($fn)?></span></div>
		</div>
		
		<?php $fn = 'province'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('editschool_'.$fn.'_prompt')?></strong></label>
			<div class="controls">
				<select name="province" id="<?=$fn?>">
					<?php foreach($provinces as $prv): ?>
						<option value="<?=$prv?>" <?=set_select($fn, $prv, $prv == $prf[$fn])?>><?=province($prv)?></option>
					<?php endforeach; ?>
				</select>
				<span class="help-inline"><?=form_error($fn)?></span>
			</div>
		</div>
		
		<?php $fn = 'emailsuffix'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('editschool_'.$fn.'_prompt')?></strong></label>
			<div class="controls"><input type="text" name="<?=$fn?>" id="<?=$fn?>" value="<?=set_value($fn, $prf[$fn])?>" class="input-xlarge" />
			<span class="help-inline"><?=form_error($fn)?></span></div>
		</div>
		
		<?php $fn = 'picture'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('editschool_'.$fn.'_prompt')?></strong></label>
			<div class="controls">
				<?php if(!empty($prf[$fn])): ?>
					<img src="<?=UPLOADS_ROOT?>/schools/<?=$prf[$fn]?>" alt="" class="pull-left" />
				<?php endif; ?>
				<input type="file" name="<?=$fn?>" id="<?=$fn?>" />
				<span class="help-inline"><?=form_error($fn)?></span>
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?=lang('editschool_save_button')?></button> <a href="<?=base_url('/schools')?>" class="btn"><?=lang('editschool_cancel_button')?></a>
		</div>
	
	<?=csrf_token_input()?>
	</form>


</div>