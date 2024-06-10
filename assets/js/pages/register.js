$(function () {

    $("#email").on("focusout", function () {
        checkEmail();
    });

    $("#username").on("focusout", function () {
        checkUsername();
    });

    $("#pass").on("focusout", function () {
        checkPass();
    });

    $("#cpass").on("focusout", function () {
        checkCPass();
    });

    $("#register").on("submit", async function (event) {
        event.preventDefault();
        loadBtn("btnSend");
        var emailValid = await checkEmail();
        var usernameValid = await checkUsername();
        var pass = checkPass();
        var cpass = checkCPass();
        var formData = new FormData($("#register")[0]);
        if (emailValid && usernameValid && pass && cpass) {
            sendAjaxForm(formData, 'REGISTER').then(
                function (res) {
                    res = JSON.parse(res);
                    if (res == "[]") {
                        message("Te has registrado con éxito", "success");
                        $("#register")[0].reset();
                        window.location.href = "login";
                    } else {
                        message("Algo salió mal", "error");
                        console.log(res);
                    }
                    grecaptcha.reset();
                    unLoadBtn("btnSend","Registrarse");
                }).catch(function (error) {
                    message("Algo salió mal", "error");
                    console.error(error);
            });
        } else {
            message("Error en los campos","error");
        }
    });

});

async function checkEmail() {
    var email = $("#email").val();
    try {
        var res = await sendAjax({ email: email }, 'CHECKEMAIL');
        res = JSON.parse(res);
        if (res != "false") {
            console.log("Email error");
            message("Este email ya está en uso","error");
            $("#email").val("");
            return false;
        } else {
            return true;
        }
    } catch (error) {
        console.error(error);
        message("Algo salió mal","error");
        return false;
    }
}

async function checkUsername() {
    var username = $("#username").val();
    try {
        var res = await sendAjax({ username: username }, 'CHECKUSERNAME');
        res = JSON.parse(res);
        if (res != "false") {
            console.log("Username error");
            message("Este nombre de usuario ya está en uso","error");
            $("#username").val("");
            return false;
        } else {
            return true;
        }
    } catch (error) {
        console.error(error);
        message("Algo salió mal","error");
        return false;
    }
}

function checkPass() {
    var pass = $("#pass").val();
    if (pass == "") {
        message("Por favor, llene los campos","error");
        return false;
    }
    if (!checkPattern(pass)) {
        var msg = 'La contraseña debe contener:<ul>' +
            '<li>Un mínimo de 6 carácteres</li>' +
            '<li>1 letra minúscula</li>' +
            '<li>1 letra mayúscula</li>' +
            '<li>1 carácter numérico</li>' +
            '</ul>';
        message(msg, "error");
        return false;
    }
    return true;
}

function checkCPass() {
    var pass = $("#pass").val();
    var cpass = $("#cpass").val();
    if (pass == "" || cpass == "") {
        message("Por favor, llene los campos","error");
        return false;
    }
    if (pass != cpass) {
        message("Las contraseñas deben coincidir","error");
        return false;
    }
    return true;
}

function checkPattern(str) {
    var reg = /^(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
    return reg.test(str);
}