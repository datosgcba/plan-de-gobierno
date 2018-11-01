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

$oEncabezados = new cEncabezados($conexion);

$oNoticias = new cNoticias($conexion);
//$oNoticiasCategorias = new cNoticiasCategorias($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
$oNoticiasMultimedia = new cNoticiasMultimedia($conexion,"");
if(!$oNoticiasMultimedia->BuscarMultimediaFotosxCodigoNoticia($_POST,$resultado,$numfilas))
	die();
	
$oMultimedia=new cMultimedia($conexion, "");

if ($numfilas>0)
{
	$multimediacod="";
	if (isset($_POST['multimediacod']) && $_POST['multimediacod']!="")
		$multimediacod = $_POST['multimediacod'];
	else
	{	
		$fila = $conexion->ObtenerSiguienteRegistro($resultado);
		$multimediacod = $fila['multimediacod'];
	}
	?>
    <script type="text/javascript">
		function CambiarImage()
		{
			$("#multimediacod").val($("#multimediacodsel").val());
			$("#imageLoad").html($("#multimediacod_"+$("#multimediacodsel").val()).html());
		}
	</script>
	<?
    $conexion->MoverPunteroaPosicion($resultado,0);
    while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
    {
        if ($fila['multimediacod']==$multimediacod)
		{
		?>	
            <div id="imageLoad" style="text-align:center; margin-bottom:5px;"> 
                <img src="<? echo $oMultimedia->DevolverDireccionImgThumb($fila['multimediacatcarpeta'],$fila['multimediaubic'])?>" alt="Imagen" />
            </div>    
            <div style="display:none" id="multimediacod_<? echo $fila['multimediacod']?>"> 
                <img src="<? echo $oMultimedia->DevolverDireccionImgThumb($fila['multimediacatcarpeta'],$fila['multimediaubic'])?>" alt="Imagen" />
            </div>    
		<? 
		}else
		{
	    ?>
            <div style="display:none" id="multimediacod_<? echo $fila['multimediacod']?>"> 
                <img src="<? echo $oMultimedia->DevolverDireccionImgThumb($fila['multimediacatcarpeta'],$fila['multimediaubic'])?>" alt="Imagen" />
            </div>    
        <?
		}
    }
	?>    
    <input type="hidden" name="multimediacod" id="multimediacod" value="<? echo $multimediacod?>" />
    <? 
	if ($numfilas>1)
	{
	?>
    <select name="multimediacodsel" id="multimediacodsel" style="width:100%;" onchange="CambiarImage()">
	<?
		$conexion->MoverPunteroaPosicion($resultado,0);
		while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
		{
			?>
				<option value="<? echo $fila['multimediacod']?>" <? if ($fila['multimediacod']==$multimediacod) echo 'selected="selected"'?> >
					<? echo ($fila['multimediadesc']!="") ? $fila['multimediadesc']:$fila['multimedianombre'];?>
					<? echo ($fila['notmultimediamuestrahome']==1) ? " - Home":"";?>
				</option>  
			<? 
		}
		?>
	</select>
	<? 
    }
}else
{
	echo "&nbsp;";	
}
?>