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

// ve si el sistema est� bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);

$oMacrosEstructuras = new cMacrosEstructuras($conexion,"");

header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="estructuraorden"; 
$sord ="ASC"; 

$datos = $_POST;


$datos['orderby'] = $sidx." ".$sord;

if (!$oMacrosEstructuras->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();

if (isset($_POST['sord']))
	$sord = $_POST['sord']; // get the direction 
if (isset($_POST['sidx']))
	$sidx = $_POST['sidx']; // get index row - i.e. user click to sort 



$count = $numfilas; 
if( $count >0 ) 
{ 
	$total_pages = ceil($count/$limit); 
} 
else 
{ $total_pages = 0; } 

if ($page > $total_pages) 
	$page=$total_pages; 
	
if ($limit<0) 
	$limit = 0; 

$start = $limit*$page - $limit; // do not put $limit*($page - 1) 

if ($start<0) 
	$start = 0;


$datos['orderby'] = $sidx." ".$sord;
$datos['limit'] = "LIMIT ".$start." , ".$limit;

if (!$oMacrosEstructuras->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();


$i = 0;
$responce= new stdClass();
$responce->page = $page;
$responce->total = $total_pages; 
$responce->records = $count; 
$responce->rows = array();


if ($numfilas>0)
{
	
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
			//print_r($fila);
			$linkdel ="";
			$datos['estructuracod']= $fila['estructuracod'];
			if($oMacrosEstructuras->PuedeEliminar($datos,false))
				$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="EliminarMacroEstructura('.$fila['estructuracod'].')" title="Eliminar" ></a>';
		    $linkedit = '<a class="editar" href="javascript:void(0)" onclick="EditMacrostructura('.$fila['estructuracod'].','.$fila['macrocod'].')" title="Editar" >&nbsp;</a>';
			FuncionesPHPLocal::ArmarLinkMD5("tap_macros_columnas.php",array("estructuracod"=>$fila['estructuracod']),$getestructura,$md5estructura);
			$linkcol ='<a class="link" href="tap_macros_columnas.php?estructuracod='.$fila['estructuracod'].'&md5='.$md5estructura.'" title="Editar Columnas" >Editar Columnas</a>';

			$datosmostrar = array(
				$fila['estructuracod'],
				utf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['estructuradesc'],ENT_QUOTES)),
				utf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['estructuraclass'],ENT_QUOTES)),
				$linkcol,
				$linkedit,
				$linkdel
			);
			$responce->rows[$i]['estructuracod'] = $fila['estructuracod'];
			$responce->rows[$i]['id'] = $fila['estructuracod'];
			$responce->rows[$i]['cell'] = $datosmostrar;
			$i++;
		
	}
}
echo json_encode($responce);
?>
