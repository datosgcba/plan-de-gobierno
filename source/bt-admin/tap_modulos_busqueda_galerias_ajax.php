<?
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);

$oGalerias= new cGalerias($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
$datos['orderby'] = "galeriacod asc";
$datos['galeriatitulo'] = $_POST['galeriatitulo'];
if (isset($_POST['multimediaconjuntocod']) && $_POST['multimediaconjuntocod']!="")
	$datos['multimediaconjuntocod'] = $_POST['multimediaconjuntocod'];
$datos['galeriaestadocod']=ACTIVO;
$datos['limit'] = "LIMIT 0,8";
if(!$oGalerias->BuscarAvanzadaxGaleria($datos,$resultadogaleria,$numfilas)) {
	$error = true;
}
if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultadogaleria))
	{
	?>
        <tr>
                                <td style="text-align:center"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galeriacod'],ENT_QUOTES); ?></td>
                                <td style="text-align:left" id="galeriatitulo_<? echo $fila['galeriacod']?>"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galeriatitulo'],ENT_QUOTES); ?></td>
                                            
                                <td style="text-align:center; font-weight:bold;">
                                   <a class="left add_image" style="margin-left:15px" href="javascript:void(0)" onclick="AgregarGaleria(<? echo $fila['galeriacod']?>)">&nbsp;</a>
     
                                </td>
                        </tr> 
	<?
	}
}
?>
