<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="author" content="Ernesto Guaman">
    <title>Login</title>
    <link rel="stylesheet" href="../static/fonts/fontawesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../static/css/bootstrap-custom.css">
    <link rel="stylesheet" href="../static/css/uikit.css">
    <link rel="stylesheet" href="../static/css/responsive.css" media="only screen and (max-width: 1200px)">
    <link rel="stylesheet" href="../static/css/loader.css">
    <script src="../static/js/loader.js"></script>
</head>
<body style="background: #e9ebee">
<div id="load-content" class="loader-wrapper">
    <div id="id-loading" class="loader-small"></div>
</div>
<header id="id-header">
    <nav class="navbar navbar-landing navbar-expand-lg navbar-dark bg-blue p-2">
        <div class="container">
            <a class="navbar-brand mr-auto" href="#">
                <i class="fa fa-globe"></i>
                Sistema MundoText S.A
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar1">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">

                    </li>
                </ul>
            </div>
        </div> <!-- container //  -->
    </nav>
</header>

<main id="id-main">
    <div class="container-fluid" style="margin-top: 5rem">
        <div class="row">
            <article class="col-lg-8 d-none d-md-block d-sm-block">

            </article>
            <aside class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <form id="id-form-login" class="form-signin p-4">
                            <input type="hidden" name="action" value="login">
                            <div class="text-center">
                                <i class="fa fa-user-circle fa-4x text-primary"></i>
                            </div>

                            <h1 class="h4 mb-3 font-weight-normal text-center">Ingresar al sistema</h1>
                            <hr>
                            <div style="min-height: 2rem">
                                <div id="id-alert" class="alert alert-danger p-1 text-center d-none" role="alert">
                                    This is a danger alert—check it out!
                                </div>
                            </div>

                            <label for="inputEmail" class="sr-only">Usuario</label>
                            <input type="text" name="cuenta" class="form-control mb-1" placeholder="Usuario" required
                                   autofocus="">
                            <label for="inputPassword" class="sr-only">Contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="Contraseña"
                                   required>
                            <div class="checkbox mb-3 mt-1">
                                <label>
                                    <input type="checkbox" name="norobot" required> No eres un Robot
                                </label>
                            </div>
                            <button id="id-btnlogin" class="btn btn-lg btn-primary btn-block">
                                <i class="fa fa-sign-in-alt"></i> Inicias sesión
                            </button>
                            <p class="mt-4 mb-3 text-muted">
                                <a href="#"> Olvidaste tu Contraseña.</a>
                            </p>
                        </form>
                    </div>
                </div>
            </aside>
        </div>

    </div>
</main>

<footer id="id-footer" class="navbar navbar-expand-lg navbar-dark fixed-bottom bg-dark-50">
    <div class="container">
        <div class="col-sm-6">
            <span class="text-white">Copyright &copy 2018</span>
        </div>
        <div class="col-sm-6">
           <span class="fa-pull-right">
               <a href="http://bootstrap-ecommerce.com" class="text-white">
                <i class="fa fa-user-circle"></i>
                   MundoText S.A
               </a>
           </span>
        </div>
    </div>
</footer>

<script src="../static/lib/jquery.min2.0.js"></script>
<script src="../static/js/bootstrap.bundle.min.js"></script>
<script src="../static/js/config.js"></script>
<script>

    var isMobile = {
        Android: function () {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function () {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function () {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function () {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function () {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function () {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

    $(function () {
        $('#id-form-login').on({
            submit: function (e) {
                e.preventDefault();
                var frmData = new FormData($(this)[0]);
                $('#id-btnlogin').attr('disabled', true);
                $('#id-alert').addClass('d-none');

                $.ajax({
                    url: '../app/seguridad/ajax/usuario.php',
                    data: frmData,
                    method: 'POST',
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false
                }).done(function (data) {

                    if (data.resp == true) {
                        if (isMobile.any()) {
                            window.location = '../m';
                        } else {
                            window.location = '../';
                        }
                        return false;
                    } else {
                        $('#id-alert').removeClass('d-none');
                        $('#id-alert').html('<em><i class="fa fa-ban"></i> ' + data.error + '</em>');
                    }
                    $('#id-btnlogin').attr('disabled', false);

                }).fail(function (jqXHR, textStatus) {
                    $('#id-alert').removeClass('d-none');
                    $('#id-alert').html('Problemas de conexion con el servidor: ' + textStatus);
                    $('#id-btnlogin').attr('disabled', false);
                });
            }
        });
    });
</script>
</body>
</html>