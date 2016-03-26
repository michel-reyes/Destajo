<?php 

/*
DESTAJO-MODULE

date: 2014.03.20
type: php module
path: application/models/salida_cumplimiento_norma_m.php

DESTAJO-MODULE-END
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/models/salida_cumplimiento_norma_m.php

DESTAJO-MODULE-END
*/

class Salida_cumplimiento_norma_m extends CI_Model {

	protected $fields = array(
        'salida_cumplimiento_norma_id',
        'producto',
        'cumplimiento_norma',
        'cumplimiento_norma_minorista',
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
        return $this->db->count_all_results('salida_cumplimiento_norma');
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
             $ofield = $this->session->userdata('ofield_salida_cumplimiento_norma') ? $this->session->userdata('ofield_salida_cumplimiento_norma') : "producto";
             $otype = $this->session->userdata('otype_salida_cumplimiento_norma') ? $this->session->userdata('otype_salida_cumplimiento_norma') : "asc";
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
         $query_temp = $this->db->get('salida_cumplimiento_norma');

         // Antes de procesar la consulta enviarla a un fichero temporal
         // para que pueda ser utilizada por la funcion Exportar
         $data = $this->db->last_query();
         if (! write_file("./query_temp/scn_query_temp.tmp", $data)) continue;

         $query['num_rows_wo_limit'] = $query_temp->num_rows();
         if ($limit!='' OR $offset!='') $this->db->limit($limit, $offset);

         // Obtener registros
         //-----------------------------
         $query['rows'] = $this->db->get('salida_cumplimiento_norma');
         $query['num_rows'] = $query['rows']->num_rows();
         $this->db->flush_cache();

         // Almacenar ordenar en session
         //-----------------------------
         $this->session->set_userdata('order_field_salida_cumplimiento_norma', $ofield);
         $this->session->set_userdata('order_type_salida_cumplimiento_norma', $otype);

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
                $campo = $this->session->userdata('buscar_campo_salida_cumplimiento_norma');
                $criterio = $this->session->userdata('buscar_criterio_salida_cumplimiento_norma');
                $texto = $this->session->userdata('buscar_texto_salida_cumplimiento_norma');
                $periodo = $this->session->userdata('search_periodo_salida_cn');
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
            if ($this->input->post('b_periodo')) $this->session->set_userdata('search_periodo_salida_cn', $this->input->post('b_periodo'));
            if ($this->input->post('buscar_campo')) $this->session->set_userdata('buscar_campo_salida_cumplimiento_norma', $this->input->post('buscar_campo'));
            if ($this->input->post('buscar_criterio')) $this->session->set_userdata('buscar_criterio_salida_cumplimiento_norma', $this->input->post('buscar_criterio'));
            if ($this->input->post('buscar_texto')) $this->session->set_userdata('buscar_texto_salida_cumplimiento_norma', $this->input->post('buscar_texto'));
        }

        // Se mando a buscar y no hay texto en la busqueda ni en session
        // so, eliminar de session
        if ($buscar_btn_avanzado AND ! $campo) {
            $this->session->unset_userdata('search_periodo_salida_cn');
            $this->session->unset_userdata('buscar_campo_salida_cumplimiento_norma');
            $this->session->unset_userdata('buscar_criterio_salida_cumplimiento_norma');
            $this->session->unset_userdata('buscar_texto_salida_cumplimiento_norma');
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
        $this->db->where('salida_cumplimiento_norma_id', $id);
        return $this->db->get('salida_cumplimiento_norma')->row();
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

			// Eliminar salida_cumplimiento_normas
			foreach ($ids as $key => $id) {
				$this->db->where('salida_cumplimiento_norma_id', $id);
	        	$this->db->delete('salida_cumplimiento_norma');
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
    	$this->db->order_by('producto', 'asc');
        return $this->db->get('salida_cumplimiento_norma');
    }

    //-------------------------------------------------------------------------

    /**
    * calcular el cumplimiento de la norma
    */
    public function autoCalc()
    {
        // Iniciar trasaccion
        $this->db->trans_start();

            // Obtener datos del periodo de pago
            $pp = $this->periodo_pago_m->get_all();
            $fipp = $pp->fecha_inicio_periodo_pago;
            $ffpp = $pp->fecha_final_periodo_pago;

            // 1- Calcular el cumplimiento de la norma por producto
            $query = $this->db->query("select
                p.producto,
                e.fecha_inicio_periodo_pago,
                e.fecha_final_periodo_pago,
                avg(e.cumplimiento_norma) as cumplimiento_norma,
                avg(e.cumplimiento_norma_minorista) as cumplimiento_norma_minorista
            from entrada e
            right join m_producto p on p.m_producto_id = e.fk_producto_id
            where e.fecha_inicio_periodo_pago >= $fipp
            and e.fecha_final_periodo_pago <= $ffpp
            group by p.m_producto_id");


            // 2- Eliminar de la tabla cumplimiento de la norma
            // todos los calculos que se ajusten al periodo actual
            $this->db->where('fecha_inicio_periodo_pago >=', $fipp);
            $this->db->where('fecha_final_periodo_pago <=', $ffpp);
            $this->db->delete('salida_cumplimiento_norma');

            // 3- Guardar todo el calculo en la tabla salida cumplimiento de la norma
            foreach ($query->result() as $calc) {
                $this->db->set('producto', $calc->producto);
                $this->db->set('cumplimiento_norma', $calc->cumplimiento_norma);
                $this->db->set('cumplimiento_norma_minorista', $calc->cumplimiento_norma_minorista);
                $this->db->set('fecha_inicio_periodo_pago', $calc->fecha_inicio_periodo_pago);
                $this->db->set('fecha_final_periodo_pago', $calc->fecha_final_periodo_pago);
                $this->db->insert('salida_cumplimiento_norma');
            }

        // Finalizar trasaccion
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    //-------------------------------------------------------------------------

    /**
     * Calcular cumplimiento de la norma general
     */

    public function autoCal_cumplimiento_norma_general()
    {
        // Obtener datos del periodo de pago
        $pp = $this->periodo_pago_m->get_all();
        $fipp = $pp->fecha_inicio_periodo_pago;
        $ffpp = $pp->fecha_final_periodo_pago;

        return $this->db->query("select avg(e.cumplimiento_norma) as cn, avg(e.cumplimiento_norma_minorista) cnm
                          from entrada e where e.fecha_inicio_periodo_pago >= $fipp
                             and e.fecha_final_periodo_pago <= $ffpp limit 1")->row();

    }
}