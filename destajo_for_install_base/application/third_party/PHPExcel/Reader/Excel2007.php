<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2013 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel_Reader
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */


/** PHPExcel root directory */
if (!defined('PHPEXCEL_ROOT')) {
	/**
	 * @ignore
	 */
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');
	require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
}

/**
 * PHPExcel_Reader_Excel2007
 *
 * @category	PHPExcel
 * @package	PHPExcel_Reader
 * @copyright	Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 */
class PHPExcel_Reader_Excel2007 extends PHPExcel_Reader_Abstract implements PHPExcel_Reader_IReader
{
	/**
	 * PHPExcel_ReferenceHelper instance
	 *
	 * @var PHPExcel_ReferenceHelper
	 */
	private $_referenceHelper = NULL;

	/**
	 * PHPExcel_Reader_Excel2007_Theme instance
	 *
	 * @var PHPExcel_Reader_Excel2007_Theme
	 */
	private static $_theme = NULL;


	/**
	 * Create a new PHPExcel_Reader_Excel2007 instance
	 */
	public function __construct() {
		$this->_readFilter = new PHPExcel_Reader_DefaultReadFilter();
		$this->_referenceHelper = PHPExcel_ReferenceHelper::getInstance();
	}


	/**
	 * Can the current PHPExcel_Reader_IReader read the file?
	 *
	 * @param 	string 		$pFilename
	 * @return 	boolean
	 * @throws PHPExcel_Reader_Exception
	 */
	public function canRead($pFilename)
	{
		// Check if file exists
		if (!file_exists($pFilename)) {
			throw new PHPExcel_Reader_Exception("Could not open " . $pFilename . " for reading! File does not exist.");
		}

		// Check if zip class exists
		if (!class_exists('ZipArchive',FALSE)) {
			throw new PHPExcel_Reader_Exception("ZipArchive library is not enabled");
		}

		$xl = false;
		// Load file
		$zip = new ZipArchive;
		if ($zip->open($pFilename) === true) {
			// check if it is an OOXML archive
			$rels = simplexml_load_string($this->_getFromZipArchive($zip, "_rels/.rels"));
			if ($rels !== false) {
				foreach ($rels->Relationship as $rel) {
					switch ($rel["Type"]) {
						case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument":
							if (basename($rel["Target"]) == 'workbook.xml') {
								$xl = true;
							}
							break;

					}
				}
			}
			$zip->close();
		}

		return $xl;
	}


	/**
	 * Reads names of the worksheets from a file, without parsing the whole file to a PHPExcel object
	 *
	 * @param 	string 		$pFilename
	 * @throws 	PHPExcel_Reader_Exception
	 */
	public function listWorksheetNames($pFilename)
	{
		// Check if file exists
		if (!file_exists($pFilename)) {
			throw new PHPExcel_Reader_Exception("Could not open " . $pFilename . " for reading! File does not exist.");
		}

		$worksheetNames = array();

		$zip = new ZipArchive;
		$zip->open($pFilename);

		//	The files we're looking at here are small enough that simpleXML is more efficient than XMLReader
		$rels = simplexml_load_string(
		    $this->_getFromZipArchive($zip, "_rels/.rels")
		); //~ http://schemas.openxmlformats.org/package/2006/relationships");
		foreach ($rels->Relationship as $rel) {
			switch ($rel["Type"]) {
				case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument":
					$xmlWorkbook = simplexml_load_string(
					    $this->_getFromZipArchive($zip, "{$rel['Target']}")
					);  //~ http://schemas.openxmlformats.org/spreadsheetml/2006/main");

					if ($xmlWorkbook->sheets) {
						foreach ($xmlWorkbook->sheets->sheet as $eleSheet) {
							// Check if sheet should be skipped
							$worksheetNames[] = (string) $eleSheet["name"];
						}
					}
			}
		}

		$zip->close();

		return $worksheetNames;
	}


	/**
	 * Return worksheet info (Name, Last Column Letter, Last Column Index, Total Rows, Total Columns)
	 *
	 * @param   string     $pFilename
	 * @throws   PHPExcel_Reader_Exception
	 */
	public function listWorksheetInfo($pFilename)
	{
		// Check if file exists
		if (!file_exists($pFilename)) {
			throw new PHPExcel_Reader_Exception("Could not open " . $pFilename . " for reading! File does not exist.");
		}

		$worksheetInfo = array();

		$zip = new ZipArchive;
		$zip->open($pFilename);

		$rels = simplexml_load_string($this->_getFromZipArchive($zip, "_rels/.rels")); //~ http://schemas.openxmlformats.org/package/2006/relationships");
		foreach ($rels->Relationship as $rel) {
			if ($rel["Type"] == "http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument") {
				$dir = dirname($rel["Target"]);
				$relsWorkbook = simplexml_load_string($this->_getFromZipArchive($zip, "$dir/_rels/" . basename($rel["Target"]) . ".rels"));  //~ http://schemas.openxmlformats.org/package/2006/relationships");
				$relsWorkbook->registerXPathNamespace("rel", "http://schemas.openxmlformats.org/package/2006/relationships");

				$worksheets = array();
				foreach ($relsWorkbook->Relationship as $ele) {
					if ($ele["Type"] == "http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet") {
						$worksheets[(string) $ele["Id"]] = $ele["Target"];
					}
				}

				$xmlWorkbook = simplexml_load_string($this->_getFromZipArchive($zip, "{$rel['Target']}"));  //~ http://schemas.openxmlformats.org/spreadsheetml/2006/main");
				if ($xmlWorkbook->sheets) {
					$dir = dirname($rel["Target"]);
					foreach ($xmlWorkbook->sheets->sheet as $eleSheet) {
						$tmpInfo = array(
							'worksheetName' => (string) $eleSheet["name"],
							'lastColumnLetter' => 'A',
							'lastColumnIndex' => 0,
							'totalRows' => 0,
							'totalColumns' => 0,
						);

						$fileWorksheet = $worksheets[(string) self::array_item($eleSheet->attributes("http://schemas.openxmlformats.org/officeDocument/2006/relationships"), "id")];

						$xml = new XMLReader();
						$res = $xml->open('zip://'.PHPExcel_Shared_File::realpath($pFilename).'#'."$dir/$fileWorksheet");
						$xml->setParserProperty(2,true);

						$currCells = 0;
						while ($xml->read()) {
							if ($xml->name == 'row' && $xml->nodeType == XMLReader::ELEMENT) {
								$row = $xml->getAttribute('r');
								$tmpInfo['totalRows'] = $row;
								$tmpInfo['totalColumns'] = max($tmpInfo['totalColumns'],$currCells);
								$currCells = 0;
							} elseif ($xml->name == 'c' && $xml->nodeType == XMLReader::ELEMENT) {
								$currCells++;
							}
						}
						$tmpInfo['totalColumns'] = max($tmpInfo['totalColumns'],$currCells);
						$xml->close();

						$tmpInfo['lastColumnIndex'] = $tmpInfo['totalColumns'] - 1;
						$tmpInfo['lastColumnLetter'] = PHPExcel_Cell::stringFromColumnIndex($tmpInfo['lastColumnIndex']);

						$worksheetInfo[] = $tmpInfo;
					}
				}
			}
		}

		$zip->close();

		return $worksheetInfo;
	}


	private static function _castToBool($c) {
//		echo 'Initial Cast to Boolean<br />';
		$value = isset($c->v) ? (string) $c->v : NULL;
		if ($value == '0') {
			return FALSE;
		} elseif ($value == '1') {
			return TRUE;
		} else {
			return (bool)$c->v;
		}
		return $value;
	}	//	function _castToBool()


	private static function _castToError($c) {
//		echo 'Initial Cast to Error<br />';
		return isset($c->v) ? (string) $c->v : NULL;
	}	//	function _castToError()


	private static function _castToString($c) {
//		echo 'Initial Cast to String<br />';
		return isset($c->v) ? (string) $c->v : NULL;
	}	//	function _castToString()


	private function _castToFormula($c,$r,&$cellDataType,&$value,&$calculatedValue,&$sharedFormulas,$castBaseType) {
//		echo 'Formula<br />';
//		echo '$c->f is '.$c->f.'<br />';
		$cellDataType 		= 'f';
		$value 				= "={$c->f}";
		$calculatedValue 	= self::$castBaseType($c);

		// Shared formula?
		if (isset($c->f['t']) && strtolower((string)$c->f['t']) == 'shared') {
//			echo 'SHARED FORMULA<br />';
			$instance = (string)$c->f['si'];

//			echo 'Instance ID = '.$instance.'<br />';
//
//			echo 'Shared Formula Array:<pre>';
//			print_r($sharedFormulas);
//			echo '</pre>';
			if (!isset($sharedFormulas[(string)$c->f['si']])) {
//				echo 'SETTING NEW SHARED FORMULA<br />';
//				echo 'Master is '.$r.'<br />';
//				echo 'Formula is '.$value.'<br />';
				$sharedFormulas[$instance] = array(	'master' => $r,
													'formula' => $value
												  );
//				echo 'New Shared Formula Array:<pre>';
//				print_r($sharedFormulas);
//				echo '</pre>';
			} else {
//				echo 'GETTING SHARED FORMULA<br />';
//				echo 'Master is '.$sharedFormulas[$instance]['master'].'<br />';
//				echo 'Formula is '.$sharedFormulas[$instance]['formula'].'<br />';
				$master = PHPExcel_Cell::coordinateFromString($sharedFormulas[$instance]['master']);
				$current = PHPExcel_Cell::coordinateFromString($r);

				$difference = array(0, 0);
				$difference[0] = PHPExcel_Cell::columnIndexFromString($current[0]) - PHPExcel_Cell::columnIndexFromString($master[0]);
				$difference[1] = $current[1] - $master[1];

				$value = $this->_referenceHelper->updateFormulaReferences(	$sharedFormulas[$instance]['formula'],
																			'A1',
																			$difference[0],
																			$difference[1]
																		 );
//				echo 'Adjusted Formula is '.$value.'<br />';
			}
		}
	}


	public function _getFromZipArchive(ZipArchive $archive, $fileName = '')
	{
		// Root-relative paths
		if (strpos($fileName, '//') !== false)
		{
			$fileName = substr($fileName, strpos($fileName, '//') + 1);
		}
		$fileName = PHPExcel_Shared_File::realpath($fileName);

		// Apache POI fixes
		$contents = $archive->getFromName($fileName);
		if ($contents === false)
		{
			$contents = $archive->getFromName(substr($fileName, 1));
		}

		return $contents;
	}


	/**
	 * Loads PHPExcel from file
	 *
	 * @param 	string 		$pFilename
	 * @throws 	PHPExcel_Reader_Exception
	 */
	public function load($pFilename)
	{
		// Check if file exists
		if (!file_exists($pFilename)) {
			throw new PHPExcel_Reader_Exception("Could not open " . $pFilename . " for reading! File does not exist.");
		}

		// Initialisations
		$excel = new PHPExcel;
		$excel->removeSheetByIndex(0);
		if (!$this->_readDataOnly) {
			$excel->removeCellStyleXfByIndex(0); // remove the default style
			$excel->removeCellXfByIndex(0); // remove the default style
		}
		$zip = new ZipArchive;
		$zip->open($pFilename);

		//	Read the theme first, because we need the colour scheme when reading the styles
		$wbRels = simplexml_load_string($this->_getFromZipArchive($zip, "xl/_rels/workbook.xml.rels")); //~ http://schemas.openxmlformats.org/package/2006/relationships");
		foreach ($wbRels->Relationship as $rel) {
			switch ($rel["Type"]) {
				case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme":
					$themeOrderArray = array('lt1','dk1','lt2','dk2');
					$themeOrderAdditional = count($themeOrderArray);

					$xmlTheme = simplexml_load_string($this->_getFromZipArchive($zip, "xl/{$rel['Target']}"));
					if (is_object($xmlTheme)) {
						$xmlThemeName = $xmlTheme->attributes();
						$xmlTheme = $xmlTheme->children("http://schemas.openxmlformats.org/drawingml/2006/main");
						$themeName = (string)$xmlThemeName['name'];

						$colourScheme = $xmlTheme->themeElements->clrScheme->attributes();
						$colourSchemeName = (string)$colourScheme['name'];
						$colourScheme = $xmlTheme->themeElements->clrScheme->children("http://schemas.openxmlformats.org/drawingml/2006/main");

						$themeColours = array();
						foreach ($colourScheme as $k => $xmlColour) {
							$themePos = array_search($k,$themeOrderArray);
							if ($themePos === false) {
								$themePos = $themeOrderAdditional++;
							}
							if (isset($xmlColour->sysClr)) {
								$xmlColourData = $xmlColour->sysClr->attributes();
								$themeColours[$themePos] = $xmlColourData['lastClr'];
							} elseif (isset($xmlColour->srgbClr)) {
								$xmlColourData = $xmlColour->srgbClr->attributes();
								$themeColours[$themePos] = $xmlColourData['val'];
							}
						}
						self::$_theme = new PHPExcel_Reader_Excel2007_Theme($themeName,$colourSchemeName,$themeColours);
					}
					break;
			}
		}

		$rels = simplexml_load_string($this->_getFromZipArchive($zip, "_rels/.rels")); //~ http://schemas.openxmlformats.org/package/2006/relationships");
		foreach ($rels->Relationship as $rel) {
			switch ($rel["Type"]) {
				case "http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties":
					$xmlCore = simplexml_load_string($this->_getFromZipArchive($zip, "{$rel['Target']}"));
					if (is_object($xmlCore)) {
						$xmlCore->registerXPathNamespace("dc", "http://purl.org/dc/elements/1.1/");
						$xmlCore->registerXPathNamespace("dcterms", "http://purl.org/dc/terms/");
						$xmlCore->registerXPathNamespace("cp", "http://schemas.openxmlformats.org/package/2006/metadata/core-properties");
						$docProps = $excel->getProperties();
						$docProps->setCreator((string) self::array_item($xmlCore->xpath("dc:creator")));
						$docProps->setLastModifiedBy((string) self::array_item($xmlCore->xpath("cp:lastModifiedBy")));
						$docProps->setCreated(strtotime(self::array_item($xmlCore->xpath("dcterms:created")))); //! respect xsi:type
						$docProps->setModified(strtotime(self::array_item($xmlCore->xpath("dcterms:modified")))); //! respect xsi:type
						$docProps->setTitle((string) self::array_item($xmlCore->xpath("dc:title")));
						$docProps->setDescription((string) self::array_item($xmlCore->xpath("dc:description")));
						$docProps->setSubject((string) self::array_item($xmlCore->xpath("dc:subject")));
						$docProps->setKeywords((string) self::array_item($xmlCore->xpath("cp:keywords")));
						$docProps->setCategory((string) self::array_item($xmlCore->xpath("cp:category")));
					}
				break;

				case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties":
					$xmlCore = simplexml_load_string($this->_getFromZipArchive($zip, "{$rel['Target']}"));
					if (is_object($xmlCore)) {
						$docProps = $excel->getProperties();
						if (isset($xmlCore->Company))
							$docProps->setCompany((string) $xmlCore->Company);
						if (isset($xmlCore->Manager))
							$docProps->setManager((string) $xmlCore->Manager);
					}
				break;

				case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/custom-properties":
					$xmlCore = simplexml_load_string($this->_getFromZipArchive($zip, "{$rel['Target']}"));
					if (is_object($xmlCore)) {
						$docProps = $excel->getProperties();
						foreach ($xmlCore as $xmlProperty) {
							$cellDataOfficeAttributes = $xmlProperty->attributes();
							if (isset($cellDataOfficeAttributes['name'])) {
								$propertyName = (string) $cellDataOfficeAttributes['name'];
								$cellDataOfficeChildren = $xmlProperty->children('http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes');
								$attributeType = $cellDataOfficeChildren->getName();
								$attributeValue = (string) $cellDataOfficeChildren->{$attributeType};
								$attributeValue = PHPExcel_DocumentProperties::convertProperty($attributeValue,$attributeType);
								$attributeType = PHPExcel_DocumentProperties::convertPropertyType($attributeType);
								$docProps->setCustomProperty($propertyName,$attributeValue,$attributeType);
							}
						}
					}
				break;

				case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument":
					$dir = dirname($rel["Target"]);
					$relsWorkbook = simplexml_load_string($this->_getFromZipArchive($zip, "$dir/_rels/" . basename($rel["Target"]) . ".rels"));  //~ http://schemas.openxmlformats.org/package/2006/relationships");
					$relsWorkbook->registerXPathNamespace("rel", "http://schemas.openxmlformats.org/package/2006/relationships");

					$sharedStrings = array();
					$xpath = self::array_item($relsWorkbook->xpath("rel:Relationship[@Type='http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings']"));
					$xmlStrings = simplexml_load_string($this->_getFromZipArchive($zip, "$dir/$xpath[Target]"));  //~ http://schemas.openxmlformats.org/spreadsheetml/2006/main");
					if (isset($xmlStrings) && isset($xmlStrings->si)) {
						foreach ($xmlStrings->si as $val) {
							if (isset($val->t)) {
								$sharedStrings[] = PHPExcel_Shared_String::ControlCharacterOOXML2PHP( (string) $val->t );
							} elseif (isset($val->r)) {
								$sharedStrings[] = $this->_parseRichText($val);
							}
						}
					}

					$worksheets = array();
					foreach ($relsWorkbook->Relationship as $ele) {
						if ($ele["Type"] == "http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet") {
							$worksheets[(string) $ele["Id"]] = $ele["Target"];
						}
					}

					$styles 	= array();
					$cellStyles = array();
					$xpath = self::array_item($relsWorkbook->xpath("rel:Relationship[@Type='http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles']"));
					$xmlStyles = simplexml_load_string($this->_getFromZipArchive($zip, "$dir/$xpath[Target]")); //~ http://schemas.openxmlformats.org/spreadsheetml/2006/main");
					$numFmts = null;
					if ($xmlStyles && $xmlStyles->numFmts[0]) {
						$numFmts = $xmlStyles->numFmts[0];
					}
					if (isset($numFmts) && ($numFmts !== NULL)) {
						$numFmts->registerXPathNamespace("sml", "http://schemas.openxmlformats.org/spreadsheetml/2006/main");
					}
					if (!$this->_readDataOnly && $xmlStyles) {
						foreach ($xmlStyles->cellXfs->xf as $xf) {
							$numFmt = PHPExcel_Style_NumberFormat::FORMAT_GENERAL;

							if ($xf["numFmtId"]) {
								if (isset($numFmts)) {
									$tmpNumFmt = self::array_item($numFmts->xpath("sml:numFmt[@numFmtId=$xf[numFmtId]]"));

									if (isset($tmpNumFmt["formatCode"])) {
										$numFmt = (string) $tmpNumFmt["formatCode"];
									}
								}

								if ((int)$xf["numFmtId"] < 164) {
									$numFmt = PHPExcel_Style_NumberFormat::builtInFormatCode((int)$xf["numFmtId"]);
								}
							}
                            $quotePrefix = false;
							if (isset($xf["quotePrefix"])) {
                                $quotePrefix = (boolean) $xf["quotePrefix"];
                            }
							//$numFmt = str_replace('mm', 'i', $numFmt);
							//$numFmt = str_replace('h', 'H', $numFmt);

							$style = (object) array(
								"numFmt" => $numFmt,
								"font" => $xmlStyles->fonts->font[intval($xf["fontId"])],
								"fill" => $xmlStyles->fills->fill[intval($xf["fillId"])],
								"border" => $xmlStyles->borders->border[intval($xf["borderId"])],
								"alignment" => $xf->alignment,
								"protection" => $xf->protection,
								"quotePrefix" => $quotePrefix,
							);
							$styles[] = $style;

							// add style to cellXf collection
							$objStyle = new PHPExcel_Style;
							self::_readStyle($objStyle, $style);
							$excel->addCellXf($objStyle);
						}

						foreach ($xmlStyles->cellStyleXfs->xf as $xf) {
							$numFmt = PHPExcel_Style_NumberFormat::FORMAT_GENERAL;
							if ($numFmts && $xf["numFmtId"]) {
								$tmpNumFmt = self::array_item($numFmts->xpath("sml:numFmt[@numFmtId=$xf[numFmtId]]"));
								if (isset($tmpNumFmt["formatCode"])) {
									$numFmt = (string) $tmpNumFmt["formatCode"];
								} else if ((int)$xf["numFmtId"] < 165) {
									$numFmt = PHPExcel_Style_NumberFormat::builtInFormatCode((int)$xf["numFmtId"]);
								}
							}

							$cellStyle = (object) array(
								"numFmt" => $numFmt,
								"font" => $xmlStyles->fonts->font[intval($xf["fontId"])],
								"fill" => $xmlStyles->fills->fill[intval($xf["fillId"])],
								"border" => $xmlStyles->borders->border[intval($xf["borderId"])],
								"alignment" => $xf->alignment,
								"protection" => $xf->protection,
								"quotePrefix" => $quotePrefix,
							);
							$cellStyles[] = $cellStyle;

							// add style to cellStyleXf collection
							$objStyle = new PHPExcel_Style;
							self::_readStyle($objStyle, $cellStyle);
							$excel->addCellStyleXf($objStyle);
						}
					}

					$dxfs = array();
					if (!$this->_readDataOnly && $xmlStyles) {
						//	Conditional Styles
						if ($xmlStyles->dxfs) {
							foreach ($xmlStyles->dxfs->dxf as $dxf) {
								$style = new PHPExcel_Style(FALSE, TRUE);
								self::_readStyle($style, $dxf);
								$dxfs[] = $style;
							}
						}
						//	Cell Styles
						if ($xmlStyles->cellStyles) {
							foreach ($xmlStyles->cellStyles->cellStyle as $cellStyle) {
								if (intval($cellStyle['builtinId']) == 0) {
									if (isset($cellStyles[intval($cellStyle['xfId'])])) {
										// Set default style
										$style = new PHPExcel_Style;
										self::_readStyle($style, $cellStyles[intval($cellStyle['xfId'])]);

										// normal style, currently not using it for anything
									}
								}
							}
						}
					}

					$xmlWorkbook = simplexml_load_string($this->_getFromZipArchive($zip, "{$rel['Target']}"));  //~ http://schemas.openxmlformats.org/spreadsheetml/2006/main");

					// Set base date
					if ($xmlWorkbook->workbookPr) {
						PHPExcel_Shared_Date::setExcelCalendar(PHPExcel_Shared_Date::CALENDAR_WINDOWS_1900);
						if (isset($xmlWorkbook->workbookPr['date1904'])) {
							if (self::boolean((string) $xmlWorkbook->workbookPr['date1904'])) {
								PHPExcel_Shared_Date::setExcelCalendar(PHPExcel_Shared_Date::CALENDAR_MAC_1904);
							}
						}
					}

					$sheetId = 0; // keep track of new sheet id in final workbook
					$oldSheetId = -1; // keep track of old sheet id in final workbook
					$countSkippedSheets = 0; // keep track of number of skipped sheets
					$mapSheetId = array(); // mapping of sheet ids from old to new


					$charts = $chartDetails = array();

					if ($xmlWorkbook->sheets) {
						foreach ($xmlWorkbook->sheets->sheet as $eleSheet) {
							++$oldSheetId;

							// Check if sheet should be skipped
							if (isset($this->_loadSheetsOnly) && !in_array((string) $eleSheet["name"], $this->_loadSheetsOnly)) {
								++$countSkippedSheets;
								$mapSheetId[$oldSheetId] = null;
								continue;
							}

							// Map old sheet id in original workbook to new sheet id.
							// They will differ if loadSheetsOnly() is being used
							$mapSheetId[$oldSheetId] = $oldSheetId - $countSkippedSheets;

							// Load sheet
							$docSheet = $excel->createSheet();
							//	Use false for $updateFormulaCellReferences to prevent adjustment of worksheet
							//		references in formula cells... during the load, all formulae should be correct,
							//		and we're simply bringing the worksheet name in line with the formula, not the
							//		reverse
							$docSheet->setTitle((string) $eleSheet["name"],false);
							$fileWorksheet = $worksheets[(string) self::array_item($eleSheet->attributes("http://schemas.openxmlformats.org/officeDocument/2006/relationships"), "id")];
							$xmlSheet = simplexml_load_string($this->_getFromZipArchive($zip, "$dir/$fileWorksheet"));  //~ http://schemas.openxmlformats.org/spreadsheetml/2006/main");

							$sharedFormulas = array();

							if (isset($eleSheet["state"]) && (string) $eleSheet["state"] != '') {
								$docSheet->setSheetState( (string) $eleSheet["state"] );
							}

							if (isset($xmlSheet->sheetViews) && isset($xmlSheet->sheetViews->sheetView)) {
							    if (isset($xmlSheet->sheetViews->sheetView['zoomScale'])) {
								    $docSheet->getSheetView()->setZoomScale( intval($xmlSheet->sheetViews->sheetView['zoomScale']) );
								}

							    if (isset($xmlSheet->sheetViews->sheetView['zoomScaleNormal'])) {
								    $docSheet->getSheetView()->setZoomScaleNormal( intval($xmlSheet->sheetViews->sheetView['zoomScaleNormal']) );
								}

							    if (isset($xmlSheet->sheetViews->sheetView['view'])) {
								    $docSheet->getSheetView()->setView((string) $xmlSheet->sheetViews->sheetView['view']);
								}

								if (isset($xmlSheet->sheetViews->sheetView['showGridLines'])) {
									$docSheet->setShowGridLines(self::boolean((string)$xmlSheet->sheetViews->sheetView['showGridLines']));
								}

								if (isset($xmlSheet->sheetViews->sheetView['showRowColHeaders'])) {
									$docSheet->setShowRowColHeaders(self::boolean((string)$xmlSheet->sheetViews->sheetView['showRowColHeaders']));
								}

								if (isset($xmlSheet->sheetViews->sheetView['rightToLeft'])) {
									$docSheet->setRightToLeft(self::boolean((string)$xmlSheet->sheetViews->sheetView['rightToLeft']));
								}

								if (isset($xmlSheet->sheetViews->sheetView->pane)) {
								    if (isset($xmlSheet->sheetViews->sheetView->pane['topLeftCell'])) {
								        $docSheet->freezePane( (string)$xmlSheet->sheetViews->sheetView->pane['topLeftCell'] );
								    } else {
								        $xSplit = 0;
								        $ySplit = 0;

								        if (isset($xmlSheet->sheetViews->sheetView->pane['xSplit'])) {
								            $xSplit = 1 + intval($xmlSheet->sheetViews->sheetView->pane['xSplit']);
								        }

								    	if (isset($xmlSheet->sheetViews->sheetView->pane['ySplit'])) {
								            $ySplit = 1 + intval($xmlSheet->sheetViews->sheetView->pane['ySplit']);
								        }

								        $docSheet->freezePaneByColumnAndRow($xSplit, $ySplit);
								    }
								}

								if (isset($xmlSheet->sheetViews->sheetView->selection)) {
									if (isset($xmlSheet->sheetViews->sheetView->selection['sqref'])) {
										$sqref = (string)$xmlSheet->sheetViews->sheetView->selection['sqref'];
										$sqref = explode(' ', $sqref);
										$sqref = $sqref[0];
										$docSheet->setSelectedCells($sqref);
									}
								}

							}

							if (isset($xmlSheet->sheetPr) && isset($xmlSheet->sheetPr->tabColor)) {
								if (isset($xmlSheet->sheetPr->tabColor['rgb'])) {
									$docSheet->getTabColor()->setARGB( (string)$xmlSheet->sheetPr->tabColor['rgb'] );
								}
							}

							if (isset($xmlSheet->sheetPr) && isset($xmlSheet->sheetPr->outlinePr)) {
								if (isset($xmlSheet->sheetPr->outlinePr['summaryRight']) &&
									!self::boolean((string) $xmlSheet->sheetPr->outlinePr['summaryRight'])) {
									$docSheet->setShowSummaryRight(FALSE);
								} else {
									$docSheet->setShowSummaryRight(TRUE);
								}

								if (isset($xmlSheet->sheetPr->outlinePr['summaryBelow']) &&
									!self::boolean((string) $xmlSheet->sheetPr->outlinePr['summaryBelow'])) {
									$docSheet->setShowSummaryBelow(FALSE);
								} else {
									$docSheet->setShowSummaryBelow(TRUE);
								}
							}

							if (isset($xmlSheet->sheetPr) && isset($xmlSheet->sheetPr->pageSetUpPr)) {
								if (isset($xmlSheet->sheetPr->pageSetUpPr['fitToPage']) &&
									!self::boolean((string) $xmlSheet->sheetPr->pageSetUpPr['fitToPage'])) {
									$docSheet->getPageSetup()->setFitToPage(FALSE);
								} else {
									$docSheet->getPageSetup()->setFitToPage(TRUE);
								}
							}

							if (isset($xmlSheet->sheetFormatPr)) {
								if (isset($xmlSheet->sheetFormatPr['customHeight']) &&
									self::boolean((string) $xmlSheet->sheetFormatPr['customHeight']) &&
									isset($xmlSheet->sheetFormatPr['defaultRowHeight'])) {
									$docSheet->getDefaultRowDimension()->setRowHeight( (float)$xmlSheet->sheetFormatPr['defaultRowHeight'] );
								}
								if (isset($xmlSheet->sheetFormatPr['defaultColWidth'])) {
									$docSheet->getDefaultColumnDimension()->setWidth( (float)$xmlSheet->sheetFormatPr['defaultColWidth'] );
								}
								if (isset($xmlSheet->sheetFormatPr['zeroHeight']) &&
									((string)$xmlSheet->sheetFormatPr['zeroHeight'] == '1')) {
									$docSheet->getDefaultRowDimension()->setzeroHeight(true);
								}
							}

							if (isset($xmlSheet->cols) && !$this->_readDataOnly) {
								foreach ($xmlSheet->cols->col as $col) {
									for ($i = intval($col["min"]) - 1; $i < intval($col["max"]); ++$i) {
										if ($col["style"] && !$this->_readDataOnly) {
											$docSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setXfIndex(intval($col["style"]));
										}
										if (self::boolean($col["bestFit"])) {
											//$docSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setAutoSize(TRUE);
										}
										if (self::boolean($col["hidden"])) {
											$docSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setVisible(FALSE);
										}
										if (self::boolean($col["collapsed"])) {
											$docSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setCollapsed(TRUE);
										}
										if ($col["outlineLevel"] > 0) {
											$docSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setOutlineLevel(intval($col["outlineLevel"]));
										}
										$docSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))->setWidth(floatval($col["width"]));

										if (intval($col["max"]) == 16384) {
											break;
										}
									}
								}
							}

							if (isset($xmlSheet->printOptions) && !$this->_readDataOnly) {
								if (self::boolean((string) $xmlSheet->printOptions['gridLinesSet'])) {
									$docSheet->setShowGridlines(TRUE);
								}

								if (self::boolean((string) $xmlSheet->printOptions['gridLines'])) {
									$docSheet->setPrintGridlines(TRUE);
								}

								if (self::boolean((string) $xmlSheet->printOptions['horizontalCentered'])) {
									$docSheet->getPageSetup()->setHorizontalCentered(TRUE);
								}
								if (self::boolean((string) $xmlSheet->printOptions['verticalCentered'])) {
									$docSheet->getPageSetup()->setVerticalCentered(TRUE);
								}
							}

							if ($xmlSheet && $xmlSheet->sheetData && $xmlSheet->sheetData->row) {
								foreach ($xmlSheet->sheetData->row as $row) {
									if ($row["ht"] && !$this->_readDataOnly) {
										$docSheet->getRowDimension(intval($row["r"]))->setRowHeight(floatval($row["ht"]));
									}
									if (self::boolean($row["hidden"]) && !$this->_readDataOnly) {
										$docSheet->getRowDimension(intval($row["r"]))->setVisible(FALSE);
									}
									if (self::boolean($row["collapsed"])) {
										$docSheet->getRowDimension(intval($row["r"]))->setCollapsed(TRUE);
									}
									if ($row["outlineLevel"] > 0) {
										$docSheet->getRowDimension(intval($row["r"]))->setOutlineLevel(intval($row["outlineLevel"]));
									}
									if ($row["s"] && !$this->_readDataOnly) {
										$docSheet->getRowDimension(intval($row["r"]))->setXfIndex(intval($row["s"]));
									}

									foreach ($row->c as $c) {
										$r 					= (string) $c["r"];
										$cellDataType 		= (string) $c["t"];
										$value				= null;
										$calculatedValue 	= null;

										// Read cell?
										if ($this->getReadFilter() !== NULL) {
											$coordinates = PHPExcel_Cell::coordinateFromString($r);

											if (!$this->getReadFilter()->readCell($coordinates[0], $coordinates[1], $docSheet->getTitle())) {
												continue;
											}
										}

	//									echo '<b>Reading cell '.$coordinates[0].$coordinates[1].'</b><br />';
	//									print_r($c);
	//									echo '<br />';
	//									echo 'Cell Data Type is '.$cellDataType.': ';
	//
										// Read cell!
										switch ($cellDataType) {
											case "s":
	//											echo 'String<br />';
												if ((string)$c->v != '') {
													$value = $sharedStrings[intval($c->v)];

													if ($value instanceof PHPExcel_RichText) {
														$value = clone $value;
													}
												} else {
													$value = '';
												}

												break;
											case "b":
	//											echo 'Boolean<br />';
												if (!isset($c->f)) {
													$value = self::_castToBool($c);
												} else {
													// Formula
													$this->_castToFormula($c,$r,$cellDataType,$value,$calculatedValue,$sharedFormulas,'_castToBool');
													if (isset($c->f['t'])) {
														$att = array();
														$att = $c->f;
														$docSheet->getCell($r)->setFormulaAttributes($att);
													}
	//												echo '$calculatedValue = '.$calculatedValue.'<br />';
												}
												break;
											case "inlineStr":
	//											echo 'Inline String<br />';
												$value = $this->_parseRichText($c->is);

												break;
											case "e":
	//											echo 'Error<br />';
												if (!isset($c->f)) {
													$value = self::_castToError($c);
												} else {
													// Formula
													$this->_castToFormula($c,$r,$cellDataType,$value,$calculatedValue,$sharedFormulas,'_castToError');
	//												echo '$calculatedValue = '.$calculatedValue.'<br />';
												}

												break;

											default:
	//											echo 'Default<br />';
												if (!isset($c->f)) {
	//												echo 'Not a Formula<br />';
													$value = self::_castToString($c);
												} else {
	//												echo 'Treat as Formula<br />';
													// Formula
													$this->_castToFormula($c,$r,$cellDataType,$value,$calculatedValue,$sharedFormulas,'_castToString');
	//												echo '$calculatedValue = '.$calculatedValue.'<br />';
												}

												break;
										}
	//									echo 'Value is '.$value.'<br />';

										// Check for numeric values
										if (is_numeric($value) && $cellDataType != 's') {
											if ($value == (int)$value) $value = (int)$value;
											elseif ($value == (float)$value) $value = (float)$value;
											elseif ($value == (double)$value) $value = (double)$value;
										}

										// Rich text?
										if ($value instanceof PHPExcel_RichText && $this->_readDataOnly) {
											$value = $value->getPlainText();
										}

										$cell = $docSheet->getCell($r);
										// Assign value
										if ($cellDataType != '') {
											$cell->setValueExplicit($value, $cellDataType);
										} else {
											$cell->setValue($value);
										}
										if ($calculatedValue !== NULL) {
											$cell->setCalculatedValue($calculatedValue);
										}

										// Style information?
										if ($c["s"] && !$this->_readDataOnly) {
											// no style index means 0, it seems
											$cell->setXfIndex(isset($styles[intval($c["s"])]) ?
												intval($c["s"]) : 0);
										}
									}
								}
							}

							$conditionals = array();
							if (!$this->_readDataOnly && $xmlSheet && $xmlSheet->conditionalFormatting) {
								foreach ($xmlSheet->conditionalFormatting as $conditional) {
									foreach ($conditional->cfRule as $cfRule) {
										if (
											(
												(string)$cfRule["type"] == PHPExcel_Style_Conditional::CONDITION_NONE ||
												(string)$cfRule["type"] == PHPExcel_Style_Conditional::CONDITION_CELLIS ||
												(string)$cfRule["type"] == PHPExcel_Style_Conditional::CONDITION_CONTAINSTEXT ||
												(string)$cfRule["type"] == PHPExcel_Style_Conditional::CONDITION_EXPRESSION
											) && isset($dxfs[intval($cfRule["dxfId"])])
										) {
											$conditionals[(string) $conditional["sqref"]][intval($cfRule["priority"])] = $cfRule;
										}
									}
								}

								foreach ($conditionals as $ref => $cfRules) {
									ksort($cfRules);
									$conditionalStyles = array();
									foreach ($cfRules as $cfRule) {
										$objConditional = new PHPExcel_Style_Conditional();
										$objConditional->setConditionType((string)$cfRule["type"]);
										$objConditional->setOperatorType((string)$cfRule["operator"]);

										if ((string)$cfRule["text"] != '') {
											$objConditional->setText((string)$cfRule["text"]);
										}

										if (count($cfRule->formula) > 1) {
											foreach ($cfRule->formula as $formula) {
												$objConditional->addCondition((string)$formula);
											}
										} else {
											$objConditional->addCondition((string)$cfRule->formula);
										}
										$objConditional->setStyle(clone $dxfs[intval($cfRule["dxfId"])]);
										$conditionalStyles[] = $objConditional;
									}

									// Extract all cell references in $ref
									$aReferences = PHPExcel_Cell::extractAllCellReferencesInRange($ref);
									foreach ($aReferences as $reference) {
										$docSheet->getStyle($reference)->setConditionalStyles($conditionalStyles);
									}
								}
							}

							$aKeys = array("sheet", "objects", "scenarios", "formatCells", "formatColumns", "formatRows", "insertColumns", "insertRows", "insertHyperlinks", "deleteColumns", "deleteRows", "selectLockedCells", "sort", "autoFilter", "pivotTables", "selectUnlockedCells");
							if (!$this->_readDataOnly && $xmlSheet && $xmlSheet->sheetProtection) {
								foreach ($aKeys as $key) {
									$method = "set" . ucfirst($key);
									$docSheet->getProtection()->$method(self::boolean((string) $xmlSheet->sheetProtection[$key]));
								}
							}

							if (!$this->_readDataOnly && $xmlSheet && $xmlSheet->sheetProtection) {
								$docSheet->getProtection()->setPassword((string) $xmlSheet->sheetProtection["password"], TRUE);
								if ($xmlSheet->protectedRanges->protectedRange) {
									foreach ($xmlSheet->protectedRanges->protectedRange as $protectedRange) {
										$docSheet->protectCells((string) $protectedRange["sqref"], (string) $protectedRange["password"], true);
									}
								}
							}

							if ($xmlSheet && $xmlSheet->autoFilter && !$this->_readDataOnly) {
								$autoFilter = $docSheet->getAutoFilter();
								$autoFilter->setRange((string) $xmlSheet->autoFilter["ref"]);
								foreach ($xmlSheet->autoFilter->filterColumn as $filterColumn) {
									$column = $autoFilter->getColumnByOffset((integer) $filterColumn["colId"]);
									//	Check for standard filters
									if ($filterColumn->filters) {
										$column->setFilterType(PHPExcel_Worksheet_AutoFilter_Column::AUTOFILTER_FILTERTYPE_FILTER);
										$filters = $filterColumn->filters;
										if ((isset($filters["blank"])) && ($filters["blank"] == 1)) {
											$column->createRule()->setRule(
												NULL,	//	Operator is undefined, but always treated as EQUAL
												''
											)
											->setRuleType(PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_RULETYPE_FILTER);
										}
										//	Standard filters are always an OR join, so no join rule needs to be set
										//	Entries can be either filter elements
										foreach ($filters->filter as $filterRule) {
											$column->createRule()->setRule(
												NULL,	//	Operator is undefined, but always treated as EQUAL
												(string) $filterRule["val"]
											)
											->setRuleType(PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_RULETYPE_FILTER);
										}
										//	Or Date Group elements
										foreach ($filters->dateGroupItem as $dateGroupItem) {
											$column->createRule()->setRule(
												NULL,	//	Operator is undefined, but always treated as EQUAL
												array(
													'year' => (string) $dateGroupItem["year"],
													'month' => (string) $dateGroupItem["month"],
													'day' => (string) $dateGroupItem["day"],
													'hour' => (string) $dateGroupItem["hour"],
													'minute' => (string) $dateGroupItem["minute"],
													'second' => (string) $dateGroupItem["second"],
												),
												(string) $dateGroupItem["dateTimeGrouping"]
											)
											->setRuleType(PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_RULETYPE_DATEGROUP);
										}
									}
									//	Check for custom filters
									if ($filterColumn->customFilters) {
										$column->setFilterType(PHPExcel_Worksheet_AutoFilter_Column::AUTOFILTER_FILTERTYPE_CUSTOMFILTER);
										$customFilters = $filterColumn->customFilters;
										//	Custom filters can an AND or an OR join;
										//		and there should only ever be one or two entries
										if ((isset($customFilters["and"])) && ($customFilters["and"] == 1)) {
											$column->setJoin(PHPExcel_Worksheet_AutoFilter_Column::AUTOFILTER_COLUMN_JOIN_AND);
										}
										foreach ($customFilters->customFilter as $filterRule) {
											$column->createRule()->setRule(
												(string) $filterRule["operator"],
												(string) $filterRule["val"]
											)
											->setRuleType(PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_RULETYPE_CUSTOMFILTER);
										}
									}
									//	Check for dynamic filters
									if ($filterColumn->dynamicFilter) {
										$column->setFilterType(PHPExcel_Worksheet_AutoFilter_Column::AUTOFILTER_FILTERTYPE_DYNAMICFILTER);
										//	We should only ever have one dynamic filter
										foreach ($filterColumn->dynamicFilter as $filterRule) {
											$column->createRule()->setRule(
												NULL,	//	Operator is undefined, but always treated as EQUAL
												(string) $filterRule["val"],
												(string) $filterRule["type"]
											)
											->setRuleType(PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_RULETYPE_DYNAMICFILTER);
											if (isset($filterRule["val"])) {
												$column->setAttribute('val',(string) $filterRule["val"]);
											}
											if (isset($filterRule["maxVal"])) {
												$column->setAttribute('maxVal',(string) $filterRule["maxVal"]);
											}
										}
									}
									//	Check for dynamic filters
									if ($filterColumn->top10) {
										$column->setFilterType(PHPExcel_Worksheet_AutoFilter_Column::AUTOFILTER_FILTERTYPE_TOPTENFILTER);
										//	We should only ever have one top10 filter
										foreach ($filterColumn->top10 as $filterRule) {
											$column->createRule()->setRule(
												(((isset($filterRule["percent"])) && ($filterRule["percent"] == 1))
													? PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_COLUMN_RULE_TOPTEN_PERCENT
													: PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_COLUMN_RULE_TOPTEN_BY_VALUE
												),
												(string) $filterRule["val"],
												(((isset($filterRule["top"])) && ($filterRule["top"] == 1))
													? PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_COLUMN_RULE_TOPTEN_TOP
													: PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_COLUMN_RULE_TOPTEN_BOTTOM
												)
											)
											->setRuleType(PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_RULETYPE_TOPTENFILTER);
										}
									}
								}
							}

							if ($xmlSheet && $xmlSheet->mergeCells && $xmlSheet->mergeCells->mergeCell && !$this->_readDataOnly) {
								foreach ($xmlSheet->mergeCells->mergeCell as $mergeCell) {
									$mergeRef = (string) $mergeCell["ref"];
									if (strpos($mergeRef,':') !== FALSE) {
										$docSheet->mergeCells((string) $mergeCell["ref"]);
									}
								}
							}

							if ($xmlSheet && $xmlSheet->pageMargins && !$this->_readDataOnly) {
								$docPageMargins = $docSheet->getPageMargins();
								$docPageMargins->setLeft(floatval($xmlSheet->pageMargins["left"]));
								$docPageMargins->setRight(floatval($xmlSheet->pageMargins["right"]));
								$docPageMargins->setTop(floatval($xmlSheet->pageMargins["top"]));
								$docPageMargins->setBottom(floatval($xmlSheet->pageMargins["bottom"]));
								$docPageMargins->setHeader(floatval($xmlSheet->pageMargins["header"]));
								$docPageMargins->setFooter(floatval($xmlSheet->pageMargins["footer"]));
							}

							if ($xmlSheet && $xmlSheet->pageSetup && !$this->_readDataOnly) {
								$docPageSetup = $docSheet->getPageSetup();

								if (isset($xmlSheet->pageSetup["orientation"])) {
									$docPageSetup->setOrientation((string) $xmlSheet->pageSetup["orientation"]);
								}
								if (isset($xmlSheet->pageSetup["paperSize"])) {
									$docPageSetup->setPaperSize(intval($xmlSheet->pageSetup["paperSize"]));
								}
								if (isset($xmlSheet->pageSetup["scale"])) {
									$docPageSetup->setScale(intval($xmlSheet->pageSetup["scale"]), FALSE);
								}
								if (isset($xmlSheet->pageSetup["fitToHeight"]) && intval($xmlSheet->pageSetup["fitToHeight"]) >= 0) {
									$docPageSetup->setFitToHeight(intval($xmlSheet->pageSetup["fitToHeight"]), FALSE);
								}
								if (isset($xmlSheet->pageSetup["fitToWidth"]) && intval($xmlSheet->pageSetup["fitToWidth"]) >= 0) {
									$docPageSetup->setFitToWidth(intval($xmlSheet->pageSetup["fitToWidth"]), FALSE);
								}
								if (isset($xmlSheet->pageSetup["firstPageNumber"]) && isset($xmlSheet->pageSetup["useFirstPageNumber"]) &&
									self::boolean((string) $xmlSheet->pageSetup["useFirstPageNumber"])) {
									$docPageSetup->setFirstPageNumber(intval($xmlSheet->pageSetup["firstPageNumber"]));
								}
							}

							if ($xmlSheet && $xmlSheet->headerFooter && !$this->_readDataOnly) {
								$docHeaderFooter = $docSheet->getHeaderFooter();

								if (isset($xmlSheet->headerFooter["differentOddEven"]) &&
									self::boolean((string)$xmlSheet->headerFooter["differentOddEven"])) {
									$docHeaderFooter->setDifferentOddEven(TRUE);
								} else {
									$docHeaderFooter->setDifferentOddEven(FALSE);
								}
								if (isset($xmlSheet->headerFooter["differentFirst"]) &&
									self::boolean((string)$xmlSheet->headerFooter["differentFirst"])) {
									$docHeaderFooter->setDifferentFirst(TRUE);
								} else {
									$docHeaderFooter->setDifferentFirst(FALSE);
								}
								if (isset($xmlSheet->headerFooter["scaleWithDoc"]) &&
									!self::boolean((string)$xmlSheet->headerFooter["scaleWithDoc"])) {
									$docHeaderFooter->setScaleWithDocument(FALSE);
								} else {
									$docHeaderFooter->setScaleWithDocument(TRUE);
								}
								if (isset($xmlSheet->headerFooter["alignWithMargins"]) &&
									!self::boolean((string)$xmlSheet->headerFooter["alignWithMargins"])) {
									$docHeaderFooter->setAlignWithMargins(FALSE);
								} else {
									$docHeaderFooter->setAlignWithMargins(TRUE);
								}

								$docHeaderFooter->setOddHeader((string) $xmlSheet->headerFooter->oddHeader);
								$docHeaderFooter->setOddFooter((string) $xmlSheet->headerFooter->oddFooter);
								$docHeaderFooter->setEvenHeader((string) $xmlSheet->headerFooter->evenHeader);
								$docHeaderFooter->setEvenFooter((string) $xmlSheet->headerFooter->evenFooter);
								$docHeaderFooter->setFirstHeader((string) $xmlSheet->headerFooter->firstHeader);
								$docHeaderFooter->setFirstFooter((string) $xmlSheet->headerFooter->firstFooter);
							}

							if ($xmlSheet && $xmlSheet->rowBreaks && $xmlSheet->rowBreaks->brk && !$this->_readDataOnly) {
								foreach ($xmlSheet->rowBreaks->brk as $brk) {
									if ($brk["man"]) {
										$docSheet->setBreak("A$brk[id]", PHPExcel_Worksheet::BREAK_ROW);
									}
								}
							}
							if ($xmlSheet && $xmlSheet->colBreaks && $xmlSheet->colBreaks->brk && !$this->_readDataOnly) {
								foreach ($xmlSheet->colBreaks->brk as $brk) {
									if ($brk["man"]) {
										$docSheet->setBreak(PHPExcel_Cell::stringFromColumnIndex((string) $brk["id"]) . "1", PHPExcel_Worksheet::BREAK_COLUMN);
									}
								}
							}

							if ($xmlSheet && $xmlSheet->dataValidations && !$this->_readDataOnly) {
								foreach ($xmlSheet->dataValidations->dataValidation as $dataValidation) {
								    // Uppercase coordinate
							    	$range = strtoupper($dataValidation["sqref"]);
									$rangeSet = explode(' ',$range);
									foreach($rangeSet as $range) {
										$stRange = $docSheet->shrinkRangeToFit($range);

										// Extract all cell references in $range
										$aReferences = PHPExcel_Cell::extractAllCellReferencesInRange($stRange);
										foreach ($aReferences as $reference) {
											// Create validation
											$docValidation = $docSheet->getCell($reference)->getDataValidation();
											$docValidation->setType((string) $dataValidation["type"]);
											$docValidation->setErrorStyle((string) $dataValidation["errorStyle"]);
											$docValidation->setOperator((string) $dataValidation["operator"]);
											$docValidation->setAllowBlank($dataValidation["allowBlank"] != 0);
											$docValidation->setShowDropDown($dataValidation["showDropDown"] == 0);
											$docValidation->setShowInputMessage($dataValidation["showInputMessage"] != 0);
											$docValidation->setShowErrorMessage($dataValidation["showErrorMessage"] != 0);
											$docValidation->setErrorTitle((string) $dataValidation["errorTitle"]);
											$docValidation->setError((string) $dataValidation["error"]);
											$docValidation->setPromptTitle((string) $dataValidation["promptTitle"]);
											$docValidation->setPrompt((string) $dataValidation["prompt"]);
											$docValidation->setFormula1((string) $dataValidation->formula1);
											$docValidation->setFormula2((string) $dataValidation->formula2);
										}
									}
								}
							}

							// Add hyperlinks
							$hyperlinks = array();
							if (!$this->_readDataOnly) {
								// Locate hyperlink relations
								if ($zip->locateName(dirname("$dir/$fileWorksheet") . "/_rels/" . basename($fileWorksheet) . ".rels")) {
									$relsWorksheet = simplexml_load_string($this->_getFromZipArchive($zip,  dirname("$dir/$fileWorksheet") . "/_rels/" . basename($fileWorksheet) . ".rels") ); //~ http://schemas.openxmlformats.org/package/2006/relationships");
									foreach ($relsWorksheet->Relationship as $ele) {
										if ($ele["Type"] == "http://schemas.openxmlformats.org/officeDocument/2006/relationships/hyperlink") {
											$hyperlinks[(string)$ele["Id"]] = (string)$ele["Target"];
										}
									}
								}

								// Loop through hyperlinks
								if ($xmlSheet && $xmlSheet->hyperlinks) {
									foreach ($xmlSheet->hyperlinks->hyperlink as $hyperlink) {
										// Link url
										$linkRel = $hyperlink->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships');

										foreach (PHPExcel_Cell::extractAllCellReferencesInRange($hyperlink['ref']) as $cellReference) {
											$cell = $docSheet->getCell( $cellReference );
											if (isset($linkRel['id'])) {
												$hyperlinkUrl = $hyperlinks[ (string)$linkRel['id'] ];
												if (isset($hyperlink['location'])) {
													$hyperlinkUrl .= '#' . (string) $hyperlink['location'];
												}
												$cell->getHyperlink()->setUrl($hyperlinkUrl);
											} elseif (isset($hyperlink['location'])) {
												$cell->getHyperlink()->setUrl( 'sheet://' . (string)$hyperlink['location'] );
											}

											// Tooltip
											if (isset($hyperlink['tooltip'])) {
												$cell->getHyperlink()->setTooltip( (string)$hyperlink['tooltip'] );
											}
										}
									}
								}
							}

							// Add comments
							$comments = array();
							$vmlComments = array();
							if (!$this->_readDataOnly) {
								// Locate comment relations
								if ($zip->locateName(dirname("$dir/$fileWorksheet") . "/_rels/" . basename($fileWorksheet) . ".rels")) {
									$relsWorksheet = simplexml_load_string($this->_getFromZipArchive($zip,  dirname("$dir/$fileWorksheet") . "/_rels/" . basename($fileWorksheet) . ".rels") ); //~ http://schemas.openxmlformats.org/package/2006/relationships");
									foreach ($relsWorksheet->Relationship as $ele) {
									    if ($ele["Type"] == "http://schemas.openxmlformats.org/officeDocument/2006/relationships/comments") {
											$comments[(string)$ele["Id"]] = (string)$ele["Target"];
										}
									    if ($ele["Type"] == "http://schemas.openxmlformats.org/officeDocument/2006/relationships/vmlDrawing") {
											$vmlComments[(string)$ele["Id"]] = (string)$ele["Target"];
										}
									}
								}

								// Loop through comments
								foreach ($comments as $relName => $relPath) {
									// Load comments file
									$relPath = PHPExcel_Shared_File::realpath(dirname("$dir/$fileWorksheet") . "/" . $relPath);
									$commentsFile = simplexml_load_string($this->_getFromZipArchive($zip, $relPath) );

									// Utility variables
									$authors = array();

									// Loop through authors
									foreach ($commentsFile->authors->author as $author) {
										$authors[] = (string)$author;
									}

									// Loop through contents
									foreach ($commentsFile->commentList->comment as $comment) {
										$docSheet->getComment( (string)$comment['ref'] )->setAuthor( $authors[(string)$comment['authorId']] );
										$docSheet->getComment( (string)$comment['ref'] )->setText( $this->_parseRichText($comment->text) );
									}
								}

								// Loop through VML comments
							    foreach ($vmlComments as $relName => $relPath) {
									// Load VML comments file
									$relPath = PHPExcel_Shared_File::realpath(dirname("$dir/$fileWorksheet") . "/" . $relPath);
									$vmlCommentsFile = simplexml_load_string( $this->_getFromZipArchive($zip, $relPath) );
									$vmlCommentsFile->registerXPathNamespace('v', 'urn:schemas-microsoft-com:vml');

									$shapes = $vmlCommentsFile->xpath('//v:shape');
									foreach ($shapes as $shape) {
										$shape->registerXPathNamespace('v', 'urn:schemas-microsoft-com:vml');

										if (isset($shape['style'])) {
	    									$style        = (string)$shape['style'];
	    									$fillColor    = strtoupper( substr( (string)$shape['fillcolor'], 1 ) );
	    									$column       = null;
	    									$row          = null;

	    									$clientData   = $shape->xpath('.//x:ClientData');
	    									if (is_array($clientData) && !empty($clientData)) {
	        									$clientData   = $clientData[0];

	        									if ( isset($clientData['ObjectType']) && (string)$clientData['ObjectType'] == 'Note' ) {
	        									    $temp = $clientData->xpath('.//x:Row');
	        									    if (is_array($temp)) $row = $temp[0];

	        									    $temp = $clientData->xpath('.//x:Column');
	        									    if (is_array($temp)) $column = $temp[0];
	        									}
	    									}

	    									if (($column !== NULL) && ($row !== NULL)) {
	    									    // Set comment properties
	    									    $comment = $docSheet->getCommentByColumnAndRow((string) $column, $row + 1);
	    									    $comment->getFillColor()->setRGB( $fillColor );

	    									    // Parse style
	    									    $styleArray = explode(';', str_replace(' ', '', $style));
	    									    foreach ($styleArray as $stylePair) {
	    									        $stylePair = explode(':', $stylePair);

	    									        if ($stylePair[0] == 'margin-left')     $comment->setMarginLeft($stylePair[1]);
	    									        if ($stylePair[0] == 'margin-top')      $comment->setMarginTop($stylePair[1]);
	    									        if ($stylePair[0] == 'width')           $comment->setWidth($stylePair[1]);
	    									        if ($stylePair[0] == 'height')          $comment->setHeight($stylePair[1]);
	    									        if ($stylePair[0] == 'visibility')      $comment->setVisible( $stylePair[1] == 'visible' );

	    									    }
	    									}
										}
									}
								}

								// Header/footer images
								if ($xmlSheet && $xmlSheet->legacyDrawingHF && !$this->_readDataOnly) {
									if ($zip->locateName(dirname("$dir/$fileWorksheet") . "/_rels/" . basename($fileWorksheet) . ".rels")) {
										$relsWorksheet = simplexml_load_string($this->_getFromZipArchive($zip,  dirname("$dir/$fileWorksheet") . "/_rels/" . basename($fileWorksheet) . ".rels") ); //~ http://schemas.openxmlformats.org/package/2006/relationships");
										$vmlRelationship = '';

										foreach ($relsWorksheet->Relationship as $ele) {
											if ($ele["Type"] == "http://schemas.openxmlformats.org/officeDocument/2006/relationships/vmlDrawing") {
												$vmlRelationship = self::dir_add("$dir/$fileWorksheet", $ele["Target"]);
											}
										}

										if ($vmlRelationship != '') {
											// Fetch linked images
											$relsVML = simplexml_load_string($this->_getFromZipArchive($zip,  dirname($vmlRelationship) . '/_rels/' . basename($vmlRelationship) . '.rels' )); //~ http://schemas.openxmlformats.org/package/2006/relationships");
											$drawings = array();
											foreach ($relsVML->Relationship as $ele) {
												if ($ele["Type"] == "http://schemas.openxmlformats.org/officeDocument/2006/relationships/image") {
													$drawings[(string) $ele["Id"]] = self::dir_add($vmlRelationship, $ele["Target"]);
												}
											}

											// Fetch VML document
											$vmlDrawing = simplexml_load_string($this->_getFromZipArchive($zip, $vmlRelationship));
											$vmlDrawing->registerXPathNamespace('v', 'urn:schemas-microsoft-com:vml');

											$hfImages = array();

											$shapes = $vmlDrawing->xpath('//v:shape');
											foreach ($shapes as $shape) {
												$shape->registerXPathNamespace('v', 'urn:schemas-microsoft-com:vml');
												$imageData = $shape->xpath('//v:imagedata');
												$imageData = $imageData[0];

												$imageData = $imageData->attributes('urn:schemas-microsoft-com:office:office');
												$style = self::toCSSArray( (string)$shape['style'] );

												$hfImages[ (string)$shape['id'] ] = new PHPExcel_Worksheet_HeaderFooterDrawing();
												if (isset($imageData['title'])) {
													$hfImages[ (string)$shape['id'] ]->setName( (string)$imageData['title'] );
												}

												$hfImages[ (string)$shape['id'] ]->setPath("zip://".PHPExcel_Shared_File::realpath($pFilename)."#" . $drawings[(string)$imageData['relid']], false);
												$hfImages[ (string)$shape['id'] ]->setResizeProportional(false);
												$hfImages[ (string)$shape['id'] ]->setWidth($style['width']);
												$hfImages[ (string)$shape['id'] ]->setHeight($style['height']);
												$hfImages[ (string)$shape['id'] ]->setOffsetX($style['margin-left']);
												$hfImages[ (string)$shape['id'] ]->setOffsetY($style['margin-top']);
												$hfImages[ (string)$shape['id'] ]->setResizeProportional(true);
											}

											$docSheet->getHeaderFooter()->setImages($hfImages);
										}
									}
								}

							}

// TODO: Autoshapes from twoCellAnchors!
							if ($zip->locateName(dirname("$dir/$fileWorksheet") . "/_rels/" . basename($fileWorksheet) . ".rels")) {
								$relsWorksheet = simplexml_load_string($this->_getFromZipArchive($zip,  dirname("$dir/$fileWorksheet") . "/_rels/" . basename($fileWorksheet) . ".rels") ); //~ http://schemas.openxmlformats.org/package/2006/relationships");
								$drawings = array();
								foreach ($relsWorksheet->Relationship as $ele) {
									if ($ele["Type"] == "http://schemas.openxmlformats.org/officeDocument/2006/relationships/drawing") {
										$drawings[(string) $ele["Id"]] = self::dir_add("$dir/$fileWorksheet", $ele["Target"]);
									}
								}
								if ($xmlSheet->drawing && !$this->_readDataOnly) {
									foreach ($xmlSheet->drawing as $drawing) {
										$fileDrawing = $drawings[(string) self::array_item($drawing->attributes("http://schemas.openxmlformats.org/officeDocument/2006/relationships"), "id")];
										$relsDrawing = simplexml_load_string($this->_getFromZipArchive($zip,  dirname($fileDrawing) . "/_rels/" . basename($fileDrawing) . ".rels") ); //~ http://schemas.openxmlformats.org/package/2006/relationships");
										$images = array();

										if ($relsDrawing && $relsDrawing->Relationship) {
											foreach ($relsDrawing->Relationship as $ele) {
												if ($ele["Type"] == "http://schemas.openxmlformats.org/officeDocument/2006/relationships/image") {
													$images[(string) $ele["Id"]] = self::dir_add($fileDrawing, $ele["Target"]);
												} elseif ($ele["Type"] == "http://schemas.openxmlformats.org/officeDocument/2006/relationships/chart") {
													if ($this->_includeCharts) {
														$charts[self::dir_add($fileDrawing, $ele["Target"])] = array('id'		=> (string) $ele["Id"],
																													 'sheet'	=> $docSheet->getTitle()
																													);
													}
												}
											}
										}
										$xmlDrawing = simplexml_load_string($this->_getFromZipArchive($zip, $fileDrawing))->children("http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing");

										if ($xmlDrawing->oneCellAnchor) {
											foreach ($xmlDrawing->oneCellAnchor as $oneCellAnchor) {
												if ($oneCellAnchor->pic->blipFill) {
													$blip = $oneCellAnchor->pic->blipFill->children("http://schemas.openxmlformats.org/drawingml/2006/main")->blip;
													$xfrm = $oneCellAnchor->pic->spPr->children("http://schemas.openxmlformats.org/drawingml/2006/main")->xfrm;
													$outerShdw = $oneCellAnchor->pic->spPr->children("http://schemas.openxmlformats.org/drawingml/2006/main")->effectLst->outerShdw;
													$objDrawing = new PHPExcel_Worksheet_Drawing;
													$objDrawing->setName((string) self::array_item($oneCellAnchor->pic->nvPicPr->cNvPr->attributes(), "name"));
													$objDrawing->setDescription((string) self::array_item($oneCellAnchor->pic->nvPicPr->cNvPr->attributes(), "descr"));
													$objDrawing->setPath("zip://".PHPExcel_Shared_File::realpath($pFilename)."#" . $images[(string) self::array_item($blip->attributes("http://schemas.openxmlformats.org/officeDocument/2006/relationships"), "embed")], false);
													$objDrawing->setCoordinates(PHPExcel_Cell::stringFromColumnIndex((string) $oneCellAnchor->from->col) . ($oneCellAnchor->from->row + 1));
													$objDrawing->setOffsetX(PHPExcel_Shared_Drawing::EMUToPixels($oneCellAnchor->from->colOff));
													$objDrawing->setOffsetY(PHPExcel_Shared_Drawing::EMUToPixels($oneCellAnchor->from->rowOff));
													$objDrawing->setResizeProportional(false);
													$objDrawing->setWidth(PHPExcel_Shared_Drawing::EMUToPixels(self::array_item($oneCellAnchor->ext->attributes(), "cx")));
													$objDrawing->setHeight(PHPExcel_Shared_Drawing::EMUToPixels(self::array_item($oneCellAnchor->ext->attributes(), "cy")));
													if ($xfrm) {
														$objDrawing->setRotation(PHPExcel_Shared_Drawing::angleToDegrees(self::array_item($xfrm->attributes(), "rot")));
													}
													if ($outerShdw) {
														$shadow = $objDrawing->getShadow();
														$shadow->setVisible(true);
														$shadow->setBlurRadius(PHPExcel_Shared_Drawing::EMUTopixels(self::array_item($outerShdw->attributes(), "blurRad")));
														$shadow->setDistance(PHPExcel_Shared_Drawing::EMUTopixels(self::array_item($outerShdw->attributes(), "dist")));
														$shadow->setDirection(PHPExcel_Shared_Drawing::angleToDegrees(self::array_item($outerShdw->attributes(), "dir")));
														$shadow->setAlignment((string) self::array_item($outerShdw->attributes(), "algn"));
														$shadow->getColor()->setRGB(self::array_item($outerShdw->srgbClr->attributes(), "val"));
														$shadow->setAlpha(self::array_item($outerShdw->srgbClr->alpha->attributes(), "val") / 1000);
													}
													$objDrawing->setWorksheet($docSheet);
												} else {
													//	? Can charts be positioned with a oneCellAnchor ?
													$coordinates	= PHPExcel_Cell::stringFromColumnIndex((string) $oneCellAnchor->from->col) . ($oneCellAnchor->from->row + 1);
													$offsetX		= PHPExcel_Shared_Drawing::EMUToPixels($oneCellAnchor->from->colOff);
													$offsetY		= PHPExcel_Shared_Drawing::EMUToPixels($oneCellAnchor->from->rowOff);
													$width			= PHPExcel_Shared_Drawing::EMUToPixels(self::array_item($oneCellAnchor->ext->attributes(), "cx"));
													$height			= PHPExcel_Shared_Drawing::EMUToPixels(self::array_item($oneCellAnchor->ext->attributes(), "cy"));
												}
											}
										}
										if ($xmlDrawing->twoCellAnchor) {
											foreach ($xmlDrawing->twoCellAnchor as $twoCellAnchor) {
												if ($twoCellAnchor->pic->blipFill) {
													$blip = $twoCellAnchor->pic->blipFill->children("http://schemas.openxmlformats.org/drawingml/2006/main")->blip;
													$xfrm = $twoCellAnchor->pic->spPr->children("http://schemas.openxmlformats.org/drawingml/2006/main")->xfrm;
													$outerShdw = $twoCellAnchor->pic->spPr->children("http://schemas.openxmlformats.org/drawingml/2006/main")->effectLst->outerShdw;
													$objDrawing = new PHPExcel_Worksheet_Drawing;
													$objDrawing->setName((string) self::array_item($twoCellAnchor->pic->nvPicPr->cNvPr->attributes(), "name"));
													$objDrawing->setDescription((string) self::array_item($twoCellAnchor->pic->nvPicPr->cNvPr->attributes(), "descr"));
													$objDrawing->setPath("zip://".PHPExcel_Shared_File::realpath($pFilename)."#" . $images[(string) self::array_item($blip->attributes("http://schemas.openxmlformats.org/officeDocument/2006/relationships"), "embed")], false);
													$objDrawing->setCoordinates(PHPExcel_Cell::stringFromColumnIndex((string) $twoCellAnchor->from->col) . ($twoCellAnchor->from->row + 1));
													$objDrawing->setOffsetX(PHPExcel_Shared_Drawing::EMUToPixels($twoCellAnchor->from->colOff));
													$objDrawing->setOffsetY(PHPExcel_Shared_Drawing::EMUToPixels($twoCellAnchor->from->rowOff));
													$objDrawing->setResizeProportional(false);

													$objDrawing->setWidth(PHPExcel_Shared_Drawing::EMUToPixels(self::array_item($xfrm->ext->attributes(), "cx")));
													$objDrawing->setHeight(PHPExcel_Shared_Drawing::EMUToPixels(self::array_item($xfrm->ext->attributes(), "cy")));

													if ($xfrm) {
														$objDrawing->setRotation(PHPExcel_Shared_Drawing::angleToDegrees(self::array_item($xfrm->attributes(), "rot")));
													}
													if ($outerShdw) {
														$shadow = $objDrawing->getShadow();
														$shadow->setVisible(true);
														$shadow->setBlurRadius(PHPExcel_Shared_Drawing::EMUTopixels(self::array_item($outerShdw->attributes(), "blurRad")));
														$shadow->setDistance(PHPExcel_Shared_Drawing::EMUTopixels(self::array_item($outerShdw->attributes(), "dist")));
														$shadow->setDirection(PHPExcel_Shared_Drawing::angleToDegrees(self::array_item($outerShdw->attributes(), "dir")));
														$shadow->setAlignment((string) self::array_item($outerShdw->attributes(), "algn"));
														$shadow->getColor()->setRGB(self::array_item($outerShdw->srgbClr->attributes(), "val"));
														$shadow->setAlpha(self::array_item($outerShdw->srgbClr->alpha->attributes(), "val") / 1000);
													}
													$objDrawing->setWorksheet($docSheet);
												} elseif(($this->_includeCharts) && ($twoCellAnchor->graphicFrame)) {
													$fromCoordinate	= PHPExcel_Cell::stringFromColumnIndex((string) $twoCellAnchor->from->col) . ($twoCellAnchor->from->row + 1);
													$fromOffsetX	= PHPExcel_Shared_Drawing::EMUToPixels($twoCellAnchor->from->colOff);
													$fromOffsetY	= PHPExcel_Shared_Drawing::EMUToPixels($twoCellAnchor->from->rowOff);
													$toCoordinate	= PHPExcel_Cell::stringFromColumnIndex((string) $twoCellAnchor->to->col) . ($twoCellAnchor->to->row + 1);
													$toOffsetX		= PHPExcel_Shared_Drawing::EMUToPixels($twoCellAnchor->to->colOff);
													$toOffsetY		= PHPExcel_Shared_Drawing::EMUToPixels($twoCellAnchor->to->rowOff);
													$graphic		= $twoCellAnchor->graphicFrame->children("http://schemas.openxmlformats.org/drawingml/2006/main")->graphic;
													$chartRef		= $graphic->graphicData->children("http://schemas.openxmlformats.org/drawingml/2006/chart")->chart;
													$thisChart		= (string) $chartRef->attributes("http://schemas.openxmlformats.org/officeDocument/2006/relationships");

													$chartDetails[$docSheet->getTitle().'!'.$thisChart] =
															array(	'fromCoordinate'	=> $fromCoordinate,
																	'fromOffsetX'		=> $fromOffsetX,
																	'fromOffsetY'		=> $fromOffsetY,
																	'toCoordinate'		=> $toCoordinate,
																	'toOffsetX'			=> $toOffsetX,
																	'toOffsetY'			=> $toOffsetY,
																	'worksheetTitle'	=> $docSheet->getTitle()
																 );
												}
											}
										}

									}
								}
							}

							// Loop through definedNames
							if ($xmlWorkbook->definedNames) {
								foreach ($xmlWorkbook->definedNames->definedName as $definedName) {
									// Extract range
									$extractedRange = (string)$definedName;
									$extractedRange = preg_replace('/\'(\w+)\'\!/', '', $extractedRange);
									if (($spos = strpos($extractedRange,'!')) !== false) {
										$extractedRange = substr($extractedRange,0,$spos).str_replace('$', '', substr($extractedRange,$spos));
									} else {
										$extractedRange = str_replace('$', '', $extractedRange);
									}

									// Valid range?
									if (stripos((string)$definedName, '#REF!') !== FALSE || $extractedRange == '') {
										continue;
									}

									// Some definedNames are only applicable if we are on the same sheet...
									if ((string)$definedName['localSheetId'] != '' && (string)$definedName['localSheetId'] == $sheetId) {
										// Switch on type
										switch ((string)$definedName['name']) {

											case '_xlnm._FilterDatabase':
												if ((string)$definedName['hidden'] !== '1') {
													$docSheet->getAutoFilter()->setRange($extractedRange);
												}
												break;

											case '_xlnm.Print_Titles':
												// Split $extractedRange
												$extractedRange = explode(',', $extractedRange);

												// Set print titles
												foreach ($extractedRange as $range) {
													$matches = array();
													$range = str_replace('$', '', $range);

													// check for repeating columns, e g. 'A:A' or 'A:D'
													if (preg_match('/!?([A-Z]+)\:([A-Z]+)$/', $range, $matches)) {
														$docSheet->getPageSetup()->setColumnsToRepeatAtLeft(array($matches[1], $matches[2]));
													}
													// check for repeating rows, e.g. '1:1' or '1:5'
													elseif (preg_match('/!?(\d+)\:(\d+)$/', $range, $matches)) {
														$docSheet->getPageSetup()->setRowsToRepeatAtTop(array($matches[1], $matches[2]));
													}
												}
												break;

											case '_xlnm.Print_Area':
												$rangeSets = explode(',', $extractedRange);		// FIXME: what if sheetname contains comma?
												$newRangeSets = array();
												foreach($rangeSets as $rangeSet) {
													$range = explode('!', $rangeSet);	// FIXME: what if sheetname contains exclamation mark?
													$rangeSet = isset($range[1]) ? $range[1] : $range[0];
													if (strpos($rangeSet, ':') === FALSE) {
														$rangeSet = $rangeSet . ':' . $rangeSet;
													}
													$newRangeSets[] = str_replace('$', '', $rangeSet);
												}
												$docSheet->getPageSetup()->setPrintArea(implode(',',$newRangeSets));
												break;

											default:
												break;
										}
									}
								}
							}

							// Next sheet id
							++$sheetId;
						}

						// Loop through definedNames
						if ($xmlWorkbook->definedNames) {
							foreach ($xmlWorkbook->definedNames->definedName as $definedName) {
								// Extract range
								$extractedRange = (string)$definedName;
								$extractedRange = preg_replace('/\'(\w+)\'\!/', '', $extractedRange);
								if (($spos = strpos($extractedRange,'!')) !== false) {
									$extractedRange = substr($extractedRange,0,$spos).str_replace('$', '', substr($extractedRange,$spos));
								} else {
									$extractedRange = str_replace('$', '', $extractedRange);
								}

								// Valid range?
								if (stripos((string)$definedName, '#REF!') !== false || $extractedRange == '') {
									continue;
								}

								// Some definedNames are only applicable if we are on the same sheet...
								if ((string)$definedName['localSheetId'] != '') {
									// Local defined name
									// Switch on type
									switch ((string)$definedName['name']) {

										case '_xlnm._FilterDatabase':
										case '_xlnm.Print_Titles':
										case '_xlnm.Print_Area':
											break;

										default:
											if ($mapSheetId[(integer) $definedName['localSheetId']] !== null) {
												$range = explode('!', (string)$definedName);
												if (count($range) == 2) {
													$range[0] = str_replace("''", "'", $range[0]);
													$range[0] = str_replace("'", "", $range[0]);
													if ($worksheet = $docSheet->getParent()->getSheetByName($range[0])) {
														$extractedRange = str_replace('$', '', $range[1]);
														$scope = $docSheet->getParent()->getSheet($mapSheetId[(integer) $definedName['localSheetId']]);
														$excel->addNamedRange( new PHPExcel_NamedRange((string)$definedName['name'], $worksheet, $extractedRange, true, $scope) );
													}
												}
											}
											break;
									}
								} else if (!isset($definedName['localSheetId'])) {
									// "Global" definedNames
									$locatedSheet = null;
									$extractedSheetName = '';
									if (strpos( (string)$definedName, '!' ) !== false) {
										// Extract sheet name
										$extractedSheetName = PHPExcel_Worksheet::extractSheetTitle( (string)$definedName, true );
										$extractedSheetName = $extractedSheetName[0];

										// Locate sheet
										$locatedSheet = $excel->getSheetByName($extractedSheetName);

										// Modify range
										$range = explode('!', $extractedRange);
										$extractedRange = isset($range[1]) ? $range[1] : $range[0];
									}

									if ($locatedSheet !== NULL) {
										$excel->addNamedRange( new PHPExcel_NamedRange((string)$definedName['name'], $locatedSheet, $extractedRange, false) );
									}
								}
							}
						}
					}

					if ((!$this->_readDataOnly) || (!empty($this->_loadSheetsOnly))) {
						// active sheet index
						$activeTab = intval($xmlWorkbook->bookViews->workbookView["activeTab"]); // refers to old sheet index

						// keep active sheet index if sheet is still loaded, else first sheet is set as the active
						if (isset($mapSheetId[$activeTab]) && $mapSheetId[$activeTab] !== null) {
							$excel->setActiveSheetIndex($mapSheetId[$activeTab]);
						} else {
							if ($excel->getSheetCount() == 0) {
								$excel->createSheet();
							}
							$excel->setActiveSheetIndex(0);
						}
					}
				break;
			}

		}


		if (!$this->_readDataOnly) {
			$contentTypes = simplexml_load_string($this->_getFromZipArchive($zip, "[Content_Types].xml"));
			foreach ($contentTypes->Override as $contentType) {
				switch ($contentType["ContentType"]) {
					case "application/vnd.openxmlformats-officedocument.drawingml.chart+xml":
						if ($this->_includeCharts) {
							$chartEntryRef = ltrim($contentType['PartName'],'/');
							$chartElements = simplexml_load_string($this->_getFromZipArchive($zip, $chartEntryRef));
							$objChart = PHPExcel_Reader_Excel2007_Chart::readChart($chartElements,basename($chartEntryRef,'.xml'));

//							echo 'Chart ',$chartEntryRef,'<br />';
//							var_dump($charts[$chartEntryRef]);
//
							if (isset($charts[$chartEntryRef])) {
								$chartPositionRef = $charts[$chartEntryRef]['sheet'].'!'.$charts[$chartEntryRef]['id'];
//								echo 'Position Ref ',$chartPositionRef,'<br />';
								if (isset($chartDetails[$chartPositionRef])) {
//									var_dump($chartDetails[$chartPositionRef]);

									$excel->getSheetByName($charts[$chartEntryRef]['sheet'])->addChart($objChart);
									$objChart->setWorksheet($excel->getSheetByName($charts[$chartEntryRef]['sheet']));
									$objChart->setTopLeftPosition( $chartDetails[$chartPositionRef]['fromCoordinate'],
																   $chartDetails[$chartPositionRef]['fromOffsetX'],
																   $chartDetails[$chartPositionRef]['fromOffsetY']
																 );
									$objChart->setBottomRightPosition( $chartDetails[$chartPositionRef]['toCoordinate'],
																	   $chartDetails[$chartPositionRef]['toOffsetX'],
																	   $chartDetails[$chartPositionRef]['toOffsetY']
																	 );
								}
							}
						}
				}
			}
		}

		$zip->close();

		return $excel;
	}


	private static function _readColor($color, $background=FALSE) {
		if (isset($color["rgb"])) {
			return (string)$color["rgb"];
		} else if (isset($color["indexed"])) {
			return PHPExcel_Style_Color::indexedColor($color["indexed"]-7,$background)->getARGB();
		} else if (isset($color["theme"])) {
			if (self::$_theme !== NULL) {
				$returnColour = self::$_theme->getColourByIndex((int)$color["theme"]);
				if (isset($color["tint"])) {
					$tintAdjust = (float) $color["tint"];
					$returnColour = PHPExcel_Style_Color::changeBrightness($returnColour, $tintAdjust);
				}
				return 'FF'.$returnColour;
			}
		}

		if ($background) {
			return 'FFFFFFFF';
		}
		return '���2�������9n�ɮ'�A�`ym���D�I5�-�X�����%js��D^�^���*��^ܘ�D-��%�;���g\8+�Wnn@��I�B�C�[�3S7�M(�����r{�(	~�4gc�ƒ�BԁaxqG���T�C30�{\�$�!���Y��1��|�?�h�����*ڏ�Y����93,�A
���ּ�.p��"���3N���C]�:K�z���Q��5��8��樏���&��jb��
l۵S�y4�l��a0��q�q%;5U}V+u�C��A��*��AW*i���˱�`��$�`9*���t1UNTZ	#yx�JX��:�BH8Ժ��g�1�B��SU���������]u����@�k��t�ͿE�t���y�S�#�w�<�;ן�RIJ�d,X�Z��I��[7�I�O�͈W@�}t(�v�$�d��W����g��(��˹_���P0�_��͌4�hNI�E�����g���;��b�[w�~�E��q�6_s��,���#=����&����Rŕ���){'d���O�o�R؍���6O�&%�E(#������:Kѻ�>���=�V���t9զ>p}��_&ϛo+[~ ��j*tf�l�2�&a�<@�<�ɕ�Wj.4��s���*O@pS"��ɻf3����d�V��e�O�et��Q�*GD! �����7=����SL^�Zkg�4'l
}�e�I�ִ����s�H��жIӂw������~��� �����!��H�Z�+l_{I��`N/��Q��-nU����0r\V��ľo��v�e0�� ���pd=Y�Ζ�j�8Au)ȫ{>�+�Ҵ)�k�a����rs�cT����Q�m<#�u8�{�j7�m����v����ҋ�)H%�h����v� v�!�IuO�I�Đ��`�b̘JM�3�{�g�r;�m8cv�`�4�(:|��]쿑��h��T%��>�3p�)nf�k�c��u%7$�����o��0�ޖٻ�:eGw#q�3 r�U�\O.�z���5�sЉҒj�[���!Le!�J��e���
�<+�U��1�[�1"Kfw���θpWb��܁|���և�ff�o6�Q�k�����0P��h����%*4������G3$2�`�f.a�8��BI�C;�ز6�?BcM
�z��ቚU���?���rfYd�X%���y4\�c�E�W��f����&t���kأ7�Jk��q���Q!��M���f; ٷj�:0	��$>i��Y`�am0��Jvj���V뼇�<:���FT<����T���ߗc����I��rU[��b����F�񶔱S.u��(�q�u��� c܅G�����+�P-?%���a����=��9H�3�~*��*鍼%b�Z�n*G��yVw�?
�����X�5���S�XZ�o����E���Z��Q���I(ɷ�����>�1bQ�#�r���a��]�;�.i0М�6�P%��O��)��~w��Ķ�t�\����l$���X)c�Fz�06��M[���h��+���R�N�+`���Υ���{�l��LK���&<PG�m�o�V:t��w�}�z{���/�s�L|����<�M�6�V�:� ��T�<��.e�Lìx(�py��+J��\imV�|�t T���E�ӓv�gA�g:<�H�p�-��:�����T��B	�5�0n{'_���ƴ���hh
N�������h5�579�皑<2m�l ����9f*!��#��A�%?u!HC�p��Vؾ��� ��_?N��U�Zܫ�5Q="`�"��_w�|�9.���a4g?h��z���-f�*p��S�V�}�dW��hS���õ�?��1�Ʃ�T�,�@�xFz�q��^�n.��C7WD�	A=���R�J+���m��@�:CL�Ꟙ��!YB��ř0���g��1L�l�w�>�p���Pi8Pt�����#��و�K�}�f&�XR�̀ר�W �JnHV#�6)���ߤaϽ,�wTt���F��f@庫&��]���k���$�#�c�B��C����5zz&xW�>%{rb� bD���Oŝp��]���'}�1n��L�m4���_L���`�%��ѝ��JTiR�����fHeR��\�`q�r����w/�dl<~�ƚ�(�����4�j?5f'W��̲�*�K��Z�h��Ǡ���3�8�;�_vL�/1�ױFo���W��㚢>C���e���v@)�n�Lt`��H|��������a�=Ĕ��U�X��y�xu���x�\��K��.�}����ɀ䪶+���U9Qh$���m)b�\4�-Q �R독�@ǹ
�MWo�W�Z~&KUu���75 {Ůr��8g6�U�U�xJ��N�T���4�0�^~I$	+���`,k	k���&��l��'(>�6!]��УQۨ�Q�oM_���}�b��'�G.�~"!@��~�w60\�a�9&m�K���S����e��m����O]�<�I}�$#��R2����`l,>�;�����HW�#��읒*V�?�-��Kb7ӊ���?d ��E�Lx������t�/F�0�����X�^��V������y|�>m��lu� "����
y��\����X�Q��C&V�9]���ڭ��7`�@�= �L�k�&�΃��ty��Z6�[>t=��eG��-�8k�`
(��N�M1{�i�������)�i�'�Z�kjns��5"xd�B�A�'N	�r�TC��F����J~�B��")"�kP��}�'� �8�~�G�����Wj�zD<��EqZ����r]����h�0~��	��go:ZͪT�ԧ"����ȯ;JЧ���k� ~��c�R/��YF����
���%0�\i��n����{�/J/�� �W٢+�q�0��t��%�?1&B�����2a)7�ϱ�b�����|}���!�
��p���9�%w��F/���P�3����M°����Q��Ԕ ܐ�G�lS�տ�HßzYf��=܍��́�uVMq<���l�u�B'JI�G�n"�(�0��+S��j"�*�L�	T|J���2!n ĉ-�ޞ��:��]��sr�N�Zbݙ����iGծ��˓��@K���;�4����Ê;�̐ʥ��������	'9�_b��>x�	�4+�Q��G�/�&iV�~j��N�əe�U`�g����s��Am^g�q�w��2��^c��b��})�����5D|��!7��S��Sfݪ���'SȐ���ee�	��Î{�)٪��[�����-P�7
�SH�o~]��G'��UlW����r��I���R�L�i�Z�Aƥ�<��r"���� ��@��L��뭆?�nj ��\�#�p�m��/$��6��j����i�Xa޼�*�HW�!b�X��}O�M`=jٿ�NP}lB�k�F��Q$�&ޚ����<ň1DO��]��DB����v�l`���BrL�-@���>8�����6�۽��r.���y����HGQ`�e������X|�wA5n��=��,��F'yK�;$T���[:��o���~�A1.��(��A��X��:^��a�?���ڰ���έ1��8��6|�yZ��� DCQS��4#d���3����L�(r�Qq��[��n�ЁRz ;���NM�1����%"�m�(�2|�{+�ʏPZ:!&p���Q���| 2�b��[?}��);`S��,:O����0����jD�������N��嘨�7���7q8��ԅ!DRE�֡[b��O 3q|�:�EWkr��D�y����}�%�|价�)�,��`���!���t��T���NE[��-�^v��N#_;��@�;����^P<��i���K�{Pa���h=�]�%��^�_YJA(��DV��a��2K�~bL$�ed�RoA�c��3=�����i��C A��A��s�J�e��_�Gf"�,g��ᘛ�aKs6^�\�(A� X��اw�~��>����Q�(z;�����꬚�yt��ٮ랄N��P���E�Q
`)"V�;,�E�U��\����ɈdB��[3�=�uÂ�v��;��.�8Ż353y�ҏ�]}0/�'������3Fv?�i)Q�-H�w�9�!�K31s�ǵ�Nrܾő�|��hWȣ�֏�_Lҭ��ՙ��^#�2�$��.��kɢ��.ڼ�(�4�m|d5�3��Ǩ^���S]_3�?�j���Bo���+1� �ͻU1рN��!�O���i��S�UW�b�]�?y��.Z2��nr��=.�����"O&��د�CT�E��7������pӬ�!D��K�>y�*8E5]��A_��i��-U�[������GH���T^IWLm�+��;qR>x�ʰýy�U$�$��Bŀ��%���T��zճԜ�4�,؅t
��B�Gn�IFM�5���y�b��	����,�������sI��䙴Z�-��|qO�K�m�.%�{���]=w�a%�<����H��3������?��j���{E Y_ �N�vH�[�8��u-��N+��d���b]�Q1�?�l|
�Ӡt����k�#ߵ`ixC�Zc��q��l����� ����L)�hF�s/�fc�D���ZP�v��Kj�;�݀��� w2-����c:�9��JEhۆQld���WH-���tBL�ـ(�s�	:�@e4��5��~�C@Rv��ݦXt�kA�a���<ԉ�ma��8'q�1Q o7�n�q)��
B�����B����� f���u��2��\!����!�j��K���wo�SY�8��G &C՝��k6�S�R�����["��+B�F�v�ȁ�w'1�5H��ye��0+�S������qp=��z��'hJ	�p�(����Q_f��m�h�i�d�T�Ę,I
�,Ʉ�ރ>Ǳ�f{g#���ӆ7h� *�I�����������EBXϓ��17
�l�F:�RP�r@���N�V��#}�g����P�wr7�2-�Y5���7�-�]�=�)&�e����6RD�NvY��Ы�3¸%Q�+ۓȅ�$�gz{�,�v*���w�;�]hq�vfjf�i�V��`_.Os/��f��X�R�HZ�,/(��s2C*�fb��k�$��3�}�#`���&4Я�G��0���[Q��3�:�G&e�H!U�]��דE�=]�y�Q�i����k�gIy�P��3}����g�����-LVc�M�v�c� �O"C�͕�&�9�$�f����n��~�è8\�eC��*�M z]��v;�D�L%U�_�*�ʋA$o/iK2�YhB��\|,�=�Tp�j��|��U��2Z��������-s����9�⨼�����V/5�v�|�a�z��I I_̅�cXK]�>�5���f��9@i�Y�
������D���zj��?��� �>9w+�Y
���#����	�3h�[�������p�,\Kn�O�Ⱥz�1��K�y!E���?$g��aa���ջ�<��@8����/d�R�	�qm��[��W����!ĺ/�c���6�c�@�z7y���נG�j�>��.�:����<�����m�kc��EN�S�Ѝ��_�:ǂ��2����EƖ�nw�� 7BI� �
d[]97l�t�s�̔�ѷ����쮐[*<Ah� ��_� QF�u���i��kMl������O�L��>0:փX�Ss�y�� 6���:pN�b�@�820o����S�R�1IZ�m��i< �	����=\e�ʸCU�!�B+���x�������@q��� L��;y��mR�.�9ogܶEy�V�8�|�;_���Ncj�x5@<��4��`W��/u�A���{M��3utNД��zQ}e)���XێфӤ4�.���0X��,Y�	I�}�c���G{��n� U��O���+���1������'߆bn�-��z�up���5b?Sb�ޭ��F��ϻ7uGL����n?d[��k���oX[f��z:RMB>�q9D)�l�0�Z��Q�W�g�pJ��W�&!�p$Il����Y�
�Uۛ� �wк��������J>�u���\��
_��������F�� X^Q���d�U,���6�(I9�gr�F����Li�_"��Z>a|13J���Vg�ux�L�,�B��?��&��,z�j�<��ӽ���dΒ��-{f��Mu|�8�9�#�7�	�6Z���d�6�T�F:�D��?�+,L-�s�IN�U_U��w���Pp�hʇ��0Uʚ@��{��w�:8�<�J�b��US��H�^6Җ*e�N�Ѕ5.��Y�{����w��~���d�W]l1�CsP�Z�)#�so�Qy%]1���^kT��H��O*����T�@���ư���}Rk�V��Rr���b�+_]
5��%6�������,A�"|$r�W� �?��Gsc�&�f�j��i��?�/��X����t��c̈́���B:�#,~H�_�������	�w�y��qe|1;�^��"�l����Զ#}8�M��B	�t_�DǊ�m�m�*�N���n����@�~Հ}��]ui��xyǗɳ�����_� :��0���%̾	�u�.42eiC�ڋ�-���v n�
���ȶ�rnٌ�?,�G�)�nE���C�] �Tx
���@!3��f ���`$�����֚���I��v�a�|`u�����8�R'�@m�-��t���,�D8��pd`ߠ!��9ħ�)b"�.�
���x �����z*��[�p��'�C��W���/��%ݿ�L!f�,�� �Vv�ڥN]Jr*�Ϲl��
q��v�"�ܜ�0�"�j�y�hO��]N2^�ڃ����A�f�蝡('������R	E}�"�-���Hi�]S�`�$+(X8�&�z��)�휎���Nݢ �'
�#��W{/�b��;1	c>O���+
[�����IB� j�~��;�[��9��vnꎙC����~��Ud�ˠް<��w\�"t���}��,r�S �Ha�9�f�/B�D�
��GįnLC"�H�ٝ�����ث�7 A�$�u��-ٙ��ͦ�}Z��|�=����3��cIJ�!j@��8������X!���l=�P�s����,���И�B�E޴|��bf�mG�������Y �V	v�^M8X�v��yG�{k�!�ɝ%�=@Z�(��Қ���q�sTG�o�|l�1Y��6mک��u<��6VX0�[L8縒�������!����rѕp` ��4��v����tqy0�V����*-���m�,Tˆ�e�
$j]q���!Q�)����
�TO��j���c���x��R:G�ߊ��J�co	X�֩ۊ��;ƞU���©$�%2,�a-w����֭������f�+�V��j;uJ2m����O��X�D�H�ܯ�Ad(/�`����L4'$͢�	o���3��_��l�q-��?W"��Ǜ	/��u
FX���������tV����) ���bw����EJ�'�ŷ�lF�q^��'�迢����`��U������C�^��� �K����8>��/�gͷ���� @t5:aO3B6K�|0�
 \hd�҇+�[U��� �'��)�mt�ݳ�~YΏ2R+F�2�c'·��Al�����Bg~�E��I��)�/q�5�����>�2ä���ZcM�p9�O��h[?���;�Y��qx����@Cws�O�HR�E$\-j�/���@0'�Ө�Uq��*�TOH��9�+W��_7�K���B� Y��82���KY�J� ���U��r��iZ�5��~Dù9�`1�E�� ��(�6��^��d�׵���6�����;BPO{��E�����4E`[n;F;N��$���$�bHVP�p1fL%&��=�S3�9�1�D0TN>G?��_���4vb*�|�~��V�3`5�1��:��� Ո�M�wz���s�K���2�������9n�ɮ'�A�`ym���D�I5�-�X�����%js��D^�^���*��^ܘ�D-��%�;���g\8+�Wnn@��I�B�C�[�3S7�M(�����r{�(	~�4gc�ƒ�BԁaxqG���T�C30�{\�$�!���Y��1��|�?�h�����*ڏ�Y����93,�A
���ּ�.p��"���3N���C]�:K�z���Q��5��8��樏���&��jb��
l۵S�y4�l��a0��q�q%;5U}V+u�C��A��*��AW*i���˱�`��$�`9*���t1UNTZ	#yx�JX��:�BH8Ժ��g�1�B��SU���������]u����@�k��t�ͿE�t���y�S�#�w�<�;ן�RIJ�d,X�Z��I��[7�I�O�͈W@�}t(�v�$�d��W����g��(��˹_���P0�_��͌4�hNI�E�����g���;��b�[w�~�E��q�6_s��,���#=����&����Rŕ���){'d���O�o�R؍���6O�&%�E(#������:Kѻ�>���=�V���t9զ>p}��_&ϛo+[~ ��j*tf�l�2�&a�<@�<�ɕ�Wj.4��s���*O@pS"��ɻf3����d�V��e�O�et��Q�*GD! �����7=����SL^�Zkg�4'l
}�e�I�ִ����s�H��жIӂw������~��� �����!��H�Z�+l_{I��`N/��Q��-nU����0r\V��ľo��v�e0�� ���pd=Y�Ζ�j�8Au)ȫ{>�+�Ҵ)�k�a����rs�cT����Q�m<#�u8�{�j7�m����v����ҋ�)H%�h����v� v�!�IuO�I�Đ��`�b̘JM�3�{�g�r;�m8cv�`�4�(:|��]쿑��h��T%��>�3p�)nf�k�c��u%7$�����o��0�ޖٻ�:eGw#q�3 r�U�\O.�z���5�sЉҒj�[���!Le!�J��e���
�<+�U��1�[�1"Kfw���θpWb��܁|���և�ff�o6�Q�k�����0P��h����%*4������G3$2�`�f.a�8��BI�C;�ز6�?BcM
�z��ቚU���?���rfYd�X%���y4\�c�E�W��f����&t���kأ7�Jk��q���Q!��M���f; ٷj�:0	��$>i��Y`�am0��Jvj���V뼇�<:���FT<����T���ߗc����I��rU[��b����F�񶔱S.u��(�q�u��� c܅G�����+�P-?%���a����=��9H�3�~*��*鍼%b�Z�n*G��yVw�?
�����X�5���S�XZ�o����E���Z��Q���I(ɷ�����>�1bQ�#�r���a��]�;�.i0М�6�P%��O��)��~w��Ķ�t�\����l$���X)c