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
    if(isset($data['g-recaptcha-response'])){
        if(checkCaptcha($data['g-recaptcha-response']) == true){
            $response = AuthController::auth($data);
            echo json_encode($response);
        } else {
            echo 4;
        }
    }else{
        echo 4;
    }
    
}