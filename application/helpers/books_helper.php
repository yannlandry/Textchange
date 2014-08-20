<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/* Valide un ISBN (10 ou 13) */
function valid_isbn($isbn)
{
	$isbn = strip_isbn($isbn);
	
	if(strlen($isbn) == 10) {
		return $isbn[9] == calculate_check_digit($isbn);
	}
	
	elseif(strlen($isbn) == 13) {
		if( !in_array(substr($isbn, 0, 3), array('978', '979')) ) return false;
		return $isbn[12] == calculate_check_digit($isbn);
	}
	
	else return false;
}


/* Calculer le check digit d'un ISBN */
function calculate_check_digit($isbn)
{
	$isbn = strip_isbn($isbn);
	$len = strlen($isbn);
	
	if($len == 9 || $len == 10) {
		$mod =
		(	intval($isbn[0]) * 10
		+	intval($isbn[1]) * 9
		+	intval($isbn[2]) * 8
		+	intval($isbn[3]) * 7
		+	intval($isbn[4]) * 6
		+	intval($isbn[5]) * 5
		+	intval($isbn[6]) * 4
		+	intval($isbn[7]) * 3
		+	intval($isbn[8]) * 2
		) % 11;
		
		if($mod == 0) return 0;
		else $mod = 11 - $mod;
		
		if($mod == 10) return 'X';
		else return $mod;
	}
	
	elseif($len == 12 || $len == 13) {
		$mod =
		(	intval($isbn[0])
		+	intval($isbn[1]) * 3
		+	intval($isbn[2])
		+	intval($isbn[3]) * 3
		+	intval($isbn[4])
		+	intval($isbn[5]) * 3
		+	intval($isbn[6])
		+	intval($isbn[7]) * 3
		+	intval($isbn[8])
		+	intval($isbn[9]) * 3
		+	intval($isbn[10])
		+	intval($isbn[11]) * 3
		) % 10;
		
		if($mod != 0) $mod = 10 - $mod;
		return $mod;
	}
	
	else return '0';
}


/* Convertit un ISBN-10 en ISBN-13 */
function convert_isbn($isbn)
{
	$isbn = strip_isbn($isbn);
	if(strlen($isbn) == 13) return $isbn;
	if(strlen($isbn) != 10 || !valid_isbn($isbn)) return false;
	return '978'.substr($isbn, 0, 9).calculate_check_digit('978'.$isbn);
}


/* Enlève les espaces et les tirets d'un ISBN */
function strip_isbn($isbn)
{
	return str_replace( array('-', ' '), array('', ''), trim((string)$isbn) );
}


/* Convertit un prix entré en float */
function input_price($input)
{
	$input = round(floatval(str_replace(lang('price_decimal'),'.',$input)), 2);
	
	if($input == 0) return false;
	else return $input;
}


/* Convertir un float en prix affichable */
function output_price($output, $includeSign = true)
{
	$output = (string)round(floatval($output), 2);
	
	# Padding de zéros
	$pos = strpos($output, '.');
	if($pos === FALSE) $output.= '.00';
	elseif(strlen($output) - $pos == 2) $output.= '0';
	
	$output = str_replace('.', lang('price_decimal'), $output);
	
	if($includeSign) return str_replace('{P}', $output, lang('price_format'));
	else return $output;
}


/* End of file books_helper.php */
/* Location: ./application/helpers/books_helper.php */