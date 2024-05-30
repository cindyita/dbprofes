$(function () {

    $("#username").on("focusout", function () {
        checkUsername();
    });

    $("#updateProfile").on("submit", async function (event) {
        event.preventDefault();
        $("#loader-btn").show();
        var usernameValid = await checkUsername();
        var formData = new FormData($("#updateProfile")[0]);
        if (usernameValid) {
            sendAjaxForm(formData, 'UPDATE').then(
                function (res) {
                    console.log(res);
                    res = JSON.parse(res);
                    if (res == 1) {
                        message("Se ha actualizado tu perfil", "success");
                        $("#mode1-username").text($("#username").val());
                        $("#mode1-biography").html($("#biography").val());
                        $("#loader-btn").hide();
                        modeNormal();
                    } else {
                        message("Algo salió mal", "error");
                        console.log(res);
                    }
                    
                }).catch(function (error) {
                    message("Algo salió mal", "error");
                    console.error(error);
            });
        } else {
            message("Error en los campos","error");
        }
    });

});
function modeEdit() {
    $("#mode1").hide();
    $("#mode2").show();
}
function modeNormal() {
    $("#mode1").show();
    $("#mode2").hide();
}

async function checkUsername() {
    var username = $("#username").val();
    var actualusername = $("#actual-username").val();
    if (username != actualusername) {
        try {
            var res = await sendAjax({ username: username }, 'CHECKUSERNAME');
            res = JSON.parse(res);
            if (res != "false") {
                console.log("Username error");
                message("Este nombre de usuario ya está en uso","error");
                $("#username").val(actualusername);
                return false;
            } else {
                return true;
            }
        } catch (error) {
            console.error(error);
            message("Algo salió mal","error");
            return false;
        }
    } else {
        return true;
    }
    
}