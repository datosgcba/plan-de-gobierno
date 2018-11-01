<?php  
ob_start();
include("./config/include.php");
include(DIR_CLASES."cPaginas.class.php");
include(DIR_CLASES."cNoticias.class.php");
include(DIR_LIBRERIAS."cProcesarHTML.php");
include(DIR_CLASES."cMultimedia.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);
if (!isset($_GET['codigo']) || $_GET['codigo']=='')
	die();
	
if(strlen($_GET['codigo'])>10)
	die();
	
if (!FuncionesPHPLocal::ValidarContenido($conexion,$_GET['codigo'],"NumericoEntero"))
	die();

$oPaginasService = new cPaginas($conexion);
$codigo = $datospagina['pagcod'] = $_GET['codigo'];

$previsualizar = false;
if(isset($_POST[session_name()]))
{
	setcookie(session_name(),$_POST[session_name()],0,"/");
	// arma las variables de sesion y verifica si se tiene permisos
	$previsualizar = true;
	FuncionesPHPLocal::ArmarLinkMD5Front(basename($_SERVER['PHP_SELF']),array("codigo"=>$_GET['codigo'],session_name()=>$_POST[session_name()]),$getPrevisualizar,$md5Prev);
	if ($_GET['md5']!=$md5Prev)
	{	
		ob_clean();
		FuncionesPHPLocal::Error404();
		die();
	}
	$oPaginas = $oPaginasService->BuscarPaginaPrevisualizacion($datospagina);
}else
	$oPaginas = $oPaginasService->BuscarPagina($datospagina);



if ($oPaginas===false)
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}

//MULTIMEDIA

$oPaginasService->CargarImagenes($oPaginas);
$oPaginasService->CargarAudios($oPaginas);
$oPaginasService->CargarVideos($oPaginas);

$imagenes = $oPaginas->getImagenes();
$videos = $oPaginas->getVideos();
$audios = $oPaginas->getAudios();


$mostrarepigrafeCarrousel = true;

$cantidadmayorCarrousel = 4;
$prioridad='imagen';
$idPrioridad = "#multimedia_imagenes";
$MuestraVideoCarrousel = $MuestraImgCarrousel = $tieneimg = $tienevideo = $hayCarrousel = $tieneImgCarrousel = $tieneVideoCarrousel = false;

if (count($imagenes)>0)
{
	$tieneimg = true;
	$imagen = current($imagenes);
	$tamanio = getimagesize(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$imagen->getCarpetaCategoria()."N/".$imagen->getUbicacion());
	if (count($imagenes)>$cantidadmayorCarrousel)
		$tieneImgCarrousel = true;
	
	if (count($imagenes)>1)
		$MuestraImgCarrousel = true;	
}
if (count($videos)>0)
{
	$tienevideo = true;
	$video = current($videos);
	if (count($videos)>$cantidadmayorCarrousel)
		$tieneVideoCarrousel = true;
	if (count($videos)>1)
		$MuestraVideoCarrousel = true;	
}
if (!$tieneimg && $tienevideo)
{
	$prioridad='video';
	$idPrioridad = "#multimedia_videos";
}

$CargarCarrousel = false;
switch($prioridad)
{
	case "imagen":
		if ($tieneImgCarrousel)
			$CargarCarrousel = true;
	break;		
	case "video":
		if ($tieneVideoCarrousel)
			$CargarCarrousel = true;
	break;		
}


if ($tieneimg && $tienevideo)
	$MuestraVideoCarrousel = $MuestraImgCarrousel = true;	


//MULTIMEDIA

$arbol = array();
$oPaginasVacia = new PaginasData();
$oPaginasVacia->setCodigoCategoria($oPaginas->getCodigoCategoria());
$oPaginasService->ArmarArbolPaginas($oPaginasVacia,$arbol);
$oPaginasService->CargarImagenes($oPaginas);
$imagenes = $oPaginas->getImagenes();

$class="onecolumn";
//$class="leftcolumn";
if ($oPaginas->getMuestraMenu()==1)
	$class="leftcolumn";

if ($oPaginas->getMenuCodigo()!="")
{
	$oEncabezados->setMenu($oPaginas->getMenuCodigo());
	$oMenu = new cMenu($conexion);
	$datosMenuBusqueda['menucod'] = $oPaginas->getMenuCodigo();
	$datosMenuBusqueda['menutipocod'] = $oPaginas->getMenuTipoCodigo();
	$oMenu->BuscarxCodigo($datosMenuBusqueda,$resultado,$numfilas);
	if ($numfilas>0)
	{
		$datosMenu = $conexion->ObtenerSiguienteRegistro($resultado);
		if ($datosMenu['menucodsup']!="")
			$oEncabezados->setMenu($datosMenu['menucodsup']);
	}
}

$oEncabezados->setTitle($oPaginas->getTitulo());
$oEncabezados->setDescription(strip_tags($oPaginas->getCopete()));
$oEncabezados->setOgTitle($oPaginas->getTitulo());
$oEncabezados->setPlantilla($oPaginas->getPlantillaHtmlCodigo());
$oEncabezados->EncabezadoMenuEmergente();


function CargarArbolMenu($codigo,$arbol)
{
	?><ul><?php  
		foreach ($arbol as $datospaginamenu){ 
		?>
			<li>
				<a <?php  if (array_key_exists($codigo,$datospaginamenu['hijos']) || $codigo == $datospaginamenu['datos']->getCodigo()) echo 'class="seleccionado"'?> href="<?php  echo DOMINIORAIZSITE?><?php  echo $datospaginamenu['datos']->getDominio();?>" title="Ir a <?php  echo $datospaginamenu['datos']->getTitulo();?>"><?php  echo $datospaginamenu['datos']->getTitulo();?></a>
				<?php  
				if (isset($datospaginamenu['subarbol']) && count($datospaginamenu['subarbol'])>0 && (array_key_exists($codigo,$datospaginamenu['hijos']) || $codigo == $datospaginamenu['datos']->getCodigo()))
					CargarArbolMenu($codigo,$datospaginamenu['subarbol']);
				?>
			</li>
			<?php  
		}
	?></ul><?php  
}

?>
<script type="text/javascript" src="<?php  echo DOMINIORAIZSITE?>js/pagina.js"></script>
<div id="DetallePagina">
	<div class="<?php  echo $class?>">
        <h1><?php  echo $oPaginas->getTitulo();?></h1>
        <div class="copete">
        	<?php  if (trim($oPaginas->getCopete())!=""){?>
            	<?php  echo $oPaginas->getCopete();?>
                <div class="separadorcopete">&nbsp;</div>
            <?php  }?>
         </div>
         <div class="cuerpo">
            <?php  echo $oPaginas->getCuerpo();?>
        </div>    
    </div>
	<div class="rightcolumn">
            <div class="fondogris">
				<?php  
					$archivo = PUBLICA."paginas/pagina_".$codigo.".html";
					if (file_exists($archivo))
					{
						$oProcesarElementosDinamicosHTML = new cProcesarElementosDinamicosHTML($conexion);
						$html=file_get_contents($archivo);
						$oProcesarElementosDinamicosHTML->Procesar($html,$htmlprocesado);
						echo $htmlprocesado;
					}
                ?>
           </div>
    </div>    
    <div class="clearboth">&nbsp;</div>
</div>
<script src="/public/gcba/bastrap3/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php  echo DOMINIORAIZSITE?>js/galeria.js"></script>
<?php  
$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>