<?php

$upload['csv']['allowedMymeTypes'] = ['text/csv', 'application/vnd.ms-excel']; //array with allowed mymetypes
$upload['csv']['allowedExtension'] = 'csv';
$upload['csv']['allowedFileCharset'] = ['UTF-8'];
$upload['csv']['maxFileSize'] = 1024 * 900; //filesize in kilobytes (kb)
$upload['csv']['minFileSize'] = 100; //filesize in bytes (b)
$upload['csv']['savePath'] = 'upload'; //save path
