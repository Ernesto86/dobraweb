<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <link rel="stylesheet" href="../css/jquery.treeview_grupo.css"/>
    <script language="javascript">
        $(document).ready(function () {
            $("#browser").treeview({animated: "fast", collapsed: true});
            $("#browser .folder").click(function () {

                $('#dt_grupo').val($(this).html());
                if (this.id == 'gral') {
                    $('#txt_codigo').val('');
                    $('#txt_codigo').focus();
                    return;
                }
                $.ajax({
                    url: 'pw_cli_select_grupo_codigo.php',
                    data: {id: this.id},
                    type: 'POST',
                    cache: false,
                    dataType: "json",
                    success: function (data) {
                        if (data.err == '0') {
                            alert('No se Encontro Codigo');
                            return;
                        }
                        $('#txt_codigo').val(data.cod);
                        $('#txt_codigo').focus();
                    },
                    error: function () {
                        alert('Fallo Conectando con el servidor');
                    }
                });
            });
        });
    </script>
</head>
<body>
<div id="menu_grupos">
    <ul id="browser" class="filetree">
        <li><span class="folder" id="gral">General</span>
            <?php
            require '../clases/cls_cli_grupo.php';
            $obj = new cls_cli_grupo;
            $obj->fn_cli_grupo_treeview(); ?>
        </li>
    </ul>
</div>
</body>
</html>
