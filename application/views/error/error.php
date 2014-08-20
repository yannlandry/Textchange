<div class="container error_wrapper">
	<h1><?=lang('error')?> <?=$code?></h1>
	<p class="error_description"><?=lang('error_'.$code.'_message')?></p>
	<p><a href="<?=base_url()?>" class="btn btn-danger"><?=lang('error_home_link')?></a></p>
</div>