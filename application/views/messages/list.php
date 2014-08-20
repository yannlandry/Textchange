<div class="white block top">
	<div class="pull-right">
		<a href="<?=append_get_vars(base_url('/messages'), array('mark' => 'read', 'csrf_token' => User::csrf_token()))?>" class="btn"><i class="icon-ok"></i> <?=lang('messages_markasread_button')?></a>
		<a href="<?=base_url('/users?after=message')?>" class="btn btn-primary"><i class="icon-envelope icon-white"></i> <?=lang('messages_newmessage_button')?></a>
	</div>
	<h1><?=lang('messages_header_title')?></h1>
</div>

<div class="gray-head block">
	<div class="row">
		<div class="span9 offset1"><strong><?=lang('messages_conversationwith_header')?></strong></div>
		<div class="span2"><strong><?=lang('messages_lastmessagedate_header')?></strong></div>
	</div>
</div>

<?php if(count($results) == 0) { ?>
	<div class="white block">
		<p class="no-result"><?=lang('messages_noresult_message')?></p>
	</div>
<?php } else foreach($results as $C): ?>
	<div class="white block">
		<div class="row">
			<div class="span1"><img src="<?=get_avatar($C->Picture, 'icon')?>" alt="" /></div>
			<div class="span9">
				<p><strong><a href="<?=base_url('/messages/'.$C->Username)?>"><?=coalesce($C->RealName, $C->Username)?></a></strong>
				<?php if(intval($C->UnreadMessages) > 0): ?>
					<span class="badge badge-warning"><?=$C->UnreadMessages?></span>
				<?php endif; ?>
				<br /><?php $C->Way == 'out' and print('<i class="icon-share-alt"></i> '); ?><?=character_limiter(strip_tags($C->Message), 200)?></p>
			</div>
			<div class="span2 muted"><small><?=csdate($C->DateSent)?></small></div>
		</div>
	</div>
<?php endforeach; ?>

<div class="gray-head block bottom pagination">
	<div class="row">
		<div class="pull-right">
			<a href="<?=append_get_vars(base_url('/messages'), array('mark' => 'read', 'csrf_token' => User::csrf_token()))?>" class="btn"><i class="icon-ok"></i> <?=lang('messages_markasread_button')?></a>
			<a href="<?=base_url('/users?after=message')?>" class="btn btn-primary"><i class="icon-envelope icon-white"></i> <?=lang('messages_newmessage_button')?></a>
		</div>
		<div class="span6">
			<?=$pagination?>
		</div>
	</div>
</div>