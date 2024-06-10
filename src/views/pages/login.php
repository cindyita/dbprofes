<div class="p-5 d-flex justify-content-center flex-column align-items-center w-100 h-100">
    <div>
        <h3>Login</h3>
    </div>
    <form method="post" id="login" class="box-form">
        <div class="mb-3 mt-3">
            <label for="username" class="form-label">Nombre de usuario:</label>
            <input type="text" class="form-control" id="username" placeholder="ingresa tu username" name="username">
        </div>
        <div class="mb-3">
            <label for="pass" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="pass" placeholder="Ingresa tu contraseña" name="pass">
        </div>
        <div class="d-flex justify-content-between">
            <div class="form-check mb-3">
                <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="remember"> Recuerdame
                </label>
            </div>
            <a href="register"><span>Registrarse</span></a>
        </div>
        
        <div class="d-flex justify-content-center py-2">
            <div class="g-recaptcha" data-sitekey="<?php echo $_ENV['RECAPTCHA_SITEKEY'] ?>"></div>
        </div>
        <div class="text-center mt-2">
            <button type="submit" class="btn btn-primary" id="btnSend">Iniciar sesión</button>
        </div>
    </form>
</div>
