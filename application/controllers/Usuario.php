<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Usuario extends CI_Controller {
	
	protected $modulo = array(
        'nombre' => "usuario", 
        'display' => "Usuario"
    );
	
	//-------------------------------------------------------------------------
	
	public function __construct()
    {
        parent::__construct();
        $this->users->can('Usuario.Ver', TRUE);
        
        $this->load->library('encrypt');
        $this->load->model('usuario_m');
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
        $this->load->view('usuario/layout_v', $data);
        $this->load->view('template/t_footer');
	}
	
	//-------------------------------------------------------------------------
	
	/**
	 * show_content
	 * 
	 * @param int $offset 
	 * @return string json_encode
	 */
	
	public function show_content($offset=0)
	{
		$data['modulo'] = $this->modulo;
        $response = array();
        $config['per_page'] = 50;
        
        // Obtener registros
        $results = $this->usuario_m->list_all($config['per_page'], $offset);
        
        // Datos necesarios para configuracion
        $data['total_rows_show_of'] = $results['num_rows_wo_limit'];
        $data['rows_to_show'] = $response['rows_to_show'] = $results['num_rows']; // paginacion mostrando X, cookie
        $data['all_rows'] = $this->usuario_m->count_all();
        $config['base_url'] = base_url('usuario/show_content');
        $config['total_rows'] = $data['total_rows_show_of'];
        $response['per_page'] = $config['per_page']; // cookie
        
        // ORDENAR
        $data['otype'] = ($this->input->get('otype') == "desc") ? "asc" : "desc";
        
        // Cargar vista de registros (side-col)
        $data['results'] = $results['rows'];
        $response['registros'] = $this->load->view('usuario/tabla_v', $data, TRUE);
        
        // Cargar vista de paginacion y almacenar los valores devueltos para
        // guardarlos en cookie functions.js->load_content
        $this->pagination->initialize($config);
        $response['paginacion'] = $this->load->view('template/t_paginacion', $data, TRUE);
        $response['cur_page'] = $this->pagination->output['cur_page'];
        $response['num_pages'] = $this->pagination->output['num_pages'];
        
        echo json_encode($response);
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
        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('apellidos', 'Apellidos', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[150]|valid_email');
        $this->form_validation->set_rules('nombre_login', 'Nombre de login', 'trim|required|max_length[10]|min_length[4]|is_unique[usuario.nombre_login]');
        if (($this->input->post('accion') != "editar") OR ($this->input->post('accion') == "editar" AND $this->input->post('password_login') != "")){
            $this->form_validation->set_rules('password_login', 'Password', 'trim|required|min_length[6]|max_length[16]|matches[confirm_password]|md5');
            $this->form_validation->set_rules('confirm_password', 'Confirmar password', 'trim|required');
        }
        $this->form_validation->set_rules('fk_empresa_id', 'Empresa', 'trim|required');
        $this->form_validation->set_rules('fk_perfil_id', 'Perfil', 'trim|required');
        
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
     * Agregar
     * 
     * Se encarga de mostrar la vista para agregar
     * Se encarga de agregar el item del modulo
     */
     
    public function agregar()
    {
        $this->users->can('Usuario.Agregar', TRUE);		
        $data['modulo'] = $this->modulo;
        
        // Mostrar formulario
         if ( !$this->input->post('accion') || $this->input->post('accion') != "agregar" ){
             
             // Obtener lista de empresas
             $this->load->model('empresa_m');
             $data['lista_empresas'] = $this->empresa_m->get_all();
             
             // Obtener lista de perfiles
             $this->load->model('perfil_m');
             $data['lista_perfiles'] = $this->perfil_m->get_all();
             
             $this->load->view($this->modulo['nombre'] . '/agregar_v', $data);
         }
         // Agregar
         else{
             $response = array();
             $query = $this->usuario_m->agregar();
             if ( ($this->db->affected_rows() >= 1) AND $query === TRUE ){
                 $response['status'] = "agregar_pass";
             }
             else {
                 $response['status'] = "agregar_error";
             }
             echo json_encode($response);
         }
    }
	
	//-------------------------------------------------------------------------
    /**
     * Editar
     * 
     * Se encarga de mostrar la vista para editar
     * Se encarga de editar el item del modulo
     */
     
    public function editar($id='')
    {
        $this->users->can('Usuario.Editar', TRUE);		
        $data['modulo'] = $this->modulo;
        
        // Mostrar formulario
        if ( !$this->input->post('accion') || $this->input->post('accion') != "editar" ){
            
            // Obtener lista de empresas
            $this->load->model('empresa_m');
            $data['lista_empresas'] = $this->empresa_m->get_all();
             
            // Obtener lista de perfiles
            $this->load->model('perfil_m');
            $data['lista_perfiles'] = $this->perfil_m->get_all();
            
            $data['ibi'] = $this->usuario_m->get_by_id($id);
            $this->load->view($this->modulo['nombre'] . '/editar_v', $data);
        }
        // Editar
        else {
            $response = array();
            $query = $this->usuario_m->editar( $this->input->post('id') );
            if ( ($this->db->affected_rows() >= 1) OR $query === TRUE ){
                $response['status'] = "editar_pass";
            }
            else {
                $response['status'] = "editar_error";
            }
            echo json_encode($response);
        }     
    }
	
	//-------------------------------------------------------------------------
    
    /**
     * Eliminar
     * 
     * Se encarga de eliminar el elemento
     */
    
    public function eliminar()
    {
        $this->users->can('Usuario.Eliminar', TRUE);		
        $response = array();
        
        if ($this->input->post('eliminar')) {
            $query = $this->usuario_m->eliminar();
            if ( $query === TRUE) {
                $response['status'] = "eliminar_pass";
            }else{
                $response['status'] = "eliminar_error";
            }
        }
        
        echo json_encode($response);
    }
    
    
    //-------------------------------------------------------------------------
    
    /**
     * Buscar
     * 
     * Se encarga de mostrar la vista para buscar
     */
     
    public function buscar()
    {       
        $data['modulo'] = $this->modulo;
        $this->load->view($this->modulo['nombre'] . '/search_v', $data);            
    }
	
	
}
