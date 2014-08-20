<?php $activeSort = $searchSort.'_'.($searchOrder == 'asc' ? 'lohi' : 'hilo'); ?>

<div class="btn-group">
	<button class="btn dropdown-toggle" data-toggle="dropdown">
		<i class="icon-list"></i> <?=lang('books_'.$activeSort)?> <span class="caret"></span></button>
	<ul class="dropdown-menu">
		<?php foreach(array(	'price_lohi' => array('srcsort' => 'price', 'srcorder' => 'asc'),
								'price_hilo' => array('srcsort' => 'price', 'srcorder' => 'desc'),
								'date_lohi' => array('srcsort' => 'date', 'srcorder' => 'asc'),
								'date_hilo' => array('srcsort' => 'date', 'srcorder' => 'desc')) as $sKey => $sUrl): ?>
			<?php $itemURL = append_get_vars($baseURL, $sUrl, array('page')); ?>
			<?php if($activeSort == $sKey): ?>
				<li class="active"><a href="<?=$itemURL?>"><i class="icon-ok"></i>
			<?php else: ?>
				<li><a href="<?=$itemURL?>"><i class="icon-empty"></i>
			<?php endif; ?>
				<?=lang('books_'.$sKey)?></a></li>
		<?php endforeach; ?>
	</ul>
</div>