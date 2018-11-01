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

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$usuarios=new cUsuarios($conexion);
$ArregloDatos['usuariocod']=$_SESSION['usuariocod'];
if (!$usuarios->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas) || $numfilas!=1)
	die();

$filausuario=$conexion->ObtenerSiguienteRegistro($resultadousuarios);
$usuarioemail=$filausuario["usuarioemail"];

$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';
	


//----------------------------------------------------------------------------------------- 
?>
<script type="text/javascript">
<!--
function Validarjs(formulario_login) 
{
	if (formulario_login.clave2.value=="")
	{
		alert("Debe ingresar una contraseña");
		formulario_login.clave2.focus();
		return false;
	}
	if (!ValidarPassword(formulario_login.clave2.value,formulario_login.clave1.value,'<?php  echo $usuarioemail ?>',8))
	{
		alert("La contraseña no cumple los requerimientos");
		formulario_login.clave2.focus();
		return false;
	}
	if (formulario_login.clave2.value!=formulario_login.clave3.value)
	{
		alert("Las contraseña de confirmación no coincide");
		formulario_login.clave3.focus();
		return false;
	}		

	return true;
}
//-->
</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Cambiar Clave de Acceso</h2>
</div>
<div class="form">
    <div class="clearfix brisa">&nbsp;</div>
    <div class="ancho_10">
        <form method="post" action="<?php  echo $_SERVER['PHP_SELF'] ?>" name="formulario_login" id="formulario_login" class="general_form" >
                <div class="ancho_2">&nbsp;</div>
                <div class="ancho_7">
                    <div class="ancho_7">
                        <div class="ancho_3">
                            <label>Contrase&ntilde;a Actual:</label>
                        </div>        
                        <div class="ancho_7">
                             <input type="password" name="clave1" id="clave1" class="full" value="" size="50"/>
                        </div>
                    </div>
                    <div class="clearboth">&nbsp;</div>
                    <div class="ancho_7">
                        <div class="ancho_3">
                            <label>Nueva Contrase&ntilde;a:</label>
                        </div>        
                        <div class="ancho_7">
                             <input type="password" name="clave2" id="clave2" class="full" value="" size="50"/>
                        </div>
                    </div>
                    <div class="clearboth">&nbsp;</div>
                    <div class="ancho_7">
                        <div class="ancho_3">
                            <label>Confirme Contrase&ntilde;a:</label>
                        </div>        
                        <div class="ancho_7">
                            <input type="password" name="clave3" id="clave3" class="full" value="" size="50"/>
                        </div>
                    </div>    
                    <div class="clearboth aire">&nbsp;</div>
                    <div class="ancho_7">
                           <div class="ancho_boton aire"> <input type="submit" class="boton verde" value="Modificar Contrase&ntilde;a" name="botonchgpwd" onclick="return Validarjs(formulario_login)" /></div>
                           <div class="ancho_boton aire"> <input type="reset" class="boton base" value="Limpiar" name="B2" /></div>
                    </div>    
                </div>    
                <div class="ancho_1">&nbsp;</div>
          </form>
      </div>
      <div class="clearboth aire">&nbsp;</div>
      <div class="cuadro ancho_11">
          <blockquote>
            <span class="textoaclaraciones">La contrase&ntilde;a nueva debe cumplir los siguientes requerimientos:<br />
            - Debe contener s&oacute;lo letras (may&uacute;sculas y/o min&uacute;sculas) y n&uacute;meros.<br />
            - Como m&iacute;nimo debe tener un largo de 8 car&aacute;cteres.<br />
            - No puede tener el mismo car&aacute;cter consecutivo mas de 4 veces.<br />
            - No puede ser parecida a la contrase&ntilde;a anterior.<br /></span>
          </blockquote>
      </div>
      <div class="clearboth">&nbsp;</div>
        <?php  
        if (isset($_POST['botonchgpwd']))
        {
            $conexion->ManejoTransacciones("B");
            
            if($usuarios->CambiarPwd($_SESSION["usuariocod"],$_POST['clave1'],$_POST['clave2'],$_POST['clave3'])) 
            {		
                $conexion->ManejoTransacciones("C");
                FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se actualizó su contrase&ntilde;a de acceso. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
            } // si realizó el cambio
            else 
            {  // si se produjo un error en los datos
                $conexion->ManejoTransacciones("R");
            } // commit
        
        }
        ?>
              
	</div>
</div>        
<div style="height:50px; clear:both">&nbsp;</div>
<?php 

$oEncabezados->PieMenuEmergente();
?>


