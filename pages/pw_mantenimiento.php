<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <?php session_start();
    if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
        die('<--Inicie Session ...');
    }
    if (!isset($_GET['id']) or !isset($_GET['menu'])) {
        die('Error :al Recibir Parametros');
    }

    $pag = strtolower($_GET['menu']);
    $_SESSION['dtitle'] = $_GET['tit']; ?>

    <!-- InstanceBeginEditable name="parametros" -->
    <link href="../css/consultas.css" rel="stylesheet" type="text/css"/>
    <!-- InstanceEndEditable -->
    <script src="../js/grid_table.js" type="text/javascript"></script>
    <!-- InstanceBeginEditable name="js" -->
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script src="../js/jquery.colorbox.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#consulta a.vista").colorbox({title: 'CONSULTA DE REGISTRO.'})
        });
    </script>
    <!-- InstanceEndEditable -->
    <script type="text/javascript">
        var pag = <?php echo "'$pag'"?>;

        function fn_editar(id) {
            window.location = 'pw_' + pag + '_form.php?opc=M&id=' + id + '&idmenu=' + $('#id_menu').val() + '&dtmenu=' + $('#dt_menu').val();
        }

        function fn_agregar() {
            window.location = 'pw_' + pag + '_form.php?opc=I&idmenu=' + $('#id_menu').val() + '&dtmenu=' + $('#dt_menu').val();
        }

        function fn_elimina(id) {
            resp = confirm('Desea Eliminar este Registro...?');
            if (resp) {
                window.location = 'pw_eliminar.php?id=' + id + '&pag=' + pag + '&idmenu=' + $('#id_menu').val();
            }
        }
    </script>
</head>
<body>
<div>
    <div id="buscar">
        <div id="title_form" class="title_form"><img src="../images/icon/list-view.png"/>
            Mantenimiento: <?php echo $_GET['tit']; ?></div>
        <div id="btns_accion">
            <div style="display:none">
                <input type="hidden" name="id_menu" id="id_menu" value="<?php echo intval($_GET['id']); ?>"/>
                <input type="hidden" name="dt_menu" id="dt_menu" value="<?php echo $pag; ?>"/>
            </div>
            <!-- InstanceBeginEditable name="btn_accions" -->
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td><?php
                        require '../clases/cls_my_transaccion.php';
                        $otrans = new cls_my_transaccion;
                        $otrans->transacciones($_GET['id']);
                        $trans = $otrans->campos();
                        unset($otrans);
                        if ($trans['agregar']) {
                            ?>
                            <input name="btn_nuevo" type="button" class="btn_nuevo" id="btn_nuevo" value="Nuevo"
                                   title="Nuevo Registros" onclick="fn_agregar();"/>
                        <?php } ?>
                    </td>
                    <td><input name="btn_actualizar" type="button" class="btn_actualizar" id="btn_actualizar"
                               value="Actualizar" title="Actualizar Registros" onclick="location.reload();"/></td>
                    <td><input name="btn_salir" type="button" class="btn_salir" id="btn_salir" value="Salir"
                               onclick="location='digital-clock/index.html'" title="Cerrar Ventana"/></td>
                </tr>
            </table>
            <!-- InstanceEndEditable -->
        </div>
    </div>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="1130">
                <div class="search">
                    <table border="0" align="left" cellpadding="2" cellspacing="0" class="tabla_cab">
                        <tr>
                            <!-- InstanceBeginEditable name="accion" --><!-- InstanceEndEditable -->
                            <td>
                                <div align="right">Buscar</div>
                            </td>
                            <td>
                                <select name="select" id="columns" style="display:none;"
                                        onchange="sorter.search('query')">
                                </select>
                                <input name="text" type="text" id="query" class="" onkeyup="sorter.search('query')"
                                       size="30" maxlength="25"/>
                                <div style="display:none">Reg. <span id="startrecord"></span>-<span
                                            id="endrecord"></span> de <span id="totalrecords"></span></div>
                                <div>
                                    <div style="display:none"><a href="javascript:sorter.reset()">reset</a></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="consulta">
                    <!-- InstanceBeginEditable name="resultado" -->
                    <?php
                    $class = 'cls_my_' . $pag;
                    require '../clases/' . $class . '.php';
                    $obj = new $class;
                    if ($trans['modificar']) {
                        $obj->s_perfil('Edit');
                        $obj->s_enlace('javascript:fn_editar(*)');
                        $obj->s_titulo('<img title="Editar Registro" src="../images/accion/edit.png"/>');
                    }
                    if ($trans['eliminar']) {
                        $obj->s_perfil('Elim');
                        $obj->s_enlace('javascript:fn_elimina(*)');
                        $obj->s_titulo('<img title="Eliminar Registro" src="../images/accion/eliminar.png"/>');
                    }
                    $obj->consulta();
                    $obj->s_perfil('Vista');
                    $obj->s_enlace('pw_' . $pag . '_form.php?id=*&opc=V&idmenu=' . $_GET['id']);
                    $obj->s_titulo('<img title="Ver Detalle de Registro" src="../images/vista/vista.png"/>');
                    $obj->dt_tabla(); ?>
                    <!-- InstanceEndEditable --></div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="tablefooter">
                    <div id="tablenav">
                        <div id="nav_tab">
                            <img src="../images/navegacion/paging_far_left.gif" alt="First Page"
                                 onClick="sorter.move(-1,true)"/>
                            <img src="../images/navegacion/paging_left.gif" alt="First Page" onClick="sorter.move(-1)"/>
                            <img src="../images/navegacion/paging_right.gif" alt="First Page" onClick="sorter.move(1)"/>
                            <img src="../images/navegacion/paging_far_right.gif" alt="Last Page"
                                 onClick="sorter.move(1,true)"/>
                        </div>
                        <div class="nav_margen">
                            <select id="pagedropdown"></select>
                        </div>
                        <div align="center" class="relleno_top">
                            <input name="btn_todo" class="btn" type="submit" id="btn_todo" value="Todo"
                                   onclick="javascript:sorter.showall()"/>
                        </div>
                    </div>
                    <div>
                        <div class="nav_margen">
                            <select onChange="sorter.size(this.value)">
                                <option value="15" selected="selected">15</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="totalpages"><span>Total Pag </span><span id="currentpage"></span> de <span
                                    id="totalpages"></span></div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <!-- InstanceBeginEditable name="js_body" -->
    <script src="../js/tyni_table.js"></script>
    <!-- InstanceEndEditable -->
</div>
</body>
<!-- InstanceEnd --></html>
