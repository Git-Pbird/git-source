<?
namespace application\libs;
use core\basic\AController	as	AController;
use	core\router\Router		as	Router;

Class Paginator
	extends AController
{
public	$curent_page;
public	$c_per_page;
public	$option_pp;
private $nav_bar_html;

	public function __construct(){
		parent::__construct();
		$this->init();
	}
	private function init(){
		$this->curent_page	=	1;
		$this->c_per_page	=	false;
		$this->option_pp	=	array(2,4,8,12,32,50);
		$this->nav_bar_html =	'';
	}
	
	public function getNumeric($total_items){
		
		$page_number	=	$this->__pageNumber($total_items,$this->per_page);

		if ($page_number){
			$pagination			=	$this->getPagination($page_number);
			$this->nav_bar_html	=	$this->template->render(	'navigation/numeric.tpl',
														array(	'pagination'=>$pagination
															#	,'total_pages'=>$page_number
															#	,'cpage'=>$cpage
															));
		}
		return $this->nav_bar_html;
	}
	
	public function getSetter(){
		if(!$this->per_page)
			$this->per_page = $this->setPerPage();
		$setter = $this->render('Navigation/setter.tpl',array(	'per_page'=>$this->per_page,
																'option_pp'=>$this->option_pp));
		return $setter;
	}
	
	public function setCurentPage($cpage = null){
		if ($cpage != null)
			$this->curent_page = (int)$cpage;
		return $this->curent_page;
	}
	
	public function setPerPage($per_page = null){
		
		if ($per_page == null){
			$per_page = $this->getFromCookie();
		}else{
			$per_page	= (int)$per_page;
			$expire		= time() + 3600 *24 *100; 
			setcookie('perpage', $per_page, $expire);
		}
		
		return $this->per_page = $per_page;
	}
	
	private function getFromCookie(){
		$cookie = null;
		
		if ($_COOKIE['perpage'])
			$cookie = (int)$_COOKIE['perpage'];
		
		return $cookie;
	}
	
	private function __pageNumber($total_items,$per_page = 0){
		if($total_items <= $per_page)
			return false;
		return (int) ceil($total_items/$per_page);
	}
	
	private function __reAssemble($drop=''){
		$uri = '?';
			if($_SERVER['QUERY_STRING'])
			{
				foreach($_GET as $key=>$value){
				if($key != $drop) $uri .= "{$key}={$value}&amp;";
				}
			}
		return $uri;
	}
	
	private function getPagination($page_number){
		
			if ($this->curent_page > $page_number)
				$this->curent_page = $page_number;
					#	<< < 3 4 5 6 7 > >>
			$cpage		= $this->curent_page;	#	-	текущая страница
			
			$startpage	=	'&laquo;';			#	-	начало
			$back		=	'&lt;';				#	-	назад
			$page2left	=	($cpage-2);			#	-	назад на 2 страницы
			$page1left	=	($cpage-1);			#	-	назад на 1 страницу
			
			$page1right	=	($cpage+1);			#	-	вперед на 1 страницу
			$page2right	=	($cpage+2);			#	-	вперед на 2 страницы
			$forward	=	'&gt;';				#	-	вперед
			$endpage	=	'&raquo;';			#	-	конец
			
			
			
	
		$pagination	=	array();
		$uri = $this->__reAssemble('page');	//	разбираем массив ГЕТ и собираем его снова убирая указаную переменную.
		
		if($cpage > 3)					$pagination[$startpage]	=	"{$uri}" . "page=" . 1;
		if($cpage > 1)					$pagination[$back]		=	"{$uri}" . "page=" . ($cpage - 1);
		if(($cpage-2) > 0)				$pagination[$page2left]	=	"{$uri}" . "page=" . ($cpage-2);
		if(($cpage-1) > 0)				$pagination[$page1left]	=	"{$uri}" . "page=" . ($cpage-1);
		
		if($cpage)						$pagination[$cpage]		=	'';
		
		if(($cpage+1) <= $page_number)	$pagination[$page1right]=	"{$uri}" . "page=" . ($cpage+1);
		if(($cpage+2) <= $page_number)	$pagination[$page2right]=	"{$uri}" . "page=" . ($cpage+2);
		if($cpage < $page_number)		$pagination[$forward]	=	"{$uri}" . "page=" . ($cpage + 1);
		if($cpage < ($page_number-2))	$pagination[$endpage]	=	"{$uri}" . "page=" . $page_number;
		
		
		
		
		
		#return $startpage . $back . $page2left . $page1left . $cpage . $page2right . $page1right . $forward . $endpage;
		return	$pagination;
	}
}
