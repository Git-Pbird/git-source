<?php
namespace application\models;

use core\basic\AModel		as	AModel;

Class Image
	extends	AModel
{
private static $instance;

	protected function __construct(){
		parent::__construct('images','image_id');
	#	$this->table= 'gallery';
	#	$this->pk='gallery_id';
	}
	
	static function Instance(){		
			if(	self::$instance	===	null)
				self::$instance	=	new Image();
		return	self::$instance;
	}
	
	public function upload_base64($name,$value){
		
		if(!$this->check_type($name)){
			return false;
		}
		
		$getMime= explode('.',$name);
		$mime	= strtolower(end($getMime));
		$f_name	= mt_rand(0,1000000) .'.'. $mime;
		
		$id = $this->insert('images',array('path'=>$f_name));
		
		$this->move_upload_base64($value,$f_name);
		return $id;
	}
	
	private function check_type($name){
		$getMime= explode('.',$name);
		$mime	= strtolower(end($getMime));
		$types	= array('jpg','png','gif','bmp','jpeg');
		return in_array($mime,$types);
	}
	
	private function move_upload_base64($file,$name){
		//	выделим данные
		$data = explode(',',$file);
		
		//	Декодируем
		$encodedData = str_replace(' ', '+', $data[1]);
		$decodedData = base64_decode($encodedData);
		
		//	Создаем изображение на сервере
		if(file_put_contents(IMG_BIG_DIR . $name,$decodedData)){
			$this->resize(IMG_BIG_DIR . $name, IMG_SMALL_DIR . $name, IMG_SMALL_WIDTH);
			return true;
		}
		return false;
	}
	
	private function resize($src,$dest,$width,$height=null,$rgb=0xffffff,$quality=75){
		if(!file_exists($src)) return false;
		
		$size = getimagesize($src);
		
		if($size===false) return false;
		
		$format = strtolower(substr($size['mime'],strpos($size['mime'],'/')+1));
		$icfunc	= 'imagecreatefrom'.$format;
		if(!function_exists($icfunc)) return false;
		
		$x_ratio = $width / $size[0];
		if( $height === null)
			$height = $size[1] * $x_ratio;
		
		$y_ratio	= $height / $size[1];
		
		$ratio		= min($x_ratio,$y_ratio);
		$use_x_ratio= ($x_ratio == $ratio);
		
		$new_width	= $use_x_ratio	?	$width	:	floor($size[0]*$ratio);
		$new_height	= !$use_x_ratio	?	$height	:	floor($size[1]*$ratio);
		$new_left	= $use_x_ratio	?	0		:	floor(($width-$new_width)/2);
		$new_top	= !$use_x_ratio	?	0		:	floor(($height-$new_height)/2);
		
		$isrc	=	$icfunc($src);
		$idest	=	imagecreatetruecolor($width,$height);
		
		imagefill($idest,0,0,$rgb);
		imagecopyresampled($idest,$isrc,$new_left,$new_top,0,0,$new_width,$new_height,$size[0],$size[1]);
		
		imagejpeg($idest,$dest,$quality);
		
		imagedestroy($isrc);
		imagedestroy($idest);
		
		return true;
	}
	
	public function dropImage($image_id){
		$img_id	= (int)$image_id;
		
		$sql1	= 'SELECT ?n FROM ?n WHERE ?u';
		$where1	= array('image_id'=>$img_id);
		$file_name = $this->db->getOne($sql1,'path','images',$where1);
		
		$sql2	= 'DELETE FROM ?n WHERE ?u;';
		$where2	= array('image_id'=>$img_id);
		$this->db->query($sql2,'images',$where2);
		
		if(file_exists(IMG_BIG_DIR.$file_name)){
			unlink(IMG_BIG_DIR.$file_name);
		}
		if(file_exists(IMG_SMALL_DIR.$file_name)){
			unlink(IMG_SMALL_DIR.$file_name);
		}
		return true;
	}
}
