<div class="white block top">
	<div class="pull-right">
		<a href="<?=base_url('/books/add')?>" class="btn btn-success btn-large"><?=lang('books_add_button')?></a>
	</div>
	<h1><?=lang('books_header_title')?></h1>
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
			<?php $baseURL = base_url('/books'); ?>
			<?php include('application/include/lang-dropdown.php'); ?>
		</div>
	
	</div>
</div>


<div class="white block bottom">
	<div class="row">
		<div class="span8">
		
		<?php if(empty($showcase)): ?>
			<p class="no-result"><?=lang('books_noresult')?></p>
		
		<?php else:
			foreach($showcase as $SC): ?>
				<h3 class="catg-underlined"><a href="<?=base_url('books/domain/'.$SC->DomainID)?>" class="unstyled"><?=$SC->DomainName?></a></h3>
				
				<?php $i = 0;
				foreach($SC->Ads as $A):
					$i++;
					
					$X = (object)array(
						'Url' => base_url('/books/ad/'.$A->AdID),
						'Title' => htmlspecialchars($A->Title),
						'ISBN' => 'ISBN '.$A->ISBN,
						'Price' => output_price($A->Price),
						'Picture' => get_book_picture($A->Picture, 'icon')
					);
					
					if(($i - 1) % 2 == 0) echo '<div class="row">'; # À gauche
						echo '<div class="span4">';
							include('application/include/book-small.php');
						echo '</div>';
					if($i % 2 == 0) echo '</div>'; # À droite
				endforeach;
				
				if($i % 2 != 0) echo '</div>';
				
			endforeach;
		endif; ?>
		
		</div>
		<div class="span4">
		
		
			<?php /*<div class="well">
				<h3><?=lang('books_indemand_header')?></h3>
				<p><em>Fonctionnalité à venir.</em></p>
			</div>*/ ?>
			
			<h3><?=lang('books_domains_header')?></h3>
			
			<div class="row">
				<div class="span2">
					<ul class="nav nav-pills nav-stacked">
						<?php foreach($domains['left'] as $dID => $dName): ?>
							<li><a href="<?=base_url('books/domain/'.$dID)?>"><?=$dName?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="span2">
					<ul class="nav nav-pills nav-stacked">
						<?php foreach($domains['right'] as $dID => $dName): ?>
							<li><a href="<?=base_url('books/domain/'.$dID)?>"><?=$dName?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		
		
		</div>
	</div>
</div>