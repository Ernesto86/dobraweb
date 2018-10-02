<?php
session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
} ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fichero Cliente</title>
    <link rel="stylesheet" href="../static/fonts/fontawesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../static/css/bootstrap-custom.css">
    <link rel="stylesheet" href="../static/css/uikit.css">
    <link rel="stylesheet" href="../static/css/responsive.css" media="only screen and (max-width: 1200px)">
    <link rel="stylesheet" href="../static/css/loader.css">
    <script src="../static/js/loader.js"></script>

    <?php
    require '../app/setting.php';
    define('MOD', DROOT . 'cliente/');
    require MOD . 'control/CtrGrupo.php';
    require '../clases/cls_my_transaccion.php';

    $otrans = new cls_my_transaccion;
    $otrans->transacciones($_GET['id']);
    $trans = $otrans->campos();
    if (!$trans['ing'])
        die('<div class="error">No cuenta con Permisos.</div>');

    if ($_SESSION['us_tipuser'] != 'administrador' and $_SESSION['us_tipuser'] != 'semiadministrador') {

        if (!$otrans->fn_usuario_caja_bodega()) {
            die('<div class="error">No cuenta con Permisos.</div>');
        }
        $cmp = $otrans->campos();
        unset($otrans);

        $bodegaid = trim($cmp['bodegaid']);
        $cajaid = trim($cmp['cajaid']);
        $divisionid = trim($cmp['divisionid']);

        if ($bodegaid == '' or $divisionid == '' or $cajaid == '')
            die('<div class="error">No se Encontró Bodega Predeterminada.</div>');

    }
    $ctrGrupo = new CtrGrupo();
    ?>
</head>
<body>
<div id="load-content" class="loader-wrapper">
    <div id="id-loading" class="loader-small"></div>
</div>
<header id="id-header">
</header>
<main id="id-main">
    <article class="container">
        <div class="row flex-column">

            <form id="id-form-cliente" class="needs-validation">
                <input type="hidden" name="action" value="save">
                <div class="card">
                    <div class="card-header text-center">
                        <h4><i class="fa fa-user-circle"></i> Fichero de Cliente</h4>
                    </div>
                    <div class="card-body">
                        <div style="min-height: 2rem">
                            <div id="id-alert" class="alert alert-danger p-1 text-center d-none" role="alert">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-2 mb-3">
                                <label for="validationCustomUsername">Código</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-qrcode"></i>
                                </span>
                                    </div>
                                    <input type="text" name="codigo" class="form-control"
                                           aria-describedby="inputGroupPrepend">
                                    <div class="invalid-feedback">
                                        Please choose a username.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="validationCustomUsername">Nombres</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-user"></i>
                                </span>
                                    </div>
                                    <input type="text" name="nombres" class="form-control"
                                           aria-describedby="inputGroupPrepend" required>
                                    <div class="invalid-feedback">
                                        Please choose a username.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="validationCustomUsername">Lista</label>
                                <div class="form-group">
                                    <select name="clase" class="custom-select" required>
                                        <option value="01">Normal</option>
                                        <option value="02">Unitario</option>
                                        <option value="03">Docena</option>
                                        <option value="04">Caja</option>
                                    </select>
                                    <div class="invalid-feedback">Example invalid custom select feedback</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCustomUsername">Grupo</label>
                                <div class="form-group">
                                    <select name="grupo" class="custom-select" required>
                                        <option value="">Seleccione</option>
                                        <?php foreach ($ctrGrupo->listaGrupos() as $g): ?>
                                            <option value="<?= $g->id ?>"><?= $g->nombre ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Example invalid custom select feedback</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="col-auto my-1">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="id-activo" checked>
                                    <label class="custom-control-label" for="id-activo">Activo</label>
                                </div>
                            </div>
                            <div class="col-auto my-1">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="id-lista">
                                    <label class="custom-control-label" for="id-lista">Precio Lista</label>
                                </div>
                            </div>
                            <div class="col-auto my-1">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="id-relacionado">
                                    <label class="custom-control-label" for="id-relacionado">Relacionado</label>
                                </div>
                            </div>

                        </div>


                        <nav class="mt-3">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="id-general-tab" data-toggle="tab"
                                   href="#id-general" role="tab" aria-controls="id-general"
                                   aria-selected="true">General</a>
                                <a class="nav-item nav-link" id="id-area-comercial-tab" data-toggle="tab"
                                   href="#id-area-comercial" role="tab" aria-controls="id-area-comercial"
                                   aria-selected="false">Área Comercial</a>

                            </div>
                        </nav>
                        <div class="tab-content p-2" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="id-general" role="tabpanel"
                                 aria-labelledby="id-general-tab">
                                <div class="form-row">
                                    <div class="col-md-3 mb-3">
                                        <label for="validationCustomUsername">Ruc</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-qrcode"></i>
                                            </span>
                                            </div>
                                            <input type="text" name="ruc" class="form-control"
                                                   placeholder="Identificación"
                                                   aria-describedby="inputGroupPrepend">
                                            <div class="invalid-feedback">
                                                Please choose a username.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="validationCustomUsername">Ciudad</label>
                                        <div class="form-group">
                                            <select name="ciudad" class="custom-select">
                                                <option value="">Seleccione</option>
                                                <?php foreach ($ctrGrupo->listaZonas('CIUDAD') as $c): ?>
                                                    <option value="<?= $c->id ?>"><?= $c->nombre ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="invalid-feedback">Example invalid custom select feedback</div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="validationCustomUsername">Teléfono1</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-phone"></i>
                                            </span>
                                            </div>
                                            <input type="text" name="telefono1" class="form-control"
                                                   aria-describedby="inputGroupPrepend">
                                            <div class="invalid-feedback">
                                                Please choose a username.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="validationCustomUsername">Teléfono2</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-phone"></i>
                                            </span>
                                            </div>
                                            <input type="text" name="telefono2" class="form-control"
                                                   aria-describedby="inputGroupPrepend">
                                            <div class="invalid-feedback">
                                                Please choose a username.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="validationCustomUsername">Celular</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-mobile"></i>
                                            </span>
                                            </div>
                                            <input type="text" name="celular" class="form-control"
                                                   aria-describedby="inputGroupPrepend">
                                            <div class="invalid-feedback">
                                                Please choose a username.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustomUsername">Dirección</label>
                                        <textarea name="direccion" class="form-control"
                                                  aria-describedby="inputGroupPrepend"></textarea>
                                        <div class="invalid-feedback">
                                            Please choose a username.
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustomUsername">E-mail</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-qrcode"></i>
                                            </span>
                                            </div>
                                            <input type="text" name="email" class="form-control"
                                                   aria-describedby="inputGroupPrepend">
                                            <div class="invalid-feedback">
                                                Please choose a username.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-3 mb-3">
                                        <label for="validationCustomUsername">Forma de Pago</label>
                                        <div class="form-group">
                                            <select name="formapago" class="custom-select" required>
                                                <option value="EFECTIVO">EFECTIVO</option>
                                                <option value="DEPOSITO">DEPOSITO</option>
                                                <option value="CHEQUE">CHEQUE</option>
                                            </select>
                                            <div class="invalid-feedback">Example invalid custom select feedback</div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="validationCustomUsername">Banco</label>
                                        <div class="form-group">
                                            <select name="banco" class="custom-select">
                                                <option value="">Seleccione</option>
                                                <?php
                                                $criterio = new stdClass;
                                                $criterio->cod = 'BANCOS';
                                                $criterio->tipo = '';
                                                foreach ($ctrGrupo->listaParametros($criterio) as $b):?>
                                                    <option value="<?= $b->nombre ?>"><?= $b->nombre ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="invalid-feedback">Example invalid custom select feedback</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="validationCustomUsername">No.Cuenta</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-qrcode"></i>
                                            </span>
                                            </div>
                                            <input type="text" name="cuenta" class="form-control"
                                                   aria-describedby="inputGroupPrepend">
                                            <div class="invalid-feedback">
                                                Please choose a username.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="validationCustomUsername">Empleado</label>
                                        <div class="form-group">
                                            <select class="custom-select">
                                                <option value="">Normal</option>
                                                <option value="1">Unitario</option>
                                            </select>
                                            <div class="invalid-feedback">Example invalid custom select feedback</div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-row">

                                    <div class="container-fluid p-0">
                                        <div id="google-maps" class="container d-flex flex-column embed-responsive">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="id-area-comercial" role="tabpanel"
                                 aria-labelledby="id-area-comercial-tab">
                            </div>
                        </div>


                    </div>
                    <div class="card-footer">
                        <div class="fa-pull-right">
                            <button type="button" onclick="window.location.reload()" id="id-btn-nuevo" class="btn">
                                <i class="fa fa-file"></i>
                                Nuevo
                            </button>
                            <button id="id-btn-guardar" class="btn btn-success">
                                <i class="fa fa-save"></i>
                                Guardar Cliente
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </article>
</main>
<footer class="navbar navbar-expand-lg navbar-dark fixed-bottom bg-dark">
    <div class="container text-center" id="id-foot-menu">
        <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-3">
            <a href="#" rel="action" data-accion="geoposicion" data-toggle="tooltip" data-placement="top"
               title="Mi Ubicación" class="text-white">
                <span class="fa fa-map-marker fa-2x"></span>
            </a>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-3">
            <a href="#" rel="action" onclick="$('#id-btn-guardar').click();" data-accion="rutas" data-toggle="tooltip" data-placement="top"
               title="Rutas Disponibles" class="text-white">
                <span class="fa fa-save fa-2x"></span>
            </a>
        </div>

    </div><!-- //container -->
</footer>

<script src="../static/lib/jquery.min2.0.js"></script>
<script src="../static/js/bootstrap.bundle.min.js"></script>
<script src="../static/plugins/util.js"></script>

<script>

    $(function () {

        $('#id-form-cliente').on({
           submit: function (e) {
              e.preventDefault();

               var frmData = new FormData($(this)[0]);
               $('#id-btn-guardar').attr('disabled', true);
               $('#id-alert').addClass('d-none');

               $.ajax({
                   url: '../app/cliente/ajax/cliente.php',
                   data: frmData,
                   method: 'POST',
                   dataType: 'json',
                   cache: false,
                   contentType: false,
                   processData: false
               }).done(function (data) {
                   $('#id-alert').removeClass('d-none');
                   if (data.resp == true) {
                       $('#id-alert').removeClass('alert-danger');
                       $('#id-alert').addClass('alert-success');
                       $('#id-alert').html('<em><i class="fa fa-check-circle"></i> Registro creado correctamente.');
                   } else {
                       $('#id-alert').html('<em><i class="fa fa-ban"></i> ' + data.error + '</em>');
                       $('#id-btn-guardar').attr('disabled', false);
                   }

               }).fail(function (jqXHR, textStatus) {
                   $('#id-alert').removeClass('d-none');
                   $('#id-alert').html('<i class="fa fa-ban"></i> Problemas de conexion con el servidor: ' + textStatus);
                   $('#id-btn-guardar').attr('disabled', false);
               });

           }
        });

    });

</script>
</body>
</html>