<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mantenimiento_m extends CI_Model {
	
    /**
     * Optimizar tablas
     * 
     * Validar el formulario
     */
     
    public function optimmizar_tablas()
    {
        $action = array();
        
        // Obtener tablas que el usuaio desea optimizar
        $tablas = $this->input->post('tablas');
        
        foreach ($tablas as $tabla) {
            if ($this->dbutil->optimize_table('table_name'))
            {
                $action['status'] = "optimizado";
            }else{
                $action['status'] = "no_optimizado";
                $action['tablas'][$tabla];
            }
        }
        
        return $action;
    }
}   