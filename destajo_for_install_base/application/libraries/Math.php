<?php  

/*
DESTAJO-MODULE

date: 2014.05.20
type: php module
path: application/libraries/Math.php

DESTAJO-MODULE-END
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Math
 * 
 * Seccion Mayorista
 * =================
 * 
 * X- Calcular importe del viaje
 * A- Calcular toneladas realizadas
 * B- Calcular toneladas kilometros producidos
 * C- Calcular entregas adicionales
 * D- Calcular tiempo total de carga
 * E- Calcular tiempo total de descarga
 * F- Calcular tarifa centavos minutos
 * G- Obtener normativa por sigla
 * H- Obtener tiempo de carga
 * I- Obtener capacidad de carga
 * J- Obtener tiempo de descarga
 * K- Obtener ID categoria del operario segun la capacida de carga del equipo
 * L- Obtener tarifa de pago segun categoria del operario
 * M- Obtener Kms por via segun id carga descarga
 * N- Obtener velocidad tecnica segun via
 * O- Obtener la tarifa completa de la tabla tarifa de pago segun id operario
 * P- Es operario auxiliar?
 * Q- Obtener tarifa de pago para auxiliares segun el id de la categoria del operario
 * R- Calcular el destajo progresivo
 * R1- Calcular el destajo progresivo incrementado
 * Y- Calcular cumplimiento de la norma
 * S- Obtener id_categoria del operario segun la chapa del operario
 * 
 * Seccion Minorista
 * =================
 * 
 * Aa- Calcular importe de la carga
 * Ba- Calcular importe de la descarga
 * Ca- Calcular Normas de tiempo para 1 kilometro
 * Da- Determinar los gastos de tiempo para las entregar
 * Ea- Obtener la velocidad media por segun el producto
 */
class Math {
	
	/**
    * Variable globales
    */
    var $G_capacidad_carga = FALSE;
    var $G_normativa_valor = FALSE;
    var $G_tiempo_carga = FALSE;
    var $G_tiempo_total_carga = FALSE;
    var $G_tiempo_descarga = FALSE;
    var $G_tiempo_total_descarga = FALSE;
    var $G_row_trifa_pago = FALSE;
    var $G_es_operario_auxiliar = FALSE;
    var $G_id_categoria_operario = FALSE; 
    var $G_id_categoria_operario_auxiliar = FALSE;   
    var $G_tarifa_centavos_minutos = FALSE;
    var $G_row_trifa_pago_auxiliar = FALSE;
    var $G_toneladas_realizadas = FALSE;
    var $G_row_Kms_via = FALSE; 
    var $G_array_velocidad_tecnica_via = FALSE;
    var $G_tarifa_km_producido = FALSE;
    var $G_entregas_adicionales = FALSE;
    var $G_importe_viaje = FALSE;
    var $G_tarifa_completa = FALSE;
    var $G_cumplimiento_norma = FALSE;
	// Minorista
	var $GM_importe_carga = FALSE;
	var $GM_importe_descarga = FALSE;
	var $GM_importe_km_recorrido = FALSE;
	var $GM_gasto_tiempo_entregas = FALSE;
	var $GM_velocidad_media = FALSE;
    
           
    //----------------------------------------------------------------------
       
    /**
    * G- Obtener normativa por sigla
    * 
    * @param sigla
    * @return Var or False
    */       
    public function G($sigla)
    {
        if (empty($sigla)) return FALSE;
            
        $CI = & get_instance();
        $query = $CI->db->get_where('m_normativa', array('sigla' => $sigla), 1); 
            
        $this->G_normativa_valor = ($query->num_rows() > 0) ? $query->row()->valor : FALSE;
        
        
        $CI =& get_instance();
        //$CI->logs->log("info", "Funcion (F): sigla= " . $sigla . " -- valor= " . $this->G_normativa_valor);
        
        
        return $this->G_normativa_valor;
    }
        
    //---------------------------------------------------------------------
        
    /**
    * H- Obtener tiempo de carga
    * 
    * @param capacidad_carga_id
    * @param producto_id
    * @param carga_descarga_id
    * @return Var or False
    */ 
    public function H(
        $capacidad_carga_id,
        $producto_id,
        $carga_descarga_id)
    {
        if (empty($capacidad_carga_id) OR empty($producto_id) OR empty($carga_descarga_id)) return FALSE;

        $CI = & get_instance();
		
        
        $CI->logs->log("info", "Funcion (H): capacidad_carga_id= " . $capacidad_carga_id . " -- producto_id= " . $producto_id
        . " -- carga_descarga_id= " . $carga_descarga_id);

        // Obtener el ID lugar de carga
        $query = $CI->db->get_where('carga_descarga', array('carga_descarga_id' => $carga_descarga_id), 1);
		
        $fk_lugar_carga_id = ($query->num_rows() > 0) ? $query->row()->fk_lugar_carga_id : FALSE;
        
        $CI->logs->log("info", "Funcion (H): fk_lugar_carga_id= " . $fk_lugar_carga_id);

        // Obtener el tiempo de carga
        if (! empty($fk_lugar_carga_id)) {
            $CI->db->where('fk_capacidad_carga_id', $capacidad_carga_id);
            $CI->db->where('fk_producto_id', $producto_id);
            $CI->db->where('fk_lugar_carga_id', $fk_lugar_carga_id);
            $query = $CI->db->get('tiempo_carga', 1);

            $this->G_tiempo_carga = ($query->num_rows() > 0) ? $query->row()->tiempo_carga : FALSE;
            
            $CI->logs->log("info", "Funcion (H): tiempo de carga= " . $this->G_tiempo_carga);
            return $this->G_tiempo_carga;
        }else{
            $CI->logs->log("info", "Funcion (H): Lugar carga id=vacio");
            return FALSE;
        }
    }

    //---------------------------------------------------------------------

    /**
    * D- Calcular tiempo total de carga
    * @param capacidad_carga_id
    * @param producto_id
    * @param carga_descarga_id
    * @return Var or False
    *
    * Se divide en 4 calculos
    * 1- x = tiempo de carga + (tiempo auxiliar/2) + (tred/2)
    * 2- tiempo de servicio = (ts/60) * x
    * 3- TDNP = (6.25/100) * (tiempo de servicio+x)
    * 4- resultado = x + tiempo de servicio + TDNP
    */
    public function D(
        $capacidad_carga_id,
        $producto_id,
        $carga_descarga_id)
    {
        if (empty($capacidad_carga_id) OR empty($producto_id) OR empty($carga_descarga_id)) return FALSE;

        // Obtener tiempo de carga
        $this->H($capacidad_carga_id, $producto_id, $carga_descarga_id);
    
        // Obtener tiempo auxiliar
        $ta = $this->G("TA");
    
        // Obtener tred: tiempo de recogida y entrega de documentos
        $tred = $this->G("TRED");
    
        $x = $this->G_tiempo_carga + round(($ta / 2), 2) + round(($tred / 2), 2);
    
        // Calcular tiempo de servicio
        $ts = round((($this->G("TS") / 60) * $x), 2);
    
        // Calcular tiempo de necesidades personales
        $tdnp = round(((6.25 / 100) * ($ts + $x)), 2);
    
        $this->G_tiempo_total_carga = round($x + $ts + $tdnp, 2);
        
        $CI =& get_instance();
        //$CI->logs->log('info', "Funcion (D): tiempo auxiliar= $ta --- TRED= $tred --- x= $x --- tiempo servicio= $ts --- tdnp= $tdnp --- tiempo total de carga= $this->G_tiempo_total_carga");
        
        return $this->G_tiempo_total_carga;
    }
    
    //-----------------------------------------------------------------------
      
    /**
    * I- Obtener capacidad de carga
    * 
    * @param capacidad_carga_id
    * @return Var or False
    */
    public function I($capacidad_carga_id)
    {
        if (empty($capacidad_carga_id)) return FALSE;
           
        $CI = & get_instance();
        $query = $CI->db->get_where('m_capacidad_carga', array('m_capacidad_carga_id' => $capacidad_carga_id), 1);
        
        //$CI->logs->log("info", "Funcion (I): capacidad_carga_id= $capacidad_carga_id");
           
        $this->G_capacidad_carga = ($query->num_rows() > 0) ? $query->row()->capacidad_carga : FALSE;
        
        //$CI->logs->log("info", "Funcion (I): capacidad_carga= $this->G_capacidad_carga");
        return $this->G_capacidad_carga;
    }
    
    //-------------------------------------------------------------------------
    
    /**
    * J- Obtener tiempo de descarga
    * 
    * @param capacidad_carga_id
    * @param producto_id
    * @param modo_descarga_id
    * @param carga_descarga_id
    * @return Var or False
    */
    
    public function J(
        $capacidad_carga_id,
        $producto_id,
        $modo_descarga_id,
        $carga_descarga_id)
    {
        if (empty($capacidad_carga_id) OR empty($producto_id) OR empty($modo_descarga_id) OR empty($carga_descarga_id)) return FALSE;
        
        $CI = & get_instance();
        
        //$CI->logs->log("info", "Funcion (J): capacidad_carga_id= $capacidad_carga_id -- producto_id= $producto_id -- modo_descarga_id= $modo_descarga_id -- carga_descarga_id= $carga_descarga_id");
        
        // Obtener ID lugar descarga
        $query = $CI->db->get_where('carga_descarga', array('carga_descarga_id' => $carga_descarga_id), 1);
        $fk_lugar_descarga_id = ($query->num_rows() > 0) ? $query->row()->fk_lugar_descarga_id : FALSE; 
        
        //$CI->logs->log("info", "Funcion (J): fk_lugar_descarga_id= $fk_lugar_descarga_id");
        
        // Obtener tiempo de descarga
        if (! empty($fk_lugar_descarga_id)) {
            $CI->db->where('fk_capacidad_carga_id', $capacidad_carga_id);
            $CI->db->where('fk_producto_id', $producto_id);
            $CI->db->where('fk_lugar_descarga_id', $fk_lugar_descarga_id);
            $CI->db->where('fk_modo_descarga_id', $modo_descarga_id);
            $query = $CI->db->get('tiempo_descarga', 1);
            
            $this->G_tiempo_descarga = ($query->num_rows() > 0) ? $query->row()->tiempo_descarga : FALSE;
            //$CI->logs->log("info", "Funcion (J): fk_lugar_descarga_id= $fk_lugar_descarga_id -- tiempo de descarga= ");
            return $this->G_tiempo_descarga;
        }else{
            //$CI->logs->log("info", "Funcion (J): fk_lugar_descarga_id esta vacio");
            return FALSE;
        }
    }

    //-------------------------------------------------------------------------
    
    /**
    * E- Calcular tiempo total de descarga
    * 
    * @param litros_entregados
    * @param capacidad_carga_id
    * @param producto_id
    * @param modo_descarga_id
    * @param carga_descarga_id
    * @return Var or False
    * 
    * Se divide en 5 calculos
    * 1- x = litros entregados / capacidad de carga
    * 2- y = (tiempo de descarga*x) + ( ((tiempo auxiliar/2) * x) + ((tred/2) * x) )
    * 3- tiempo de servicio = (2.26/60) * y
    * 4- tdnp = (6.25/100) * (tiempo de servicio+y)
    * 5- Resultado = y + tiempo de servicio + tdnp
    */
    
    public function E(
        $litros_entregados,
        $capacidad_carga_id,
        $producto_id,
        $modo_descarga_id,
        $carga_descarga_id)
    {
        if (empty($litros_entregados) OR empty($capacidad_carga_id) OR empty($producto_id) OR empty($modo_descarga_id) OR empty($carga_descarga_id)) return FALSE;
        
        $CI = & get_instance();
        /*$CI->logs->log("Info", "Funcion (E): litros_entregados= " . $litros_entregados 
            . " capacidad_carga_id= ". $capacidad_carga_id . " producto_id= " . $producto_id
            . " modo_descarga_id= " . $modo_descarga_id . " carga_descarga_id= " . $carga_descarga_id);*/
        
        // Obtener capacidad de carga
        $this->I($capacidad_carga_id);
        
        $x = round($litros_entregados / $this->G_capacidad_carga, 2);
        
        // Obtener tiempo de descarga
        $this->J($capacidad_carga_id, $producto_id, $modo_descarga_id, $carga_descarga_id);
                
        // Obtener tiempo auxiliar
        $ta = $this->G("TA");
        
        // Obtener tiempo de recogida y entrega de documentos
        $tred = $this->G("TRED");
        
        $y = round(($this->G_tiempo_descarga * $x) + ( (($ta / 2) * $x) + (($tred / 2) * $x) ), 2);        
        
        // Calcular tiempo de servicio
        $ts = round(($this->G("TS") / 60) * $y, 2);
        
        // Calcular tiempo de necesidades personales
        $tdnp = round((6.25 / 100) * ($ts + $y), 2);        
        
        $this->G_tiempo_total_descarga = round($y + $ts + $tdnp, 2);
        
        //$CI->logs->log("info", "Funcion (E): x= $x --- y= $y --- tiempo de servicio= $ts --- tdnp= $tdnp --- tiempo total descarga= $this->G_tiempo_total_descarga");
        return $this->G_tiempo_total_descarga;         
    }

    //-------------------------------------------------------------------------
    
    /**
    * L- Obtener tarifa de pago segun categoria del operario
    * La tarifa de pago se puede obtener de 3 formas (Leer K)
    * Esta funcion devuleve un registro completo (row)
    * 
    * @param categoria_operario_id
    * @return Array or False
    */
    
    public function L($categoria_operario_id)
    {
        if (empty($categoria_operario_id)) return FALSE;
        
        $CI =& get_instance();
        
        $query = $CI->db->get_where('tarifa_pago', array('fk_categoria_operario_id' => $categoria_operario_id), 1);
        $this->G_row_trifa_pago = ($query->num_rows() > 0) ? $query->row() : FALSE;
        
        return $this->G_row_trifa_pago;
    }
    
    //-------------------------------------------------------------------------
    
    /**
    * P- Es operario auxiliar?
    * 
    * @param operario_id
    * @return bool
    * @return ID del operario auxiliar
    */
    public function P($operario_id)
    {
        $CI =& get_instance();
        
        $query = $CI->db->query("select count(o.m_operario_id) as total, co.m_categoria_operario_id
            from m_categoria_operario co
            join m_operario o on o.fk_categoria_operario_id = co.m_categoria_operario_id
            where co.nomenclador = 'F' and o.m_operario_id = " . $operario_id . " limit 1");
            
        $this->G_es_operario_auxiliar = ($query->row()->total >= 1) ? TRUE : FALSE;
        $this->G_id_categoria_operario_auxiliar = ($query->row()->total >= 1) ? $query->row()->m_categoria_operario_id : FALSE;
        
        //$CI->logs->log("info", "Funcion (P): Operario es auxiliar?= $this->G_es_operario_auxiliar --- ctagoria del operario auxiliar= $this->G_id_categoria_operario_auxiliar");
                
        return $this->G_es_operario_auxiliar;
    }
    
    //-------------------------------------------------------------------------
    
    /**
    * K- Obtener ID categoria del operario segun la capacida de carga del equipo
    * 
    * @param capacidad_carga
    * @return Var or False
    */
     
    public function K($capacidad_carga)
    {
        $CI =& get_instance();
        
        $CI->db->where('min_capacidad_carga <= ', $capacidad_carga);
        $CI->db->where('max_capacidad_carga >=', $capacidad_carga);
        $query = $CI->db->get('m_categoria_operario', 1);
        
        $this->G_id_categoria_operario = ($query->num_rows() > 0) ? $query->row()->m_categoria_operario_id : FALSE;
        
        //$CI->logs->log("info", "Funcion (K): id_categoria_operario= $this->G_id_categoria_operario");
        return $this->G_id_categoria_operario;
    } 
    
    //------------------------------------------------------------------------- 
    
    /**
    * Q- Obtener tarifa de pago para auxiliares segun el id de la categoria del operario auxiliar
    * 
    * @param categoria_operario_id
    * @return Array or False
    */
    
    public function Q($categoria_operario_id)
    {
        $CI =& get_instance();        
        $query = $CI->db->get_where('tarifa_pago', array('fk_categoria_operario_id' => $categoria_operario_id), 1);
        
        $this->G_row_trifa_pago_auxiliar = ($query->num_rows() > 0) ? $query->row() : FALSE;
        return $this->G_row_trifa_pago_auxiliar;
    } 
    
    //-------------------------------------------------------------------------   
    
    /**
    * F- Calcular tarifa centavos minutos
     * 
     * -Articulo 14: 
     *     "A partir de los kilometros con carga y de la capacidad de carga del equipo,
     *      se busca la tarifa centavos minutos que corresponde"
     * -Conociendo la capacidad de carga del equipo obtenemos la categoria del operario
     * y a su vez la tarifa d epago segun los kilometros del viaje
     * -Esta funcion esta dividida en 2
     * 1- Calcular la tarifa centavos minutos sabiendo la capacida de carga del equipo
     * 2- Calcular la tarifa centavos minutos cuando el operario es (Auxiliar), la cual
     * se calcula por el ID del operario y NO por la capacidad de carga del equipo
    */
    
    public function F(
        $capacidad_carga_id,
        $km_recorridos_carga,
        $operario_id)
    {
        if (empty($capacidad_carga_id) OR empty($km_recorridos_carga) OR empty($operario_id)) return FALSE;
        
        $CI =& get_instance();
        $CI->logs->log("info", "Funcion (F): capacidad_carga_id= $capacidad_carga_id --- km_recorridos_carga= $km_recorridos_carga --- operario_id= $operario_id");
        
        // Conocer si el operario es auxiliar y obbtener el id del operario
        $this->P($operario_id);
                
        // Calcular la tarifa centavos minutos sabiendo la capacidad de carga del equipo
        // El operario NO es auxiliar
        if ($this->G_es_operario_auxiliar == FALSE) {
            
            // Obtener capacidad de carga
            $this->I($capacidad_carga_id);
            
            // Obtener la categoria del operario segun la capacidad de carga
            $this->K($this->G_capacidad_carga);
            
            // Obtener tarifa de pago segun el ID de la categoria del operario
            $this->L($this->G_id_categoria_operario);
            
            // Calcular tarifa centavos minutos
            $this->G_tarifa_centavos_minutos = ($km_recorridos_carga > 90) ? $this->G_row_trifa_pago->tarifa_mayor : $this->G_row_trifa_pago->tarifa_menor;
            $CI->logs->log("info", "Funcion (F): tarifa centavos minutos: $this->G_tarifa_centavos_minutos");
            return $this->G_tarifa_centavos_minutos;
        }
        
        // Calcular la tarifa centavos minutos cuando el operario es auxiliar 
        elseif ($this->G_es_operario_auxiliar == TRUE) {
            
            // Obtener tarifa de pago para auxiliares segun el id de la categoria del operario auxiliar
            $this->Q($this->G_id_categoria_operario_auxiliar);
            
            // Calcular tarifa centavos minutos
            $this->G_tarifa_centavos_minutos = ($km_recorridos_carga > 90) ? $this->G_row_trifa_pago_auxiliar->tarifa_mayor : $this->G_row_trifa_pago_auxiliar->tarifa_menor;
            $CI->logs->log("info", "Funcion (F): tarifa centavos minutos auxiliares: $this->G_tarifa_centavos_minutos");
            return $this->G_tarifa_centavos_minutos;
        }
    }
    
    //-------------------------------------------------------------------------
    
    /**
    * A- Calcular toneladas realizadas
    * 
    * @param capacidad_carga_id
    * @param km_recorridos_carga
    * @param litros_entregados
    * @param producto_id
    * @param modo_descarga_id
    * @param carga_descarga_id
    * @param operario_id
    * @return float
    * 
    * Depende de 3 funciones: D, E, F
    * toneladas realizadas = ((tiempo total de carga + tiempo total de descarga) * tarifa centavos minutos) / 100
    */
    public function A(
        $capacidad_carga_id,
        $km_recorridos_carga,
        $litros_entregados,
        $producto_id,
        $modo_descarga_id,
        $carga_descarga_id,
        $operario_id)
    {
        // Calcular tiempo total de carga
        $this->D($capacidad_carga_id, $producto_id, $carga_descarga_id);
        
        // Calcular tiempo total de descarga
        $this->E($litros_entregados, $capacidad_carga_id, $producto_id, $modo_descarga_id, $carga_descarga_id);
        
        // Calcular tarifa centavos minutos
        $this->F($capacidad_carga_id, $km_recorridos_carga, $operario_id);
        
        // Calcular toneladas realizadas
        $this->G_toneladas_realizadas = round((($this->G_tiempo_total_carga + $this->G_tiempo_total_descarga) * $this->G_tarifa_centavos_minutos) / 100, 2);
        $CI =& get_instance();
        //$CI->logs->log("info", "Funcion (A): toneladas realizadas= $this->G_toneladas_realizadas");
        return $this->G_toneladas_realizadas;         
    }
    
    //-------------------------------------------------------------------------
    
    /**
    * M- Obtener Kms por via segun id carga descarga
    * 
    * @param carga_descarga_id
    * @return Array or False
    */
    
    public function M($carga_descarga_id)
    {
        if (empty($carga_descarga_id)) return FALSE;
        
        $CI =& get_instance();
        $query = $CI->db->get_where('carga_descarga', array('carga_descarga_id' => $carga_descarga_id), 1);
        
        $this->G_row_Kms_via = ($query->num_rows() > 0) ? $query->row() : FALSE;
        return $this->G_row_Kms_via;
    }
    
    //-------------------------------------------------------------------------
    
    /**
    * N- Obtener velocidad tecnica segun via
    * 
    * #return Array or False
    */
    
    public function N()
    {
        $CI =& get_instance();
             
        $CI->db->where('sigla', 'A');
        $CI->db->or_where('sigla', 'CT');
        $CI->db->or_where('sigla', 'CV');
        $CI->db->or_where('sigla', 'C');
        $CI->db->or_where('sigla', 'CM');
        $CI->db->or_where('sigla', 'PU');
        $CI->db->or_where('sigla', 'T');
        $CI->db->or_where('sigla', 'TM');
        $query = $CI->db->get('m_normativa');
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $via) {
                $this->G_array_velocidad_tecnica_via[$via->sigla] = $via->valor;
                //$CI->logs->log("info", "Funcion (N): velocidad_tecnica_via[$via->sigla]= $via->valor");
            }
            
        }else {
           $this->G_array_velocidad_tecnica_via = FALSE; 
        }
        return $this->G_array_velocidad_tecnica_via;
    }
    
    //-------------------------------------------------------------------------
    
    /**
    * B- Calcular toneladas kilometros producidos
    * 
    * Articulo 11:
    * Depende de 4 calculos
    * 
    * 1- Calcular para cada tramo de via: 
    * Norma de tiempo kilometros recorridos a partir de la velocidad tecnica (VT)
    * utilizando la siguiente formula Norma de tiempo para una via especifica = 63.75 + TPC / vt * 0.5
    * 
    * 2- Cada norma de tiempo para cada tramo de via se multiplica por los kilometros 
    * recorridos con carga en ese tipo de via y con ello se obtiene la Norma de tiempo
    * por cada tramo (Ntramo)
    * Ntramo = NTvia * KmVia
    * 
    * 3- Sumar todas las normas de tiempo de cada tramo obteniendo la norma de viaje (NvTotal)
    * NvTotal = Ntramo1 + Ntramo2 + ...
    * 
    * 4- Calcular la tasa a destajo por toneladas kilometros producidos (Tkp)
    * Tkp = (tarifa del viaje[salario minuto del chofer] * NvTotal) / 100 (Entre 100 para convertir a pesos)  
    */
    
    public function B(
        $capacidad_carga_id,
        $carga_descarga_id,
        $km_recorridos_carga,
        $operario_id)
    {
        if (empty($capacidad_carga_id) OR empty($carga_descarga_id) OR empty($km_recorridos_carga) OR empty($operario_id)) return FALSE;
        
        $CI =& get_instance();
        //$CI->logs->log("info", "Funcion (B): capacidad_carga_id= $capacidad_carga_id --- carga_descarga_id= carga_descarga_id --- km_recorridos_carga= $km_recorridos_carga --- operario_id= $operario_id");
        
        // 1- Calcular para cada tramo de via: Norma de tiempo kilometros recorridos
        
        // Obtener tiempo preparativo conclusivo
        $this->G("TPC");
        $divisor = 63.75 + $this->G_normativa_valor;
        
        // Obtener Kms por via segun id carga descarga
        $KmVia = $this->M($carga_descarga_id);
        
        // Obtener velocidad tecnica segun via
        $vt = $this->N();
        
        // Recorrer cada velocidad tecnica para calcular: Norma de tiempo por cada tramo
        // Sumar NTramo y obtener norma del viaje
        $NvTotal = 0;
        $Ntramo = 0;
        foreach ($vt as $key => $value) {
            
            // 2- Se obtiene la Norma de tiempo * por cada tramo (Ntramo)
            $Ntramo = round((($divisor / ( $vt[$key] * 0.5 )) * $KmVia->$key), 2);
            
            // 3- Sumar todas las normas de tiempo de cada tramo, obteniendose la norma del viaje (NvTotal)
            $NvTotal += $Ntramo;
        }
        
        // 4- Calcular la tasa a destajo por toneladas kilometros producidos
        
        // Calcular tarifa centavos minutos
        $salario_minuto_chofer = $this->F($capacidad_carga_id, $km_recorridos_carga, $operario_id);
        //$CI->logs->log("info", "Funcion (B): NvTotal= $NvTotal --- salario_minuto_chofer= $salario_minuto_chofer"); 
        
        $this->G_tarifa_km_producido = round((($salario_minuto_chofer * $NvTotal) / 100), 2);
        
        //$CI->logs->log("info", "Funcion (B): tarifa_km_producido= $this->G_tarifa_km_producido");
        return $this->G_tarifa_km_producido;        
    } 

    //-------------------------------------------------------------------------
    
    /**
    * C- Calcular entregas adicionales
    */
    
    public function C($numero_de_entregas)
    {
        if (empty($numero_de_entregas)) return FALSE;
        if ($numero_de_entregas <= 1) return 0;
        
        // Obtener pago por entrega adicional
        $pea = $this->G("PEA");
        
        if (empty($pea)) return 0;
                
        $this->G_entregas_adicionales = round(($numero_de_entregas - 1) * $pea, 2);
        return $this->G_entregas_adicionales;
    }
    
    //-------------------------------------------------------------------------
    
    /**
    * X- Calcular importe del viaje
    */
    
    public function X(
        $capacidad_carga_id,
        $km_recorridos_carga,
        $litros_entregados,
        $producto_id,
        $modo_descarga_id,
        $carga_descarga_id,
        $numero_de_entregas,
        $operario_id)
    {
        // Calcular toneladas realizadas
        $this->A($capacidad_carga_id, $km_recorridos_carga, $litros_entregados, $producto_id, $modo_descarga_id, $carga_descarga_id, $operario_id);
        
        // Calcular toneladas kilometros producidos
        $this->B($capacidad_carga_id, $carga_descarga_id, $km_recorridos_carga, $operario_id);
        
        // Calcular entregas adicionales
        $this->C($numero_de_entregas);
        
        // Calcular el importe del viaje
        $this->G_importe_viaje = $this->G_toneladas_realizadas + $this->G_tarifa_km_producido + $this->G_entregas_adicionales;
        return $this->G_importe_viaje;
    }
    
    //-------------------------------------------------------------------------
    
    /**
    * O- Obtener la tarifa completa de la tabla tarifa de pago segun id operario
    */
    
    public function O($operario_id)
    {
        $CI =& get_instance();
        $query = $CI->db->get_where('m_operario', array('m_operario_id' => $operario_id), 1);
        // Obtener categoria del operario
        $categoria_operario_id = ($query->num_rows() > 0) ? $query->row()->fk_categoria_operario_id : NULL;
        
        // Buscar tarifa de pago completa
        $query = $CI->db->get_where('tarifa_pago', array('fk_categoria_operario_id' => $categoria_operario_id), 1);
        $this->G_tarifa_completa = ($query->num_rows() > 0) ? $query->row()->tarifa_completa : NULL;
        
        //$CI->logs->log('info', "Funcion (O): categoria del operario: $categoria_operario_id --- tarifa completa: $this->G_tarifa_completa");
        return $this->G_tarifa_completa;
    }
    
    //-------------------------------------------------------------------------
    
    /**
    * Y- Calcular cumplimiento de la norma
    */
    
    public function Y(
        $operario_id,
        $importe_viaje, 
        $horas_de_viaje)
    {
    	$CI =& get_instance();
		$CI->logs->log("info", "operario: " . $operario_id . " importe: " . $importe_viaje . " horas: " . $horas_de_viaje);
		     
        if (empty($horas_de_viaje)) return FALSE;        
        
        // Calcular la tarifa completa
        $this->O($operario_id);
		$CI->logs->log('info', "tarifa completa: " . $this->G_tarifa_completa);
        
        // Resultado
        $this->G_cumplimiento_norma = round( ( ( $importe_viaje /  ( $horas_de_viaje * $this->G_tarifa_completa ) ) * 100 ), 2 );
        $CI->logs->log('info', "c norma: " . $this->G_cumplimiento_norma);
        
        //$CI->logs->log("info", "Funcion (Y): cumplimiento de la norma= $this->G_cumplimiento_norma");
        return $this->G_cumplimiento_norma;
     }

    // ------------------------------------------------------------------------

    /*
    * S- Obtener id_categoria del operario segun la chapa del operario 
    */ 
    public function S($chapa)
    {
        if (empty($chapa)) return FALSE;
        $CI =& get_instance();

        return $CI->db->get_where('m_operario', array('chapa' => $chapa), 1)->row()->fk_categoria_operario_id;
       
    }
    
    //-------------------------------------------------------------------------
    
    /**
    * R- Calcular el destajo progresivo
    */
    
    public function R(
        $capacidad_carga_id,
        $numero_de_viajes)
    {
        if (empty($capacidad_carga_id) OR empty($numero_de_viajes)) return FALSE;
        $CI =& get_instance();
        
        // Obtener los viajes promedios del equipo
        $viajes_promedio = $CI->db->get_where('m_capacidad_carga', array('m_capacidad_carga_id' => $capacidad_carga_id), 1)->row()->viajes_promedio;
        
        // Calcular la diferencia de viajes promedios con numero de viajes realizados
        $diferencia_de_viajes = ($numero_de_viajes > $viajes_promedio) ? ($numero_de_viajes - $viajes_promedio) : 0 ;
        
        // Obtener el factor de multiplicacion
        $factor = 1;
        if ($diferencia_de_viajes == 1) $factor = $this->G("VP+1"); else        
        if ($diferencia_de_viajes == 2) $factor = $this->G("VP+2"); else        
        if ($diferencia_de_viajes >= 3) $factor = $this->G("VP+3");
        
        //$CI->logs->log('info', "Funcion (R): numero de viajes= $numero_de_viajes --- diferencia de viajes= $diferencia_de_viajes --- factor= $factor --- importe del viaje= $this->G_importe_viaje");
        
        // Calcular el nuevo imorte del viaje con los datos del destajo progresivo
        return (! empty($this->G_importe_viaje)) ? ($this->G_importe_viaje * $factor) : FALSE ;
    }

	//-------------------------------------------------------------------------
	
	/**
	* R1- Calcular el destajo progresivo incrementado
    */
    
    public function R1($capacidad_carga_id, $numero_de_viajes, $fecha_incidencia, $hoja_de_ruta, $editando=FALSE)
    {
    	$vp = 0; // viajes promedio
    	$dv = 0; // diferencia de viajes
    	$factor = 0; // factor de multiplicacion
    	$ivp = 0; // importe del viaje progresivo
		
        if (empty($capacidad_carga_id) OR empty($numero_de_viajes)
			OR empty($fecha_incidencia) OR empty($hoja_de_ruta)) return FALSE;
		
        $CI =& get_instance();
		
		// Obtener los viajes promedios del equipo
        $vp = $CI->db->get_where('m_capacidad_carga', array('m_capacidad_carga_id' => $capacidad_carga_id), 1)->row()->viajes_promedio;
                
		// Calcular la diferencia de viajes promedios contra numero de viajes realizados
        $dv = ($numero_de_viajes > $vp) ? ($numero_de_viajes - $vp) : 0 ;
				
		if ($dv > 0)
		{
			// Obtener el factor de multiplicacion
			$factorA = array(0=>1, 1=>$this->G("VP+1"), 2=>$this->G("VP+2"), 3=>$this->G("VP+3"));
			// En caso de estarse editando obtener el factor tiene una modificacion,
			// se debe obtener el factor del mayor numero de viaje
			if ($dv >= 3)
			{
				$factor = $factorA[3];
			}			
			else 
			{
				if ($editando == FALSE)
				{
					$factor = $factorA[$dv] . "noedi";
				} 
				else 
				{
					$sql = "select e.entrada_id, e.numero_de_viajes, e.importe_viaje, max(e.numero_de_viajes) as mayor";
					$sql .= " from entrada e";
					$sql .= " where e.fecha_incidencia = '". $fecha_incidencia . "'";
					$sql .= " and e.hoja_de_ruta = '". $hoja_de_ruta ."'";
					$sql .= " and e.numero_de_viajes > '". $vp ."'";
					$sql .= " and e.numero_de_viajes is not null";
					$sql .= " limit 1";
					$max = $CI->db->query($sql)->row()->mayor;
					
					$dv = ($max > $vp) ? ($max - $vp) : 0 ;
					
					$factor = ($dv >= 3)? $factorA[3]: $factorA[$dv];
				}
			}
			$ivp = $this->G_importe_viaje * $factor;
			
			// Obtener lista de viajes afectados anetriores al actual
			$sql = "select e.entrada_id, e.numero_de_viajes, e.importe_viaje";
			$sql .= " from entrada e";
			$sql .= " where e.fecha_incidencia = '". $fecha_incidencia . "'";
			$sql .= " and e.hoja_de_ruta = '". $hoja_de_ruta ."'";			
			$sql .= " and e.numero_de_viajes <= '". $numero_de_viajes ."'";
			$sql .= " and e.numero_de_viajes > '". $vp ."'";
			$sql .= " and e.numero_de_viajes is not null";
			$viajes = $CI->db->query($sql);
			$q = $CI->db->last_query();
			$CI->logs->log('info', 'lista: ' . $q);
			
			if ($viajes->num_rows() > 0)
			{				
				foreach ($viajes->result() as $row) 
				{
					$sql = "UPDATE `entrada`";
					$sql .= " SET `importe_viaje_progresivo_i` =" . ($row->importe_viaje * $factor);
					$sql .= " WHERE `entrada_id` =" . $row->entrada_id;
					$CI->db->query($sql);
					$q = $CI->db->last_query();
					$CI->logs->log('info', 'lista: ' . $q);	
				}
			}
			
			return (! empty($this->G_importe_viaje)) ? $ivp : FALSE ;			
		}
		else
		{
			return FALSE;
		}
    }
    
    /**************************************************************************
	 * Seccion Mayorista 
	 *************************************************************************/
	
	/**
	 * Aa
	 * Calcular importe de la carga
	 * Anexo 20
	 */
	public function Aa($capacidad_carga_id, $producto_id, $lugar_carga_id, 
					   $km_recorridos_carga, $operario_id)
	{
		$tdnp = NULL;
		$tc = NULL;
		$ta = NULL;
		$tred = NULL;
		$ntc = NULL;
		$tcm = NULL;
		$tph = NULL;
		$ic = FALSE;
		$CI =& get_instance();
		
		// Formula 10. Tiempo de necesidades personales
		//---------------------------------------------
		
			// 1. Obtener tiempo de carga
			$CI->db->where('fk_capacidad_carga_id', $capacidad_carga_id);
			$CI->db->where('fk_producto_id', $producto_id);
			$CI->db->where('fk_lugar_carga_id', $lugar_carga_id);
			$tc = $CI->db->get('tiempo_carga', 1)->row()->tiempo_carga;			
			
			// 2. Obtener tiempo auxiliar
			$ta = $this->G("TA");
			
			// 3. Obtener tiempo de recogida y entrega de documentos
			$tred = $this->G("TRED");
			
			// 4. Calcular tiempo de necesidades personales
			$tdnp = ($tc + ($ta/2) + ($tred/2)) * 0.0625;
		
		
		// Formula 12. Normativa para la carga en minutos
		//-----------------------------------------------
			
			// 1. Calcular la normativa para la carga
			$ntc = $tc + ($ta/2) + ($tred/2) + $tdnp;		
		
		// Calcular el importe de la carga
		//--------------------------------
		
			// 1. Obtener la tarifa centavos / minutos
			$tcm = $this->F($capacidad_carga_id, $km_recorridos_carga, $operario_id);
			
			// 2. Convertir a pesos / hora
			$tph = $tcm * 0.6;
			
			// 3. Importe carga
			$ic = ($ntc/60) * $tph;
			
			$this->GM_importe_carga = $ic;
			return $ic;
	}

	//-------------------------------------------------------------------------
	
	/**
	 * Ba
	 * Calcular importe de la descarga
	 * Anexo 20
	 */
	public function Ba($capacidad_carga_id, $producto_id, $municipio_id, $modo_descarga_id,
					   $km_recorridos_carga, $operario_id)
	{
		$tdnp = NULL;
		$td = NULL;
		$ta = NULL;
		$tred = NULL;
		$ntd = NULL;
		$tcm = NULL;
		$tph = NULL;
		$id = FALSE;
		$CI =& get_instance();
		
		// Formula 10. Tiempo de necesidades personales
		//---------------------------------------------
		
			// 1. Obtener tiempo de descarga
			$CI->db->where('fk_capacidad_carga_id', $capacidad_carga_id);
			$CI->db->where('fk_producto_id', $producto_id);
			$CI->db->where('fk_lugar_descarga_id', $municipio_id);
			$CI->db->where('fk_modo_descarga_id', $modo_descarga_id);
			$td = $CI->db->get('tiempo_descarga', 1)->row()->tiempo_descarga;
						
			// 2. Obtener tiempo auxiliar
			$ta = $this->G("TA");
			
			// 3. Obtener tiempo de recogida y entrega de documentos
			$tred = $this->G("TRED");
			
			// 4. Calcular tiempo de necesidades personales
			$tdnp = ($td + ($ta/2) + ($tred/2)) * 0.0625;
		
		
		// Formula 12. Normativa para la descarga en minutos
		//-----------------------------------------------
			
			// 1. Calcular la normativa para la descarga
			$ntd = $td + ($ta/2) + ($tred/2) + $tdnp;	
		
		
		// Calcular el importe de la descarga
		//--------------------------------
		
			// 1. Obtener la tarifa centavos / minutos
			$tcm = $this->F($capacidad_carga_id, $km_recorridos_carga, $operario_id);
			
			// 2. Convertir a pesos / hora
			$tph = $tcm * 0.6;
			
			// 3. Importe carga
			$id = ($ntd/60) * $tph;
			
			$this->GM_importe_descarga = $id;
			return $id;
	}

	//-------------------------------------------------------------------------
	
	/**
	 * Ca
	 * Calcular Normas de tiempo para 1 kilometro
	 * Articulo 19
	 */
	
	public function Ca($km_total_recorridos, $fk_municipio_id, 
					   $km_recorridos_carga, $operario_id, $producto_id,
					   $capacidad_carga_id)
	{		
		$vm = 0;
		$tm = 0; 
		$ts = 0;
		$tpc = 0;
		$tdnp = 0;
		$nt = 0;
		$ikr = 0;
		
		// 0. Obtener la velocidad media segun producto y lugar de descarga
		$vm = $this->Ea($fk_municipio_id, $producto_id);
		
		$CI =& get_instance();
		
		// 1. Calcular tiempo en movimiento
		//---------------------------------
		$tm = 1*60/$vm;

        $CI->logs->log("info", "tm: " . $tm);
		
		// 2. Calcular tiempo de servicio
		//-------------------------------
		$tsn = $this->G('TS');
		$ts = ($tsn*$tm)/60;

        $CI->logs->log("info", "ts: " . $ts);
		
		// 3. Calcular tiempo preparativo conclusivo
		//------------------------------------------
		$tpcn = $this->G('TPC');
		$tpc = ($tm+$ts)*$tpcn/60;

        $CI->logs->log("info", "tpc: " . $tpc);
		
		// 4. Calcular tiempo de necesidades personales
		//---------------------------------------------
		$tdnp = ($tm+$ts+$tpc)*0.0625;

        $CI->logs->log("info", "tdnp: " . $tdnp);
		
		// 5. Norma de tiempo para 1 kilometro en minuto
		//----------------------------------------------
		$nt = $tm+$ts+$tpc+$tdnp;
        $CI->logs->log("info", "nt: " . $nt);
		
		// 6. Importe en kilometros recorridos
		//------------------------------------
		$tcm = $this->F($capacidad_carga_id, $km_recorridos_carga, $operario_id);
        $CI->logs->log("info", "tcm: " . $tcm);

		$ikr = ($nt*$km_total_recorridos*$tcm)/100;
        $CI->logs->log("info", "ikr: " . $ikr);
		
		$this->GM_importe_km_recorrido = $ikr;
		return $this->GM_importe_km_recorrido;
	} 

	//-------------------------------------------------------------------------
	
	/**
	 * Da 
	 * Determinar los gastos de tiempo para las entregas
	 * Articulo 22
	 */
	
	public function Da($capacidad_carga_id, $km_recorridos_carga, $operario_id, $numero_entregas,
					   $fecha_incidencia, $hoja_ruta, $numero_de_viajes)
	{
		$tph = 0;
		$tcm = 0;
		$gte = 0;
		$t = 0; // Suma de entregas adicionales anteriores
		$nep = 0;
		
		$CI =& get_instance();
		
		// 1. Obtener tarifa pesos hora
		//-----------------------------
		$tcm = $this->F($capacidad_carga_id, $km_recorridos_carga, $operario_id);
		
		$tph = $tcm*0.6;
		
		
		// 2. Determinar gastos de tiempo para las entregas
		//-------------------------------------------------
		
		// 2.1 Obtener entregas anteriores de este equipo con esta hoja de ruta, esta fecha y este operario
		$CI->db->select_sum('e.numero_de_entregas');
		$CI->db->where('e.fk_capacidad_carga_id', $capacidad_carga_id);
		$CI->db->where('e.hoja_de_ruta', $hoja_ruta);
		$CI->db->where('e.fecha_incidencia', $fecha_incidencia);
        $CI->db->where('e.fk_operario_id', $operario_id);

        if ($numero_de_viajes != NULL) // solo al editar
            $CI->db->where('e.numero_de_viajes !=', $numero_de_viajes);

		$total_entregas = $CI->db->get('entrada e', 1);
		$total_entregas = $total_entregas->row()->numero_de_entregas;
		if (gettype($total_entregas) == NULL) $total_entregas = 0;
		
		// 2.2 Obtener el numero de pagos por entrega
		$ppe = $this->G('PPE');		
		
		// 2.3 Obtener las entregas promedio
		$nep = $CI->db->get_where('m_capacidad_carga', array('m_capacidad_carga_id' => $capacidad_carga_id), 1);
		$nep = $nep->row()->entregas_promedio;
				
		// 2.4 Calcular gastos por entregas progresivo

		if ($total_entregas > $nep) {$gte = $numero_entregas * 2;  $CI->logs->log("info", "arriba");}


		if ($total_entregas <= $nep)
		{
			$a = (20/60) * $tph;
			$b = ( ($total_entregas + $numero_entregas) > $nep ) ? ($ppe - $a) * ( ($total_entregas + $numero_entregas) - $nep ) : 0;
			$gte = $a * $numero_entregas + $b;
            $CI->logs->log("info", "abajo");
		}
		
		$CI->logs->log("info", "t: " . $total_entregas . " gte: " . $gte);
		
		//$gte = (20/60)*$tph*$numero_entregas;
		
		$this->GM_gasto_tiempo_entregas = $gte;
		
		return $this->GM_gasto_tiempo_entregas;
	}
	
	//-------------------------------------------------------------------------
	
	/**
	 * Ea
	 * Obtener velocida media segun el producto
	 */
	 
	public function Ea($fk_municipio_id, $producto_id)
	{
		$CI =& get_instance();
		
		$vm = NULL;
		
		// 1. Obtener el nombre del producto
		$query = $CI->db->get_where('m_producto', array('m_producto_id' => $producto_id), 1);
		$producto = $query->row()->producto;
				
		// 2. Obtener velocidad media
		$CI->db->where('m_lugar_descarga_id', $fk_municipio_id);
		$query = $CI->db->get('m_lugar_descarga');
				
		if ($producto == "Alcohol" || $producto == "Kerosina")
			$vm = $query->row()->velocidad_media_a_k;
		else
		if ($producto == "Diesel")
			$vm = $query->row()->velocidad_media_d;
				
		$this->GM_velocidad_media = $vm;
		return $this->GM_velocidad_media;
	} 
	  
}
/* End of file Math.php */            