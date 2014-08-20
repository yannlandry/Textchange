<div class="white block top">
	<h1><?=lang('listschools_header_title')?></h1>
	<p><?=lang('listschools_intro_text_p1')?> <a href="<?=base_url('/contact')?>"><?=lang('listschools_intro_text_p2')?></a><?=lang('listschools_intro_text_p3')?></p>
	<p><strong><?=$rescount?> <?=lang('listschools_rescount_suffix')?></strong></p>
	<?php if(User::is_admin()): ?>
		<p><a href="<?=base_url('/schools/add')?>" class="btn"><i class="icon-plus"></i> <?=lang('listschools_addschool_button')?></a></p>
	<?php endif; ?>
</div>

<?php foreach($results as $S): ?>
	<div class="white block">
		<div class="row">
			<div class="span1"><img src="<?=UPLOADS_ROOT?>/schools/<?=$S->Picture?>" alt="" /></div>
			<div class="span9">
				<p><strong><?=$S->SchoolName?></strong>
				<br /><?=$S->Town?>, <?=province($S->Province)?></p>
			</div>
			<?php if(User::is_admin()): ?>
				<div class="span2"><a href="<?=base_url('/schools/'.$S->SchoolID)?>" class="btn pull-right"><i class="icon-edit"></i> <?=lang('listschools_editschool_button')?></a></div>
			<?php endif; ?>
		</div>
	</div>
<?php endforeach; ?>

<div class="white block bottom pagination">
	<?=$pagination?>
</div>