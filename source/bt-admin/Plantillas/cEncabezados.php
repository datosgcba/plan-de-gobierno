<?php  

class cEncabezados
{
	var $conexionencab;
	var $arreglomenumostrar=array();
	
	function cEncabezados($conexion)
	{
		$this->conexionencab = &$conexion;
	}

//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// 

	function EncabezadoMenuEmergente($rolcod,$usuariocod)
	{
		?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Administrador de Contenidos - Bigtree Studio SRL</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">	
        <link rel="shortcut icon" href="favicon.ico" /> 
        <link href="css/themes/apple_pie/ui.css" rel="stylesheet" title="style" media="all" />
        <link media="screen" rel="stylesheet" type="text/css" href="assets/css/snackbar.css?v=1.6"  />
        
        <link media="screen" rel="stylesheet" type="text/css" href="assets/lib/normalize/css/normalize.css"  />
        <link href="assets/css/ui/ui.base.css" rel="stylesheet" media="all" />
        <link href="assets/lib/jquery-ui/jquery-ui.css" rel="stylesheet" media="all" />
        <link href="assets/lib/jquery-ui/jquery-ui.theme.min.css" rel="stylesheet" media="all" />
        <link media="screen" rel="stylesheet" type="text/css" href="assets/lib/bootstrap-3.3.6/css/bootstrap.min.css"  />
		<link media="screen" rel="stylesheet" type="text/css" href="assets/css/estilos.css?v=1.8"  />
		<link media="screen" rel="stylesheet" type="text/css" href="assets/css/step.css?v=1.7"  />
        <link media="screen" rel="stylesheet" type="text/css" href="assets/lib/font-awesome/css/font-awesome.min.css"  />

        <link media="screen" rel="stylesheet" type="text/css" href="assets/css/ui.jqgrid.css"  />
        <link media="screen" rel="stylesheet" type="text/css" href="assets/css/ui.jqgrid-bootstrap.css"  />
        <link media="screen" rel="stylesheet" type="text/css" href="assets/css/roboto.css"  />

		<script type="text/javascript" src="assets/lib/jquery/jquery.min.js"></script>
	    <script type="text/javascript" src="assets/lib/bootstrap-3.3.6/js/bootstrap.js"></script>
        <script type="text/javascript" src="assets/lib/jquery-ui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/datepicker_lang_ES.js"></script>
        <script type="text/javascript" src="js/funcionesjs.js?v=1.1"></script>
        <script type="text/javascript" src="js/jquery.blockUI.js"></script>
		<script type="text/javascript" src="js/custom.js?v=1.3"></script>  
		<script type="text/javascript" src="assets/lib/jqgrid/grid.locale-es.js"></script>
        <script type="text/javascript" src="assets/lib/jqgrid/jquery.jqGrid.min.js"></script>
        <script type="text/javascript" src="js/fileuploader.js"></script>
		<script type="text/javascript" src="js/tiny_mce/tiny_mce.min.js?v=1.3"></script>  
        <script type="text/javascript" src="js/jquery.nestedsortablewidget-1.0.pack.js"></script>
        
        <!-- NUEVO -->
        <link rel="stylesheet" href="//brick.a.ssl.fastly.net/Raleway:100,200,300,400,500,600,700,800,900,200i,300i,400i,500i">
        
        <!-- FIN NUEVO -->
        </head>
        
        <body>
        <div id="MsgGuardando">Guardando...</div>
        <div id="page_wrapper">
         
            
           <nav class="navbar navbar-default" role="navigation">
              <!-- Brand and toggle get grouped for better mobile display -->
              <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="javascript:void(0);">
                   
                    <span id="sidebar-exp"></span>
                </a>
              </div>
        
              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse navbar-ex1-collapse">
                
                <ul class="nav navbar-nav navbar-right header-drop-right">

                  <li class="dropdown user-dropdown-one">
                    <a href="#" class="dropdown-toggle" id="dropdown-usuario">
						<div id="nombre"><?php  echo FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['usuarionombre'], ENT_QUOTES)?></div><div id="apellido"><?php  echo FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['usuarioapellido'],ENT_QUOTES)?></div>
                        <?php  if (isset($_SESSION['avatar']) && $_SESSION['avatar']!=""){?>
                            <img src=<?php  echo CARPETA_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_M.$_SESSION['avatar']?> alt=" <?php  echo FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['usuarionombre']." ".$_SESSION['usuarioapellido'],ENT_QUOTES)?>">
                        <?php  }else{?>
                        	<img src="<?php  echo DOMINIOPORTAL.DOMINIO_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_M ?>/default.png" alt="Avatar">
                        <?php  }?>
                        <b class="caret"></b>
                    </a>
                    
                    <ul class="dropdown-menu user-dropdown-two" id="submenu_usuario">
                      <li><a href="usuario_cambiapwd.php">Cambiar contrase&ntilde;a</a></li>
                      <li><a href="usuarios_modificar.php">Modificar perfil</a></li>
                      <li class="log-out"><a href="salir.php">Salir</a></li>
                    </ul>
                  </li>
                </ul>
                <?php  /*
                <ul class="header-notifications pull-right">
                    <li><a href="#" class="clock"><i class="fa fa-clock-o"></i></a></li>
                    <li><a href="#" class="task"><i class="fa fa-tasks"></i></a></li>
                    <li class="last">
                        <a href="#" class="globe"><i class="fa fa-globe"></i></a>
                        <span class="lbl">12</span>
                    </li>
                </ul>
                */?>
            
              </div><!-- /.navbar-collapse -->
            </nav>    
            <div id="wrapper">
            	<div id="body-overlay"></div>
				<div id="page-layout">
                
            		<div id="sidebar">

                            <form action="usuario_cambiarol.php" style="margin-top:6%" name="formulariorol" id="formulariorol" method="post" >
                             <div class="f-right">
                            <?php 
                                $oRoles = new cRoles($this->conexionencab);
                                $oRoles->RolesDeUnUsuario($_SESSION['usuariocod'],$numfilas,$resultado);
                                if ($numfilas>1)
                                {
                                ?>
                               		Rol :
                                    <select name="rolcod" class="select"  onChange="formulariorol.submit();" style="width:80%;">
                                        <?php  while ($fila = $this->conexionencab->ObtenerSiguienteRegistro($resultado)){
                                            $chek = false;
                                            if ($fila['rolcod']==$_SESSION['rolcod'])
                                                $chek = true;
                                            ?>
                                            <option <?php  if ($chek) echo 'selected="selected"'?> value="<?php  echo $fila['rolcod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['roldesc'],ENT_QUOTES)?></option>
                                        <?php  }?>
                                     </select>
                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;        
                                <?php 
                                }
                                ?>
                                    </div>
                                </form>    

                        <?php  
                        $grupomodulo="";
                        $this->_MenuEmergente($_SESSION['rolcod'],$grupomodulo);
                    	?>
                    </div>
                    <div id="wrap">
                    	<div class="container">
                        	<div class="panel-style space">
		<?php 			
	}	


	function PieMenuEmergente()
	{
?>								<div class="clearboth">&nbsp;</div>
							</div><!-- panel-style-->
						</div><!-- fin container-->
					</div> <!-- fin wrap-->
					<div id="PopupGrafico"></div>
					<div id="PopupVisualizarMultimedia"></div>	
                    <div id="footer">
                        <div class="row">
                            <div class="col-md-6 left">
                                <ul>
                                    <li></li>
                                </ul>
                            </div>
                            <div class="col-md-6 right">
                              
                            </div>
                        </div>
                    </div>        
				</div><!-- wrapper-->
			</div> <!-- contenedor sitio-->
        </div>
    </body>
    </html>
        
<?php 	
	}


	
	

	function EncabezadoMenuEmergenteLogin()
	{
		?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="imagetoolbar" content="no" />
        <title>Configuraci&oacute;n de Sitios</title>

        <link href="css/ui/ui.base.css" rel="stylesheet" media="all" />
        <link href="css/themes/apple_pie/ui.css" rel="stylesheet" title="style" media="all" />
        <link href="assets/css/jquery-ui/jquery-ui.css" rel="stylesheet" media="all" />
        <link media="screen" rel="stylesheet" type="text/css" href="css/bootstrap.css"  />
		<link media="screen" rel="stylesheet" type="text/css" href="css/estilos.css?v=1"  />
		<link href="css/font-awesome.min.css" rel="stylesheet">      

		
        <script type="text/javascript" src="js/funcionesjs.js"></script>
        <script type="text/javascript" src="js/jquery.nestedsortablewidget-1.0.pack.js"></script>
		<script type="text/javascript" src="js/tiny_mce/tiny_mce.min.js?v=1.2"></script>  
		<script type="text/javascript" src="assets/lib/jquery/jquery.min.js"></script>
		        <script type="text/javascript" src="assets/lib/jquery-ui/jquery-ui.min.js"></script>
        </head>
        

        <body class="login" >
              
            <div id="login">
                <div class="logo">
                    <a href="javascript:void(0);"><img src="images/logo.png" alt="" /></a>
                </div>
<?php 	
	}



	function PieMenuEmergenteLogin()
	{
?>
            <div class="footerlinkbigtree">
				
             </div>            
         </div>			<!--Login-->	
            <!--[if !IE]>end wrapper<![endif]-->
        </body>
        </html>
        
<?php 	
	}






//-----------------------------------------------------------------------------------------
//							 PRIVADAS
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// 
	
	function _MenuEmergente($rolcod,$grupomodulo)
	{
		if($rolcod=='' || $rolcod==0)
			return false;

		// se piden todos los gruposmod que tengan 'S' o 'L' 
		// en modulomostrar
		$param=array('prolcod'=> $rolcod);
		if(!$this->conexionencab->ejecutarStoredProcedure("sel_menusuperior_xrol",$param,$menuprinc,$numfilas,$errno))
			return false;
		
		?>	

        <ul class="drop-area">
            <li class="no-back">
               <a href="ingreso.php">Dashboard</a>
            </li>
			<?php 
            $i = 0;
            while ($filaprinc = $this->conexionencab->ObtenerSiguienteRegistro($menuprinc)) 
            { 
                $param=array('prolcod'=> $rolcod,'pgrupomodcod'=>$filaprinc['grupomodcod']);
                if(!$this->conexionencab->ejecutarStoredProcedure("sel_menuizq_xrolcod_xgrupocod",$param,$menusecund,$numfilas,$errno))
                    return false;
                    
                // si tiene una sola fila con 'L' en modulomostrar 
                // es porque no tiene submenu
                // pone link en el men principal izquierdo	
                $filasecund = $this->conexionencab->ObtenerSiguienteRegistro($menusecund); 
    
                if($numfilas==1 && $filasecund['modulomostrar']=="L")
                {
                ?>
                    <li> 
                        <a href="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filasecund['archivonom'],ENT_QUOTES) ?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaprinc['grupomodtextomenu'],ENT_QUOTES) ?>"> 
                            <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaprinc['grupomodtextomenu'],ENT_QUOTES)?>
                        </a>
                    </li>
                <?php 			
                }
                else
                {
                ?>
                    <li >
                        <a href="javascript:void(0)" class="groupMenu" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaprinc['grupomodtextomenu'],ENT_QUOTES)?>"> 
                            <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaprinc['grupomodtextomenu'],ENT_QUOTES)?>
                        </a>
                        <?php  //echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaprinc['grupomodtextomenu'],ENT_QUOTES);
                        $param=array('prolcod'=> $rolcod,'pgrupomodcod'=>$filaprinc['grupomodcod']);
                        if(!$this->conexionencab->ejecutarStoredProcedure("sel_menuizq_xrolcod_xgrupocod",$param,$menusecund,$numfilas,$errno))
                            return false;
                         ?>
                        <ul>
                            <?php 
                            while ($filasecund = $this->conexionencab->ObtenerSiguienteRegistro($menusecund)) 
                            { 
                            ?>	
                                <li>	
                                    <a href="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filasecund['archivonom'],ENT_QUOTES) ?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filasecund['modulotextomenu'],ENT_QUOTES) ?>">
                                        <?php  if ($filasecund['moduloimg']!=""){?>
                                        <i class="fa <?php  echo $filasecund['moduloimg']?>"></i>
                                        <?php  }else{ ?>
                                         <i class="fa fa-caret-right"></i>
										<?php  }?>
										<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filasecund['modulotextomenu'],ENT_QUOTES) ?>
                                    </a>
                                </li>			
                            <?php  
                                
                            }
                            ?>
                        </ul>
                    </li>
                <?php 			
                }
                $i++;
            }
            ?>
        </ul>
	<?php 
	}


	
	function EncabezadoConsulta()
	{
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title></title>
		<link rel="stylesheet" type="text/css" href="css/estilos.css?v=1">
		<script  src="js/funcionesjs.js" type="text/javascript"></script>
		</head>
		<body>
		<?php 
	
	}

	function PieConsulta()
	{
		?>
		
				<hr class="noscreen" />

			<!-- Footer -->
			<div id="footer" class="box">


			</div> <!-- /footer -->

		</div> <!-- /main -->

		</body>
		</html>
		<?php 
	}
	
	function _MenuDashboard($rolcod,$grupomodulo)
		{
			if($rolcod=='' || $rolcod==0)
				return false;
	
			// se piden todos los gruposmod que tengan 'S' o 'L' 
			// en modulomostrar
			$param=array('prolcod'=> $rolcod);
			if(!$this->conexionencab->ejecutarStoredProcedure("sel_menusuperior_xrol",$param,$menuprinc,$numfilas,$errno))
				return false;
			
			$i = 0;
			while ($filaprinc = $this->conexionencab->ObtenerSiguienteRegistro($menuprinc)) 
			{ 
	
				$param=array('prolcod'=> $rolcod,'pgrupomodcod'=>$filaprinc['grupomodcod']);
				if(!$this->conexionencab->ejecutarStoredProcedure("sel_menuizq_xrolcod_xgrupocod",$param,$menusecund,$numfilas,$errno))
					return false;
					
				// si tiene una sola fila con 'L' en modulomostrar 
				// es porque no tiene submenu
				// pone link en el men principal izquierdo	
				$filasecund = $this->conexionencab->ObtenerSiguienteRegistro($menusecund); 
	
				if($numfilas==1 && $filasecund['modulomostrar']=="L" && $filasecund['modulodash']=="1")
				{
	
	
				?>
					<li> 
						<a href="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filasecund['archivonom'],ENT_QUOTES) ?>" style="background-image:url(<?php  echo  $filasecund['moduloimg']?>)" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaprinc['grupomodtextomenu'],ENT_QUOTES) ?>"> 
							<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filasecund['modulotextomenu'],ENT_QUOTES)?>
						</a>
					</li>
				<?php 			
	
				}
				else
				{
	
				?>
						<?php  //echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaprinc['grupomodtextomenu'],ENT_QUOTES);
						$param=array('prolcod'=> $rolcod,'pgrupomodcod'=>$filaprinc['grupomodcod']);
						if(!$this->conexionencab->ejecutarStoredProcedure("sel_menuizq_xrolcod_xgrupocod",$param,$menusecund,$numfilas,$errno))
							return false;
						 ?>
						
							<?php 
							while ($filasecund = $this->conexionencab->ObtenerSiguienteRegistro($menusecund)) 
							{ 
								if($filasecund['modulodash']=="1")
									{
							?>	
								<li >	
								  <a href="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filasecund['archivonom'],ENT_QUOTES) ?>" style="background-image:url(<?php  echo  $filasecund['moduloimg']?>)" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaprinc['grupomodtextomenu'],ENT_QUOTES) ?>"> 
										<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filasecund['modulotextomenu'],ENT_QUOTES) ?>
									</a>
								</li>			
							<?php  
									}
							}
							?>
				<?php 			
				}
				$i++;
			}
			?>
		 
		<?php 
		}	
	
}//fin clase
?>