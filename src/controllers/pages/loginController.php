<?php
require_once '../../../commons.php';
// use ModelsNS\QueryModel;
use ControllersNS\AuthController;

if (!empty(getView())) {
    switch (getView()) {
        case 'LOGIN':
            login();
        break;
        default:
            echo "No se ha definido una acción";
        break;
    }
}

function login(){
    $data = $_POST;
    if(checkCaptcha(($data['g-recaptcha-response'] ?? 0)) == true){
        $response = AuthController::auth($data);
        echo json_encode($response);
    } else {
        echo 4;
    }
}