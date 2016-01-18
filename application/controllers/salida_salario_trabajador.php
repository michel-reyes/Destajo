<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
DESTAJO-MODULE

date: 2014.12.19
type: php module
path: application/controllers/salida_salario_trabajador.php

DESTAJO-MODULE-END
*/

class Salida_salario_trabajador extends CI_Controller {
	
	protected $modulo = array(
        'nombre' => "salida_salario_trabajador", 
        'display' => "Salario del trabajador"
    );
    
    protected $periodo_pago = array(
        'fipp' => "",
        'ffpp' => "",
        'ppa' => "",
        'fh' => ""
    );
	
	//-------------------------------------------------------------------------
	
	public function __construct()
    {
        parent::__construct();
        $this->users->can('SalidaSalarioTrabajador.Ver', TRUE); 
        $this->load->helper('file');
               
        $this->load->model('periodo_pago_m');
        $pp = $this->periodo_pago_m->get_all();
        $this->periodo_pago['ppa'] = $pp->perioro_pago_abierto;
        $this->periodo_pago['fipp'] = $pp->fecha_inicio_periodo_pago;
        $this->periodo_pago['ffpp'] = $pp->fecha_final_periodo_pago;
        //$this->periodo_pago['fh'] = $pp->fondo_horario;
        $this->load->library('math');
        
        // Comprobar que el periodo de pago este abierto
        $this->periodo_pago_m->check_open($pp->perioro_pago_abierto);
        
        $this->load->model('salida_salario_trabajador_m');
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
        $this->load->view('salida_salario_trabajador/layout_v', $data);
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
        $results = $this->salida_salario_trabajador_m->list_all($config['per_page'], $offset);
        
        // Datos necesarios para configuracion
        $data['total_rows_show_of'] = $results['num_rows_wo_limit'];
        $data['rows_to_show'] = $response['rows_to_show'] = $results['num_rows']; // paginacion mostrando X, cookie
        $data['all_rows'] = $this->salida_salario_trabajador_m->count_all();
        $config['base_url'] = base_url('salida_salario_trabajador/show_content');
        $config['total_rows'] = $data['total_rows_show_of'];
        $response['per_page'] = $config['per_page']; // cookie
        
        // ORDENAR
        $data['otype'] = ($this->input->get('otype') == "desc") ? "asc" : "desc";
        
        // Cargar vista de registros (side-col)
        $data['results'] = $results['rows'];
        $response['registros'] = $this->load->view('salida_salario_trabajador/tabla_v', $data, TRUE);
        
        // Cargar vista de paginacion y almacenar los valores devueltos para
        // guardarlos en cookie functions.js->load_content
        $this->pagination->initialize($config);
        $response['paginacion'] = $this->load->view('template/t_paginacion', $data, TRUE);
        $response['cur_page'] = $this->pagination->output['cur_page'];
        $response['num_pages'] = $this->pagination->output['num_pages'];
        
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
        $this->users->can('SalidaSalarioTrabajador.Calcular', TRUE);        
        $response = array();
        
        $query = $this->salida_salario_trabajador_m->autoCalc();
        
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
     * Eliminar
     * 
     * Se encarga de eliminar el elemento
     */
    
    public function eliminar()
    {       
        $response = array();
        
        if ($this->input->post('eliminar')) {
            $query = $this->salida_salario_trabajador_m->eliminar();
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
     * Exportar
     * 
     * Exporta la consulta actual (con o sin busquedas) a Excel, 
     * la consulta a exportar debe estar almacenada en un fichero
     * previamnete almacenado en el modelo
     */
    
    public function exportar()
    {
        // Obtener el query a exportar
        $sql = read_file('./query_temp/sst_query_temp.tmp');
        
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
        $this->excel->getActiveSheet()->setTitle('Salida_salario_trabajador');
        
        // Crear cabeceras
        $this->excel->getActiveSheet()->setCellValue('A1', 'Chapa');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Nombre y apellidos');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Importe del viaje');
        $this->excel->getActiveSheet()->setCellValue('D1', 'Cumplimiento de la norma');
        $this->excel->getActiveSheet()->setCellValue('E1', 'Viaje');
        $this->excel->getActiveSheet()->setCellValue('F1', 'Interrupto');
        $this->excel->getActiveSheet()->setCellValue('G1', 'No vinculado');
        $this->excel->getActiveSheet()->setCellValue('H1', 'Nocturnidad corta');
        $this->excel->getActiveSheet()->setCellValue('I1', 'Nocturnidad larga');
        $this->excel->getActiveSheet()->setCellValue('J1', html_entity_decode('Capacitaci&oacute;n'));
        $this->excel->getActiveSheet()->setCellValue('K1', 'Movilizado');
        $this->excel->getActiveSheet()->setCellValue('L1', 'Feriado');
        $this->excel->getActiveSheet()->setCellValue('M1', 'Ausencia');
        $this->excel->getActiveSheet()->setCellValue('N1', 'Inicio del periodo de pago');
        $this->excel->getActiveSheet()->setCellValue('O1', 'Final del periodo de pago');
        
        // Hacer la letra negrita
        for ($i=0,$j = count($map); $i < $j; $i++) { 
            $this->excel->getActiveSheet()->getStyle("$map[$i]1")->getFont()->setBold(true);
        }
        
        $letra = 0;
        // Comenzar en la segunda linea
        $j = 2;
        foreach ($query->result() as $row) {
            
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->chapa);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->nombre.' '.$row->apellidos);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->importe_viaje));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->cumplimiento_norma));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->horas_viaje));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->horas_interrupto));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->horas_no_vinculado));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->horas_nocturnidad_corta));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->horas_nocturnidad_larga));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->horas_capacitacion));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->horas_movilizado));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->horas_feriado));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->horas_ausencia));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, date("d/m/Y", $row->fecha_inicio_periodo_pago));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, date("d/m/Y", $row->fecha_final_periodo_pago));
             
            $j ++;  
            $letra = 0;
        }
        
        // Crear el fichero excel y hacerlo descargable
        $date = date("d_m_y");
        $filename="Salida_salario_trabajador_$date.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
        
    }

    // ------------------------------------------------------------------------

    /*
     * XML SISCONT 
     * Exportar un XML con el formato requerido por la app SISCONT 
     * Este XML resultante se almacenara en la PC y sera importado por SISCONT
     */

    public function xmlSiscont()
    {   
        // Cargar la libreria de matematica para reutilizar funciones
        $this->load->library('math'); 

        // Obtener datos de la tabla salida salario trabajador
        $incidencia = $this->salida_salario_trabajador_m->get_all_period();

        // Obtener los valores de las claves del siscont
        $this->load->model('claves_siscont_m');
        // clave de vinculacion
        $cev = $this->claves_siscont_m->getClaveBySigla('CEV');

        // clave de entrada nocturnidad corta
        $cenc = $this->claves_siscont_m->getClaveBySigla('CENC');

        // Fondo horario
        $fondo_horario = 1;//$this->periodo_pago['fh'];

        $newline = "\n"; 
        $tab = "\t";

        $xml  = '<?xml version="1.0" encoding="ISO-8859-1"?>'.$newline;
        $xml .= $tab.'<INCIDENCIAS>'.$newline;


        foreach ($incidencia->result() as $i) 
        {

            // obtener tarifa completa del operario
            $tarifa_completa = $this->math->L($this->math->S($i->chapa))->tarifa_completa;


            // ajustar tiempo de trabajo TiemTiepo 2
            // ajuste:
            // si tiemtipo1 + tiemtipo2 > fondo horario entonces tiemtipo2 = fondo horario
            $horas_viaje = ( ($i->horas_viaje + $i->horas_viaje_m) >  $fondo_horario) ? $fondo_horario : $i->horas_viaje + $i->horas_viaje_m;

            // cuantia horaria nocturnidad corta
            $CHNC = $this->math->G('CHNC');

            // cuantia horaria nocturnidad larga
            $CHNL = $this->math->G('CHNL');
            
            $j = 1;
            $xml .= $tab.$tab.'<INCIDENCIA>'.$newline;

            $xml .= $tab.$tab.$tab.'<Identidad>'.$i->ci.'</Identidad>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemTipo>1</TiemTipo>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemConsec>1</TiemConsec>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemTrab>'.$i->horas_no_vinculado.'</TiemTrab>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemTarifa>'.$tarifa_completa.'</TiemTarifa>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemTipo>2</TiemTipo>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemConsec>2</TiemConsec>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemClave>'.$cenc.'</TiemClave>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemTrab>'.$horas_viaje.'</TiemTrab>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemImporte>'.($i->importe_viaje+$i->importe_viaje_m).'</TiemImporte>'.$newline;
            // nocturnidad corta
            $xml .= $tab.$tab.$tab.'<TiemTipo>2</TiemTipo>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemConsec>3</TiemConsec>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemClave>'.$cev.'</TiemClave>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemTrab>'.$i->horas_nocturnidad_corta.'</TiemTrab>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemTarifa>'.$CHNC.'</TiemTarifa>'.$newline;
            // nocturnidad larga
            $xml .= $tab.$tab.$tab.'<TiemTipo>2</TiemTipo>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemConsec>4</TiemConsec>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemClave>'.$cev.'</TiemClave>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemTrab>'.$i->horas_nocturnidad_larga.'</TiemTrab>'.$newline;
            $xml .= $tab.$tab.$tab.'<TiemTarifa>'.$CHNL.'</TiemTarifa>'.$newline;

            $xml .= $tab.$tab.'</INCIDENCIA>'.$newline;
            $j++;
        }

        $xml .= $tab.'</INCIDENCIAS>'.$newline;

        // Cargar la libreria para hacer descargas
        $this->load->helper('download');
        force_download('siscont.xml', $xml);

    }

    //-------------------------------------------------------------------------
    
    /**
     * Imprimir
     */
    
    public function imprimir()
    {
        // Obtener el query de la ultima consulta y realizar consulta
        $sql = read_file('./query_temp/sst_query_temp.tmp');
        $data['query'] = $this->db->query($sql);
        
        $data['modulo'] = $this->modulo;
        $data['fipp'] = $this->periodo_pago['fipp'];
        $data['ffpp'] = $this->periodo_pago['ffpp']; 
        
        $this->load->view('template/t_header');
        $this->load->view('salida_salario_trabajador/imprimir_v', $data);
        $this->load->view('template/t_footer');
        
    }
	
	
}
