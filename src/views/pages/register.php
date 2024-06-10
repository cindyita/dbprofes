<div class="p-3 p-lg-5 d-flex justify-content-center flex-column align-items-center w-100 h-100">
    <div>
        <h3>Registro</h3>
    </div>
    <form method="post" id="register" class="box-form">
        <div class="mb-3 mt-3">
            <label for="username" class="form-label">Nombre de usuario:</label>
            <input type="text" class="form-control" id="username" placeholder="ingresa un username" name="username" required>
        </div>
        <div class="mb-3 mt-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" placeholder="ingresa tu email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="pass" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="pass" placeholder="Ingresa una contraseña" name="pass" required>
        </div>
        <div class="mb-3">
            <label for="cpass" class="form-label">Confirmación de contraseña:</label>
            <input type="password" class="form-control" id="cpass" placeholder="Confirma tu contraseña" name="cpass" required>
        </div>
        <div class="d-flex justify-content-end">
            <a href="login"><span>Ya tengo cuenta</span></a>
        </div>
        
        <div class="d-flex justify-content-center py-2">
            <div class="g-recaptcha" data-sitekey="<?php echo $_ENV['RECAPTCHA_SITEKEY'] ?>"></div>
        </div>
        <div class="text-center mt-2">
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </div>
    </form>
</div>
