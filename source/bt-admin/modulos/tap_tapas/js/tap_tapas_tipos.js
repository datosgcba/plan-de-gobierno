jQuery(document).ready(function(){
	listarTipos();	
	 $(document).on("click", "#Guardar", function() {  
	 	ProcesarGuardar();
	 });
});
	
var timeoutHnd; 
function doSearchTipos(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReloadTipos,500) 
}

function gridReloadTipos(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarTipos").jqGrid('setGridParam', {url:"tap_tapas_tipos_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
	return false;
} 


function Resetear()
{
	//RESETEAR BUSQUEDAS
	$("#tapatipodesc").val("");
	timeoutHnd = setTimeout(gridReloadTipos,500) 
}


function listarTipos()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarTipos").jqGrid(
	{ 
		url:'tap_tapas_tipos_lst_ajax.php?rand='+Math.random(),
		postData: datos,
		datatype: "json", 
		colNames:['#','Nombre','Url','Nombre Archivo','Act/Desc','Editar','Eliminar'], 
		colModel:[ {name:'tapatipocod',index:'tapatipocod', width:20, align:"center",sortable:false}, 
				  {name:'tapatipodesc',index:'tapatipodesc'},
				  {name:'tapatipourlfriendly',index:'tapatipourlfriendly', align:"left",width:70}, 							
				  {name:'tapatipoarchivo',index:'tapatipoarchivo', align:"left",width:40}, 							
				  {name:'act',index:'act', width:70, align:"center", sortable:false},
				  {name:'edit',index:'edit', width:30, align:"center", sortable:false},
				  {name:'del',index:'del', width:30, align:"center", sortable:false},
			  ], 
		rowNum:20, 
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pager2', 
		sortname: 'tapatipocod', 
		viewrecords: true, 
		sortorder: "asc", 
		height:290,
		caption:"",
		emptyrecords: "Sin tipos para mostrar.",
		loadError : function(xhr,st,err) {
			  // alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
				//alert("Error al procesar los datos");
		},
	
	}); 

	$(window).bind('resize', function() {
		$("#listarTipos").setGridWidth($("#LstTipos").width());
	}).trigger('resize');
		jQuery("#listarTipos").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}
	
function FormTipos(modif,tapatipocod)
{
	$.blockUI({ message: '<div class="h1block"><div class="load-sistema"></div>Cargando...</div>',baseZ: 9999999999 });
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param += "&tapatipocod="+tapatipocod;
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_tipos_am.php",
	   data: param,
	   success: function(msg){
			
		   	$("#DataPortada").html(msg);
			$('#ModalPortadas').modal('show');
			$("#cargando").hide();
			$(".chzn-select").chosen();
			$.unblockUI()
	   }
	 });

	return true;
}

function AltaTipo()
{
	FormTipos(0,'');
	return true;
}
	
function EditarTipo(tapatipocod)
{
	FormTipos(1,tapatipocod);
	return true;
}
	
	
function EnviarDatos(param)
{
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_tipos_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReloadTipos();
			$('#ModalPortadas').modal('hide');
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

function EliminarTipo(tapatipocod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el Tipo de portada?"))
		return false;
	$.blockUI({ message: '<div class="h1block"><div class="load-sistema"></div>Eliminando...</div>',baseZ: 9999999999 });
	param = "tapatipocod="+tapatipocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(tapatipocod,tipo)
{
	$.blockUI({ message: '<div class="h1block"><div class="load-sistema"></div>Procesando...</div>',baseZ: 9999999999 });
	var param;
	param = "tapatipocod="+tapatipocod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}

function DialogClose()
{

	 $("#Popup").dialog("close"); 
}

function InsertarTipo()
{
	var param;
	$.blockUI({ message: '<div class="h1block"><div class="load-sistema"></div>Agregando tipo...</div>',baseZ: 9999999999 });
	param = $("#formulario").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}
//va al upd con la accion 2
function ModificarTipo(tapatipocod)
{
	$.blockUI({ message: '<div class="h1block"><div class="load-sistema"></div>Modificando datos...</div>',baseZ: 9999999999 });
	param = $("#formulario").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}


function ProcesarGuardar()
{
	var $tapatipocod = $("#formulario #tapatipocod").val();
	
	if ($tapatipocod=="")
		InsertarTipo()
	else
		ModificarTipo($tapatipocod);	
	
}



function CargarMenu()
{
	var param="tipo=4&menutipocod="+$("#menutipocod").val();
	$("#Menus").html("Cargando menu...");	
	$.ajax({
	   type: "POST",
	   url: "combo_ajax.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$("#Menus").html(msg);	 
			$(".chzn-select").chosen();
	   }
	   
	 });
}



