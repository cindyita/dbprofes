$(function () {
    
    $("#login").on("submit", function (event) {
        event.preventDefault();
        loadBtn("btnSend");
        var formData = new FormData($("#login")[0]);
        if ($("#username").val() != "" && $("#pass").val() != "") {
            sendAjaxForm(formData, 'LOGIN').then(
                function (res) { 
                    if (processError(res)) {
                        message("Has iniciado sesión correctamente", "success");
                        $("#login")[0].reset();
                        window.location.href = "home";
                    }
                    grecaptcha.reset();
                    unLoadBtn("btnSend",'Iniciar sesión');
                }).catch(function (error) {
                    message("Algo salió mal", "error");
                    console.error(error);
            });
        } else {
            message("Debes rellenar los campos","error");
        }
    });

});