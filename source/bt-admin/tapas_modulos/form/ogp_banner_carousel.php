<? 
//print_r($vars);
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));
$bannercod="";
$BannerLink="";
$BannerTitulo="";
$BannerLink="";
$BannerTexto="";
$muestroagregar=false;
$cargobanners=false;
$MuestraBotonera = 1;
$TipoCarrousel = 1;

$popup_titulo = "";
$popup_mensaje = "";
$popup_boton = "";
$popup_boton_muestra = "";
$popup_boton_ubic = "left";



if (isset($vars['zonamodulocod']))
{
	$muestroagregar=true;
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->bannercod) && count($objDataModel->bannercod)>0)
		$cargobanners=true;
		
		
	if (isset($objDataModel->popup_titulo))
		$popup_titulo = $objDataModel->popup_titulo;
	
	if (isset($objDataModel->popup_mensaje))
		$popup_mensaje = $objDataModel->popup_mensaje;	
		
	if (isset($objDataModel->popup_boton))
		$popup_boton = $objDataModel->popup_boton;	

	if (isset($objDataModel->popup_boton_muestra))
		$popup_boton_muestra = $objDataModel->popup_boton_muestra;	

	if (isset($objDataModel->popup_boton_ubic))
		$popup_boton_ubic = $objDataModel->popup_boton_ubic;	

}
$oBanner= new cBanners($vars['conexion']);
$datos=array(); 
$datos['bannertipocod']="2";
$datos['orderby'] = "bannercod desc";
$datos['limit'] = "LIMIT 0,20";
if(!$oBanner->BusquedaAvanzada($datos,$resultadobanners,$numfilas)) {
	$error = true;
}

?>
<div class="col-md-4">
    <div style="padding-top:10px">
        <div style="float:left; width:280px; text-align:left;"><input type="text" style="width:100%;" id="BannerDesc" name="BannerDesc" onkeypress="SearchBanner(arguments[0]||event)"></div>
		<div style="height:250px; overflow-y:auto; margin-top:5px; float:left">
            <table  class="tableseleccion">
                     <tr>
                        <th width="10%">C&oacute;d</th>
                        <th width="50%">Titulo</th>
                        <th width="20%">Im&aacute;gen</th>
                        <th width="20%">Agregar</th>
                     </tr> 
                     <tbody id="bannerLstData">
          <?  if ($numfilas>0)
            {
                
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadobanners))
                {?>
                        <tr>
                            <td style="text-align:center"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['bannercod'],ENT_QUOTES); ?></td>
                            <td style="text-align:left" id="bannerdesc_<? echo $fila['bannercod']?>"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['bannerdesc'],ENT_QUOTES); ?></td>
                             <td style="text-align:left" id="bannerimg_<? echo $fila['bannercod']?>"><img style="width:50px; " src="<? echo  DOMINIO_SERVIDOR_MULTIMEDIA."banners/".$fila['bannerarchubic']?>"/> </img> &nbsp;</td>                               
                            <td style="text-align:center; font-weight:bold;">
                               <a class="add_image"  style="text-align:center; margin-left:15px" href="javascript:void(0)" onclick="AgregarBanner(<? echo $fila['bannercod']?>)">&nbsp;</a>
                               <input type="hidden" name="bannerlink_<? echo $fila["bannercod"]?>" id="bannerlink_<? echo $fila["bannercod"]?>" value="<? echo $fila['bannerurl']; ?>"/>
                            </td>
                        </tr> 
            <?
                }
            }
            ?>
            		</tbody>            
            </table> 
  	    </div><!-- Cierre div-->
    </div><!-- Cierre div-->
</div><!-- Cierre div columna izq-->

<div class="col-md-4">
    <div style="text-align:left; margin-top:5px;">
        <h2 class="titulopopup">Banner Seleccionado</h2>
        <div style="margin:5px 1px 5px 0px">
            <div style="float:left; width:200px">Nombre del banner:</div>
            <div id="TituloBanner" style="float:left; width:71%; text-align:left; font-weight:bold"></div><!-- Cierre TituloBanner-->
            <div style="clear:both; height:1px;">&nbsp;</div>
            <input type="hidden" name="bannercod" id="bannercod" value="" />
        </div><!-- Cierre div-->
    </div><!-- Cierre div-->
    <div style="clear:both; height:1px;"></div>
	<div style="margin:5px 0px 5px 0px;clear:both;">
        <label>T&iacute;tulo:</label>
        <input type="text" value="" id="BannerTitulo" name="BannerTitulo" class="form-control input-md"/>
    </div><!-- Cierre div ancho-->
    <div style="clear:both">&nbsp;</div>
     <div style="margin:5px 0px 5px 0px;clear:both;">
        <label>Texto:</label>
        <div style="clear:both"></div>
       <textarea class="form-control input-md" id="BannerTexto" name="BannerTexto" cols="40" rows="3"></textarea>
    </div><!-- Cierre div ancho-->
    <div style="clear:both">&nbsp;</div>
    <div style="margin:5px 0px 5px 0px;clear:both;">
        <label>Link:</label>
        <input class="form-control input-md" type="text" value="" id="BannerLink" name="BannerLink"/>
    </div><!-- Cierre div ancho-->
    
    <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" id="AgregarBanner" onclick="AgregarBannerTabla()">Agregar</a>
                <a href="javascript:void(0)" id="ModificarBanner" style="display:none" onclick="ModificarBannerTabla()">Modificar</a>
                <a href="javascript:void(0)" id="CancelarBanner" style="display:none" onclick="CancelarAccionModif()">Cancelar</a>
            </li>
        </ul>
    </div><!-- Cierre menucarga-->
</div> <!-- Cierre columna derecha-->

<div class="col-md-4">
<h2 class="titulopopup">Popup</h2>
    <div class="form-group clearfix">
        <label>Muestra boton</label>
		<select name="popup_boton_muestra" id="popup_boton_muestra"  class="form-control input-md" onChange="MostrarBoton()">
        	<option <? echo ($popup_boton_muestra==1)?"selected=selected":""; ?> value="1" >Si</option>
        	<option <? echo ($popup_boton_muestra==0)?"selected=selected":""; ?> value="0" >No</option>
        </select>
        <div class="clearboth">&nbsp;</div>
        <div id="mostrarbtn" style="display:<? echo ($popup_boton_muestra==1)?"block":"none"; ?>">
            <label>Titulo</label>
            <input class="form-control input-md" type="text" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($popup_titulo),ENT_QUOTES)?>" id="popup_titulo" name="popup_titulo" maxlength="100" size="100" />
            <div class="clearboth">&nbsp;</div>
            <label>Mensaje</label>
            <textarea name="popup_mensaje" id="popup_mensaje"  class="form-control input-md textarea full rich-text" cols="80" rows="10"><?php  echo $popup_mensaje?></textarea>
            <div class="clearboth">&nbsp;</div>
        	<label>Ubicaci&oacute;n del boton</label>
            <select id="popup_boton_ubic" name="popup_boton_ubic" class="form-control input-md">
            	<option value="left" <? echo ($popup_boton_ubic=="left")?"selected=selected":""; ?>>Izquierda</option>
            	<option value="right" <? echo ($popup_boton_ubic=="right")?"selected=selected":""; ?>>Derecha</option>
            </select>
            <div class="clearboth">&nbsp;</div>
        	<label>Texto Boton</label>
        	<input class="form-control input-md" type="text" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($popup_boton),ENT_QUOTES)?>" id="popup_boton" name="popup_boton" maxlength="100" size="100" />
    	</div>
        <div class="clearboth" style="padding-top:15px;">&nbsp;</div>
    </div>
</div>


<div style="clear:both">&nbsp;</div>
<div>
    <table class="tableseleccion">
             <tr>
                <th width="5%">C&oacute;d</th>
                <th width="50%">Titulo</th>
                <th width="15%" style="text-align:center">Orden</th>
                <th width="15%" style="text-align:center">Editar</th>
                <th width="15%" style="text-align:center">Eliminar</th>
             </tr> 
             <tbody id="TituloBannerTabla">             
				<? if ($cargobanners){
						foreach ($objDataModel->bannercod as $bannercod)
						{
							$datosbusqueda['bannercod'] = $bannercod;
							if(!$oBanner->BuscarBannerxCodigo($datosbusqueda,$resultado,$numfilas))
								return false;
							
							$datosbanner = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
							?>
                            <tr id="trbannercod_<? echo $bannercod?>">
                            	<td>
                                	<? echo $bannercod;?>
                                    <input type="hidden" id="bannercod_<? echo  $bannercod?>" name="bannercod[<? echo  $bannercod?>]" value="<? echo  $bannercod?>" />
                                     <input type="hidden" id="BannerTitulo_<? echo  $bannercod?>" name="BannerTitulo[<? echo  $bannercod?>]" value="<? echo  utf8_decode($objDataModel->BannerTitulo->{$bannercod})?>" />
                                      <input type="hidden" id="BannerLink_<? echo  $bannercod?>" name="BannerLink[<? echo  $bannercod?>]" value="<? echo  $objDataModel->BannerLink->{$bannercod}?>" />
                                      <input type="hidden" id="BannerTexto_<? echo  $bannercod?>" name="BannerTexto[<? echo  $bannercod?>]" value="<? echo  utf8_decode($objDataModel->BannerTexto->{$bannercod})?>" />
                               </td>
                            	<td id="titulo_<? echo $bannercod?>">
                               		<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($objDataModel->BannerTitulo->{$bannercod}),ENT_QUOTES)?>
                                </td>
                            	<td style="text-align:center">
                                	<a href="javascript:void(0)" class="orden" title="Ordenar">
                                    	<img src='modulos/tap_tapas/imagenes/move.png' style='cursor:pointer' alt='Ordenar' />
                                    </a>
                                </td>
                            	<td  style="text-align:center">
                                 	<a href="javascript:void(0)" onclick="Editar(<? echo $bannercod?>)" title="Editar">
                                    	<img src='modulos/tap_tapas/imagenes/edit_action.gif' style='cursor:pointer' alt='Editar' />
                                    </a>
                               </td>
                            	<td  style="text-align:center">
                                 	<a href="javascript:void(0)" onclick="Eliminar(<? echo $bannercod?>)" title="Eliminar">
                                    	<img src='modulos/tap_tapas/imagenes/eliminar_tmp.png' style='cursor:pointer' alt='Ordenar' />
                                    </a>
                               </td>
                            </tr>
                            <? 
						}
                }?>
             </tbody>            
    </table> 
</div> 
<div style="clear:both; height:30px;">&nbsp;</div>
<div class="menucarga" style="text-align:right">
    <ul>
        <li>
            <a class="btn btn-success" href="javascript:void(0)" onclick="ModificaryCerrar()">Guardar y Cerrar</a>
            <? if (!$muestroagregar) {   ?>
            <a class="btn btn-success" href="javascript:void(0)" onclick="agregarModulo()">Guardar y Agregar otro</a>
            <? } ?>
        </li>
    </ul>
</div>           




<script type="text/javascript">
	
	function ModificaryCerrar()
	{
		var Cuerpo = tinyMCE.get('popup_mensaje');
		$("#popup_mensaje").val(Cuerpo.getContent());

		saveModulo()
	}
	
	jQuery(document).ready(function(){
		MovimientoOrden();
		iniciarTextEditors();	
	});


	function AgregarBanner(codigo)
	{
		CancelarAccionModif();
		$("#BannerTitulo").val($("#bannerdesc_"+codigo).html());
		$("#BannerLink").val($("#bannerlink_"+codigo).val());
		$("#bannercod").val(codigo);
	}
	
	
	function ModificarBannerTabla()
	{
		$("#TituloBanner_"+$("#bannercod").val()).val($("#TituloBanner").val());
		$("#Alineacion_"+$("#bannercod").val()).val($("#Alineacion").val());
		$("#MuestraDescripcion_"+$("#bannercod").val()).val($("#MuestraDescripcion").val());
		$("#BannerTitulo_"+$("#bannercod").val()).val($("#BannerTitulo").val());
		$("#Link_"+$("#bannercod").val()).val($("#Link").val());
		$("#BannerTexto_"+$("#bannercod").val()).val($("#BannerTexto").val());
		CancelarAccionModif();
	}
	
	
	function AgregarBannerTabla()
	{
		var html = "";
		if ($("#bannercod").val()=="")
		{
			alert("Debe seleccionar un banner")
			return false;	
		}
		var codigo = $("#bannercod").val();
		if($("#bannercod_"+codigo).length==0)
		{
			var titulo = $("#bannerdesc_"+codigo).html();
			var input = $('<input>').attr({type: 'hidden',id: 'bannercod_'+codigo, name: 'bannercod['+codigo+']', value:codigo});
			var inputBannerTitulo = $('<input>').attr({type: 'hidden',id: 'BannerTitulo_'+codigo, name: 'BannerTitulo['+codigo+']', value:$("#BannerTitulo").val()});
			var inputLink = $('<input>').attr({type: 'hidden',id: 'BannerLink_'+codigo, name: 'BannerLink['+codigo+']', value:$("#BannerLink").val()});
			var inputBannerTexto = $('<input>').attr({type: 'hidden',id: 'BannerTexto_'+codigo, name: 'BannerTexto['+codigo+']', value:$("#BannerTexto").val()});
	
	
			var aEliminar = $('<a>').attr({onclick: 'Eliminar('+codigo+')'}).html("<img src='modulos/tap_tapas/imagenes/eliminar_tmp.png' style='cursor:pointer' alt='Eliminar' />");
			var aEditar = $('<a>').attr({onclick: 'Editar('+codigo+')'}).html("<img src='modulos/tap_tapas/imagenes/edit_action.gif' style='cursor:pointer' alt='Editar' />");
			var aOrden = $('<a>').attr({class: 'orden', onclick: 'javascript:void(0)'}).html("<img src='modulos/tap_tapas/imagenes/move.png' style='cursor:pointer' alt='Ordenar' />");
	
			var TDcodigo = $('<td>').html(codigo).append(input).append(inputBannerTitulo).append(inputBannerTexto).append(inputLink);
			var TDtitulo = $('<td>').attr({id: 'titulo_'+codigo}).html(titulo);
			var TDorden = $('<td>').attr({style:'text-align: center'}).html(aOrden);
			var TDeditar = $('<td>').attr({style:'text-align: center'}).html(aEditar);
			var TDeliminar = $('<td>').attr({style:'text-align: center'}).html(aEliminar);
	
			var tr = $('<tr>').attr({id: 'trbannercod_'+codigo});
			tr.append(TDcodigo);
			tr.append(TDtitulo);
			tr.append(TDorden);
			tr.append(TDeditar);
			tr.append(TDeliminar);
			$("#TituloBannerTabla").append(tr);
			MovimientoOrden();
			LimpiarDatos();
		}else
			alert("El banner ya se encuentra dentro del carrousel.")
	}
	
	
	function Eliminar(codigo)
	{
		if (!confirm("Esta seguro que desea eliminar el banner?"))
			return false;
		$("#trbannercod_"+codigo).remove();
	}
	
	function Editar(codigo)
	{
		$("#TituloBanner").html($("#titulo_"+codigo).html());
		$("#bannercod").val(codigo);
		$("#BannerTitulo").val($("#BannerTitulo_"+codigo).val());
		$("#BannerLink").val($("#BannerLink_"+codigo).val());
		$("#BannerTexto").val($("#BannerTexto_"+codigo).val());
		
		MostrarBtModif();
	}
	
	
	function MostrarBtModif()
	{
		$("#AgregarBanner").hide();
		$("#ModificarBanner").show();
		$("#CancelarBanner").show();
	}
	function OcultarBtModif()
	{
		$("#AgregarBanner").show();
		$("#ModificarBanner").hide();
		$("#CancelarBanner").hide();
		
	}
	
	function LimpiarDatos()
	{
		$("#TituloBanner").html("");
		$("#LinkBanner").html("");
		$("#bannercod").val("");
		$("#BannerTitulo").val("");
		$("#Link").val("");
		$("#BannerTexto").val("");
	}
	
	function CancelarAccionModif()
	{
		OcultarBtModif();
		LimpiarDatos();
	}
	 
	var timeoutHnd; 
	function SearchBanner(ev){ 
		if(timeoutHnd) 
			clearTimeout(timeoutHnd) 
		timeoutHnd = setTimeout(BuscarBanner,500) 
	}
	
	function BuscarBanner()
	{
		var param, url;
		$("#bannerLstData").html('<tr><td colspan="4" align="center"><div style="margin-top:5px;"><img src="images/cargando.gif" />&nbsp;Buscando Banners...</td></div></tr>');
		param = "BannerLink="+$("#BannerLink").val();
		$.ajax({
		   type: "POST",
		   url: "tap_modulos_busqueda_banners_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#bannerLstData").html(msg);
		   }
		 });
	}
	
	
	function MovimientoOrden()
	{	
		$("#TituloBannerTabla").sortable(
		  { 
			tolerance: 'pointer',
			scroll: true , 
			handle: $(".orden"),
			revert: 'invalid',
			connectWith: '.orden',
			cursor: 'pointer',
			placeholder: "placeholdertablas",
			opacity: 0.6, 		
		});
	}	
	
	
	function MostrarBoton()
	{
		if($("#popup_boton_muestra").val()==1)
		{
			$("#mostrarbtn").show();
		}
		else
		{
			$("#mostrarbtn").hide();
		}
	}

function iniciarTextEditors()
{
	var editorConfig = {
		mode : 'specific_textareas',
        	language : "es",
		selector: '.rich-text',
		plugins : 'paste,lists,tabfocus,table,link,anchor',
		spellchecker_languages : "+Spanish=es",
		theme : 'modern',
		menubar: false,
		width : "100%",
		content_css : 'css/texteditor.css?v=1.3',
		toolbar_items_size: 'small',
		toolbar1 : 'cut,copy,paste,pasteword,|,bold,italic,underline,|,link unlink anchor,|,removeformat,|,formatselect,|,bullist,numlist,|,hr,|,justifyleft,justifycenter,justifyright,justifyfull,|,blockquote,cite,|,styleselect,|,code',
		apply_source_formatting : false,
		theme_advanced_blockformats : "h2,h3,h4,h5,h6,p,table",
		element_format : 'xhtml',
		theme_advanced_resizing : true,
		forced_root_block : 'p',
		force_p_newlines : true,
		force_br_newlines : false,
		valid_elements : 'div,br,p[style|class],a[name|href|target=_blank|title],img[*],strong/b,em/i,u,span[style=text-decoration:underline;],h2[style|class],h3,h4,h5,h6,ul,ol,li,hr,iframe[*]', 
		paste_auto_cleanup_on_paste : true,
		paste_preprocess : function(pl, o) {
			// Replace <br>s and <div>s by paragraphs and filter the html tags (except <p>s).
			o.content = o.content.replace(/<br(\s[^>]*)?\/?>/ig, '</p><p>')
			                     .replace(/<div(\s[^>]*)?\/?>/ig, '<p>')
			                     .replace(/<\/?(?!p)(\s[^>]*)?\/?>/ig, '');
		}	
	};
	tinyMCE.remove(".rich-text");
	tinyMCE.init(editorConfig);
}

</script>