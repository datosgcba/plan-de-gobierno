<?php 
require("./config/include.php");

$conexion->SeleccionBD(BASEDATOS);

$conexion->SetearAdmiGeneral(ADMISITE);

$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

$oSistemaBloqueo->VerificarBloqueo($conexion);

header('Content-Type: text/html; charset=iso-8859-1');
if (isset ($_POST['page']))
	$page = $_POST['page'];
else
	$page = 1; 

	$limit = $_POST['rows'];
else
	$limit = 1;

$sord = "DESC";



	die();

	$sord = $_POST['sord'];
if (isset ($_POST['sidx']))
	$sidx = $_POST['sidx'];
$count = $numfilas;
$count = $numfilas;
if( $count >0 )
	$total_pages = ceil($count/$limit); 
else
	$total_pages = 0; 

	$page = $total_pages;

	$limit = 0;

	$start = 0;

$datos['limit'] = "LIMIT ".$start." , ".$limit;

	die();

	$responce =new StdClass; 
	$responce->page = $page; 
	$responce->total = $total_pages; 
	$responce->records = $count;
	$responce->rows = array();
while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$linkedit = '<a class="editar" href="gcba_comunas_barrios_am.php?comunabarriocod='.$fila["comunabarriocod"].'" title="Editar" id="editar_'.$fila['comunabarriocod'].'">&nbsp;</a>';
	$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="Eliminar('.$fila['comunabarriocod'].')" title="Eliminar" >&nbsp;</a>';

		$fila['comunabarriocod'],
		utf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['barriocoddesc'],ENT_QUOTES)),
		$linkedit,
		$linkdel
	);
	$responce->rows[$i]['comunabarriocod'] = $fila['comunabarriocod'];
	$responce->rows[$i]['id'] = $fila['comunabarriocod'];
	$responce->rows[$i]['cell'] = $datosmostrar;
	$i++;
}

?>