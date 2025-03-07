/* The code is initializing two constants `ACTUALPAGE` and `CONTROLLER` by calling the functions `actualPage()` and `pageController()` respectively. */
const ACTUALPAGE = actualPage();
const CONTROLLER = pageController();

/* The code is using jQuery to select all elements with the class "page-overlay" and then calling the
`fadeOut()` method on them. This code is likely intended to fade out any elements with the
"page-overlay" class when the document is ready or when the DOM has finished loading. */
$(function () {
  $('body').prepend('<div id="message-container"></div>');
  var timeout = setTimeout(function() {
    alert('Parece que hay problemas de conexión. Intente recargar la página o regrese más tarde.');
  }, 8000);
  $(".page-overlay").fadeOut(function() {
    clearTimeout(timeout);
  });
  
});

/**
 * The function "message" displays a message on the webpage with a specified text and type, fading it
 * out after 4 seconds.
 * @param text - The `text` parameter is the message content that you want to display in the message
 * box. It can be any text or information that you want to communicate to the user.
 * @param [type=info] - The `type` parameter in the `message` function is used to specify the type of
 * message being displayed. By default, the type is set to "info", but it can be overridden with a
 * different type such as "success", "warning", or "error" depending on the message being displayed
 */
var typesAlert = { "error": "alert-danger", "info": "alert-info", "success": "alert-success", "warning": "alert-warning" };
var typesAlertText = { "error": "Error", "info": "Info", "success": "Éxito", "warning": "Aviso" };

function message(text, type = "info") {
  var html = '<div class="message"><div class="alert alert-dismissible '+typesAlert[type]+'"><button type="button" data-bs-dismiss="alert" class="close"><i class="fa-solid fa-xmark"></i></button><strong>'+typesAlertText[type]+':</strong> '+text+'</div></div>';
  var $message = $(html);
  $message.hide().appendTo('#message-container').fadeIn();
  setTimeout(function() {
      $message.fadeOut(function() {
          $(this).remove();
      });
  }, 6500);
}

/**
 * The function `processError` takes a response code as input and handles different error cases based
 * on the code.
 * @param res - The `res` parameter in the `processError` function is expected to be a number that
 * represents an error code.
 * @returns The function `processError` will return either `true` if the `res` value is 1, or `false`
 * if the `res` value is other value.
 */
function processError(res) {
  res = +res;
  switch (res) {
    case 1:
      return true;
    case 2:
      message("Los datos son incorrectos", "error");
      console.log("Error 2: Algunos datos son incorrectos");
      return false;
    case 3:
      message("Necesitas estar logeado para realizar esta acción", "error");
      console.log("Error 3: Necesitas estar logeado para realizar esta acción");
      return false;
    case 4:
      message("El captcha es inválido. Recarga la página.", "error");
      console.log("Error 4: El captcha es inválido");
      return false;
    case 5:
      message("Hay carácteres inválidos", "error");
      console.log("Error 4: Hay carácteres inválidos");
      return false;
    default:
      console.log("Error: "+res);
      return false;
  }
}

/**
 * The function `resSuccess` checks if the response is empty, a single value, a string, not set, or
 * does not contain a curly brace.
 * @param res - The `res` parameter seems to be a response object that is expected to be in JSON
 * format. The function `resSuccess` attempts to parse the `res` object as JSON and then checks if it
 * meets certain conditions to determine if the response is successful or not.
 * @returns The function `resSuccess` is checking if the input `res` is an empty array `[]`, the number
 * `1`, an empty string `""`, not set, or does not contain a curly brace `{`. If any of these
 * conditions are met, the function returns `true`, otherwise it returns `false`.
 */
function resSuccess(res){
  res = JSON.parse(res);
  if (res == [] || res == 1 || res == "" || !isset(res) || !res.infexOf("{") > -1) {
    return true;
  } else {
    return false;
  }
}

/**
 * The function `actualPage` extracts the name of the current page from the URL path.
 * @returns The function `actualPage()` returns the name of the current page without the file
 * extension.
 */
function actualPage() {
    var path = window.location.pathname;
    var pageName = path.split('/').pop().split('.').shift();
    return pageName;
}

/**
 * The function `pageController` returns the name of the controller file based on the current page's
 * URL path.
 * @returns homeController.php
 */
function pageController() {
    var path = window.location.pathname ? window.location.pathname : 'home.php';
    var pageName = path.split('/').pop().split('.').shift();
    pageName = pageName ? pageName : "home"
    var controllerName = pageName + "Controller.php";
    return controllerName;
}

/**
 * Performs an AJAX request using jQuery.ajax and returns a promise with the result.
 * @param {Object} data - The data to be sent in the AJAX request.
 * @param {string} action - The action to be performed in the server controller.
 * @returns {Promise} - A promise that resolves with the response data from the AJAX request or rejects with an error.
 */
function sendAjax(data, action) {
  return new Promise(function(resolve, reject) {
    $.ajax({
      url: './src/controllers/pages/' + CONTROLLER,
      type: 'POST',
      data: { data: data,'__view__':action },
      success: function(res) {
        resolve(res);
      },
      error: function(xhr) {
          console.error('Error en la solicitud. Código de estado: ' + xhr.status);
          message('Algo salió mal','error');
          reject('error');
      }
    });
  });
}

/**
 * The function sendAjaxForm sends an AJAX request to a specified URL with form data and returns a
 * promise that resolves with the response or rejects with an error message.
 * @param formData - The formData parameter is an object that contains the data to be sent in the AJAX
 * request. It can be in the form of a FormData object or a serialized string. This data will be sent
 * to the server-side script specified in the action parameter.
 * @param action - The "action" parameter is a string that represents the action to be performed by the
 * server-side code. It is typically used to determine which function or method to execute on the
 * server. In this case, it is appended to the URL as a query parameter in the AJAX request.
 * @returns a Promise object.
 */
function sendAjaxForm(formData, action) {
  formData.append("__view__", action);
  return new Promise(function(resolve, reject) {
      $.ajax({
          url: './src/controllers/pages/'+CONTROLLER,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(res) {
              resolve(res);
          },
          error: function(xhr) {
              console.error('Error en la solicitud. Código de estado: ' + xhr.status);
              message('error', 'Algo salió mal');
              reject('error');
          }
      });
  });
}

/**
 * Validates whether the given URL is valid.
 *
 * @param {string} url - The URL to be validated.
 * @returns {boolean} - True if the URL is valid, false otherwise.
 */
function validarURL(url) {
  var regexURL = /^(ftp|http|https):\/\/[^ "]+$/;
  if (regexURL.test(url)) {
    return true;
  } else {
    return false;
  }
}

/**
 * Copies the specified text to the clipboard.
 * @param {string} text - The text to be copied.
 */
function copyToClipboard(text) {
  navigator.clipboard.writeText(text)
    .then(function() {
      message('success', 'Enlace copiado al portapapeles');
    })
    .catch(function() {
      message('error', 'No se pudo copiar el enlace');
    });
}

/**
 * The function `transposeData` takes in a modal ID and data object, and updates the corresponding
 * elements in the modal with the values from the data object.
 * @param modalid - The `modalid` parameter is a string that represents the ID of a modal element in
 * the HTML document. This ID is used to select and manipulate elements within the modal.
 * @param data - The `data` parameter is an object that contains key-value pairs. Each key represents
 * the ID of an element in the HTML document, and the corresponding value represents the data that
 * should be assigned to that element.
 */
function transposeData(modalid, data, idModalInInput = false, idText = true) {
  console.log("Se han transpasado los datos al formulario");
    for (const key in data) {
        if (data.hasOwnProperty(key)) {
            const value = data[key];
            var element = $("#" + key);
            if (idModalInInput) {
                element = $("#" + modalid + "-" + key);
            }

            if (element.length > 0) {
                if (element.attr('type') !== 'file') {
                    if (element.is("input") || element.is("select") || element.is("textarea")) {
                      element.val(value);
                    } else {
                        element.html(value);
                    }
                }
            }
            if (idText) {
              if (key == 'id' && $("#" + modalid + "-" + key + "Text").length > 0) {
                $("#" + modalid + "-" + key + "Text").html(value);
              }
            }
            
        }
    }
}


/**
 * The function `handleFileImage` is used to handle and validate an image file, and display a preview
 * of the image.
 * @param files - An array of files that the user has selected. It should contain only one file.
 * @param previewId - The `previewId` parameter is the ID of the HTML element where you want to display
 * the preview of the selected image file.
 * @returns The function does not explicitly return anything.
 */
function handleFileImage(files, previewId) {
    const allowedExtensions = ["jpg", "jpeg", "png", "gif", "webp", "bmp", "tiff"];
    var preview = $("#" + previewId);
    var file = files[0];

    // Validations
    var maxSizeInBytes = 500 * 1024 * 1024; // 500 MB
    if (file.size > maxSizeInBytes) {
        message("error", "El archivo '" + file.name + "' excede el límite de tamaño permitido");
        return;
    }
    var fileExtension = file.name.split(".").pop().toLowerCase();
    if (allowedExtensions.indexOf(fileExtension) === -1) {
        message("error", "El archivo '" + file.name + "' tiene una extensión no permitida");
        return;
    }

    var reader = new FileReader();
    reader.onload = function (e) {
        preview.attr("src", e.target.result);
    };
    reader.readAsDataURL(file);
}

function handleFileImages(files, previewId,typePost = 'opinion',modePost = 'post') {
    const allowedExtensions = ["jpg", "jpeg", "png", "gif", "webp", "bmp", "tiff"];
    var previewContainer = $("#" + previewId);
    previewContainer.empty();

    var maxSizeInBytes = 500 * 1024 * 1024; // 500 MB

    Array.from(files).forEach(file => {
        // Validations
        if (file.size > maxSizeInBytes) {
            message("El archivo '" + file.name + "' excede el límite de tamaño permitido","error");
            return;
        }

        var fileExtension = file.name.split(".").pop().toLowerCase();
        if (allowedExtensions.indexOf(fileExtension) === -1) {
            message("El archivo '" + file.name + "' tiene una extensión no permitida","error");
            return;
      }
      
      if (typePost == 'opinion' && modePost == 'post') {
        imagesOpinion[file.name] = file;
      } else if(typePost == 'opinion' && modePost == 'edit') {
        imagesEditOpinion[file.name] = file;
      } else if(typePost == 'response' && modePost == 'post') {
        imagesResponse[file.name] = file;
      } else if(typePost == 'response' && modePost == 'edit') {
        imagesEditResponse[file.name] = file;
      }

      var reader = new FileReader();
      reader.onload = function (e) {
            var img = $("<img />", {
                src: e.target.result,
                class: "img-preview",
                width: "120px"
            });
            var imgContainer = $("<div></div>", {
                class: "img-container"
            });
            imgContainer.append(img);
            previewContainer.append(imgContainer);
        };
        reader.readAsDataURL(file);
    });
}

function dateFormatAll() {
  $('.dateFormat').each(function () {
    const dateText = $(this).text().trim();
    const date = new Date(dateText);

    if (!isNaN(date)) {
      const formattedDate = date.toLocaleDateString('es-MX', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
      $(this).text(formattedDate);
    }
  });
  console.log("Se han formateado las fechas");
}
//------------------OPINIONS------------------------
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

function collapseResponsesShow(id) {
    var isShow = $("#responses" + id).hasClass("show");
    return isShow;
}

function ImagePostModal(id, img) {
    $("#opImg-id").text(id);
    $("#opImg-img").html('<img src="./assets/img/posts/'+id+'/'+img+'" width="100%">');
}

function ImageResponseModal(idopinion,id, img) {
    $("#resImg-id").text('#'+idopinion+'#'+id);
    $("#resImg-img").html('<img src="./assets/img/responses/'+id+'/'+img+'" width="100%">');
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
//---------------------------------------------------------

function loadBtn(id) {
  $("#" + id).html('<div class="spinner-border text-muted"></div>');
  $("#" + id).prop("disabled", true);
}

function unLoadBtn(id,text = "Enviar") {
  $("#" + id).html(text);
  $("#" + id).prop("disabled", false);
}

function loadBtnForm(form) {
  var btn = $(form).find("button[type=submit]");
  btn.data('text', btn.html());
  btn.prop("disabled", true);
  btn.html('<div class="spinner-border text-light spinner-border-sm"></div>');
}
function unLoadBtnForm(form,text = "") {
  var btn = $(form).find("button[type=submit]");
  var newText = text == "" ? btn.data('text') : text;
  btn.html(newText);
  btn.prop("disabled", false);
}

function getQueryParams() {
  const params = new URLSearchParams(window.location.search);
  return Object.fromEntries(params.entries()) ?? false;
}

function isEmpty(obj) {
    return Object.entries(obj).length === 0;
}

function reloadWithoutParams() {
    const url = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.location.replace(url);
}

