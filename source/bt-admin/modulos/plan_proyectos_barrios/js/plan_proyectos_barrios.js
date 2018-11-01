jQuery(document).ready(function(){
	Listar_plan_proyectos_barrios();
});

var timeoutHnd;
function doSearch_plan_proyectos_barrios(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload_plan_proyectos_barrios,500)
}

function gridReload_plan_proyectos_barrios(){
	var datos = $("#formbusqueda_plan_proyectos_barrios").serializeObject();
	jQuery("#listarDatos_plan_proyectos_barrios").jqGrid('setGridParam', {url:"plan_proyectos_barrios_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid");
}
function Resetear_plan_proyectos_barrios(){
	timeoutHnd = setTimeout(gridReload,500);
}

function Listar_plan_proyectos_barrios(){
	var datos = $("#formbusqueda_plan_proyectos_barrios").serializeObject();
	jQuery("#listarDatos_plan_proyectos_barrios").jqGrid(
	{
		url:'plan_proyectos_barrios_lst_ajax.php?rand='+Math.random(),
		postData: datos,
		datatype: "json", 
		colNames:['Proyecto','Barrio','Eliminar'],
		colModel:[
			{name:'planproyectocod',index:'planproyectocod', width:25, align:'center',hidden:true},
			{name:'barrionombre',index:'barrionombre', align:'left'},
			{name:'del',index:'del', width:30,  align:'center', sortable:false}
		], 
		rowNum:20,
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pager_plan_proyectos_barrios',
		sortname: 'planproyectocod',
		viewrecords: true,
		sortorder: "DESC", 
		height:290, 
		caption:"", 
		emptyrecords: "Sin datos para mostrar.", 
		loadError : function(xhr,st,err) { 
		} 
	});

	$(window).bind('resize', function() {
		$("#listarDatos_plan_proyectos_barrios").setGridWidth($("#LstDatos_plan_proyectos_barrios").width());
	}).trigger('resize');

	jQuery("#listarDatos_plan_proyectos_barrios").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});

}

function EliminarBarrio(planproyectocod,barriocod){
	if (!confirm("Esta seguro que desea eliminar?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Eliminando...</h1>',baseZ: 9999999999 })	
	param = "planproyectocod="+planproyectocod;
	param += "&barriocod="+barriocod;
	param += "&accion=3";
	EnviarDatos_plan_proyectos_barrios(param);

}

function AgregarBarrio()
{
	if($("#formalta_plan_proyectos_barrios #barriocod").val()=="")
	{
		alert("Debe selecionar un barrio");
		return false;	
	}
	
	if (!confirm("Esta seguro que desea agregar el barrio?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Agregando...</h1>',baseZ: 9999999999 })	
	param = $("#formalta_plan_proyectos_barrios").serialize();
	param += "&accion=1";
	EnviarDatos_plan_proyectos_barrios(param);
	
}

function EnviarDatos_plan_proyectos_barrios(param){
	$.ajax({
		type: "POST",
		url: "plan_proyectos_barrios_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSucceed==true)
			{
				gridReload_plan_proyectos_barrios();
				$("#formalta_plan_proyectos_barrios #barriocod").val("");
				alert(msg.Msg);
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