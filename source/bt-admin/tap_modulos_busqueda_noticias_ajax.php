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

$oNoticias = new cNoticias($conexion);
//$oNoticiasCategorias = new cNoticiasCategorias($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
$datos['usuariocod'] = $_SESSION['usuariocod'];
$datos['rolcod'] = $_SESSION['rolcod']; 
$datos['orderby'] = "noticiacod desc";
$datos['noticiatitulo'] = $_POST['noticiatitulodesc'];
if (isset($_POST['catcod']) && $_POST['catcod']!="")
	$datos['catcod'] = $_POST['catcod'];
$datos['noticiaestadocod'] = NOTPUBLICADA;
$datos['limit'] = "LIMIT 0,20";

if(!$oNoticias->BusquedaAvanzada($datos,$resultadonoticias,$numfilas)) {
	$error = true;
}
if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultadonoticias))
	{
	?>
        <tr>
            <td style="text-align:center"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiacod'],ENT_QUOTES); ?></td>
            <td style="text-align:left" id="noticiatitulo_<? echo $fila['noticiacod']?>"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES); ?></td>
            <td style="text-align:center; font-weight:bold;">
               <input type="hidden" name="noticiacopete_<? echo $fila['noticiacod']?>" id="noticiacopete_<? echo $fila['noticiacod']?>" value="<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_encode($fila['noticiacopete']),ENT_QUOTES); ?>" />
               <a class="left add_noticia" href="javascript:void(0)" onclick="AgregarNoticia(<? echo $fila['noticiacod']?>)">&nbsp;</a>
            </td>
        </tr> 
	<?
	}
}
?>