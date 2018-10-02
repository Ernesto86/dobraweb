<?php
if(session_id()==''){
    session_start();
}
if(empty($_SESSION['us_id']) or empty($_SESSION['us_idtipo'])){
    header('Location: seguridad/login.php');
    exit();
}?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <meta name="title" content="Principal">
    <title>Sistema MundoText</title>
    <link rel="stylesheet" type="text/css" href="css/style_facebook.css">
    <link rel="stylesheet" type="text/css" href="css/menu.css">
    <link rel="stylesheet" type="text/css" href="css/loader.css"/>
    <script src="js/loader.js"></script>
</head>
<body class="timelineLayout">
<div id="load-content" class="loader-wrapper">
    <div id="id-loading" class="loader-small"></div>
</div>
<div>
    <div class="rigth" id="btn_ocultar">
        <img src="images/flechas/arrow-left.png" id="btn_es_panel" title="Ocultar Menu"/>
    </div>
    <div id="blueBarHolder" class="slim">
        <div id="blueBar">
            <div id="title_logo">MundoText S.A</div>
            <div class="web_master">WebMaster : Ernesto Guaman U.</div>
            <div class="rigth">
                <div id="menu_head">
                    <div id="header">
                        <ul class="nav">
                            <li><a>CUENTA<span class="icono"><img src="images/icon/User.png"/></span></a>
                                <ul>
                                    <li><a id="login_password">Cambiar Password</a></li>
                                </ul>
                            </li>
                            <li><a href="salir.php">Salir<span class="icono"><img
                                                src="images/icon/Off.png"/></span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="pageLogo2"><img src="images/logo_38px.png"/></div>
        </div>
    </div>
    <div id="menu_panel">
        <div id="menu_cont">
            <div id="user_perfil">
                <div class="title_online"><img src="images/icon/User.png"/> Usuario Online</div>
                <img src="images/user/usu.gif" class="left foto"/>
                <div class="left" id="online_user">
                    <div class="relleno_5"><strong>User : </strong> <?php echo $_SESSION['us_cuenta']; ?></div>
                    <div align="center" class="relleno_5">
                        <input type="button" name="button" id="button" value="Salir" class="btn_salir" title="Salir del Sistema"
                                                                 onclick="window.location='salir.php'"/>
                    </div>
                </div>
            </div>
            <div class="title_online"><img src="images/menu/opciones.png" class="left_opciones"/>Menú de Opciones
            </div>

            <div id="menu">
                <ul>
                    <?php
                    require 'clases/cls_my_menu.php';
                    $omenu = new cls_my_menu;
                    $omenu->modulos_menus();
                    ?>
                </ul>
            </div>

        </div>
    </div>
    <div id="contenedor">
        <iframe id="iframe_cont" name="iframe_cont" width="100%" height="100%" src="pages/digital-clock/index.html"
                frameborder="0">
        </iframe>
    </div>
</div>
<div class="float-content" id="cont_flotante" style="display:none">
    <div class="cerrar"><a href="#" title="Cerrar"></a></div>
    <div id="cont_flot"></div>
</div>
<script type="text/javascript" src="js/lib/jquery.min2.0.js"></script>
<script type="text/javascript" src="js/principal.js"></script>
</body>
</html>