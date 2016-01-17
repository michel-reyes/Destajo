<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Operario extends CI_Controller {
	
	protected $modulo = array(
        'nombre' => "operario", 
        'display' => "Operario"
    );
	
	//-------------------------------------------------------------------------
	
	public function __construct()
    {
        parent::__construct();
        $this->users->can('Operario.Ver', TRUE);
        
        $this->load->model('operario_m');
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
        $this->load->view('operario/layout_v', $data);
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
        $results = $this->operario_m->list_all($config['per_page'], $offset);
        
        // Datos necesarios para configuracion
        $data['total_rows_show_of'] = $results['num_rows_wo_limit'];
        $data['rows_to_show'] = $response['rows_to_show'] = $results['num_rows']; // paginacion mostrando X, cookie
        $data['all_rows'] = $this->operario_m->count_all();
        $config['base_url'] = base_url('operario/show_content');
        $config['total_rows'] = $data['total_rows_show_of'];
        $response['per_page'] = $config['per_page']; // cookie
        
        // ORDENAR
        $data['otype'] = ($this->input->get('otype') == "desc") ? "asc" : "desc";
        
        // Cargar vista de registros (side-col)
        $data['results'] = $results['rows'];
        $response['registros'] = $this->load->view('operario/tabla_v', $data, TRUE);
        
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
        $this->form_validation->set_rules('chapa', 'Chapa', 'trim|required|xss_clean|max_length[5]|is_unique[m_operario.chapa]');
        $this->form_validation->set_rules('ci', 'Carn&eacute; de identidad', 'trim|required|xss_clean|min_length[11]|max_length[11]');
        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|xss_clean|max_length[100]');
        $this->form_validation->set_rules('apellidos', 'Apellidos', 'trim|required|xss_clean|max_length[200]');
        $this->form_validation->set_rules('fk_categoria_operario_id', 'Categor&iacute;a del operario', 'trim|required|xss_clean');
        
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
        $this->users->can('Operario.Agregar', TRUE);		
        $data['modulo'] = $this->modulo;
        
        // Mostrar formulario
         if ( !$this->input->post('accion') || $this->input->post('accion') != "agregar" ){
             
             // Obtener categorias
             $this->load->model('categoria_operario_m');
             $data['lista_categoria_operario'] = $this->categoria_operario_m->get_all();
             
             $this->load->view($this->modulo['nombre'] . '/agregar_v', $data);
         }
         // Agregar
         else{
             $response = array();
             $query = $this->operario_m->agregar();
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
        $this->users->can('Operario.Editar', TRUE);		
        $data['modulo'] = $this->modulo;
        
        // Mostrar formulario
        if ( !$this->input->post('accion') || $this->input->post('accion') != "editar" ){
            
            // Obtener categorias
            $this->load->model('categoria_operario_m');
            $data['lista_categoria_operario'] = $this->categoria_operario_m->get_all();
             
            $data['ibi'] = $this->operario_m->get_by_id($id);
            $this->load->view($this->modulo['nombre'] . '/editar_v', $data);
        }
        // Editar
        else {
            $response = array();
            $query = $this->operario_m->editar( $this->input->post('id') );
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
        $this->users->can('Operario.Eliminar', TRUE);		
        $response = array();
        
        if ($this->input->post('eliminar')) {
            $query = $this->operario_m->eliminar();
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
