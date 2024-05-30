<div class="px-4">

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
                        <label for="teacher" class="form-label">Nombre del profesor/a:</label>
                        <input type="text" class="form-control" placeholder="Ingresa el nombre del profesor/a" name="teacher">
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="school" class="form-label">Escuela:</label>
                        <input type="text" class="form-control" placeholder="Ingresa la escuela o independiente" name="school">
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="subject" class="form-label">Asignatura:</label>
                        <input type="text" class="form-control" placeholder="Ingresa la asignatura que te impartió" name="subject">
                    </div>
                    <div class="mb-3 mt-3">
                        <label>Forma de calificar:</label>
                        <select class="form-select" name="id_form_grading" id="id_form_grading">
                            <?php foreach ($form_grading as $key => $value) { ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label>Tiempo de calificar:</label>
                        <select class="form-select" name="id_time_grading" id="id_time_grading">
                            <?php foreach ($time_grading as $key => $value) { ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label>Accesibilidad:</label>
                        <select class="form-select" name="id_accessibility" id="id_accessibility">
                            <?php foreach ($accessibility as $key => $value) { ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="comment" class="mb-2">Opinión:</label>
                        <textarea class="form-control" rows="8" id="comment" name="opinion" placeholder="Asegurate de mencionar datos que puedan ser útiles para otros estudiantes por ejemplo cómo le agrada que realices las tareas o si hay que tener consideraciones adicionales como tipo de cita que deberían usar o si revisa estrictamente la ortografía, si tuviste algún problema o si te ayudó en algo, entre otros."></textarea>
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
                        <button class="btn btn-primary mt-3 d-flex gap-2 align-items-center" type="submit"><div class="spinner-border spinner-border-sm btn-load"></div><span>Enviar opinión</span></button>
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
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar profesor..">
                <span class="input-group-text btn-primary">Buscar</span>
            </div>
        </div>

        <section class="box-comments">
            <div class="container my-5 text-body">

                <div class="text-center pb-3">
                    <p class="text-primary"><span>últimas opiniones</span> <span class="btn-icon" onclick="realodOpinions()"><i class="fa-solid fa-arrows-rotate"></i></span></p>
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