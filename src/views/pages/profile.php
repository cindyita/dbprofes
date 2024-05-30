<div class="p-5 container">

    <div class="py-5 d-flex justify-content-center">
        <h3>Mi perfil</h3>
    </div>

    <div class="d-flex flex-start mb-4 flex-column flex-lg-row justify-content-center justify-content-lg-start">
        <div class="icon-primary text-center icon-comment">
            <i class="fa-solid fa-id-card"></i>
        </div>
        <!----MODO NORMAL--->
        <div class="card w-100" id="mode1">
            <div class="card-body p-4">
                <div class="">
                    <div class="d-flex justify-content-between">
                        <h3 id="mode1-username"><?php echo $_SESSION['PSESSION']['username']; ?></h3>
                        <div><button class="btn btn-primary" onclick="modeEdit();">Editar <i class="fa-solid fa-pen"></i></button></div>
                    </div>
                    <div class="d-flex flex-column gap-1 pb-3">
                        <span class="small text-muted"><?php echo $_SESSION['PSESSION']['role']; ?></span>
                    </div>
                    <p>
                        <h5 class="text-primary">Biografía:</h5>
                        <span id="mode1-biography"><?php echo $_SESSION['PSESSION']['biography'] ?? "-"; ?></span>
                    </p>


                    <div class="d-flex justify-content-between align-items-start flex-column flex-lg-row gap-1 gap-lg-3">
                        <div class="d-flex gap-1 gap-lg-3 flex-column flex-lg-row">
                            <div class="text-muted">
                                Registro: <?php echo dateFormat($_SESSION['PSESSION']['timestamp_create']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!----MODO EDITAR PERFIL----->
        <div class="card w-100 hidden" id="mode2">
            <form method="post" id="updateProfile">
                <div class="card-body p-4">
                    <div class="">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="mb-3 mt-3">
                                    <input type="hidden" id="actual-username" value="<?php echo $_SESSION['PSESSION']['username']; ?>">
                                    <input type="hidden" name="id" value="<?php echo $_SESSION['PSESSION']['id']; ?>">
                                    <input type="username" class="form-control" id="username" placeholder="Cambiar nombre de usuario" name="username" value="<?php echo $_SESSION['PSESSION']['username']; ?>" required>
                                </div>
                            </div>
                            <div>
                                <a class="btn btn-muted me-1" onclick="modeNormal()">Cancelar</a>
                                <button class="btn btn-primary" type="submit">
                                <div class="spinner-border spinner-border-sm hidden" id="loader-btn"></div> Actualizar <i class="fa-solid fa-floppy-disk"></i></button>
                            </div>
                        </div>
                        <p>
                            <h5>Biografía:</h5>
                            <div class="mb-3 mt-3">
                                <textarea class="form-control" rows="5" id="biography" name="biography"><?php echo $_SESSION['PSESSION']['biography']; ?></textarea>
                            </div>
                        </p>

                    </div>
                </div>
            </form>
        </div>

    </div>

</div>