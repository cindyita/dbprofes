<div class="p-3 p-lg-5 container">

    <div class="py-3 py-lg-5 d-flex justify-content-center">
        <h3>Usuario</h3>
    </div>

    <div class="d-flex flex-start mb-4 flex-column flex-lg-row justify-content-center justify-content-lg-start">
        <div class="icon-primary text-center icon-comment">
            <i class="fa-solid fa-id-card"></i>
        </div>
        <!----MODO NORMAL--->
        <div class="card w-100" id="mode1">
            <div class="card-body p-4">
                <div class="">
                    <div class="d-flex justify-content-start">
                        <h3 id="mode1-username"><?php echo $user['username']; ?></h3>
                    </div>
                    <div class="d-flex flex-column gap-1 pb-3">
                        <span class="small text-muted">Rol: <?php echo $user['role']; ?></span>
                    </div>
                    <p>
                        <h5 class="text-primary">Biografía:</h5>
                        <span id="mode1-biography"><?php echo $user['biography'] ?? "-"; ?></span>
                    </p>


                    <div class="d-flex justify-content-between align-items-start flex-column flex-lg-row gap-1 gap-lg-3">
                        <div class="d-flex gap-1 gap-lg-3 flex-column flex-lg-row">
                            <div class="text-muted">
                                Registro: <?php echo dateFormat($user['timestamp_create']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>