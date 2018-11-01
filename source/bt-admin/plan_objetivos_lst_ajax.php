<?php 
require("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

$sesion = new Sesion($conexion,false);
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oObjeto = new cPlanObjetivos($conexion,"");
header('Content-Type: text/html; charset=iso-8859-1');
if (isset ($_POST['page']))
	$page = $_POST['page'];
else
	$page = 1; 

if (isset ($_POST['rows']))
	$limit = $_POST['rows'];
else
	$limit = 1;

$sidx = "planobjetivocod";
$sord = "DESC";

$_SESSION['BusquedaAvanzada'] = array();

$datos = $_SESSION['BusquedaAvanzada'] = $_POST;

if(!$oObjeto->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();

if (isset ($_POST['sord']))
	$sord = $_POST['sord'];
if (isset ($_POST['sidx']))
	$sidx = $_POST['sidx'];
$count = $numfilas;
$count = $numfilas;
if( $count >0 )
	$total_pages = ceil($count/$limit); 
else
	$total_pages = 0; 

if( $page > $total_pages )
	$page = $total_pages;

if( $limit<0 )
	$limit = 0;

$start = $limit*$page - $limit;if( $start<0 )
	$start = 0;

$datos['orderby'] = $sidx." ".$sord;
$datos['limit'] = "LIMIT ".$start." , ".$limit;

if(!$oObjeto->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();

	$i = 0;
	$responce =new StdClass; 
	$responce->page = $page; 
	$responce->total = $total_pages; 
	$responce->records = $count;
	$responce->rows = array();
while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$linkedit = '<a class="btn btn-xs btn-primary" href="plan_objetivos_am.php?planobjetivocod='.$fila["planobjetivocod"].'" title="Editar" id="editar_'.$fila['planobjetivocod'].'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Editar</a>';
	$tipoactivacion = 5;
	$class = "desactivo";
	if ($fila['planobjetivoestado']==ACTIVO)
	{
		$tipoactivacion = 4;
		$class = "activo";
	}
	$linkestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['planobjetivocod'].','.$tipoactivacion.')" title="Activar / Desactivar" >&nbsp;</a>';

	$linkdel = '<a class="btn btn-xs btn-danger" href="javascript:void(0)" onclick="Eliminar('.$fila['planobjetivocod'].')" title="Eliminar" ><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Eliminar</a>';

	$datosmostrar = array(
		$fila['planobjetivocod'],
		utf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planobjetivonombre'],ENT_QUOTES)),
		$linkestado,
		$linkedit,
		$linkdel
	);
	$responce->rows[$i]['planobjetivocod'] = $fila['planobjetivocod'];
	$responce->rows[$i]['id'] = $fila['planobjetivocod'];
	$responce->rows[$i]['cell'] = $datosmostrar;
	$i++;
}

echo json_encode($responce);
?>