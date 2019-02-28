<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport"/>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<link href="{pigcms{$static_path}css/base.css" rel="stylesheet">
<link href="{pigcms{$static_path}css/cate_list.css" rel="stylesheet">
<link rel="stylesheet" href="{pigcms{$static_path}css/swiper.min.css" type="text/css">
<title>技师列表</title>

<script src="{pigcms{$static_path}js/rem.js"></script>
<script type="text/javascript">
var locationUrl="{pigcms{:U('Appoint/workerList')}";
var sort_id="{pigcms{$_GET['sort_id']}";
var page="{pigcms{$_GET['page']}";
var cat_id="{pigcms{$_GET['cat_id']}";
</script>
<style type="text/css">
.no-deals{
	    text-align: center;
    margin-top: 50px;
    width: 100%;
}
</style>
</head>
<body>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_path}js/fakeLoader.min.js"></script>
<link rel="stylesheet" href="{pigcms{$static_path}css/fakeLoader.css">
 <div class="container">
    <div class="fakeloader"></div>
</div> 
<script>
            $(".fakeloader").fakeLoader({
                timeToHide:1200,
                bgColor:"#F87EA3",
                spinner:"spinner1"
            });
    </script>
<header>
    <div class="categroy_header table">
        <ul class="clearfix">
            <li><a href="{pigcms{:U('Appoint/productList',array('cat_id'=>$_GET['cat_id']))}">项目</a></li>
             <li class="active"><a href="{pigcms{:U('Appoint/workerList',array('cat_id'=>$_GET['cat_id']))}">技师</a></li>
        </ul>
        <i onclick="location.href='{pigcms{:U('search')}'"></i></div>
</header>
<article>
    <section class="categroy_table">
    <ul class="categroy_title clearfix">
            <volist name="sort_array" id="vo">
                <li data-sort-id="{pigcms{$vo.sort_id}" <if condition="$vo['sort_id'] eq $now_sort_array['sort_id']">class="active"</if> onclick="list_location($(this));return false;"><a href="javascript:void(0)">{pigcms{$vo.sort_value}</a></li>
            </volist>
        </ul>
        <if condition="$merchant_workers_list['worker_list']">
        <ul class="categroy_craft">
        <volist name="merchant_workers_list['worker_list']" id="vo">
            <a href="{pigcms{:U('workerDetail',array('merchant_workers_id'=>$vo['merchant_worker_id']))}"><li class="clearfix">
                <div class="head_img"><img src="{pigcms{$config.site_url}/upload/appoint/{pigcms{$vo.avatar_path}" /></div>
                <div class="cate_txt">
                    <p class="name">{pigcms{$vo.name}</p>
                    <p>
                    
                    <if condition="$vo['comment_num'] neq 0">
                        <if condition="$vo['all_avg_score'] neq 0">
                            <for start='0' end='floor($vo["all_avg_score"])'>
                                 <img src="{pigcms{$static_path}images/shoucnagdianji.png" width="15px" height="15px"/>
                            </for><for start='floor($vo["all_avg_score"])' end='5'>
                                <img src="{pigcms{$static_path}images/shoucang.png" width="15px" height="15px" />
                            </for>
                        <else/>
                            <for start='0' end='5'>
                                <img src="{pigcms{$static_path}images/shoucang.png" width="15px" height="15px" />
                            </for>
                        </if>
                    <else/>
                    	<for start='0' end='5'>
                                <img src="{pigcms{$static_path}images/shoucnagdianji.png" width="15px" height="15px" />
                            </for>
                    </if>
                    
                    <if condition="$vo['comment_num'] neq 0">
                        <if condition="$vo['all_avg_score'] egt 4.8">
                            <span>顶级</span>
                        <elseif condition="$vo['all_avg_score'] egt 4.5"/>
                            <span>优秀</span>
                        <elseif condition="$vo['all_avg_score'] egt 4" />
                            <span>卓越</span>
                        <elseif condition="$vo['all_avg_score'] egt 3.5"/>
                            <span>良好</span>
                        <else/>
                            <span>一般</span>
                        </if>
                    <else/>
                    	<span>顶级</span>
                    </if>
                    </p>
                    <p>
                    
                   <if condition="$vo['appoint_num'] lt 10">
                    <p><span>接单&nbsp;<b>10次以下</b></span>
                    <else/>
                    <p><span>接单&nbsp;<b>{pigcms{$vo.appoint_num}次</b></span>
                    </if>
                    
                    <if condition="$vo['appoint_price'] neq 0.00"><span>均价:<b>￥{pigcms{$vo.appoint_price}</b></span></if></p>
                </div>
            </li></a>
         </volist>
        </ul>
        <else/>
        	<div class="no-deals">暂无技师列表</div>
        </if>
    </section>
    <div style=" text-align:center">{pigcms{$merchant_workers_list.pagebar}</div>
</article>


<script type="text/javascript">
function list_location(obj){
	var now_sort_id = obj.attr('data-sort-id');
	if(now_sort_id){
		page=0;
		locationUrl += '&sort_id='+now_sort_id;
	}
	
	if(cat_id){
		page=0;
		locationUrl += '&cat_id='+cat_id;
	}
	
	if(page){
		locationUrl += '&page='+page;
	}
	location.href=locationUrl;
}
</script>
</body>
</html>
