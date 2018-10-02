<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <link rel="stylesheet" type="text/css" href="../css/flotant_busc.css">
    <script language="javascript">
        $(document).ready(function () {
            $('#txt_buscar').focus();
            fn_prod_buscar();
        });
        function fn_prod_buscar() {
            $('#dt_clientes #detalle').load('pw_det_buscar_producto.php', {
                desc: $('#txt_buscar').val(),
                opc: $("input[name=radiobutton]:checked").val()
            });
        }
    </script>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="table_cont_busq">
    <tr>
        <td>
            <form id="frm_busc_producto" name="frm_busc_producto" action="javascript:fn_prod_buscar();">
                <div id="cont_parametros">
                    <table width="562" height="32" border="0" cellpadding="1" cellspacing="2">
                        <tr>
                            <td><strong>Descripci&oacute;n :</strong></td>
                            <td><input name="txt_buscar" type="text" id="txt_buscar" size="25" class="txt_det_buscar"/>
                            </td>
                            <td>&nbsp;</td>
                            <td><input name="btn_buscar" type="submit" class="btn" id="btn_buscar" value="Buscar."/>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right"><strong>Nombres</strong></td>
                            <td><input name="radiobutton" type="radio" value="N" checked="checked" class="rdb"/></td>
                            <td>&nbsp;</td>
                            <td align="right"><strong>C&oacute;digo</strong></td>
                            <td><input name="radiobutton" type="radio" value="C" class="rdb"/></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </form>
        </td>
    </tr>
    <tr>
        <td>
            <div id="dt_clientes">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" id="table_detbuscar">
                    <thead>
                    <tr>
                        <th>N#</th>
                        <th>C�digo</th>
                        <th>Nombre</th>
                    </tr>
                    </thead>
                    <tbody id="detalle">
                    </tbody>
                </table>
            </div>
        </td>
    </tr>
</table>
</body>
</html>