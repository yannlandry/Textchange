<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/* Fonctionnalités pour messages flash */
# 4 types de messages dans Bootstrap : success, danger, error, info


# Ajouter un message
function addFlash($message, $type = 'success', $isLangKey = false) 
{
	if(!isset($_SESSION['flash']))
		$_SESSION['flash'] = array();
	
	$_SESSION['flash'][] = array($message, $type, (bool)$isLangKey);
	# La troisième variable nous permet de préparer des messages avant le chargement des langues
}


# Lire les messages
function readFlash()
{
	if(!isset($_SESSION['flash'])) return false;
	
	while(!empty($_SESSION['flash'])) {
		list($message, $type, $isLangKey) = array_pop($_SESSION['flash']);
		if($isLangKey) $message = lang($message);
		echo '<p class="alert alert-site alert-'.$type.' fade in">';
		echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
		echo $message;
		echo '</p>';
	}
	
	return true;
}


/* End of file flash_helper.php */
/* Location: ./application/helpers/flash_helper.php */