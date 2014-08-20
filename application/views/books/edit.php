<?php include('application/include/isbn-modal.php'); ?>

<div class="white">
	<h1><?=$headerTitle?></h1>

	<form method="post" class="form-horizontal" enctype="multipart/form-data">
	
		<?php $fn = 'title'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('editad_'.$fn.'_prompt')?></strong></label>
			<div class="controls"><input type="text" name="<?=$fn?>" id="<?=$fn?>" value="<?=set_value($fn, $prf[$fn])?>" class="input-xlarge" />
			<span class="help-inline"><?=form_error($fn)?></span></div>
		</div>
		
		<?php $fn = 'isbn'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('editad_'.$fn.'_prompt')?></strong></label>
			<div class="controls"><input type="text" name="<?=$fn?>" id="<?=$fn?>" value="<?=set_value($fn, $prf[$fn])?>" class="input-xlarge" />
			<span class="help-inline"><?=form_error($fn)?></span>
			<br /><a href="#ISBNModal" data-toggle="modal" data-target="#ISBNModal"><?=lang('editad_isbnmodal_link')?></a></div>
		</div>
		
		<?php $fn = 'authors'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('editad_'.$fn.'_prompt')?></strong></label>
			<div class="controls"><input type="text" name="<?=$fn?>" id="<?=$fn?>" value="<?=set_value($fn, $prf[$fn])?>" class="input-xlarge" />
			<span class="help-inline"><?=form_error($fn)?></span></div>
		</div>
		
		<?php $fn = 'publisher'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('editad_'.$fn.'_prompt')?></strong></label>
			<div class="controls"><input type="text" name="<?=$fn?>" id="<?=$fn?>" value="<?=set_value($fn, $prf[$fn])?>" class="input-xlarge" />
			<span class="help-inline"><?=form_error($fn)?></span></div>
		</div>
		
		<?php $fn = 'pubyear'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<div class="control-label"><strong><?=lang('editad_'.$fn.'_prompt')?></strong></div>
			<div class="controls">
				<select name="<?=$fn?>" id="<?=$fn?>" size="6">
					<option value="" <?=set_select($fn, 0, 0 == $prf[$fn])?>></option>
					<?php for($i = date('Y'); $i >= 1950; --$i): ?>
						<option value="<?=$i?>" <?=set_select($fn, $i, $prf[$fn] == $i)?>><?=$i?></option>
					<?php endfor; ?>
				</select>
				<span class="help-inline"><?=form_error($fn)?></span>
			</div>
		</div>
		
		<?php $fn = 'domain'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<div class="control-label"><strong><?=lang('editad_'.$fn.'_prompt')?></strong></div>
			<div class="controls">
				<select name="<?=$fn?>" id="<?=$fn?>" size="6">
					<?php foreach($domains as $dID => $dName): ?>
						<option value="<?=$dID?>" <?=set_select($fn, $dID, $dID == $prf[$fn])?>><?=$dName?></option>
					<?php endforeach; ?>
				</select>
				<span class="help-inline"><?=form_error($fn)?></span>
			</div>
		</div>
		
		<?php $fn = 'lang'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<div class="control-label"><strong><?=lang('editad_'.$fn.'_prompt')?></strong></div>
			<div class="controls">
				<select name="<?=$fn?>" id="<?=$fn?>">
					<?php foreach(array('french', 'english', 'other') as $lang): ?>
						<option value="<?=$lang?>" <?=set_select($fn, $lang, $lang == $prf[$fn])?>><?=lang('lang_'.$lang)?></option>
					<?php endforeach; ?>
				</select>
				<span class="help-inline"><?=form_error($fn)?></span>
			</div>
		</div>
		
		<?php $fn = 'information'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('editad_'.$fn.'_prompt')?></strong></label>
			<div class="controls"><textarea name="<?=$fn?>" id="<?=$fn?>" class="span6 autosize" rows="4"><?=set_value($fn, $prf[$fn])?></textarea>
			<span class="help-inline"><?=form_error($fn)?></span></div>
		</div>
		
		<?php $fn = 'picture'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<div class="control-label"><strong><?=lang('editad_'.$fn.'_prompt')?></strong></div>
			<div class="controls">
				<?php $pic = get_book_picture($prf[$fn], 'display', false);
				if(!empty($pic)): ?>
					<p><img src="<?=$pic?>" alt="" class="img-polaroid" /></p>
					<label for="keep-picture" class="radio">
						<input type="radio" name="picture-actions" id="keep-picture" value="keep" checked="checked" />
						<?=lang('editad_keepimage_label')?>
					</label>
					<label for="delete-picture" class="radio">
						<input type="radio" name="picture-actions" id="delete-picture" value="delete" />
						<?=lang('editad_deleteimage_label')?>
					</label>
				<?php else: ?>
					<label for="no-image" class="radio">
						<input type="radio" name="picture-actions" id="no-image" value="keep" checked="checked" />
						<?=lang('editad_noimage_label')?>
					</label>
				<?php endif; ?>
				<label for="use-image" class="radio">
					<input type="radio" name="picture-actions" id="use-image" value="use" />
					<?=lang('editad_useimage_label')?><br />
					<input type="file" name="new-picture" id="<?=$fn?>" />
				</label>
			</div>
		</div>
		
		<?php $fn = 'price'; ?>
		<div class="control-group <?php form_error($fn) and print('error'); ?>">
			<label class="control-label" for="<?=$fn?>"><strong><?=lang('editad_'.$fn.'_prompt')?></strong></label>
			<div class="controls">
				<div class="input-prepend"><span class="add-on">$</span><input type="text" name="<?=$fn?>" id="<?=$fn?>" value="<?=set_value($fn, output_price($prf[$fn], false))?>" class="input-small" /></div><span class="help-inline"><?=form_error($fn)?></span>
			</div>
		</div>
		
		<div class="form-actions">
			<?=csrf_token_input()?>
			<button type="submit" class="btn btn-primary"><?=lang('editad_save_button')?></button> <a href="<?=$cancelReturnLink?>" class="btn"><?=lang('editad_cancel_button')?></a>
		</div>
	
	</form>

</div>