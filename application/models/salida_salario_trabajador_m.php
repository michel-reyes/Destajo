<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
DESTAJO-MODULE

date: 2014.05.20
type: php module
path: application/models/salida_salario_trabajador_m.php

DESTAJO-MODULE-END
*/

class Salida_salario_trabajador_m extends CI_Model {
	
	protected $fields = array(
        'salida_salario_trabajador_id', 
        'chapa',
        'nombre',
        'apellidos',
        'horas_viaje',
        'horas_viaje_m',
        'importe_viaje',
        'importe_viaje_m',
        'cumplimiento_norma',
        'cumplimiento_norma_m',
        'horas_interrupto',
        'horas_interrupto_m',
        'horas_no_vinculado',
        'horas_no_vinculado_m',
        'horas_nocturnidad_corta',
        'horas_nocturnidad_larga',
        'horas_nocturnidad_corta_m',
        'horas_nocturnidad_larga_m',
        'cuantia_horaria_nocturnidad_corta',
        'cuantia_horaria_nocturnidad_larga',
        'cuantia_horaria_nocturnidad_corta_m',
        'cuantia_horaria_nocturnidad_larga_m',
        //'horas_capacitacion',
        //'horas_movilizado',
        //'horas_feriado',
        //'horas_ausencia',
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
        return $this->db->count_all_results('salida_salario_trabajador');
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
             $ofield = $this->session->userdata('ofield_salida_salario_trabajador') ? $this->session->userdata('ofield_salida_salario_trabajador') : "chapa";
             $otype = $this->session->userdata('otype_salida_salario_trabajador') ? $this->session->userdata('otype_salida_salario_trabajador') : "asc";
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
         $query_temp = $this->db->get('salida_salario_trabajador');
         
         // Antes de procesar la consulta enviarla a un fichero temporal
         // para que pueda ser utilizada por la funcion Exportar
         $data = $this->db->last_query();         
         if (! write_file("./query_temp/sst_query_temp.tmp", $data)) continue;
         
         $query['num_rows_wo_limit'] = $query_temp->num_rows();
         if ($limit!='' OR $offset!='') $this->db->limit($limit, $offset);
        
         // Obtener registros
         //-----------------------------
         $query['rows'] = $this->db->get('salida_salario_trabajador');
         $query['num_rows'] = $query['rows']->num_rows();
         $this->db->flush_cache();
        
         // Almacenar ordenar en session
         //-----------------------------
         $this->session->set_userdata('order_field_salida_salario_trabajador', $ofield);
         $this->session->set_userdata('order_type_salida_salario_trabajador', $otype);
        
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
                $campo = $this->session->userdata('buscar_campo_salida_salario_trabajador');
                $criterio = $this->session->userdata('buscar_criterio_salida_salario_trabajador');
                $texto = $this->session->userdata('buscar_texto_salida_salario_trabajador');
                $periodo = $this->session->userdata('search_periodo_salida_st');
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
            if ($this->input->post('b_periodo')) $this->session->set_userdata('search_periodo_salida_st', $this->input->post('b_periodo'));
            if ($this->input->post('buscar_campo')) $this->session->set_userdata('buscar_campo_salida_salario_trabajador', $this->input->post('buscar_campo'));
            if ($this->input->post('buscar_criterio')) $this->session->set_userdata('buscar_criterio_salida_salario_trabajador', $this->input->post('buscar_criterio'));
            if ($this->input->post('buscar_texto')) $this->session->set_userdata('buscar_texto_salida_salario_trabajador', $this->input->post('buscar_texto'));
        }
        
        // Se mando a buscar y no hay texto en la busqueda ni en session
        // so, eliminar de session
        if ($buscar_btn_avanzado AND ! $campo) {
            $this->session->unset_userdata('search_periodo_salida_st');
            $this->session->unset_userdata('buscar_campo_salida_salario_trabajador');
            $this->session->unset_userdata('buscar_criterio_salida_salario_trabajador');
            $this->session->unset_userdata('buscar_texto_salida_salario_trabajador');
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
        $this->db->where('salida_salario_trabajador_id', $id);
        return $this->db->get('salida_salario_trabajador')->row();
    }

	
	//-------------------------------------------------------------------------
    
    /**
     * Get_all
     * 
     * Obtener la lista completa del modulo
     */
    
    public function get_all()
    {
    	$this->db->order_by('salida_salario_trabajador_id', 'asc');
        return $this->db->get('salida_salario_trabajador');
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
            
            // Eliminar salida_salario_trabajador_id
            foreach ($ids as $key => $id) {
                $this->db->where('salida_salario_trabajador_id', $id);
                $this->db->delete('salida_salario_trabajador');
            }
        // Terminar transaccion
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    // ------------------------------------------------------------------------

    public function get_all_period()
    {
        $pp = $this->periodo_pago_m->get_all();
        $fipp = $pp->fecha_inicio_periodo_pago;
        $ffpp = $pp->fecha_final_periodo_pago;

        $this->db->join('m_operario o', 'o.chapa = salida_salario_trabajador.chapa');

        $this->db->where('fecha_final_periodo_pago >=', $fipp);
        $this->db->where('fecha_final_periodo_pago <=', $ffpp);
        return $this->db->get('salida_salario_trabajador');
    }
    
    //-------------------------------------------------------------------------
    
    /**
    * calcular la salida salario trabajador
    */
    public function autoCalc()
    {
        // Iniciar trasaccion
        $this->db->trans_start();

            // Obtener datos del periodo de pago
            $pp = $this->periodo_pago_m->get_all();
            $fipp = $pp->fecha_inicio_periodo_pago;
            $ffpp = $pp->fecha_final_periodo_pago;

            $sumatoria_importe_viaje = 0;
            $sumatoria_horas_viaje = 0;
            $tarifa_completa = 0;
            $cumplimiento_norma = 0;

            // 0- Eliminar de la tabla salida salario trabajador
            // todos los calculos que se ajusten al periodo actual
            $this->db->where('fecha_final_periodo_pago >=', $fipp);
            $this->db->where('fecha_final_periodo_pago <=', $ffpp);
            $this->db->delete('salida_salario_trabajador');

            // 1- Calcular los datos de la tabla entrada para mayorista

            // 1.1- Hacer consulta a entradas ordenadas por operario
            $this->db->join('m_operario o', 'o.m_operario_id = entrada.fk_operario_id');
            $this->db->where('fecha_inicio_periodo_pago >=', $fipp);
            $this->db->where('fecha_final_periodo_pago <=', $ffpp);
            $this->db->order_by('fk_operario_id', 'desc');
            $query = $this->db->get('entrada');

            // 1.2- Sumar los datos de cada operario
            $sum = array();
            $operario_temp = 0;
            foreach ($query->result() as $e)
            {
                if ($operario_temp != $e->fk_operario_id)
                {
                    $sum[$e->fk_operario_id]['chapa']       = $e->chapa;
                    $sum[$e->fk_operario_id]['nombre']      = $e->nombre;
                    $sum[$e->fk_operario_id]['apellidos']   = $e->apellidos;
                    $sum[$e->fk_operario_id]['operario_id'] = $e->m_operario_id;

                    // igualar operarios
                    $operario_temp = $e->fk_operario_id;
                }
                
                $sum[$e->fk_operario_id]['horas_interrupto']                  += $e->horas_interrupto;
                $sum[$e->fk_operario_id]['horas_no_vinculado']                += $e->horas_no_vinculado;
                $sum[$e->fk_operario_id]['horas_nocturnidad_corta']           += $e->horas_nocturnidad_corta;         
                $sum[$e->fk_operario_id]['cuantia_horaria_nocturnidad_corta'] += $e->cuantia_horaria_nocturnidad_corta;
                $sum[$e->fk_operario_id]['horas_nocturnidad_larga']           += $e->horas_nocturnidad_larga;
                $sum[$e->fk_operario_id]['cuantia_horaria_nocturnidad_larga'] += $e->cuantia_horaria_nocturnidad_larga;
                $sum[$e->fk_operario_id]['horas_capacitacion']                += $e->horas_capacitacion;
                $sum[$e->fk_operario_id]['horas_movilizacion']                += $e->horas_movilizacion;
                $sum[$e->fk_operario_id]['horas_feriado']                     += $e->horas_feriado;
                $sum[$e->fk_operario_id]['horas_ausencia']                    += $e->horas_ausencia;

                // Analisis de horas de viaje (solo mayoristas)
                if ( $e->importe_viaje_m == NULL )
                    $sum[$e->fk_operario_id]['horas_de_viaje'] += $e->horas_de_viaje;

                // Analisis de importe del viaje
                if ( $e->importe_viaje_progresivo_i != NULL && $e->importe_viaje_progresivo_i != 0.00 )
                   $sum[$e->fk_operario_id]['importe_viaje'] += $e->importe_viaje_progresivo_i;
                else
                    $sum[$e->fk_operario_id]['importe_viaje'] += $e->importe_viaje;
            }


            // 2- Guardar todo el calculo en la tabla salida salario por trabajador
            /**
             * ---TIP---
             * Para poder agregar el cumplimiento de la norma se debe calcular
             *  ya que no es la sumatoria de los cumplimientos de norma, sino
             *  que es cn = (sumatoria(importe_viaje)/(sumatoria(horas_viaje) * tarifa_completa))*100
             */

            foreach ($sum as $key => $value)
            {
                $sumatoria_horas_viaje = $sum[$key]['horas_de_viaje'];
                $sumatoria_importe_viaje = $sum[$key]['importe_viaje'];
                $tarifa_completa = $this->math->O($sum[$key]['operario_id']);                
                $cumplimiento_norma = round( ( ( $sumatoria_importe_viaje /  ( $sumatoria_horas_viaje * $tarifa_completa ) ) * 100 ), 2 );
                

                $this->db->set('chapa', $sum[$key]['chapa']);
                $this->db->set('nombre', $sum[$key]['nombre']);
                $this->db->set('apellidos', $sum[$key]['apellidos']);
                $this->db->set('horas_viaje', $sum[$key]['horas_de_viaje']);
                $this->db->set('importe_viaje', $sum[$key]['importe_viaje']);
                $this->db->set('cumplimiento_norma', $cumplimiento_norma);
                $this->db->set('horas_interrupto', $sum[$key]['horas_interrupto']);
                $this->db->set('horas_no_vinculado', $sum[$key]['horas_no_vinculado']);
                $this->db->set('horas_nocturnidad_corta', $sum[$key]['horas_nocturnidad_corta']);
                $this->db->set('cuantia_horaria_nocturnidad_corta', $sum[$key]['cuantia_horaria_nocturnidad_corta']);
                $this->db->set('horas_nocturnidad_larga', $sum[$key]['horas_nocturnidad_larga']);
                $this->db->set('cuantia_horaria_nocturnidad_larga', $sum[$key]['cuantia_horaria_nocturnidad_larga']);
                $this->db->set('fecha_inicio_periodo_pago', $fipp);
                $this->db->set('fecha_final_periodo_pago', $ffpp);
                $this->db->insert('salida_salario_trabajador');
            }


            // 4- Calcular los datos de la tabla entrada para minorista
            $query_m = $this->db->query("
            select
                o.chapa, o.nombre, o.apellidos, e.fecha_inicio_periodo_pago, e.fecha_final_periodo_pago, o.m_operario_id,
                sum(e.horas_de_viaje) as horas_de_viaje,
                sum(e.importe_viaje_m) as importe_viaje,
                sum(e.horas_interrupto) as horas_interrupto,
                sum(e.horas_no_vinculado) as horas_no_vinculado,
                sum(e.horas_nocturnidad_corta) as horas_nocturnidad_corta,
                sum(e.cuantia_horaria_nocturnidad_corta) as cuantia_horaria_nocturnidad_corta,
                sum(e.horas_nocturnidad_larga) as horas_nocturnidad_larga,
                sum(e.cuantia_horaria_nocturnidad_larga) as cuantia_horaria_nocturnidad_larga,
                sum(e.horas_capacitacion) as horas_capacitacion,
                sum(e.horas_movilizacion) as horas_movilizado,
                sum(e.horas_feriado) as horas_feriado,
                sum(e.horas_ausencia) as horas_ausencia
            from entrada e
            join m_operario o on o.m_operario_id = e.fk_operario_id
            where e.fecha_inicio_periodo_pago >= $fipp
            and e.fecha_final_periodo_pago <= $ffpp
            and e.importe_viaje_m is not null
            group by o.m_operario_id");

            // 5- Cumplimiento de la norma minorista
            foreach ($query_m->result() as $calc) {

                $sumatoria_horas_viaje = $calc->horas_de_viaje;
                $sumatoria_importe_viaje = $calc->importe_viaje;
                $tarifa_completa = $this->math->O($calc->m_operario_id);
                $cumplimiento_norma = round( ( ( $sumatoria_importe_viaje /  ( $sumatoria_horas_viaje * $tarifa_completa ) ) * 100 ), 2 );

                // 6- Buscar en la tabla salida salario trabajador el operario,
                // SI existe actualizar datos
                // No existe insertar datos

                $q = $this->db->query('select sst.chapa from salida_salario_trabajador sst where sst.chapa = ' . $calc->chapa . ' limit 1');
                if ($q->num_rows() > 0)
                {
                    $this->db->set('horas_viaje_m', $calc->horas_de_viaje);
                    $this->db->set('importe_viaje_m', $calc->importe_viaje);
                    $this->db->set('cumplimiento_norma_m', $cumplimiento_norma);
                    $this->db->set('horas_interrupto_m', $calc->horas_interrupto);
                    $this->db->set('horas_no_vinculado_m', $calc->horas_no_vinculado);
                    $this->db->set('cuantia_horaria_nocturnidad_corta_m', $calc->cuantia_horaria_nocturnidad_corta);
                    $this->db->set('horas_nocturnidad_corta_m', $calc->horas_nocturnidad_corta);
                    $this->db->set('cuantia_horaria_nocturnidad_larga_m', $calc->cuantia_horaria_nocturnidad_larga);
                    $this->db->set('horas_nocturnidad_larga_m', $calc->horas_nocturnidad_larga);
                    $this->db->where('chapa', $calc->chapa);
                    $this->db->update('salida_salario_trabajador');
                }
                else if ($q->num_rows() <= 0)
                {
                    $this->db->set('chapa', $calc->chapa);
                    $this->db->set('nombre', $calc->nombre);
                    $this->db->set('apellidos', $calc->apellidos);
                    $this->db->set('horas_viaje', $calc->horas_de_viaje);
                    $this->db->set('fecha_inicio_periodo_pago', $fipp);
                    $this->db->set('fecha_final_periodo_pago', $ffpp);

                    $this->db->set('horas_viaje_m', $calc->horas_de_viaje);
                    $this->db->set('importe_viaje_m', $calc->importe_viaje);
                    $this->db->set('cumplimiento_norma_m', $cumplimiento_norma);
                    $this->db->set('horas_interrupto_m', $calc->horas_interrupto);
                    $this->db->set('horas_no_vinculado_m', $calc->horas_no_vinculado);
                    $this->db->set('cuantia_horaria_nocturnidad_corta_m', $calc->cuantia_horaria_nocturnidad_corta);
                    $this->db->set('horas_nocturnidad_corta_m', $calc->horas_nocturnidad_corta);
                    $this->db->set('cuantia_horaria_nocturnidad_larga_m', $calc->cuantia_horaria_nocturnidad_larga);
                    $this->db->set('horas_nocturnidad_larga_m', $calc->horas_nocturnidad_larga);

                    $this->db->insert('salida_salario_trabajador');
                }
            }


        // Finalizar trasaccion
        $this->db->trans_complete();
        return $this->db->trans_status();     
        
    }
}