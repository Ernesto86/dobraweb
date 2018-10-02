var load_gif='<div class="load_gif" align="center"><img src="../images/loader.gif"></div>';
function fn_image(){
$.ajax({	url    : 'pw_carga_images.php', 
			type   : 'POST',
			cache  : false,
			contentType: "application/x-www-form-urlencoded",
			beforeSend: function(objeto){ $("#subir_image").html(load_gif);},
			success: function(datos){
					 $("#subir_image").html(datos);
					 return;
			}
	   });
}
function fn_guardar(img){
$.ajax({url   : 'pw_guardar_'+pag+'.php',
		data  : $('#frm_'+pag ).serialize(),
		type  : "POST",
		async : true,
	   cache  : false,
   contentType: "application/x-www-form-urlencoded",
	ifModified: false,
	beforeSend: function(objeto){$('#loading').addClass('loading');},
		success: function(resp){ 
				   if(parseInt(resp) >= 1) {
						 id = resp;
						 $("#loading").removeClass('loading');
						 alert('Registro Guardado Correctamente..'); 
						 if (parseInt(img)==1 && $('#opc').val()!='M'){
							 $('#btn_enviar').hide()
							 $('#btn_nuevo').show()
							 fn_image();
							 resp.stopPropagation();
						 }
						window.location=$('#url').val(); 
						resp.stopPropagation();
					 }
				  $("#loading").removeClass('loading');	 
				  alert('Error: '+ resp +'..?');
				  resp.stopPropagation();								  														
				},
		timeout: 3000
});
}