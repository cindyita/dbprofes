let currentOffset = 0;
const limit = 15;
let responseOffset = 0;
const responseLimit = 15;
const queryParams = getQueryParams();
const typesearchs = {
    "teacher": "profesor",
    "subject": "asignatura",
    "school": "escuela"
};

$(function () {

    if (!isEmpty(queryParams)) {
        realodOpinions(1, false, queryParams['type'], queryParams['text']);
        $("#title-opinions").html("Resultados de la búsqueda <span style='color:darkred;'>" + queryParams['text'] + "</span> para <span style='color:darkred;'>" + typesearchs[queryParams['type']] + "</span>");
        $("#btn-deleteSearch").removeClass("d-none");
    } else {
        realodOpinions(1, false);
    }

    $("#show-more").on("click", function() {
        currentOffset += limit;
        const queryParams = getQueryParams();
        if (queryParams != {}) {
            realodOpinions(1, true, queryParams['type'],queryParams['text']);
        } else {
            realodOpinions(1, true);
        }
        
    });

    $("#id_form_grading").val(4);
    $("#id_time_grading").val(3);
    $("#id_accessibility").val(3);

    $("#opinionForm").on("submit", async function (event) {
        event.preventDefault();
        $("#opinionForm .btn-load").show();
        var formData = new FormData($("#opinionForm")[0]);
        if ($("#img")[0].files[0]) {
            var file = $("#img")[0].files[0];
            formData.append('file', file);
        }
        sendAjaxForm(formData, 'POST').then(
            function (res) {
                console.log(res);
                res = JSON.parse(res);
                if (res == 1) {
                    message("Se ha agregado tu opinión", "success");
                    $(this).reset();
                    $("#opinion").removeClass("show");
                    realodOpinions();
                } else if (res == 6) {
                    message("El tipo de archivo no está permitido", "error");
                } else {
                    message("Algo salió mal", "error");
                    console.log(res);
                }
                $("#opinionForm .btn-load").hide();
                
            }).catch(function (error) {
                message("Algo salió mal", "error");
                console.error(error);
                $("#opinionForm .btn-load").hide();
        });
    });

    // ENVIAR BÚSQUEDA
    $("#search-form").on("submit", async function (event) {
        event.preventDefault();
        const text = $("#search-text").val();
        const type = $("#search-type").val();
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?type=' + encodeURIComponent(type) + '&text=' + encodeURIComponent(text);
        window.history.pushState({ path: newUrl }, '', newUrl);
        var queryParams = getQueryParams();
        realodOpinions(1, false, queryParams['type'], queryParams['text']);

        $("#title-opinions").html("Resultados de la búsqueda <span style='color:darkred;'>" + queryParams['text'] + "</span> para <span style='color:darkred;'>" + typesearchs[queryParams['type']] + "</span>");
        $("#btn-deleteSearch").removeClass("d-none");
        console.log("Se realizó la búsqueda");
        
    });

});

function sendFormResponse(id,event) {
    event.preventDefault();
    loadBtn("op-btnSend");
    var formData = new FormData($("#responseForm"+id)[0]);
    if ($("#responseForm"+id+" .img")[0].files[0]) {
        var file = $("#responseForm"+id+" .img")[0].files[0];
        formData.append('file', file);
    }
    sendAjaxForm(formData, 'POSTRESPONSE').then(
        function (res) {
            console.log(res);
            data = JSON.parse(res);
            if (data == 1) {
                message("Se ha agregado tu respuesta", "success");
                $("#responseForm" + id)[0].reset();
                $("#numResponses" + id).text(parseInt($("#numResponses" + id).text()) + 1);
                showResponses(id);
                $("#formresponse" + id).removeClass("show");
            } else if (data == 6) {
                message("El tipo de archivo no está permitido", "error");
            } else {
                message("Algo salió mal", "error");
                console.log(data);
            }
            unLoadBtn("op-btnSend",'Enviar');
            
        }).catch(function (error) {
            message("Algo salió mal", "error");
            console.error(error);
            $("#responseForm"+id+" .btn-load").hide();
    });
}

async function realodOpinions(type = 1, append = false, typesearch = "", textsearch = "") {
    $("#btn-show-more").hide();
    if (!append) {
        $("#show-opinions").html('<div class="spinner-border text-muted"></div>');
    } else {
        $("#btn-show-more").show();
        $("#show-more").html('<div class="spinner-border text-muted"></div>');
    }

    try {
        var res = await sendAjax({ type: type, limit: limit, offset: currentOffset, typesearch: typesearch, textsearch: textsearch }, 'LOADOPINIONS');
        console.log(res);
        if (res == 4) {
            message("La búsqueda tiene carácteres inválidos", "error");
            $("#show-opinions").html("<span class='text-muted text-center'>Error en la búsqueda</span>");
            $("#search-form")[0].reset();
            return false;
        }
        const opinions = JSON.parse(res);
        if (opinions != "") {
            console.log("Se han cargado las opiniones");
            if (append) {
                $("#show-opinions").append(opinions);
            } else {
                $("#show-opinions").html(opinions);
            }
            $("#btn-show-more").show();
            dateFormatAll();
        } else {
            if (append) {
                $("#show-opinions").append("<span class='text-muted text-center'>No hay más opiniones</span>");
            } else {
                $("#show-opinions").html("<span class='text-muted text-center'>No hay opiniones</span>");
            }    
            $("#show-more").hide();
        }
        $("#show-more").html('Ver más');
        return true;
    } catch (error) {
        console.error(error);
        message("Algo salió mal","error");
        return false;
    }
}

function ImagePostModal(id, img) {
    $("#opImg-id").text(id);
    $("#opImg-img").html('<img src="./assets/img/posts/'+id+'/'+img+'" width="100%">');
}

function ImageResponseModal(idopinion,id, img) {
    $("#resImg-id").text('#'+idopinion+'#'+id);
    $("#resImg-img").html('<img src="./assets/img/responses/'+id+'/'+img+'" width="100%">');
}

function collapseResponsesShow(id) {
    var isShow = $("#responses" + id).hasClass("show");
    return isShow;
}

async function showResponses(id, append = false) {
    const responsesContainer = $("#responses" + id+"-content")

    if (!append) {
        responsesContainer.html('<div class="spinner-border text-muted"></div>');
    } else {
        $("#show-more-responses" + id).html('<div class="spinner-border text-muted"></div>');
    }

    try {
        
        var res = await sendAjax({ id: id, limit: responseLimit, offset: responseOffset }, 'LOADRESPONSES');
        res = JSON.parse(res);
        if (res) {
            if (append) {
                responsesContainer.append(res);
            } else {
                responsesContainer.html(res);
            }
            $("#show-more-responses" + id).html("Ver más");
            dateFormatAll();
        } else {
            $("#show-more-responses" + id).hide();
            if (!append) {
                responsesContainer.html("<span class='text-muted text-center'>No hay respuestas</span>");
            } else {
                responsesContainer.append("<span class='text-muted text-center'>No hay más respuestas</span>");
            }
            
        }
        console.log("Se han cargado las respuestas");
    } catch (error) {
        console.error(error);
        message("Algo salió mal", "error");
        return false;
    }
}

function loadMoreResponses(id) {
    currentOffset += limit;
    showResponses(id, true);
}

function toggleLike(element, id, typeLike = 'like', typePost = 'post') {
    console.log(`Se ha dado Like/dislike a un ${typePost}`);
    var numLikes = parseInt($(element).find("span").text());
    var functionAtr = "";
    
    var action = (typeLike + typePost).toUpperCase();
    sendAjax({ id: id }, action).then(function (res) {
        if (res == 3) {
            console.log("Necesitas estar logeado para dar like");
            return;
        }
        if (typeLike == 'like') {
            numLikes++;
            functionAtr = "toggleLike(this,"+id+",'dislike','" + typePost + "')";
            $(element).addClass("active");
        } else if(typeLike == 'dislike' && numLikes > 0) {
            numLikes--;
            $(element).removeClass("active");
            functionAtr = "toggleLike(this,"+id+",'like','"+typePost+"')";
        }
        $(element).find("span").text(numLikes);
        $(element).attr('onclick', functionAtr);
    }).catch(function (error) {
        console.error(`Error en like del tipo ${typePost}:`, error);
    });
}