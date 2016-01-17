<?php 
/**
 * Funcion para formatear los numeros que se almacenaran n al abase de datos
 * estos numeros estan siendo tratados con el plugin (JS) autoNumeric.js
 * de esta forma los numeros se comportan como string los cuales no son accesibles
 * por mysql, por lo que hay que tratarlos
 */
function number_to_mysql($number='')
{
	if ($number == '') return 0.00;
	
	return str_replace(',', '.', str_replace('.', '', $number));
}

//-----------------------------------------------------------------------------

/**
 * Funcion para formatear los numeros de mysql a numeros humanos
 */
function mysql_to_number($number='', $decimal=2)
{
    if ($number == '') return '0,00';
     
    return number_format($number, $decimal, ',', '.');
}

