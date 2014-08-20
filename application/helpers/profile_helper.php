<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/* Formate un numéro de téléphone */
function format_phone($phone)
{
	return '('.substr($phone, 0, 3).') '.substr($phone, 3, 3).'-'.substr($phone, 6, 4);
}


/* End of file profile_helper.php */
/* Location: ./application/helpers/profile_helper.php */