<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Salva_restaura extends CI_Controller {
	
	protected $modulo = array(
        'nombre' => "salva_restaura", 
        'display' => "Salva / Restaura"
    );
	
	//-------------------------------------------------------------------------
	
	public function __construct()
    {
        parent::__construct();
        
    }
	
	//-------------------------------------------------------------------------
	
	/**
	* Salva
	* 
	* Crear una salva de la base de datos y la exporta al usuario
    * Los ficheros de salva se almacenan en la carpeta backup del server 
	* @return object
	*/
	 
	public function salva()
	{
	    // Salvar
	    if ($this->input->post('exportar')) {
                        
            // Asignar un nombre de fichero de salva
            $file_name = $this->input->post('filename');
            if (! $file_name OR $file_name == "") $file_name = "mybackup";
                       
            // Agregar fecha y hora al nombre del fichero
            if ($this->input->post('fn_datetime') == TRUE) 
            {
                $date_str = date("d_m_Y_g;i;a");
                $file_name .= $date_str;
            }        
            
            // Agregar extencion al fichero
            $file_name .= ".sql";                
            
    	    // Load the DB utility class
            $this->load->dbutil();
            
            // Backup your entire database and assign it to a variable
            $prefs = array(            
                'ignore'      => array(),           // List of tables to omit from the backup
                'format'      => 'txt',             // gzip, zip, txt
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                'newline'     => "\n",              // Newline character used in backup file
                'fk_checks'   => FALSE              // No chekear fk para poder importar sin restricciones
             );
    
            $backup =& $this->dbutil->backup($prefs); 
            
            // Load the file helper and write the file to 
            // your browser download default directory
            $this->load->helper('file');
            write_file('backup/' . $file_name, $backup);
            
            // Load the download helper and send the file to your desktop
            $this->load->helper('download');
            force_download($file_name, $backup, TRUE);
            
            $x = array();
            $x['ok'] = "ok";
            echo json_encode($x);
                        
        }
        // Mostrar formulario
        else{
            $this->load->view('salva_restaura/salva_v');
        }
	}

    //-------------------------------------------------------------------------
    
    /**
     * Restaura
     * 
     * Restaura los datos salvados previamente en la base de datos
     */
     
     public function restaura()
    {
        $response = array();
            
        $config['upload_path'] = './restore_temp/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '10240';
        $config['overwrite'] = TRUE;
        
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload()){
            $response = array('errores' => $this->upload->display_errors());
        }else{
            $response['upload_success'] = $this->upload->data();
            $response['database_restore'] = $this->restore_sql('./restore_temp/' . $response['upload_success']['file_name']);
        }
        echo json_encode($response);
    }
    
    //---------------------------------
    
    public function restaura_form()
    {
        $this->load->view('salva_restaura/restaura_v');
    }
    
    //-------------------------------------------------------------------------
    
    /**
     * Restore SQL
     * 
     * restaurar los datos subidos a la base de datos
     */
    
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
            
            // Todo ok
            $x = "Se ha recuperado la base de datos con exito";
            return $x;
        }else{
            $x = "No hay contenido";
            return $x;    
        }        
    }
}
