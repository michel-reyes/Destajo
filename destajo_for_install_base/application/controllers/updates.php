<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CLASE PARA REALIZAR ACTUALIZACIONES EN LOS MODULOS Y BASE DE DATOS DE DESTAJO
 * 
 * 14/10-2013
 * 
 * 1- los ficheros para actualizar que se le entregara a los usuarios tendra la extencion .zip
 * 2- el nombre de dicho fichero debe tener la siguiente mascara: du-year-month-day.zip donde "du" significa destajo update
 * 3- dentro del fichero de actualizacion deben existir como minimo 2 ficheros
 * 4- estos dos ficheros deben ser 
 * 		a) fichero de instrucciones
 * 		b) fichero(s) de codigo ejecutable
 * 5- el fichero de INSTRUCCIONES
 * 		- debe tener un nombre con la siguiente mascara: dia-mes-año.upe
 * 		- el contenido debe tener el siquiente formato: 
 * 			- por cada fichero de ejecucion debe existir una instruccion
 * 			- por cada instruccion debe haber un comentario que lo explique
 * 		- Ejemplo para ficheros ejecutables de BASE DE DATOS
 * 			periodo_pago.mkd
 * 			Actualiza los campos de la tabla paeriodo_pago para los campos varchar sean de 100 caracteres
 * 		- Ejemplo para ficheros ejecutables de SOBREESCRITURA ****
 * 			a_h_MY_date_helper.cpe -> application/helpers/MY_date_helper.php
 * 			Actualizacion para convertir fechas
 * 			**  En el caso de los ficheros de SOBREESCRITURA el formato es com se muestra a continuacion
 * 				filename.extencion -> (la flecha indica que se debe copiar a:) destino filename.extencion **
 * 6- los FICHEROS EJECUTABLES deben tener codigo tanto PHP como SQL ejecutables y funcionales ya probados
 */
class Updates extends CI_Controller {
	
	protected $modulo = array(
        'nombre' => "update", 
        'display' => "Actualizaciones"
    );
	
	//---------------------------------------------------------------------
	
	public function index()
	{
		$this->load->view('updates/upload_form', array('error' => ' ' ));
	}
	
	//---------------------------------------------------------------------
	
	function do_upload()
	{
		$config['upload_path'] = './restore_temp/';
		$config['allowed_types'] = '*'; // MKD Ejecutar en base de datos
										// CPE Sobreescribir contenido
										// UPE fichero de instrucciones
		$config['max_size']	= '3698524';
		$config['overwrite'] = true;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('updates/upload_form', $error);
		}
		else
		{
			$ud = $this->upload->data();
			$this->read_update($this->upload->data($ud));
			// Almacenar la direccion del fichero de atualizacion en session
			// para poder acceder a el posteriormente	
			$this->session->set_userdata("update", $ud["full_path"]);
		}
	}
	
	//---------------------------------------------------------------------
	
	public function read_update($file='')
	{
		// Leer el fichero de actualizacion y buscan dentro la extencion "upe"
		$a = $this->unzip($file["full_path"]);
		$y = "";
		foreach($a as $k => $v) {
			if (strpos($v["name"], ".upe") !== false) {
				$y = $v["content"];
			}
		}
		$data["pieces"] = explode("\n", $y);
		$this->load->view("updates/upload_info", $data);
	}
	
	//---------------------------------------------------------------------
	
	public function update()
	{
		$this->load->helper('file');		
		$u = $this->session->userdata("update");
		$a = $this->unzip($u);
		
		// Obtener informacion de las actualizaciones
		$y = "";
		foreach($a as $k => $v) {
			if (strpos($v["name"], ".upe") !== false) {
				$y = $v["content"];
			}
		}
		$pieces = explode("\n", $y);
		
		
		// Ejecutar actualizaciones
		foreach ($a as $key => $value) {
			
			//------------------------------------------------
			// el fichero es de ejecucion
			if (strpos($value["name"], ".mkd") !== FALSE) 
			{
				$sql = $value["content"];
				$x = $this->db->query($sql);
				if (! $x ){
					$error['db'][] = "No se pudo actualizar: " . $value['name'];
				}
			}
			
			//------------------------------------------------
			// el fichero es de sobreescritura
			if (strpos($value["name"], ".cpe") != FALSE)
			{ 
				// Obtener direccion de destino
				$path = "";
				foreach ($pieces as $k => $v) {
					if (strpos($v, $value["name"]) !== FALSE)
					{
						$path = substr($v, strlen($value["name"] . " -> "));
						break;
					}
				}			
				
				$c = $value["content"];
				if (!write_file('./' . $path, $c)){
					$error['file'][] = "No se pudo actualizar: " . $value["name"];	
				}
			}			
		}
	}
	
	
	//----------------------------------------------------------------
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
	
			// Add to the files array.
			$files[] = array(
				"name" => $header["filename"],
				"size" => $header["size"],
				"directory" => $directory,
				"content" => !$directory ? $content : false
			);
	
		}
	
		fclose($handle);
	
		// Return an array of files that were extracted.
		return $files;
	}
}
