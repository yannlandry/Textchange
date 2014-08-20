<div class="white block top">
	<h1><?=lang('listusers_header_title')?></h1>
	<p><?php $tomsg and print(lang('listusers_sendmessage_intro')); ?></p>
</div>

<div class="gray-head block">
	<div class="row">
		<div class="pull-right">
			<form method="get" class="form-inline input-append">
				<?=pass_get_vars()?>
				<input type="search" value="<?=htmlspecialchars($Q)?>" placeholder="<?=lang('listusers_search_placeholder')?>" name="q" class="input-xlarge" />
				<button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i></button>
			</form>
			<?php if(!empty($_GET['q'])): ?><br /><small><a href="<?=append_get_vars(base_url('/users'), array(), array('q'));?>" class="smaller"><?=lang('listusers_search_cancel')?></a></small><?php endif; ?>
		</div>
		<div class="span7"><?=$resintro.' <strong>'.$rescount.'</strong>'?></div>
	</div>
</div>

<?php foreach($results as $P): ?>
	<div class="white block">
		<div class="row">
			<div class="span1"><img src="<?=get_avatar($P->Picture, 'icon')?>" alt="<?=$P->Username?>" /></div>
			<div class="span11">
				<p><strong><a href="<?=base_url('/'.($tomsg?'messages':'users').'/'.$P->Username.($tomsg?'#newmsg':''))?>"><?=coalesce($P->RealName, $P->Username)?></a> <?=badge_of($P->Rights)?></strong>
				<br /><?=$P->SchoolName?></p>
			</div>
		</div>
	</div>
<?php endforeach; ?>

<div class="gray-head block bottom pagination">
	<?=$pagination?>
</div>