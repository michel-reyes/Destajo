<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/models/lugar_descarga_m.php

DESTAJO-MODULE-END
*/

class Lugar_descarga_m extends CI_Model {

    protected $fields = array(
        'm_lugar_descarga_id', 
        'lugar_descarga', 
        'capacidad_bombeo_turbina_cliente',
        'velocidad_media_a_k',
        'velocidad_media_d'
    );

    //-------------------------------------------------------------------------

    /**
     * count_all
     *
     * Obtiene el numero real de registros
     * @return double
     */

    public function count_all() {
        return $this->db->count_all_results('m_lugar_descarga');
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

    public function list_all($limit = '', $offset = '') {
        $query = array();
        $search = "";

        // Select
        //-----------------------------
        $this->db->select("m_lugar_descarga.lugar_descarga, m_lugar_descarga.m_lugar_descarga_id, m_lugar_descarga.capacidad_bombeo_turbina_cliente, m_lugar_descarga.velocidad_media_a_k, m_lugar_descarga.velocidad_media_d,
        sum(if(m_producto.producto='GLP', lugar_descarga_producto.fk_producto_id, 0)) GLP,
        sum(if(m_producto.producto='Alcohol', lugar_descarga_producto.fk_producto_id, 0)) Alcohol,
        sum(if(m_producto.producto='Kerosina', lugar_descarga_producto.fk_producto_id, 0)) Kerosina,
        sum(if(m_producto.producto='Fuell', lugar_descarga_producto.fk_producto_id, 0)) Fuell,
        sum(if(m_producto.producto='Gasolina Regular', lugar_descarga_producto.fk_producto_id, 0)) Gasolina_Regular,
        sum(if(m_producto.producto='Gasolina Especial', lugar_descarga_producto.fk_producto_id, 0)) Gasolina_Especial,
        sum(if(m_producto.producto='Diesel', lugar_descarga_producto.fk_producto_id, 0)) Diesel,
        sum(if(m_producto.producto='Crudo', lugar_descarga_producto.fk_producto_id, 0)) Crudo,
        sum(if(m_producto.producto='Turbo', lugar_descarga_producto.fk_producto_id, 0)) Turbo,
        sum(if(m_producto.producto='Lubricantes', lugar_descarga_producto.fk_producto_id, 0)) Lubricantes,
        sum(if(m_producto.producto='Nafta', lugar_descarga_producto.fk_producto_id, 0)) Nafta,
        sum(if(m_producto.producto='BioMix', lugar_descarga_producto.fk_producto_id, 0)) BioMix", FALSE);

        // Ordenar
        //-----------------------------
        $ofield = $this->input->get("ofield");
        $otype = $this->input->get("otype");
        if (!$ofield OR !$otype) {
            $ofield = $this->session->userdata('ofield_lugar_descarga') ? $this->session->userdata('ofield_lugar_descarga') : "lugar_descarga";
            $otype = $this->session->userdata('otype_lugar_descarga') ? $this->session->userdata('otype_lugar_descarga') : "asc";
        }
        $this->db->order_by($ofield, $otype);

        // Join
        //----------------------------
        $this->db->join("lugar_descarga_producto", "lugar_descarga_producto.fk_lugar_descarga_id = m_lugar_descarga.m_lugar_descarga_id", "left");
        $this->db->join("m_producto", "m_producto.m_producto_id = lugar_descarga_producto.fk_producto_id", "left");

        // Group by
        //----------------------------
        $this->db->group_by("m_lugar_descarga.m_lugar_descarga_id");

        // Buscar
        //-----------------------------
        $search = $this->create_search();

        // Si llegan los limites
        // Buscar todo lo relacionado a los lugares de carga con limites
        if ($limit != "" OR $offset != "") {

            $this->db->limit($limit, $offset);
            $query['rows'] = $this->db->get('m_lugar_descarga');
            $query['num_rows'] = $query['rows']->num_rows();

        }
        // Si No llegan los limites
        // Buscar todo lo relaciondado a los lugares de carga para la paginacion
        else {
            $query['num_rows_wo_limit'] = $this->db->get('m_lugar_descarga')->num_rows();
            return $query;

        }

        // Almacenar ordenar en session
        //-----------------------------
        $this->session->set_userdata('order_field_lugar_descarga', $ofield);
        $this->session->set_userdata('order_type_lugar_descarga', $otype);

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

    public function create_search() {
        $query = "";

        // AVANZADO
        $campo = $this->input->post('buscar_campo');
        $criterio = $this->input->post('buscar_criterio');
        $texto = $this->input->post('buscar_texto');
        $buscar_btn_avanzado = $this->input->post('buscar_btn_avanzado');

        // No se mando a buscar, pero hay busquedas almacenadas en session
        if (!$buscar_btn_avanzado) {
            if (!$campo OR count($campo) <= 0) {
                $campo = $this->session->userdata('buscar_campo_lugar_descarga');
                $criterio = $this->session->userdata('buscar_criterio_lugar_descarga');
                $texto = $this->session->userdata('buscar_texto_lugar_descarga');
            }
        }

        // Buscar
        if ($campo AND count($campo) > 0) {
            foreach ($campo as $key => $value) {
                switch ($criterio[$key]) {
                    case 'like_none' :
                        $this->db->like($value, $texto[$key], 'none');
                        break;
                    case 'not_like_none' :
                        $this->db->not_like($value, $texto[$key], 'none');
                        break;
                    case 'like_both' :
                        $this->db->like($value, $texto[$key], 'both');
                        break;
                    case 'or_like_both' :
                        $this->db->or_like($value, $texto[$key], 'both');
                        break;
                    case 'not_like_both' :
                        $this->db->not_like($value, $texto[$key], 'both');
                        break;
                    case 'or_not_like_both' :
                        $this->db->or_not_like($value, $texto[$key], 'both');
                        break;
                    case 'lt' :
                        $this->db->where($value . ' < ', $texto[$key]);
                        break;
                    case 'gt' :
                        $this->db->where($value . ' > ', $texto[$key]);
                        break;
                }
            }
        }

        // Se mando a buscar y hay texto, so alacenarlo en session,
        // no almacenar lo que ya esta almacenado, so preguntar si se envio abuscar
        if ($buscar_btn_avanzado AND $campo) {
            if ($this->input->post('buscar_campo'))
                $this->session->set_userdata('buscar_campo_lugar_descarga', $this->input->post('buscar_campo'));
            if ($this->input->post('buscar_criterio'))
                $this->session->set_userdata('buscar_criterio_lugar_descarga', $this->input->post('buscar_criterio'));
            if ($this->input->post('buscar_texto'))
                $this->session->set_userdata('buscar_texto_lugar_descarga', $this->input->post('buscar_texto'));
        }

        // Se mando a buscar y no hay texto en la busqueda ni en session
        // so, eliminar de session
        if ($buscar_btn_avanzado AND !$campo) {
            $this->session->unset_userdata('buscar_campo_lugar_descarga');
            $this->session->unset_userdata('buscar_criterio_lugar_descarga');
            $this->session->unset_userdata('buscar_texto_lugar_descarga');
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
    public function get_by_id($id = 0) {
        $this->db->limit(1);
        $this->db->where('m_lugar_descarga_id', $id);
        return $this->db->get('m_lugar_descarga')->row();
    }

    //-------------------------------------------------------------------------

    /**
     * Agregar
     *
     * Agregar el elemento del modulo
     * @return bool
     */

    public function agregar() {
        
        // Comenzar transaccion
        $this->db->trans_start();
            
            // Agregar lugar de descarga
            $this->db->set('lugar_descarga', $this->input->post("lugar_descarga"));
            if ($this->input->post("capacidad_bombeo_turbina_cliente")) {
                $this->db->set('capacidad_bombeo_turbina_cliente', number_to_mysql($this->input->post("capacidad_bombeo_turbina_cliente")));
            }  
			if ($this->input->post("velocidad_media_a_k")) {
				$this->db->set("velocidad_media_a_k", number_to_mysql($this->input->post("velocidad_media_a_k")));
			}   
			if ($this->input->post("velocidad_media_d")) {
				$this->db->set("velocidad_media_d", number_to_mysql($this->input->post("velocidad_media_d")));
			}       
            $this->db->insert('m_lugar_descarga');
            
            // Obtener el ultimo id agregado del lugar de descarga
            $last_id = $this->db->insert_id();
            
            // Agregar productos y lugares de descarga
            $productos = $this->input->post("productos");
            $data = array();
            foreach ($productos as $key => $value) {
                $data[] = array(
                    "fk_lugar_descarga_id" => $last_id, 
                    "fk_producto_id" => $value
                );              
            }   
            $this->db->insert_batch('lugar_descarga_producto', $data);
            
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
    public function editar($id = '') {
        
        // Comenzar transaccion
        $this->db->trans_start();
            
            // Actualizar lugar de descarga
            $this->db->set('lugar_descarga', $this->input->post("lugar_descarga"));
            if ($this->input->post("capacidad_bombeo_turbina_cliente")) {
                $this->db->set('capacidad_bombeo_turbina_cliente', number_to_mysql($this->input->post("capacidad_bombeo_turbina_cliente")));
            }     
			if ($this->input->post("velocidad_media_a_k")) {
				$this->db->set("velocidad_media_a_k", number_to_mysql($this->input->post("velocidad_media_a_k")));
			} 
			if ($this->input->post("velocidad_media_d")) {
				$this->db->set("velocidad_media_d", number_to_mysql($this->input->post("velocidad_media_d")));
			}      
            $this->db->where('m_lugar_descarga_id', $id);
            $this->db->update('m_lugar_descarga');
            
            // Eliminar la relacion de lugares de descarga y productos
            $this->db->where('fk_lugar_descarga_id', $id);
            $this->db->delete('lugar_descarga_producto');
                        
            // Agregar productos y lugares de descarga
            $productos = $this->input->post("productos");
            $data = array();
            foreach ($productos as $key => $value) {
                $data[] = array(
                    "fk_lugar_descarga_id" => $id, 
                    "fk_producto_id" => $value
                );              
            }   
            $this->db->insert_batch('lugar_descarga_producto', $data);
            
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

    public function eliminar() {
        $ids = $this->input->post('id');
        if (count($ids) <= 0)
            return FALSE;

        // Comenzar transaccion
        $this->db->trans_start();

            // Eliminar lugar_descargas
            foreach ($ids as $key => $id) {
                
                // Eliminar lugar descarga productos
                $this->db->where('fk_lugar_descarga_id', $id);
                $this->db->delete('lugar_descarga_producto');
                
                // Eliminar lugar de descarga
                $this->db->where('m_lugar_descarga_id', $id);
                $this->db->delete('m_lugar_descarga');
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

    public function get_all() {
        $this->db->order_by('lugar_descarga', 'asc');
        return $this->db->get('m_lugar_descarga');
    }
    
    //-------------------------------------------------------------------------
    
    /**
     * Get cb_by_ld
     * 
     * Obtener capacidades de bombeo segun el lugar de descarga
     */
      public function get_productos_by_ld($id='')
      {
          if ($id=="") return FALSE;
        
          $this->db->where('fk_lugar_descarga_id', $id);
          return $this->db->get('lugar_descarga_producto');
      }

}
