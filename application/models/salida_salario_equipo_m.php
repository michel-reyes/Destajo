<?php 

/*
DESTAJO-MODULE

date: 2014.03.20
type: php module
path: application/models/salida_salario_equipo_m.php

DESTAJO-MODULE-END
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salida_salario_equipo_m extends CI_Model {

	protected $fields = array(
        'salida_salario_equipo_id',
        'numero_operacional_equipo',
        'numero_operacional_cuna',
        'importe_viaje',
        'fecha_inicio_periodo_pago',
        'fecha_final_periodo_pago'
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
        return $this->db->count_all_results('salida_salario_equipo');
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
             $ofield = $this->session->userdata('ofield_salida_salario_equipo') ? $this->session->userdata('ofield_salida_salario_equipo') : "numero_operacional_equipo";
             $otype = $this->session->userdata('otype_salida_salario_equipo') ? $this->session->userdata('otype_salida_salario_equipo') : "asc";
         }
         $this->db->order_by($ofield, $otype);

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
         $query_temp = $this->db->get('salida_salario_equipo');

         // Antes de procesar la consulta enviarla a un fichero temporal
         // para que pueda ser utilizada por la funcion Exportar
         $data = $this->db->last_query();
         if (! write_file("./query_temp/sse_query_temp.tmp", $data)) continue;

         $query['num_rows_wo_limit'] = $query_temp->num_rows();
         if ($limit!='' OR $offset!='') $this->db->limit($limit, $offset);

         // Obtener registros
         //-----------------------------
         $query['rows'] = $this->db->get('salida_salario_equipo');
         $query['num_rows'] = $query['rows']->num_rows();
         $this->db->flush_cache();

         // Almacenar ordenar en session
         //-----------------------------
         $this->session->set_userdata('order_field_salida_salario_equipo', $ofield);
         $this->session->set_userdata('order_type_salida_salario_equipo', $otype);

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
                $campo = $this->session->userdata('buscar_campo_salida_salario_equipo');
                $criterio = $this->session->userdata('buscar_criterio_salida_salario_equipo');
                $texto = $this->session->userdata('buscar_texto_salida_salario_equipo');
                $periodo = $this->session->userdata('search_periodo_salida_se');
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
            if ($this->input->post('b_periodo')) $this->session->set_userdata('search_periodo_salida_se', $this->input->post('b_periodo'));
            if ($this->input->post('buscar_campo')) $this->session->set_userdata('buscar_campo_salida_salario_equipo', $this->input->post('buscar_campo'));
            if ($this->input->post('buscar_criterio')) $this->session->set_userdata('buscar_criterio_salida_salario_equipo', $this->input->post('buscar_criterio'));
            if ($this->input->post('buscar_texto')) $this->session->set_userdata('buscar_texto_salida_salario_equipo', $this->input->post('buscar_texto'));
        }

        // Se mando a buscar y no hay texto en la busqueda ni en session
        // so, eliminar de session
        if ($buscar_btn_avanzado AND ! $campo) {
            $this->session->unset_userdata('search_periodo_salida_se');
            $this->session->unset_userdata('buscar_campo_salida_salario_equipo');
            $this->session->unset_userdata('buscar_criterio_salida_salario_equipo');
            $this->session->unset_userdata('buscar_texto_salida_salario_equipo');
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
        $this->db->where('salida_salario_equipo_id', $id);
        return $this->db->get('salida_salario_equipo')->row();
    }

	//-------------------------------------------------------------------------

    /**
     * Get_all
     *
     * Obtener la lista completa del modulo
     */

    public function get_all()
    {
    	$this->db->order_by('salida_salario_equipo_id', 'asc');
        return $this->db->get('salida_salario_equipo');
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

            // Eliminar salida_salario_equipo
            foreach ($ids as $key => $id) {
                $this->db->where('salida_salario_equipo_id', $id);
                $this->db->delete('salida_salario_equipo');
            }
        // Terminar transaccion
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    //-------------------------------------------------------------------------

    /**
    * calcular la salida salario equipo
    */
    public function autoCalc()
    {
        // Iniciar trasaccion
        $this->db->trans_start();

            // Obtener datos del periodo de pago
            $pp = $this->periodo_pago_m->get_all();
            $fipp = $pp->fecha_inicio_periodo_pago;
            $ffpp = $pp->fecha_final_periodo_pago;

            // 0- Eliminar de la tabla salida salario de equipo
            // todos los registros que se ajusten al periodo e pago actual
            $this->db->where('fecha_inicio_periodo_pago >=', $fipp);
            $this->db->where('fecha_final_periodo_pago <=', $ffpp);
            $this->db->delete('salida_salario_equipo');

            // 1- Calcular los datos de la tabla entrada

            // 1.1  Hacer consulta a entradas ordenada por capacidad de carga (equipo)
            $this->db->select('e.fk_capacidad_carga_id as cc, me.numero_operacional as nome, c.numero_operacional as noc, e.importe_viaje as iv, e.importe_viaje_progresivo_i as ivpi, e.importe_viaje_m as ivm');
            $this->db->join('m_capacidad_carga cc', 'cc.m_capacidad_carga_id = e.fk_capacidad_carga_id');
            $this->db->join('m_equipo me', 'me.m_equipo_id = cc.fk_equipo_id');
            $this->db->join('m_cuna c', 'c.m_cuna_id = cc.fk_cuna_id', 'left');
            $this->db->where('e.fecha_inicio_periodo_pago >=', $fipp);
            $this->db->where('e.fecha_final_periodo_pago <=', $ffpp);
            $this->db->order_by('cc.m_capacidad_carga_id', 'desc');
            $query = $this->db->get('entrada e');

            
            // 1.2 Sumar los datos de cada equipo
            $sum = array();
            $equipo_temp = 0;
            foreach ($query->result() as $e) 
            {
                if ($equipo_temp != $e->cc)
                {
                    $sum[$e->cc]['nome'] = $e->nome;
                    $sum[$e->cc]['noc'] = $e->noc;

                    // igualar equipos
                    $equipo_temp = $e->cc;
                }

                // Analisis de importe del viaje
                if ( $e->ivpi != NULL && $e->ivpi != 0.00 )
                   $sum[$e->cc]['iv'] += $e->ivpi;
                else
                    $sum[$e->cc]['iv'] += $e->iv;

                // sumar importe del viaje minorista
                if ($e->ivm != NULL && $e->ivm != 0.00)
                    $sum[$e->cc]['iv'] += $e->ivm;
            }
            

            //2- Guardar todo el calculo en la tabla salida salario por equipo
            foreach ($sum as $key => $value) 
            {
                $this->db->set('numero_operacional_equipo', $sum[$key]['nome']);
                $this->db->set('numero_operacional_cuna', $sum[$key]['noc']);
                $this->db->set('importe_viaje', $sum[$key]['iv']);
                $this->db->set('fecha_inicio_periodo_pago', $fipp);
                $this->db->set('fecha_final_periodo_pago', $ffpp);

                $this->db->insert('salida_salario_equipo');
            }


        // Finalizar trasaccion
        $this->db->trans_complete();

        return $this->db->trans_status();

    }

}