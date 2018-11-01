<? 

require_once 'Spreadsheet/Excel/Writer.php';


//----------------------------------------------------------------------------------------- 
// CLASE DE LOGICA DE EXCEL.
	
	/*
	1- Para generar un excel primero se debe crear el objeto con el nombre del archivo a enviar.
			$nombrearchivo="text.xls";
			$oExcel= new cEscribirExcel($nombrearchivo);
	2- Luego crear la hoja; (la clase utilizar� esta hoja como actual, si se crea otra hoja, y se desea
	   utilizar la hoja anterior, deber� utilizar la funci�n cambiarhoja.
			$oExcel->AddHoja ("hoja1");
	3- Si se desea cambiar el ancho de una columna, utilizar la funcion
			$oExcel->FormatoColumna (1,50);
	4- Luego para agregar un dato anteriormente si se desea utilizar formato, debe utilizar:
			$oExcel->AddFormato ("Font","Times New Roman");
			$oExcel->AddFormato ("Tama�o",20);
			$oExcel->CargarDatos (1,0,"C�digo",true,6); 
			o si se desea en varias columnas y filas:
			$oExcel->CargarDatosVariasCeldas (0,0,0,1,"Datos totales",true,10);
	5- Si luego se desea borrar un formato utilizar la funci�n:
			$oExcel->BorrarFormato ("Border");
	   Si se desea borrar todos los formatos utilizar:
			$oExcel->BorrarFormato ("");
	6- Si se desea traer los formatos en un arreglo, utilizar la funci�n
			$formato1=$oExcel->GetFormato();
	7- Si se desea enviar un arreglo o un query utilizar la funci�n:
			$formato1=$oExcel->GetFormato();
			$formato2=$oExcel->GetFormato();
			$arregloformatos=array ("archivonom"=>$formato1,"archivocod"=>$formato2);
			$arrayclaves = array ("archivocod","archivonom");
			$arreglo= array ("archivocod"=>1,"archivonom"=>"hola");
			$oExcel->CargarGrilla ($arreglo,3,0,$arrayclaves,$arregloformatos);
	8- Si se desea setear la p�gina horizontal o vertical:
			$oExcel->PaginaHorizontal();
			$oExcel->PaginaVertical();
	9- Para enviar el archivo utilizar la funci�n:
			$oExcel->Enviar();
	
	10-	COLORES PARA ENVIAR:
			-azul.
			-gris.
			-rojo.
			-magenta.
			-negro.
			-blanco.
			-cyan.
			-amarillo.
			-aqua.
			-lima
			-fuxia.
			-naranja.
			-violeta.
			-plata.
		
	11- FORMATOS A MODIFICAR:
			 "Tama�o" : Tama�o de la letra. Entero. Ejemplo: 12
			 "Align":	Alineamiento: "center, left, right, etc"
			 "Color":	Color. Ejemplo: rojo
			 "FgColor": Color.
			 "BgColor": Color.
			 "Bold": 1. 
			 "Bottom": 1 => thin, 2 => thick
			 "Top": 1 => thin, 2 => thick. 
			 "Right": 1 => thin, 2 => thick
			 "Border":  1 => thin, 2 => thick
			 "BorderColor": Color.
			 "TopColor": Color.
			 "RightColor": Color.
			 "LeftColor": Color.
			 "Pattern": Opcional. Defaults to 1. Meaningful values are: 0-18. 0 = no background. 1 = solid "background" 
			 "Underline": 1 => underline, 2 => double underline.
			 "TextRotation": Posible valores: 0, 90, 270 y -1 para top-to-bottom
			 "NumFormat":0, 0.00, #.##, 0%,0.00%, $#.#;[Red]($#.#), ??/??, # ??/??, 0.00E+#, DD-MM-YY, D/M/YYYY h:mm:ss, h:mm:ss AM/PM 
			 "Script":1 => superscript, 2 => subscript.
			 "Font":'Times New Roman', 'Courier', 'Arial', etc.
			 "Italic":"" 
			 "VAlign": 'vcenter' 'vjustify' , etc.
			 "HAlign":'hcenter' 'hjustify' , etc.
	
	*/

class cEscribirExcel 
{

	var $Excel;
	
	var $Hojas=array();			// Arreglo de hojas en el excel.
	
	var $HojaActual;			// Hoja actual en la que est� seteado el excel.
	
//	var $_FormatoActual;		//Variable interna para el formato.	
	
	var $Formato=array();		// Formato total
								// El formato se va guardando, y utiliza el mismo formato
								// siempre.
								
	var $FormatosUsados=array(); // Arreglo con los formatos usados. Cada posicion es del tipo:
									// formato: array del tipo usado por SysONTIExcel, utilizado para buscar si ya se us� con anterioridad
									// referencia: es la referencia que retorna el addformat al agregar el formato
	
	


//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 
	
	
//Funci�n que crea el objeto de Excel.

// Par�metros de Entrada:
//		nombrearchivo: el nombre del archivo que va a devolver al usuario.
	
	function cEscribirExcel ($nombrearchivo)
	{
			$this->Excel = new Spreadsheet_Excel_Writer();	
			$this->Excel->setVersion(8);
			//$this->Excel->setBIFF8InputEncoding('utf-8');
			$this->Excel->send($nombrearchivo);
			$directorio=dirname ($_SERVER['PHP_SELF'])."/";
			$carpeta=$_SERVER['DOCUMENT_ROOT'].$directorio;
			$this->Excel->setTempDir($carpeta);
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n que crea el objeto de Excel.

// Par�metros de Entrada:
//		nombrearchivo: el nombre del archivo que va a devolver al usuario.
	
	function cEscribirExcelmaquina ($nombrearchivo)
	{
			$this->Excel = new Spreadsheet_Excel_Writer($nombrearchivo);	
			$this->Excel->setVersion(8);
			//$this->Excel->setBIFF8InputEncoding('utf-8');
			$directorio=dirname ($_SERVER['PHP_SELF'])."/";
			$carpeta=$_SERVER['DOCUMENT_ROOT'].$directorio;
			$this->Excel->setTempDir($carpeta);
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

//Funci�n que carga los datos al excel.

// Par�metros de Entrada:
//		fila: fila en el excel. (Se marca con n�meros. Ej, fila 0); Comienza en la 0.
//		columna: columna en el excel. (Se marca con n�meros. Ej, columna 0); Comienza en la 0.
//		dato: El dato a cargar.
//		formato: Si ingresa en true, carga el formato cargado en el arreglo de formatos.

	function CargarDatos ($fila,$columna,$dato,$formato=false)
	{
			
		if ($formato===false)
		{	
			$this->Hojas[$this->HojaActual]->write($fila, $columna, $dato);
		}
		else
		{
			if (is_array ($formato))
				$FormatoActual=$this->_BuscarFormatoYAgregar($formato);
			else
				$FormatoActual=$this->_BuscarFormatoYAgregar($this->Formato);
			if (preg_match("/^([+-]?)(?=\d|\.\d)\d*(\.\d*)?([Ee]([+-]?\d+))?$/", $dato)) 
				$this->Hojas[$this->HojaActual]->writeNumber($fila, $columna, $dato, $FormatoActual);
			else
				$this->Hojas[$this->HojaActual]->writeString($fila, $columna, $dato, $FormatoActual);
		}	
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

//Funci�n que carga los datos en varias celdas en el excel.

// Par�metros de Entrada:
//		fila: fila inicial en el excel. (Se marca con n�meros. Ej, fila 0); Comienza en la 0.
//		columna: columna inicial en el excel. (Se marca con n�meros. Ej, columna 0); Comienza en la 0.
//		filafinal: fila final en el excel. (Se marca con n�meros. Ej, fila 0); Comienza en la 0.
//		columnafinal: columna final en el excel. (Se marca con n�meros. Ej, columna 0); Comienza en la 0.
//		dato: El dato a cargar.
//		formato: Si ingresa en true, carga el formato cargado en el arreglo de formatos.

	function CargarDatosVariasCeldas ($fila,$columna,$filafinal,$columnafinal,$dato,$formato=false)
	{
		
		if ($columna!=$columnafinal)
		{
			for ($i=$columna;$i<=$columnafinal;$i++)
			{
				$this->CargarDatos ($fila,$i,$dato,$formato);
			}
		}
		if ($fila!=$filafinal)
		{
			for ($i=$fila;$i<=$filafinal;$i++)
			{
				$this->CargarDatos ($i,$columna,$dato,$formato);
			}
		}
		//$this->Hojas[$this->HojaActual]->mergeCells($fila,$columna,$filafinal,$columnafinal);
		$this->Hojas[$this->HojaActual]->setMerge($fila,$columna,$filafinal,$columnafinal);
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

	function InsertarImagen ()
	{
		//$im = @imagecreatetruecolor(50, 100)
		//	 or die("Cannot Initialize new GD image stream");
		//$text_color = imagecolorallocate($im, 233, 14, 91);
		//imagestring($im, 1, 5, 5,  "A Simple Text String", $text_color);		
		/*$file="imagenes/Arrow_down.gif";
		$im=imagecreatefromgif($file);
		imagewbmp($im,"hola.wbmp",);
		die();*/
		//$this->Hojas[$this->HojaActual]->insertBitmap (10,10,"hola.wbmp");
		//unlink ("hola.bmp");
	}



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n que carga los datos al excel.

// Par�metros de Entrada:
//		columna: columna en el excel. (Se marca con n�meros. Ej, columna 0); Comienza en la 0.
//		anchocolumna: El ancho que se le da a la columna.


	function FormatoColumna ($columna,$anchocolumna)
	{
		$this->Hojas[$this->HojaActual]->setColumn($columna,$columna,$anchocolumna);
	}
	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n que carga los datos al excel.

// Par�metros de Entrada:
//		fila: fila en el excel. (Se marca con n�meros. Ej, fila 0); Comienza en la 0.
//		altofila: El alto que se le da a la columna.
	
	function FormatoFila ($fila,$altofila)
	{
			$this->Hojas[$this->HojaActual]->setRow($fila,$altofila);
	}



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n que retorna el formato cargado en un array.

	function GetFormato()
	{
		return $this->Formato;
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n carga los datos en el excel a partir de un arreglo o un query.

// Par�metros de Entrada:
//		resultado= query o arreglo.
//		filainicial: fila en el excel. (Se marca con n�meros. Ej, columna 0); Comienza en la 0.
//		columnainicial: columna en el excel. (Se marca con n�meros. Ej, columna 0); Comienza en la 0.
//		arrayclaves: Arreglo con las claves que deseo mostrar, si viene vacio muestra todos los campos.
//		arrayformatos: Arreglo con los formatos, formado por las claves de cada uno, osea toma la clave que exista.

	function CargarGrilla ($resultado,$filainicial,$columnainicial,$arrayclaves=array(),$arrayformatos=array())
	{
		$fila=$filainicial;
		$columna=$columnainicial;
		$cantidad=count ($arrayclaves);
		$primero=true;
		
		if (is_array ($resultado))
		{
			$result=array ($resultado);
			foreach ($result as $datosencontrados)
				{
				if ($cantidad==0 && $primero)
				{
					$arrayclaves=array_keys($datosencontrados);
					$primero=false;
				}
				foreach ($arrayclaves as $claves)
				{
					if (isset ($arrayformatos[$claves]) && (!is_null ($arrayformatos[$claves]) ))
					{	
						$this->CargarDatos ($fila,$columna,$datosencontrados[$claves],$arrayformatos[$claves]);
					}
					else
						$this->CargarDatos ($fila,$columna,$datosencontrados[$claves]);
					$columna=$columna+1;
				}
				$columna=$columnainicial;
				$fila=$fila+1;
			}
		}
		else
		{
			while ($datosencontrados=mysql_fetch_assoc ($resultado))
			{
				if ($cantidad==0 && $primero)
				{
					$arrayclaves=array_keys($datosencontrados);
					$primero=false;
				
				}
				foreach ($arrayclaves as $claves)
				{
					if (isset ($arrayformatos[$claves]) && (!is_null ($arrayformatos[$claves]) ))
					{	
						$this->CargarDatos ($fila,$columna,$datosencontrados[$claves],$arrayformatos[$claves]);
					}
					else
						$this->CargarDatos ($fila,$columna,$datosencontrados[$claves]);
					$columna=$columna+1;
				}
				$columna=$columnainicial;
				$fila=$fila+1;
			}
		}
	
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n borra todo el formato cargado, o uno si se ingresa alguno.

// Par�metros de Entrada:
//		nombre= nombre del formato a borrar.

	function BorrarFormato ($nombre=false)
	{
		if ($nombre===false)
			$this->Formato=array();
		else
			unset ($this->Formato[$nombre]);
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n agrega un formato.

// Par�metros de Entrada:
//		tipo= Tipo de formato.
// 		dato: el dato del formato

	function AddFormato ($tipo,$dato)
	{
		
		switch ($tipo) {

			case "Tama�o" : 
				$this->Formato['Size']=$dato;
				break;
			case "Align":
				$this->Formato['Align']=$dato;
				break;
			case "Color":
				$this->_Colores($dato,$color);
				$this->Formato['Color']=$color;
				break;
			case "FgColor":
				$this->_Colores($dato,$color);
				$this->Formato['FgColor']=$color;
				break;
			case "BgColor":
				$this->_Colores($dato,$color);
				$this->Formato['BgColor']=$color;
				break;
			case "Bold":
				$this->Formato['Bold']=$dato;
				break;
			case "Bottom":
				$this->Formato['Bottom']=$dato;
				break;
			case "Top":
				$this->Formato['Top']=$dato;
				break;
			case "Right":
				$this->Formato['Right']=$dato;
				break;
			case "Border":
				$this->Formato['Border']=$dato;
				break;
			case "BorderColor":
				$this->_Colores($dato,$color);
				$this->Formato['BorderColor']=$color;
				break;
			case "TopColor":
				$this->Formato['TopColor']=$dato;
				break;
			case "RightColor":
				$this->Formato['RightColor']=$dato;
				break;
			case "LeftColor":
				$this->Formato['LeftColor']=$dato;
				break;
			case "Pattern":
				$this->Formato['Pattern']=$dato;
				break;
			case "Underline":
				$this->Formato['Underline']=$dato;
				break;
			case "TextRotation":
				$this->Formato['TextRotation']=$dato;
				break;
			case "NumFormat":
				$this->Formato['NumFormat']=$dato;
				break;
			case "Script":
				$this->Formato['Script']=$dato;
				break;
			case "Font":
				$this->Formato['FontFamily']=$dato;
				break;	
			case "Italic":
				$this->Formato['Italic']="";
				break;						
			case "VAlign":
				$this->Formato['vAlign']=$dato;
				break;			
			case "HAlign":
				$this->Formato['hAlign']=$dato;
				break;
			case "TextWrap":
				$this->Formato['TextWrap']=$dato;
				break;
			
			}	
			
	
	}



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n que esconde todas los grises de las grillas en la presentaci�n preliminar.

	function EsconderGrillas()
	{
		$this->Hojas[$this->HojaActual]->hideGridLines();
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n setea la p�gina horizontal.

	function PaginaHorizontal()
	{
		$this->Hojas[$this->HojaActual]->setLandscape();
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n setea la p�gina vertical.
 
 	function PaginaVertical()
	{
		$this->Hojas[$this->HojaActual]->setPortrait();
	}
 


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n agrega una hoja, cuando se agrega una hoja se setear� como la p�gina actual.
	function AddHoja ($nombre)
	{
		$this->Hojas[$nombre]=& $this->Excel->addWorksheet($nombre);
		$this->HojaActual=$nombre;
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n que cambia de hoja.
	function CambiarHoja ($nombre)
	{
		//verificar que exista en el array
		$this->HojaActual=$nombre;
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n que envia el excel al usuario.
	function Enviar ()
	{
		$this->Excel->close();
	}


//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n que ingresando el color en castellano devuelve el color en ingles.
	function _Colores($color,&$colororiginal)
	{
	
		switch ($color) {

			case "gris" : 
				$colororiginal="gray";
				break;
			case "azul":
				$colororiginal="blue";
				break;
			case "rojo":
				$colororiginal="red";
				break;
			case "magenta":
				$colororiginal="magenta";
				break;
			case "negro":
				$colororiginal="black";
				break;
			case "blanco":
				$colororiginal="white";
				break;
			case "cyan":
				$colororiginal="cyan";
				break;
			case "marron":
				$colororiginal="brown";
				break;
			case "amarillo":
				$colororiginal="yellow";
				break;			
			case "aqua":
				$colororiginal="aqua";
				break;		
			case "lima":
				$colororiginal="lime";
				break;			
			case "fuxia":
				$colororiginal="fuchsia";
				break;
			case "violetaoscuro":
				$colororiginal="navy";
				break;
			case "naranja":
				$colororiginal="orange";
				break;
			case "violeta":
				$colororiginal="purple";
				break;
			case "plata":
				$colororiginal="silver";
				break;
			default:
				$colororiginal=$color;
		}
	
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
//Funci�n que busca si ya fue utilizado el formato y retorna su referencia. Si no existe, agrega el formato y retorna la referencia

// Par�metros de Entrada:
//		formato: array de formatos a buscar

	function _BuscarFormatoYAgregar($formato)
	{
		foreach($this->FormatosUsados as $datos)
		{
			if($formato==$datos["formato"])
				return $datos["referencia"];
		}

		$referencia=& $this->Excel->addFormat($formato);
		$this->FormatosUsados[]=array("referencia"=>$referencia,"formato"=>$formato);

		return $referencia;
	}




}	


?>