<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Install extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }

    // ------------------------------------------------------------------------

    function index() 
    {
      $this->load->view('install/sfirst');  
    }

    // ------------------------------------------------------------------------

    function ssecond()
    {
    	// Mostrar la segunda ventana del wizard
    	if (! $this->input->post('createdb'))
    		$this->load->view('install/ssecond');
    	// Escribir configuracion
    	else
    	{
    		// comprobar que los datos proporcionados son validos para la conexion
				$dbhost       = $this->input->post('dbhost');
				$userpassword = $this->input->post('userpassword');
				$username     = $this->input->post('username');
				$dbname       = $this->input->post('dbname');

    		$enlace = mysql_connect($dbhost, $username, $userpassword);
    		// No se conecto
				if (!$enlace) 
				{
					$data['mysql_connect_error'] = 1;
					$this->load->view('install/ssecond', $data);
				}
				// Se conecto
				else
				{
					// Configurar fichero de database
					$pass1 = $this->database_conf($dbhost, $userpassword, $username, $dbname);	

					// Configurar el fichero de librerias
					if ($pass1)
					$pass2 = $this->autoload_conf();

					// no se pudo configurar
					if (! $pass1 OR ! $pass2)
					{
						$data['config_error'] = 1;
						$this->load->view('install/ssecond', $data);
					}
					// Se configuro
					else
					{
            // Crear base de datos
            if ($this->_createdb($dbhost, $username, $userpassword, $dbname, $enlace))
            {
                $this->load->view('install/sthird');
            }
            else
            {
              $data['create_db'] = 1;
              $this->load->view('install/ssecond', $data);
            }
						
					}

					// Crear bd
				}
				mysql_close($enlace);    		
    	}
    }

    // ------------------------------------------------------------------------

    public function sthird()
    { 
      $pass = true;
      // restaurar la base de datos de inicio que esta en backup
      if (! $this->session->userdata('restore'))
      $pass = $this->restore_sql("./backup/inicio.sql");

      if (! $pass)
      {        
        $data['load_init_db'] = 0;
        $this->load->view('install/sfourth');
      }
      // Se restauro la bd de inicio
      else
      {
        $this->session->set_userdata('restore', 'done');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="resalt">', '</div>');

        $this->form_validation->set_rules('uebname', 'Empresa', 'trim|required|xss_clean');
        $this->form_validation->set_rules('username', 'Usuario', 'trim|required|xss_clean');
        $this->form_validation->set_rules('pass', 'Password', 'required|matches[confirm_pass]|trim');
        $this->form_validation->set_rules('confirm_pass', 'Confirmar password', 'required|trim');

        if ($this->form_validation->run() == FALSE)
        {
          $this->load->view('install/sfourth');
        }
        else
        {
          // insertar los datos de la empresa
          $this->db->set('empresa', $this->input->post('uebname'));
          $this->db->insert('empresa');
          $empresaid = $this->db->insert_id();

          // insertar los datos del administrador
          $this->load->library('encrypt');
          
          $this->db->set('nombre', 'installer');
          $this->db->set('apellidos', 'installer');
          $this->db->set('email', 'installer@trans.cupet.cu');
          $this->db->set('nombre_login', $this->input->post('username'));
          $this->db->set('password_login', $this->encrypt->sha1($this->input->post('pass')));
          $this->db->set('fecha_alta', date('Y-m-d'));
          $this->db->set('fk_empresa_id', $empresaid);
          $this->db->set('fk_perfil_id', 3);
          $this->db->insert('usuario');

          $array = array('done' => 'ok');          
          $this->session->set_userdata( $array );          

           $this->load->view('install/final');
        }
        
      }
    }

    // ------------------------------------------------------------------------

    public function restore_sql($sql_path='')
    {
        $backup=file_get_contents($sql_path);
        if ($backup) {
                     
            $sql_clean = '';
            foreach (explode("\n", $backup) as $line){
                
                if(isset($line[0]) && $line[0] != "#"){
                    $sql_clean .= $line."\n";
                }                
            }
            
            foreach (explode(";\n", $sql_clean) as $sql){
                $sql = trim($sql);
                if($sql) 
                {
                    $this->db->query($sql);
                } 
            }
            
            return true;
        }else{
            return false;  
        }        
    }

    // ------------------------------------------------------------------------

    public function database_conf($dbhost, $userpassword, $username, $dbname)
    {
    	$this->load->helper('file');
      $c = read_file("./application/config/database.php");
      
      $host = "['default']['hostname']" . " = '" . $dbhost . "'";
      $user = "['default']['username']" . " = '" . $username . "'";
      $pass = "['default']['password']" . " = '" . $userpassword . "'"; 
      $db   = "['default']['database']" . " = '" . $dbname . "'"; 
      
      $c = str_replace("['default']['hostname'] = ''", $host, $c);
      $c = str_replace("['default']['username'] = ''", $user, $c);
      $c = str_replace("['default']['password'] = ''", $pass, $c);
      $c = str_replace("['default']['database'] = ''", $db, $c);
      
      if ( ! write_file("./application/config/database.php", $c)) return FALSE; else return TRUE;      
    }

    // ------------------------------------------------------------------------

    public function autoload_conf()
    {
    	$this->load->helper('file');
      $c = read_file("./application/config/autoload.php");
      
      $library = "autoload['libraries'] = array('session', 'pagination', 'form_validation', 'logs', 'users')";
      $library_replace = "autoload['libraries'] = array('database', 'session', 'pagination', 'form_validation', 'logs', 'users')";
      
      $c = str_replace($library, $library_replace, $c);
      
      if ( ! write_file("./application/config/autoload.php", $c)) return FALSE; else return TRUE;
    }

    // ------------------------------------------------------------------------

    public function _createdb($h, $u, $p, $db, $enlace)
    {
      $sql = "CREATE DATABASE IF NOT EXISTS " . $db . " COLLATE 'utf8_unicode_ci'";
      if (mysql_query($sql, $enlace)) return true; else return false;      
    }

    // ------------------------------------------------------------------------

    public function clear_installation_data()
    {
      $this->load->helper('file');
      $cont = "./application/controllers/install.php";
      $view = "./application/views/install";
      if(is_dir($view)) rmdir($view);
      if(is_file($cont)) unlink($cont);
      redirect('entrada');
    }
}
        

/* End of file install.php */
/* Location: ./application/controllers/install.php */