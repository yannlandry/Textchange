<div class="white">
	<p><?=$confirmText?></p>

	<form method="post" class="form-actions text-center">
		<input type="hidden" name="action-confirm" />
		<?=csrf_token_input()?>
		<button type="submit" class="btn btn-primary"><?=$confirmButtonText?></button>
		<a href="<?=$cancelReturnLink?>" class="btn"><?=$cancelButtonText?></a>
	</form>
</div>