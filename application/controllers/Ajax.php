<?
namespace application\controllers;

use core\basic\AController	as	AController;
use	application\models		as	Model;
use	application\libs		as	Lib;

Class Ajax
	extends AController
{
	public function __construct(){
		self::$default_method	=	'index';
	}
	public function index(){}
	
	public function ckupload(){
		
		$callback = 3;
		$file_name = $_FILES['upload']['name'];
		
		$getMime = explode('.',$file_name);
		$mime = end($getMime);
		$types = array('jpg','png','gif','bmp','jpeg');
		if(!in_array($mime,$types)){
			$error = 'Ошибка загрузки типа файла';
			$http_path = '';
		}else{
			$file_name = substr_replace(sha1(microtime(true)),'',12).'.'.$mime;
			
			$file_name_tmp = $_FILES['upload']['tmp_name'];
			$file_new_name = UPLOADPATH;
			$full_path  = $file_new_name.$file_name;
			
			if(copy($file_name_tmp,$full_path)){
				$http_path = DOMEN.$full_path;
				$error = '';
			}else{
				$error = 'Произошла ошибка при копировании файла.';
				$http_path = '';
			}
		}
		$callback = $_REQUEST['CKEditorFuncNum'];
		echo '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("'.$callback.'", "'.$http_path.'", "'.$error.'" );</script>';
	}
	
	public function uploadImage(){
		
		$result = Lib\Painter::Instance()->upload_base64($_POST); // возвращает array
				
		 if($_POST['type'] == 'gallery'){
			 if($result['id']){
				Model\Gallery::Instance()->add_image($_POST['id'],$result['id']);
				die(json_encode($result));
			} 
			die($_POST['name'].'- ошибка загрузки');
		}
		/*
		if($_POST['product']){
			if(	Lib\Painter::Instance()->upload_base64($_POST)){
				die($_POST['name'].':загружен успешно');
			} 
			die($_POST['name'].'- ошибка загрузки');
		} */
	}
	
	public function sortImages(){
		echo (int)Model\Gallery::Instance()->sortImages($_POST['gallery_id'],$_POST['images']);
	}
	
	public function getGallery(){
		$galls = Model\Gallery::Instance()->getAll();
		
		foreach ($galls as $key => $val){   
		 
			// $data[$key]['id_gallery'] = '[[--widget/gllery/'. $val['id_gallery'] . '--]]';
			$data[$key]['gallery_id'] = $val['gallery_id'];
			$data[$key]['title'] = $val['title'];	
		}	
		
		echo json_encode($data);                
	}
}
