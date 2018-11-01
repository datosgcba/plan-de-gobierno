jQuery(document).ready(function(){
	listarTapas();	
		
	 $(document).on("click", "#Guardar", function() {  
	 	ProcesarGuardar();
	 });

});
	
var timeoutHnd; 
	
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) ;
}

function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarTapas").jqGrid('setGridParam', {url:"tap_tapas_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
	return false;
} 

function Resetear()
{
	//RESETEAR BUSQUEDAS
	$("#tapanom").val("");
	$("#tapatipocod").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}

	function listarTapas()
	{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarTapas").jqGrid(
	{ 

				url:'tap_tapas_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Tipo','Plantilla','Activa','Confeccionar','Metadatos','Act/Desc','Edit','Del'], 
				colModel:[ {name:'tapacod',index:'tapacod', width:20, align:"center"}, 
						  {name:'tapanom',index:'tapanom'}, 	
						  {name:'tapatipodesc',width:55,index:'tapatipodesc'},
						  {name:'plantdesc',width:55,index:'plantdesc'},
						  {name:'poract',index:'poract',  width:25,align:"center", sortable:false},
						  {name:'conf',index:'conf',  width:50,align:"center", sortable:false},
						  {name:'meta',index:'meta',  width:40,align:"center", sortable:false},
						  {name:'act',index:'act', width:70, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:30, align:"center", sortable:false},
						  {name:'del',index:'del', width:30, align:"center", sortable:false},
					  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'tapacod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:290,
				caption:"",
				emptyrecords: "Sin tapas para mostrar.",
				/*
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},*/
				loadError : function(xhr,st,err) {
                      // alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
			
			}); 
	
			$(window).bind('resize', function() {
				$("#listarTapas").setGridWidth($("#LstTapas").width());
			}).trigger('resize');
				jQuery("#listarTapas").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}
	
function FormTapas(modif,tapacod)
{
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param += "&tapacod="+tapacod;
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_am.php",
	   data: param,
	   success: function(msg){
		   	$("#DataPortada").html(msg);
			$('#ModalPortadas').modal('show');
			$("#cargando").hide();
	   }
	 });

	return true;
}

function AltaTapas()
{
	FormTapas(0,'');
	return true;
}
	
function EditarTapas(tapacod)
{
	FormTapas(1,tapacod);
	return true;
}
	
function EnviarDatos(param)
{
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReload();
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

function EliminarTapa(tapacod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la Tapa?"))
		return false;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Eliminando tapa...</h1>',baseZ: 9999999999 })	
	param = "tapacod="+tapacod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(tapacod,tipo)
{
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Actualizando...</h1>',baseZ: 9999999999 })	
	param = "tapacod="+tapacod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}


function ProcesarGuardar()
{
	var $tapacod = $("#formulario #tapacod").val();
	
	if ($tapacod=="")
		InsertarTapas()
	else
		ModificarTapas($tapacod);	
	
}

function InsertarTapas()
{
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Agregando tapa...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}
//va al upd con la accion 2
function ModificarTapas(tapacod)
{
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Actualizando datos...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}


function MetadatosTapas(tapacod)
{
	var param, url;
	$("#cargando").show();
	param += "&tapacod="+tapacod;
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_metadata.php",
	   data: param,
	   success: function(msg){
		   	$("#DataPortada").html(msg);
			$('#ModalPortadas').modal('show');
			$("#cargando").hide();
	   }
	 });

	return true;
}



function GuardarMetadata()
{
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Actualizando datos...</h1>',baseZ: 9999999999 })	
	var param;
	param = $("#formulario").serialize();
	param += "&accion=6";
	EnviarDatos(param);
}




