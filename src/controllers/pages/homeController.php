<?php
require_once '../../../commons.php';
use ModelsNS\QueryModel;
session_start();

if (!empty(getView())) {
    switch (getView()) {
        case 'GET':
            getOpinion();
        break;
        case 'GETRESPONSE':
            getResponse();
        break;
        case "POST":
            postOpinion();
        break;
        case 'DELETE':
            deleteOpinion();
        break;
        case 'DELETERESPONSE':
            deleteResponse();
        break;
        case 'UPDATE':
            updateOpinion();
        break;
        case 'UPDATERESPONSE':
            updateResponse();
        break;
        case 'LOADOPINIONS':
            loadOpinions();
        break;
        case 'LOADRESPONSES':
            loadResponses();
        break;
        case 'POSTRESPONSE':
            postResponse();
        break;
        case 'LIKEPOST':
            like(1);
        break;
        case 'LIKERESPONSE':
            like(2);
        break;
        case 'DISLIKEPOST':
            dislike(1);
        break;
        case 'DISLIKERESPONSE':
            dislike(2);
        break;
        default:
            echo "No se ha definido una acción";
        break;
    }
}

function getOpinion(){
    $data = getPostData();
    $db = new QueryModel();
    $id = $data['id'];
    $opinion = $db->queryUnique("SELECT o.*,a.name accessibility,f.name form_grading,t.name time_grading,img.img,img.num_img
            FROM POST_OPINION o 
            LEFT JOIN REG_ACCESSIBILITY a ON o.id_accessibility = a.id
            LEFT JOIN REG_FORM_GRADING f ON o.id_form_grading = f.id
            LEFT JOIN REG_TIME_GRADING t ON o.id_time_grading = t.id
            LEFT JOIN POST_IMG img ON img.id_opinion_response = o.id AND img.type_opinion = 1
            WHERE o.id = :id",[':id'=>$id]);
    echo json_encode($opinion);
}

function getResponse(){
    $data = getPostData();
    $db = new QueryModel();
    $id = $data['id'];
    $responses = $db->queryUnique("SELECT r.*,img.img,img.num_img
        FROM POST_RESPONSE r
        LEFT JOIN POST_IMG img ON img.id_opinion_response = r.id AND img.type_opinion = 2
        WHERE r.id = :id
        ORDER BY r.id DESC",[':id'=>$id]);
    echo json_encode($responses);
}

function postOpinion() {
    $data = $_POST;
    $db = new QueryModel();

    if(isset($data['anonymous']) && $data['anonymous'] == "on"){
        $data['anonymous'] = 1;
    }

    $fields = dataForInsert($data);

    $row = "no data";
    if (isset($fields['setParams'])) {
        $setQueryParams = implode(',', $fields['setParams']);
        $setQueryValues = implode(',', $fields['setValues']);
        $params = $fields['params'];
        $row = $db->query("INSERT INTO POST_OPINION($setQueryParams) VALUES($setQueryValues)",$params);
    }

    if($_FILES && $_FILES['img'] && $_FILES['img']['size'] != 0){
        $numFiles = is_array($_FILES['img']['name']);
        $numImg = 0;

        $idPost = $db->lastid();
        $ruta = '../../../assets/img/posts/'.$idPost.'/';
        if($numFiles){
            $img = createMultiFiles('img',$ruta,"",0);
            $numImg = count($_FILES['img']['name']);
            $img = call_user_func_array('array_merge', $img);
            $nameImgs = implode(',', $img);
            $queryimg = $db->query("INSERT INTO POST_IMG(img,type_opinion,id_opinion_response,num_img) VALUES(:img,1,:id_opinion,:num_img)",[":img"=>$nameImgs,":id_opinion"=>$idPost,":num_img"=>$numImg]);
        }else{
            $img = createFile('img',$ruta,"",0);
            $numImg = 1;
            $img = call_user_func_array('array_merge', $img);
            $queryimg = $db->query("INSERT INTO POST_IMG(img,type_opinion,id_opinion_response,num_img) VALUES(:img,1,:id_opinion,1)",[":img"=>$img,":id_opinion"=>$idPost]);
        }
        if($img == 6){
            echo 6;
            return;
        }
        
        $row = $db->update("POST_OPINION",["num_img"=>$numImg],"id = $idPost");
    }

    if($row == []){
        echo 1;
    }else{
        echo json_encode($row);
    }
}

function deleteOpinion(){
    $data = getPostData();
    $db = new QueryModel();
    $id = $data['id'];
    $id_user = $db->value("POST_OPINION","id = $id","id_user");

    if (isset($_SESSION['PSESSION']) && ($_SESSION['PSESSION']['id'] == $id_user || $_SESSION['PSESSION']['id_role'] <= 2)) {

        $ruta = '../../../assets/img/posts/'.$id.'/';
        if (!file_exists($ruta)) {
            $files = glob($ruta . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        $db->query("DELETE FROM POST_IMG WHERE id_opinion_response = :id_opinion AND type_opinion = 1",[':id_opinion'=>$id]);

        //------------------------------------------------
        $responses = $db->select("POST_RESPONSE","id_opinion = $id");
        if($responses){
            foreach ($responses as $value) {
                $ruta = '../../../assets/img/responses/'.$value['id'].'/';
                if (!file_exists($ruta)) {
                    $files = glob($ruta . '*');
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            unlink($file);
                        }
                    }
                }
            }
            $db->query("DELETE FROM POST_IMG WHERE id_opinion_response = :id_opinion AND type_opinion = 2",[':id_opinion'=>$id]);
            $row = $db->query("DELETE FROM POST_RESPONSE WHERE id_opinion = :id",[":id"=>$id]);
        }
        //-------------------------------------------------

        $row = $db->query("DELETE FROM POST_OPINION WHERE id = :id",[":id"=>$data['id']]);
        if($row == []){
            echo 1;
        }else{
            echo json_encode($row);
        }

    }else{
        echo json_encode("No tienes permisos para esta acción");
    }
}

function deleteResponse(){
    $data = getPostData();
    $db = new QueryModel();
    $id = $data['id'];
    $id_user = $db->value("POST_RESPONSE","id = $id","id_user");

    if (isset($_SESSION['PSESSION']) && ($_SESSION['PSESSION']['id'] == $id_user || $_SESSION['PSESSION']['id_role'] <= 2)) {

        $ruta = '../../../assets/img/responses/'.$id.'/';
            if (!file_exists($ruta)) {
                $files = glob($ruta . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
            
        $db->query("DELETE FROM POST_IMG WHERE id_opinion_response = :id_opinion AND type_opinion = 2",[':id_opinion'=>$id]);
        
        $row = $db->query("DELETE FROM POST_RESPONSE WHERE id = :id",[":id"=>$id]);
        if($row == []){
            echo 1;
        }else{
            echo json_encode($row);
        }

    }else{
        echo json_encode("No tienes permisos para esta acción");
    }
}

function updateOpinion(){
    $data = getPostData();
    $db = new QueryModel();
    $id = $data['id'];
    $id_user = $db->value("POST_OPINION","id = $id","id_user");

    if (isset($_SESSION['PSESSION']) && ($_SESSION['PSESSION']['id'] == $id_user || $_SESSION['PSESSION']['id_role'] <= 2)) {

        if(isset($data['anonymous']) && $data['anonymous'] == "on"){
            $data['anonymous'] = 1;
        }else if(isset($data['anonymous'])){
            $data['anonymous'] = 0;
        }

        $fields = dataInQuery($data);
        
        if($_FILES && $_FILES['img']['size'] != 0){
            $numFiles = is_array($_FILES['img']['name']);
            $ruta = '../../../assets/img/posts/'.$id.'/';
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $files = glob($ruta . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            $imgs = "";
            if($numFiles){
                $img = createMultiFiles('img',$ruta,"",0);
                $numImg = count($_FILES['img']['name']);
                $img = call_user_func_array('array_merge', $img);
                $imgs = implode(',', $img);
            }else{
                $img = createFile('img',$ruta,"",0);
                $numImg = 1;
                $img = call_user_func_array('array_merge', $img);
                $imgs = $img;
            }
            if($img == 6){
                echo 6;
                return;
            }
            $fields['num_img'] = $numImg;
            $db->query("DELETE FROM POST_IMG WHERE id_opinion_response = :id_opinion AND type_opinion = 1",[':id_opinion'=>$id]);
            $db->query("INSERT INTO POST_IMG(img,type_opinion,id_opinion_response,num_img) VALUES(:img,1,:id_opinion,:num_img)",[":img"=>$imgs,":id_opinion"=>$id,":num_img"=>$numImg]);
        }

        $setParams = [];
        $params = [":id"=>$id];
        foreach ($fields as $key => $value) {
            if ($value !== null) {
                $setParams[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        if (!empty($setParams)) {
            $setQuery = implode(', ', $setParams);
            $row = $db->query("UPDATE POST_OPINION SET $setQuery WHERE id=:id",$params);
        }

        if($row == []){
            echo 1;
        }else{
            echo json_encode($row);
        }
    }else{
        echo json_encode("No tienes permisos para esta acción");
    }
}

function updateResponse(){
    $data = getPostData();
    $db = new QueryModel();
    $id = $data['id'];
    $id_user = $db->value("POST_RESPONSE","id = $id","id_user");

    if (isset($_SESSION['PSESSION']) && ($_SESSION['PSESSION']['id'] == $id_user || $_SESSION['PSESSION']['id_role'] <= 2)) {
    
        if(isset($data['anonymous']) && $data['anonymous'] == "on"){
            $data['anonymous'] = 1;
        }else if(isset($data['anonymous'])){
            $data['anonymous'] = 0;
        }

        $fields = dataInQuery($data);
        
        if($_FILES && $_FILES['img']['size'] != 0){
            $numFiles = is_array($_FILES['images']['name']);
            $ruta = '../../../assets/img/responses/'.$id.'/';
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $files = glob($ruta . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            $imgs = "";
            if($numFiles){
                $img = createMultiFiles('images',$ruta,"",0);
                $numImg = count($_FILES['images']['name']);
                $img = call_user_func_array('array_merge', $img);
                $imgs = implode(',', $img);
            }else{
                $img = createFile('images',$ruta,"",0);
                $numImg = 1;
                $img = call_user_func_array('array_merge', $img);
                $imgs = $img;
            }
            if($img == 6){
                echo 6;
                return;
            }
            $fields['num_img'] = $numImg;
            $db->query("DELETE FROM POST_IMG WHERE id_opinion_response = :id_opinion AND type_opinion = 2",[':id_opinion'=>$id]);
            $db->query("INSERT INTO POST_IMG(img,type_opinion,id_opinion_response,num_img) VALUES(:img,2,:id_opinion,:num_img)",[":img"=>$imgs,":id_opinion"=>$id,":num_img"=>$numImg]);
        }

        $setParams = [];
        $params = [":id"=>$id];
        foreach ($fields as $key => $value) {
            if ($value !== null) {
                $setParams[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        if (!empty($setParams)) {
            $setQuery = implode(', ', $setParams);
            $row = $db->query("UPDATE POST_RESPONSE SET $setQuery WHERE id=:id",$params);
        }

        if($row == []){
            echo 1;
        }else{
            echo json_encode($row);
        }

    }else{
        echo json_encode("No tienes permisos para esta acción");
    }
}

function loadOpinions(){
    $data = getPostData();
    $limit = $data['limit'] ?? 15;
    $offset = $data['offset'] ?? 0;
    $db = new QueryModel();
    $typesearch = isset($data['typesearch']) ? urldecode($data['typesearch']) : "";
    $textsearch = isset($data['textsearch']) ? urldecode($data['textsearch']) : "";

    if(isset($_SESSION['PSESSION'])){
        $iduser = $_SESSION['PSESSION']['id'];
        if($typesearch == "" && $textsearch == ""){
            $opinions = $db->query("SELECT o.*,u.username,a.name accessibility,f.name form_grading,t.name time_grading,img.img,img.num_img, ps.num_likes likes, ps.num_responses responses,
            IF(rl.id_opinion_response IS NOT NULL, 1, 0) AS user_liked
                FROM POST_OPINION o 
                LEFT JOIN SYS_USER u ON o.id_user = u.id
                LEFT JOIN REG_ACCESSIBILITY a ON o.id_accessibility = a.id
                LEFT JOIN REG_FORM_GRADING f ON o.id_form_grading = f.id
                LEFT JOIN REG_TIME_GRADING t ON o.id_time_grading = t.id
                LEFT JOIN POST_IMG img ON img.id_opinion_response = o.id AND img.type_opinion = 1
                LEFT JOIN VIEW_POST_STATS ps ON ps.post_id = o.id
                LEFT JOIN REL_LIKES rl ON o.id = rl.id_opinion_response AND rl.type_opinion = 1 AND rl.id_user = :id_user
                ORDER BY o.id DESC
                LIMIT :limits OFFSET :offset",[":id_user"=>$iduser,':limits'=>$limit,":offset"=>$offset]);
        }else{
            if (preg_match('/^[\p{L}\p{N}_\-,.\s]+$/u', $textsearch) && preg_match('/^[\p{L}\p{N}_\-,.\s]+$/u', $typesearch)) {
                if($typesearch == "my"){
                    $opinions = $db->query("SELECT o.*,u.username,a.name accessibility,f.name form_grading,t.name time_grading,img.img,img.num_img, ps.num_likes likes, ps.num_responses responses,
                    IF(rl.id_opinion_response IS NOT NULL, 1, 0) AS user_liked
                        FROM POST_OPINION o 
                        LEFT JOIN SYS_USER u ON o.id_user = u.id
                        LEFT JOIN REG_ACCESSIBILITY a ON o.id_accessibility = a.id
                        LEFT JOIN REG_FORM_GRADING f ON o.id_form_grading = f.id
                        LEFT JOIN REG_TIME_GRADING t ON o.id_time_grading = t.id
                        LEFT JOIN POST_IMG img ON img.id_opinion_response = o.id AND img.type_opinion = 1
                        LEFT JOIN VIEW_POST_STATS ps ON ps.post_id = o.id
                        LEFT JOIN REL_LIKES rl ON o.id = rl.id_opinion_response AND rl.type_opinion = 1 AND rl.id_user = :id_user1
                        WHERE o.id_user = :id_user2
                        ORDER BY o.id DESC
                        LIMIT :limits OFFSET :offset",[":id_user1"=>$iduser,":id_user2"=>$iduser,':limits'=>$limit,":offset"=>$offset]);
                }else{
                    $opinions = $db->query("SELECT o.*,u.username,a.name accessibility,f.name form_grading,t.name time_grading,img.img,img.num_img, ps.num_likes likes, ps.num_responses responses,
                    IF(rl.id_opinion_response IS NOT NULL, 1, 0) AS user_liked
                        FROM POST_OPINION o 
                        LEFT JOIN SYS_USER u ON o.id_user = u.id
                        LEFT JOIN REG_ACCESSIBILITY a ON o.id_accessibility = a.id
                        LEFT JOIN REG_FORM_GRADING f ON o.id_form_grading = f.id
                        LEFT JOIN REG_TIME_GRADING t ON o.id_time_grading = t.id
                        LEFT JOIN POST_IMG img ON img.id_opinion_response = o.id AND img.type_opinion = 1
                        LEFT JOIN VIEW_POST_STATS ps ON ps.post_id = o.id
                        LEFT JOIN REL_LIKES rl ON o.id = rl.id_opinion_response AND rl.type_opinion = 1 AND rl.id_user = :id_user
                        WHERE o.$typesearch LIKE :text
                        ORDER BY o.id DESC
                        LIMIT :limits OFFSET :offset",[":id_user"=>$iduser,':limits'=>$limit,":offset"=>$offset,":text" => $textsearch . '%']);
                }
                
            }else{
                echo 4;
                return;
            }
        }
        
    }else{
        $opinions = $db->query("SELECT o.*,u.username,a.name accessibility,f.name form_grading,t.name time_grading,img.img,img.num_img, ps.num_likes likes, ps.num_responses responses,
        0 AS user_liked
            FROM POST_OPINION o 
            LEFT JOIN SYS_USER u ON o.id_user = u.id
            LEFT JOIN REG_ACCESSIBILITY a ON o.id_accessibility = a.id
            LEFT JOIN REG_FORM_GRADING f ON o.id_form_grading = f.id
            LEFT JOIN REG_TIME_GRADING t ON o.id_time_grading = t.id
            LEFT JOIN POST_IMG img ON img.id_opinion_response = o.id AND img.type_opinion = 1
            LEFT JOIN VIEW_POST_STATS ps ON ps.post_id = o.id
            ORDER BY o.id DESC
            LIMIT :limits OFFSET :offset",[':limits'=>$limit,":offset"=>$offset]);
    }
    
    $html = "";

    foreach ($opinions as $value) {
        $anonimo = $value['anonymous'] == 1 ? "Opinión anónima" : ($value['username'] ? ("Aportado por: <a href='user?id=".$value['id_user']."' class='link-user'>".$value['username']."</a>") : "Aportado por: ?");

        if($value['user_liked'] == "1"){
            $likefield = '<a onclick="toggleLike(this,'.$value['id'].',\'dislike\',\'post\')" class="text-primary like active"><i class="fas fa-thumbs-up me-1"></i><span>'.$value['likes'].'</span></a>';
        }else{
            $likefield = '<a onclick="toggleLike(this,'.$value['id'].',\'like\',\'post\')" class="text-primary like"><i class="fas fa-thumbs-up me-1"></i><span>'.$value['likes'].'</span></a>';
        }
        $actions = '<div class="d-flex gap-2 align-items-center">
                        <span class="text-primary">#'.$value['id'].'</span>
                    </div>';
        if (isset($_SESSION['PSESSION']) && ($_SESSION['PSESSION']['id'] == $value['id_user'] || $_SESSION['PSESSION']['id_role'] <= 2)) {
            $actions = '<div class="d-flex gap-2 align-items-center">
                            <span class="text-primary">#'.$value['id'].'</span>
                            <span>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-muted-v2" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end actions-opinion">
                                        <li><a class="dropdown-item" onclick="editOpinionModalText('.$value['id'].')" data-bs-toggle="modal" data-bs-target="#editOpinionModal"><i class="fa-solid fa-pen"></i> Editar</a></li>
                                        <li><a class="dropdown-item text-danger" onclick="deleteOpinionModalText('.$value['id'].')" data-bs-toggle="modal" data-bs-target="#deleteOpinionModal"><i class="fa-solid fa-trash text-danger"></i> Eliminar</a></li>
                                    </ul>
                                </div>
                            </span>
                        </div>';
        }
        

        $html .= '
            <div class="col-12">
                <div class="d-flex flex-start mb-4 flex-column flex-lg-row justify-content-center justify-content-lg-start">
                    <div class="icon-primary text-center icon-comment">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>
                    <div class="card w-100">
                        <div class="card-body p-4">
                            <div class="">
                                <div class="d-flex justify-content-between">
                                    <h5>Opinando sobre: '.$value['teacher'].'</h5>
                                    '.$actions.'
                                </div>
                                <div class="d-flex flex-column gap-1 pb-3">
                                    <span class="small">Escuela: '.$value['school'].'</span>
                                    <span class="small text-muted">'.$anonimo.'</span>
                                </div>
                                <p>
                                '.$value['opinion'].'
                                </p>

                                <div class="mb-3 d-flex flex-column gap-1">
                                    <div>
                                        <i class="fa-solid fa-bookmark me-1"></i> <span class="text-primary">Asignatura: </span><span>'.$value['subject'].'</span>
                                    </div>
                                    <div>
                                        <i class="fa-solid fa-book me-1"></i> <span class="text-primary">Forma de calificar: </span><span>'.$value['form_grading'].'</span>
                                    </div>
                                    <div>
                                        <i class="fa-solid fa-hourglass-start me-1"></i> <span class="text-primary">Tiempo de calificar: </span><span>'.$value['time_grading'].'</span>
                                    </div>
                                    <div>
                                        <i class="fa-solid fa-hand me-1"></i><span class="text-primary">Accesibilidad: </span><span>'.$value['accessibility'].'</span>
                                    </div>
                                </div>';
                    if(isset($value['num_img'])){
                        $html .= '<div class="d-flex flex-column gap-2">
                                        <span class="text-primary">Imagenes: </span>
                                        <div class="d-flex flex-wrap gap-2">';
                                        
                                        $nameImages = explode(",",$value['img']);
                                        for ($i=0; $i < $value['num_img']; $i++) {
                                            $html .= '<a class="op-img" data-bs-toggle="modal" data-bs-target="#opImg" onclick="ImagePostModal('.$value['id'].',\''.$nameImages[$i].'\')"><img src="./assets/img/posts/'.$value['id'].'/'.$nameImages[$i].'" width="100px"></a>';
                                        }
                                        
                        $html .= '</div>
                                </div>
                                <hr>';
                        }

                    $html .= '  <div class="d-flex justify-content-between align-items-start flex-column flex-lg-row gap-1 gap-lg-3">
                                    <div class="d-flex gap-1 gap-lg-3 flex-column flex-lg-row">
                                        <div class="text-muted dateFormat">'.$value['timestamp_create'].'</div>
                                        <div class="d-flex align-items-center">
                                            '.$likefield.'
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <a class="text-primary" data-bs-toggle="collapse" data-bs-target="#responses'.$value['id'].'" onclick="showResponses('.$value['id'].');"><i class="fa-solid fa-comment me-1"></i> <span id="numResponses'.$value['id'].'">'.$value['responses'].'</span> [Ver/Ocultar]</a>
                                        </div>
                                    </div>
                                    <a data-bs-toggle="collapse" data-bs-target="#formresponse'.$value['id'].'" class="text-primary"><i class="fas fa-reply me-1"></i> Responder</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

            $html .= '<div class="comment-reply collapse mb-4" id="formresponse'.$value['id'].'">
                        <div class="d-flex flex-start">
                            <div class="icon-primary">
                                <i class="fa-solid fa-circle-plus"></i>
                            </div>
                            <div class="card w-100">
                                <div class="p-3 pb-1 text-primary d-flex justify-content-between">
                                    <p>Responder a #'.$value['id'].'</p>
                                </div>
                                <div class="card-body p-4 pt-0">
                                    <div>
                                        <form method="post" id="responseForm'.$value['id'].'" class="responseForm" autocomplete="off" enctype="multipart/form-data" data-id="'.$value['id'].'">
                                            <div class="mb-3 mt-3">
                                                <label for="comment" class="mb-2">Respuesta:</label>
                                                <textarea class="form-control" rows="8" name="opinion" placeholder="Respuesta a la opinión.."></textarea>
                                            </div>
                                            <div class="mb-3 mt-3">
                                                <label for="img" class="form-label">Imagenes: (Selecciona varias imagenes con ctrl + click)</label>
                                                <input type="file" class="form-control img" name="img" onchange="handleFileImages(this.files, \'previewImage'.$value['id'].'\',\'response\',\'post\')" multiple>
                                                <div id="previewImage'.$value['id'].'" class="d-flex gap-2 flex-wrap py-3"></div>
                                            </div>
                                            <div class="form-check mb-3">
                                                <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="anonymous"> Responder de forma anónima
                                                </label>
                                            </div>
                                            <input type="hidden" name="id_opinion" value="'.$value['id'].'">
                                            <div class="text-center">
                                                <button class="btn btn-primary mt-3 d-flex gap-2 align-items-center" type="submit" onclick="sendFormResponse('.$value['id'].',event)"><div class="spinner-border spinner-border-sm btn-load"></div><span>Enviar respuesta</span></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';

            $html .= '<div class="comment-reply collapse mb-4" id="responses'.$value['id'].'">
                        <div id="responses'.$value['id'].'-content"></div>
                    </div>';    
                        
            $html .= '<hr></div>';
    }
    echo json_encode($html);
}

function loadResponses(){
    $data = getPostData();
    $db = new QueryModel();
    $limit = $data['limit'] ?? 15;
    $offset = $data['offset'] ?? 0;
    $id = $data['id'];
    $iduser = $_SESSION['PSESSION']['id'];

    $responses = $db->query("SELECT r.*,u.username,img.img,img.num_img,rs.num_likes likes,
        IF(rl.id_opinion_response IS NOT NULL, 1, 0) AS user_liked
        FROM POST_RESPONSE r
        LEFT JOIN SYS_USER u ON r.id_user = u.id
        LEFT JOIN POST_IMG img ON img.id_opinion_response = r.id AND img.type_opinion = 2
        LEFT JOIN VIEW_RESPONSE_STATS rs ON rs.response_id = r.id
        LEFT JOIN REL_LIKES rl ON r.id = rl.id_opinion_response AND rl.type_opinion = 2 AND rl.id_user = :id_user
        WHERE r.id_opinion = :id
        ORDER BY r.id DESC
        LIMIT :limits OFFSET :offset",[':id'=>$id,":id_user"=>$iduser,':limits'=>$limit,":offset"=>$offset]);
    
    $html = "";
    
    foreach ($responses as $value) {
        if($value['user_liked'] == "1"){
            $likefield = '<a onclick="toggleLike(this,'.$value['id'].',\'dislike\',\'response\')" class="text-primary me-2 like active"><i class="fas fa-thumbs-up me-1"></i>'.$value['likes'].'</a>';
        }else{
            $likefield = '<a onclick="toggleLike(this,'.$value['id'].',\'like\',\'response\')" class="text-primary me-2 like"><i class="fas fa-thumbs-up me-1"></i>'.$value['likes'].'</a>';
        }
        $anonimo = $value['anonymous'] == 1 ? "anonimo" : "<a href='user?id=".$value['id_user']."' class='link-user'>".$value['username']."</a>";

        $actions = '';
        if (isset($_SESSION['PSESSION']) && ($_SESSION['PSESSION']['id'] == $value['id_user'] || $_SESSION['PSESSION']['id_role'] <= 2)) {
            $actions = '<span>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-muted-v2" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end actions-opinion">
                                        <li><a class="dropdown-item" onclick="editResponseModalText('.$value['id'].','.$value['id_opinion'].')" data-bs-toggle="modal" data-bs-target="#editResponseModal"><i class="fa-solid fa-pen"></i> Editar</a></li>
                                        <li><a class="dropdown-item text-danger" onclick="deleteResponseModalText('.$value['id'].','.$value['id_opinion'].')" data-bs-toggle="modal" data-bs-target="#deleteResponseModal"><i class="fa-solid fa-trash text-danger"></i> Eliminar</a></li>
                                    </ul>
                                </div>
                            </span>';
        }

        $html .= '
            <div class="d-flex flex-start mb-3">
                <div class="icon-primary">
                    <i class="fa-solid fa-reply"></i>
                </div>
                <div class="card w-100">
                    <div class="p-3 pb-1 text-primary d-flex justify-content-between">
                        <p class="title">Respuesta de '.$anonimo.' a #'.$value['id_opinion'].'</p>
                        <div class="d-flex gap-2 align-items-center">
                            <span>#'.$value['id_opinion'].'#'.$value['id'].'</span>
                            '.$actions.'
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="">
                            <p>
                            '.$value['opinion'].'
                            </p>';

                    if(isset($value['num_img'])){
                        $html .= '<div class="d-flex flex-column gap-2">
                                        <span class="text-primary">Imagenes: </span>
                                        <div class="d-flex flex-wrap gap-2">';
                                        
                                        $nameImages = explode(",",$value['img']);
                                        for ($i=0; $i < $value['num_img']; $i++) {
                                            $html .= '<a class="op-img" data-bs-toggle="modal" data-bs-target="#resImg" onclick="ImageResponseModal('.$value['id_opinion'].','.$value['id'].',\''.$nameImages[$i].'\')"><img src="./assets/img/responses/'.$value['id'].'/'.$nameImages[$i].'" width="100px"></a>';
                                        }
                                        
                        $html .= '</div>
                                </div>
                                <hr>';
                    }

                    $html .= '<div class="d-flex justify-content-between align-items-start flex-column flex-lg-row gap-1 gap-lg-3">
                                <div class="d-flex gap-1 gap-lg-3 flex-column flex-lg-row">
                                    <div class="text-muted dateFormat">'.$value['timestamp_create'].'</div>
                                    <div class="d-flex align-items-center">
                                        '.$likefield.'
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }
    if($responses) {
        $html .= '<div class="text-center p-4"><button class="btn btn-muted" id="show-more-responses'.$value['id'].'" onclick="loadMoreResponses('.$value['id'].')">Ver más</button></div>';
    }
    
    echo json_encode($html);
    
}

function postResponse(){
    $data = $_POST;
    $db = new QueryModel();

    if(isset($data['anonymous']) && $data['anonymous'] == "on"){
        $data['anonymous'] = 1;
    }

    $fields = dataForInsert($data);

    $row = "no data";
    if (isset($fields['setParams'])) {
        $setQueryParams = implode(',', $fields['setParams']);
        $setQueryValues = implode(',', $fields['setValues']);
        $params = $fields['params'];
        $row = $db->query("INSERT INTO POST_RESPONSE($setQueryParams) VALUES($setQueryValues)",$params);
    }

    if($_FILES && $_FILES['img'] && $_FILES['img']['size'] != 0){
        $numFiles = is_array($_FILES['img']['name']);
        $numImg = 0;

        $idResponse = $db->lastid();
        $ruta = '../../../assets/img/responses/'.$idResponse.'/';
        if($numFiles){
            $img = createMultiFiles('img',$ruta,"",0);
            $numImg = count($_FILES['img']['name']);
            $img = call_user_func_array('array_merge', $img);
            $nameImgs = implode(',', $img);
            $queryimg = $db->query("INSERT INTO POST_IMG(img,type_opinion,id_opinion_response,num_img) VALUES(:img,2,:id_opinion,:num_img)",[":img"=>$nameImgs,":id_opinion"=>$idResponse,":num_img"=>$numImg]);
        }else{
            $img = createFile('img',$ruta,"",0);
            $numImg = 1;
            $img = call_user_func_array('array_merge', $img);
            $queryimg = $db->query("INSERT INTO POST_IMG(img,type_opinion,id_opinion_response,num_img) VALUES(:img,2,:id_opinion,1)",[":img"=>$img,":id_opinion"=>$idResponse]);
        }
        if($img == 6){
            echo 6;
            return;
        }
        
        $row = $db->update("POST_RESPONSE",["num_img"=>$numImg],"id = $idResponse");
    }
    if($row == []){
        echo 1;
    }else{
        echo json_encode($row);
    }
    
}

function like($type = 1){
    $data = getPostData();
    $id = $data['id'];
    if(isset($_SESSION['PSESSION'])){
        $db = new QueryModel();
        $iduser = $_SESSION['PSESSION']['id'];
        $searchlike = $db->select("REL_LIKES","id_user = $iduser AND type_opinion = $type AND id_opinion_response = $id");
        if(!$searchlike){
            $row = $db->query("INSERT INTO REL_LIKES(id_user,type_opinion,id_opinion_response) VALUES(:id_user,:type,:id_opinion)",[':id_user'=>$iduser,":type"=>$type,":id_opinion"=>$id]);
            echo json_encode($row);
        }else{
            echo json_encode("Ya has dado like");
        }
    }else{
        echo 3;
    }
    
    
}

function dislike($type = 1){
    $data = getPostData();
    $id = $data['id'];
    if(isset($_SESSION['PSESSION'])){
        $db = new QueryModel();
        $iduser = $_SESSION['PSESSION']['id'];
        $row = $db->delete("REL_LIKES","id_user = $iduser AND type_opinion = $type AND id_opinion_response = $id");
        echo json_encode($row);
    }else{
        echo 3;
    }
}
