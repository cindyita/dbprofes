<?php 
require 'vendor/autoload.php';
//Cargar variables de entorno
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$appENV = $_ENV['APP_ENV'] ?? "production";

if($appENV == 'local'){
    $dotenv = Dotenv::createMutable(__DIR__,'.env.local');
    $dotenv->load();
}

require_once 'const.php';
require_once 'src/resources/functions.php';
require_once 'src/resources/emails.php';
