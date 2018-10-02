<?php
if(session_id()==''){
    session_start();
}
if(empty($_SESSION['us_id']) or empty($_SESSION['us_idtipo'])){
   header('Location: ../seguridad/login.php');
   exit();
}


