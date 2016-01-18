<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
DESTAJO-MODULE

date: 2014.12.17
type: php module
path: application/controllers/entrada.php

DESTAJO-MODULE-END
*/

class Entrada extends CI_Controller {

	protected $modulo = array(
        'nombre' => "entrada",
        'display' => "Entrada",
        'menu' => '2'
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

        // Comprobar si la condiguracion para conectarse a la base de datos existe
        //if (!$this->session->userdata('done'))
        $this->check_install();


        $this->load->library('math');
        $this->load->model('periodo_pago_m');
        $this->load->helper('file');

        $pp = $this->periodo_pago_m->get_all();
        $this->periodo_pago['ppa'] = $pp->perioro_pago_abierto;
        $this->periodo_pago['fipp'] = $pp->fecha_inicio_periodo_pago;
        $this->periodo_pago['ffpp'] = $pp->fecha_final_periodo_pago;

        // Comprobar que el periodo de pago este abierto
        $this->periodo_pago_m->check_open($pp->perioro_pago_abierto);

        $this->load->model('entrada_m');
		// Almacenar nombre del modulo en cookie para poder acceder por jquery
		$cookie = array(
		    'name'   => 'modulo',
		    'value'  => $this->modulo['nombre'],
		    'expire' => '86500',
		);
		$this->input->set_cookie($cookie);
    }

	//-------------------------------------------------------------------------

	public function check_install()
	{
		if (file_exists('./application/controllers/install.php'))
        {
            $this->load->helper('file');
            if ($this->session->userdata('install') == "done")
            {
               // Eliminar los datos de la instalacion
               delete_files('./application/views/install/',TRUE);
               rmdir('./application/views/install/');
               unlink('./application/controllers/install.php');
               $this->session->unset_userdata('install');
            }
            else
            {
               redirect('install');
            }
        }
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
        $data['ppa'] = $this->periodo_pago['ppa'];

        $this->load->view('template/t_header');
        $this->load->view('template/t_main_menu', $data);
        $this->load->view('entrada/layout_v', $data);
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
        $results = $this->entrada_m->list_all($config['per_page'], $offset);

        // Datos necesarios para configuracion
        $data['total_rows_show_of'] = $results['num_rows_wo_limit'];
        $data['rows_to_show'] = $response['rows_to_show'] = $results['num_rows']; // paginacion mostrando X, cookie
        $data['all_rows'] = $this->entrada_m->count_all();
        $config['base_url'] = base_url('entrada/show_content');
        $config['total_rows'] = $data['total_rows_show_of'];
        $response['per_page'] = $config['per_page']; // cookie

        // ORDENAR
        $data['otype'] = ($this->input->get('otype') == "desc") ? "asc" : "desc";

        // Cargar vista de registros (side-col)
        $data['results'] = $results['rows'];
        $response['registros'] = $this->load->view('entrada/tabla_v', $data, TRUE);

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

        // Reuqeridos
        $this->form_validation->set_rules('fk_operario_id', 'Operario', 'trim|required|xss_clean');
        $this->form_validation->set_rules('fecha_incidencia', 'Fecha de la incidencia', 'trim|required|xss_clean|human_date');
        $this->form_validation->set_rules('fecha_captacion', 'Fecha de captaci&oacute;n', 'trim|required|xss_clean|human_date');

        // Datos generales
        if ($this->input->post('hoja_de_ruta')) {
            $this->form_validation->set_rules('hoja_de_ruta', 'Hoja de ruta', 'trim|required|xss_clean|max_length[15]');
        }

        // Vinculacion
        // En caso de llegar algun valor de la pestaña VINCULACION deben validarse
        if ($this->input->post('fk_carga_descarga_id')){
            $this->form_validation->set_rules('fk_carga_descarga_id', 'Recorrido', 'trim|required|xss_clean');
        }
        if ($this->input->post('km_recorridos_carga')) {
            $this->form_validation->set_rules('km_recorridos_carga', 'Km. recorridos con carga', 'trim|required|xss_clean|decimal');
        }
        if ($this->input->post('litros_entregados')) {
            $this->form_validation->set_rules('litros_entregados', 'Litros entregados', 'trim|required|xss_clean|integer');
        }
        if ($this->input->post('horas_de_viaje')) {
            $this->form_validation->set_rules('fk_modo_descarga_id', 'Modo de descarga', 'trim|required|xss_clean|integer');
        }
        if ($this->input->post('numero_de_viajes')) {
            $this->form_validation->set_rules('horas_de_viaje', 'Horas de viaje', 'trim|required|xss_clean|decimal');
        }
        if ($this->input->post('numero_de_entregas')) {
            $this->form_validation->set_rules('numero_de_viajes', 'N&uacute;mero de viajes', 'trim|required|xss_clean|integer');
        }
        if ($this->input->post('fk_modo_descarga_id')) {
            $this->form_validation->set_rules('numero_de_entregas', 'N&uacute;mero de entregas', 'trim|required|xss_clean|integer');
        }



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
        $this->users->can('Entrada.Agregar', TRUE);
        $data['modulo'] = $this->modulo;
        $data['ppa'] = $this->periodo_pago['ppa'];

        // Mostrar formulario
         if ( !$this->input->post('accion') || $this->input->post('accion') != "agregar" ){

             // Obtener fechas del periodo actual
             $data['periodo_pago'] =  $this->periodo_pago_m->get_all();

             // Obtener operarios
             $this->load->model('operario_m');
             $data['lista_operarios'] = $this->operario_m->get_all();

             // Obtener capacidades de carga
             $this->load->model('capacidad_carga_m');
             $data['capacidades_carga'] = $this->capacidad_carga_m->get_all();

             // Obtener causa de la ausencia
             $this->load->model('causa_ausencia_m');
             $data['causas_ausencia'] = $this->causa_ausencia_m->get_all();

             $this->load->view($this->modulo['nombre'] . '/agregar_v', $data);
         }
         // Agregar
         else{
             $response = array();
             $query = $this->entrada_m->agregar();
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
        $this->users->can('Entrada.Editar', TRUE);
        $data['modulo'] = $this->modulo;

        // Mostrar formulario
        if ( !$this->input->post('accion') || $this->input->post('accion') != "editar" ){

             $data['ibi'] = $this->entrada_m->get_by_id($id);
		     $minorista = FALSE;

			// Comrpobar si la entrada es minorista o mayorista
			if ($data['ibi']->fk_municipio_id != NULL) $minorista = TRUE;


			// Obtener datos segun el tipo de entrada

			// Datos comunes
			//--------------

			// Obtener fechas del periodo actual
	        $data['periodo_pago'] =  $this->periodo_pago_m->get_all();

			// Obtener operarios
            $this->load->model('operario_m');
            $data['lista_operarios'] = $this->operario_m->get_all();

			// Obtener capacidades de carga
             $this->load->model('capacidad_carga_m');
             $data['capacidades_carga'] = $this->capacidad_carga_m->get_all();

			 // Obtener productos
             $productos = $this->entrada_m->getProducto($data['ibi']->fk_capacidad_carga_id);
             $data['lista_productos'] = $productos["sql"];



			// Datos de mayorista
			//--------------------
			if ( ! $minorista)
			{
	             // Obtener carga descarga (recorridos)
	             $recorridos = $this->entrada_m->getRecorridos($data['ibi']->fk_producto_id, $data['ibi']->fk_capacidad_carga_id);
	             $data['recorridos'] = $recorridos['lista_recorridos'];

               // Obtener modos de descarga
            $data['modos_descarga'] = $recorridos['lista_modos_descarga'];

	            $this->load->view($this->modulo['nombre'] . '/editar_v', $data);
			}
			// Datos de mayorista
			//-------------------
			elseif($minorista)
			{
				// Obtener el lugar de carga
				$lugares_carga = $this->entrada_m->getLugarCarga($data['ibi']->fk_producto_id, $data['ibi']->fk_capacidad_carga_id);
				$data['lugares_carga'] = $lugares_carga['lc_list'];

				// Obtener el municipio de descarga
				$municipios = $this->entrada_m->getMunicipios($data['ibi']->fk_producto_id, $data['ibi']->fk_capacidad_carga_id);
				$data['municipios'] = $municipios['ld_list'];
				$data['modos_descarga'] = $municipios['md_list'];


				$this->load->view($this->modulo['nombre'] . '/editar_minorista_v', $data);
			}

        }
        // Editar
        else {
            $response = array();
			$query = null;

			// Enviar datos a editar segun sea mayorista o minorista
			if ($this->input->post('fk_municipio_id'))
			{
				 $query = $this->entrada_m->editar_minorista( $this->input->post('id') );
			}
			else
			{
				 $query = $this->entrada_m->editar( $this->input->post('id') );
			}

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
     * Agregar minorista
     *
     * Se encarga de mostrar la vista para agregar
     * Se encarga de agregar el item del modulo
     */

    public function agregar_minorista()
    {
        $this->users->can('Entrada.Agregar', TRUE);
        $data['modulo'] = $this->modulo;
        $data['ppa'] = $this->periodo_pago['ppa'];

        // Mostrar formulario
         if ( !$this->input->post('accion') || $this->input->post('accion') != "agregar" ){

             // Obtener fechas del periodo actual
             $data['periodo_pago'] =  $this->periodo_pago_m->get_all();

             // Obtener operarios
             $this->load->model('operario_m');
             $data['lista_operarios'] = $this->operario_m->get_all();

             // Obtener capacidades de carga
             $this->load->model('capacidad_carga_m');
             $data['capacidades_carga'] = $this->capacidad_carga_m->get_all();

             $this->load->view($this->modulo['nombre'] . '/agregar_minorista_v', $data);
         }
         // Agregar
         else{
             $response = array();
             $query = $this->entrada_m->agregar_minorista();
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
     * Eliminar
     *
     * Se encarga de eliminar el elemento
     */

    public function eliminar()
    {
        $this->users->can('Entrada.Eliminar', TRUE);
        $response = array();

        if ($this->input->post('eliminar')) {
            $query = $this->entrada_m->eliminar();
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
     * Get Producto
     *
     * Obtener lista de productos luego de seleccionar una capacidad de carga
     * (equipo cuña), esto incluye validar si existen tiempos de carga y de descarga
     */
    public function getProducto()
    {
        $response = array();
        $query = $this->entrada_m->getProducto($this->input->post('capacidad_carga_id'));

        // Falta un tiempo
        if ($query["status"] == 'notime') {
            $response['no_time'] = $query['no_time'];
        }
        // Enviar productos
        else{
            $response['lista_productos'] = $query["sql"]->result();
        }
        echo json_encode($response);
    }

    //-------------------------------------------------------------------------

    /**
     * Get Recorridos
     *
     * Obtener lista de recorridos luego de seleccionar un producto
     */
	public function getRecorridos()
	{
		$response = array();
        $query = $this->entrada_m->getRecorridos($this->input->post('producto_id'), $this->input->post('capacidad_carga_id'));
        // Falta carga descarga
        if ($query['status'] == 'nocd') {
            $response['no_cd'] = $query['no_cd'];
        }
        // Enviar recorridos y modos de descarga
        else{
            $response['lista_modos_descarga'] = $query['lista_modos_descarga'];
            $response['lista_recorridos'] = $query['lista_recorridos']->result();
        }
        echo json_encode($response);
	}

	//-------------------------------------------------------------------------

	/**
	 * Get Municipios
	 * Obtener lista de municipios luego de seleccionar un producto (Minorista)
	 */
	 public function getMunicipios()
	 {
		 $query = $this->entrada_m->getMunicipios($this->input->post('producto_id'), $this->input->post('capacidad_carga_id'));
		 echo json_encode($query);
	 }

	//-------------------------------------------------------------------------

	/**
	 * Get LugarCarga
	 * Obtener lista de lugares de carga luego de seleccionar producto (minosrista)
	 */
	public function getLugarCarga()
	{
		$query = $this->entrada_m->getLugarCarga($this->input->post('producto_id'), $this->input->post('capacidad_carga_id'));
		echo json_encode($query);
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
        $sql = read_file('./query_temp/entrada_query_temp.tmp');

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
        $this->excel->getActiveSheet()->setTitle('Entradas');

        // Crear cabeceras
        $this->excel->getActiveSheet()->setCellValue('A1', 'Chapa');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Nombre y apellidos');
        $this->excel->getActiveSheet()->setCellValue('C1', 'Hoja de ruta');
        $this->excel->getActiveSheet()->setCellValue('D1', 'Fecha de la incidencia');
        $this->excel->getActiveSheet()->setCellValue('E1', 'Equipo');
        $this->excel->getActiveSheet()->setCellValue('F1', html_entity_decode('Cu&ntilde;a'));
        $this->excel->getActiveSheet()->setCellValue('G1', 'Capacidad de carga');
        $this->excel->getActiveSheet()->setCellValue('H1', 'Producto');
        $this->excel->getActiveSheet()->setCellValue('I1', 'Lugar de carga');
        $this->excel->getActiveSheet()->setCellValue('J1', 'Lugar de descarga');
        $this->excel->getActiveSheet()->setCellValue('K1', 'Litros entregados');
        $this->excel->getActiveSheet()->setCellValue('L1', html_entity_decode('Kil&oacute;metros recorridos'));
        $this->excel->getActiveSheet()->setCellValue('M1', 'Modo de descarga');
        $this->excel->getActiveSheet()->setCellValue('N1', html_entity_decode('N&deg; de viajes'));
        $this->excel->getActiveSheet()->setCellValue('O1', html_entity_decode('N&deg; de entregas'));
        $this->excel->getActiveSheet()->setCellValue('P1', 'Viaje');
        $this->excel->getActiveSheet()->setCellValue('Q1', 'Interrupto');
        $this->excel->getActiveSheet()->setCellValue('R1', 'No vinculado');
        $this->excel->getActiveSheet()->setCellValue('S1', 'Nocturnidad corta');
        $this->excel->getActiveSheet()->setCellValue('T1', 'Nocturnidad larga');
        $this->excel->getActiveSheet()->setCellValue('U1', 'Capacitaci&oacute;n');
        $this->excel->getActiveSheet()->setCellValue('V1', html_entity_decode('Movilizaci&oacute;n'));
        $this->excel->getActiveSheet()->setCellValue('W1', 'Feriado');
        $this->excel->getActiveSheet()->setCellValue('X1', 'Ausencia');
        $this->excel->getActiveSheet()->setCellValue('Y1', 'Causa de la ausencia');
        $this->excel->getActiveSheet()->setCellValue('Z1', 'Pago feriado');
        $this->excel->getActiveSheet()->setCellValue('AA1', 'Importe del viaje');
        $this->excel->getActiveSheet()->setCellValue('AB1', 'Cumplimiento de la norma');
        $this->excel->getActiveSheet()->setCellValue('AC1', 'Fecha de captacion');
        $this->excel->getActiveSheet()->setCellValue('AD1', 'Inicio del periodo de pago');
        $this->excel->getActiveSheet()->setCellValue('AE1', 'Final del periodo de pago');

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
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->hoja_de_ruta);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, to_date($row->fecha_incidencia));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->no_equipo);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->no_cuna);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->capacidad_carga);
            //fixed
            $f = $row->producto;
            if ($row->tipo != "") $f.= "(" . $row->tipo . ")";
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $f);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->lugar_carga);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->lugar_descarga);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->litros_entregados);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->km_recorridos));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->modo);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->numero_de_viajes);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->numero_de_entregas);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->horas_de_viaje);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->horas_interrupto);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->horas_no_vinculado);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->horas_nocturnidad_corta);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->horas_nocturnidad_larga);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->horas_capacitacion);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->horas_movilizacion);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->horas_feriado);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $row->horas_ausencia);
            //fixed
            $f = ($row->pago_feriado == 1) ? "si" : "no" ;
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, $f);
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->importe_viaje));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, mysql_to_number($row->cumplimiento_norma));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, to_date($row->fecha_captacion));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, date("d/m/Y", $row->fecha_inicio_periodo_pago));
            $this->excel->getActiveSheet()->setCellValue($map[$letra++] . "" . $j, date("d/m/Y", $row->fecha_final_periodo_pago));

            $j ++;
            $letra = 0;
        }

        // Crear el fichero excel y hacerlo descargable
        $date = date("d_m_y");
        $filename="Entrada_$date.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');

    }

}
