<?php
class Page{
	// 起始行数
    public $firstRow;
	//现在页数
	public $nowPage;
	//总页数
	public $totalPage;
	//总行数
	public $totalRows;
	//分页的条数
	public $page_rows;
	//分页的参数
	public $page_val;
	//架构函数
	public function __construct($totalRows,$listRows,$page_val){
		$this->totalRows = $totalRows;
		$this->nowPage  = !empty($_GET[$page_val]) ? intval($_GET[$page_val]) : 1;
		$this->listRows = $listRows;
		$this->page_val = $page_val;
		$this->totalPage = ceil($totalRows/$listRows);
		if($this->nowPage > $this->totalPage && $this->totalPage>0){
			$this->nowPage = $this->totalPage;
		}
		$this->firstRow = $listRows*($this->nowPage-1);
	}
    public function show(){
		if($this->totalRows == 0) return false;
		$now = $this->nowPage;
		$total = $this->totalPage;
		if($total == 1) return false;
		
		$page_val = $this->page_val;
		
		$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?");
        $parse = parse_url($url);
        if(isset($parse['query'])){
            parse_str($parse['query'],$params);
            unset($params[$page_val]);
			if(!empty($params)){
				$url  = $parse['path'].'?'.http_build_query($params).'&'.$page_val.'=';
			}else{
				$url = $parse['path'].'?'.$page_val.'=';
			}
        }else{
			$url .= ''.$page_val.'=';
		}
		$str = '';
		if($now > 1){
			$str .= '<a href="'.$url.($now-1).'" class="btn btn-weak">上一页</a>';
		}else{
			$str .= '<a class="btn btn-weak btn-disabled">上一页</a>';
		}

		$str .= '&nbsp;<span class="pager-current">'.$now.'</span>&nbsp;';

		if ($now != $total){
			$str .= '<a href="'.$url.($now+1).'" class="btn btn-weak">下一页</a>';
		}else{
			$str .= '<a class="btn btn-weak btn-disabled">下一页</a>';
		}
		
		return $str;
    }
}
?>