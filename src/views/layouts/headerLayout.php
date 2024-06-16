<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta property="description" content="Opina de un profesor y haz la vida más fácil a un estudiante nuevo" />
    <meta property="locale" content="en_ES" />
	<meta property="title" content="DBPROFES opiniones de profesores" />
    <meta property="site_name" content="DBprofes" />
    <title>DBprofes | Opiniones de profesores</title>
    <link rel="shortcut icon" href="./assets/img/system/favicon.png" type="image/PNG">

    <!-----------ReCaptcha------------>
    <?php if($_ENV['DISABLE_CAPTCHA'] != "true"){ ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php } ?>

    <link rel="stylesheet" href="./node_modules/bootstrap/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="./assets/css/app.css?version=<?php echo VERSION; ?>">
    <link rel="stylesheet" href="./assets/css/theme.css?version=<?php echo VERSION; ?>">

    <link rel="stylesheet" href="./assets/required/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="./assets/required/fontawesome/css/brands.min.css">
    <link rel="stylesheet" href="./assets/required/fontawesome/css/solid.min.css">

    <link rel="stylesheet" href="./assets/required/summernote/summernote-lite.min.css">

    <!-- Dark/light theme -->
    <script defer>
        var themeDark = window.matchMedia("(prefers-color-scheme: dark)").matches ?? true;
    
        if (localStorage.getItem("theme") === 'dark') {
            themeDark = true;
        } else if (localStorage.getItem("theme") === 'light') {
            themeDark = false;
        }
        // DARK/LIGHT THEME
        function toggleMode() {
            if (themeDark) {
                localStorage.setItem('theme', 'dark');
                document.documentElement.setAttribute("data-theme", "dark");
                themeDark = false;
            } else {
                localStorage.setItem('theme', 'light');
                document.documentElement.setAttribute("data-theme", "light");
                themeDark = true;
            }
        }
        toggleMode();
    </script>

    <?php 
        if($styles){
            foreach ($styles as $value) {
                echo '<link href="'.$value.'?version='.VERSION.'" rel="stylesheet">';
            }
        }
    ?>

    <script src="node_modules/jquery/dist/jquery.min.js"></script>

</head>
<body>

<div class="page-overlay">
    <div class="content">
        <div class="loader"></div>
    </div>
</div>

<div class="main">
