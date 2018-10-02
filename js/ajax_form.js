function fn_guardar(){
$.ajax({url   : 'pw_guardar_tablas_maestras.php',
		data  : $('#form').serialize()+'&pag='+pag,
		type  : "POST",
		async : true,
	   cache  : false,				   
	   contentType: "application/x-www-form-urlencoded",
	ifModified: false,
	beforeSend: function(objeto){$('#loading').addClass('loading');},
		success: function(resp){
		if(parseInt(resp)>= 1){  
			$("#loading").removeClass('loading');
			switch($('#opc').val()){											 
			 case 'M':alert('El Registro se Guardo Correctamente');break;
			 default:resp=confirm('El Registro se Guardo Correctamente \n Desea Continuar Ingresando');
				  if(resp){
					 location.reload();
					 resp.stopPropagation();
				  }		  
			}
			window.location='pw_mantenimiento.php?id='+$('#url').val(); 										
			resp.stopPropagation();																											 	  }
	$("#loading").removeClass('loading');		
	alert('Error: '+ resp +'..?');								   
	resp.stopPropagation();							  														
	},
timeout: 3000						
});	
}
