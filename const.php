<?php 
// Constantes disponibles en todo el programa
define("DATETIME", date('Y-m-d H:i:s'));
define("BASEPATH", $_SERVER['DOCUMENT_ROOT']);
define("BASESELF", $_SERVER['PHP_SELF']);
define("ACTUALPAGE", basename($_SERVER['PHP_SELF']));
define("SESSION",$_SESSION['PSESSION'] ?? false);

define('VIEW', '__view__');

define("VERSION", DATETIME);