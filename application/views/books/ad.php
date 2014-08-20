<div class="gray-head">
	<div class="row">
		<div class="span3"><div class="book-picture">
			<?php $PLoc = get_book_picture($A->Picture); ?>
			<p>
				<?php !empty($PLoc) and print('<a href="'.$PLoc.'" id="book-picture" title="'.htmlspecialchars($A->Title).' (ISBN '.$A->ISBN.')">'); ?>
					<img src="<?=get_book_picture($A->Picture, 'display')?>" class="img-polaroid">
				<?php !empty($PLoc) and print('</a>'); ?>
			</p>
			<h3 class="text-center"><?=output_price($A->Price)?></h3>
			<?php if(User::id($A->UserID) || User::is_mod()): ?>
				<hr />
				<p><a href="<?=base_url('/books/ad/'.$A->AdID.'/edit')?>" class="btn"><i class="icon-edit"></i> <?=lang('ad_edit_button')?></a></p>
				<p><a href="<?=base_url('/books/ad/'.$A->AdID.'/delete')?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> <?=lang('ad_delete_button')?></a></p>
			<?php endif; ?>
		</div></div>
		<div class="span9">
			<div class="pull-right"><?php include('application/include/fb-share.php'); ?></div>
			<h1><?=htmlspecialchars($A->Title)?></h1>
			<p><strong><?=lang('ad_isbn_section')?></strong> <a href="<?=base_url('books/isbn/'.$A->ISBN)?>"><?=$A->ISBN?></a></p>
			<p>
				<?php if(User::is_connected()): ?>
					<div class="btn-group">
						<button class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-envelope"></i> <?=lang('ad_contact_button')?> <span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a href="<?=base_url('/messages/'.$A->Username.'#newmsg')?>">
								<i class="icon-share-alt"></i> <?=lang('ad_messageseller_option')?></a></li>
							<li><a href="<?=base_url('/books/ad/'.$A->AdID.'/email')?>">
								<i class="icon-envelope"></i> <?=lang('ad_emailseller_option')?></a></li>
						</ul>
					</div>
					<a href="<?=base_url('/books/ad/'.$A->AdID)?>?alerts=<?=($A->IsWatched == 1 ? 'unwatch' : 'watch')?>" class="btn <?php $A->IsWatched == 1 and print(' btn-success'); ?>">
						<i class="icon-<?=($A->IsWatched == 1 ? 'ok icon-white' : 'eye-open')?>"></i> <?=lang('ad_watch_button')?></a>
				<?php elseif($A->UnregContact == '1'): ?>
					<a href="<?=base_url('/books/ad/'.$A->AdID.'/email')?>" class="btn">
						<i class="icon-envelope"></i> <?=lang('ad_contact_button')?></a>
				<?php endif; ?>
				<a href="<?=base_url('/books/ad/'.$A->AdID.'/report')?>" class="btn">
					<i class="icon-flag"></i> <?=lang('ad_report_button')?></a>
			</p>
		</div>
	</div>
</div>


<div class="white">
	<div class="row">
		<div class="offset3 span9">
		
			<div class="row book-row">
				<div class="span3"><strong><?=lang('ad_seller_section')?></strong></div>
				<div class="span6">
					<img src="<?=get_avatar($A->UserPicture, 'icon')?>" alt="" />
					<strong><a href="<?=base_url('/users/'.$A->Username)?>"><?=coalesce($A->RealName, $A->Username)?></a></strong>
				</div>
			</div>
		
			<?php if(!empty($A->Authors)): ?>
				<div class="row book-row">
					<div class="span3"><strong><?=lang('ad_authors_section')?></strong></div>
					<div class="span6"><?=htmlspecialchars($A->Authors)?></div>
				</div>
			<?php endif; ?>
		
			<?php if(!empty($A->Publisher)): ?>
				<div class="row book-row">
					<div class="span3"><strong><?=lang('ad_publisher_section')?></strong></div>
					<div class="span6"><?=htmlspecialchars($A->Publisher)?></div>
				</div>
			<?php endif; ?>
		
			<?php if(intval($A->PubYear) > 0): ?>
				<div class="row book-row">
					<div class="span3"><strong><?=lang('ad_pubyear_section')?></strong></div>
					<div class="span6"><?=$A->PubYear?></div>
				</div>
			<?php endif; ?>
		
			<?php if(!empty($A->Information)): ?>
				<div class="row book-row">
					<div class="span3"><strong><?=lang('ad_information_section')?></strong></div>
					<div class="span6"><?=nl2br(htmlspecialchars($A->Information))?></div>
				</div>
			<?php endif; ?>
		
			<div class="row book-row">
				<div class="span3"><strong><?=lang('ad_creation_section')?></strong></div>
				<div class="span6"><?=csdate($A->Creation)?></div>
			</div>
		
		</div>
	</div>
</div>


<?php if(!empty($ads)): ?>
	<div class="white spaced">
		<h3><?=lang('ad_similar_title')?></h3>
		
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
			
			if(($i - 1) % 3 == 0) echo '<div class="row">'; # À gauche
				echo '<div class="span4">';
					include('application/include/book-small.php');
				echo '</div>';
			if($i % 3 == 0) echo '</div>'; # À droite
		endforeach;
		
		if($i % 3 != 0) echo '</div>'; ?>
		
		<ul class="pager">
			<li class="next"><a href="<?=base_url('/books/isbn/'.$A->ISBN)?>"><?=lang('ad_similar_link')?></a></li>
		</ul>
	</div>
<?php endif; ?>