<?php 
define("SISTEMA",'SITIOCONFIGURACION');

//SITIOPRODUCTIVO 1 - desarrollo
//SITIOPRODUCTIVO 2 - testing
//SITIOPRODUCTIVO 3 - producccion
define("SITIOPRODUCTIVO",'2');

//CONECTORMYSQL 1 - Mysql
//CONECTORMYSQL 2 - Mysqlli
define("CONECTORMYSQL",'2');


switch(SITIOPRODUCTIVO)
{
	case 2:
		define("RAIZPORTAL","");
		define("DOMINIOPORTAL","http://plandegobierno-dev.gcba.gob.ar/");
		define("BASEDATOS",'plandegobierno');
		define("SERVIDORBD",'localhost');
		define("USUARIOBD",'root');
		define("CLAVEBD",'');
		error_reporting(E_ALL & ~E_DEPRECATED); 
		break;
	case 3:
		define("RAIZPORTAL","");
		define("DOMINIOPORTAL","http://plandegobierno.com.ar/");
		define("BASEDATOS",'plandegobierno');
		define("SERVIDORBD",'localhost');
		define("USUARIOBD",'root');
		define("CLAVEBD",'');
		error_reporting(0);  
		break;
}

define("TIEMPOSESION",10800); // cantidad de segundos que dura la sesion
?>
