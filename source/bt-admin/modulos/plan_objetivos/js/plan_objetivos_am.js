/*jQuery(document).ready(function(){
	initTextEditors();
});

function InsertarTiny(){
	var planobjetivodescripcion = tinyMCE.get('planobjetivodescripcion');
	$("#planobjetivodescripcion").val(planobjetivodescripcion.getContent());
}
*/

function Insertar(){
	var param;
	//InsertarTiny();
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Agregando...</h1>',baseZ: 9999999999 });
	param = $("#formalta").serialize();
	param += "&accion=1";
	EnviarDatosInsertarModificar(param,1);
	return true;
}


function Modificar(){
	var param;
	//InsertarTiny();
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Actualizando...</h1>',baseZ: 9999999999 });
	param = $("#formalta").serialize();
	param += "&accion=2";
	EnviarDatosInsertarModificar(param,2);
	return true;
}


function EnviarDatosInsertarModificar(param,tipo){
	$.ajax({
		type: "POST",
		url: "plan_objetivos_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSucceed==true)
			{
				$("#MsgGuardar").html(msg.Msg);
				$("#MsgGuardar").addClass("show");
				setTimeout(function(){ $("#MsgGuardar").removeClass("show");}, 3000);	
				if (tipo==1)
					window.location=msg.header;
				$.unblockUI();
			}
			else
			{
				alert(msg.Msg);
				$.unblockUI();
			}
		}
	});
}