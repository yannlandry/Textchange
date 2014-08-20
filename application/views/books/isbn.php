<div class="white block top">
	<div class="pull-right">
		<a href="<?=base_url('/books/add')?>" class="btn btn-success btn-large"><?=lang('books_add_button')?></a>
	</div>
	<h1><?=lang('isbn_header_prefix')?> <?=$isbn?></h1>
</div>


<div class="gray-head block">
	<div class="row">
	
		<div class="pull-right">
			<form action="<?=base_url('/books/search')?>" method="get" class="form-inline input-append">
				<input type="search" placeholder="<?=lang('books_search_placeholder')?>" name="q" class="input-xlarge" />
				<button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i></button>
			</form>
		</div>
		
		<div class="span6">
			<?php $baseURL = base_url('/books/isbn/'.$isbn); ?>
			<?php include('application/include/sort-dropdown.php'); ?>
			<?php if(User::is_connected()): ?>
				<a href="<?=base_url('/books/isbn/'.$isbn)?>?alerts=<?=($watched ? 'unwatch' : 'watch')?>" class="btn <?php $watched and print(' btn-success'); ?>">
					<i class="icon-<?=($watched ? 'ok icon-white' : 'eye-open')?>"></i> <?=lang('isbn_watch_button')?></a>
			<?php endif; ?>
		</div>
		
	</div>
</div>


<div class="white block">
		
	<?php if(empty($ads)): ?>
		<p class="no-result"><?=lang('books_noresult')?></p>
	
	<?php else:
		$i = 0;
		foreach($ads as $A):
			$i++;
			
			$X = (object)array(
				'Url' => base_url('/books/ad/'.$A->AdID),
				'Title' => htmlspecialchars($A->Title),
				'ISBN' => 'ISBN '.$A->ISBN,
				'Price' => output_price($A->Price),
				'Picture' => get_book_picture($A->Picture, 'icon')
			);
			
			if(($i - 1) % 3 == 0) echo '<div class="row">'; # À gauche
				echo '<div class="span4">';
					include('application/include/book-small.php');
				echo '</div>';
			if($i % 3 == 0) echo '</div>'; # À droite
		endforeach;
		
		if($i % 3 != 0) echo '</div>';
	endif;?>
	
</div>


<div class="white block bottom">
	<ul class="pager no-margin">
		<?php if(!empty($prevlink)): ?>
			<li class="previous"><a href="<?=base_url('/books/isbn/'.$isbn.'?page='.($curpage-1))?>"><?=lang('books_pager_previous')?></a></li>
		<?php endif;
		if(!empty($nextlink)): ?>
			<li class="next"><a href="<?=base_url('/books/isbn/'.$isbn.'?page='.($curpage+1))?>"><?=lang('books_pager_next')?></a></li>
		<?php endif; ?>
	</ul>
</div>