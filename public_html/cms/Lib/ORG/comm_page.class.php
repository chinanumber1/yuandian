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
	//架构函数
	public function __construct($totalRows,$listRows){
		$this->totalRows = $totalRows;
		$this->nowPage  = !empty($_GET['page']) ? intval($_GET['page']) : 1;
		$this->listRows = $listRows;
		$this->totalPage = ceil($totalRows/$listRows);
		$this->firstRow = $listRows*($this->nowPage-1);
	}
}
?>