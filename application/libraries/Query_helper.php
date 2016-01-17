<?php 

/*
DESTAJO-MODULE

date: 2014.03.09
type: hlp module
path: application/libraries/Query_helper.php

DESTAJO-MODULE-END
*/

class Query_helper {

	/**
	*	Comprobar que un campo sea KEY en una tabla
	*/
    public function is_key_field($field='', $table='')
    {
    	$CI =& get_instance();

    	$ddl = "";
    	$show = $CI->db->query("SHOW CREATE TABLE `destajo`.`" . $table . "`");

    	foreach ($show->result_array() as $key) {
    		$ddl = $key['Create Table'];
    	}

    	if (strpos($ddl, "KEY `" . $field . "`") !== false) return true; else return false;
    }

    //-------------------------------------------------------------------------

    /**
    *	Comrpnbar que un campos sea FOREIGN KEY en una tabla
    */
    public function is_foreign_field($field='', $table='')
    {
    	$CI =& get_instance();

    	$ddl = "";
    	$show = $CI->db->query("SHOW CREATE TABLE `destajo`.`" . $table . "`");

    	foreach ($show->result_array() as $key) {
    		$ddl = $key['Create Table'];
    	}

    	if (strpos($ddl, "FOREIGN KEY (`" . $field . "`)") !== false) return true; else return false;
    }

}

