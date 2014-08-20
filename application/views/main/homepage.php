<div class="white">
	<h1><?=lang('home_header_title')?></h1> 
	<?php if(User::lang() == 'english'): ?><p class="alert alert-warning">This page is temporary and does not exist in english.</p><?php endif; ?>
	
	<div class="row">
		<div class="span8">
			<div class="row">
				<div class="span8">
					<p><img src="<?=BASE_URL?>/static/images/splash.png" /></p>
				</div>
			</div>
			<div class="row">
				<div class="span4">
					<h4>À propos de Textchange</h4>
					<p>Textchange est une plateforme pour étudiants universitaires ou collégiaux leur permettant de vendre et/ou d'acheter des manuels de cours usagés selon un modèle de petites annonces. Les informations sur les livres sont maintenues à jour par la communauté.</p>
				</div>
				
				<div class="span4">
					<h4>Phase de développement</h4>
					<p>Le site est actuellement en phase de développement, ce qui signifie que plusieurs fonctionnalités ne sont pas encore présentes. C'est pourquoi plusieurs liens peuvent être morts et aussi pourquoi certaines erreurs pourraient survenir.</p>
				</div>
			</div>
			<div class="row">
				<div class="span4">
					<h4>Pour s'inscrire</h4>
					<p>Normalement, le site sera ouvert à tous les étudiants inscrits dans un établissement enregistré auprès de Textchange. Pour le moment, seuls ceux qui fréquentent l'un des trois campus de l'Université de Moncton sont admis.</p>
				</div>
				
				<div class="span4">
					<h4>Envie de contribuer ?</h4>
					<p>Aidez-nous à repérer les bugs, les problèmes et les erreurs sur Textchange. Signalez-les à l'aide du <a href="<?=base_url('/contact')?>">formulaire de contact</a>. Nous serons bientôt à la recherche de vérificateurs pour gérer tout notre contenu et d'autres membres prêts à donner un coup de main.</p>
				</div>
			</div>
		</div>
			
		<div class="span4">
			
			<?php if(!User::is_connected()): ?>
				<p><a href="<?=base_url('/signup')?>" class="btn input-block-level btn-large btn-success"><?=lang('home_signup_button')?></a></p>
			<?php endif; ?>
			
			<div class="well">
				<h3>Avancement du projet</h3>
				<p>Design de base : <strong>100%</strong></p>
				<div class="progress"><div class="bar bar-success" style="width: 100%"></div></div>
				
				<p>Pages principales : <strong>80%</strong></p>
				<div class="progress"><div class="bar bar-warning" style="width: 80%"></div></div>
				
				<p>Module Annonces : <strong>75%</strong></p>
				<div class="progress"><div class="bar bar-warning" style="width: 75%"></div></div>
				
				<p>Module Établissements : <strong>100%</strong></p>
				<div class="progress"><div class="bar bar-success" style="width: 100%"></div></div>
				
				<p>Module Utilisateurs : <strong>90%</strong></p>
				<div class="progress"><div class="bar bar-warning" style="width: 90%"></div></div>
				
				<p>Module Messagerie : <strong>100%</strong></p>
				<div class="progress"><div class="bar bar-success" style="width: 100%"></div></div>
				
				<p>Autres modules mineurs : <strong>60%</strong></p>
				<div class="progress"><div class="bar bar-warning" style="width: 60%"></div></div>
				
				<p>Traduction : <strong>40%</strong></p>
				<div class="progress"><div class="bar bar-danger" style="width: 40%"></div></div>
			</div>
		</div>
	</div>

</div>