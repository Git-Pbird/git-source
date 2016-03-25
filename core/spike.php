<?php
#	18.07.2014
#	Набор костылей 
#	Для работы с сайтами

function look($var='Функция LOOK ожидает аргумент'){
	echo "<pre><p>";
	print_r (var_dump($var));
	echo "</p></pre>";
}
function clearData($data,$type='string')
{
$clearedData		=	false;
	switch ($type)
	{
	case 'string':
			$clearedData	=	trim(htmlspecialchars(strip_tags($data)));
			break;
	default:
			break;
	}
return $clearedData;
}
/*-------------------работа с папками-----------------------------------------*/
function setDir ($name){
	if(!is_dir($name)){ mkdir($name); }
}

function lookDir ($path,$revers=false) {

	$file	=	NULL;
	if(!is_dir($path)) {return false;}
	$dir	=	opendir($path);
	$file	=	scandir($path);

	foreach($file as $key=>$list_item)
	{
		if($list_item == '.' OR $list_item== '..')
		{
		unset($file[$key]);
		}
	}
	
	if($revers == true)					//	если нужен реверсный порядок то
		$file =  array_reverse($file);	//	переворачиваем массив после удаления родительских папок
	return $file;
}


/*-------------------------работа с файлами----------------------------------------------------------*/

/* --- Фильтр разширений --- */
function	filterExt($file,$ext=array())
{
	if(!$file){return false;}
	if(!$ext)
	{
		return $file;
	}
	else
	{
		if(!is_array($file))
		{
			$file = explode('.',$file);
			$file = array_pop($file);
			if (!in_array($file,$ext) )
				{
					unset($file);
				}
			return	$file;
		}
		else
		{
			foreach ($file as $key=>$item)
			{
			$item = explode('.',$item);
			$item = array_pop($item);
			if (!in_array($item,$ext) )
				{
					unset($file[$key]);
				}
			
			}
		return	$file;
		}
	}
}

/*--------------------------Работа с массивами--------------------------------------------*/
function iconvArray($inputArray,$newEncoding,$oldEncoding='Windows-1251'){
  $outputArray=array();
    if ($newEncoding!=''){
      if (!empty($inputArray)){
        foreach ($inputArray as $element){
          if (!is_array($element)){
            $element=iconv($oldEncoding, $newEncoding, $element);
          } else {
            $element=iconvArray($element);
          }
          $outputArray[]=$element;
        }
      }
    }
  return $outputArray;
}

function merge($array_1,$array_2=array())
{
	$default_array	=	array();
	if(count($array_1)==0)
	{
		if(count($array_2)==0)
			$result_array	=	$default_array;
			$result_array	=	$array_2;
	}
	else
	{
		if(count($array_2)==0)
			$result_array	=	$array_1;
			$result_array	=	array_merge($array_1,$array_2);
	}
	return	$result_array;
}

/*------------------------Генератор CSS-----------------------------------------------------------*/
function getRandomRadius($width=null,$height=null){
	if ($width!=null OR $height!=null)
	{
	$w1 =	rand(10,$width);	$h1	=	rand(10,$height);
	$w2	=	rand(10,$width);	$h2	=	rand(10,$height);
	$w3	=	rand(10,$width);	$h3	=	rand(10,$height);
	$w4	=	rand(10,$width);	$h4	=	rand(10,$height);		
	$top_left	=	"
	border-top-left-radius:				$w1".'px'." $h1".'px'.";
	-moz-border-radius-topleft:			$w1".'px'." $h1".'px'.";
	-webkit-border-top-left-radius:		$w1".'px'." $h1".'px'.";";
	$top_right	=	"
	border-top-right-radius:			$w2".'px'." $h2".'px'.";
	-moz-border-radius-topright:		$w2".'px'." $h2".'px'.";
	-webkit-border-top-right-radius:	$w2".'px'." $h2".'px'.";";
	$bottom_left=	"
	border-bottom-left-radius:			$w3".'px'." $h3".'px'.";
	-moz-border-radius-bottomleft:		$w3".'px'." $h3".'px'.";
	-webkit-border-bottom-left-radius:	$w3".'px'." $h3".'px'.";";
	$bottom_right=	"
	border-bottom-right-radius:			$w4".'px'." $h4".'px'.";
	-moz-border-radius-bottomright:		$w4".'px'." $h4".'px'.";
	-webkit-border-bottom-right-radius:	$w4".'px'." $h4".'px'.";";

		$result = "width:$width".'px'.";height:$height".'px'.";".$top_left.$top_right.$bottom_left.$bottom_right;
	}
	else
		$result = false;
		
return $result;
}

function getHeight($count,$num,$height){ //Массив данных,число елементов в строчке, высота одного елемента
	if($count%$num==0)
		$r = ($count/$num)*$height;
	else 
		$r = ((($count-$count%$num)/$num)+1)*$height;
return	$r;
}

/*------------------------Test функции-----------------------------------------------------------*/
function	look_url_info()
{
	$preset_value	= array
			(
			"SCRIPT_FILENAME",
			"SCRIPT_NAME",
			"REQUEST_METHOD",
			"REQUEST_URI",
			"REDIRECT_URL",
			"QUERY_STRING",
			"REDIRECT_QUERY_STRING",
			);
	echo "<table>";
	foreach ($preset_value	as	$server_variable)
	{	
	if(isset($_SERVER[$server_variable]))
		echo	"<tr><td>".'$_SERVER[\''.$server_variable.'\']'."</td><td>$_SERVER[$server_variable]</td></tr>";
	else
		echo	"<tr><td>".'$_SERVER[\''.$server_variable.'\']'."</td><td><b>NULL</b></td></tr>";
	}
	echo "</table>";
}

function	look_server_info()
{
	echo "<b> REQUEST_METHOD :</b>"; echo"$_SERVER[REQUEST_METHOD]<br>";
	echo "<b>POST :</b>"; look($_POST);
	echo "<b>GET :</b>"; look($_GET);
	echo "<b>REQUEST:</b>";	look($_REQUEST); 
	echo "<b>SESSION:</b>"; look($_SESSION);
	echo "<b>COOKIE:</b>";	look($_COOKIE);
	echo "<b>SERVER:</b>";
	
	$preset_value	= array
			(
			"HTTP_HOST",
			"HTTP_REFERER",
			"SERVER_NAME",
			"SERVER_ADDR",
			"REMOTE_ADDR",
			"REQUEST_METHOD",
			"QUERY_STRING",
			"REQUEST_URI",
			"PHP_SELF",
			"REQUEST_TIME"
			);
	echo "<table>";
	foreach ($preset_value	as	$server_variable)
	{	
	if(isset($_SERVER[$server_variable]))
		echo	"<tr><td>".'$_SERVER[\''.$server_variable.'\']'."</td><td>$_SERVER[$server_variable]</td></tr>";
	}
	echo "</table>";
	
}


?>