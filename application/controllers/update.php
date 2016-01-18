<?php
/*
DESTAJO-MODULE

date: 2014.12.17
type: php module
path: application/controllers/update.php

DESTAJO-MODULE-END
*/

class Update extends CI_Controller {

	// Index
	// Cargar la vista para cargar actualizaciones

	public function index()
	{
		$this->load->view('update/update_main');
	}

	//-------------------------------------------------------------------------

	// File Upload
	// Cargar fichero de actualizaciones

	public function file_upload()
	{
		// Configuracion para la subida del fichero
		$config['upload_path'] = "./restore_temp";
		$config['allowed_types'] = "*";
		$config['max_size'] = "4096"; // ~4Mb
		$config['overwrite'] = true;

		$this->load->library('upload', $config);

		// No se ha podido subir el fichero
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('update/update_main', $error);
		}
		// Se ha subido el fichero
		else
		{
			$upload_data = $this->upload->data();
			$upload_zip_path = $upload_data['full_path'];
			$this->session->set_userdata('upload_zip_path', $upload_zip_path);

			// Obtener la informacion del zip
			$unzip = $this->unzip($upload_zip_path);

			// Crear la Tabla de actualizaciones si no existe
			if ($this->db->table_exists('updates') == false) {
				$this->db->query("CREATE TABLE `updates` ( `update_id` int(4) unsigned NOT NULL AUTO_INCREMENT, `path` varchar(255) DEFAULT NULL, `fichero` varchar(255) NOT NULL, `status` varchar(10) NOT NULL DEFAULT 'waiting', `date` varchar(11) DEFAULT NULL, PRIMARY KEY (`update_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8");
			}

			// Obtener lista previa de actualizaciones
			$this->db->order_by('date', 'asc');
			$this->db->where('status', 'done');
			$data['old_update_list'] = $this->db->get('updates');

			// Agregar lista de nuevas actualizaciones y marcar como en espera
			foreach ($unzip as $key => $value) {

				$query = $this->db->get_where('updates', array('fichero' => $value['name']), 1);

				if ($query->num_rows() <= 0)
				{
					// Solo guardar las extenciones .dtj
					// Evitar guardar o analizar los recursos
					if ($value['ext'] == ".dtj")
					{
						$this->db->set("fichero", $value['name']);
						$this->db->insert('updates');
					}
				}
			}

			// Obtener lista de ficheros en espera
			$names = array();
			$query = $this->db->get_where('updates', array('status' => 'waiting'));
			if ($query->num_rows() > 0)
			foreach ($query->result() as $f) {
				$names[] = $f->fichero;
			}
			$data['new_update_list'] = $names;

			// Mostrar formulario de nuevas actualizaciones
			$this->load->view('update/update_list', $data);

		}
	}

	//-------------------------------------------------------------------------

	// Do Update
	// Realiza la actualizacion

	public function do_update()
	{
		$response = array();
		$fichero = $this->input->post('fichero');

		// Comprobar que el fichero exista en la bd como pendiente
		$query = $this->db->get_where('updates', array('fichero' => $fichero, 'status' => 'waiting'));
		if ($query->num_rows() > 0)
		{
			// Comprobar que el fichero esta en el zip
			// devolver el contenido en caso de estar
			$content = $this->check_file_in_zip($fichero, $this->session->userdata('upload_zip_path'));

			// El fichero no esta en el zip
			if ($content == false)
			{
				// Marcar en bd como fail
				$this->db->set('status', "fail");
				$this->db->where('fichero', $fichero);
				$this->db->update('updates');

				$response['title'] = "El fichero no se encuentra en el zip";
				$response['status'] = "fail";
				echo json_encode($response);
			}

			// El fichero esta en el zip
			else
			{
				// Obtener instrucciones
				$instrucciones = substr($content, strpos($content, "/*"), strpos($content, "*/"));
				$content_array = explode("\n", $instrucciones);
				$date = "";
				$type="";
				$path="";
				$create = array();
				$delete = array();

				// Obtener instrucciones
				foreach ($content_array as $key => $value) {
					// date
					if (strpos($value, "date:")!==false) $date=substr($value, strpos("date:", $value)+5);
					// type
					if (strpos($value, "type:")!==false) $type=trim(substr($value, strpos("type:", $value)+5));
					// path
					if (strpos($value, "path:")!==false) $path=trim(substr($value, strpos("path:", $value)+5));
					// create
					if (strpos($value, "create:")!==false) $create[]=trim(substr($value, strpos("create:", $value)+7));
					// delete
					if (strpos($value, "delete:")!==false) $delete[]=trim(substr($value, strpos("delete:", $value)+7));
				}

				// Ejecutar instrucciones
				$this->exe_instruction($fichero, $content, $date, $type, $path, $create, $delete);
			}
		}
		else
		{
			$response['status'] = "fail";
			$response['title'] = "El fichero no esta pendiente en la bd";
			echo json_encode($response);
		}

		//echo json_encode($response);
	}

	//-------------------------------------------------------------------------

	// Check file in zip
	// Comprobar que eun fichero este en el zip

	public function check_file_in_zip($fichero='', $zip_path='')
	{
		if ($fichero=="" || $zip_path=="") return false;
		$existe = false;
		$content = NULL;

		$unzip = $this->unzip($zip_path);
		foreach ($unzip as $key => $value)
		{
			log_message('debug', $value['name']);
			if(strpos($value['name'], $fichero)!==false)
			{

				$content = $value['content'];
				$existe = true;
				//break;
			}
		}

		if ($existe == true) return $content;
		else
		if ($existe == false) return false;
	}

	//-------------------------------------------------------------------------

	// Exe instruction
	// Ejecutar las instrucciones

	public function exe_instruction($fichero, $content, $date, $type, $path, $create, $delete)
	{

		if ($fichero == "" || $content == "" || $type == "")
		{
			$response['status'] = "fail";
			$response['title'] = "Falta algun argumento de instrucciones";
			echo json_encode($response);
		}

		$response = array();
		$this->load->helper('file');

		// Acciones sobre la BD
		// Crear un model temporal y ejecutar
		if ($type == "sql module")
		{
			if ( ! write_file("./" . $path . "/temporal.php", $content))
			{
				$response['status'] = "fail";
			    $response['title'] = "No se pudo copiar el fichero a: " . "./" . $path . "/temporal.php";
			    echo json_encode($response);
			}
			else
			{
			    // Ejecutar el SQL del modelo

			    $this->load->model('temporal');
			    $query = $this->temporal->one();

			    if ($query == true)
			    {

			    	// Eliminar el fichero temporal del modelo
			    	@unlink("./" . $path . "/temporal.php");

			    	// Marcar el fichero como completado en la bd
			    	$this->db->set('path', $path);
			    	$this->db->set('status', 'done');
			    	$this->db->set('date', date("Y.m.d"));
			    	$this->db->where('fichero', $fichero);
			    	$this->db->update('updates');

			    	$response['status'] = "done";
			    	$response['title'] = "Todo bien";
			   	    echo json_encode($response);
			    }
			    else if ($query == false)
			    {
			    	// Marcar el fichero como fail en la bd
			    	$this->db->set('path', $path);
			    	$this->db->set('status', 'fail');
			    	$this->db->set('date', date("Y.m.d"));
			    	$this->db->where('fichero', $fichero);
			    	$this->db->update('updates');

			    	$response['status'] = "fail";
			    	$response['title'] = "No se pudo ejecutar la consulta";
			   	    echo json_encode($response);
			    }
			}

		}

		else

		// Acciones de actualizacion JS | PHP | CSS
		// Actualizar un contenido o crearlo si no existe
		if ($type == "php module" || $type == "js module" || $type == "css module")
		{

			// Intentar crear directorio si no existe
			// quitar el fichero de la lista de directorios a crear
			$this->rmkdir(substr($path, 0, strrpos($path, "/")+1));


			if ( ! write_file("./" . $path, $content))
			{
				// Marcar el fichero como fail en la bd
		    	$this->db->set('path', $path);
		    	$this->db->set('status', 'fail');
		    	$this->db->set('date', date("Y.m.d"));
		    	$this->db->where('fichero', $fichero);
		    	$this->db->update('updates');

				$response['status'] = "fail";
			    $response['title'] = "No se pudo actualizar el fichero";
			   	echo json_encode($response);
			}
			else
			{
				// Marcar el fichero como completado en la bd
		    	$this->db->set('path', $path);
		    	$this->db->set('status', 'done');
		    	$this->db->set('date', date("Y.m.d"));
		    	$this->db->where('fichero', $fichero);
		    	$this->db->update('updates');

				$response['status'] = "done";
			    $response['title'] = "Todo bien";
			   	echo json_encode($response);
			}
		}

		else

		//  Acciones de crear o eliminar un recurso del servidor

		if ($type == "resource module")
		{
			$error=false;

			// Delete
			foreach ($delete as $key => $value) {
				if (is_writable($value)) @unlink($value);
			}

			// Create
			foreach ($create as $key => $value) {

				// Obtener el nombre del recurso a crear
				$res_name = substr($value, strrpos($value, "/")+1);

				// Obtener el contenido del recurso a crear
				$content = $this->check_file_in_zip($res_name, $this->session->userdata('upload_zip_path'));

				// Crear el fichero
				$this->load->helper('file');
				if ( ! write_file($value, $content))
				{
				     $response['resourse_error'][] = $res_name;
					 $error=true;
				}
			}

			if ($error==true) {

				$this->db->set('path', $path);
		    	$this->db->set('status', 'fail');
		    	$this->db->set('date', date("Y.m.d"));
		    	$this->db->where('fichero', $fichero);
		    	$this->db->update('updates');

				$response['status'] = "fail";
			    $response['title'] = "Errores";
			   	echo json_encode($response);
			}else{

				$this->db->set('path', $path);
		    	$this->db->set('status', 'done');
		    	$this->db->set('date', date("Y.m.d"));
		    	$this->db->where('fichero', $fichero);
		    	$this->db->update('updates');

				$response['status'] = "done";
			    $response['title'] = "Todo bien";
			   	echo json_encode($response);
			}
		}


		// Eliminar el fichero de actualizaciones temporales
		//@unlink($this->session->userdata('upload_zip_path'));
	}


	//-------------------------------------------------------------------------
	/**
	 * Extract the contents of a ZIP file, and return a list of files it contains and their contents.
	 *
	 * @param string $filename The filepath to the ZIP file.
	 * @return array An array of files and their details/contents.
	 *
	 * @package esoTalk
	 */
	function unzip($filename)
	{
		$files = array();
		$handle = fopen($filename, "rb");

		// Seek to the end of central directory record.
		$size = filesize($filename);
		@fseek($handle, $size - 22);

		// Error checking.
		if (ftell($handle) != $size - 22) return false; // Can't seek to end of central directory?
		// Check end of central directory signature.
		$data = unpack("Vid", fread($handle, 4));
		if ($data["id"] != 0x06054b50) return false;

		// Extract the central directory information.
		$centralDir = unpack("vdisk/vdiskStart/vdiskEntries/ventries/Vsize/Voffset/vcommentSize", fread($handle, 18));
		$pos = $centralDir["offset"];

		// Loop through each entry in the zip file.
		for ($i = 0; $i < $centralDir["entries"]; $i++) {

			// Read next central directory structure header.
			@rewind($handle);
			@fseek($handle, $pos + 4);
			$header = unpack("vversion/vversionExtracted/vflag/vcompression/vmtime/vmdate/Vcrc/VcompressedSize/Vsize/vfilenameLen/vextraLen/vcommentLen/vdisk/vinternal/Vexternal/Voffset", fread($handle, 42));

			// Get the filename.
			$header["filename"] = $header["filenameLen"] ? fread($handle, $header["filenameLen"]) : "";

			// Save the position.
			$pos = ftell($handle) + $header["extraLen"] + $header["commentLen"];

			// Go to the position of the file.
			@rewind($handle);
			@fseek($handle, $header["offset"] + 4);

			// Read the local file header to get the filename length.
			$localHeader = unpack("vversion/vflag/vcompression/vmtime/vmdate/Vcrc/VcompressedSize/Vsize/vfilenameLen/vextraLen", fread($handle, 26));

			// Get the filename.
			$localHeader["filename"] = fread($handle, $localHeader["filenameLen"]);
			// Skip the extra bit.
			if ($localHeader["extraLen"] > 0) fread($handle, $localHeader["extraLen"]);

			// Extract the file (if it's not a folder.)
			$directory = substr($header["filename"], -1) == "/";
			if (!$directory and $header["compressedSize"] > 0) {
				if ($header["compression"] == 0) $content = fread($handle, $header["compressedSize"]);
				else $content = gzinflate(fread($handle, $header["compressedSize"]));
			} else $content = "";

			// Get the file extencion MICHEL REYES
			$header["file_ext"] = ($header["filename"] == "") ? "" : substr($header["filename"], strrpos($header["filename"], "."));

			// Add to the files array.
			$files[] = array(
				"name" => $header["filename"],
				"size" => $header["size"],
				"ext" => $header["file_ext"],
				"directory" => $directory,
				"content" => !$directory ? $content : false
			);

		}

		fclose($handle);

		// Return an array of files that were extracted.
		return $files;
	}

	// ------------------------------------------------------------------------

	/**
	  * Makes directory and returns BOOL(TRUE) if exists OR made.
	  *
	  * @param  $path Path name
	  * @return bool
	  */
	public function rmkdir($path, $mode = 0755) {

	     $path = rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), "/");

	     $e = explode("/", ltrim($path, "/"));
	     if(substr($path, 0, 1) == "/") {
	         $e[0] = "/".$e[0];
	     }
	     $c = count($e);
	     $cp = $e[0];
	     for($i = 1; $i < $c; $i++) {
	         if(!is_dir($cp) && !@mkdir($cp, $mode)) {
	             return false;
	         }
	         $cp .= "/".$e[$i];
	     }
	     return @mkdir($path, $mode);
	 }

}
