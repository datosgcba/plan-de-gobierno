<?php 
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

$oGalerias = new cGalerias($conexion,"");
//print_r($_POST);
header('Content-Type: text/html; charset=iso-8859-1'); 
if (isset ($_POST['page']))
	$page = $_POST['page'];
else	
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows']; 
else	
	$limit = 1; 
	
$sidx ="galeriaorden"; 
$sord ="ASC"; 

$datos = $_POST;

$datos['orderby'] = $sidx." ".$sord;

if (!$oGalerias->BuscarAvanzadaxGaleria ($datos,$resultado,$numfilas))
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


$datos['orderby'] = $sidx." ".$sord;
$datos['limit'] = "LIMIT ".$start." , ".$limit;

	
if (!$oGalerias->BuscarAvanzadaxGaleria($datos,$resultado,$numfilas))
	die();


$i = 0;

$responce =new StdClass; 
$responce->page = $page;
$responce->total = $total_pages; 
$responce->records = $count; 
$responce->rows = array();

if ($numfilas>0)
{
	
	
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		
		$link = '<a class="seleccionar" href="javascript:void(0)" onclick="InsertarGaleria('.$_POST['albumcod'].','.$fila['galeriacod'].')" title="Seleccionar Galeria" id="seleccionar_'.$fila['galeriacod'].'">&nbsp;</a>';

		$datosmostrar = array($fila['galeriacod'],utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galeriatitulo'],ENT_QUOTES)),$link);
		
		
		$responce->rows[$i]['galeriacod'] = $fila['galeriacod'];
		$responce->rows[$i]['id'] = $fila['galeriacod'];
		$responce->rows[$i]['cell'] = $datosmostrar; 
		$i++;
	}
}

echo json_encode($responce);

?>
