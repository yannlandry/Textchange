<div class="gray-head">
	<div class="row">
		<div class="span3"><div class="profile-avatar">
			<p><img src="<?=get_avatar($P->Picture)?>" class="img-polaroid"></p>
			<?php if(User::id($P->UserID) || User::is_admin()): ?><p><a href="<?=base_url('users/'.$P->Username.'/edit')?>" class="btn"><i class="icon-edit"></i> <?=lang('profile_editprofile_button')?></a></p><?php endif; ?>
		</div></div>
		<div class="span9">
			<h1><?=coalesce($P->RealName, $P->Username)?>
			<?php if($P->Rights == 2): ?><span class="label label-important"><?=lang('administrator')?></span><?php elseif($P->Rights == 1): ?><span class="label label-info"><?=lang('moderator')?></span><?php endif; ?></h1>
			<p><?=$P->SchoolName?>&nbsp;</p>
			<p>
				<?php if(User::is_connected() && !User::id($P->UserID)): ?>
					<a href="<?=base_url('/messages/'.$P->Username.'#newmsg')?>" class="btn">
						<i class="icon-share-alt"></i> <?=lang('profile_sendmessage_button')?>
					</a>
				<?php endif; ?>
				<a href="<?=base_url('/users/'.$P->Username.'/report')?>" class="btn">
					<i class="icon-flag"></i> <?=lang('profile_report_button')?>
				</a>
			</p>
		</div>
	</div>
</div>


<div class="white">
	<div class="row">
		<div class="offset3 span9">
		
			<div class="row profile-row">
				<div class="span3"><strong><?=lang('profile_email_section')?></strong></div>
				<div class="span6">
					<?php 	if($P->DisplayEmail == 0 && !User::id($P->UserID) && !User::is_mod()) echo '<em>'.lang('profile_dontdisplayemail').'</em>';
							elseif(!User::is_connected()) echo '<em>'.lang('profile_connecttosee').'</em>';
							else {
								echo '<a href="mailto:'.$P->Email.'">'.$P->Email.'</a>';
								if($P->DisplayEmail == 0) echo '<br /><em>('.lang('profile_emailprivate_mention').')</em>';
							} ?>
				</div>
			</div>
		
			<?php if(!empty($P->PhoneNumber)): ?>
				<div class="row profile-row">
					<div class="span3"><strong><?=lang('profile_phone_section')?></strong></div>
					<div class="span6">
						<?php 	if(!User::is_connected()) echo '<em>'.lang('profile_connecttosee').'</em>';
								else echo format_phone($P->PhoneNumber); ?>
					</div>
				</div>
			<?php endif; ?>
		
			<?php if(!empty($P->Information)): ?>
				<div class="row profile-row">
					<div class="span3"><strong><?=lang('profile_information_section')?></strong></div>
					<div class="span6"><?=nl2br(htmlspecialchars($P->Information))?></div>
				</div>
			<?php endif; ?>
		
		</div>
	</div>
</div>


<?php if(!empty($ads)): ?>
	<div class="white spaced">
		<h3><?=lang('profile_ads_title')?></h3>
		
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
			<li class="next"><a href="<?=base_url('/books/user/'.$P->Username)?>"><?=lang('profile_ads_link')?></a></li>
		</ul>
	</div>
<?php endif; ?>