<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users {

    public function can($permiso='', $exit=FALSE)
    {
    	// Instanciamos a CI para utilizarlo como this
    	$CI =& get_instance();
		$CI->load->library('session');
		
    	// Si no se pregunta por un permiso, que hacemos aqui?    	
		if ($permiso == '') return FALSE;
		
		// El usuario no esta logueado		
		if (! $CI->session->userdata('nombre_login')) {
			
			// Se solicito salida
			// Esto ocurre cuando el usuario intenta acceder a una session no prmitida
			// Exit, nos saca del modulo, nos redirecciona si es necesario y muestra un mensaje
			if ($exit) {
				if ($CI->uri->segment(2) === FALSE) {
					// Necesaria para mostrar mensaje 403 por JS
			 		$CI->session->set_flashdata('noaccess_redirect', 'Usted no tiene permisos para acceder a esta sesi&oacute;n');                    
					redirect('erd');
				}else{
					// Si la peticion fue por AJAX retornar FALSE 
				 	// para gestionar en el controlador la respuesta correcta
				 	if ($CI->input->is_ajax_request()){
				 		return FALSE;
				 	}
					// Necesaria para mostrar mensaje 403 por JS
			 		$CI->session->set_flashdata('noaccess_redirect', 'Usted no tiene permisos para acceder a esta sesi&oacute;n');				
				 	redirect($CI->uri->segment(1));
				}
			}else{
				return FALSE;
			}
		}
		// El usuario esta logueado, comprobar su permiso
		else {
			
            // Consultar si el usuario tiene el permiso que se solicita
            $usuario_id = $CI->session->userdata('usuario_id');
            $query = $CI->db->query("select *
            from perfil_permiso pp
            left join perfil pe on pe.perfil_id = pp.fk_perfil_id
            left join permiso p on p.permiso_id = pp.fk_permiso_id
            left join usuario u on u.fk_perfil_id = pe.perfil_id
            where u.usuario_id = $usuario_id and p.nombre ='" . $permiso . "'");
            $can = ($query->num_rows() > 0) ? TRUE : FALSE;  
            
			if ($can) {
				return TRUE;
			}else{
				if ($exit) {
					if ($CI->uri->segment(2) === FALSE) {
						// Necesaria para mostrar mensaje 403 por JS
				 		$CI->session->set_flashdata('noaccess_redirect', 'Usted no tiene permisos para acceder a esta sesi&oacute;n');
						redirect('entrada');
					}else{
						// Si la peticion fue por AJAX retornar FALSE 
					 	// para gestionar en el controlador la respuesta correcta
					 	if ($CI->input->is_ajax_request()){
					 		return FALSE;
					 	}
						// Necesaria para mostrar mensaje 403 por JS
				 		$CI->session->set_flashdata('noaccess_redirect', 'Usted no tiene permisos para acceder a esta sesi&oacute;n');				
					 	redirect($CI->uri->segment(1));
					}
				}else{
					return FALSE;
				}
			}
		}
			
    }
}