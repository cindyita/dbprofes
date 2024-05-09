<div class="p-3 w-100">
    <nav class="d-flex justify-content-end gap-3 menu">
        <a href="home"><i class="fa-solid fa-house"></i></a>
        <?php if(isset($_SESSION["PSESSION"])){ ?>
            <a href="profile"><i class="fa-solid fa-user pe-1"></i> Usuario1</a>
        <?php }else{ ?>
            <a href="login"><i class="fa-solid fa-user"></i></a>
        <?php } ?>
    </nav>
</div>