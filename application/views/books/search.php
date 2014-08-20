<div class="white block top">
	<h1><?=lang('search_header_prefix')?> <?=lang('quote_open').htmlspecialchars($Q).lang('quote_close')?></h1>
</div>


<div class="gray-head block">
	<div class="row">
	
		<div class="pull-right">
			<form action="<?=base_url('/books/search')?>" method="get" class="form-inline input-append">
				<input type="search"  name="q" value="<?=$Q?>" class="input-xlarge" />
				<button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i></button>
			</form>
		</div>
		
		<div class="span6">
			<?php $baseURL = base_url('/books/search'); ?>
			<?php include('application/include/lang-dropdown.php'); ?>
			<?php include('application/include/sort-dropdown.php'); ?>
		</div>
		
	</div>
</div>


<?php if(empty($ads)) { ?>
	<div class="white block">
		<p class="no-result"><?=lang('search_notfound')?></p>
	</div>

<?php } else foreach($ads as $A): ?>
	<?php $booklink = base_url('/books/ad/'.$A->AdID); ?>
	<div class="white block">
		<div class="row">
			<div class="span2"><a href="<?=$booklink?>"><img src="<?=get_book_picture($A->Picture, 'small')?>" alt="" class="img-polaroid" /></a></div>
			<div class="span10">
				<h4><a href="<?=$booklink?>"><?=$A->Title?></a></h4>
				<p>
					<strong><?=lang('search_isbn_prefix')?></strong> <a href="<?=base_url('/books/isbn/'.$A->ISBN)?>"><?=$A->ISBN?></a>
					<?php if(!empty($A->Authors)): ?>
						<br /><strong><?=lang('search_authors_prefix')?></strong> <?=$A->Authors?>
					<?php endif; ?>
				</p>
				<p><?=lang('search_soldby_prefix')?> <strong><a href="<?=base_url('/users/'.$A->Username)?>"><?=coalesce($A->RealName, $A->Username)?></a></strong></p>
				<p><strong><?=output_price($A->Price)?></strong></p>
			</div>
		</div>
	</div>
<?php endforeach; ?>


<div class="white block text-center">
	<?=lang('search_alerts_intro')?><br />
	<strong><a href="<?=base_url('/books/alerts')?>"><?=lang('search_alerts_link')?></a></strong>
</div>


<div class="white block bottom">
	<ul class="pager no-margin">
		<?php if(!empty($prevlink)): ?>
			<li class="previous"><a href="<?=append_get_vars(base_url('/books/search'), array('page' => $curpage - 1))?>"><?=lang('books_pager_previous')?></a></li>
		<?php endif;
		if(!empty($nextlink)): ?>
			<li class="next"><a href="<?=append_get_vars(base_url('/books/search'), array('page' => $curpage + 1))?>"><?=lang('books_pager_next')?></a></li>
		<?php endif; ?>
	</ul>
</div>
