<!DOCTYPE html>
<html>
<head>

	<!-- Titre & meta -->
	<title><?=$title?></title>
	<meta charset="utf-8" />
	
	<!-- Styles de base -->
	<link rel="stylesheet" type="text/css" href="<?=ASSETS_ROOT?>/bootstrap/css/bootstrap.css?v=2013-09-01" />
	<link rel="stylesheet" type="text/css" href="<?=ASSETS_ROOT?>/style/general.css?v=2013-09-02" />
	
	<!-- Styles additionnels -->
	<?php if(!empty($styles) && is_array($styles)) foreach($styles as $s): ?>
		<link rel="stylesheet" type="text/css" href="<?=$s?>" />
	<?php endforeach; ?>
	
	<!-- jQuery -->
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	
	<!-- Plugins Bootstrap -->
	<script type="text/javascript" src="<?=ASSETS_ROOT?>/bootstrap/js/bootstrap.min.js"></script>
	
	<!-- Scripts additionnels -->
	<?php if(!empty($scripts) && is_array($scripts)) foreach($scripts as $s): ?>
		<script type="text/javascript" src="<?=$s?>"></script>
	<?php endforeach; ?>
	
	<link rel="icon" type="image/x-icon" href="<?=base_url('/favicon.ico')?>" />
	<link rel="shortcut icon" type="image/x-icon" href="<?=base_url('/favicon.ico')?>" />

</head>



<body>

<?php /*<div id="ads">
	<div>
		<script type="text/javascript"><!--
			google_ad_client = "ca-pub-1157667153997918";
			/* Top ad *
			google_ad_slot = "1088774182";
			google_ad_width = 728;
			google_ad_height = 90;
			//-->
		</script>
		<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
	</div>
</div>*/ ?>

<header>
	<div id="banner">
		<div>
			<div id="logo">
				<p><a href="<?=base_url()?>"><img src="<?=ASSETS_ROOT?>/images/logos/logo_<?=User::lang()?>.png" alt="Textchange" /></a></p>
				<form action="<?=base_url('books/search')?>" method="get" class="form-horizontal input-append pull-left">
					<input type="search" name="q" class="input-xlarge" placeholder="<?=lang('header_search_placeholder')?>" />
					<button class="btn btn-inverse" type="submit"><i class="icon-search icon-white"></i></button>
				</form>
				<div>
					<a href="<?=base_url('/books/add')?>" class="btn btn-success addoffer-button"><?=lang('header_createad_button')?></a>
				</div>
			</div>
			<?php if(User::is_connected()):
				$newMessages = count_new_messages();
				if(User::is_mod()) $newReports = count_new_reports(); ?>
				<div id="userbox">
					<div id="avatar"><img src="<?=get_avatar(User::session()->Picture, 'icon')?>" alt="" /></div>
					<div id="userinfo">
						<?php if(isset($newReports)): ?>
							<a href="<?=base_url('/admin')?>" class="badge <?php $newReports > 0 and print('badge-warning')?>">
								<i class="icon-flag icon-white"></i> <?=$newReports?></a>
						<?php endif; ?>
						<a href="<?=base_url('/messages')?>" class="badge <?php $newMessages > 0 and print('badge-warning')?>">
							<i class="icon-envelope icon-white"></i> <?=$newMessages?></a>
						<a href="<?=base_url('/users/'.User::name(TRUE))?>"><?=User::name()?></a><br />
						<span><a href="<?=base_url('/users/'.User::name(TRUE).'/edit')?>"><?=lang('header_myaccount_link')?></a> | <a href="<?=base_url('/logout')?>"><?=lang('header_logoff_link')?></a></span>
					</div>
				</div>
			<?php else: ?>
				<div id="logbox"><a href="<?=base_url('/login')?>" class="btn btn-primary"><?=lang('header_login_button')?></a>
				<a href="<?=base_url('/signup')?>" class="btn"><?=lang('header_signup_button')?></a></div>
			<?php endif; ?>
		</div>
	</div>

	<nav>
		<div class="header-cnt">
			<div class="header-left">
				<div class="header-nav">
					<div>
						<a href="<?=BASE_URL?>"><i class="icon-home icon-white"></i> <?=lang('header_home_link')?></a>
					</div>
					<div>
						<a href="<?=base_url('/books')?>"><i class="icon-book icon-white"></i> <?=lang('header_books_link')?></a>
						<div><div><div>
							<a href="<?=BASE_URL?>/books"><?=lang('header_books_browse_link')?></a>
							<a href="<?=BASE_URL?>/books/add"><?=lang('header_books_sell_link')?></a>
							<?php if(User::is_connected()): ?>
								<a href="<?=BASE_URL?>/users/<?=User::name(TRUE)?>#ads"><?=lang('header_books_myads_link')?></a>
							<?php endif; ?>
						</div></div></div>
					</div>
					<div>
						<a href="<?=base_url('/schools')?>"><i class="icon-certificate icon-white"></i> <?=lang('header_schools_link')?></a>
					</div>
					<div>
						<a href="<?=base_url('/users')?>"><i class="icon-user icon-white"></i> <?=lang('header_users_link')?></a>
						<div><div><div>
							<a href="<?=BASE_URL?>/users"><?=lang('header_users_browse_link')?></a>
							<?php if(User::is_connected()): ?>
								<a href="<?=BASE_URL?>/users/<?=User::name(TRUE)?>"><?=lang('header_users_myprofile_link')?></a>
							<?php else: ?><a href="<?=BASE_URL?>/login"><?=lang('header_users_login_link')?></a>
								<a href="<?=BASE_URL?>/signup"><?=lang('header_users_signup_link')?></a>
							<?php endif; ?>
						</div></div></div>
					</div>
					<?php if(User::is_mod()): ?>
						<div>
							<a href="<?=base_url('/admin')?>"><i class="icon-cog icon-white"></i> <?=lang('header_admin_link')?></a>
							<?php if(User::is_admin()): ?>
								<div><div><div>
									<a href="<?=base_url('/admin/management')?>"><?=lang('header_admin_management_link')?></a>
									<a href="<?=base_url('/admin/reports')?>"><?=lang('header_admin_reports_link')?></a>
									<a href="<?=base_url('/admin/maintenance')?>"><?=lang('header_admin_maintenance_link')?></a>
									<?php if(User::id(1)): ?><a href="http://textchange.ca/cpanel" target="_blank">cPanel</a><?php endif; ?>
								</div></div></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			
			<div class="header-right">
				<div class="header-nav">
					<div>
						<a href="http://<?=bil('en','fr')?>.<?=GLOBAL_URL.$_SERVER['REQUEST_URI']?>"><i class="icon-globe icon-white"></i> <?=bil('English','Français')?></a>
					</div>
				</div>
			</div>
		</div>
	</nav>
	
	<div id="path">
		<div><a href="<?=base_url()?>">Textchange</a>
			<?php if(!empty($path) && is_array($path)) foreach($path as $page => $url) {
				echo ' :: ';
				if(!empty($url)) echo '<a href="'.base_url($url).'">'.$page.'</a>';
				else echo $page; } ?>
		</div>
	</div>
</header>


<!-- Début du corps -->
<div id="body">

	<?php # Lecture des messages flash
			readFlash(); ?>
