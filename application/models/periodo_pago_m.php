<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
DESTAJO-MODULE

date: 2014.12.17
type: php module
path: application/models/periodo_pago_m.php

DESTAJO-MODULE-END
*/

class Periodo_pago_m extends CI_Model {
	
    /**
     * Get_all
     * 
     * Obtener la lista completa del modulo
     */
    
    public function get_all()
    {
    	$this->db->where('periodo_pago_id', 1);
        return $this->db->get('periodo_pago')->row();
    }
    
    //-------------------------------------------------------------------------
    
    /**
     * Apertura 
     * 
     * Abrir el periodo de pago
     */
     public function apertura()
     {
         // Comenzar transaccion
         $this->db->trans_start();
              
             $fecha_inicio = $this->input->post('fecha_inicio_periodo_pago');
             // Crear una fecha de cierre
             // -Debe ser igual al ultimo dia del mes de la fecha de inicio
             $sql_fecha_inicio = to_sql($fecha_inicio);
             $str_fecha_inicio = strtotime($sql_fecha_inicio);
             $sql_fecha_cierre = date("Y-m-d", strtotime("last day of this month", $str_fecha_inicio));
             $str_fecha_cierre = strtotime($sql_fecha_cierre);
             
             // Abrir periodo de pago
             
             $this->db->set('fecha_inicio_periodo_pago',  $str_fecha_inicio);
             $this->db->set('fecha_final_periodo_pago', $str_fecha_cierre, $key);
             $this->db->set('perioro_pago_abierto', TRUE);
             $this->db->set('fondo_horario', number_to_mysql($this->input->post('fondo_horario')));
             $this->db->where('periodo_pago_id', 1);
             $this->db->update('periodo_pago');
         
         // Terminar transaccion
        $this->db->trans_complete();        
        return $this->db->trans_status();
     }

     //------------------------------------------------------------------------
     
     /**
      * Check Entrada Salida
      * 
      * Comprueba la cantidad de entradas y de salidas para el periodo de pago actual
      */
      
      public function checkEntradaSalida($fecha_inicio='', $fecha_cierre='')
      {
          if ($fecha_cierre == "" || $fecha_inicio == "") return FALSE;
          
          $query = array();
          $this->db->start_cache();
              
              // Rango de fechas
              $this->db->where('fecha_inicio_periodo_pago >=', $fecha_inicio);
              $this->db->where('fecha_final_periodo_pago <=', $fecha_cierre);
              
          $this->db->stop_cache();
          
              // Entradas
              $query['count_entradas'] = $this->db->count_all('entrada');
              // Salida cumplimiento de la norma
              $query['count_salida_cumplimineto_norma'] = $this->db->count_all_results('salida_cumplimiento_norma');
              // Salida salario equipo
              $query['count_salida_salario_equipo'] = $this->db->count_all_results('salida_salario_equipo');
              // Salida salario trabajador
              $query['count_salida_salario_trabajador'] = $this->db->count_all_results('salida_salario_trabajador');
              
          $this->db->flush_cache();
          
          return $query;          
      }

     //-------------------------------------------------------------------------
    
    /**
     * Cierre 
     * 
     * Cerrar el periodo de pago
     */
     public function cierre()
     {
         // Comenzar transaccion
         $this->db->trans_start();
              
             $fecha_cierre = $this->input->post('fecha_final_periodo_pago');
             
             // Cerrar periodo de pago
             $this->db->set('perioro_pago_abierto', FALSE);
             $this->db->where('periodo_pago_id', 1);
             $this->db->update('periodo_pago');
         
         // Terminar transaccion
        $this->db->trans_complete();        
        return $this->db->trans_status();
     }

    //-------------------------------------------------------------------------
    
    /**
     * Check open
     * 
     * Para los modulos entrada y salida(s)
     * se comprueba que el periodo de pago este abierto para poder insertar datos
     * en caso contrario se sale, muestra mensaje de error
     */
    
    public function check_open($ppa=0)
    {
        if ($ppa == 1) return TRUE;
        
        if ($ppa == 0) {
            if ($this->uri->segment(2) === FALSE) {
                // Necesaria para mostrar mensaje 403 por JS
                $this->session->set_flashdata('noaccess_redirect', 'Actualmente no hay ning&uacute;n periodo de pago abierto.<br/>Usted no puede acceder aqu&iacute; hasta que no se habra un nuevo periodo de pago.');                    
                redirect('erd');
            }else {
                // Si la peticion fue por AJAX retornar FALSE 
                // para gestionar en el controlador la respuesta correcta
                if ($this->input->is_ajax_request()){
                    return FALSE;
                }
                // Necesaria para mostrar mensaje 403 por JS
                $this->session->set_flashdata('noaccess_redirect', 'Actualmente no hay ning&uacute;n periodo de pago abierto.<br/>Usted no puede acceder aqu&iacute; hasta que no se habra un nuevo periodo de pago.');              
                redirect($this->uri->segment(1));
            }
        }
        
    }
}   