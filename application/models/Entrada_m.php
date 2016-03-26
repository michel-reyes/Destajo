<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
DESTAJO-MODULE

date: 2015.01.19
type: php module
path: application/models/entrada_m.php

DESTAJO-MODULE-END
*/

class Entrada_m extends CI_Model {

    protected $fields = array(
        'entrada.entrada_id',
        'entrada.fk_operario_id',
        'entrada.fecha_incidencia',
        'entrada.hoja_de_ruta',
        'entrada.fk_capacidad_carga_id',
        'entrada.fk_producto_id',
        'entrada.fecha_captacion',
        'entrada.horas_de_viaje',
        'entrada.numero_de_viajes',
        'entrada.numero_de_entregas',
        'entrada.fk_modo_descarga_id',
        'entrada.litros_entregados',
        'entrada.fk_carga_descarga_id',
        'entrada.km_recorridos_carga',
        'entrada.horas_interrupto',
        'entrada.horas_no_vinculado',
        'entrada.horas_nocturnidad_corta',
        'entrada.cuantia_horaria_nocturnidad_corta',
        'entrada.horas_nocturnidad_larga',
        'entrada.cuantia_horaria_nocturnidad_larga',
        'entrada.horas_capacitacion',
        'entrada.horas_movilizacion',
        'entrada.horas_feriado',
        'entrada.pago_feriado',
        'entrada.horas_ausencia',
        'entrada.fk_causa_ausencia_id',
        'entrada.observaciones',
        'entrada.importe_viaje',
        'entrada.importe_viaje_progresivo_i',
        'entrada.importe_viaje_m',
        'entrada.cumplimiento_norma',
        'entrada.cumplimiento_norma_minorista',
        'entrada.fecha_inicio_periodo_pago',
        'entrada.fecha_final_periodo_pago',
        // Operario
        'op.m_operario_id',
        'op.chapa',
        'op.nombre',
        'op.apellidos',
        'op.fk_categoria_operario_id',
        // Carga descarga
        'cd.carga_descarga_id',
        'cd.codigo',
        'cd.km_recorridos',
        // Producto
        'p.m_producto_id',
        'p.producto',
        'p.tipo',
        // Modo de descarga
        'md.m_modo_descarga_id',
        'md.modo',
        // Capacidad de carga
        'cc.m_capacidad_carga_id',
        'cc.viajes_promedio',
        'cc.capacidad_carga',
        'cc.tipo_de_producto',
        // Equipo
        'm_equipo.m_equipo_id',
        'm_equipo.numero_operacional as no_equipo',
        // Cuna
        'm_cuna.m_cuna_id',
        'm_cuna.numero_operacional as no_cuna',
        // Lugar de carga
        'lc.m_lugar_carga_id',
        'lc.lugar_carga',
        // Lugar de descarga
        'ld.m_lugar_descarga_id',
        'ld.lugar_descarga',
        // Causa de ausencia
        'ca.m_causa_ausencia_id',
        'ca.causa'

    );

    protected $periodo_pago = array(
        'fipp' => "",
        'ffpp' => "",
        'ppa' => ""
    );

    //-------------------------------------------------------------------------

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $pp = $this->periodo_pago_m->get_all();
        $this->periodo_pago['ppa'] = $pp->perioro_pago_abierto;
        $this->periodo_pago['fipp'] = $pp->fecha_inicio_periodo_pago;
        $this->periodo_pago['ffpp'] = $pp->fecha_final_periodo_pago;
    }

    //-------------------------------------------------------------------------

    /**
     * count_all
     *
     * Obtiene el numero real de registros
     * @return double
     */

    public function count_all()
    {
        $this->db->where('fecha_inicio_periodo_pago >=', $this->periodo_pago['fipp']);
        $this->db->where('fecha_final_periodo_pago <=', $this->periodo_pago['ffpp']);
        return $this->db->count_all_results('entrada');
    }

    //-------------------------------------------------------------------------

    /**
     * list_all
     *
     * Obtener todo el contenido de la tabla
     * -limites
     * -busquedas
     *
     * @param limit
     * @param offset
     * @return array
     */

     public function list_all($limit='', $offset='')
     {
         $this->db->start_cache();
         $query = array();
         $search = "";

         // Buscar
         //-----------------------------
         $search = $this->create_search();

         // Ordenar
         //-----------------------------
         $ofield = $this->input->get("ofield");
         $otype = $this->input->get("otype");
         if (!$ofield OR !$otype) {
             $ofield = $this->session->userdata('ofield_entrada') ? $this->session->userdata('ofield_entrada') : "entrada_id";
             $otype = $this->session->userdata('otype_entrada') ? $this->session->userdata('otype_entrada') : "desc";
         }
         $this->db->order_by($ofield, $otype);

         // Evitar que se vean datos si el periodo de pago esta cerrado
         if ($this->periodo_pago['ppa'] == 0) {
             $this->db->where('fecha_final_periodo_pago >', $this->periodo_pago['ffpp']);
         }


         // Join
         //----------------------------
         $this->db->join('m_operario op', 'op.m_operario_id = entrada.fk_operario_id', 'left');
         $this->db->join('m_capacidad_carga cc', 'cc.m_capacidad_carga_id = entrada.fk_capacidad_carga_id', 'left');
         $this->db->join('m_producto p', 'p.m_producto_id = entrada.fk_producto_id', 'left');
         $this->db->join('m_modo_descarga md', 'md.m_modo_descarga_id = entrada.fk_modo_descarga_id', 'left');
         $this->db->join('carga_descarga cd', 'cd.carga_descarga_id = entrada.fk_carga_descarga_id', 'left');
         $this->db->join('m_equipo', 'm_equipo.m_equipo_id = cc.fk_equipo_id', 'left');
         $this->db->join('m_cuna', 'm_cuna.m_cuna_id = cc.fk_cuna_id', 'left');
         $this->db->join('m_lugar_carga lc', 'lc.m_lugar_carga_id = cd.fk_lugar_carga_id', 'left');
         $this->db->join('m_lugar_descarga ld', 'ld.m_lugar_descarga_id = cd.fk_lugar_descarga_id', 'left');
         $this->db->join('m_causa_ausencia ca', 'ca.m_causa_ausencia_id = entrada.fk_causa_ausencia_id', 'left');


         // Select
         //-----------------------------
         $fields_name = array();
         foreach ($this->fields as $key => $value) {
             $fields_name[] = $value;
         }
         $this->db->select($fields_name);

         $this->db->stop_cache();

         // Paginacion
         //-----------------------------
         // Obtener registros sin limit
         $query_temp = $this->db->get('entrada');

         // Antes de procesar la consulta enviarla a un fichero temporal
         // para que pueda ser utilizada por la funcion Exportar
         $data = $this->db->last_query();
         if (! write_file("./query_temp/entrada_query_temp.tmp", $data)) continue;

         $query['num_rows_wo_limit'] = $query_temp->num_rows();
         if ($limit!='' OR $offset!='') $this->db->limit($limit, $offset);

         // Obtener registros
         //-----------------------------
         $query['rows'] = $this->db->get('entrada');
         $query['num_rows'] = $query['rows']->num_rows();
         $this->db->flush_cache();

         // Almacenar ordenar en session
         //-----------------------------
         $this->session->set_userdata('order_field_entrada', $ofield);
         $this->session->set_userdata('order_type_entrada', $otype);

         return $query;
     }

    //-------------------------------------------------------------------------

    /**
     * create_search
     *
     * Funcion encargada de crear el codigo SQL necesario para realizar una busqueda
     * en el modulo
     * @return string
     */

    public function create_search()
    {
        $query = "";

        // AVANZADO
        $campo = $this->input->post('buscar_campo');
        $criterio = $this->input->post('buscar_criterio');
        $texto = $this->input->post('buscar_texto');
        $buscar_btn_avanzado = $this->input->post('buscar_btn_avanzado');
        $periodo = $this->input->post('b_periodo');

        // No se mando a buscar, pero hay busquedas almacenadas en session
        if (! $buscar_btn_avanzado) {
            if (! $campo OR count($campo) <= 0) {
                $campo = $this->session->userdata('buscar_campo_entrada');
                $criterio = $this->session->userdata('buscar_criterio_entrada');
                $texto = $this->session->userdata('buscar_texto_entrada');
                $periodo = $this->session->userdata('search_periodo_entrada');
            }
        }

        // Buscar
        if ($campo AND count($campo) > 0) {

             // Ajustar las entradas al periodo de pago
             //----------------------------
             if ($periodo) {
                 switch ($periodo) {
                     case 'present':
                         $this->db->where('fecha_inicio_periodo_pago >=', $this->periodo_pago['fipp']);
                         $this->db->where('fecha_final_periodo_pago <=', $this->periodo_pago['ffpp']);
                         break;

                     case 'past':
                         $this->db->where('fecha_final_periodo_pago <', $this->periodo_pago['fipp']);
                         break;
                     case 'both':
                         $this->db->where('fecha_final_periodo_pago <=', $this->periodo_pago['ffpp']);
                         break;
                 }
             }

            foreach ($campo as $key => $value) {

                // Si el campo a buscar es de tipo fecha
                // convertir la fecha a sql
                if (strpos($value, "fecha") !== FALSE) {
                    // strtotime
                    if (strpos($value, "periodo_pago") !== FALSE) {
                        $texto[$key] = strtotime(to_sql($texto[$key]));
                    }else{
                        $texto[$key] = to_sql($texto[$key]);
                    }
                }

                switch ($criterio[$key]) {
                    case 'like_none':
                        $this->db->like($value, $texto[$key], 'none');
                        break;
                    case 'not_like_none':
                        $this->db->not_like($value, $texto[$key], 'none');
                        break;
                    case 'like_both':
                        $this->db->like($value, $texto[$key], 'both');
                        break;
                    case 'or_like_both':
                        $this->db->or_like($value, $texto[$key], 'both');
                        break;
                    case 'not_like_both':
                        $this->db->not_like($value, $texto[$key], 'both');
                        break;
                    case 'or_not_like_both':
                        $this->db->or_not_like($value, $texto[$key], 'both');
                        break;
                    case 'lt':
                        $this->db->where($value . ' < ', $texto[$key]);
                        break;
                    case 'gt':
                        $this->db->where($value . ' > ', $texto[$key]);
                        break;
                }
            }
        }else{
            $this->db->where('fecha_inicio_periodo_pago >=', $this->periodo_pago['fipp']);
            $this->db->where('fecha_final_periodo_pago <=', $this->periodo_pago['ffpp']);
        }

        // Se mando a buscar y hay texto, so alacenarlo en session,
        // no almacenar lo que ya esta almacenado, so preguntar si se envio abuscar
        if ($buscar_btn_avanzado AND $campo) {
            if ($this->input->post('b_periodo')) $this->session->set_userdata('search_periodo_entrada', $this->input->post('b_periodo'));
            if ($this->input->post('buscar_campo')) $this->session->set_userdata('buscar_campo_entrada', $this->input->post('buscar_campo'));
            if ($this->input->post('buscar_criterio')) $this->session->set_userdata('buscar_criterio_entrada', $this->input->post('buscar_criterio'));
            if ($this->input->post('buscar_texto')) $this->session->set_userdata('buscar_texto_entrada', $this->input->post('buscar_texto'));
        }

        // Se mando a buscar y no hay texto en la busqueda ni en session
        // so, eliminar de session
        if ($buscar_btn_avanzado AND ! $campo) {
            $this->session->unset_userdata('search_periodo_entrada');
            $this->session->unset_userdata('buscar_campo_entrada');
            $this->session->unset_userdata('buscar_criterio_entrada');
            $this->session->unset_userdata('buscar_texto_entrada');
        }

        return $query;
    }

    //-------------------------------------------------------------------------

    /**
     * Get_by_id
     *
     * Obtiene el elemento por su id
     * @param int id
     * @return array
     */
    public function get_by_id($id=0)
    {
        $this->db->limit(1);
        $this->db->where('entrada_id', $id);
        return $this->db->get('entrada')->row();
    }

    //-------------------------------------------------------------------------

    /**
     * Agregar
     *
     * Agregar el elemento del modulo
     * @return bool
     */

    public function agregar()
    {
        $pass = true;
        $operarios[] = $this->input->post('fk_operario_id');
        if ($this->input->post('fk_ayudante_id'))
            $operarios[] = $this->input->post('fk_ayudante_id');

        for($iter = 0; $iter < count($operarios); $iter++)
        {
            $importe_viaje = 0;
            $importe_viaje_progresivo_incrementado = 0;

            $importe_viaje = $this->math->X(
                $this->input->post('fk_capacidad_carga_id'),
                number_to_mysql($this->input->post('km_recorridos_carga')),
                $this->input->post('litros_entregados'),
                $this->input->post('fk_producto_id'),
                $this->input->post('fk_modo_descarga_id'),
                $this->input->post('fk_carga_descarga_id'),
                $this->input->post('numero_de_entregas'),
                $operarios[$iter]);

            $cumplimiento_norma = $this->math->Y($operarios[$iter],$importe_viaje,
                number_to_mysql($this->input->post('horas_de_viaje')));

            // Comenzar transaccion
            $this->db->trans_start();

                // Obtener datos del periodo de pago
                $pp = $this->periodo_pago_m->get_all();
                $fipp = $pp->fecha_inicio_periodo_pago;
                $ffpp = $pp->fecha_final_periodo_pago;


                // ENTRADA

                // Datos requeridos
                $this->db->set('fk_operario_id', $operarios[$iter]);
                $this->db->set('fecha_incidencia', to_sql($this->input->post('fecha_incidencia')));
                $this->db->set('fecha_captacion', to_sql($this->input->post('fecha_captacion')));
                $this->db->set('fecha_inicio_periodo_pago', $fipp);
                $this->db->set('fecha_final_periodo_pago', $ffpp);

                // Datos generales
                if ($this->input->post('fk_capacidad_carga_id')) {
                    $this->db->set('fk_capacidad_carga_id', $this->input->post('fk_capacidad_carga_id'));
                }
                if ($this->input->post('fk_producto_id')) {
                    $this->db->set('fk_producto_id', $this->input->post('fk_producto_id'));
                }
                if ($this->input->post('hoja_de_ruta')) {
                    $this->db->set('hoja_de_ruta', $this->input->post('hoja_de_ruta'));
                }

                // Vinculacion
                if ($this->input->post('fk_carga_descarga_id')) {
                    $this->db->set('fk_carga_descarga_id', $this->input->post('fk_carga_descarga_id'));
                }
                if ($this->input->post('km_recorridos_carga')) {
                    $this->db->set('km_recorridos_carga', number_to_mysql($this->input->post('km_recorridos_carga')));
                }

                if ($this->input->post('litros_entregados')) {
                    $this->db->set('litros_entregados', $this->input->post('litros_entregados'));
                }
                if ($this->input->post('fk_modo_descarga_id')) {
                    $this->db->set('fk_modo_descarga_id', $this->input->post('fk_modo_descarga_id'));
                }
                if ($this->input->post('horas_de_viaje')) {
                    $this->db->set('horas_de_viaje', number_to_mysql($this->input->post('horas_de_viaje')));
                }
                if ($this->input->post('numero_de_viajes')) {
                    $this->db->set('numero_de_viajes', $this->input->post('numero_de_viajes'));

                    // Calcular destajo progresivo
                    /*$destajo_progresivo = $this->math->R(
                        $this->input->post('fk_capacidad_carga_id'),
                        $this->input->post('numero_de_viajes'));*/

                    // Calcular destajo progresivo (incrementado 2014)
                    $destajo_progresivo = $this->math->R1(
                        $this->input->post('fk_capacidad_carga_id'),
                        $this->input->post('numero_de_viajes'),
                        to_sql($this->input->post('fecha_incidencia')),
                        $this->input->post('hoja_de_ruta')
                    );

                    if ($destajo_progresivo !== FALSE){
                        $importe_viaje_progresivo_incrementado = $destajo_progresivo;
                    }
                }
                if ($this->input->post('numero_de_entregas')) {
                    $this->db->set('numero_de_entregas', $this->input->post('numero_de_entregas'));
                }
                if ($this->input->post('pago_feriado')) {
                    $this->db->set('pago_feriado', ($this->input->post('pago_feriado') == "on") ? 1 : 0);
                }


                // Sin vinculacion
                if ($this->input->post('horas_ausencia')) {
                    $this->db->set('horas_ausencia', number_to_mysql($this->input->post('horas_ausencia')));
                }
                if ($this->input->post('fk_causa_ausencia_id')) {
                    $this->db->set('fk_causa_ausencia_id', $this->input->post('fk_causa_ausencia_id'));
                }
                if ($this->input->post('observaciones')) {
                    $this->db->set('observaciones', $this->input->post('observaciones'));
                }
                if ($this->input->post('horas_interrupto')) {
                    $this->db->set('horas_interrupto', number_to_mysql($this->input->post('horas_interrupto')));
                }
                if ($this->input->post('horas_no_vinculado')) {
                    $this->db->set('horas_no_vinculado', number_to_mysql($this->input->post('horas_no_vinculado')));
                }
                if ($this->input->post('horas_nocturnidad_corta')) {
                    $this->db->set('horas_nocturnidad_corta', number_to_mysql($this->input->post('horas_nocturnidad_corta')));
                    // Calcular cuantia horario para nocturnidad corta y almacenar dicho campo
                    $chnc = $this->math->G("CHNC");
                    $chnc *= number_to_mysql($this->input->post('horas_nocturnidad_corta'));
                    $this->db->set('cuantia_horaria_nocturnidad_corta', $chnc);
                }
                if ($this->input->post('horas_nocturnidad_larga')) {
                    $this->db->set('horas_nocturnidad_larga', number_to_mysql($this->input->post('horas_nocturnidad_larga')));
                    // Calcular cuantia horario para nocturnidad larga y almacenar dicho campo
                    $chnl = $this->math->G("CHNL");
                    $chnl *= number_to_mysql($this->input->post('horas_nocturnidad_larga'));
                    $this->db->set('cuantia_horaria_nocturnidad_larga', $chnl);
                }
                if ($this->input->post('horas_capacitacion')) {
                    $this->db->set('horas_capacitacion', number_to_mysql($this->input->post('horas_capacitacion')));
                }
                if ($this->input->post('horas_movilizacion')) {
                    $this->db->set('horas_movilizacion', number_to_mysql($this->input->post('horas_movilizacion')));
                }
                if ($this->input->post('horas_feriado')) {
                    $this->db->set('horas_feriado', number_to_mysql($this->input->post('horas_feriado')));
                }

                /*
                 * Datos que afectan el importe del viaje
                 */

                // Pago feriado
                if ($this->input->post('pago_feriado'))
                {
                    // El importe debe pagarse doble
                    $importe_viaje *= 2;
                    $importe_viaje_progresivo_incrementado *= 2;
                }

                // Modo de descarga Turbina del equipo
                if ($this->input->post('fk_modo_descarga_id') && $this->input->post('fk_modo_descarga_id') == 5)
                {
                    // El importe debe multiplicarse por el coeficiente CDTMA
                    $cdtma = $this->math->G('CDTMA');
                    $importe_viaje *= $cdtma;
                    $importe_viaje_progresivo_incrementado *= $cdtma;
                }


                // Importe del viaje
                $this->db->set('importe_viaje', $importe_viaje);
                // Importe del viaje progresivo incrementado
                $this->db->set('importe_viaje_progresivo_i', $importe_viaje_progresivo_incrementado);

                // Cumplimiento de la norma
                $this->db->set('cumplimiento_norma', $cumplimiento_norma);

                $this->db->insert('entrada');

            // Terminar transaccion
            $this->db->trans_complete();

            if (! $this->db->trans_status()) $pass = false;
        }

        return $pass;
    }

    //-------------------------------------------------------------------------

    public function agregar_minorista()
    {
        $operarios[] = $this->input->post('fk_operario_id');
        if ($this->input->post('fk_ayudante_id'))
            $operarios[] = $this->input->post('fk_ayudante_id');

        // Comenza transaccion
        $this->db->trans_start();

        for($iter = 0; $iter < count($operarios); $iter++)
        {

            $importe_minorista = 0;
            $cumplimiento_norma_minorista = 0;

            //Calcular importe de la carga
            $aa = $this->math->Aa($this->input->post('fk_capacidad_carga_id'),
                            $this->input->post('fk_producto_id'),
                            $this->input->post('fk_lugar_carga_id'),
                            number_to_mysql($this->input->post('km_recorridos_carga')),
                            $operarios[$iter]);

            //Calcular importe de la descarga
            $ba = $this->math->Ba($this->input->post('fk_capacidad_carga_id'),
                            $this->input->post('fk_producto_id'),
                            $this->input->post('fk_municipio_id'),
                            $this->input->post('fk_modo_descarga_id'),
                            number_to_mysql($this->input->post('km_recorridos_carga')),
                            $operarios[$iter]);

            //Calcular Normas de tiempo para 1 kilometro
            $ca = $this->math->Ca($this->input->post('km_totales_recorridos'),
                            $this->input->post('fk_municipio_id'),
                            number_to_mysql($this->input->post('km_recorridos_carga')),
                            $operarios[$iter],
                            $this->input->post('fk_producto_id'),
                            $this->input->post('fk_capacidad_carga_id'));

            //Determinar los gastos de tiempo para las entregas
            // la ultima variable es numero de viajes pero solo se debe afectar
            // cuando se esta editando (VER la misma funcion DA al editar)
            $da = $this->math->Da(
                    $this->input->post('fk_capacidad_carga_id'),
                    number_to_mysql($this->input->post('km_recorridos_carga')),
                    $operarios[$iter],
                    $this->input->post('numero_de_entregas'),
                    to_sql($this->input->post('fecha_incidencia')),
                    $this->input->post('hoja_de_ruta'),
                    NULL);

            $importe_minorista = $ca+$ba+$aa+$da;

            $cumplimiento_norma_minorista =
                $this->math->Y($operarios[$iter],$importe_minorista,number_to_mysql($this->input->post('horas_de_viaje')));

            // Guardar los datos en la BD

                // Obtener datos del periodo de pago
                $pp = $this->periodo_pago_m->get_all();
                $fipp = $pp->fecha_inicio_periodo_pago;
                $ffpp = $pp->fecha_final_periodo_pago;

                // Datos requeridos
                $this->db->set('fk_operario_id', $operarios[$iter]);
                $this->db->set('fecha_incidencia', to_sql($this->input->post('fecha_incidencia')));
                $this->db->set('fecha_captacion', to_sql($this->input->post('fecha_captacion')));
                $this->db->set('fecha_inicio_periodo_pago', $fipp);
                $this->db->set('fecha_final_periodo_pago', $ffpp);

                // Datos generales
                if ($this->input->post('fk_capacidad_carga_id')) {
                    $this->db->set('fk_capacidad_carga_id', $this->input->post('fk_capacidad_carga_id'));
                }
                if ($this->input->post('fk_producto_id')) {
                    $this->db->set('fk_producto_id', $this->input->post('fk_producto_id'));
                }
                if ($this->input->post('hoja_de_ruta')) {
                    $this->db->set('hoja_de_ruta', $this->input->post('hoja_de_ruta'));
                }

                // Vinculacion
                if ($this->input->post('fk_lugar_carga_id'))
                {
                    $this->db->set('fk_lugar_carga_id', $this->input->post('fk_lugar_carga_id'));
                }

                if ($this->input->post('fk_municipio_id'))
                {
                    $this->db->set('fk_municipio_id', $this->input->post('fk_municipio_id'));
                }

                if ($this->input->post('km_recorridos_carga'))
                {
                    $this->db->set('km_recorridos_carga', number_to_mysql($this->input->post('km_recorridos_carga')));
                }

                if ($this->input->post('km_totales_recorridos'))
                {
                    $this->db->set('km_totales_recorridos', number_to_mysql($this->input->post('km_totales_recorridos')));
                }

                if ($this->input->post('litros_entregados'))
                {
                    $this->db->set('litros_entregados', $this->input->post('litros_entregados'));
                }

                if ($this->input->post('fk_modo_descarga_id'))
                {
                    $this->db->set('fk_modo_descarga_id', $this->input->post('fk_modo_descarga_id'));
                }

                if ($this->input->post('horas_de_viaje'))
                {
                    $this->db->set('horas_de_viaje', number_to_mysql($this->input->post('horas_de_viaje')));
                }

                if ($this->input->post('numero_de_viajes'))
                {
                    $this->db->set('numero_de_viajes', $this->input->post('numero_de_viajes'));
                }

                if ($this->input->post('numero_de_entregas'))
                {
                    $this->db->set('numero_de_entregas', $this->input->post('numero_de_entregas'));
                }

                if ($this->input->post('pago_feriado')) {
                    $this->db->set('pago_feriado', ($this->input->post('pago_feriado') == "on") ? 1 : 0);
                    // El importe debe pagarse doble
                    $importe_minorista *= 2;
                }


                // Sin vinculacion
                if ($this->input->post('horas_interrupto'))
                {
                    $this->db->set('horas_interrupto', number_to_mysql($this->input->post('horas_interrupto')));
                }

                if ($this->input->post('horas_no_vinculado'))
                {
                    $this->db->set('horas_no_vinculado', number_to_mysql($this->input->post('horas_no_vinculado')));
                }

                if ($this->input->post('horas_nocturnidad_corta'))
                {
                    $this->db->set('horas_nocturnidad_corta', number_to_mysql($this->input->post('horas_nocturnidad_corta')));
                    // Calcular cuantia horario para nocturnidad corta y almacenar dicho campo
                    $chnc = $this->math->G("CHNC");
                    $chnc *= number_to_mysql($this->input->post('horas_nocturnidad_corta'));
                    $this->db->set('cuantia_horaria_nocturnidad_corta', $chnc);
                }

                if ($this->input->post('horas_nocturnidad_larga'))
                {
                    $this->db->set('horas_nocturnidad_larga', number_to_mysql($this->input->post('horas_nocturnidad_larga')));
                    // Calcular cuantia horario para nocturnidad larga y almacenar dicho campo
                    $chnl = $this->math->G("CHNL");
                    $chnl *= number_to_mysql($this->input->post('horas_nocturnidad_larga'));
                    $this->db->set('cuantia_horaria_nocturnidad_larga', $chnl);
                }

                // Importe del viaje  + prgresivo
                $this->db->set('importe_viaje_m', $importe_minorista);

                // Cumplimiento de la norma
                $this->db->set('cumplimiento_norma_minorista', $cumplimiento_norma_minorista);

                $this->db->insert('entrada');

        }
        // Terminar transaccion
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    //-------------------------------------------------------------------------

    public function editar_minorista($id='')
    {
        $importe_minorista = 0;
        $cumplimiento_norma_minorista = 0;

        //Calcular importe de la carga
        $aa = $this->math->Aa($this->input->post('fk_capacidad_carga_id'),
                        $this->input->post('fk_producto_id'),
                        $this->input->post('fk_lugar_carga_id'),
                        number_to_mysql($this->input->post('km_recorridos_carga')),
                        $this->input->post('fk_operario_id'));

        //Calcular importe de la descarga
        $ba = $this->math->Ba($this->input->post('fk_capacidad_carga_id'),
                        $this->input->post('fk_producto_id'),
                        $this->input->post('fk_municipio_id'),
                        $this->input->post('fk_modo_descarga_id'),
                        number_to_mysql($this->input->post('km_recorridos_carga')),
                        $this->input->post('fk_operario_id'));

        //Calcular Normas de tiempo para 1 kilometro
        $ca = $this->math->Ca($this->input->post('km_totales_recorridos'),
                        $this->input->post('fk_municipio_id'),
                        number_to_mysql($this->input->post('km_recorridos_carga')),
                        $this->input->post('fk_operario_id'),
                        $this->input->post('fk_producto_id'),
                        $this->input->post('fk_capacidad_carga_id'));

        //Determinar los gastos de tiempo para las entregas
        // En caso de editar se agrego una nueva variable: numero_de_viajes
        // para obtener correctamente el total de entregas.
        $da = $this->math->Da(
                $this->input->post('fk_capacidad_carga_id'),
                number_to_mysql($this->input->post('km_recorridos_carga')),
                $this->input->post('fk_operario_id'),
                $this->input->post('numero_de_entregas'),
                to_sql($this->input->post('fecha_incidencia')),
                $this->input->post('hoja_de_ruta'),
                $this->input->post('numero_de_viajes'));

        $importe_minorista = $ca+$ba+$aa+$da;

        $cumplimiento_norma_minorista =
            $this->math->Y($this->input->post('fk_operario_id'),$importe_minorista,number_to_mysql($this->input->post('horas_de_viaje')));

        // Guardar los datos en la BD

        // Comenza transaccion
        $this->db->trans_start();

            // Obtener datos del periodo de pago
            $pp = $this->periodo_pago_m->get_all();
            $fipp = $pp->fecha_inicio_periodo_pago;
            $ffpp = $pp->fecha_final_periodo_pago;

            // Datos requeridos
            $this->db->set('fk_operario_id', $this->input->post('fk_operario_id'));
            $this->db->set('fecha_incidencia', to_sql($this->input->post('fecha_incidencia')));
            $this->db->set('fecha_captacion', to_sql($this->input->post('fecha_captacion')));
            $this->db->set('fecha_inicio_periodo_pago', $fipp);
            $this->db->set('fecha_final_periodo_pago', $ffpp);

            // Datos generales
            if ($this->input->post('fk_capacidad_carga_id')) {
                $this->db->set('fk_capacidad_carga_id', $this->input->post('fk_capacidad_carga_id'));
            }
            if ($this->input->post('fk_producto_id')) {
                $this->db->set('fk_producto_id', $this->input->post('fk_producto_id'));
            }
            if ($this->input->post('hoja_de_ruta')) {
                $this->db->set('hoja_de_ruta', $this->input->post('hoja_de_ruta'));
            }

            // Vinculacion
            if ($this->input->post('fk_lugar_carga_id'))
            {
                $this->db->set('fk_lugar_carga_id', $this->input->post('fk_lugar_carga_id'));
            }

            if ($this->input->post('fk_municipio_id'))
            {
                $this->db->set('fk_municipio_id', $this->input->post('fk_municipio_id'));
            }

            if ($this->input->post('km_recorridos_carga'))
            {
                $this->db->set('km_recorridos_carga', number_to_mysql($this->input->post('km_recorridos_carga')));
            }

            if ($this->input->post('km_totales_recorridos'))
            {
                $this->db->set('km_totales_recorridos', number_to_mysql($this->input->post('km_totales_recorridos')));
            }

            if ($this->input->post('litros_entregados'))
            {
                $this->db->set('litros_entregados', $this->input->post('litros_entregados'));
            }

            if ($this->input->post('fk_modo_descarga_id'))
            {
                $this->db->set('fk_modo_descarga_id', $this->input->post('fk_modo_descarga_id'));
            }

            if ($this->input->post('horas_de_viaje'))
            {
                $this->db->set('horas_de_viaje', number_to_mysql($this->input->post('horas_de_viaje')));
            }

            if ($this->input->post('numero_de_viajes'))
            {
                $this->db->set('numero_de_viajes', $this->input->post('numero_de_viajes'));
            }

            if ($this->input->post('numero_de_entregas'))
            {
                $this->db->set('numero_de_entregas', $this->input->post('numero_de_entregas'));
            }

            if ($this->input->post('pago_feriado')) {
                $this->db->set('pago_feriado', ($this->input->post('pago_feriado') == "on") ? 1 : 0);
                // El importe debe pagarse doble
                $importe_minorista *= 2;
            }


            // Sin vinculacion
            if ($this->input->post('horas_interrupto'))
            {
                $this->db->set('horas_interrupto', number_to_mysql($this->input->post('horas_interrupto')));
            }

            if ($this->input->post('horas_no_vinculado'))
            {
                $this->db->set('horas_no_vinculado', number_to_mysql($this->input->post('horas_no_vinculado')));
            }

            if ($this->input->post('horas_nocturnidad_corta'))
            {
                $this->db->set('horas_nocturnidad_corta', number_to_mysql($this->input->post('horas_nocturnidad_corta')));
                // Calcular cuantia horario para nocturnidad corta y almacenar dicho campo
                $chnc = $this->math->G("CHNC");
                $chnc *= number_to_mysql($this->input->post('horas_nocturnidad_corta'));
                $this->db->set('cuantia_horaria_nocturnidad_corta', $chnc);
            }

            if ($this->input->post('horas_nocturnidad_larga'))
            {
                $this->db->set('horas_nocturnidad_larga', number_to_mysql($this->input->post('horas_nocturnidad_larga')));
                // Calcular cuantia horario para nocturnidad larga y almacenar dicho campo
                $chnl = $this->math->G("CHNL");
                $chnl *= number_to_mysql($this->input->post('horas_nocturnidad_larga'));
                $this->db->set('cuantia_horaria_nocturnidad_larga', $chnl);
            }

            // Importe del viaje  + prgresivo
            $this->db->set('importe_viaje_m', $importe_minorista);

            // Cumplimiento de la norma
            $this->db->set('cumplimiento_norma_minorista', $cumplimiento_norma_minorista);

            $this->db->where('entrada_id', $id);
            $this->db->update('entrada');


        // Terminar transaccion
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    //-------------------------------------------------------------------------

    /**
     * Editar
     *
     * Editar el elemento del modulo
     * @return bool
     */
    public function editar($id='')
    {
        $importe_viaje = 0;
        $importe_viaje_progresivo_incrementado = 0;

        $importe_viaje = $this->math->X(
            $this->input->post('fk_capacidad_carga_id'),
            number_to_mysql($this->input->post('km_recorridos_carga')),
            $this->input->post('litros_entregados'),
            $this->input->post('fk_producto_id'),
            $this->input->post('fk_modo_descarga_id'),
            $this->input->post('fk_carga_descarga_id'),
            $this->input->post('numero_de_entregas'),
            $this->input->post('fk_operario_id'));

        $cumplimiento_norma = $this->math->Y(
            $this->input->post('fk_operario_id'),
            $importe_viaje,
            number_to_mysql($this->input->post('horas_de_viaje')));


        // Comenzar transaccion
        $this->db->trans_start();

            // Obtener datos del periodo de pago
            $pp = $this->periodo_pago_m->get_all();
            $fipp = $pp->fecha_inicio_periodo_pago;
            $ffpp = $pp->fecha_final_periodo_pago;


            // ENTRADA

            // Datos requeridos
            $this->db->set('fk_operario_id', $this->input->post('fk_operario_id'));
            $this->db->set('fecha_incidencia', to_sql($this->input->post('fecha_incidencia')));
            $this->db->set('fecha_captacion', to_sql($this->input->post('fecha_captacion')));
            $this->db->set('fecha_inicio_periodo_pago', $fipp);
            $this->db->set('fecha_final_periodo_pago', $ffpp);

            // Datos generales
            if ($this->input->post('fk_capacidad_carga_id')) {
                $this->db->set('fk_capacidad_carga_id', $this->input->post('fk_capacidad_carga_id'));
            }else {
                $this->db->set('fk_capacidad_carga_id', NULL);
            }
            if ($this->input->post('fk_producto_id')) {
                $this->db->set('fk_producto_id', $this->input->post('fk_producto_id'));
            }else {
                $this->db->set('fk_producto_id', NULL);
            }
            if ($this->input->post('hoja_de_ruta')) {
                $this->db->set('hoja_de_ruta', $this->input->post('hoja_de_ruta'));
            }else {
                $this->db->set('hoja_de_ruta', NULL);
            }

            // Vinculacion
            if ($this->input->post('fk_carga_descarga_id')) {
                $this->db->set('fk_carga_descarga_id', $this->input->post('fk_carga_descarga_id'));
            }else {
                $this->db->set('fk_carga_descarga_id', NULL);
            }
            if ($this->input->post('km_recorridos_carga')) {
                $this->db->set('km_recorridos_carga', number_to_mysql($this->input->post('km_recorridos_carga')));
            }else {
                $this->db->set('km_recorridos_carga', NULL);
            }
            if ($this->input->post('litros_entregados')) {
                $this->db->set('litros_entregados', $this->input->post('litros_entregados'));
            }else {
                $this->db->set('litros_entregados', NULL);
            }
            if ($this->input->post('fk_modo_descarga_id')) {
                $this->db->set('fk_modo_descarga_id', $this->input->post('fk_modo_descarga_id'));
            }else {
                $this->db->set('fk_modo_descarga_id', NULL);
            }
            if ($this->input->post('horas_de_viaje')) {
                $this->db->set('horas_de_viaje', number_to_mysql($this->input->post('horas_de_viaje')));
            }else {
                 $this->db->set('horas_de_viaje', NULL);
            }
            if ($this->input->post('numero_de_viajes')) {
                $this->db->set('numero_de_viajes', $this->input->post('numero_de_viajes'));
                // Calcular destajo progresivo
                /*$destajo_progresivo = $this->math->R(
                    $this->input->post('fk_capacidad_carga_id'),
                    $this->input->post('numero_de_viajes'));*/

                // Calcular destajo progresivo (incrementado 2014)
                $destajo_progresivo = $this->math->R1(
                    $this->input->post('fk_capacidad_carga_id'),
                    $this->input->post('numero_de_viajes'),
                    to_sql($this->input->post('fecha_incidencia')),
                    $this->input->post('hoja_de_ruta'),
                    true
                );

                if ($destajo_progresivo !== FALSE){
                    $importe_viaje_progresivo_incrementado = $destajo_progresivo;
                }
            }else {
                $this->db->set('numero_de_viajes', NULL);
            }
            if ($this->input->post('numero_de_entregas')) {
                $this->db->set('numero_de_entregas', $this->input->post('numero_de_entregas'));
            }else {
                $this->db->set('numero_de_entregas', NULL);
            }
            if ($this->input->post('pago_feriado')) {
                $this->db->set('pago_feriado', 1);
            }else {
                $this->db->set('pago_feriado', 0);
            }


            // Sin vinculacion
            if ($this->input->post('horas_ausencia')) {
                $this->db->set('horas_ausencia', number_to_mysql($this->input->post('horas_ausencia')));
            }else {
                $this->db->set('horas_ausencia', NULL);
            }
            if ($this->input->post('fk_causa_ausencia_id')) {
                $this->db->set('fk_causa_ausencia_id', $this->input->post('fk_causa_ausencia_id'));
            }else {
                $this->db->set('fk_causa_ausencia_id', NULL);
            }
            if ($this->input->post('observaciones')) {
                $this->db->set('observaciones', $this->input->post('observaciones'));
            }else {
                $this->db->set('observaciones', "");
            }
            if ($this->input->post('horas_interrupto')) {
                $this->db->set('horas_interrupto', number_to_mysql($this->input->post('horas_interrupto')));
            }else {
                $this->db->set('horas_interrupto', NULL);
            }
            if ($this->input->post('horas_no_vinculado')) {
                $this->db->set('horas_no_vinculado', number_to_mysql($this->input->post('horas_no_vinculado')));
            }else {
                 $this->db->set('horas_no_vinculado', NULL);
            }
            if ($this->input->post('horas_nocturnidad_corta')) {
                $this->db->set('horas_nocturnidad_corta', number_to_mysql($this->input->post('horas_nocturnidad_corta')));
                // Calcular cuantia horario para nocturnidad corta y almacenar dicho campo
                $chnc = $this->math->G("CHNC");
                $chnc *= number_to_mysql($this->input->post('horas_nocturnidad_corta'));
                $this->db->set('cuantia_horaria_nocturnidad_corta', $chnc);
            }else {
                $this->db->set('horas_nocturnidad_corta', NULL);
            }
            if ($this->input->post('horas_nocturnidad_larga')) {
                $this->db->set('horas_nocturnidad_larga', number_to_mysql($this->input->post('horas_nocturnidad_larga')));
                // Calcular cuantia horario para nocturnidad larga y almacenar dicho campo
                $chnl = $this->math->G("CHNL");
                $chnl *= number_to_mysql($this->input->post('horas_nocturnidad_larga'));
                $this->db->set('cuantia_horaria_nocturnidad_larga', $chnl);
            }else {
                $this->db->set('horas_nocturnidad_larga', NULL);
            }
            if ($this->input->post('horas_capacitacion')) {
                $this->db->set('horas_capacitacion', number_to_mysql($this->input->post('horas_capacitacion')));
            }else {
                $this->db->set('horas_capacitacion', NULL);
            }
            if ($this->input->post('horas_movilizacion')) {
                $this->db->set('horas_movilizacion', number_to_mysql($this->input->post('horas_movilizacion')));
            }else {
                $this->db->set('horas_movilizacion', NULL);
            }
            if ($this->input->post('horas_feriado')) {
                $this->db->set('horas_feriado', number_to_mysql($this->input->post('horas_feriado')));
            }else {
                $this->db->set('horas_feriado', NULL);
            }

            // Pago feriado
            if ($this->input->post('pago_feriado'))
            {
                // El importe debe pagarse doble
                $importe_viaje *= 2;
                $importe_viaje_progresivo_incrementado *= 2;
            }

            // Modo de descarga Turbina del equipo
            if ($this->input->post('fk_modo_descarga_id') && $this->input->post('fk_modo_descarga_id') == 5)
            {
                // El importe debe multiplicarse por el coeficiente CDTMA
                $cdtma = $this->math->G('CDTMA');
                $importe_viaje *= $cdtma;
                $importe_viaje_progresivo_incrementado *= $cdtma;
            }

            // Importe del viaje
            $this->db->set('importe_viaje', $importe_viaje);
            $this->db->set('importe_viaje_progresivo_i', $importe_viaje_progresivo_incrementado);

            // Cumplimiento de la norma
            $this->db->set('cumplimiento_norma', $cumplimiento_norma);

            $this->db->where('entrada_id', $id);

            $this->db->update('entrada');

        // Terminar transaccion
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    //-------------------------------------------------------------------------

    /**
     * Eliminar el elemento del modulo
     * @return bool
     */

    public function eliminar()
    {
        $ids = $this->input->post('id');
        if (count($ids) <= 0) return FALSE;

        // Comenzar transaccion
        $this->db->trans_start();

            // Eliminar entradas
            foreach ($ids as $key => $id) {
                $this->db->where('entrada_id', $id);
                $this->db->delete('entrada');
            }
        // Terminar transaccion
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    //-------------------------------------------------------------------------

    /**
     * Get_all
     *
     * Obtener la lista completa del modulo
     */

    public function get_all()
    {
        $this->db->order_by('empresa', 'asc');
        return $this->db->get('empresa');
    }

    //-------------------------------------------------------------------------

    /**
     * Get Producto
     *
     * Obtener lista de productos luego de seleccionar una capacidad de carga
     * (equipo cu?), esto incluye validar si existen tiempos de carga y de descarga
     */
    public function getProducto($capacidad_carga_id='')
    {
        if (empty($capacidad_carga_id)) return FALSE;

        $query = NULL;

        // Comprobar que existen tiempos de carga
        $tcCount = $this->db->get_where('tiempo_carga', array('fk_capacidad_carga_id' => $capacidad_carga_id), 1)->num_rows();
        $tdCount = $this->db->get_where('tiempo_descarga', array('fk_capacidad_carga_id' => $capacidad_carga_id), 1)->num_rows();
        if ($tcCount <= 0 OR $tdCount <= 0) {
            if ($tcCount <= 0) $query['no_time']['tc'] = "noTc";
            if ($tdCount <= 0) $query['no_time']['td'] = "noTd";
            $query['status'] = 'notime';
            return $query;
        }

        // Existen tiempos de carga y de descarga
        // Obtener productos
        $query['sql'] = $this->db->query("
                select p.m_producto_id, p.producto, p.tipo, tc.fk_capacidad_carga_id
                from tiempo_carga tc
                right join m_producto p on p.m_producto_id = tc.fk_producto_id
                where tc.fk_capacidad_carga_id = $capacidad_carga_id
                group by p.m_producto_id");
        $query['status'] = 'ok';
        return $query;
    }

    //-------------------------------------------------------------------------

    /**
     * Get Recorridos
     *
     * Obtener lista de recorridos luego de seleccionar un producto
     */
     public function getRecorridos($producto_id='', $capacidad_carga_id='')
     {
         if (empty($producto_id) OR empty($capacidad_carga_id)) return FALSE;

         // Variables auxiliares
         $query = array();
         $lugares_carga_ids = array();
         $lugares_descarga_ids = array();
         $modos_descarga_ids = array();
         $modos_descarga = array();

         // Obtener lugares de carga
         $sql = $this->db->query("
            select tc.fk_lugar_carga_id
            from tiempo_carga tc
            where tc.fk_capacidad_carga_id = $capacidad_carga_id
            and tc.fk_producto_id = $producto_id");
        foreach ($sql->result() as $lc) {
            $lugares_carga_ids[] = $lc->fk_lugar_carga_id;
        }

        // Obtener lugares de descarga
        $sql = $this->db->query("
            select td.fk_lugar_descarga_id, md.m_modo_descarga_id, md.modo
            from tiempo_descarga td
            left join m_modo_descarga md on md.m_modo_descarga_id = td.fk_modo_descarga_id
            where td.fk_capacidad_carga_id = $capacidad_carga_id
            and td.fk_producto_id = $producto_id
            group by td.fk_lugar_descarga_id
            order by td.fk_lugar_descarga_id asc");
        foreach ($sql->result() as $ldmd) {
            $lugares_descarga_ids[] = $ldmd->fk_lugar_descarga_id;
        }

        // Obtener modos de descarga
        $sql = $this->db->query("
            select md.m_modo_descarga_id, md.modo
            from tiempo_descarga td
            join m_modo_descarga md on md.m_modo_descarga_id = td.fk_modo_descarga_id
            where td.fk_capacidad_carga_id = $capacidad_carga_id
            and td.fk_producto_id = $producto_id
            group by md.m_modo_descarga_id
            order by td.fk_lugar_descarga_id asc");
        $i = 0;
        foreach ($sql->result() as $md) {
            $modos_descarga[$i]['m_modo_descarga_id'] = $md->m_modo_descarga_id;
            $modos_descarga[$i]['modo'] = $md->modo;
            $i++;
        }
        $query["lista_modos_descarga"] = $modos_descarga;

        // Finalmente obtener los recorridos para este equipo con este producto
        $this->db->select("lc.lugar_carga, ld.lugar_descarga, cd.carga_descarga_id, cd.codigo");
        $this->db->join('m_lugar_carga lc', 'lc.m_lugar_carga_id = cd.fk_lugar_carga_id', 'left');
        $this->db->join('m_lugar_descarga ld', 'ld.m_lugar_descarga_id = cd.fk_lugar_descarga_id', 'left');
        $this->db->order_by('cd.codigo', 'asc');
        for ($i=0, $ic = count($lugares_carga_ids); $i < $ic; $i++) {
            for ($j=0, $jc = count($lugares_descarga_ids); $j < $jc; $j++) {
                if ($i != 0) {
                    $this->db->or_where('lc.m_lugar_carga_id', $lugares_carga_ids[$i]);
                }else{
                    $this->db->where('lc.m_lugar_carga_id', $lugares_carga_ids[$i]);
                }
                $this->db->or_where('ld.m_lugar_descarga_id', $lugares_descarga_ids[$j]);
            }
        }
        $cd = $this->db->get('carga_descarga cd');
        if ($cd->num_rows() <= 0) {
            $query['status'] = "nocd";
            $query['no_cd'] = "noCd";
        }else{
            $query['status'] = 'sql';
            $query['lista_recorridos'] = $cd;
        }

        return $query;
     }

    //-------------------------------------------------------------------------

    public function getMunicipios($producto_id='', $capacidad_carga_id='')
    {
        if (empty($producto_id) OR empty($capacidad_carga_id)) return FALSE;
        $query = array();

        // 1. Obtener lugares de descarga
        $sql = $this->db->query("
            select distinct ld.m_lugar_descarga_id, ld.lugar_descarga
            from m_lugar_descarga ld
            join tiempo_descarga td on td.fk_lugar_descarga_id = ld.m_lugar_descarga_id
            join m_capacidad_carga cc on cc.m_capacidad_carga_id = td.fk_capacidad_carga_id
            join m_producto p on p.m_producto_id = td.fk_producto_id
            where
            cc.m_capacidad_carga_id = $capacidad_carga_id
            and (ld.velocidad_media_a_k is not null or ld.velocidad_media_d is not null)
            and p.m_producto_id = $producto_id");

        if ($sql->num_rows() > 0)
        {
            $query['ld_status'] = "1";
            $query['ld_list'] = $sql->result();
        }
        else
        {
            $query['ld_status'] = "0";
        }

        // 2. Obtener modos de descarga
        $sql = $this->db->query("
            select
            md.m_modo_descarga_id, md.modo
            from tiempo_descarga td
            join m_modo_descarga md on md.m_modo_descarga_id = td.fk_modo_descarga_id
            where td.fk_capacidad_carga_id = $capacidad_carga_id
            and td.fk_producto_id = $producto_id
            group by md.m_modo_descarga_id
            order by td.fk_lugar_descarga_id asc");

        if ($sql->num_rows() > 0)
        {
            $query['md_status'] = "1";
            $query['md_list'] = $sql->result();
        }
        else
        {
            $query['md_status'] = "0";
        }

        return $query;
    }

    //-------------------------------------------------------------------------

    public function getLugarCarga($producto_id='', $capacidad_carga_id='')
    {
        if (empty($producto_id) OR empty($capacidad_carga_id)) return FALSE;
        $query = array();

        // 1. Obtener lista de lugares de carga segun el producto y el equipo seleccionado
        $sql = $this->db->query("
            select lc.m_lugar_carga_id, lc.lugar_carga
            from m_lugar_carga lc
            join capacidad_bombeo_lugar_carga cb on cb.fk_lugar_carga_id = lc.m_lugar_carga_id
            join m_producto p on  p.m_producto_id = cb.fk_producto_id
            join m_capacidad_carga cc on cc.tipo_de_producto = p.tipo
            where p.m_producto_id = $producto_id
            and cc.m_capacidad_carga_id = $capacidad_carga_id
            order by lc.lugar_carga asc");

        if ($sql->num_rows() > 0)
        {
            $query['lc_status'] = "1";
            $query['lc_list'] = $sql->result();
        }
        else
        {
            $query['lc_status'] = "0";
        }

        return $query;
    }
}