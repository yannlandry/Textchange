<div class="white block top">
	<div class="pull-right">
		<a href="<?=base_url('/books/add')?>" class="btn btn-success btn-large"><?=lang('books_add_button')?></a>
	</div>
	<h1>
		<a href="<?=base_url('/users/'.$U->Username)?>"><img src="<?=get_avatar($U->Picture, 'icon')?>" alt="" class="img-polaroid" /></a>
		<?=lang('user_header_prefix')?> <a href="<?=base_url('/users/'.$U->Username)?>"><?=coalesce($U->RealName, $U->Username)?></a>
	</h1>
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
			<?php $baseURL = base_url('/books/user/'.$U->Username); ?>
			<?php include('application/include/lang-dropdown.php'); ?>
			<?php include('application/include/sort-dropdown.php'); ?>
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
			<li class="previous"><a href="<?=base_url('/books/user/'.$U->Username.'?page='.($curpage-1))?>"><?=lang('books_pager_previous')?></a></li>
		<?php endif;
		if(!empty($nextlink)): ?>
			<li class="next"><a href="<?=base_url('/books/user/'.$U->Username.'?page='.($curpage+1))?>"><?=lang('books_pager_next')?></a></li>
		<?php endif; ?>
	</ul>
</div>