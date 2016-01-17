<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
DESTAJO-MODULE

date: 2014.03.10
type: php module
path: application/models/auth_m.php

DESTAJO-MODULE-END
*/

class Auth_m extends CI_Model {
	
	/**
     * Login
     * 
     * Comprueba que los datos del usuario sean los correctos
     * @return array
     */
     public function login()
     {
     	 $this->db->limit(1);
		 $this->db->where('nombre_login', $this->input->post('nombre_login'));
		 $this->db->where('password_login', $this->encrypt->sha1($this->input->post('password_login')));
		 $this->db->join('empresa e', 'e.empresa_id = u.fk_empresa_id', 'left');
		 $this->db->join('perfil p', 'p.perfil_id = u.fk_perfil_id', 'left');
		 return $this->db->get('usuario u');
     }
}