<div class="btn-group">
	<button class="btn dropdown-toggle" data-toggle="dropdown">
		<i class="icon-globe"></i> <?=lang('books_sellang_'.$searchLang)?> <span class="caret"></span></button>
	<ul class="dropdown-menu">
		<?php foreach(array('all', 'french', 'english', 'other') as $BL): ?>
			<?php $itemURL = append_get_vars($baseURL, array('srclang' => $BL), array('page')); ?>
			<?php if($searchLang == $BL): ?>
				<li class="active"><a href="<?=$itemURL?>"><i class="icon-ok"></i>
			<?php else: ?>
				<li><a href="<?=$itemURL?>"><i class="icon-empty"></i>
			<?php endif; ?>
				<?=lang('books_sellang_'.$BL)?></a></li>
		<?php endforeach; ?>
	</ul>
</div>