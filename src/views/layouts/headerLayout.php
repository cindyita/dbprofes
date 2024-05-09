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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/e0df5df9e9.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="./assets/css/app.css?version=<?php echo VERSION; ?>">
    <link rel="stylesheet" href="./assets/css/theme.css?version=<?php echo VERSION; ?>">

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
