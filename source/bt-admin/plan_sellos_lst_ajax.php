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
$oObjeto = new cPlanSellos($conexion,"");
header('Content-Type: text/html; charset=iso-8859-1');
if (isset ($_POST['page']))
	$page = $_POST['page'];
else
	$page = 1; 
if (isset ($_POST['rows']))
	$limit = $_POST['rows'];
else
	$limit = 1;
$sidx = "sellocod";
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
	$linkedit = '<a class="editar" href="plan_sellos_am.php?sellocod='.$fila["sellocod"].'" title="Editar" id="editar_'.$fila['sellocod'].'">&nbsp;</a>';
	$tipoactivacion = 5;
	$class = "desactivo";
	if ($fila['selloestado']==ACTIVO)
	{
		$tipoactivacion = 4;
		$class = "activo";
	}
	$linkestado = '<a class="'.$class.'" href="javascript:void(0)" onclick="ActivarDesactivar('.$fila['sellocod'].','.$tipoactivacion.')" title="Activar / Desactivar" >&nbsp;</a>';
	$linkdel = '<a class="eliminar" href="javascript:void(0)" onclick="Eliminar('.$fila['sellocod'].')" title="Eliminar" >&nbsp;</a>';
	$datosmostrar = array(
		$fila['sellocod'],
		utf8_encode(FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['sellonombre'],ENT_QUOTES)),
		$linkestado,
		$linkedit,
		$linkdel
	);
	$responce->rows[$i]['sellocod'] = $fila['sellocod'];
	$responce->rows[$i]['id'] = $fila['sellocod'];
	$responce->rows[$i]['cell'] = $datosmostrar;
	$i++;
}
echo json_encode($responce);
?>