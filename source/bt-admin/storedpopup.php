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
$oEncabezados->EncabezadoConsulta($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oStored=new cStored($conexion);
$Tables="Tables_in_".BASEDATOS;


$result=true;
if (!$oStored->TraerTablas(BASEDATOS,$query))
	$result=false;


if (!isset ($_POST['boton']))
{
?>
<form action="storedpopup.php" method="post" name="formulario_popup" >
<table width="100%" class="textotabla" border="1">
	<tr>
		<td valign="top" width="25%">
		<input type="radio" name="Chek" id="Update" value="Update" checked="checked"><label for="Update">Update</label>
		</td>
		<td valign="top" width="25%">
		<input type="radio" name="Chek" id="Insert" value="Insert"><label for="Insert">Insert</label>
		</td>
		<td valign="top" width="25%">
		<input type="radio" name="Chek" id="Delete" value="Delete"><label for="Delete">Delete</label>
		</td>
		<td valign="top" width="25%">
		<input type="radio" name="Chek" id="Select" value="Select"><label for="Select">Select</label>
		</td>
	</tr>
</table>
<br />
<table width="100%" class="textotabla" border="1">
	
	<tr>
		<td valign="top" width="20%">
			<span class='textonombreclave'>Tablas</span>
		</td>
		<td valign="top" width="80%">
		</td>
	</tr>
<?php 
$primero=true;
while ($tabla = $conexion->ObtenerSiguienteRegistro($query)) 
{
 ?>
	<tr>	
		<td valign="top" width="20%">
		</td>
		<td width="80%" >
			<input type="radio" id="opc_<?php  echo $tabla[$Tables]?>" name="tablas" value="<?php  echo $tabla[$Tables]?>" <?php  if ($primero) echo "checked"?> > <label for="opc_<?php  echo $tabla[$Tables]?>"><?php  echo $tabla[$Tables];?> </label>
		</td>
	</tr>	
 <?php 
 $primero=false;
}?>

</table>
<table width="100%" class="textotabla" >
	<tr>	
		<td valign="top" width="20%">
		</td>
		<td width="80%" align="right">
			  <input type="submit" name="boton" class="botones" value="Crear">
		</td>
	</tr>	

</table>
</form>
<?php  
}else
{
	if (!$oStored->TraerTablas(BASEDATOS,$query))
		$result=false;

	$tablaencontrada=$_POST['tablas'];
	$NombreTabla=strtoupper ($tablaencontrada);
	if ($result)
	{
		$encontrado=false;
		while ($tabla = $conexion->ObtenerSiguienteRegistro($query)) 
		{
			if ($tabla[$Tables]==$tablaencontrada)
			{	
				$encontrado=true;
			}	
		}
		if (!$encontrado)
			$result=false;
	}
	
	if ($result)
	{
		if (!$oStored->TraerCampos($tablaencontrada,$resultado))
			$result=false;


		$OperacionMostrar="";
		$NombreStored="";
		if ($result)
		{
			if ($_POST['Chek']=="Insert")
			{
				$NombreStored.="ins_".$tablaencontrada;
				$OperacionMostrar="INS";
				$textoafuera="";
				$textoescribir="INSERT INTO ".$tablaencontrada." ( ";
				$cantidad=$conexion->ObtenerCantidadDeRegistros($resultado);
				$i=1;
				while ($campos = $conexion->ObtenerSiguienteRegistro($resultado)) 
				{
					$textoescribir.= $campos['Field'];
					$textoafuera.='"#p'.$campos['Field'].'#"';
					if ($i<$cantidad)
					{
						$textoescribir.=", ";
						$textoafuera.=", ";
					}
					$i++;
				}
				$textoescribir.=") VALUES (";
				$textoescribir.=$textoafuera.")";
			}
			elseif ($_POST['Chek']=="Update")
			{
				$NombreStored.="upd_".$tablaencontrada;
				$OperacionMostrar="UPD";
				$textoafuera="";
				$textoescribir="UPDATE ".$tablaencontrada." SET ";
				$cantidad=$conexion->ObtenerCantidadDeRegistros($resultado);
				$i=1;
				$existeprimari=false;
				while ($campos = $conexion->ObtenerSiguienteRegistro($resultado)) 
				{
					if ($campos['Key']=="PRI")
					{	
						if ($existeprimari)
						{	
							
							$textoafuera.=" AND ";
							$NombreStored.="_".$campos['Field'];
						}else
							$NombreStored.="_x".$campos['Field'];
						$textoafuera.=$campos['Field']."=";
						$textoafuera.='"#p'.$campos['Field'].'#"';
						$existeprimari=true;
					}
					else
					{
						$textoescribir.= $campos['Field']."=";
						$textoescribir.='"#p'.$campos['Field'].'#"';
					}
					if ($i<$cantidad)
					{
						if ($campos['Key']!="PRI")
							$textoescribir.=", ";
					}
					$i++;
				}
				$textoescribir.=" WHERE ".$textoafuera;
				
			}elseif ($_POST['Chek']=="Delete")
			{
				$NombreStored.="del_".$tablaencontrada;
				$OperacionMostrar="DEL";
				$textoafuera="";
				$textoescribir="DELETE FROM ".$tablaencontrada;
				$cantidad=$conexion->ObtenerCantidadDeRegistros($resultado);
				$i=1;
				$existeprimari=false;
				while ($campos = $conexion->ObtenerSiguienteRegistro($resultado)) 
				{
					if ($campos['Key']=="PRI")
					{	
						if (!$existeprimari)
						{	
							$textoescribir.=" WHERE ";
							$NombreStored.="_x".$campos['Field'];
						}
						else
						{	
							$textoescribir.=" AND ";
							$NombreStored.="_".$campos['Field'];
						}
						$textoescribir.=$campos['Field']."=";
						$textoescribir.='"#p'.$campos['Field'].'#"';
						$existeprimari=true;
					}
				}
				
			}elseif ($_POST['Chek']=="Select")
			{
				$NombreStored.="sel_".$tablaencontrada;
				$OperacionMostrar="SEL";
				$textoafuera="";
				$textoescribir="SELECT ";
				$cantidad=$conexion->ObtenerCantidadDeRegistros($resultado);
				$i=1;
				$existeprimari=false;
				while ($campos = $conexion->ObtenerSiguienteRegistro($resultado)) 
				{
					if ($campos['Key']=="PRI")
					{	
						if ($existeprimari)
						{	
							$textoafuera.=" AND ";
							$NombreStored.="_".$campos['Field'];
						}else
						{
							$NombreStored.="_x".$campos['Field'];
						}
						$textoafuera.=$campos['Field']."=";
						$textoafuera.='"#p'.$campos['Field'].'#"';
						$existeprimari=true;
					}
					else
						$textoescribir.= $campos['Field'];
				
					if ($i<$cantidad)
					{
						if ($campos['Key']!="PRI")
							$textoescribir.=", ";
					}
					$i++;
				}
				$textoescribir.=" FROM ".$tablaencontrada." WHERE ".$textoafuera;
				
			}

			
		}
	}
	if ($result)
	{?>
		<form action="storedpopup.php" method="post" name="formulario_popup" >
		<div align="right" style="width:97%">
			<a href="storedpopup.php" class="linkfondoblanco textoaclaraciones">Volver</a>
		</div>
		<table width="100%" class="textotabla">
			<tr>
				<td valign="top" width="100%">
					<span class='textonombreclave'>SQL Generado</span>
				</td>
			</tr>
			<tr>
				<td valign="top" width="100%">
				<textarea cols="50" rows="7" name="Texto"><?php  echo $textoescribir?></textarea>
				</td>
			</tr>
			<tr>	
				<td width="100%" align="left">
					  <input type="button" name="boton" class="botones" value="Agregar"  onclick="SetearTexto('spsqlstring',formulario_popup.Texto.value,window.opener.document);SetearTexto('sptabla','<?php  echo $NombreTabla?>',window.opener.document); SetearTexto('spoperacion','<?php  echo $OperacionMostrar; ?>',window.opener.document); SetearTexto('spnombre','<?php  echo $NombreStored; ?>',window.opener.document);window.close();">
				</td>
			</tr>	
		</table>
		</form>	
	<?php 
	
	}
	
	
	
}

?>

<?php 
//----------------------------------------------------------------------------------------- 	


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
?>
<?php 
$oEncabezados->PieConsulta();
?>
