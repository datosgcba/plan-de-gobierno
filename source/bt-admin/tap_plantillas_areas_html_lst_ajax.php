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

$oPlantillasAreasHtml = new cPlantillasAreasHtml($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
$sidx ="areahtmlcod"; 
$sord ="ASC"; 

if (!$oPlantillasAreasHtml->TraerAreasHtml ($resultado,$numfilas))
	die();
	
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

$i = 0;
$responce= new stdClass();
$responce->page = $page;
$responce->total = 1; 
$responce->records = $numfilas; 
$responce->rows = array();

  
if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
			$linkedit ='<a class="editar" href="javascript:void(0)" onclick="EditarAreaHTML('.$fila['areahtmlcod'].')" title="Editar" >&nbsp;</a>';
			$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="Eliminar('.$fila['areahtmlcod'].')" title="Eliminar" >&nbsp;</a>';

			$datosmostrar = array(
				$fila['areahtmlcod'],
				utf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['areahtmldesc'],ENT_QUOTES)),
				$linkedit,$linkdel
			);
			$responce->rows[$i]['id'] = $fila['areahtmlcod'];
			$responce->rows[$i]['cell'] = $datosmostrar;
			$i++;
		
	}
}
echo json_encode($responce);
?>
