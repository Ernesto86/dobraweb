function fn_validar_ingreso() {
    $.ajax({
        url: 'pages/pw_validar_usuario.php',
        data: {user: $('#login').val(), passw: $('#password').val()},
        type: "POST",
        dataType: "json",
        beforeSend: function () {
            $('#loading').addClass('loading');
            $('#carga').show('slow');
        },
        success: function (data) {
            if (data.resp == true) {
                window.location = '../index.php';
            } else {
                $('#carga').hide('slow');
                $("#loading").removeClass('loading');
                alert(data.error);
            }
        }
        ,
        error: function () {
            $("#loading").removeClass('loading');
            $('#carga').hide('slow');
            alert('Fallo conectando con el servidor');
        }
    });
}