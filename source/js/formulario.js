
function Enviar()
{	
	$.blockUI({ message: '<div style="font-size:26px; font-weight:bold"><img src="/imagenes/load-indicator.gif" />&nbsp;Enviando...</h1>',baseZ: 9999999999 })	
	param = $("#formulario_contacto").serialize(); 
	param=param+'&accion=1';
	$.ajax({
	   type: "POST",
	   url: "/formulario/upd",
	   data: param,
	   dataType:"json",
	   success: function(msg){
			if (msg.IsSuccess==true)
			{
				/*$("#formulario_ok").html("<h3>"+msg.Msg+"</h3>");*/
				//$("#formulario_contacto").hide();
				document.getElementById("formulario_contacto").reset();
				$("#formulario_ok").html('<div class="alert alert-success">Gracias por contactarse. Su consulta ser&aacute; enviada al &aacute;rea correspondiente.</div>');
				$("#formulario_ok").show();
				grecaptcha.reset();
				$.unblockUI();	
			}else
			{
				alert(msg.Msg);
				$("#formulario_ok").html('<div class="alert alert-warning">'+msg.Msg+'</div>');
				$("#formulario_ok").show();
				$.unblockUI();
				
			}
	   }
	 });
	 return false;
	
}

$( document ).ready(function() {
   $( "#formulario_contacto .btn_submit" ).click(function() {
	 	 return Enviar();
	});
});
