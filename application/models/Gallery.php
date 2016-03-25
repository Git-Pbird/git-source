<?php
namespace application\models;

use core\basic\AModel		as	AModel;

Class Gallery
	extends	AModel
{
private static $instance;

	protected function __construct(){
		parent::__construct('gallery','gallery_id');
	}
	
	static function Instance(){		
			if(	self::$instance	===	null)
				self::$instance	=	new Gallery();
		return	self::$instance;
	}
	
	
	
	public function add_gallery($fields){
		return parent::add($fields);
	}
	
	public function add_image($gallery_id,$id_img){
		return $this->insert('gallery_images',array('gallery_id'=>(int)$gallery_id,'image_id'=>(int)$id_img));
	}
	
	public function getImages($gallery_id){
		$sql = 'SELECT * FROM ?n
				LEFT JOIN ?n using(image_id)
				WHERE ?u
				ORDER BY ?n';
		return $this->db->getAll($sql,'gallery_images','images',array('gallery_id'=>(int)$gallery_id),'num_sort');
	}
	
	public function sortImages($gallery_id,$images){
	
		$id 	= (int)$gallery_id;
		$obj	= array();
		
		for($i=0;$i<count($images);$i++){
			$image_id	= (int)$images[$i];
			$where1		= array('gallery_id'=>$id);
			$where2		= array('image_id'=>$image_id);
			$obj['num_sort'] = $i;
			$sql		= "UPDATE ?n SET ?u WHERE ?u AND ?u";
			$this->db->query($sql,'gallery_images',$obj,$where1,$where2);
		}
		return true;
	}
	
	public function dropImage($gallery_id,$image_id){
		$gid	= (int)$gallery_id;
		$img_id	= (int)$image_id;
		
		$sql	= 'DELETE FROM ?n WHERE ?u AND ?u;';
		$where1	= array('gallery_id'=>$gid);
		$where2	= array('image_id'=>$img_id);
		$this->db->query($sql,'gallery_images',$where1,$where2);
		return Image::Instance()->dropImage($img_id);
	}
	
	
	/* 
	public function edit($id,$fields){
		
		if(in_array('',$fields))
			return false;
		
		$fields['full_cach_url'] = $this->makeFURL($fields['parent_page_id'],$fields['url']);
		$fields['active'] = !isset($fields['active'])?0:1;
		$id = (int)$id;
		$where = array('page_id'=>$id);
		$this->update($this->table,$fields,$where);
		$this->changeURL($id);
		return true;
	}
	
	public function getByParent($parent_id){
		$id = (int)$parent_id;
		$sql	=	'SELECT * FROM ?n WHERE ?u ORDER BY ?n,?n ASC';
		return $this->db->getAll($sql,$this->table,array('parent_page_id'=>$id),'child_sort','page_id');
	} 
	
	
	
	private function makeFURL($parent_id,$url){
		if($parent_id == 0) return $url;
		
		$page = $this->getByID($parent_id);
		return $page['full_cach_url'].'/'.$url;
	}
	
	private function changeURL($id_parent){
		$sql		=	'SELECT * FROM ?n WHERE parent_page_id = ?s';
		$children	=	$this->db->getAll($sql,$this->table,$id_parent);
		$page		=	array();
		
		foreach ($children as $child){
			$page['full_cach_url']	= $this->makeFURL($child['parent_page_id'],$child['url']);
			$where = array('page_id'=>$child['page_id']);
			$this->update($this->table,$page,$where);
			$this->changeURL($child['page_id']);
		}
	}
	
	public function sorting($id,$fields){
		$id;//не используем
		$pages	= explode(',',$fields);
		$obj	= array();
		for($i=0;$i<count($pages);$i++){
			$page_id	= (int)$pages[$i];
			$where		= array('page_id'=>$page_id);
			$obj['child_sort'] = $i;
			$sql		= "UPDATE ?n SET ?u WHERE ?u";
			$this->db->query($sql,'pages',$obj,$where);
		}
		return true;
	} */
	
}

