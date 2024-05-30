<?php
require_once '../../../commons.php';
use ModelsNS\QueryModel;
session_start();

if (!empty(getView())) {
    switch (getView()) {
        case 'CHECKUSERNAME':
            checkExistUsername();
        break;
        case 'UPDATE':
            updateProfile();
        break;
        default:
            echo "No se ha definido una acción";
        break;
    }
}

function checkExistUsername() {
    $data = getPostData();
    $db = new QueryModel();
    if (!empty($data)) {
        $row = $db->queryUnique("SELECT username FROM SYS_USER WHERE username=:username",[":username"=>$data['username']]);
        $response = json_encode($row);
    } else {
        $response = json_encode(['error'=>'Invalid format or no info']);
    }
    $db->close();
    echo json_encode($response);
}

function updateProfile(){
    $data = $_POST;
    $db = new QueryModel();

    $fields = dataForUpdate($data);

    if (!empty($fields['setParams'])) {
        $setQuery = implode(', ', $fields['setParams']);
        $row = $db->query("UPDATE SYS_USER SET $setQuery WHERE id=:id",$fields['params']);
    }
    $res = processUpdate($row);
    if($res){
        $_SESSION['PSESSION']['username'] = $data['username'];
        $_SESSION['PSESSION']['biography'] = $data['biography'];
    }

    echo $res;
}