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
        $queryimg = $db->query("INSERT INTO POST_IMG(img,type_opinion,id_opinion_response) VALUES(:)",$params);
        
        $row = $db->update("POST_OPINION",["num_img"=>$numImg],"id = $idPost");
    }

    echo json_encode($row);
}

function loadOpinions(){
    $data = getPostData();
    $limit = $data['limit'] ?? 15;
    $offset = $data['offset'] ?? 0;
    $db = new QueryModel();

    $opinions = $db->query("SELECT o.*,u.username,a.name accessibility,f.name form_grading,t.name time_grading 
        FROM POST_OPINION o 
        LEFT JOIN SYS_USER u ON o.id_user = u.id
        LEFT JOIN REG_ACCESSIBILITY a ON o.id_accessibility = a.id
        LEFT JOIN REG_FORM_GRADING f ON o.id_form_grading = f.id
        LEFT JOIN REG_TIME_GRADING t ON o.id_time_grading = t.id
        ORDER BY o.id DESC
        LIMIT :limits OFFSET :offset",[':limits'=>$limit,":offset"=>$offset]);

    $html = "";

    foreach ($opinions as $key => $value) {
        $anonimo = $value['anonymous'] == 1 ? "Opinión anónima" : "<a href='#'>".$value['username']."</a>";
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
                                </div>

                                <div class="d-flex flex-column gap-2">
                                    <span class="text-primary">Imagenes: </span>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a class="op-img" data-bs-toggle="modal" data-bs-target="#opImg"><img src="./assets/img/system/image404.png" width="120px"></a>
                                        <a class="op-img"><img src="./assets/img/system/image404.png" width="120px"></a>
                                    </div>
                                    
                                </div>

                                <hr>


                                <div class="d-flex justify-content-between align-items-start flex-column flex-lg-row gap-1 gap-lg-3">
                                    <div class="d-flex gap-1 gap-lg-3 flex-column flex-lg-row">
                                        <div class="text-muted dateFormat">'.$value['timestamp_create'].'</div>
                                        <div class="d-flex align-items-center">
                                            <a href="#!" class="text-primary"><i class="fas fa-thumbs-up me-1"></i>'.$value['likes'].'</a>
                                        </div>
                                        <div class="d-flex align-items-center" data-bs-toggle="collapse" data-bs-target="#comment'.$value['id'].'">
                                            <a href="#!" class="text-primary"><i class="fa-solid fa-comment me-1"></i> '.$value['responses'].' Respuesta [Ver/Ocultar]</a>
                                        </div>
                                    </div>
                                    <a href="#" class="text-primary"><i class="fas fa-reply me-1"></i> Responder</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

            $html .= '
                    <div class="comment-reply collapse mb-4" id="comment'.$value['id'].'">
                            
                        <div class="d-flex flex-start">
                            <div class="icon-primary">
                                <i class="fa-solid fa-reply"></i>
                            </div>
                            <div class="card w-100">
                                <div class="p-3 pb-1 text-primary d-flex justify-content-between">
                                    <p>Respuesta anónima a #12</p>
                                    <span>#12#1</span>
                                </div>
                                <div class="card-body p-4 pt-0">
                                    <div class="">
                                        <p>
                                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Delectus
                                        cumque doloribus dolorum dolor repellat nemo animi at iure autem fuga
                                        cupiditate architecto ut quam provident neque, inventore nisi eos quas?
                                        </p>

                                        <div class="d-flex justify-content-between align-items-start flex-column flex-lg-row gap-1 gap-lg-3">
                                            <div class="d-flex gap-1 gap-lg-3 flex-column flex-lg-row">
                                                <div class="text-muted">
                                                    Hace 3 horas
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <a href="#!" class="text-primary me-2"><i class="fas fa-thumbs-up me-1"></i>132</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    ';
                

            $html .= '</div>';
    }
    echo json_encode($html);
}
