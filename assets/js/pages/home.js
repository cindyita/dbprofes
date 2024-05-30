let currentOffset = 0;
const limit = 15;
$(function () {

    realodOpinions();

    $("#show-more").on("click", function() {
        currentOffset += limit;
        realodOpinions(1, true);
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

});

async function realodOpinions(type = 1, append = false) {
    $("#btn-show-more").hide();
    if (!append) {
        $("#show-opinions").html('<div class="spinner-border text-muted"></div>');
    } else {
        $("#show-more").html('<div class="spinner-border text-muted"></div>');
    }

    try {
        var res = await sendAjax({ type: type,limit: limit, offset: currentOffset }, 'LOADOPINIONS');
        const opinions = JSON.parse(res);
        if (opinions != 0) {
            console.log("Se han cargado las opiniones");
            if (append) {
                $("#show-opinions").append(opinions);
            } else {
                $("#show-opinions").html(opinions);
            }
            $("#btn-show-more").show();
            dateFormatAll();
        } else {
            $("#show-more").hide();
        }
        $("#show-more").html('Ver más');
    } catch (error) {
        console.error(error);
        message("Algo salió mal","error");
        return false;
    }
}
