<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
DESTAJO-MODULE

date: 2014.12.17
type: php module
path: application/controllers/auth.php

DESTAJO-MODULE-END
*/


class Auth extends CI_Controller {
	
	protected $modulo = array(
        'nombre' => "auth", 
        'display' => "Autenticaci&oacute;n"
    );
	
	//-------------------------------------------------------------------------
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('encrypt');
        $this->load->model('auth_m');
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

		// Eliminar datos de instalacion if
		if ($this->session->userdata('done') && $this->session->userdata('done') == "ok")
		{
        $this->load->helper('file');
        delete_files('./application/views/install/',TRUE);
        rmdir('./application/views/install/');
        unlink('./application/controllers/install.php');
		}

		$data['modulo'] = $this->modulo;
		
		$this->load->view('template/t_header');
        //$this->load->view('template/t_main_menu', $data);
		$this->load->view('auth/login_v', $data);
		$this->load->view('template/t_footer');	
	}
	
	//-------------------------------------------------------------------------	
    
    /**
     * Validar
     * 
     * Validar el formulario
     */
     
    public function validar()
    {
        $response = array();
        
        // Reglas de validacion
        $this->form_validation->set_rules('nombre_login', 'Login', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password_login', 'Password', 'trim|required|xss_clean');
        
        // Validacion fallo
        if ( $this->form_validation->run() == FALSE ){
            $response = array(
                "status" => "validation_error",
                "campo" => array(),
                "error" => array()
            );
            $fields = $this->form_validation->_field_data;
            foreach ($fields as $key => $value) {
                if (strlen($fields[$key]['error']) > 0 ){
                    $response['campo'][] = $fields[$key]['field'];
                    $response['error'][] = $fields[$key]['error'];
                }               
            }
        }       
        // Validacion paso
        else{
            $response["status"] = 'validation_pass';
        }
        
        echo json_encode($response);
    }
	
	//-------------------------------------------------------------------------
	
	/**
     * Login
     * 
     * Comprueba que los datos del usuario sean los correctos, de ser asi:
	 * Almacena sus datos en cookies
     * @return array
     */
	
	public function login()
	{	       
	    $response = array();
	    $query = $this->auth_m->login();
		if ($query->num_rows() <= 0)
		{
			$response['status'] = "login_error";
		}
		else
		{
			// Almacenar los datos del usuario en cookies
			
			$query = $query->row();
			$this->session->set_userdata('usuario_id', $query->usuario_id);
            $this->session->set_userdata('nombre', $query->nombre . ' ' . $query->apellidos );
            $this->session->set_userdata('email', $query->email);            
			$this->session->set_userdata('nombre_login', $query->nombre_login);
			$this->session->set_userdata('empresa_id', $query->empresa_id);
			$this->session->set_userdata('empresa', $query->empresa);			
			$this->session->set_userdata('perfil_id', $query->perfil_id);
			$this->session->set_userdata('perfil', $query->perfil);
			$response['status'] = "login_pass";
		}
		
		echo json_encode($response);
         		
	}
	
	//-------------------------------------------------------------------------
	
	/**
     * LogOut
     * 
     * Comprueba que el usuario este logeado, de ser asi:
	 * Borra sus datos en cookies
     * @return array
     */
	
	public function logOut()
	{       
	    if ($this->session->userdata('nombre_login')) {
	    	// Borrar los datos del usuario en cookies
			$this->session->unset_userdata('usuario_id');
            $this->session->unset_userdata('nombre');
            $this->session->unset_userdata('email');            
            $this->session->unset_userdata('nombre_login');
            $this->session->unset_userdata('empresa_id');
            $this->session->unset_userdata('empresa');           
            $this->session->unset_userdata('perfil_id');
            $this->session->unset_userdata('perfil');
			
			$this->session->sess_destroy();
	    }
		
		 redirect('entrada');        		
	}
	
}
