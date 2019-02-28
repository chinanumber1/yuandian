<?php
class Page
{
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
	public function __construct($totalRows, $listRows)
	{
		$this->totalRows = $totalRows;
		$this->nowPage  = !empty($_GET['page']) ? intval($_GET['page']) : 1;
		$this->listRows = $listRows;
		$this->totalPage = ceil($totalRows / $listRows);
		if($this->nowPage > $this->totalPage && $this->totalPage > 0) {
			$this->nowPage = $this->totalPage;
		}
		$this->firstRow = $listRows * ($this->nowPage - 1);
	}
    public function show()
    {
		if($this->totalRows == 0) return false;
		$now = $this->nowPage;
		$total = $this->totalPage;
		$url  =  $_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?') ? '' : '?');
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'], $params);
            unset($params['page']);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
		$url .= (strpos($url, '?') ? '' : '?') . 'page=';
		
		$str = '<div class="pages">';
		if($now > 1){
			$str .= '<a href="' . $url . '1" class="a_first">首页</a><a href="' . $url . ($now - 1) . '" class="a_prve">上一页</a>';
		}
		
		$str .= '<span class="num">';
		for ($i=1; $i<=5; $i++) {
			if ($now <= 1) {
				$page = $i;
			} elseif ($now > $total - 1) {
				$page = $total - 5 + $i;
			} else {
				$page = $now - 3 + $i;
			}
			if ($page != $now  && $page > 0) {
				if ($page <= $total) {
					$str .= '<a href="' . $url . $page . '">' . $page . '</a>';
				} else {
					break;
				}
			} else {
				if($page == $now) $str .= '<a href="javascript:;" class="a_cur">'.$page.'</a>';
			}
		}
		$str .= '</span>';
		if ($now != $total){
			$str .= '<a href="'.$url.($now+1).'" class="a_next">下一页</a><a href="' . $url . $total .'" class="a_end">尾页</a>';
		}
		$str .= '</div>';
		return $str;
    }
}
?>