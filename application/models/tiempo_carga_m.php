<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tiempo_carga_m extends CI_Model {
    
    protected $fields = array(
        'tiempo_carga_id', 
        'fk_capacidad_carga_id',
        'fk_producto_id',
        'fk_lugar_carga_id',
        'tiempo_carga',
        // Capacidad de carga
        'm_capacidad_carga_id',
        'm_capacidad_carga.tipo_de_producto as tipo_producto',
        'm_capacidad_carga.fk_equipo_id',
        'm_capacidad_carga.fk_cuna_id',
        'm_capacidad_carga.tipo_de_producto',
        // Equipo
        'm_equipo.m_equipo_id',
        'm_equipo.numero_operacional as no_equipo',
        // Cuña
        'm_cuna.m_cuna_id',
        'm_cuna.numero_operacional as no_cuna',
        // Producto
        'm_producto_id',
        'producto',
        'm_producto.tipo as tipo',
        // Lugar de carga
        'm_lugar_carga_id',
        'lugar_carga'
    );
    
    //-------------------------------------------------------------------------
		
	/**
     * count_all
     * 
     * Obtiene el numero real de registros
     * @return double
     */
     
    public function count_all()
    {
        return $this->db->count_all_results('tiempo_carga');
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
             $ofield = $this->session->userdata('ofield_tiempo_carga') ? $this->session->userdata('ofield_tiempo_carga') : "no_equipo";
             $otype = $this->session->userdata('otype_tiempo_carga') ? $this->session->userdata('otype_tiempo_carga') : "asc";
         }
         $this->db->order_by($ofield, $otype);
         
         // Join
         //----------------------------
         $this->db->join('m_capacidad_carga', 'm_capacidad_carga.m_capacidad_carga_id = tiempo_carga.fk_capacidad_carga_id', 'left');
         $this->db->join('m_producto', 'm_producto.m_producto_id = tiempo_carga.fk_producto_id', 'left');
         $this->db->join('m_lugar_carga', 'm_lugar_carga.m_lugar_carga_id = tiempo_carga.fk_lugar_carga_id', 'left');
         $this->db->join('m_equipo', 'm_equipo.m_equipo_id = m_capacidad_carga.fk_equipo_id', 'left');
         $this->db->join('m_cuna', 'm_cuna.m_cuna_id = m_capacidad_carga.fk_cuna_id', 'left');
         
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
         $query['num_rows_wo_limit'] = $this->db->get('tiempo_carga')->num_rows();
         if ($limit!='' OR $offset!='') $this->db->limit($limit, $offset);
        
         // Obtener registros
         //-----------------------------
         $query['rows'] = $this->db->get('tiempo_carga');
         $query['num_rows'] = $query['rows']->num_rows();
         $this->db->flush_cache();
        
         // Almacenar ordenar en session
         //-----------------------------
         $this->session->set_userdata('order_field_tiempo_carga', $ofield);
         $this->session->set_userdata('order_type_tiempo_carga', $otype);
        
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
        
        // No se mando a buscar, pero hay busquedas almacenadas en session
        if (! $buscar_btn_avanzado) {
            if (! $campo OR count($campo) <= 0) {
                $campo = $this->session->userdata('buscar_campo_tiempo_carga');
                $criterio = $this->session->userdata('buscar_criterio_tiempo_carga');
                $texto = $this->session->userdata('buscar_texto_tiempo_carga');
            }
        }
        
        // Buscar
        if ($campo AND count($campo) > 0) {
            foreach ($campo as $key => $value) {
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
        }

        // Se mando a buscar y hay texto, so alacenarlo en session,
        // no almacenar lo que ya esta almacenado, so preguntar si se envio abuscar
        if ($buscar_btn_avanzado AND $campo) {
            if ($this->input->post('buscar_campo')) $this->session->set_userdata('buscar_campo_tiempo_carga', $this->input->post('buscar_campo'));
            if ($this->input->post('buscar_criterio')) $this->session->set_userdata('buscar_criterio_tiempo_carga', $this->input->post('buscar_criterio'));
            if ($this->input->post('buscar_texto')) $this->session->set_userdata('buscar_texto_tiempo_carga', $this->input->post('buscar_texto'));
        }
        
        // Se mando a buscar y no hay texto en la busqueda ni en session
        // so, eliminar de session
        if ($buscar_btn_avanzado AND ! $campo) {
            $this->session->unset_userdata('buscar_campo_tiempo_carga');
            $this->session->unset_userdata('buscar_criterio_tiempo_carga');
            $this->session->unset_userdata('buscar_texto_tiempo_carga');
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
         // Join
        //----------------------------
        $this->db->join('m_capacidad_carga', 'm_capacidad_carga.m_capacidad_carga_id = tiempo_carga.fk_capacidad_carga_id', 'left');
        $this->db->join('m_producto', 'm_producto.m_producto_id = tiempo_carga.fk_producto_id', 'left');
        $this->db->join('m_lugar_carga', 'm_lugar_carga.m_lugar_carga_id = tiempo_carga.fk_lugar_carga_id', 'left');
        $this->db->join('m_equipo', 'm_equipo.m_equipo_id = m_capacidad_carga.fk_equipo_id', 'left');
        $this->db->join('m_cuna', 'm_cuna.m_cuna_id = m_capacidad_carga.fk_cuna_id', 'left');

        // Select
        //-----------------------------
        $fields_name = array();
        foreach ($this->fields as $key => $value) {
            $fields_name[] = $value;
        }
        $this->db->select($fields_name);

        $this->db->limit(1);
        $this->db->where('tiempo_carga_id', $id);
        return $this->db->get('tiempo_carga')->row();

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
			
			// Eliminar tiempo_cargas
			foreach ($ids as $key => $id) {
				$this->db->where('tiempo_carga_id', $id);
	        	$this->db->delete('tiempo_carga');
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
         // Join
        //----------------------------
        $this->db->join('m_capacidad_carga', 'm_capacidad_carga.m_capacidad_carga_id = tiempo_carga.fk_capacidad_carga_id', 'left');
        $this->db->join('m_producto', 'm_producto.m_producto_id = tiempo_carga.fk_producto_id', 'left');
        $this->db->join('m_lugar_carga', 'm_lugar_carga.m_lugar_carga_id = tiempo_carga.fk_lugar_carga_id', 'left');
        $this->db->join('m_equipo', 'm_equipo.m_equipo_id = m_capacidad_carga.fk_equipo_id', 'left');
        $this->db->join('m_cuna', 'm_cuna.m_cuna_id = m_capacidad_carga.fk_cuna_id', 'left');

        // Select
        //-----------------------------
        $fields_name = array();
        foreach ($this->fields as $key => $value) {
            $fields_name[] = $value;
        }
        $this->db->select($fields_name);

        $this->db->order_by('tiempo_carga', 'asc');
        return $this->db->get('tiempo_carga');
    }
    //-------------------------------------------------------------------------
    
    /**
     * AutoCalc
     * 
     * Funcion para autocalcular los tiempos de carga de los equipo
     * El tiempo de carga es igual a capacidad de carga del equipo entre (dividido)
     * capacidad de bombeo del lugar de carga (segun el producto)
     */
     
     public function autoCalc()
     {
         // Comenzar transaccion
         $this->db->trans_start();
         
             $tiempo_carga = 0;
             
             // 1- Limpiar la tabla de tiempo de carga
             $this->db->empty_table('tiempo_carga'); 
             
             // 2- Obtener capacidades de carga
             $t_capacidad_carga = $this->db->get('m_capacidad_carga');
             
             // 3- Obtener capacidad de bombeo del lugar de carga
             $this->db->join('m_lugar_carga', 'm_lugar_carga.m_lugar_carga_id = capacidad_bombeo_lugar_carga.fk_lugar_carga_id', 'left');
             $this->db->join('m_producto', 'm_producto.m_producto_id = capacidad_bombeo_lugar_carga.fk_producto_id', 'left');
             $t_capacidad_bombeo_lc = $this->db->get('capacidad_bombeo_lugar_carga');
             
             foreach ($t_capacidad_carga->result() as $cc) {
                 foreach ($t_capacidad_bombeo_lc->result() as $cblc) {
                     // Los tipos de productos son iguales
                     if ($cc->tipo_de_producto == $cblc->tipo) {
                         // Calcular tiempo de carga
                         $tiempo_carga = $cc->capacidad_carga / ($cblc->capacidad_bombeo * 0.97);
                         $tiempo_carga = number_format($tiempo_carga, 2, '.', '');
                         
                         // Insertar datos en la tabla de tiempo de carga
                         $this->db->set('fk_capacidad_carga_id', $cc->m_capacidad_carga_id);
                         $this->db->set('fk_producto_id', $cblc->m_producto_id);
                         $this->db->set('fk_lugar_carga_id', $cblc->m_lugar_carga_id);
                         $this->db->set('tiempo_carga', $tiempo_carga);
                         $this->db->insert('tiempo_carga');
                     }
                 }
             }

        // Culminar transaccion
        $this->db->trans_complete();        
        return $this->db->trans_status();             
     }
        
}