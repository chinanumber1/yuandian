<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{pigcms{$cat_name}</title>
    <meta name="keywords" content="{pigcms{$classify['seo_keywords']}" />
    <meta name="description" content="{pigcms{$classify['seo_description']}" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}classify/base.css" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}classify/listui.css" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}classify/common.css" />
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <style type="text/css">
   #pageHtml{font-size: 20px;}
   #pageHtml .current{border: 1px solid #e1e1e1;display:inline-block; height:30px;line-height:30px;width:35px;background-color:#06c1ae;color:#fff;margin: 0 1px;padding: 0 0 0 1px;}
   #pageHtml a{height: 30px;line-height: 30px;}
   #pageHtml .pga{ border: 1px solid #e1e1e1;width: 35px;}
  </style>
</head>
<body>
    <div id="site-mast" class="site-mast">
        <include file="topbar"/>
    </div>
    <div id="homeWrap" class="wrapper">
        <div id="header"  class="mainpage">
            <div id="headerinside">
                <if condition="isset($config['classify_logo']) AND !empty($config['classify_logo'])">
                    <span id="logo" style="top:10px">
                        <a href="{pigcms{$siteUrl}/classify/" target="_blank">
                            <img src="{pigcms{$config.classify_logo}" alt="{pigcms{$config.classify_name}" title="{pigcms{$config.classify_name}" width="180" height="58" />
                        </a>
                    </span>
                    <else/>
                    <span id="logo">
                        <a href="/" target="_blank">
                            <img src="{pigcms{$config.site_logo}" alt="{pigcms{$config.classify_name}" title="{pigcms{$config.classify_name}" width="160" height="45" />
                        </a>
                        <a href="{pigcms{$siteUrl}/classify/" class="classify">{pigcms{$config.classify_name}</a>
                    </span>
                </if>
                <form action="{pigcms{$siteUrl}/classify/searchlist.html" method="get" name="mysearch">
                    <div id="searchbar">
                        <div id="saerkey">
                            <span>
                            <input type="text" id="keyword" name="keystr" class="keyword" value="<if condition="$_GET['keystr']">{pigcms{$_GET['keystr']}<else/>请填写关键词进行搜索</if>" onblur="if(this.value=='')this.value='请填写关键词进行搜索',this.className='keyword'" onfocus="if(this.value=='请填写关键词进行搜索')this.value='',this.className='keyword2'" />
                            </span>
                        </div>
                        <div class="inputcon">
                            <input type="submit" class="btnall" value="搜一搜" onmousemove="this.className='btnal2'" onmouseout="this.className='btnall'" />
                        </div>
                        <div class="clear"></div>
                        <div class="search-no">
                            <span id="hot"></span>
                            <span class="hot2"></span>
                        </div>
                    </div>
                </form>
                <a href="{pigcms{$siteUrl}/classify/selectsub.html" id="fabu" rel="nofollow"> <i></i>
                    免费发布信息
                </a>
            </div>
        </div>
        <div class="hShadow"></div>
        <div class="navcon" id="nav">
            <ul class="nav2">
                <li>
                    <a href="{pigcms{$siteUrl}/classify/">首页</a>
                </li>
                <if condition="!empty($navClassify)">
                    <volist name="navClassify" id="nav">
                        <li <if condition="$nav['cid'] eq $fcid">class="on"</if>
                        >
                        <a href="{pigcms{$siteUrl}/classify/subdirectory-{pigcms{$nav['cid']}.html">{pigcms{$nav['cat_name']}</a>
                    </li>
                </volist>
            </if>
        </ul>
        <div id="1003" class="ad_nav"></div>
    </div>
</div>

<!-- =S mainlist -->
<div id="mainlist" class="clearfix pr mainpage" style="margin-top: 20px;">
    <div id="infolist">
        <div class="filterbar"></div>
        <table class="tbimg" cellpadding="0" cellspacing="0">
            <tbody>
                <if condition="!empty($listsdatas)">
                    <volist name="listsdatas" id="vl">
                        <tr>
                            <td class="img">
                                <a <if condition="empty($vl['jumpUrl'])">
                                    href="{pigcms{$siteUrl}/classify/{pigcms{$vl['id']}.html"
                                    <else/>
                                    href="{pigcms{$vl['jumpUrl']}"
                                </if>
                                target="_blank">
                                <if condition="isset($vl['imgthumbnail'])">
                                    <img src="{pigcms{$vl['imgthumbnail']}" alt="" />
                                    <else/>
                                    <img src="{pigcms{$static_path}classify/img/noimg.jpg" alt="" />
                                </if>
                            </a>
                        </td>
                        <td class="t">
                            <a <if condition="empty($vl['jumpUrl'])">
                                href="{pigcms{$siteUrl}/classify/{pigcms{$vl['id']}.html"
                                <else/>
                                href="{pigcms{$vl['jumpUrl']}"
                            </if>
                            class="t">
                            <span class="bt" <if condition="!empty($vl['btcolor'])">style="color:{pigcms{$vl['btcolor']}"</if>
                            >
                            <if condition="isset($qstr) AND !empty($qstr)">
                                {pigcms{$vl['title']|str_replace=$qstr,' <b>'.$qstr.'</b>
                                ',###}
                                <else/>
                                {pigcms{$vl['title']}
                            </if>
                        </span>
                        <if condition="$vl['toptime'] gt 0">
                            &nbsp;
                            <span class="ico ding"></span>
                        </if>
                    </a> <i class="clear"></i>
                    <p>
                        <if condition="isset($qstr) AND !empty($qstr)">
                            {pigcms{$vl['input1']|str_replace=$qstr,' <b class="showsearch">'.$qstr.'</b>
                            ',###}
                            <else/>
                            {pigcms{$vl['input1']}
                        </if>
                    </p>
                    <p>
                        <if condition="isset($qstr) AND !empty($qstr)">
                            {pigcms{$vl['input2']|str_replace=$qstr,'
                            <b class="showsearch">'.$qstr.'</b>
                            ',###}
                            <else/>
                            {pigcms{$vl['input2']}
                        </if>
                    </p>
                    <i class="clear"></i>
                </td>

                <td class="tc">
                    <if condition="isset($qstr) AND !empty($qstr)">
                        {pigcms{$vl['input3']|str_replace=$qstr,'
                        <b class="showsearch">'.$qstr.'</b>
                        ',###}
                        <else/>
                        {pigcms{$vl['input3']}
                    </if>
                </td>

                <td>
                    <p style="float:right;color:#ff7201;">{pigcms{$vl['timestr']}</p>
                    <if condition="isset($qstr) AND !empty($qstr)">
                        {pigcms{$vl['input4']|str_replace=$qstr,'
                        <b class="showsearch">'.$qstr.'</b>
                        ',###}
                        <else/>
                        {pigcms{$vl['input4']}
                    </if>
                </td>
            </tr>
        </volist>
        <else/>
        <tr>
            <td colspan=3 style="text-align:center;font-size: 25px;font-weight: bold;text-align: center; height: 100px;line-height: 100px;">没有数据！</td>
        </tr>
    </if>

</tbody>
</table>

<div class="pager mb10" id="pageHtml">{pigcms{$pagebar}</div>
</div>

</div>
<include file="Classify:footer"/>
<div class="clear"></div>
</body>
<script  src="{pigcms{$static_path}classify/banner.js"></script>
</html>