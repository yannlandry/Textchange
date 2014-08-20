<?php
/*
 * REDIRECTION VERS LA BONNE ADRESSE SELON LA LANGUE
 */

# Suffixe de l'hôte
if(ENVIRONMENT == 'development')
	define('GLOBAL_URL', 'local.textchange.ca');
else
	define('GLOBAL_URL', 'textchange.ca');

# A-t-on déjà demandé une langue ?
if($_SERVER['HTTP_HOST'] == 'fr.'.GLOBAL_URL) {
	define('TEXTCHANGE_LANG', 'french');
	define('BASE_URL', 'http://fr.'.GLOBAL_URL);
}
elseif($_SERVER['HTTP_HOST'] == 'en.'.GLOBAL_URL) {
	define('TEXTCHANGE_LANG', 'english');
	define('BASE_URL', 'http://en.'.GLOBAL_URL);
}
else { # Aucune demande valide
	header("Location: http://".(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == 'fr' ? 'fr' : 'en')
				.".".GLOBAL_URL.$_SERVER['REQUEST_URI']);
	exit();
}