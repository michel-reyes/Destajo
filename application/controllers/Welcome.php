<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	public function index() {
		
		$this->load->library('encryption');
		
	
		$nlogin = "michel";
		$plogin = "abrete08";

		$this->encryption->initialize(
			array(
				'cipher' => 'aes-256',
				'mode' => 'cbc',
				'key' => $nlogin
			)
		);
		$cipherpass = $this->encryption->encrypt($plogin);  
		echo "cipher: " . $cipherpass;
		
		$this->xx($cipherpass);	
		
	}
	
	public function xx($cipherpass = "") {
		
		$cipherpass = "de25bc353e61ea3e3fa07040898a7822432128182ca1802134a7a3dff5beb993";
		$nlogin = "michel";
		$this->encryption->initialize(
			array(
				'cipher' => 'aes-256',
				'mode' => 'cbc',
				'key' => $nlogin
			)
		);
		$password = $this->encryption->decrypt($cipherpass);
		echo "\ndecypher: " . $password;
	}
}
