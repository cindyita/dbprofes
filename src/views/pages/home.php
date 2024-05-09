<div class="px-4">

    <div class="d-flex justify-content-center flex-column align-items-center">

        <div class="logo py-3">
            <img src="./assets/img/system/logo.png" alt="Logo">
        </div>
        
        <div class="p-4">
            <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#opinion">Deja una opinión</button>
        </div>

        <div class="collapse w-100" id="opinion">
            <div class="p-4 w-100 d-flex flex-column align-items-center gap-3">
                <hr class="line">
                <p>Antes de opinar revisa si ya hay una opinión de ese profesor/a</p>
                <form class="w-50" method="post">
                    <div class="mb-3 mt-3">
                        <label for="teacher" class="form-label">Nombre del profesor/a:</label>
                        <input type="text" class="form-control" id="teacher" placeholder="Ingresa el nombre del profesor/a" name="teacher">
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="school" class="form-label">Escuela:</label>
                        <input type="text" class="form-control" id="school" placeholder="Ingresa la escuela o independiente" name="school">
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="subject" class="form-label">Asignatura:</label>
                        <input type="text" class="form-control" id="subject" placeholder="Ingresa la asignatura que te impartió" name="subject">
                    </div>
                    <div class="mb-3 mt-3">
                        <label>Forma de calificar:</label>
                        <select class="form-select" name="methodGrading">
                            <option>100 Gratis</option>
                            <option>Simple</option>
                            <option>Justa</option>
                            <option selected>Normal</option>
                            <option>Estricta</option>
                            <option>Muy estricta</option>
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label>Tiempo de calificar:</label>
                        <select class="form-select" name="timeGrading">
                            <option>Muy lento</option>
                            <option>lento</option>
                            <option selected>Normal</option>
                            <option>Rápido</option>
                            <option>Inmediato</option>
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label>Accesibilidad:</label>
                        <select class="form-select" name="accessibility">
                            <option>Muy accesible</option>
                            <option>Accesible</option>
                            <option selected>Normal</option>
                            <option>Difícil acceso</option>
                            <option>Nada accesible</option>
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="comment" class="mb-2">Opinión:</label>
                        <textarea class="form-control" rows="6" id="comment" name="text" placeholder="Asegurate de mencionar datos que puedan ser útiles para otros estudiantes por ejemplo cómo le agrada que realices las tareas o si hay que tener consideraciones adicionales como tipo de cita que deberían usar o si revisa estrictamente la ortografía, si tuviste algún problema o si te ayudó en algo, entre otros."></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="anonimo"> Opinar de forma anónima
                        </label>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary mt-3">Enviar opinión</button>
                    </div>
                </form>
                <hr class="line">

            </div>
        </div>

        <div class="p-4 box-search">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar profesor..">
                <span class="input-group-text btn-primary">Buscar</span>
            </div>
        </div>

        <section class="box-comments">
            <div class="container my-5 text-body">

                <div class="text-center pb-3">
                    <p class="text-primary">últimas opiniones</p>
                </div>

                <div class="row d-flex justify-content-center">
                    <div class="col-12">
                        <div class="d-flex flex-start mb-4 flex-column flex-lg-row justify-content-center justify-content-lg-start">
                            <div class="icon-primary text-center icon-comment">
                                <i class="fa-solid fa-comment-dots"></i>
                            </div>
                            <div class="card w-100">
                                <div class="card-body p-4">
                                    <div class="">
                                        <div class="d-flex justify-content-between">
                                            <h5>Opinando sobre: Jhony Vega</h5>
                                            <span class="text-primary">#12</span>
                                        </div>
                                        <div class="d-flex flex-column gap-1 pb-3">
                                            <span class="small">Escuela: UnADM (Online)</span>
                                            <span class="small text-muted">Opinión anónima</span>
                                        </div>
                                        <p>
                                        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque
                                        ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus
                                        viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla.
                                        Donec lacinia congue felis in faucibus ras purus odio, vestibulum in
                                        vulputate at, tempus viverra turpis.
                                        </p>

                                        <div class="mb-3 d-flex flex-column gap-1">
                                            <div>
                                                <i class="fa-solid fa-bookmark me-1"></i> <span class="text-primary">Asignatura: </span><span>Cálculo diferencial</span>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-book me-1"></i> <span class="text-primary">Forma de calificar: </span><span>Estricto</span>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-hourglass-start me-1"></i> <span class="text-primary">Tiempo de calificar: </span><span>Rápido</span>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-hand me-1"></i><span class="text-primary">Accesibilidad: </span><span>No accesible</span>
                                            </div>
                                        </div>


                                        <div class="d-flex justify-content-between align-items-start flex-column flex-lg-row gap-1 gap-lg-3">
                                            <div class="d-flex gap-1 gap-lg-3 flex-column flex-lg-row">
                                                <div class="text-muted">
                                                    Hace 1 día
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <a href="#!" class="text-primary"><i class="fas fa-thumbs-up me-1"></i>132</a>
                                                </div>
                                                <div class="d-flex align-items-center" data-bs-toggle="collapse" data-bs-target="#comment1">
                                                    <a href="#!" class="text-primary"><i class="fa-solid fa-comment me-1"></i> 1 Respuesta [Ver/Ocultar]</a>
                                                </div>
                                            </div>
                                            <a href="#!" class="text-primary"><i class="fas fa-reply me-1"></i> Responder</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="comment-reply collapse" id="comment1">
                            
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
                        </div>

                        
                    </div>
                </div>
            </div>
        </section>

    </div>

</div>

