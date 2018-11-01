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

$oMultimedia = new cMultimedia($conexion,"");

header('Content-Type: text/html; charset=iso-8859-1'); 
$datos = $_POST;
$sidx ="multimediacod"; 
$sord ="DESC";
$datos['multimediaestadocod']=ACTIVO;
$datos['limit'] = "LIMIT 0,20";
$datos['orderby'] = $sidx." ".$sord;
if (!$oMultimedia->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();

$titulo_boton = '&nbsp;';
if(isset($_POST['titulo_boton']) && $_POST['titulo_boton']!="")
	$titulo_boton= $_POST['titulo_boton'];

if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		//if (!isset($fila["multimediatitulo"]) || $fila["multimediatitulo"]=="")
		//	$fila["multimediatitulo"]=$fila["multimedianombre"];
		
		$imagen = '<img src="'.cMultimedia::DevolverDireccionImg($fila).'" style="max-width:60px;" width="60" alt="Imagen" />';
		$imagenPreview = cMultimedia::DevolverDireccionImg($fila);
		?>
        <tr>
            <td style="text-align:left"><? echo $fila['multimediacod']; ?></td>
            <td style="text-align:left"><? echo $imagen; ?></td>
            <td style="text-align:left" id="multimediatitulo_<? echo $fila['multimediacod']; ?>"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediatitulo'],ENT_QUOTES); ?></td>
            <td style="text-align:center; font-weight:bold;">
               <a class="left add_noticia" href="javascript:void(0)" onclick="SeleccionarMultimedia(<? echo $fila['multimediacod'] ?>,'<? echo $imagenPreview;?>')"><? echo $titulo_boton;?></a>
            </td>
        </tr> 
		<?
	}
}
?>