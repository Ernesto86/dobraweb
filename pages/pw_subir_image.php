<?php session_start();
if (empty($_FILES)) die('al Recibir la Imagen Vuelva a Intentarlo');

if (!isset($_GET['user']) or !isset($_GET['dir'])) {
    die('al Recibir Parametros Vuelva a Intentarlo');
}
$user = $_GET['user'];
$tabla = strtolower($_GET['dir']);

$cad = explode(".", $_FILES['image']['name']);
$ext = strtolower($cad[count($cad) - 1]);
if ($ext != 'gif' && $ext != 'jpg' && $ext != 'png' && $ext != 'jpeg') {
    die('La imagen debe ser de extensión gif-jpg-png Vuelva a Intentarlo');
}
$tam = $_FILES["image"]['size'];
$kb = round($tam / 1024, 2);

if ($kb > 300) {
    die('El tamaño de la imagen debe ser máximo 300 kb. Vuelva a Intentarlo');

}
$tempFile = $_FILES['image']['tmp_name'];
$prefijo = substr(md5(uniqid(rand())), 0, 5) . '_';
$nomb_image = utf8_decode(trim($prefijo . $_FILES['image']['name']));
$carpeta_raiz = $_SERVER['DOCUMENT_ROOT'] . $_GET['folder'];

if (!file_exists($carpeta_raiz)) mkdir($carpeta_raiz, 777, true);

$carpeta_raiz = $_SERVER['DOCUMENT_ROOT'] . $_GET['folder'] . '/' . $tabla;

if (!file_exists($carpeta_raiz)) mkdir($carpeta_raiz, 777, true);

$carpeta_raiz = $carpeta_raiz . '/';

if (file_exists($carpeta_raiz . $nomb_image)) {
    die('Para evitar duplicacion se a cancelado el Envio.. Vuelva a Intentarlo');
}
$destino = str_replace('//', '/', $carpeta_raiz) . $nomb_image;
move_uploaded_file($tempFile, $destino);
$nomb_image = $_GET['folder'] . '/' . $tabla . '/' . $nomb_image;

require "../clases/cls_usuario_mysql.php";
$ouser = new cls_usuario_mysql;
$ouser->set_usuario_online($user);
$ouser->set_dir_image($nomb_image);
if ($ouser->update_image()) {
    $_SESSION['us_ruta_img'] = $nomb_image;
    echo $nomb_image;
}
?>