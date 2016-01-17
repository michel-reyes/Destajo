<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/models/capacidad_carga_m.php

DESTAJO-MODULE-END
*/

class Capacidad_carga_m extends CI_Model {
	
	protected $fields = array(
        'm_capacidad_carga_id', 
        'fk_equipo_id',
        'fk_cuna_id',
        'viajes_promedio',
        'entregas_promedio',
        'capacidad_carga',
        'tipo_de_producto',
        // Eqipo
        'm_equipo.m_equipo_id',
        'm_equipo.numero_operacional as equipo',
        // Cuña
        'm_cuna.m_cuna_id',
        'm_cuna.numero_operacional as cuna',
        // Capacidad de bombeo equipo
        'capacidad_bombeo_equipo.capacidad_bombeo_equipo_id',
        'capacidad_bombeo_equipo.fk_capacidad_carga_id',
        'capacidad_bombeo_equipo.fk_modo_descarga_id',
        'capacidad_bombeo_equipo.capacidad_bombeo',
        // Modo de descarga
        'm_modo_descarga.m_modo_descarga_id',
        'm_modo_descarga.modo'
        
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
        return $this->db->count_all_results('m_capacidad_carga');
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
         $query = array();
         $search = "";
         
         // Select
         //-----------------------------
         $this->db->select("cc.m_capacidad_carga_id,
         cc.viajes_promedio,
         cc.entregas_promedio,
         cc.capacidad_carga,
         cc.tipo_de_producto,    
         m_equipo.numero_operacional as no_equipo,
         m_cuna.numero_operacional as no_cuna,
         sum(if(md.modo='Turbina del equipo', cbe.capacidad_bombeo, 0)) 'turbina_equipo',
         sum(if(md.modo='Gravedad 2\"', cbe.capacidad_bombeo, 0)) 'g2',
         sum(if(md.modo='Gravedad 3\"', cbe.capacidad_bombeo, 0)) 'g3',
         sum(if(md.modo='Gravedad 4\"', cbe.capacidad_bombeo, 0)) 'g4'", FALSE);
         
         // Ordenar
         //-----------------------------
         $ofield = $this->input->get("ofield");
         $otype = $this->input->get("otype");
         if (!$ofield OR !$otype) {
             $ofield = $this->session->userdata('ofield_capacidad_carga') ? $this->session->userdata('ofield_capacidad_carga') : "capacidad_carga";
             $otype = $this->session->userdata('otype_capacidad_carga') ? $this->session->userdata('otype_capacidad_carga') : "asc";
         }
         $this->db->order_by($ofield, $otype);
         
         // Join
         //----------------------------
         $this->db->join('m_equipo', 'm_equipo.m_equipo_id = cc.fk_equipo_id', 'left');
         $this->db->join('m_cuna', 'm_cuna.m_cuna_id = cc.fk_cuna_id', 'left');
         $this->db->join('capacidad_bombeo_equipo cbe', 'cbe.fk_capacidad_carga_id = cc.m_capacidad_carga_id', 'left');
         $this->db->join('m_modo_descarga md', 'md.m_modo_descarga_id = cbe.fk_modo_descarga_id', 'left');

         // Group by
         //----------------------------
         $this->db->group_by("cc.m_capacidad_carga_id");
         
         // Buscar
         //-----------------------------
         $search = $this->create_search();
        
         // Si llegan los limites
         // Buscar todo lo relacionado a los lugares de carga con limites
         if ($limit != "" OR $offset != "") {            
             $this->db->limit($limit, $offset);
             $query['rows'] = $this->db->get('m_capacidad_carga cc');
             $query['num_rows'] = $query['rows']->num_rows();            
         }
         // Si No llegan los limites 
         // Buscar todo lo relaciondado a los lugares de carga para la paginacion
         else{
             $query['num_rows_wo_limit'] = $this->db->get('m_capacidad_carga cc')->num_rows();  
             return $query;                                  
         }         
        
         // Almacenar ordenar en session
         //-----------------------------
         $this->session->set_userdata('order_field_capacidad_carga', $ofield);
         $this->session->set_userdata('order_type_capacidad_carga', $otype);
        
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
                $campo = $this->session->userdata('buscar_campo_capacidad_carga');
                $criterio = $this->session->userdata('buscar_criterio_capacidad_carga');
                $texto = $this->session->userdata('buscar_texto_capacidad_carga');
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
            if ($this->input->post('buscar_campo')) $this->session->set_userdata('buscar_campo_capacidad_carga', $this->input->post('buscar_campo'));
            if ($this->input->post('buscar_criterio')) $this->session->set_userdata('buscar_criterio_capacidad_carga', $this->input->post('buscar_criterio'));
            if ($this->input->post('buscar_texto')) $this->session->set_userdata('buscar_texto_capacidad_carga', $this->input->post('buscar_texto'));
        }
        
        // Se mando a buscar y no hay texto en la busqueda ni en session
        // so, eliminar de session
        if ($buscar_btn_avanzado AND ! $campo) {
            $this->session->unset_userdata('buscar_campo_capacidad_carga');
            $this->session->unset_userdata('buscar_criterio_capacidad_carga');
            $this->session->unset_userdata('buscar_texto_capacidad_carga');
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
         $this->db->join('m_equipo', 'm_equipo.m_equipo_id = m_capacidad_carga.fk_equipo_id', 'left');
         $this->db->join('m_cuna', 'm_cuna.m_cuna_id = m_capacidad_carga.fk_cuna_id', 'left');
         $this->db->join('capacidad_bombeo_equipo', 'capacidad_bombeo_equipo.capacidad_bombeo_equipo_id = m_capacidad_carga.m_capacidad_carga_id', 'left');
         $this->db->join('m_modo_descarga', 'm_modo_descarga.m_modo_descarga_id = capacidad_bombeo_equipo.fk_modo_descarga_id', 'left');
        
         // Select
         //-----------------------------
         $fields_name = array();
         foreach ($this->fields as $key => $value) {
             $fields_name[] = $value;
         }
         $this->db->select($fields_name);
         
         $this->db->limit(1);
         $this->db->where('m_capacidad_carga_id', $id);
         return $this->db->get('m_capacidad_carga')->row();
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
        // Comenzar transaccion
        $this->db->trans_start();
            
            // Agregar la capacidad de carga            
            $this->db->set('fk_equipo_id', $this->input->post('fk_equipo_id'));
            if ($this->input->post('fk_cuna_id'))
                $this->db->set('fk_cuna_id', $this->input->post('fk_cuna_id'));
            else 
                $this->db->set('fk_cuna_id', NULL); 
			if ($this->input->post('viajes_promedio'))
			{
				$this->db->set('viajes_promedio', number_to_mysql($this->input->post('viajes_promedio'))); 
			}
			if ($this->input->post('entregas_promedio'))
			{
				$this->db->set('entregas_promedio', number_to_mysql($this->input->post('entregas_promedio'))); 
			}            
            $this->db->set('capacidad_carga', number_to_mysql($this->input->post('capacidad_carga'))); 
            $this->db->set('tipo_de_producto', $this->input->post('tipo_de_producto'));           		
            $this->db->insert('m_capacidad_carga');
            
            // Obtener ID agregado
            $ccId = $this->db->insert_id();
            
            // Insertar capacidad de bombeo
            $capacidad_bombeo = $this->input->post('capacidad_bombeo');
            if ($capacidad_bombeo) {
                foreach ($capacidad_bombeo as $key => $value) {
                    $this->db->set('fk_capacidad_carga_id', $ccId);
                    $this->db->set('fk_modo_descarga_id', $key);
                    $this->db->set('capacidad_bombeo', number_to_mysql($value));
                    $this->db->insert('capacidad_bombeo_equipo');
                }
            }
              
            
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
        // Comenzar transaccion
        $this->db->trans_start();
            
            // Borrar capacidad bombeo segun capacidad de carga
            $this->db->where('fk_capacidad_carga_id', $id);
            $this->db->delete('capacidad_bombeo_equipo');
            
            // Actualizar la capacidad de carga            
            $this->db->set('fk_equipo_id', $this->input->post('fk_equipo_id'));
            if ($this->input->post('fk_cuna_id'))
                $this->db->set('fk_cuna_id', $this->input->post('fk_cuna_id'));
            else 
                $this->db->set('fk_cuna_id', NULL); 
            if ($this->input->post('viajes_promedio'))
			{
				$this->db->set('viajes_promedio', number_to_mysql($this->input->post('viajes_promedio'))); 
			}
			if ($this->input->post('entregas_promedio'))
			{
				$this->db->set('entregas_promedio', number_to_mysql($this->input->post('entregas_promedio'))); 
			}
            $this->db->set('capacidad_carga', number_to_mysql($this->input->post('capacidad_carga'))); 
            $this->db->set('tipo_de_producto', $this->input->post('tipo_de_producto')); 
            $this->db->where('m_capacidad_carga_id', $id);                
            $this->db->update('m_capacidad_carga');
                        
            // Insertar capacidad de bombeo
            $capacidad_bombeo = $this->input->post('capacidad_bombeo');
            if ($capacidad_bombeo) {
               foreach ($capacidad_bombeo as $key => $value) {
                   $this->db->set('fk_capacidad_carga_id', $id);
                   $this->db->set('fk_modo_descarga_id', $key);
                   $this->db->set('capacidad_bombeo', number_to_mysql($value));
                   $this->db->insert('capacidad_bombeo_equipo');
               } 
            }
              
            
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
			
			// Eliminar capacidad_cargas
			foreach ($ids as $key => $id) {
				$this->db->where('m_capacidad_carga_id', $id);
	        	$this->db->delete('m_capacidad_carga');
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
         $this->db->join('m_equipo', 'm_equipo.m_equipo_id = m_capacidad_carga.fk_equipo_id', 'left');
         $this->db->join('m_cuna', 'm_cuna.m_cuna_id = m_capacidad_carga.fk_cuna_id', 'left');
         $this->db->join('capacidad_bombeo_equipo', 'capacidad_bombeo_equipo.capacidad_bombeo_equipo_id = m_capacidad_carga.m_capacidad_carga_id', 'left');
         $this->db->join('m_modo_descarga', 'm_modo_descarga.m_modo_descarga_id = capacidad_bombeo_equipo.fk_modo_descarga_id', 'left');
        
         // Select
         //-----------------------------
         $fields_name = array();
         foreach ($this->fields as $key => $value) {
             $fields_name[] = $value;
         }
         $this->db->select($fields_name);
         
    	 $this->db->order_by('capacidad_carga', 'asc');
         return $this->db->get('m_capacidad_carga');
    } 
    
    //-------------------------------------------------------------------------
    
    /**
     * GET cb_by_cc
     * 
     * Obtener capacidades de bombeo segun capacidad de carga
     */
     
     public function get_cb_by_cc($id='')
     {
         if ($id=="") return FALSE;
        
         $this->db->where('fk_capacidad_carga_id', $id);
         return $this->db->get('capacidad_bombeo_equipo');
     }
}