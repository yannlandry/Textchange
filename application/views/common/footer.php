</div>
<!-- Fin du corps -->


<footer>

	<div class="container">
		<div class="row">
			<div class="span3">
				<h5><?=lang('footer_textchange_section_title')?></h5>
				<p>
					<a href="<?=BASE_URL?>/"><?=lang('footer_textchange_section_home')?></a><br />
					<a href="<?=BASE_URL?>/books"><?=lang('footer_textchange_section_books')?></a><br />
					<a href="<?=BASE_URL?>/schools"><?=lang('footer_textchange_section_schools')?></a><br />
					<a href="<?=BASE_URL?>/users"><?=lang('footer_textchange_section_users')?></a><br />
				</p>
			</div>
			<div class="span3">
				<h5><?=lang('footer_aboutus_section_title')?></h5>
				<p>
					<?=lang('footer_aboutus_section_about')?><br />
					<?=lang('footer_aboutus_section_legal')?><br />
					<?=lang('footer_aboutus_section_team')?><br />
					<a href="<?=BASE_URL?>/contact"><?=lang('footer_aboutus_section_contactus')?></a>
				</p>
			</div>
			<div class="span3">
				<h5><?=lang('footer_myaccount_section_title')?></h5>
				<p>
					<?php if(User::is_connected()): ?>
						<a href="<?=BASE_URL?>/users/<?=User::name(true)?>"><?=lang('footer_myaccount_section_myprofile')?></a><br />
						<a href="<?=BASE_URL?>/users/<?=User::name(true)?>/edit"><?=lang('footer_myaccount_section_manage')?></a><br />
						<a href="<?=BASE_URL?>/books/user/<?=User::name(true)?>"><?=lang('footer_myaccount_section_myads')?></a><br />
						<a href="<?=BASE_URL?>/books/alerts"><?=lang('footer_myaccount_section_alerts')?></a>
					<?php else: ?>
						<a href="<?=BASE_URL?>/login"><?=lang('footer_myaccount_section_login')?></a><br />
						<a href="<?=BASE_URL?>/signup"><?=lang('footer_myaccount_section_signup')?></a><br />
						<a href="<?=BASE_URL?>/login/recover"><?=lang('footer_myaccount_section_recover')?></a><br />
						<a href="<?=BASE_URL?>/activate/resend"><?=lang('footer_myaccount_section_activate')?></a>
					<?php endif; ?>
				</p>
			</div>
			<div class="span3">
				<h5><?=lang('footer_copyright')?></h5>
				<p><?=lang('footer_poweredby')?> <strong><a href="http://ellislab.com/codeigniter" target="_blank">CodeIgniter</a></strong></p>
			</div>
		</div>
	</div>

</footer>

<script type="text/javascript">
	// Alertes
	$(".alert").alert();
</script>

</body>
</html>