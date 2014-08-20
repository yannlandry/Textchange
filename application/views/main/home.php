<div class="white">
	
	<div class="home-splash"><a href="<?=base_url(User::is_connected() ? '/books/add' : '/signup')?>"><img src="<?=ASSETS_ROOT?>/images/splash_<?=User::lang()?>.png" alt="Textchange" /></a></div>
	
	<div class="row">
		<div class="pull-right span4">
			<?php if(User::is_connected()): ?>
				<p><a href="<?=base_url('/books/add')?>" class="btn input-block-level btn-large btn-success"><?=lang('home_createad_button')?></a></p>
			<?php else: ?>
				<p><a href="<?=base_url('/signup')?>" class="btn input-block-level btn-large btn-success"><?=lang('home_signup_button')?></a></p>
			<?php endif; ?>
			
			<h4><?=lang('home_about_title')?></h4>
			
			<p><?=lang('home_about_paragraph')?></p>
		</div>
		
		<div class="span8">
			<h4><?=lang('home_recent_title')?></h4>
			
			<?php $i = 0;
			foreach($ads as $A):
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
			if($i % 2 != 0) echo '</div>'; ?>
			
			<ul class="pager"><li class="next"><a href="<?=base_url('/books')?>"><?=lang('home_recent_link')?></a></li></ul>
		</div>
	</div>

</div>