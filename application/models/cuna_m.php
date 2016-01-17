<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cuna_m extends CI_Model {
	
	protected $fields = array(
        'm_cuna_id', 
        'numero_operacional'
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
        return $this->db->count_all_results('m_cuna');
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
             $ofield = $this->session->userdata('ofield_cuna') ? $this->session->userdata('ofield_cuna') : "numero_operacional";
             $otype = $this->session->userdata('otype_cuna') ? $this->session->userdata('otype_cuna') : "asc";
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
         $query['num_rows_wo_limit'] = $this->db->get('m_cuna')->num_rows();
         if ($limit!='' OR $offset!='') $this->db->limit($limit, $offset);
        
         // Obtener registros
         //-----------------------------
         $query['rows'] = $this->db->get('m_cuna');
         $query['num_rows'] = $query['rows']->num_rows();
         $this->db->flush_cache();
        
         // Almacenar ordenar en session
         //-----------------------------
         $this->session->set_userdata('order_field_cuna', $ofield);
         $this->session->set_userdata('order_type_cuna', $otype);
        
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
                $campo = $this->session->userdata('buscar_campo_cuna');
                $criterio = $this->session->userdata('buscar_criterio_cuna');
                $texto = $this->session->userdata('buscar_texto_cuna');
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
            if ($this->input->post('buscar_campo')) $this->session->set_userdata('buscar_campo_cuna', $this->input->post('buscar_campo'));
            if ($this->input->post('buscar_criterio')) $this->session->set_userdata('buscar_criterio_cuna', $this->input->post('buscar_criterio'));
            if ($this->input->post('buscar_texto')) $this->session->set_userdata('buscar_texto_cuna', $this->input->post('buscar_texto'));
        }
        
        // Se mando a buscar y no hay texto en la busqueda ni en session
        // so, eliminar de session
        if ($buscar_btn_avanzado AND ! $campo) {
            $this->session->unset_userdata('buscar_campo_cuna');
            $this->session->unset_userdata('buscar_criterio_cuna');
            $this->session->unset_userdata('buscar_texto_cuna');
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
        $this->db->where('m_cuna_id', $id);
        return $this->db->get('m_cuna')->row();
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
        $this->db->set('numero_operacional', $this->input->post('numero_operacional'));		
        return $this->db->insert('m_cuna');
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
        $this->db->set('numero_operacional', $this->input->post('numero_operacional'));
        $this->db->where('m_cuna_id', $id);
        return $this->db->update('m_cuna');
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
			
			// Eliminar cunas
			foreach ($ids as $key => $id) {
				$this->db->where('m_cuna_id', $id);
	        	$this->db->delete('m_cuna');
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
    	$this->db->order_by('numero_operacional', 'asc');
        return $this->db->get('m_cuna');
    }
}