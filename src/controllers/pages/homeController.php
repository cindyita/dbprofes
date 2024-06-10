<?php
require_once '../../../commons.php';
use ModelsNS\QueryModel;
session_start();

if (!empty(getView())) {
    switch (getView()) {
        case "POST":
            postOpinion();
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

    if($_FILES['img']){
        $numFiles = is_array($_FILES['img']['name']);
        $numImg = 0;

        $idPost = $db->lastid();
        $ruta = '../../../assets/img/posts/'.$idPost.'/';
        if($numFiles){
            $img = createMultiFiles('img',$ruta,"",0);
            $numImg = count($_FILES['img']['name']);
            $nameImgs = implode(',', $img);
            $queryimg = $db->query("INSERT INTO POST_IMG(img,type_opinion,id_opinion_response,num_img) VALUES(:img,1,:id_opinion,:num_img)",[":img"=>$nameImgs,":id_opinion"=>$idPost,":num_img"=>$numImg]);
        }else{
            $img = createFile('img',$ruta,"",0);
            $numImg = 1;
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

function loadOpinions(){
    $data = getPostData();
    $limit = $data['limit'] ?? 15;
    $offset = $data['offset'] ?? 0;
    $db = new QueryModel();

    if(isset($_SESSION['PSESSION'])){
        $iduser = $_SESSION['PSESSION']['id'];
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
        $anonimo = $value['anonymous'] == 1 ? "Opinión anónima" : "Aportado por: <a href='#' class='link-user'>".$value['username']."</a>";

        if($value['user_liked'] == "1"){
            $likefield = '<a onclick="toggleLike(this,'.$value['id'].',\'dislike\',\'post\')" class="text-primary like active"><i class="fas fa-thumbs-up me-1"></i><span>'.$value['likes'].'</span></a>';
        }else{
            $likefield = '<a onclick="toggleLike(this,'.$value['id'].',\'like\',\'post\')" class="text-primary like"><i class="fas fa-thumbs-up me-1"></i><span>'.$value['likes'].'</span></a>';
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
                                    <span class="text-primary">#'.$value['id'].'</span>
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
                                                <input type="file" class="form-control img" name="img" onchange="handleFileImages(this.files, \'previewImage'.$value['id'].'\')" multiple>
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
        $anonimo = $value['anonymous'] == 1 ? "anonimo" : "<a href='#' class='link-user'>".$value['username']."</a>";
        $html .= '
            <div class="d-flex flex-start mb-3">
                <div class="icon-primary">
                    <i class="fa-solid fa-reply"></i>
                </div>
                <div class="card w-100">
                    <div class="p-3 pb-1 text-primary d-flex justify-content-between">
                        <p class="title">Respuesta de '.$anonimo.' a #'.$value['id_opinion'].'</p>
                        <span>#'.$value['id_opinion'].'#'.$value['id'].'</span>
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

    if($_FILES['img']){
        $numFiles = is_array($_FILES['img']['name']);
        $numImg = 0;

        $idResponse = $db->lastid();
        $ruta = '../../../assets/img/responses/'.$idResponse.'/';
        if($numFiles){
            $img = createMultiFiles('img',$ruta,"",0);
            $numImg = count($_FILES['img']['name']);
            $nameImgs = implode(',', $img);
            $queryimg = $db->query("INSERT INTO POST_IMG(img,type_opinion,id_opinion_response,num_img) VALUES(:img,2,:id_opinion,:num_img)",[":img"=>$nameImgs,":id_opinion"=>$idResponse,":num_img"=>$numImg]);
        }else{
            $img = createFile('img',$ruta,"",0);
            $numImg = 1;
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
