<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Carga_descarga extends CI_Controller {
	
	protected $modulo = array(
        'nombre' => "carga_descarga", 
        'display' => "Carga descarga",
        'menu' => '4'
    );
	
	//-------------------------------------------------------------------------
	
	public function __construct()
    {
        parent::__construct();
        $this->users->can('CargaDescarga.Ver', TRUE);
        
        $this->load->model('carga_descarga_m');
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
        $this->load->view('carga_descarga/layout_v', $data);
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
        $results = $this->carga_descarga_m->list_all($config['per_page'], $offset);
        
        // Datos necesarios para configuracion
        $data['total_rows_show_of'] = $results['num_rows_wo_limit'];
        $data['rows_to_show'] = $response['rows_to_show'] = $results['num_rows']; // paginacion mostrando X, cookie
        $data['all_rows'] = $this->carga_descarga_m->count_all();
        $config['base_url'] = base_url('carga_descarga/show_content');
        $config['total_rows'] = $data['total_rows_show_of'];
        $response['per_page'] = $config['per_page']; // cookie
        
        // ORDENAR
        $data['otype'] = ($this->input->get('otype') == "desc") ? "asc" : "desc";
        
        // Cargar vista de registros (side-col)
        $data['results'] = $results['rows'];
        $response['registros'] = $this->load->view('carga_descarga/tabla_v', $data, TRUE);
        
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
        $this->users->can('CargaDescarga.Agregar', TRUE);
        
        $response = array();
        
        // Reglas de validacion
        $this->form_validation->set_rules('codigo', 'C&oacute;digo', 'trim|required|integer|is_unique[carga_descarga.codigo]');
        $this->form_validation->set_rules('fk_lugar_carga_id', 'Lugar de carga', 'trim|required');
        $this->form_validation->set_rules('fk_lugar_descarga_id', 'Lugar de descarga', 'trim|required');
        
        // Validar que al menos existan kilometros recorridos por una via
        $kms_pass = TRUE;
        $pu = $this->input->post('PU');
        $c = $this->input->post('C');
        $a = $this->input->post('A');
        $t = $this->input->post('T');
        $cm = $this->input->post('CM');
        $ct = $this->input->post('CT');
        $tm = $this->input->post('TM');
        $cv = $this->input->post('CV');
        
        if ( (!$pu OR $pu == "" OR $pu == 0.00) AND (!$c OR $c == "" OR $c == 0.00) AND
             (!$a OR $a == "" OR $a == 0.00) AND (!$t OR $t == "" OR $t == 0.00) AND
             (!$cm OR $cm == "" OR $cm == 0.00) AND (!$ct OR $ct == "" OR $ct == 0.00) AND 
             (!$tm OR $tm == "" OR $tm == 0.00) AND (!$cv OR $cv == "" OR $cv == 0.00)) {
             $kms_pass = FALSE;  
        }
        
        // Validacion fallo
        if ( $this->form_validation->run() == FALSE OR $kms_pass == FALSE ){
            $response = array(
                "status" => "validation_error",
                "campo" => array(),
                "error" => array()
            );
            
            // Enviando error kilometros recorridos
            if ($kms_pass == FALSE) {
               $response['falta_kms_recorridos']  = true;
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
        $this->users->can('CargaDescarga.Editar', TRUE);
           
        $data['modulo'] = $this->modulo;
        
        // Mostrar formulario
         if ( !$this->input->post('accion') || $this->input->post('accion') != "agregar" ){
             
             // Obtener lugares de carga
             $this->load->model('lugar_carga_m');
             $data['lista_lugares_carga'] = $this->lugar_carga_m->get_all();
             
             // Obtener lugares de descarga
             $this->load->model('lugar_descarga_m');
             $data['lista_lugares_descarga'] = $this->lugar_descarga_m->get_all();
             
             $this->load->view($this->modulo['nombre'] . '/agregar_v', $data);
         }
         // Agregar
         else{
             $response = array();
             $query = $this->carga_descarga_m->agregar();
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
        $data['modulo'] = $this->modulo;
        
        // Mostrar formulario
        if ( !$this->input->post('accion') || $this->input->post('accion') != "editar" ){
            
            // Obtener lugares de carga
            $this->load->model('lugar_carga_m');
            $data['lista_lugares_carga'] = $this->lugar_carga_m->get_all();

            // Obtener lugares de descarga
            $this->load->model('lugar_descarga_m');
            $data['lista_lugares_descarga'] = $this->lugar_descarga_m->get_all();
            
            $data['ibi'] = $this->carga_descarga_m->get_by_id($id);
            $this->load->view($this->modulo['nombre'] . '/editar_v', $data);
        }
        // Editar
        else {
            $response = array();
            $query = $this->carga_descarga_m->editar( $this->input->post('id') );
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
        $this->users->can('CargaDescarga.Eliminar', TRUE);
        		
        $response = array();
        
        if ($this->input->post('eliminar')) {
            $query = $this->carga_descarga_m->eliminar();
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
