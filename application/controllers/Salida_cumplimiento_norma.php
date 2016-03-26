<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Salida_cumplimiento_norma extends CI_Controller {
	
	protected $modulo = array(
        'nombre' => "salida_cumplimiento_norma", 
        'display' => "Cumplimiento de la norma"
    );
    
    protected $periodo_pago = array(
        'fipp' => "",
        'ffpp' => "",
        'ppa' => ""
    );
	
	//-------------------------------------------------------------------------
	
	public function __construct()
    {
        parent::__construct();
        $this->users->can('SalidaCumplimientoNorma.Ver', TRUE);        
        $this->load->helper('file');
        
        $this->load->model('periodo_pago_m');
        $pp = $this->periodo_pago_m->get_all();
        $this->periodo_pago['ppa'] = $pp->perioro_pago_abierto;
        $this->periodo_pago['fipp'] = $pp->fecha_inicio_periodo_pago;
        $this->periodo_pago['ffpp'] = $pp->fecha_final_periodo_pago;
        
        // Comprobar que el periodo de pago este abierto
        $this->periodo_pago_m->check_open($pp->perioro_pago_abierto);
        
        $this->load->model('salida_cumplimiento_norma_m');
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
        $this->load->view('salida_cumplimiento_norma/layout_v', $data);
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
        
        $data['fipp'] = $this->periodo_pago['fipp'];
        $data['ffpp'] = $this->periodo_pago['ffpp'];
        
        // Obtener registros
        $results = $this->salida_cumplimiento_norma_m->list_all($config['per_page'], $offset);
        
        // Datos necesarios para configuracion
        $data['total_rows_show_of'] = $results['num_rows_wo_limit'];
        $data['rows_to_show'] = $response['rows_to_show'] = $results['num_rows']; // paginacion mostrando X, cookie
        $data['all_rows'] = $this->salida_cumplimiento_norma_m->count_all();
        $config['base_url'] = base_url('salida_cumplimiento_norma/show_content');
        $config['total_rows'] = $data['total_rows_show_of'];
        $response['per_page'] = $config['per_page']; // cookie
        
        // ORDENAR
        $data['otype'] = ($this->input->get('otype') == "desc") ? "asc" : "desc";
        
        // Cargar vista de registros (side-col)
        // Obtener cumplimiento general de la norma
        $data['cgn'] = $this->salida_cumplimiento_norma_m->autoCal_cumplimiento_norma_general();
        $data['results'] = $results['rows'];
        $response['registros'] = $this->load->view('salida_cumplimiento_norma/tabla_v', $data, TRUE);
        
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
            $query = $this->salida_cumplimiento_norma_m->eliminar();
            if ( $query === TRUE) {
                $response['status'] = "eliminar_pass";
            }else{
                $response['status'] = "eliminar_error";
            }
        }
        
        echo json_encode($response);
    }
    
    //---------------------------------------------------------------------------
    
    /**
    * autoCalc
    * 
    * Autocalcular la salida del salario por equipo
    */
    public function autoCalc()
    {
        $this->users->can('SalidaSalarioEquipo.Calcular', TRUE);
                
        $response = array();
        
        $query = $this->salida_cumplimiento_norma_m->autoCalc();
        
        if ($query === TRUE)
        {
            $response['status'] = "calc";  
        }else{
            $response['status'] = "no_calc";
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
     * Exportar
     * 
     * Exporta la consulta actual (con o sin busquedas) a Excel, 
     * la consulta a exportar debe estar almacenada en un fichero
     * previamnete almacenado en el modelo
     */
    
    public function exportar()
    {
        // Obtener el query a exportar
        $sql = read_file('./query_temp/scn_query_temp.tmp');
        
        // hacer consulta con los datos almacenados en fichero
        $query = $this->db->query($sql);
                
        $map = array(
         'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //0-7
         'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', //8-15
         'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', //16-23
         'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF' //24-31
        );
                
        // Crear un objeto Excel       
        //load PHPExcel library
        
        $this->load->library('excel');
        // Activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        // Name the worksheet
        $this->excel->getActiveSheet()->setTitle('Salida_cumplimiento_norma');
        
        // Crear cabeceras
        $this->excel->getActiveSheet()->setCellValue('A1', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Cumplimiento de la norma');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Inicio del periodo de pago');
        $this->excel->getActiveSheet()->setCellValue('D1', 'Final del periodo de pago');
        
        // Hacer la letra negrita
        for ($i=0,$j = count($map); $i < $j; $i++) { 
            $this->excel->getActiveSheet()->getStyle("$map[$i]1")->getFont()->setBold(true);
        }
        
        $letra = 0;
        // Comenzar en la tercera linea
        $j = 2;
        foreach ($query->result() as $row) {
            
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->producto);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->cumplimiento_norma);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, date("d/m/Y", $row->fecha_inicio_periodo_pago));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, date("d/m/Y", $row->fecha_final_periodo_pago));
             
            $j ++;  
            $letra = 0;
        }
        
        // Crear el fichero excel y hacerlo descargable
        $date = date("d_m_y");
        $filename="Salida_cumplimiento_norma_$date.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
        
    }

    //-------------------------------------------------------------------------
    
    /**
     * Imprimir
     */
    
    public function imprimir()
    {
        // Obtener el query de la ultima consulta y realizar consulta
        $sql = read_file('./query_temp/scn_query_temp.tmp');
        $data['query'] = $this->db->query($sql);
        
        $data['modulo'] = $this->modulo;
        $data['fipp'] = $this->periodo_pago['fipp'];
        $data['ffpp'] = $this->periodo_pago['ffpp']; 
        
        $this->load->view('template/t_header');
        $this->load->view('salida_cumplimiento_norma/imprimir_v', $data);
        $this->load->view('template/t_footer');
        
    }
	
	
}
