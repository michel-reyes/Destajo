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
     	 // obtnemos los datos del usurio en la bd
        $this->db->limit(1);
        $this->db->where('nombre_login', $this->input->post('nombre_login'));
        $this->db->join('empresa e', 'e.empresa_id = u.fk_empresa_id', 'left');
        $this->db->join('perfil p', 'p.perfil_id = u.fk_perfil_id', 'left');
        $query =  $this->db->get('usuario u');
        if ($query->num_rows() <= 0 ) {
            return false;
        }
        else {
            // el hash es igual al password?
            $r = $query->row();
            $this->encryption->initialize(
                array(         
                    'cipher' => 'aes-128',
                    'mode' => 'cbc',
                    'key' => $this->input->post('nombre_login'),
                    'hmac_digest' => 'sha256'
                )
            );
            $password = $this->encryption->decrypt($r->password_login);
            if ($password == $this->input->post('password_login')) {
                return $query;
            }
            else {
                return false;
            }
        }
     }
}