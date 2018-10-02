<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="author" content="Ernesto Guaman">
    <title>Estado de Cuenta Cliente</title>
    <link rel="stylesheet" href="../static/fonts/fontawesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../static/css/bootstrap-custom.css">
    <link rel="stylesheet" href="../static/css/uikit.css">
    <link rel="stylesheet" href="../static/css/responsive.css" media="only screen and (max-width: 1200px)">
    <link rel="stylesheet" href="../static/css/">
    <link rel="stylesheet" href="../static/css/loader.css">
    <script src="../static/js/loader.js"></script>
</head>
<body>
<div id="load-content" class="loader-wrapper">
    <div id="id-loading" class="loader-small"></div>
</div>
<header id="id-header">
    <form id="id-form-data">
        <nav class="navbar navbar-landing navbar-expand-lg navbar-dark bg-light p-1">
            <div class="col-sm-3 col-md-3">
                <div class="input-group">
                    <input type="text" name="codigo" class="form-control" placeholder="Codigo de Cliente" required>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" title="Buscar Clientes">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-2">
                <div class="input-group">
                    <input name="inicio" value="<?= date('d/m/Y') ?>" class="form-control" placeholder="Fecha Inicial"
                           type="text" required>
                    <div class="input-group-append">
                        <button class="btn " type="button">
                            <i class="fa fa-calendar"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-2">
                <div class="input-group">
                    <input name="fin" value="<?= date('d/m/Y') ?>" class="form-control" placeholder="Fecha Final"
                           type="text" required>
                    <div class="input-group-append">
                        <button class="btn " type="button">
                            <i class="fa fa-calendar"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-2">
                <button class="btn btn-success">
                    Estado de Cuenta
                    <i class="fa fa-arrow-down"></i>
                    <input type="hidden" name="action" value="estado_cuenta">
                </button>
            </div>

            <div class="col-sm-3 col-md-3">
                <div class="btn-group fa-pull-right">
                    <button class="btn">
                        <i class="fa fa-print"></i> Imprimir
                    </button>
                    <button class="btn btn-success">
                        <i class="fa fa-file-excel"></i> Excel
                    </button>
                </div>
            </div>

        </nav>
    </form>

</header>

<main id="id-main" class="mt-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="box">
                    <dl class="dlist-align">
                        <dt>Código:</dt>
                        <dd class="text-left" id="p-codigo"></dd>
                    </dl>
                    <dl class="dlist-align">
                        <dt>Nombre:</dt>
                        <dd class="text-left" id="p-nombres"></dd>
                    </dl>
                    <dl class="dlist-align">
                        <dt>Grupo:</dt>
                        <dd class="text-left" id="p-grupo"></dd>
                    </dl>
                </div> <!-- box.// -->
            </div>
            <div class="col-md-4">
                <div class="box">
                    <dl class="dlist-align">
                        <dt>Parameter:</dt>
                        <dd class="text-right">Value name</dd>
                    </dl>
                    <dl class="dlist-align">
                        <dt>Color:</dt>
                        <dd class="text-right">Orange and Black</dd>
                    </dl>
                    <dl class="dlist-align">
                        <dt>Material:</dt>
                        <dd class="text-right">Leather</dd>
                    </dl>

                </div> <!-- box.// -->
            </div>
            <div class="col-md-4">
                <div class="box">
                    <dl class="dlist-align">
                        <dt>Parameter:</dt>
                        <dd class="text-right">Value name</dd>
                    </dl>
                    <dl class="dlist-align">
                        <dt>Color:</dt>
                        <dd class="text-right">Orange and Black</dd>
                    </dl>
                    <dl class="dlist-align">
                        <dt>Material:</dt>
                        <dd class="text-right">Leather</dd>
                    </dl>

                </div> <!-- box.// -->
            </div>
        </div>
    </div>

    <div class="card">
        <table class="table table-hover shopping-cart-wrap">
            <thead class="text-muted">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Fecha</th>
                <th scope="col">Tipo</th>
                <th scope="col">Número</th>
                <th scope="col">Detalle</th>
                <th scope="col">Debe</th>
                <th scope="col">Haber</th>
                <th scope="col">Saldo</th>
            </tr>
            </thead>
            <tbody id="id-table-detalle">

            </tbody>
        </table>
    </div>
</main>

<footer id="id-footer">
</footer>

<script src="../static/lib/jquery.min2.0.js"></script>
<script src="../static/js/bootstrap.bundle.min.js"></script>
<script src="../static/js/config.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jsrender/0.9.88/jsrender.min.js"></script>

<script id="js-template" type="text/x-jsrender">
{{for deuda}}
   <tr>
        <td></td>
        <td>{{:Fecha}}</td>
        <td>{{:Tipo}}</td>
        <td>{{:Numero}}</td>
        <td>{{:Detalle}}</td>
        <td>
           {{if DEBE > 0}}
            {{:DEBE}}
           {{else}}
             -
           {{/if}}
        </td>
        <td>
           {{if HABER > 0}}
            {{:HABER}}
           {{else}}
             -
           {{/if}}
        </td>
        <td>
            <div class="price-wrap">
                <var class="price">USD {{:SALDO}}</var>
            </div>
        </td>
    </tr>
  {{/for}}


</script>

<script>
    $(function () {

        $('#id-form-data').on({
            submit: function (e) {
                e.preventDefault();
                var frmData = new FormData($(this)[0]);
                $('#id-btnlogin').attr('disabled', true);
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
                    console.log(data);
                    if (data.resp == true) {
                        var cliente = data.cliente;
                        $('#p-codigo').html(cliente.cod);
                        $('#p-nombres').html(cliente.nomb);
                        $('#p-grupo').html(cliente.grupo);

                        var tmpl = $.templates("#js-template"); // Get compiled template
                        var html = tmpl.render(data);      // Render template using data - as HTML string
                        $("#id-table-detalle").html(html);          // Insert HTML string into DOM


                    } else {

                    }
                }).fail(function (jqXHR, textStatus) {
                    console.log(textStatus);
                });
            }
        });
    });


</script>
</body>
</html>
