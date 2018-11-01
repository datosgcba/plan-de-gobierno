<?php
/**
 * Backup automatizado de la base de datos
 * utiliza las constantes definidas del sitio
 * y la librería PHPMailer para poder enviar
 * correo en caso de errores
 */
date_default_timezone_set('America/Argentina/Buenos_Aires');
require($_SERVER['DOCUMENT_ROOT']."/bt-admin/config/include.php");
require_once(DIR_LIBRERIAS."PHPMailerAutoload.php");

$enviamailbien = true;

/**
 * Define el nombre de archivo y directorio de salida
 */
define("OUTPUT_DIRDB", DOCUMENT_ROOT.'/'.CARPETA_SERVIDOR_MULTIMEDIA.'db/');
/**
 * Define el log de errores
 */
define("LOGFILEDB", OUTPUT_DIRDB.'error.log');
define("EXTLOGDB",$_SERVER['HTTP_HOST'].'/'.CARPETA_SERVIDOR_MULTIMEDIA.'db/'.'error.log');

/**
 * Selección de tipo de compresión a utilizar
 *ZIPPER = 0 no comprime (no recomendado)
 *ZIPPER = 1 usa gzip para comprimir
 *ZIPPER = 2 usa bzip2 para comprimir (opción recomendada)
 */
define("ZIPPER",'2');
switch(ZIPPER)
{
	case 0:
		define("ZIPNAME",' 2>> '.LOGFILEDB." > ");
		define("ZIPEXT",".sql");
		break;
	case 1:
		define("ZIPNAME",' 2>> '.LOGFILEDB." | gzip > ");
		define("ZIPEXT",".sql.gz");
		break;
	case 2:
		define("ZIPNAME",' 2>> '.LOGFILEDB." | bzip2 > ");
		define("ZIPEXT",".sql.bz2");
		break;
}
/**
 * Nombre de archivo temporal de configuración
 */
$conffile = OUTPUT_DIRDB.'config.cnf';
$confdel = 'rm '.$conffile;
$conf="[client]
user = ".USUARIOBD."
password = ".CLAVEBD."
host = ".SERVIDORBD."
[mysqldump]
single-transaction";

/**
 * Si el log de errores es mayor a 12 Mb lo elimina
 */
$size = 0;
if (file_exists(LOGFILEDB))
	$size = filesize(LOGFILEDB);
if ($size> 12000000)
	exec("rm ".LOGFILEDB);
/**
 * Agrega la fecha al log de errores
 */
$hoy = date("Y-m-d");
$fechalog = '['.$hoy.' '.date('H:i:s').']
';


/**
 * Nombre de archivo de salida
 */
$file = OUTPUT_DIRDB.BASEDATOS.'-'.$hoy;
/**
 * Nombre del backup de hace cinco días para eliminar
 */
$oldfile =  OUTPUT_DIRDB.BASEDATOS.'-'.hace5dias($hoy).ZIPEXT;

/**
 * Genera y limpia el array de salida
 */
$output=array();
/**
 * Genera un retorno de error en cero y un mensaje de error vacío
 */
$return_var=0;
$errordesc='';

/**
 * Comando que exporta la base como SQL compimido en gzip o bzip2
 */
$cmddir = 'mkdir  '.OUTPUT_DIRDB;
/**
 * Comando que exporta la base como SQL compimido en gzip o bzip2
 */
//$cmd = 'mysqldump --single-transaction --user="'.USUARIOBD.'" --password="'.CLAVEBD.'" --host="'.SERVIDORBD.'" "'.BASEDATOS.'"'.ZIPNAME.$file.ZIPEXT;
$cmd = 'mysqldump --defaults-extra-file='.$conffile.' "'.BASEDATOS.'"'.ZIPNAME.$file.ZIPEXT;
/**
 * Comando que elimina el archivo de haace cinco días
 */
$cmddel = 'rm '.$oldfile;

/**
 * Si no existe, genera la carpeta de salida
 */
if(!file_exists(OUTPUT_DIRDB))
	exec($cmddir);
/**
 * Crea el archivo temporal  de configuración
 */	
$cnx=fopen($conffile,'wb');
fwrite($cnx,$conf);
fclose($cnx);
/**
 * Escribe la fecha en el log de errores
 * y si no existe lo crea
 */
$cnx=fopen(LOGFILEDB,'ab');
fwrite($cnx,$fechalog);
fclose($cnx);
/**
 * Ejecuta el comando que realiza el backup
 */
exec($cmd,$output,$return_var);
/**
 * Elimina el archivo temporal  de configuración
 */	
exec($confdel);

/**
 * Genera un mensaje de error a partir del retorno de error.
 * Los mensajes corresponden almcódigo de salida de GNU/Linux, 
 * pueden variar en otros sistemas.
 */
switch ($return_var)
{
	case 1: //      Catchall for general errors
		$errordesc='Error general';
		break;
	case 2: //      Misuse of shell builtins
		$errordesc='Uso indebido de construcciones';
		break;
	case 126: //	Command invoked cannot execute
		$errordesc='El comando invocado no puede ser ejecutado';
		break;
	case 127: //    Command not found
		$errordesc='Comando no encontrado';
		break;
	case 255: //    Exit status out of range
		$errordesc='Estado de salida fuera de rango';
		break;
	default:
		if ($return_var > 127)
		{//             Fatal error signal 
			$errorexit=$return_var-128;
			$errordesc='Señal de error fatal N&deg; '.$errorexit;
		}
		else
		{//             User-defined exit code 
			$errordesc='C&ocute;digo de error definido por el usuario N&deg; '.$return_var;
		}
		break;
}
/**
 * Verifica el códigod e saliada, si es error (distinto a cero)
 * envía mail a soporte informando de este.
 * Si no hay error intenta eliminar backups viejos
 */
if($return_var==0)
{
	echo "Backup result: OK<br />";
	if($enviamailbien)
		EnviarMail($return_var,"");
	/**
	 * Si existe, ejecuta el comando que elimina el backup de hace cinco días
	 */
	if(file_exists($oldfile))
		exec($cmddel);
}
	else
{
	echo "Backup result: FAILED. ERROR CODE =".$return_var.'<br />';
	echo "Reported error: ".$errordesc."<br />"; 
	/**
	 * Envía un mail con el código y mensaje de error
	 */
	EnviarMail($return_var,$errordesc);
}

function EnviarMail($return_var,$errordesc)
{
	
	/**
	 * Envía el mail usando la librería PHPMailier
	 */
	$mail = new PHPMailer ();
	$mail->SetLanguage( 'es', 'phpmailer/language/' );
	$mail -> SMTPDebug=0;
	$mail -> FromName = EMAIL_FROMNAME;
	$mail -> From = EMAIL_FROM;
	$mail -> Subject = "Backup de ".BASEDATOS." en ".PROJECTNAME;
	$mail -> AddAddress(EMAIL_TO);
	/**
	 * Genera el cuerpo del mail
	 */
	if($return_var!=0)
	{
		$htmlmail = "C&oacute;digo de error ".$return_var."<br />";
		$htmlmail .= "Error reportado por el sistema: ".$errordesc; 
		$htmlmail .= "<br />Registro de errores en: ".LOGFILEDB;
		$htmlmail .= "<br />o en ".EXTLOGDB;
	}else{
		$htmlmail = "Backup correcto<br/>";
		$htmlmail .= "El archivo se encuentra en ".OUTPUT_DIRDB.BASEDATOS.'-'.date('Y-m-d').ZIPEXT;
	}

	$mail -> Body = $htmlmail;
	$mail -> IsHTML (true);
	/**
	 * Establacemos que utilzaremos SMTP 
	 * y habilitamos la autenticación.
	 */
	$mail->IsSMTP();
	$mail->SMTPAuth   = true;
	
	/**
	 * La siguiente parte debería estar comentada en la mayoría de los casos
	 * ya que desactiva la verificación de certificados, solo se debe usar cuando 
	 * la verificacion de certificados falla constantemente (geralmente pasa en windows)
	 */
//----------------------------------------------------------------------------
//	$mail -> SMTPOptions = array(                                           //
//		'ssl' => array(                                                     //
//			'verify_peer' => false,                                         //
//			'verify_peer_name' => false,                                    //
//			'allow_self_signed' => true                                     //
//		)                                                                   //
//	);                                                                      //
//----------------------------------------------------------------------------
	
	/**
	 * Seleccióna el tipo de autenticación dependiendo de los parametros
	 */
	if (SMTP_SSL==1)
		$mail->SMTPSecure = "ssl";
	if (SMTP_TLS==1)
		$mail->SMTPSecure = "tls";
	$mail->SMTPKeepAlive = true;
	$mail->Host       = SMTP_HOST;
	$mail->Port       = SMTP_PORT;
	$mail->Username   = SMTP_USER;
	$mail->Password   = SMTP_PASSW;
	$mail->SetFrom(EMAIL_FROM, EMAIL_FROMNAME);
	/**
	 * Envía e mail.
	 */
	if(!$mail->Send()) 
	{
		echo "Error al enviar mail: ".$mail->ErrorInfo;
		return false;
	}
	else
	{
		echo "Mail enviado";
	}
	return true;
}

function hace5dias($fecha)
{
	/*
	 * Funcion que calcula la fecha de hace 5 días, 
	 * la cual va a ser utilizada para borrar el backup anterior.
	 * La entrada es una fecha como string en formato 'año-mes-día'
	 * Devuelve la salida en el mismo formato.
	 */
	list($anio,$mes,$dia) = sscanf($fecha, "%d-%d-%d");
	if($dia<=5)
	{
		/*
		 * A principio de mes la fecha de backup a borrar corresponde al mes anterior.
		 * Lo mismo sucede a princiopios de enero con el año.
		 */
		$mes -= 1;
		switch ($mes)
		{
			/*
			 * Meses de 31 días
			 */
			case 0:
				$anio -= 1;
				$mes = 12;
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
				$dia += 26;
				break;
			/*
			 * Meses de 30 días
			 */
			case 4:
			case 6:
			case 9:
			case 11:
				$dia += 25;
				break;
			/*
			 * Febrero
			 */
			case 2:
				if($anio%4==0 && ($anio%100!=0 || $anio%400==0))
					$dia += 24;
				else
					$dia += 23;	
				break;
		}
	}
	else
		$dia -= 5;
	if($mes < 10)
		$mes = "0$mes";
	if($dia < 10)
		$dia = "0$dia";
	return "$anio-$mes-$dia";
}
?>