<table width="100%" border="0" cellpadding="0" cellspacing="0" id="table_cont_busq">
    <tr>
        <td>
            <form id="frm_busc_cliente" name="frm_busc_cliente">
                <div id="cont_parametros">
                    <table width="562" border="0" cellpadding="1" cellspacing="2">
                        <tr>
                            <td><strong>Descripci&oacute;n :</strong></td>
                            <td>
                                <input name="txt_buscar" type="text" id="txt_buscar" size="25" class="txt_det_buscar">
                            </td>
                            <td>&nbsp;</td>
                            <td><input name="btn_buscar" type="button" class="btn" id="btn_buscar" value="Buscar."
                                       style="display:none"/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td align="right"><strong>Nombres</strong></td>
                            <td>
                                <input name="radiobutton" type="radio" value="N" checked="checked" class="rdb"/></td>
                            <td>&nbsp;</td>
                            <td align="right"><strong>C&oacute;digo</strong></td>
                            <td><input name="radiobutton" type="radio" value="C" class="rdb"/></td>
                            <td>&nbsp;</td>
                            <td><strong>Folder</strong></td>
                            <td><input name="radiobutton" type="radio" value="F" class="rdb"/></td>
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
                        <th>C&oacute;digo</th>
                        <th>Nombre</th>
                        <th>Folder</th>
                        <th>Zona</th>
                    </tr>
                    </thead>
                    <tbody id="detalle">
                    </tbody>
                </table>
            </div>
        </td>
    </tr>
</table>
