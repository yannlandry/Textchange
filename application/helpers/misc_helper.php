<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/* Renvoie le badge (administrateur ou vérificateur) de l'utilisateur */
function coalesce()
{
	foreach(func_get_args() as $arg)
		if(!empty($arg))
			return $arg;
	
	return NULL;
}


/* Renvoie la province dans la langue appropriée */
function province($init = 'NB')
{
	return lang('province_'.strtolower($init));
}


/* Renvoie une date dans la langue en cours */
function lgdate($T = NULL, $format = NULL)
{
	if(empty($T)) $T = time();
	if(empty($format)) $format = lang('csdate_format');
	
	$T = intval($T);
	
	return str_replace(
		array('{D}','{M}','{Y}'),
		array(date('d',$T), lang('csdate_'.date('F',$T)), date('Y',$T)),
		$format
	);
}


/* Renvoie une date lisible */
function csdate($input = NULL, $absdate = false, $exclude_hrs = false, $no_acronym = false)
{
	# Transformation de l'input
	$T = !is_int($input) ? strtotime($input) : $input;
	if($T === FALSE) return $input;
	unset($input);
	
	# Maintenant
	$now = time();
	
	# Application des ajustements
	if(defined('CSDATE_SERVER_ADJUST')) $now+= intval(CSDATE_SERVER_ADJUST) * 3600;
	if(defined('CSDATE_DATABASE_ADJUST')) $T+= intval(CSDATE_DATABASE_ADJUST) * 3600;
	
	# Différence de temps
	$diff = $now - $T;
	
	# Génération de la date absolue
	$date = str_replace(
		array('{D}', '{M}', '{Y}'),
		array(date('j', $T), lang('csdate_'.date('F', $T)), date('Y', $T)),
		lang('csdate_format')
	);
	$hour = date('H:i', $T);
	
	# Here we go...
	$display = '';
	
	if(!$absdate && $diff < 518400) {
		# Affichage "à l'instant"
		if($diff < 10)
			$display = lang('csdate_now');
		
		# Affichage d'un intervalle
		elseif($diff < 86400) {
			if($diff < 60)			$display = $diff.' '.lang('csdate_sec_p');
			elseif($diff < 3600)	$display = intval($diff / 60).' '.($diff < 120 ? lang('csdate_min_s') : lang('csdate_min_p'));
			else					$display = intval($diff / 3600).' '.($diff < 7200 ? lang('csdate_hrs_s') : lang('csdate_hrs_p'));
			
			$display = str_replace('{T}', $display, lang('csdate_interval_format'));
		}
		
		# Affichage d'un jour de semaine
		else {
			$display = lang('csdate_'.date('l', $T));
			if(!$exclude_hrs) $display.= ' '.lang('csdate_at').' '.$hour;
		}
	}
	
	# Affchage simple de la date
	else {
		$display = $date;
		if(!$exclude_hrs) $display.= ' '.lang('csdate_at').' '.$hour;
	}
	
	return ($no_acronym?'':'<acronym title="'.$date.' '.lang('csdate_at').' '.$hour.'" class="time">').$display.($no_acronym?'':'</acronym>');
}


/* Renvoie le badge (administrateur ou vérificateur) de l'utilisateur */
function badge_of($rank)
{
	if($rank == 0) return NULL;
	elseif($rank == 1) return '<span class="label label-info">'.lang('moderator').'</span>';
	else return '<span class="label label-important">'.lang('administrator').'</span>';
}


/* Renvoie le nombre de nouveaux messages pour un membre */
function count_new_messages()
{
	$CI =& get_instance();
	return $CI->global_model->count_new_messages();
}


/* ADMINS - Compte les nouveaux signalements */
function count_new_reports()
{
	$CI =& get_instance();
	return $CI->global_model->count_new_reports();
}


/* Génère un sel pour un mot de passe */
function generate_salt() {
	$salt = '';
	for($i = mt_rand(16, 32); $i > 0; --$i)
		$salt.= chr(mt_rand(33, 126));
	return $salt;
}


/* End of file misc_helper.php */
/* Location: ./application/helpers/misc_helper.php */
