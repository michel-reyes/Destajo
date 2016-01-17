<?php
/**
 * Controller
 * -----------
 * -----------
 * <?php

class Install extends CI_Controller {
    
    var $config_database_file = "./application/config/database.php";
    var $config_autoload_file = "./application/config/autoload.php";
    
 
    
    public function index()
    {        
        $this->load->view('template/t_header');
        $this->load->view('install/wizard');
        $this->load->view('template/t_footer');
    }
    
  
    
    public function createdb()
    {
        $data = array();
        $cdb_pass = FALSE;
        $cal_pass = FALSE;
        
        $host = $this->input->post('host');
        $user = $this->input->post('user');
        $pass = $this->input->post('pass');
        
        $db = "CREATE DATABASE IF NOT EXISTS `destajo` DEFAULT CHARACTER SET utf8;";        
        $link = @mysql_connect($host, $user, $pass);
        
        if (!$link) 
        {
            $data['error'][] = "No se pudo conectar a la Base de Datos con los datos proporcionados";
            
        }
        else
        {
            if (mysql_query($db, $link)) 
            { 
                $data['susess'][] =  "Se ha creado la base de datos Destajo.";
                // Modificar dicheros de base de datos
                if ($this->database_config() == FALSE)
                {
                    $data['error'][] = "No se han podido modificar los ficheros de configuraci&oacute;n de la base de datos";    
                }
                else 
                {
                    $cdb_pass = TRUE;    
                }
                
                if ($this->autoload_config() == FALSE)
                {
                    $data['error'][] = "No se ha podido autocargar el fichero de base de datos";
                }
                else 
                {
                    $cal_pass = TRUE;    
                }
            } 
            else 
            {
                $data['error'][] = 'Error al crear la base de datos: ' . mysql_error();
            }   
        }
        
        if ($cal_pass == TRUE && $cdb_pass == TRUE) 
        {
            $data['finish'] = TRUE;
        }
        
        $this->load->view('template/t_header');
        $this->load->view('install/wizard', $data);
        $this->load->view('template/t_footer');
    }
    
 
    
    public function database_config()
    {
        $this->load->helper('file');
        $c =  read_file($this->config_database_file);
        
        $host = "['default']['hostname']" . " = '" . $this->input->post('host', TRUE) . "'";
        $user = "['default']['username']" . " = '" . $this->input->post('user', TRUE) . "'";
        $pass = "['default']['password']" . " = '" . $this->input->post('pass', TRUE) . "'"; 
        
        $c = str_replace("['default']['hostname'] = ''", $host, $c);
        $c = str_replace("['default']['username'] = ''", $user, $c);
        $c = str_replace("['default']['password'] = ''", $pass, $c);
        
        if ( ! write_file($this->config_database_file, $c))
        {
             return FALSE;
        }
        else
        {
             return TRUE;
        }
    }

  
    
    public function autoload_config()
    {
        $this->load->helper('file');
        $c = read_file($this->config_autoload_file);
        
        $library = "autoload['libraries'] = array('session', 'pagination', 'form_validation', 'logs', 'users')";
        $library_replace = "autoload['libraries'] = array('database', 'session', 'pagination', 'form_validation', 'logs', 'users')";
        
        $c = str_replace($library, $library_replace, $c);
        
        if ( ! write_file($this->config_autoload_file, $c))
        {
             return FALSE;
        }
        else
        {
             return TRUE;
        }
    }
    
    
    
    public function restore()
    {
        $backup=file_get_contents("./backup/inicio.sql");
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
            
            $data['status'] = "done";
        }else{
            $data['status'] = "error";    
        } 
        echo json_encode($data);
    }
    
}
 * 
 * 
 * View->install->wizard.php
 * --------
 * ----------
 * <div class="container-fluid">
    <div class="row-fluid">
        <div class="span6 offset3">
            <div class="well">
                <h4 class="text-center">Instalaci&oacute;n b&aacute;sica de Destajo</h4>
                <?php echo form_open('install/createdb'); ?>
                    
                                        
                    <?php if (isset($error)): ?>
                        <?php foreach ($error as $key => $value): ?>
                            <div class="alert alert-danger"><?php echo $value; ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <?php if (isset($susess)): ?>
                        <?php foreach ($susess as $key => $value): ?>
                            <div class="alert"><?php echo $value; ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                
                    <label>Host</label>
                    <input type="text" name="host" value="localhost" />
                    <p class="help-block">Servidor donde esta instalado MySQL, en caso de estar en la propia PC debe ponerse <strong>localhost</strong></p>
                    <br />
                    
                    <label>Ususario</label>
                    <input type="text" name="user" value="root" />
                    <p class="help-block">Usuario de MySQL</p>
                    <br />
                    
                    <label>Password</label>
                    <input type="text" name="pass" />
                    <p class="help-block">Password del usuario de MySQL</p>
                    <br />
                    
                    <?php if (isset($finish) && $finish == TRUE): ?>
                        <?php echo form_hidden('load_table_script', 'load'); ?>
                        <?php $this->session->set_userdata('install', 'done'); ?>
                        <?php echo anchor('entrada', 'Finalizar', array('class'=>'btn btn-success')); ?>
                    <?php else: ?>
                        <button type="submit" name="btn_create_db" class="btn btn-primary">Siguiente</button>
                    <?php endif; ?>
                    
                    
                </form>
            </div>
        </div>
    </div>
</div>
 */