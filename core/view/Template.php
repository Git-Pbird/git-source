<?
namespace core\View;
use	core\router\Router		as	Router;

Class Template
{
	
static $instance;
private	$theme_name;
private $vars;

	private function __construct(){
		$this->theme_name	=	'basic';
		$this->vars	=	array(
							'theme_name'=>$this->theme_name,
							'main_tpl'=>'',
							'title'=>'',
							'content'=>'',
							'script_up'=>array(),
							'script_bt'=>array(),
							'style_up'=>array(),
							'style_bt'=>array(),
						#	'meta_name'=>array(),
						#	'meta_http'=>array(),
							'viewport'=>'width=device-width, initial-scale=1',
							'charset'=>'UTF-8',
							'keyword'=>'',
							'description'=>'',
							'top_menu'=>'',
							'side_menu'=>'',
							'auth_bar'=>'',
							'search_box'=>''
							);
	}
	private function __clone(){}
	public function __destruct(){}
	
	public static function Instance(){
		if( self::$instance===null)
			self::$instance = new Template();
	return	self::$instance;
	}
	
	public function addScript($name,$up_position=false,$own=true){
		if(!trim($name)) return false;
		$path='';
		if($own){
			$path.= STYLEPATH.$this->theme_name.'/js/';
		}
		$filename = $path.$name.'.js';
		if(!isset($this->vars[$name])){
			if($up_position){
				$this->vars['script_up'][$name] = $filename;
			}else{
				$this->vars['script_bt'][$name] = $filename;
			}
		}
		
		return true;
	}
	
	public function addStyle($name,$up_position=false,$own=true){
		if(!trim($name)) return false;
		$path='';
		if($own){
			$path.= STYLEPATH.$this->theme_name.'/css/';
		}
		$filename = $path.$name.'.css';
		if(!isset($this->vars[$name])){
			if($up_position){
				$this->vars['style_up'][$name] = $filename;
			}else{
				$this->vars['style_bt'][$name] = $filename;
			}
		}
		return true;
	}

#	public function addMeta($attr,$value,$content){
#		switch($attr){
#			case 'http-equiv':	$this->vars['meta_http'][$value] = $content;
#			break;
#			case 'name':		$this->vars['meta_name'][$value] = $content;
#			break;			
#			default:return false;
#			break;
#		}
#		return true;		
#	}
	
	public function set($name,$value,$overwrite=true){
	#	if(isset($this->vars[$name])){
			$this->vars[$name] = ($overwrite)?$value: $this->vars[$name].' '.$value ;
	#		return true;
	#	}
	#	return false;
	}
	
		
	
	public function render($tpl,$vars=array()){  
		$tpl_path = ROOT.THEMEDIR.$this->theme_name.'/template/'.$tpl;
		if(file_exists($tpl_path)){
			ob_start();
			extract($vars);
			require	$tpl_path;
			return	ob_get_clean();
		}else{
			return('Необходимый шаблон [ '.$tpl_path.' ] не найден');
		}
	}
	
	public function print_page($replace_widget = true){
		if(!$this->vars['main_tpl'] || empty($this->vars['main_tpl'])){
			die('Главный шаблон отображения не задан');
		}
		
		$render = $this->render($this->vars['main_tpl'],$this->vars);
		
		
		if($replace_widget){
			$page = $this->replace_widget($render);
		}else{
			$page = $render;
		}
		echo $page;
	}
	
	public static function request($url){
		ob_start();
		
		if(strpos($url,'http://')===0 || strpos($url,'https://'))
			echo file_get_contents($url);
		else{
			Router::Instance()->run($url);
		}
		
		return ob_get_clean();
	}
	
	protected function replace_widget($str){
		return preg_replace_callback(
				WIDGET_REPLACE_PATTERN,
				function($e){
					return  Template::request($e[2]);
				},
				$str
		);
	}
	
}
