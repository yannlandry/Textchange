<div class="gray-head">
	<h1><?=lang('admin_header_title')?></h1>
</div>


<div class="white">
	<div class="row">
		<div class="span3">
			<div class="well admin-menu">
				<ul class="nav nav-list">
					<li class="nav-header"><?=lang('reports_menu_title')?></li>
						<li <?php $active == 'adsreports' and print('class="active"'); ?>>
							<a href="<?=base_url('admin/reports/ads')?>"><?=lang('adsreports_menu_title')?>
							<?php !empty($newadsreports) and print('<span class="badge badge-warning">'.$newadsreports.'</span>'); ?></a>
						</li>
						<?php if(User::is_admin()): ?>
							<li <?php $active == 'profilesreports' and print('class="active"'); ?>>
								<a href="<?=base_url('admin/reports/profiles')?>"><?=lang('profilesreports_menu_title')?>
								<?php !empty($newprofilesreports) and print('<span class="badge badge-warning">'.$newprofilesreports.'</span>'); ?></a>
							</li>
						<?php endif; ?>
						
					<?php if(User::is_admin()): ?>
						<li class="nav-header"><?=lang('management_menu_title')?></li>
							<li <?php $active == 'domains' and print('class="active"'); ?>>
								<a href="<?=base_url('admin/management/domains')?>"><?=lang('domains_menu_title')?></a></li>
							<li <?php $active == 'config' and print('class="active"'); ?>>
								<?=lang('slideshow_menu_title')?></li>
					<?php endif; ?>
						
					<?php if(User::is_admin()): ?>
						<li class="nav-header"><?=lang('maintenance_menu_title')?></li>
							<li <?php $active == 'config' and print('class="active"'); ?>>
								<?=lang('config_menu_title')?></li>
							<li <?php $active == 'logs' and print('class="active"'); ?>>
								<a href="<?=base_url('admin/maintenance/logs')?>"><?=lang('logs_menu_title')?></a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
		
		<div class="span9">
			<?=$view;?>
		</div>
	</div>
</div>