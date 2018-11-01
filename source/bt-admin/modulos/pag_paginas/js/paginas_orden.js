jQuery(document).ready(function(){
	ListarPaginas();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarPaginas").jqGrid('setGridParam', {url:"pag_paginas_orden_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}

function ListarPaginas()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarPaginas").jqGrid(
	{ 

				url:'pag_paginas_orden_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','T\u00edtulo de P\u00e1gina','Categoria','Orden','Sub p&aacute;ginas'], 
				colModel:[ {name:'pagcod',index:'pagcod', width:20, align:"center",sortable:false},
						  {name:'pagtitulo',index:'pagtitulo',sortable:false}, 						 
						  {name:'catnom',index:'catnom', width:40,sortable:false},
						   {name:'orden',index:'orden', width:15, align:"center"}, 
						  {name:'subpaginas',index:'subpaginas', align:"center", width:25}, 
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'pagcod', 
				viewrecords: true, 
				sortorder: "desc", 
				height:280,
				caption:"",
				emptyrecords: "Sin paginas para mostrar.",
				loadError : function(xhr,st,err) {
                       //alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
					
				
			}); 
	
			$(window).bind('resize', function() {
				$("#ListarPaginas").setGridWidth($("#LstPaginas").width());
			}).trigger('resize');
				jQuery("#ListarPaginas").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			
			
			jQuery("#ListarPaginas").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#ListarPaginas").jqGrid("getDataIDs");
				   catcod= $("#catcod").val();
				   ReordenarPaginasRelacionados(neworder,catcod);
			   }}
			 );
}




function ReordenarPaginasRelacionados(orden,catcod)
{
	$("#MsgGuardando").show();
	 
	param  = "orden="+orden; 
	param += "&catcod="+catcod;
	param += "&accion=4";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "pag_paginas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSuccess)
			{
				
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}


 
 
