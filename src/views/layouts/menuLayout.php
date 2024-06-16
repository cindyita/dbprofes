<div class="p-3 w-100">
    <nav class="d-flex justify-content-end gap-3 menu">
        <a href="home"><i class="fa-solid fa-house"></i></a>
        <a onclick="toggleMode()"><i class="fa-solid fa-circle-half-stroke"></i></a>
        <?php if(isset($_SESSION["PSESSION"])){ ?>

            <div class="dropdown">
                <a href="profile" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-user pe-1"></i> <?php echo SESSION['username']; ?></a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="profile"><i class="fa-solid fa-id-card-clip"></i> Mi perfil</a></li>
                    <li><a class="dropdown-item" href="home?type=my&text=my"><i class="fa-solid fa-address-card"></i> Mis opiniones</a></li>
                    <li><a class="dropdown-item" href="logout"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
                </ul>
            </div>

        <?php }else{ ?>
            <a href="login"><i class="fa-solid fa-user"></i></a>
        <?php } ?>
    </nav>
</div>