<?php

/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/helpers/MY_date_helper.php

DESTAJO-MODULE-END
*/

// ------------------------------------------------------------------------

/**
 * Convierte fechas spanish date (00-00-0000) a mysql (0000-00-00)
 *
 * @access	public
 * @param	date (dd-mm-yyyy)
 * @return	date (yyyy-mm-dd)
 */
if ( ! function_exists('to_sql'))
{
	function to_sql($date)
	{
		if (empty($date)) return NULL;
        
        list($dia, $mes, $year) = explode("/", $date);
		
		// YYYY-MM-DD
		return $year . '-' . $mes . '-' . $dia;
	}
}

// ------------------------------------------------------------------------

/**
 * Convierte fechas mysql (0000-00-00) a spanish date (00-00-0000)
 *
 * @access	public
 * @param	date (yyyy-mm-dd)
 * @return date (dd/mm/yyyy)
 */
if ( ! function_exists('to_date'))
{
	function to_date($date)
	{
		if (empty($date)) return NULL;
        
        list($year, $mes, $dia) = explode("-", $date);
        
        // DD/MM/YYYY
        return $dia . '/' . $mes . '/' . $year;
	}
}