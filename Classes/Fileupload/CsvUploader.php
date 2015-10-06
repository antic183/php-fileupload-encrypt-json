<?php

namespace Fileupload;
use \GibberishAES\GibberishAES;

require_once 'Uploader.php';
/*
 * implement class Uploader
 */

/**
 * CsvUploader
 *
 * @author Antic Marjan
 */
class CsvUploader extends \Fileupload\Uploader {

  private static $jsonContent;

  public function __construct($_identyfier = null) {
    //first implements your requirements. Per Example: "login" etc..
    //call the parent constructor to initialize fundamentle functionality
    parent::__construct(isset($_identyfier) ? $_identyfier : null, 'csv');
  }

  public function startUpload() {
    parent::initUpload();

    if (self::$jsonContent) {
      $this->saveJsonFile();
    }
  }

  protected static function checkFileContent() {
    $fileContent = file_get_contents(static::$tmpFilepath);

    // check encoding and convert to utf-8 when necessary
    $detectedEncoding = mb_detect_encoding($fileContent, 'UTF-8, ISO-8859-1, WINDOWS-1252', true);
    If ($detectedEncoding) {
      if ($detectedEncoding !== 'UTF-8') {
        $fileContent = iconv($detectedEncoding, 'UTF-8', $fileContent);
      }
    } else {
      echo 'Zeichensatz der CSV-Date stimmt nicht. Der sollte UTF-8 oder ISO-8856-1 sein.';
      return false;
    }

    //prepare data array
    $tmpData = str_getcsv($fileContent, PHP_EOL);
    array_shift($tmpData);

    $preparedData = [];
    $data = array_map(function($row) use (&$preparedData) {
      $tmpDataArray = str_getcsv($row, ';');
      $tmpKey = trim($tmpDataArray[0]);
      array_shift($tmpDataArray);
      $preparedData[$tmpKey] = $tmpDataArray;
    }, $tmpData);

    // generate json
    $jsonContent = json_encode($preparedData, JSON_HEX_TAG | JSON_HEX_AMP);
    self::$jsonContent = $jsonContent;
    return true;
  }

  private function saveJsonFile() {
    GibberishAES::size(256);
    $encrypted_string = GibberishAES::enc(self::$jsonContent, '7dfb11d4f3e70cf78ab1a4fcca5279dc');
    $saveFile = file_put_contents(static::$savePath . '/data.json', $encrypted_string);
    if ($saveFile) {
      echo 'saved';
    }
  }

}
