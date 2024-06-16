<div class="px-1 px-lg-4">

    <div class="d-flex justify-content-center flex-column align-items-center">

        <div class="logo py-3">
            <img src="./assets/img/system/logo.png" alt="Logo">
        </div>
        
        <?php if(isset($_SESSION['PSESSION'])){ ?>
        <div class="p-4">
            <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#opinion">Deja una opinión</button>
        </div>

        <div class="collapse w-100" id="opinion">
            <div class="p-4 w-100 d-flex flex-column align-items-center gap-3">
                <hr class="line">
                <p>Antes de opinar revisa si ya hay una opinión de ese profesor/a</p>
                <form class="opinionForm" method="post" id="opinionForm" autocomplete="off" enctype="multipart/form-data">
                    <div class="mb-3 mt-3">
                        <label for="teacher" class="form-label">Nombre COMPLETO del profesor/a:</label>
                        <input type="text" class="form-control" placeholder="Ingresa el nombre completo del profesor/a" name="teacher" required>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="school" class="form-label">Escuela:</label>
                        <input type="text" class="form-control" placeholder="Agrega el nombre COMPLETO de la escuela (Ejemplo: En vez de UDC pon Universidad Del Campo)" name="school" required>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="subject" class="form-label">Asignatura:</label>
                        <input type="text" class="form-control" placeholder="Ingresa la asignatura que te impartió" name="subject" required>
                    </div>
                    <div class="mb-3 mt-3">
                        <label>Forma de calificar:</label>
                        <select class="form-select" name="id_form_grading" id="id_form_grading" required>
                            <?php foreach ($form_grading as $key => $value) { ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label>Tiempo de calificar:</label>
                        <select class="form-select" name="id_time_grading" id="id_time_grading" required>
                            <?php foreach ($time_grading as $key => $value) { ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label>Accesibilidad:</label>
                        <select class="form-select" name="id_accessibility" id="id_accessibility" required>
                            <?php foreach ($accessibility as $key => $value) { ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="opiniontext" class="mb-2">Opinión:</label>
                        <textarea class="form-control" rows="8" id="opiniontext" name="opinion" placeholder="Asegurate de mencionar datos que puedan ser útiles para otros estudiantes."></textarea>
                        <!-- <div id="opiniontext"></div> -->
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="img" class="form-label">Imagenes: (Selecciona varias imagenes con ctrl + click)</label>
                        <input type="file" class="form-control" name="img" id="img" onchange="handleFileImages(this.files, 'preview')" multiple>
                        <div id="preview" class="d-flex gap-2 flex-wrap py-3"></div>
                    </div>
                    <div class="form-check mb-3">
                        <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="anonymous"> Opinar de forma anónima
                        </label>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary mt-3" type="submit" id="op-btnSend">Enviar opinión</button>
                    </div>
                </form>
                <hr class="line">

            </div>
        </div>
        <?php }else{ ?>
        <div class="p-4">
            <a href="login"><button class="btn btn-primary">Deja una opinión</button></a>
        </div>
        <?php } ?>

        <div class="p-4 box-search">
            <form method="post" id="search-form">
                <div class="input-group">
                    <input type="text" class="form-control" name="text" id="search-text" placeholder="Buscar...">
                    <div class="form-select-content">
                        <select class="form-select" name="typesearch" id="search-type">
                            <option value="teacher">Profesor</option>
                            <option value="school">Escuela</option>
                            <option value="subject">Asignatura</option>
                        </select>
                    </div>
                    <button class="input-group-text btn-primary" type="submit" id="search-btnSend">Buscar</button>
                </div>
            </form>
        </div>

        <section class="box-comments">
            <div class="container my-5 text-body">

                <div class="text-center pb-3">
                    <p class="text-primary"><span id="title-opinions">últimas opiniones</span> <span class="btn-icon" onclick="realodOpinions()" title="Recargar resultados"><i class="fa-solid fa-arrows-rotate"></i></span><span class="text-danger ms-2 btn-icon d-none" title="Eliminar búsqueda" id="btn-deleteSearch" onclick="reloadWithoutParams()"><i class="fa-solid fa-xmark"></i></span></p>
                </div>

                <div class="row d-flex justify-content-center flex-wrap flex-column align-items-center" id="show-opinions"></div>
                
                <div class="hidden" id="btn-show-more">
                    <div class="p-5 d-flex justify-content-center">
                        <button class="btn btn-primary" id="show-more">Ver más</button>
                    </div>
                </div>

            </div>
        </section>

    </div>

</div>

<!-------MODALS-------> 

<!-- OPINION IMG -->
<div class="modal" id="opImg">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Imagen de la opinión #<span id="opImg-id"></span></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div id="opImg-img"><img src="./assets/img/system/image404.png" width="100%"></div>
      </div>

    </div>
  </div>
</div>

<!-- RESPONSE IMG -->
<div class="modal" id="resImg">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Imagen de la respuesta <span id="resImg-id"></span></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div id="resImg-img"><img src="./assets/img/system/image404.png" width="100%"></div>
      </div>

    </div>
  </div>
</div>
<?php if(isset($_SESSION['PSESSION'])){ ?>
<!-- OPINION EDIT -->
<div class="modal" id="editOpinionModal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Editar opinión #<span id="editOpinionModal-idText"></span></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="p-2 w-100 d-flex flex-column align-items-center gap-3">
            <form class="opinionForm w-100" method="post" id="editOpinionForm" autocomplete="off" enctype="multipart/form-data">
                <div class="mb-3 mt-3">
                    <label for="teacher" class="form-label">Nombre del profesor/a:</label>
                    <input type="text" class="form-control" id="editOpinionModal-teacher" placeholder="Ingresa el nombre del profesor/a" name="teacher">
                </div>
                <div class="mb-3 mt-3">
                    <label for="school" class="form-label">Escuela:</label>
                    <input type="text" class="form-control" placeholder="Ingresa la escuela o independiente" name="school" id="editOpinionModal-school">
                </div>
                <div class="mb-3 mt-3">
                    <label for="subject" class="form-label">Asignatura:</label>
                    <input type="text" class="form-control" placeholder="Ingresa la asignatura que te impartió" name="subject" id="editOpinionModal-subject">
                </div>
                <div class="mb-3 mt-3">
                    <label>Forma de calificar:</label>
                    <select class="form-select" name="id_form_grading" id="editOpinionModal-id_form_grading">
                        <?php foreach ($form_grading as $key => $value) { ?>
                            <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3 mt-3">
                    <label>Tiempo de calificar:</label>
                    <select class="form-select" name="id_time_grading" id="editOpinionModal-id_time_grading">
                        <?php foreach ($time_grading as $key => $value) { ?>
                            <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3 mt-3">
                    <label>Accesibilidad:</label>
                    <select class="form-select" name="id_accessibility" id="editOpinionModal-id_accessibility">
                        <?php foreach ($accessibility as $key => $value) { ?>
                            <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3 mt-3">
                    <label for="comment" class="mb-2">Opinión:</label>
                    <textarea class="form-control" rows="8" id="editOpinionModal-opinion" name="opinion" placeholder="Asegurate de mencionar datos que puedan ser útiles para otros estudiantes por ejemplo cómo le agrada que realices las tareas o si hay que tener consideraciones adicionales como tipo de cita que deberían usar o si revisa estrictamente la ortografía, si tuviste algún problema o si te ayudó en algo, entre otros."></textarea>
                </div>
                <div class="mb-3 mt-3">
                    <label for="editOpinionModal-img" class="form-label">Resubir imagenes: (Selecciona varias imagenes con ctrl + click) (Si subes imagenes, se borrarán las anteriores).</label>
                    <input type="file" class="form-control" name="editimg" id="editOpinionModal-img" onchange="handleFileImagesOpinion(this.files, 'editOpinionModal-preview')" multiple>
                    <div id="editOpinionModal-preview" class="d-flex gap-2 flex-wrap py-3"></div>
                </div>
                <div class="form-check mb-3">
                    <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="anonymous" id="editOpinionModal-anonymous"> Opinión anónima
                    </label>
                </div>

                <input type="hidden" name="id" id="editOpinionModal-id">

                <div class="text-center">
                    <button class="btn btn-primary mt-3" type="submit" data-bs-dismiss="modal">Editar opinión</button>
                </div>
            </form>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- OPINION DELETE -->
<div class="modal" id="deleteOpinionModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">¿Deseas eliminar la opinión #<span id="deleteOpinionModal-idText"></span>?</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

        <div class="modal-body">
            <form method="post" id="deleteOpinionModalForm">
                <input type="hidden" name="id" id="deleteOpinionModal-id">
                <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
            </form>
        </div>

    </div>
  </div>
</div>
<!-- RESPONSE EDIT -->
<div class="modal" id="editResponseModal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Editar respuesta <span id="editResponseModal-idText"></span></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="p-2 w-100 d-flex flex-column align-items-center gap-3">
            <form method="post" id="editResponseForm" class="responseForm w-100" autocomplete="off" enctype="multipart/form-data">
                <div class="mb-3 mt-3">
                    <label for="comment" class="mb-2">Respuesta:</label>
                    <textarea class="form-control" rows="8" name="opinion" placeholder="Respuesta a la opinión.." id="editResponseModal-opinion"></textarea>
                </div>
                <div class="mb-3 mt-3">
                    <label for="img" class="form-label">Imagenes: (Selecciona varias imagenes con ctrl + click)</label>
                    <input type="file" class="form-control img" name="editResponseModal-img" id="editResponseModal-img" onchange="handleFileImagesResponse(this.files, 'editResponseModal-preview')" multiple>
                    <div id="editResponseModal-preview" class="d-flex gap-2 flex-wrap py-3"></div>
                </div>
                <div class="form-check mb-3">
                    <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="anonymous" id="editResponseModal-anonymous"> Responder de forma anónima
                    </label>
                </div>
                <input type="hidden" name="id" id="editResponseModal-id">
                <input type="hidden" id="editResponseModal-id_opinion">

                <div class="text-center">
                    <button class="btn btn-primary mt-3" type="submit" data-bs-dismiss="modal">Editar opinión</button>
                </div>
            </form>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- RESPONSE DELETE -->
<div class="modal" id="deleteResponseModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">¿Deseas eliminar la respuesta <span id="deleteResponseModal-idText"></span>?</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

        <div class="modal-body">
            <form method="post" id="deleteResponseModalForm">
                <input type="hidden" name="id" id="deleteResponseModal-id">
                <input type="hidden" id="deleteResponseModal-id_opinion">
                <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
            </form>
        </div>

    </div>
  </div>
</div>
<?php } ?>