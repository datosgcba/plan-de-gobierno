<? 
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo(); 
$oSistemaBloqueo->VerificarBloqueo($conexion);


$conexion->ManejoTransacciones("B");
header('Content-Type: text/html; charset=iso-8859-1'); 

$msg = array();
$datos = $_POST;
$datos['usuariocod'] = $_SESSION['usuariocod'];
$datos['rolcod'] = $_SESSION['rolcod'];

$oMultimedia = new cMultimediaFormulario($conexion,$datos['prefijo'],$datos['codigo']);
if (!$oMultimedia->CargarListadoMultimedia($datos,$arreglo))
	return false;

$tipo = $oMultimedia->getTipoMultimedia();


$arregloTipos[FOTOS]['id'] = "multimedia_fotos";
$arregloTipos[FOTOS]['idSortable'] = "sortable_multimedia_fotos";
$arregloTipos[FOTOS]['txt'] = "Sin im&aacute;genes cargadas";
$arregloTipos[FOTOS]['iconoVideo'] = false;
$arregloTipos[FOTOS]['tienePreview'] = false;
$arregloTipos[FOTOS]['url'] = false;

$arregloTipos[VIDEOS]['id'] = "multimedia_videos";
$arregloTipos[VIDEOS]['idSortable'] = "sortable_multimedia_videos";
$arregloTipos[VIDEOS]['txt'] = "Sin videos cargados";
$arregloTipos[VIDEOS]['iconoVideo'] = true;
$arregloTipos[VIDEOS]['tienePreview'] = true;
$arregloTipos[VIDEOS]['url'] = false;

$arregloTipos[AUDIOS]['id'] = "multimedia_audios";
$arregloTipos[AUDIOS]['idSortable'] = "sortable_multimedia_audios";
$arregloTipos[AUDIOS]['txt'] = "Sin audios cargados";
$arregloTipos[AUDIOS]['iconoVideo'] = false;
$arregloTipos[AUDIOS]['tienePreview'] = true;
$arregloTipos[AUDIOS]['url'] = false;

$arregloTipos[FILES]['id'] = "multimedia_archivos";
$arregloTipos[FILES]['idSortable'] = "sortable_multimedia_archivos";
$arregloTipos[FILES]['txt'] = "Sin archivos cargados";
$arregloTipos[FILES]['iconoVideo'] = false;
$arregloTipos[FILES]['tienePreview'] = false;
$arregloTipos[FILES]['url'] = true;


if(count($arreglo)>0)
{	

	?>
	<ul id="<? echo $arregloTipos[$datos['tipo']]['id']?>">
	<? 
		foreach ($arreglo as $fila)
		{
		?>
		<li id="multimedia_<? echo $fila['multimediacod']?>" class="<? echo $arregloTipos[$datos['tipo']]['idSortable']?>">
			<? if($fila['puedeeditar'] && $tipo['tieneorden']){?>
				<div class="float-left anchoorden orden" style="cursor:move">
					<div style="text-align:left">
						<img src="images/up.png" alt="Ordenar" />
					</div>
					<div style="text-align:left">
						<img src="images/down.png" alt="Ordenar" />
					</div>
				</div>
			<? } ?>
			<div class="float-left anchoimagen">
            	<? if ($arregloTipos[$datos['tipo']]['iconoVideo']){?>
            		<div class="play"><img src="images/play_large.png" alt="Play" /></div>
                <? }?>
				<img src="<? echo $fila['multimediaimg']?>" class="imagen_multimedia" alt="<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>" <? if($arregloTipos[$datos['tipo']]['url'])echo "onclick='return AbrirPopupDominio(".$fila['multimediacod'].")'" ?>  />
			
               
				<? if ($arregloTipos[$datos['tipo']]['tienePreview'] && $fila['puedeeditar']){?>
                	<div class="linkpreview clearfix">
                        <a  class="preview" href="javascript:void(0)" title="Subir preview de imagen" onclick="return SeleccionarSubirMultimediaPreview(<? echo $fila['multimediacod']?>)">
                            <img src="images/camera.png" />
                        </a>
                        <a class="crosspreview" href="javascript:void(0)" title="Eliminar Preview de imagen" onclick="return EliminarPreview(<? echo $fila['multimediacod']?>)">
                            <img src="images/camera_delete.png" alt="Eliminar" />
                        </a>
                    </div>
					<div class="clear fixalto">&nbsp;</div>
				<? }?>
            
            </div>
			<div class="float-left anchodescripcion">
				<? if ($tipo['tienetitulo'] && $fila['puedeeditar']){?>
					<input type="text" maxlength="255" value="<? echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediatitulo'],ENT_QUOTES);?>"  class="input-md form-control" id="multimediatitulo_<? echo $fila['multimediacod']?>" name="multimediatitulo_<? echo $fila['multimediacod']?>" onchange="ModificarTituloListadoMultimedia(<? echo $fila['multimediacod']?>)" />
					<div class="clear fixalto">&nbsp;</div>
				<? }elseif ($tipo['tienetitulo']){?>
					 <div class="clear fixalto">&nbsp;</div><?
					 echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediatitulo'],ENT_QUOTES);
				}?>
				
				<? if ($tipo['tienedesc'] && $fila['puedeeditar']){?>
					<textarea name="multimediadesc_<? echo $fila['multimediacod']?>" class="input-md form-control" id="multimediadesc_<? echo $fila['multimediacod']?>" cols="10" rows="3"  onchange="ModificarDescripcionListadoMultimedia(<? echo $fila['multimediacod']?>)"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES)?></textarea>
					<div class="clear fixalto">&nbsp;</div>
				<? }elseif ($tipo['tienedesc']){?>
					 <div class="clear fixalto">&nbsp;</div><?
					 echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES);
				}?>
				
				<? if ($tipo['tienehome'] && $fila['puedeeditar']){?>
					<input type="checkbox" class="chkHome" style="width:20px !important; margin-top:8px" onclick="MultimediaSoloHome(<? echo $fila['multimediacod']?>)" <? if ($fila['home']==1) echo 'checked="checked"'?>  name="enhome_<? echo $fila['multimediacod']?>" value="1" id="enhome_<? echo $fila['multimediacod']?>" />
                    <label class="labelHome" for="enhome_<? echo $fila['multimediacod']?>" value="1" id="enhome_<? echo $fila['multimediacod']?>">Solo Home</label>
					<div class="clear fixalto">&nbsp;</div>
				<? }elseif($tipo['tienehome']){ echo ($fila['home']==1)?'Solo Home':'';}?>

			</div>
			<div class="clear fixalto">&nbsp;</div>
            <div class="footermultimedia">
                <div class="descripcion">
                    <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimedianombre'],ENT_QUOTES);?>
                </div>
                <? if($fila['puedeeditar']){?>
                <div class="linkeliminar">
                    <a href="javascript:void(0)" class="text-danger fa-2x" style="color:#d43f3a" onclick="EliminarMultimedia(<? echo $fila['multimediacod']?>,<? echo $fila['multimediaconjuntocod']?>)">
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                    </a>
                </div>
                <? }?>
            </div>    
			<div class="clear fixalto">&nbsp;</div>
            
		</li>
		<? 
		
	}	
	?>
	</ul>
	<? 
}else
{
	?>	
		<b><? echo $arregloTipos[$datos['tipo']]['txt']?></b>
	<? 
}

?>