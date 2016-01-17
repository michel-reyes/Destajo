<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lugar_carga_m extends CI_Model {
	
	protected $fields = array(
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
        return $this->db->count_all_results('m_lugar_carga');
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
         $this->db->select("lugar_carga, m_lugar_carga_id,
         sum(if(m_producto.producto='GLP', capacidad_bombeo_lugar_carga.capacidad_bombeo, 0)) GLP,
         sum(if(m_producto.producto='Alcohol', capacidad_bombeo_lugar_carga.capacidad_bombeo, 0)) Alcohol,
         sum(if(m_producto.producto='Kerosina', capacidad_bombeo_lugar_carga.capacidad_bombeo, 0)) Kerosina,
         sum(if(m_producto.producto='Fuell', capacidad_bombeo_lugar_carga.capacidad_bombeo, 0)) Fuell,
         sum(if(m_producto.producto='Gasolina Regular', capacidad_bombeo_lugar_carga.capacidad_bombeo, 0)) Gasolina_Regular,
         sum(if(m_producto.producto='Gasolina Especial', capacidad_bombeo_lugar_carga.capacidad_bombeo, 0)) Gasolina_Especial,
         sum(if(m_producto.producto='Diesel', capacidad_bombeo_lugar_carga.capacidad_bombeo, 0)) Diesel,
         sum(if(m_producto.producto='Crudo', capacidad_bombeo_lugar_carga.capacidad_bombeo, 0)) Crudo,
         sum(if(m_producto.producto='Turbo', capacidad_bombeo_lugar_carga.capacidad_bombeo, 0)) Turbo,
         sum(if(m_producto.producto='Lubricantes', capacidad_bombeo_lugar_carga.capacidad_bombeo, 0)) Lubricantes,
         sum(if(m_producto.producto='Nafta', capacidad_bombeo_lugar_carga.capacidad_bombeo, 0)) Nafta,
         sum(if(m_producto.producto='BioMix', capacidad_bombeo_lugar_carga.capacidad_bombeo, 0)) BioMix", FALSE);
        
        // Ordenar
         //-----------------------------
         $ofield = $this->input->get("ofield");
         $otype = $this->input->get("otype");
         if (!$ofield OR !$otype) {
             $ofield = $this->session->userdata('ofield_lugar_carga') ? $this->session->userdata('ofield_lugar_carga') : "lugar_carga";
             $otype = $this->session->userdata('otype_lugar_carga') ? $this->session->userdata('otype_lugar_carga') : "asc";
         }
         $this->db->order_by($ofield, $otype);
         
         // JOIN
         $this->db->join("capacidad_bombeo_lugar_carga", "capacidad_bombeo_lugar_carga.fk_lugar_carga_id = m_lugar_carga.m_lugar_carga_id", "left");
         $this->db->join("m_producto", "m_producto.m_producto_id = capacidad_bombeo_lugar_carga.fk_producto_id", "left");
         
         // GROUP BY
         $this->db->group_by("m_lugar_carga.lugar_carga");
        
         // Buscar
         //-----------------------------
         $search = $this->create_search();
        
         // Si llegan los limites
         // Buscar todo lo relacionado a los lugares de carga con limites
         if ($limit != "" OR $offset != "") {            
             $this->db->limit($limit, $offset);
             $query['rows'] = $this->db->get('m_lugar_carga');
             $query['num_rows'] = $query['rows']->num_rows();            
         }
         // Si No llegan los limites 
         // Buscar todo lo relaciondado a los lugares de carga para la paginacion
         else{
             $query['num_rows_wo_limit'] = $this->db->get('m_lugar_carga')->num_rows();  
             return $query;                                  
         }
        
         // Almacenar ordenar en session
         //-----------------------------
         $this->session->set_userdata('order_field_lugar_carga', $ofield);
         $this->session->set_userdata('order_type_lugar_carga', $otype);
        
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
                $campo = $this->session->userdata('buscar_campo_lugar_carga');
                $criterio = $this->session->userdata('buscar_criterio_lugar_carga');
                $texto = $this->session->userdata('buscar_texto_lugar_carga');
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
            if ($this->input->post('buscar_campo')) $this->session->set_userdata('buscar_campo_lugar_carga', $this->input->post('buscar_campo'));
            if ($this->input->post('buscar_criterio')) $this->session->set_userdata('buscar_criterio_lugar_carga', $this->input->post('buscar_criterio'));
            if ($this->input->post('buscar_texto')) $this->session->set_userdata('buscar_texto_lugar_carga', $this->input->post('buscar_texto'));
        }
        
        // Se mando a buscar y no hay texto en la busqueda ni en session
        // so, eliminar de session
        if ($buscar_btn_avanzado AND ! $campo) {
            $this->session->unset_userdata('buscar_campo_lugar_carga');
            $this->session->unset_userdata('buscar_criterio_lugar_carga');
            $this->session->unset_userdata('buscar_texto_lugar_carga');
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
        $this->db->where('m_lugar_carga_id', $id);
        return $this->db->get('m_lugar_carga')->row();
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
            
            // Agregar lugar de carga
            $this->db->set('lugar_carga', $this->input->post("lugar_carga"));
            $this->db->insert('m_lugar_carga');
            
            // Obtener el ultimo id agregado del producto
            $pro_last_id = $this->db->insert_id();          
            
            // Agregar capacida de bombeo
            $arr_pro = $this->input->post('productos');
            foreach ($arr_pro as $key => $value) {
                if ($value != "" && $value != 0.00) {
                    $this->db->set('fk_producto_id', $key);
                    $this->db->set('fk_lugar_carga_id', $pro_last_id);
                    $this->db->set('capacidad_bombeo', number_to_mysql($value));
                    $this->db->insert('capacidad_bombeo_lugar_carga');
                }
            }
        
        // Terminar transaccion
        $this->db->trans_complete();
        
        // Comprobar si la transaccion fue correcta
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
            
            // Actualizar lugar de carga
            $this->db->set('lugar_carga', $this->input->post("lugar_carga"));
            $this->db->where('m_lugar_carga_id', $id);
            $this->db->update('m_lugar_carga');
            
            // Eliminar todas las capacidades de bombeo para este lugar de carga
            $this->db->where('fk_lugar_carga_id', $id);
            $this->db->delete('capacidad_bombeo_lugar_carga');
                    
            // Agregar capacida de bombeo
            $arr_pro = $this->input->post('productos');
            foreach ($arr_pro as $key => $value) {
                if ($value != "" && $value != 0.00) {
                    $this->db->set('fk_producto_id', $key);
                    $this->db->set('fk_lugar_carga_id', $id);
                    $this->db->set('capacidad_bombeo', number_to_mysql($value));
                    $this->db->insert('capacidad_bombeo_lugar_carga');
                }
            }
        
        // Terminar transaccion
        $this->db->trans_complete();
        
        // Comprobar si la transaccion fue correcta
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
			
			// Eliminar lugar_cargas
			foreach ($ids as $key => $id) {
				// Eliminar todas las capacidades de bombeo para este lugar de carga
                $this->db->where('fk_lugar_carga_id', $id);
                $this->db->delete('capacidad_bombeo_lugar_carga');
                
                // Eliminar lugar de carga
                $this->db->where('m_lugar_carga_id', $id);
                $this->db->delete('m_lugar_carga');
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
    	$this->db->order_by('lugar_carga', 'asc');
        return $this->db->get('m_lugar_carga');
    }
    
    //-------------------------------------------------------------------------
    
    /**
     * Get cb_by_lc
     * 
     * Obtener capacidades de bombeo segun el lugar de carga
     */
      public function get_cb_by_lc($id='')
      {
          if ($id=="") return FALSE;
        
          $this->db->where('fk_lugar_carga_id', $id);
          return $this->db->get('capacidad_bombeo_lugar_carga');
      }
}