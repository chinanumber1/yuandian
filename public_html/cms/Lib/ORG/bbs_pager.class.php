<?php

class Pager
{
    /**
     * 当前页
     */
    public $currentPage;

    /**
     * 总记录数
     */
    public $totalRecords;

    /**
     * 总页数
     */
    public $totalPages;

    /**
     * 页记录数
     */
    public $pageSize;

    /**
     * 链接前缀
     */
    public $url;

    /**
     * 构造函数
     *
     * @param integer $currentPage 当前页ID
     * @param integer $totalRecords 总记录数
     * @param string $url 链接前缀
     * @param integer $pageSize 每页显示记录数
     */
    public function __construct($totalRecords, $pageSize, $currentPage, $url)
    {
        $this->totalRecords = $totalRecords;
        $this->pageSize = $pageSize;

        if ($this->totalRecords)
        {
            $this->totalPages = ceil($this->totalRecords / $this->pageSize);
        }
        else
        {
            $this->totalPages = 1;
        }

        if ($currentPage > $this->totalPages)
        {
            $this->currentPage = $this->totalPages;
        }
        elseif ($currentPage < 1)
        {
            $this->currentPage = 1;
        }
        else
        {
            $this->currentPage = $currentPage;
        }

        $this->url = strpos($url, '?') === FALSE ? $url.'?' : $url.'&';
    }

    /**
     * 返回上一页编号
     *
     * @return integer
     */
    public function getPreLink()
    {
        return ($this->currentPage > 1) ? ($this->currentPage - 1) : 1;
    }

    /**
     * 返回下一页编号
     *
     * @return integer
     */
    public function getNextLink()
    {
        return ($this->currentPage < $this->totalPages) ? ($this->currentPage + 1) : $this->totalPages;
    }

    /**
     * 返回当前页编号
     *
     * @return integer
     */
    public function getCurrentLink()
    {
        return $this->currentPage;
    }

    /**
     * 返回链接
     *
     * @return string
     */
    public function getMultLink()
    {
        $cp_message = array(
            'prev' => '上一页',
            'next' => '下一页'
        );

        $mult = '';

        if ($this->currentPage == 1)
        {
            $mult .= '<span class="nextprev">'.$cp_message['prev'].'</span><span class="current">1</span>';

            for ($i = 2; $i <= $this->totalPages; $i++)
            {
                $mult .= '<a href="'.$this->url.'page='.$i.'">'.$i.'</a>';
                $prev = $this->totalPages - 1;

                if ($i >= 5 && $this->totalPages > 5)
                {
                    $mult .= '<span>&#8230;</span><a href="'.$this->url.'page='.$prev.'">'.$prev.'</a>';
                    $mult .= '<a href="'.$this->url.'page='.$this->totalPages.'">'.$this->totalPages.'</a>';

                    break;
                }
            }
        }
        else
        {
            $mult .= '<a href="'.$this->url.'page='.($this->currentPage - 1).'" class="nextprev">'.$cp_message['prev'].'</a>';

            if ($this->currentPage <= 8)
            {
                for ($i = 1; $i < $this->currentPage; $i++)
                {
                    $mult .= '<a href="'.$this->url.'page='.$i.'">'.$i.'</a>';
                }
            }
            else
            {
                $mult .= '<a href="'.$this->url.'page=1">1</a><a href="'.$this->url.'page=2">2</a><span>&#8230;</span>';

                for ($i = $this->currentPage -2; $i < $this->currentPage; $i++)
                {
                    $mult .= '<a href="'.$this->url.'page='.$i.'">'.$i.'</a>';
                }
            }

            $mult .= '<span class="current">'.$this->currentPage.'</span>';
        }

        if (($this->currentPage == $this->totalPages) || $this->totalPages == 0)
        {
            $mult .= '<span class="nextprev">'.$cp_message['next'].'</span>';
        }
        else
        {
            if ($this->currentPage != 1)
            {
                if ($this->currentPage <= 8)
                {
                    for ($i = $this->currentPage + 1; $i <= $this->totalPages; $i++)
                    {
                        $mult .= '<a href="'.$this->url.'page='.$i.'">'.$i.'</a>';

                        if ($i >= 6)
                        {
                            break;
                        }
                    }

                    if ($this->totalPages > 10)
                    {
                        $mult .= '<span>&#8230;</span>';
                        $mult .= '<a href="'.$this->url.'page='.($this->totalPages - 1).'">'.($this->totalPages - 1).'</a>';
                        $mult .= '<a href="'.$this->url.'page='.$this->totalPages.'">'.$this->totalPages.'</a>';
                    }
                }
                elseif (($this->totalPages - $this->currentPage) > 6)
                {
                    $endlink = $this->currentPage + 5;

                    for ($i = $this->currentPage + 1; $i < $endlink; $i++)
                    {
                        $mult .= '<a href="'.$this->url.'page='.$i.'">'.$i.'</a>';
                    }

                    $mult .= '<span>&#8230;</span>';
                    $mult .= '<a href="'.$this->url.'page='.($this->totalPages - 1).'">'.($this->totalPages - 1).'</a>';
                    $mult .= '<a href="'.$this->url.'page='.$this->totalPages.'">'.$this->totalPages.'</a>';
                }
                else
                {
                    $endlink = $this->totalPages - $this->currentPage;

                    for ($i = 1; $i <= $endlink; $i++)
                    {
                        $next = $this->currentPage + $i;
                        $mult .= '<a href="'.$this->url.'page='.$next.'">'.$next.'</a>';
                    }
                }
            }

            $mult .= '<a href="'.$this->url.'page='.($this->currentPage + 1).'" class="nextprev">'.$cp_message['next'].'</a>';
        }

        return $mult;
    }
}