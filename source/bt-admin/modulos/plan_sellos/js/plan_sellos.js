jQuery(document).ready(function(){
	listar();
});

function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500)
}

	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarDatos").jqGrid('setGridParam', {url:"plan_sellos_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid");
}
function Resetear(){
	$("#sellocod").val("");
	$("#sellonombre").val("");
	timeoutHnd = setTimeout(gridReload,500);
}

	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarDatos").jqGrid(
	{
		url:'plan_sellos_lst_ajax.php?rand='+Math.random(),
		postData: datos,
		datatype: "json", 
		colNames:['Cod','Nombre','Estado','Editar','Eliminar'],
		colModel:[
			{name:'sellocod',index:'sellocod', width:25, align:'center'},
			{name:'sellonombre',index:'sellonombre', align:'left'},
			{name:'act',index:'act', width:30,  align:'center', sortable:false},
			{name:'edit',index:'edit', width:30,  align:'center', sortable:false},
			{name:'del',index:'del', width:30,  align:'center', sortable:false}
		], 
		rowNum:20,
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pager2',
		sortname: 'sellocod',
		viewrecords: true,
		sortorder: "DESC", 
		height:290, 
		caption:"", 
		emptyrecords: "Sin datos para mostrar.", 
		loadError : function(xhr,st,err) { 
		} 
	});

		$("#listarDatos").setGridWidth($("#LstDatos").width());
	}).trigger('resize');



	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Actualizando...</h1>',baseZ: 9999999999 })	
	param = "sellocod="+codigo;
	param += "&accion="+tipo;
	EnviarDatos(param);


	if (!confirm("Esta seguro que desea eliminar?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Eliminando...</h1>',baseZ: 9999999999 })	
	param = "sellocod="+codigo;
	param += "&accion=3";
	EnviarDatos(param);


	$.ajax({
		type: "POST",
		url: "plan_sellos_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSucceed==true)
			{
				gridReload();
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