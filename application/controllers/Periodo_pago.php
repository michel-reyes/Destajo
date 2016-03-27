<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
DESTAJO-MODULE

date: 2015.05.11
type: php module
path: application/controllers/periodo_pago.php

DESTAJO-MODULE-END
*/

class Periodo_pago extends CI_Controller {	
	
    protected $fipp = "";
    protected $ffpp = "";
    protected $ppa = "";
    protected $fh  = "";
    
    //-------------------------------------------------------------------------
    
	public function __construct()
    {
        parent::__construct();      
        $this->load->model('periodo_pago_m');
        $this->load_config();
    }	
    
    //-------------------------------------------------------------------------
    
    public function load_config()
    {        
        $pp = $this->periodo_pago_m->get_all();
        
        $this->fipp = ($pp->fecha_inicio_periodo_pago == "0") ? 0 : $pp->fecha_inicio_periodo_pago;
        $this->ffpp = ($pp->fecha_final_periodo_pago == "0") ? 0 :  $pp->fecha_final_periodo_pago;
        $this->fh = ($pp->fondo_horario == NULL) ? NULL : $pp->fondo_horario;
		
        $this->ppa = (int)$pp->perioro_pago_abierto;
		return TRUE;
    }
    
    //-------------------------------------------------------------------------
    
    /**
     * Validar
     * 
     * Validar el formulario
     */
     
    public function validar()
    {
        $response = array();
        // Reglas de validacion para APERTURA
        if ($this->input->post('accion') && $this->input->post('accion') == "apertura") {
            $this->form_validation->set_rules('fecha_inicio_periodo_pago', 'Fecha de inicio', 'trim|required|regex_match[/^([0-9]{1,2}\/([0-9]{1,2})\/([0-9]{4}))/]|callback_periodo_pago_apertura_date_check');        
        }

        $this->form_validation->set_rules('fondo_horario', 'Fondo horario', 'trim|required');
        
        // Reglas de validacion para CIERRE
        if ($this->input->post('accion') && $this->input->post('accion') == "cierre") {
            $this->form_validation->set_rules('fecha_final_periodo_pago', 'Fecha de cierre', 'trim|required|regex_match[/^([0-9]{1,2}\/([0-9]{1,2})\/([0-9]{4}))/]|callback_periodo_pago_cierre_date_check');
        }
        
        // Validacion fallo
        if ( $this->form_validation->run() == FALSE ){
            $response = array(
                "status" => "validation_error",
                "campo" => array(),
                "error" => array()
            );
            $fields = $this->form_validation->_field_data;
            foreach ($fields as $key => $value) {
                if (strlen($fields[$key]['error']) > 0 ){
                    $response['campo'][] = $fields[$key]['field'];
                    $response['error'][] = $fields[$key]['error'];
                }               
            }
        }       
        // Validacion paso
        else{
            $response["status"] = 'validation_pass';
        }
        
        echo json_encode($response);
    }

    //-------------------------------------------------------------------------   
    
    /**
     * Validar fecha de apertura
     */
     
     public function periodo_pago_apertura_date_check($user_date)
     {         
         $message = "";
                  
         // La fecha que envia el usuario tiene el formato d/m/Y
         // para poder comparar debemos convertir a MySQL time
         $user_date_sql = to_sql($user_date);
         
         // Convertimos a strtotime la fecha en formato MySQL
         $user_date_str = strtotime($user_date_sql);         
         
         // Es la primera vez que se agrega una fecha de apertura (borraron los datos, o se instalo el software)
         if ($this->fipp == 0 && $this->ffpp == 0 && $this->ppa == 0)
         {             
             // Comprobaremos que el dia es el primero del mes enviado             
             $first_day = date('Y-m-d', strtotime('first day of this month', $user_date_str));
			
                                       
             if ($user_date_sql == $first_day)
             {
                 return TRUE;
             }
             else 
             {
                 $message = "Basandonos en la fecha proporcionada la fecha de inicio del periodo de pago debe ser: " . to_date($first_day);   
                 $this->form_validation->set_message('periodo_pago_apertura_date_check', $message);
                 return FALSE;  
             }          
         }
         
         // El periodo de pago esta cerrado por el usuario
         if ($this->fipp != 0 && $this->ffpp != 0 && $this->ppa == 0)
         {
             // Comprobamos que el dia es el perimero del mes siguiente a la fecha de cierre             
             $first_day_next_month = date('Y-m-d', strtotime('first day of next month', $this->ffpp));
             if ($user_date_sql == $first_day_next_month)
             {
                 return TRUE;       
             }
             else 
             {
                 $message = "Basandonos en la fecha de cierre la fecha actual debe ser: " . $first_day_next_month;   
                 $this->form_validation->set_message('periodo_pago_apertura_date_check', $message);
                 return FALSE; 
             }
                        
         }      
     }

    //-------------------------------------------------------------------------
    
    /**
     * Validar fecha de cierre
     */
    public function periodo_pago_cierre_date_check($user_date=0)
    {
        $message = "";
         
         // La fecha que envia el usuario tiene el formato d/m/Y
         // para poder comparar debemos convertir a MySQL time
         $user_date_sql = to_sql($user_date);
         
         if ($this->fipp != 0 && $this->ffpp != 0 && $this->ppa == 1)
         {
             // Comporbar que la fecha es igual al primer dia del proximo mes
             $last_day_this_month = date("Y-m-d", strtotime('last day of this month', $this->ffpp));
             if ($user_date_sql == $last_day_this_month)
             {
                // Comprobar que existan entradas y salidas para el periodo que finaliza
                 $counts = $this->periodo_pago_m->checkEntradaSalida($this->fipp, $this->ffpp); 
				
                 if ($counts['count_entradas'] > 0 && $counts['count_salida_cumplimineto_norma'] > 0 &&
                    $counts['count_salida_salario_equipo'] > 0 && $counts['count_salida_salario_trabajador'] > 0) {
                    return TRUE;       
                 }else{
                    $this->form_validation->set_message('periodo_pago_cierre_date_check', 'Con los datos actuales usted no puede realizar un cierre de periodo.<br/>No pueden existir entradas ni salidas vacias.');
                    return FALSE;
                 }        
             }
             else 
             {
                 $message = "Basandonos en las fecha del periodo de pago actual, la fecha proporcionada debe ser: " . to_date($last_day_this_month);   
                 $this->form_validation->set_message('periodo_pago_cierre_date_check', $message);
                 return FALSE;
             }
         }
         else 
         {
             $message = "Parece que su periodo de pago no esta abierto o los datos del periodo de pago est&aacute;n corruptos";   
             $this->form_validation->set_message('periodo_pago_cierre_date_check', $message);
             return FALSE;       
         } 
    }
    
	//-------------------------------------------------------------------------
	
	/**
	 * Apertura
     * 
	 * Abrir un nuevo periodo de pago
	 */
    public function apertura()
    {
        $this->users->can('PeriodoPago.Abrir', TRUE);
        
        // Mostrar formulario
        if ( !$this->input->post('accion') || $this->input->post('accion') != "apertura" ){
            
            // Antes de mostrar el formulario comprobar que:
            // periodo de pago este cerrado, de lo contrario no se puede abrir
            $periodo_pago = $this->periodo_pago_m->get_all();
            if ($periodo_pago->perioro_pago_abierto == 1) {                
                echo "periodo_pago_abierto";
            }else{
                $this->load->view('periodo_pago/apertura_v');
            }
            
        }
        // Abrir periodo de pago
        else {
            $response = array();
             $query = $this->periodo_pago_m->apertura();
             if ( $query === TRUE ){
                 $response['status'] = "pp_apertura_pass";
             }
             else {
                 $response['status'] = "agregar_error";
             }
            echo json_encode($response);
        }                
    }  

    //-------------------------------------------------------------------------
    
    /**
     * Cierre
     * 
     * Cerrar el periodo de pago
     */
    public function cierre()
    {
        $this->users->can('PeriodoPago.Cerrar', TRUE);
        
        // Mostrar formulario
        if ( !$this->input->post('accion') || $this->input->post('accion') != "cierre" ){
            
            // Antes de mostrar el formulario comprobar que:
            // periodo de pago este cerrado, de lo contrario no se puede cerrar
            if ($this->ppa == 0) {                
                echo "periodo_pago_cerrado";
            }else{
			$data['ffpp'] = $this->ffpp;
                $this->load->view('periodo_pago/cierre_v', $data);
            }
            
        }
        // Cerrar periodo de pago
        else {
            $response = array();
             $query = $this->periodo_pago_m->cierre();
             if ( $query === TRUE ){
                 $response['status'] = "pp_cierre_pass";
             }
             else {
                 $response['status'] = "agregar_error";
             }
            echo json_encode($response);
        }                
    } 

    //-------------------------------------------------------------------------
    
    /**
     * Estado
     * 
     * Estado del periodo de pago
     */
    public function estado()
    {
        $data['ppa'] = $this->ppa;
        $data['fipp'] = $this->fipp;
        $data['ffpp'] = $this->ffpp;
        $this->load->view('periodo_pago/estado_v', $data);                
    } 
	
}
