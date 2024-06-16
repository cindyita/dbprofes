</div> <!--End main-->

<div class="p-3 mt-5 text-center">
    <hr>
    @ DBprofes v0.1 BETA
</div>

<script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js?version=<?php echo VERSION; ?>"></script>
<script src="./assets/required/summernote/summernote-lite.min.js"></script>
<?php 
    if($scripts){
        foreach ($scripts as $value) {
            echo '<script src="'.$value.'?version='.VERSION.'"></script>';
        }
    }
?>
</body>
</html>