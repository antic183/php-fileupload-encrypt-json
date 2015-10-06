<?php

namespace Fileupload;

/*
 * Licene: "MIT"
 * Version: "0.0.1" alpha
 * Date: "25.09.2015"
 */

/**
 * Uploader
 * You can use the 'Class Uploader' to implements any upload Classes with fundamental functionality.
 * You can expand configurations in file 'Config.php'.
 *
 * @author Antic Marjan
 */
Abstract Class Uploader {

  //upload-configurations
  private static $id;
  private static $configFile = 'Config.php';
  private $readyToStart = false;
  //configuration (Object) 
  //-------------> used in CsvUploader
  protected static $config;
  protected static $tmpFilepath;
  private static $uploadError;
  //-------------->used in CsvUploader
  protected static $uploadFileType;
  //-------------->used in CsvUploader
  protected static $uploadFileSize;
  //-------------->used in CsvUploader
  protected static $uploadFileName;
  //-------------->used in CsvUploader
  protected static $allowedCharset;
  //-------------->used in CsvUploader
  protected static $savePath;

  /**
   * Helper methode
   * It convert Array to Object
   * @param Array $array Mulitple Array
   * @return Object
   */
  private static function arrayToObject(Array $array) {
    return json_decode(json_encode($array));
  }

  /**
   * Load configuration file
   * Load and set upload configurations
   * @param type $type
   * @return boolean true|false
   */
  private function setConfig($type) {
    //inlude configuration
    require_once(realpath(__DIR__ . '/../' . self::$configFile));
    //set upload configuration
    self::$config = isset($upload[$type]) ? self::arrayToObject($upload[$type]) : false;
    return self::$config ? true : false;
  }

  public function __construct($_identyfier, $type) {
    //set id
    if ($_identyfier && is_string($_identyfier)) {
      self::$id = $_identyfier;
      //set config
      if (!$this->setConfig($type)) {
        throw new \Exception('Fehler bei der Kofiguration. Überprüfe den Typ (' . $_identyfier . ')');
      }
      $this->readyToStart = true;
    } else {
      throw new \Exception("Fehlender Parameter");
    }
  }

  protected function initUpload() {
    // preparation
    if (!$this->readyToStart) {
      throw new \Exception("not ready for upload!");
    }
    self::$tmpFilepath = $_FILES[self::$id]['tmp_name'];
    self::$uploadError = $_FILES[self::$id]['error'];
    self::$uploadFileType = $_FILES[self::$id]['type'];
    self::$uploadFileSize = $_FILES[self::$id]['size'];
    self::$uploadFileName = $_FILES[self::$id]['name'];
    self::$allowedCharset = self::$config->allowedFileCharset;
    self::$savePath = self::$config->savePath;
    // check upload
    if (!$this->checkUpload()) {
      Throw new \Exception("upload failed!!");
    }

    // check file type
    if (!static::checkFileType()) {
      Throw new \Exception("error: mimetype!");
    }

    // check file size
    if (!static::checkFilsize()) {
      Throw new \Exception("error: filesize!");
    }

    // check file content
    if (!static::checkFileContent()) {
      Throw new \Exception("error: fail file content!");
    }
  }

  private function checkUpload() {
    $errors['isUploadedFile'] = is_uploaded_file(self::$tmpFilepath) ? 1 : 0;
    $errors['uploadError'] = self::$uploadError ? 0 : 1;
    if (in_array(0, $errors)) {
      return false;
    } else {
      return true;
    }
  }

  protected static function checkFileType() {
    if (!in_array(self::$uploadFileType, self::$config->allowedMymeTypes)) {
      echo 'falscher file type: ';
      echo self::$uploadFileType;
      return false;
    }
    return true;
  }

  protected static function checkFilsize() {
    if (self::$uploadFileSize < self::$config->minFileSize || self::$uploadFileSize > self::$config->maxFileSize
    ) {
      echo 'Datei zu gross oder zu klein!<br/>';
      echo htmlspecialchars(self::$uploadFileSize) . ' byte';
      return false;
    }
    return true;
  }

  protected static abstract function checkFileContent();
}
