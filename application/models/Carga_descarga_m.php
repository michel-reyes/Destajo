<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Carga_descarga_m extends CI_Model {
	
	protected $fields = array(
        'carga_descarga_id', 
        'codigo',
        'fk_lugar_carga_id',
        'fk_lugar_descarga_id',
        'km_recorridos',
        'PU',
        'C',
        'A',
        'T',
        'CM',
        'CT',
        'TM',
        'CV',
        // Lugar de carga
        'm_lugar_carga_id',
        'lugar_carga',
        // Lugar de descarga
        'm_lugar_descarga_id',
        'lugar_descarga'
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
        return $this->db->count_all_results('carga_descarga');
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
             $ofield = $this->session->userdata('ofield_carga_descarga') ? $this->session->userdata('ofield_carga_descarga') : "codigo";
             $otype = $this->session->userdata('otype_carga_descarga') ? $this->session->userdata('otype_carga_descarga') : "asc";
         }
         $this->db->order_by($ofield, $otype);
         
         // Join
         //----------------------------
         $this->db->join('m_lugar_carga', 'm_lugar_carga.m_lugar_carga_id = carga_descarga.fk_lugar_carga_id','left');
         $this->db->join('m_lugar_descarga', 'm_lugar_descarga.m_lugar_descarga_id = carga_descarga.fk_lugar_descarga_id','left');
        
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
         $query['num_rows_wo_limit'] = $this->db->get('carga_descarga')->num_rows();
         if ($limit!='' OR $offset!='') $this->db->limit($limit, $offset);
        
         // Obtener registros
         //-----------------------------
         $query['rows'] = $this->db->get('carga_descarga');
         $query['num_rows'] = $query['rows']->num_rows();
         $this->db->flush_cache();
        
         // Almacenar ordenar en session
         //-----------------------------
         $this->session->set_userdata('order_field_carga_descarga', $ofield);
         $this->session->set_userdata('order_type_carga_descarga', $otype);
        
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
                $campo = $this->session->userdata('buscar_campo_carga_descarga');
                $criterio = $this->session->userdata('buscar_criterio_carga_descarga');
                $texto = $this->session->userdata('buscar_texto_carga_descarga');
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
            if ($this->input->post('buscar_campo')) $this->session->set_userdata('buscar_campo_carga_descarga', $this->input->post('buscar_campo'));
            if ($this->input->post('buscar_criterio')) $this->session->set_userdata('buscar_criterio_carga_descarga', $this->input->post('buscar_criterio'));
            if ($this->input->post('buscar_texto')) $this->session->set_userdata('buscar_texto_carga_descarga', $this->input->post('buscar_texto'));
        }
        
        // Se mando a buscar y no hay texto en la busqueda ni en session
        // so, eliminar de session
        if ($buscar_btn_avanzado AND ! $campo) {
            $this->session->unset_userdata('buscar_campo_carga_descarga');
            $this->session->unset_userdata('buscar_criterio_carga_descarga');
            $this->session->unset_userdata('buscar_texto_carga_descarga');
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
        $this->db->join('m_lugar_carga', 'm_lugar_carga.m_lugar_carga_id = carga_descarga.fk_lugar_carga_id','left');
        $this->db->join('m_lugar_descarga', 'm_lugar_descarga.m_lugar_descarga_id = carga_descarga.fk_lugar_descarga_id','left');
        
        // Select
        //-----------------------------
        $fields_name = array();
        foreach ($this->fields as $key => $value) {
            $fields_name[] = $value;
        }
        $this->db->select($fields_name);
         
        $this->db->limit(1);
        $this->db->where('carga_descarga_id', $id);
        return $this->db->get('carga_descarga')->row();
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
        // Analizar cual sera el proximo "Codigo" autonumerico
        if ($this->session->userdata('cc_codigo')) {
            $codigo = $this->session->userdata('cc_codigo');
            $post_codigo = $this->input->post('codigo');
            if ($codigo < $post_codigo) {
                $this->session->set_userdata('cc_codigo', intval($post_codigo)+1);
            }
        }else{
            $this->session->set_userdata('cc_codigo', intval($this->input->post('codigo'))+1);
        }        
        
        // Guardar cargar descarga       
        $this->db->set('codigo', $this->input->post('codigo'));
        $this->db->set('fk_lugar_carga_id', $this->input->post('fk_lugar_carga_id'));
        $this->db->set('fk_lugar_descarga_id', $this->input->post('fk_lugar_descarga_id'));
        $this->db->set('km_recorridos', number_to_mysql($this->input->post('km_recorridos')));
        $this->db->set('PU', number_to_mysql($this->input->post('PU')));
        $this->db->set('C', number_to_mysql($this->input->post('C')));
        $this->db->set('A', number_to_mysql($this->input->post('A')));
        $this->db->set('T', number_to_mysql($this->input->post('T')));
        $this->db->set('CM', number_to_mysql($this->input->post('CM')));
        $this->db->set('CT', number_to_mysql($this->input->post('CT')));
        $this->db->set('TM', number_to_mysql($this->input->post('TM')));
        $this->db->set('CV', number_to_mysql($this->input->post('CV')));		
        return $this->db->insert('carga_descarga');
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
        $this->db->set('codigo', $this->input->post('codigo'));
        $this->db->set('fk_lugar_carga_id', $this->input->post('fk_lugar_carga_id'));
        $this->db->set('fk_lugar_descarga_id', $this->input->post('fk_lugar_descarga_id'));
        $this->db->set('km_recorridos', number_to_mysql($this->input->post('km_recorridos')));
        $this->db->set('PU', number_to_mysql($this->input->post('PU')));
        $this->db->set('C', number_to_mysql($this->input->post('C')));
        $this->db->set('A', number_to_mysql($this->input->post('A')));
        $this->db->set('T', number_to_mysql($this->input->post('T')));
        $this->db->set('CM', number_to_mysql($this->input->post('CM')));
        $this->db->set('CT', number_to_mysql($this->input->post('CT')));
        $this->db->set('TM', number_to_mysql($this->input->post('TM')));
        $this->db->set('CV', number_to_mysql($this->input->post('CV')));
        $this->db->where('carga_descarga_id', $id);
        return $this->db->update('carga_descarga');
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
			
			// Eliminar carga_descargas
			foreach ($ids as $key => $id) {
				$this->db->where('carga_descarga_id', $id);
	        	$this->db->delete('carga_descarga');
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
        $this->db->join('m_lugar_carga', 'm_lugar_carga.m_lugar_carga_id = carga_descarga.fk_lugar_carga_id', 'left');
        $this->db->join('m_lugar_descarga', 'm_lugar_descarga.m_lugar_descarga_id = carga_descarga.fk_lugar_descarga_id', 'left');

        // Select
        //-----------------------------
        $fields_name = array();
        foreach ($this->fields as $key => $value) {
            $fields_name[] = $value;
        }
        $this->db->select($fields_name);

        $this->db->order_by('codigo', 'asc');
        return $this->db->get('carga_descarga');
    }
}
    