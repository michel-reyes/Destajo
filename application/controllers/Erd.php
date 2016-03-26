<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Erd extends CI_Controller {
	
	protected $modulo = array(
        'nombre' => "erd", 
        'display' => "Vista relaciones"
    );
	
	//-------------------------------------------------------------------------
	
	public function __construct()
    {
        parent::__construct();
        ;
		// Almacenar nombre del modulo en cookie para poder acceder por jquery
		$cookie = array(
		    'name'   => 'modulo',
		    'value'  => $this->modulo['nombre'],
		    'expire' => '86500',
		);		
		$this->input->set_cookie($cookie);
    }
	
	//-------------------------------------------------------------------------
	
	/**
	 * Index
	 * 
	 * Cargar los modulos principales
	 * @return object
	 */
	 
	public function index()
	{
	    $data['modulo'] = $this->modulo;
        
        $this->load->view('template/t_header');
        $this->load->view('template/t_main_menu', $data);
        $this->load->view('erd/layout_v', $data);
        $this->load->view('template/t_footer');
	}
	
}
