<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/controllers/capacidad_carga.php

DESTAJO-MODULE-END
*/

class Capacidad_carga extends CI_Controller {
	
	protected $modulo = array(
        'nombre' => "capacidad_carga", 
        'display' => "Capacidad de carga",
        'menu' => '3'
    );
	
	//-------------------------------------------------------------------------
	
	public function __construct()
    {
        
        parent::__construct();
        $this->users->can('CapacidadCarga.Ver', TRUE);
        
        $this->load->model('capacidad_carga_m');
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
        $this->load->view('capacidad_carga/layout_v', $data);
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
        $results = $this->capacidad_carga_m->list_all($config['per_page'], $offset);
        
        // Datos necesarios para configuracion        
        $data['rows_to_show'] = $response['rows_to_show'] = $results['num_rows']; // paginacion mostrando X, cookie
        $data['all_rows'] = $this->capacidad_carga_m->count_all();
        $config['base_url'] = base_url('capacidad_carga/show_content');        
        $response['per_page'] = $config['per_page']; // cookie
        
        // ORDENAR
        $data['otype'] = ($this->input->get('otype') == "desc") ? "asc" : "desc";
        
        // Cargar vista de registros (side-col)
        $data['results'] = $results['rows'];
        $response['registros'] = $this->load->view('capacidad_carga/tabla_v', $data, TRUE);
        
        // Cargar vista de paginacion y almacenar los valores devueltos para
        // guardarlos en cookie functions.js->load_content
        $results_p = $this->capacidad_carga_m->list_all('', '');
        $config['total_rows'] = $data['total_rows_show_of'] = $results_p['num_rows_wo_limit'];
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
        $this->form_validation->set_rules('fk_equipo_id', 'N&uacute;mero operacional del equipo', 'trim|required|xss_clean');
		if ($this->input->post('viajes_promedio'))
		{
			$this->form_validation->set_rules('viajes_promedio', 'Viajes promedio', 'trim|required|xss_clean|integer');
		}
		if ($this->input->post('entregas_promedio'))
		{
			$this->form_validation->set_rules('entregas_promedio', 'Entregas promedio', 'trim|required|xss_clean|integer');
		}        
        $this->form_validation->set_rules('capacidad_carga', 'Capacidad de carga', 'trim|required|xss_clean|integer');
        $this->form_validation->set_rules('tipo_de_producto', 'Tipo de producto', 'trim|required|xss_clean');
                
        // Validar que al menos exista una capacidad de bombeo (para la descarga)
        $capacidad_bombeo_pass = NULL;
        $capacidad_bombeo = $this->input->post('capacidad_bombeo');
        if ($capacidad_bombeo) {
            $capacidad_bombeo_pass = FALSE;
            foreach ($capacidad_bombeo as $key => $value) {
                if ($value != "" && $value != 0.00) {
                    $capacidad_bombeo_pass = TRUE;
                    break;   
                }            
            }    
        }
        
        // Validacion fallo
        if ( $this->form_validation->run() == FALSE OR $capacidad_bombeo_pass === FALSE){
            $response = array(
                "status" => "validation_error",
                "campo" => array(),
                "error" => array()
            );
            
            // Enviando error de capacidad de bombeo
            if ($capacidad_bombeo_pass === FALSE) {
               $response['falta_capacidad_bombeo']  = true;
            }
            
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
        $this->users->can('CapacidadCarga.Agregar', TRUE);
        		
        $data['modulo'] = $this->modulo;
        
        // Mostrar formulario
         if ( !$this->input->post('accion') || $this->input->post('accion') != "agregar" ){
             
             // Obtener equipos
             $this->load->model('equipo_m');
             $data['lista_equipos'] = $this->equipo_m->get_all();
             
             // Obtener cuñas
             $this->load->model('cuna_m');
             $data['lista_cunas'] = $this->cuna_m->get_all();
             
             // Obtener modos de descarga
             $this->load->model('modo_descarga_m');
             $data['lista_modo_descarga'] = $this->modo_descarga_m->get_all();
             
             $this->load->view($this->modulo['nombre'] . '/agregar_v', $data);
         }
         // Agregar
         else{
             $response = array();
             $query = $this->capacidad_carga_m->agregar();
             if ( $query === TRUE ){
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
        $this->users->can('CapacidadCarga.Editar', TRUE);
           
        $data['modulo'] = $this->modulo;
        
        // Mostrar formulario
        if ( !$this->input->post('accion') || $this->input->post('accion') != "editar" ){
            
            // Obtener equipos
            $this->load->model('equipo_m');
            $data['lista_equipos'] = $this->equipo_m->get_all();
             
            // Obtener cuñas
            $this->load->model('cuna_m');
            $data['lista_cunas'] = $this->cuna_m->get_all();
            
            // Obtener modos de descarga
            $this->load->model('modo_descarga_m');
            $data['lista_modo_descarga'] = $this->modo_descarga_m->get_all();             
             
            $data['ibi'] = $this->capacidad_carga_m->get_by_id($id);
            
            // Obtener capacidad de bombeo por capacidad de carga
            $data['capacidades_bombeo'] = $this->capacidad_carga_m->get_cb_by_cc($data['ibi']->m_capacidad_carga_id);
            
            $this->load->view($this->modulo['nombre'] . '/editar_v', $data);
        }
        // Editar
        else {
            $response = array();
            $query = $this->capacidad_carga_m->editar( $this->input->post('id') );
            if ( $query === TRUE ){
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
        $this->users->can('CapacidadCarga.Eliminar', TRUE);
        		
        $response = array();
        
        if ($this->input->post('eliminar')) {
            $query = $this->capacidad_carga_m->eliminar();
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
