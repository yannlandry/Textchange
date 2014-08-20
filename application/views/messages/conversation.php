<form method="post" class="no-margin">

	<div class="white block top">
		<div class="pull-right">
			<a href="<?=base_url('/users/'.$C->Username)?>">
				<img src="<?=get_avatar($C->Picture, 'icon')?>" alt="" />
				<strong><small><?=coalesce($C->RealName, $C->Username)?></small></strong>
			</a>
		</div>
		<h1><?=lang('conversation_header_title')?></h1>
	</div>
	
	<div class="gray-head block">
		<div class="row">
			<div class="pull-right">
				<a href="#newmsg" class="btn btn-primary"><i class="icon-share-alt icon-white"></i> <?=lang('conversation_answer_button')?></a>
				<button class="btn" type="submit"><i class="icon-remove"></i> <?=lang('conversation_delete_button')?></button>
			</div>
			<div class="span7">
				<a href="<?=base_url('/messages')?>" class="btn"><i class="icon-arrow-left"></i> <?=lang('conversation_back_button')?></a>
				<a href="<?=append_get_vars(base_url('/messages/'.$C->Username), array('mark' => 'read', 'csrf_token' => User::csrf_token()))?>" class="btn"><i class="icon-ok"></i> <?=lang('messages_markasread_button')?></a>
			</div>
		</div>
	</div>

	<?php if(count($results) == 0) { ?>
		<div class="white block">
			<p class="no-result"><?=lang('conversation_noresult_message')?></p>
		</div>
	<?php } else foreach($results as $M): ?>
		<div id="message-<?=$M->MessageID?>" class="white block">
			<div class="row">
				<?php $link = base_url('users/'.($M->Way == 'in' ? $C->Username : User::name(true))); ?>
				<div class="span2 text-center">
					<p><a href="<?=$link?>"><img src="<?=get_avatar($M->Way == 'in' ? $C->Picture : User::session()->Picture, 'small')?>" alt="" /></a></p>
					<p><strong><a href="<?=$link?>"><?=($M->Way == 'in' ? coalesce($C->RealName, $C->Username) : User::name())?></a></strong>
					<br /><?=badge_of($M->Way == 'in' ? $C->Rights : User::session()->Rights)?></p>
				</div>
				<div class="span10">
					<label for="delete-msg-<?=$M->MessageID?>" class="select-message">
						<input type="checkbox" id="delete-msg-<?=$M->MessageID?>" name="delete_msg[]" value="<?=$M->MessageID?>" />
					</label>
					<p class="muted"><small><?=csdate($M->DateSent)?></small></p>
					<p>
						<?=$M->Message?>
					</p>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<div class="gray-head block pagination">
		<div class="row">
			<div class="pull-right">
				<a href="#newmsg" class="btn btn-primary"><i class="icon-share-alt icon-white"></i> <?=lang('conversation_answer_button')?></a>
				<button class="btn" type="submit"><i class="icon-remove"></i> <?=lang('conversation_delete_button')?></button>
			</div>
			<div class="span7">
				<?=$pagination?>
			</div>
		</div>
	</div>

	<?=csrf_token_input()?>
</form>

<div class="white block bottom" id="newmsg">
	<div class="row">
		<form method="post" class="span6 offset3">
			<h4><?=lang('conversation_answer_title').' '.coalesce($C->RealName, $C->Username)?></h4>
			<p><textarea name="message" rows="4" class="input-block-level autosize"></textarea></p>
			<p class="text-center"><button type="submit" class="btn btn-primary">Envoyer</button></p>
			<?=csrf_token_input()?>
		</form>
	</div>
</div>