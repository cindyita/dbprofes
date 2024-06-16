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
let imagesOpinion = {};
let imagesResponse = {};
let imagesEditOpinion = {};
let imagesEditResponse = {};

$(function () {
    
    $("#opiniontext, #editOpinionModal-opinion").summernote({
        placeholder: "Asegurate de mencionar datos que puedan ser útiles para otros estudiantes",
        tabsize: 5,
        height: 150,
        lang: 'es-ES',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'italic', 'clear', 'superscript']],
            ['para', ['ul', 'ol']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view',['fullscreen','codeview','undo', 'redo']]
        ],
        disableDragAndDrop: true
    });

    if (!isEmpty(queryParams)) {
        realodOpinions(1, false, queryParams['type'], queryParams['text']);
        $("#title-opinions").html("Resultados de la búsqueda <span style='color:darkred;'>" + queryParams['text'] + "</span> para <span style='color:darkred;'>" + typesearchs[queryParams['type']] + "</span>");
        if (queryParams['type'] == 'my') {
            $("#title-opinions").html("<span>Viendo: <span style='color:darkred;'>Mis opiniones</span></span>");
        }
            
        if (queryParams['type'] == 'my') {
            
        }
        
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

    $("#opinionForm").on("submit", async function (event) {
        event.preventDefault();
        $("#opinionForm .btn-load").show();
        var formData = new FormData($("#opinionForm")[0]);
        // if ($("#img")[0].files[0]) {
        //     var file = $("#img")[0].files[0];
        //     formData.append('file', file);
        // }
        if ($("#img")[0].files[0]) {
            for (var key in imagesOpinion) {
                formData.append('img[]', imagesOpinion[key]);
            }
        }
        images = {};
        sendAjaxForm(formData, 'POST').then(
            function (res) {
                console.log(res);
                res = JSON.parse(res);
                if (res == 1) {
                    message("Se ha agregado tu opinión", "success");

                    $("#id_form_grading").val(4);
                    $("#id_time_grading").val(3);
                    $("#id_accessibility").val(3);
                    $(".note-editable").html("");
                    $("#opiniontext").val("");
                    $("#preview").html("");
                    $("#opinionForm")[0].reset();

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

    $("#deleteOpinionModalForm").on("submit", function (event) {
        event.preventDefault();
        var form = this;
        loadBtnForm(form);
        var formData = new FormData($(this)[0]);
        sendAjaxForm(formData, 'DELETE').then(
            function (res) {
                if (res == 1) {
                    message("Se eliminó la opinión", "success");
                    realodOpinions();
                }
                unLoadBtnForm(form);
            }).catch(function (error) {
                message("Algo salió mal", "error");
                console.error(error);
        });
    });

    $("#editOpinionForm").on("submit", function (event) {
        event.preventDefault();
        var form = this;
        loadBtnForm(form);
        var formData = new FormData($("#editOpinionForm")[0]);
        
        if ($("#editOpinionModal-img")[0].files[0]) {
            for (var key in imagesEditOpinion) {
                formData.append('img[]', imagesEditOpinion[key]);
            }
        }

        sendAjaxForm(formData, 'UPDATE').then(
            function (res) {
                console.log(res);
                if (res == 1) {
                    message("Se actualizó la opinión", "success");
                    realodOpinions();
                }
                unLoadBtnForm(form);
            }).catch(function (error) {
                message("Algo salió mal", "error");
                console.error(error);
        });
    });

    $("#editResponseForm").on("submit", function (event) {
        event.preventDefault();
        var form = this;
        var id_opinion = $("#editResponseModal-id_opinion").val();
        loadBtnForm(form);
        var formData = new FormData($("#editResponseForm")[0]);
        if ($("#editResponseModal-img")[0].files[0]) {
            for (var key in imagesEditResponse) {
                formData.append('img[]', imagesEditResponse[key]);
            }
        }

        sendAjaxForm(formData, 'UPDATERESPONSE').then(
            function (res) {
                console.log(res);
                if (res == 1) {
                    message("Se actualizó la respuesta", "success");
                    showResponses(id_opinion);
                }
                unLoadBtnForm(form);
            }).catch(function (error) {
                message("Algo salió mal", "error");
                console.error(error);
        });
    });

    $("#deleteResponseModalForm").on("submit", function (event) {
        event.preventDefault();
        var form = this;
        var id_opinion = $("#deleteResponseModal-id_opinion").val();
        loadBtnForm(form);
        var formData = new FormData($(this)[0]);
        sendAjaxForm(formData, 'DELETERESPONSE').then(
            function (res) {
                if (res == 1) {
                    message("Se eliminó la respuesta", "success");
                    $("#numResponses" + id_opinion).text(parseInt($("#numResponses" + id_opinion).text()) -1);
                    showResponses(id_opinion);
                }
                unLoadBtnForm(form);
            }).catch(function (error) {
                message("Algo salió mal", "error");
                console.error(error);
        });
    });

    $("#id_form_grading").val(4);
    $("#id_time_grading").val(3);
    $("#id_accessibility").val(3);

});

function sendFormResponse(id,event) {
    event.preventDefault();
    loadBtn("op-btnSend");
    var formData = new FormData($("#responseForm"+id)[0]);
    // if ($("#responseForm"+id+" .img")[0].files[0]) {
    //     var file = $("#responseForm"+id+" .img")[0].files[0];
    //     formData.append('file', file);
    // }
    if ($("#responseForm" + id + " .img")[0].files[0]) {
        for (var key in imagesResponse) {
            formData.append('img[]', imagesResponse[key]);
        }
    }
    images = {};
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

function editOpinionModalText(id) {
    $("#editOpinionModal-idText").text(id);
    $("#editOpinionModal-id").val(id);
    sendAjax({id: id}, 'GET').then(
        function (res) {
            var data = JSON.parse(res);
            $("#editOpinionForm")[0].reset();
            transposeData('editOpinionModal', data, true);
            $("#editOpinionModal .note-placeholder").html("");
            $("#editOpinionModal .note-editable").html(data['opinion']);
            if (data['anonymous'] == 1) {
                $("#editOpinionModal-anonymous").prop("checked", true);
            } else {
                $("#editOpinionModal-anonymous").prop("checked", false);
            }
            images = {};
            $("#editOpinionModal-preview").html("");
            $("#editOpinionModal-img").val("");
            if (data['img'] != null) {
                var imgs = data['img'].split(",");
                imgs.forEach(element => {
                    var img = $("<img />", {
                        src: './assets/img/posts/' + data['id'] + '/' + element,
                        class: "img-preview",
                        width: "120px"
                    });
                    var imgContainer = $("<div></div>", {
                        class: "img-container"
                    });
                    imgContainer.append(img);
                    $("#editOpinionModal-preview").append(imgContainer);
                });
            }
        }).catch(function (error) {
            console.error(error);
        });
}

function deleteOpinionModalText(id) {
    $("#deleteOpinionModal-idText").text(id);
    $("#deleteOpinionModal-id").val(id);
}

function editResponseModalText(id,id_opinion) {
    $("#editResponseModal-idText").text('#'+id_opinion+'#'+id);
    $("#editResponseModal-id").val(id);
    sendAjax({id: id}, 'GETRESPONSE').then(
        function (res) {
            var data = JSON.parse(res);
            $("#editResponseForm")[0].reset();
            transposeData('editResponseModal', data, true,false);
            // $("#editResponseModal .note-placeholder").html("");
            // $("#editResponseModal .note-editable").html(data['Response']);
            if (data['anonymous'] == 1) {
                $("#editResponseModal-anonymous").prop("checked", true);
            } else {
                $("#editResponseModal-anonymous").prop("checked", false);
            }
            images = {};
            $("#editResponseModal-preview").html("");
            $("#editResponseModal-img").val("");
            if (data['img'] != null) {
                var imgs = data['img'].split(",");
                imgs.forEach(element => {
                    var img = $("<img />", {
                        src: './assets/img/responses/' + data['id'] + '/' + element,
                        class: "img-preview",
                        width: "120px"
                    });
                    var imgContainer = $("<div></div>", {
                        class: "img-container"
                    });
                    imgContainer.append(img);
                    $("#editResponseModal-preview").append(imgContainer);
                });
            }
        }).catch(function (error) {
            console.error(error);
        });
}

function deleteResponseModalText(id,id_opinion) {
    $("#deleteResponseModal-idText").text('#'+id_opinion+'#'+id);
    $("#deleteResponseModal-id").val(id);
    $("#deleteResponseModal-id_opinion").val(id_opinion);
}
