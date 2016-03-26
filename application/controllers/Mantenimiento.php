<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Mantenimiento extends CI_Controller {  
    
    public function __construct()
    {
        parent::__construct();
        $this->load->dbutil();      
        $this->load->model('mantenimiento_m');
    }   
    
    //-------------------------------------------------------------------------
    
    /**
     * Optimizar tablas
     * 
     * Validar el formulario
     */
     
    public function optimmizar_tablas()
    {
        // Mostrar formulario
        if (! $this->input->post('optimizar')) {
            $this->load->view('mantenimiento/optimizar_v');
        }else{
            $response = array();
            $response = $this->mantenimiento_m->optimmizar_tablas();
            echo json_encode($response);
        }         
    }
    
}
