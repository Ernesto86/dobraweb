var menu_panel_status = 1;
var cab_panel_status = 1;
$(document).ready(function () {
    $("#menu ul li ul").hide();
    $("#menu ul li span.current").addClass("open").next("ul").show();
    $("#menu ul li span").click(function () {
        $(this).next("ul").slideToggle("fast").parent("li").siblings("li").find("ul:visible").slideUp("fast");
        $("#menu ul li").find("span").removeClass("open");
        $(this).addClass("open");
    });
    $("#menu ul li ul li a").click(function () {
        $("#menu ul li ul li").find("a").removeClass("open");
        $(this).addClass("open");
    });
    fn_window();
    $("#btn_es_panel").click(function () {
        if (menu_panel_status == 0) {
//MOSTRAMOS LATERALPANEL
            $('#btn_es_panel').attr("src", "images/flechas/arrow-left.png");
            $('#btn_es_panel').attr("title", "Ocultar Menu");
            $("#menu_panel").css('display', 'block');
            menu_panel_status = 1;
        } else {
//OCULTAMOS LATERALPANEL
            $('#btn_es_panel').attr("src", "images/flechas/arrow-right.png");
            $('#btn_es_panel').attr("title", "Mostrar Menu");
            $("#menu_panel").css('display', 'none');
            menu_panel_status = 0;
        }
    });
    $(window).bind('resize', function () {
        fn_window();
    });
    $("#login_password").click(function () {
        $("#cont_flot").load('pw_seg_form_usuario_password.php');
        $("#cont_flotante").show();
        $("#cont_flotante").css("top", '200px');
        $("#cont_flotante").css("left", ($(window).width() - $("#cont_flotante").width()) / 2);
    });
    $("#cont_flotante .cerrar").click(function () {
        $("#cont_flot").html('');
        $("#cont_flotante").hide("fat");
        event.preventDefault();
    });
});
function fn_window() {
    var Height = document.documentElement.clientHeight - (document.getElementById("blueBarHolder").clientHeight + 1);
    $("#blueBarHolder").css("width", '100%');
    $("#prodi_contenedor").css("width", '100%');
    $("#menu_panel").css("height", Height);
    $("#contenedor").css("height", Height);
}