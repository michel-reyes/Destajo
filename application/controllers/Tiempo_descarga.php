<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tiempo_descarga extends CI_Controller {
	
	protected $modulo = array(
        'nombre' => "tiempo_descarga", 
        'display' => "Tiempo de descarga"
    );
	
	//-------------------------------------------------------------------------
	
	public function __construct()
    {
        parent::__construct();
        $this->users->can('TiempoDescarga.Ver', TRUE);
        
        $this->load->model('tiempo_descarga_m');
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
        $this->load->view('tiempo_descarga/layout_v', $data);
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
        $results = $this->tiempo_descarga_m->list_all($config['per_page'], $offset);
        
        // Datos necesarios para configuracion
        $data['total_rows_show_of'] = $results['num_rows_wo_limit'];
        $data['rows_to_show'] = $response['rows_to_show'] = $results['num_rows']; // paginacion mostrando X, cookie
        $data['all_rows'] = $this->tiempo_descarga_m->count_all();
        $config['base_url'] = base_url('tiempo_descarga/show_content');
        $config['total_rows'] = $data['total_rows_show_of'];
        $response['per_page'] = $config['per_page']; // cookie
        
        // ORDENAR
        $data['otype'] = ($this->input->get('otype') == "desc") ? "asc" : "desc";
        
        // Cargar vista de registros (side-col)
        $data['results'] = $results['rows'];
        $response['registros'] = $this->load->view('tiempo_descarga/tabla_v', $data, TRUE);
        
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
     * Eliminar
     * 
     * Se encarga de eliminar el elemento
     */
    
    public function eliminar()
    {		
        $response = array();
        
        if ($this->input->post('eliminar')) {
            $query = $this->tiempo_descarga_m->eliminar();
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
    
    //-------------------------------------------------------------------------
    
    /**
     * AutoCalc
     * 
     * Autocalcular tiempos de carga
     */
    
    public function autoCalc()
    {
        $this->users->can('TiempoDescarga.Calcula', TRUE);                
        $response = array();
        
        $query = $this->tiempo_descarga_m->autoCalc();
        
        if ($query === TRUE)
        {
            $response['status'] = "calc"; 
            $this->session->set_flashdata('success_redirect', 'Se ha calculado satisfactoriamente.');  
        }else{
            $response['status'] = "no_calc";
        }  
        
        echo json_encode($response);        
    }	
	
}
