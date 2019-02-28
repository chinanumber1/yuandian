
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>搜索结果页</title>
<meta name="description" content="搜索结果页">
<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name='apple-touch-fullscreen' content='yes'>
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">
<link href="{pigcms{$static_path}css/cate_list.css" rel="stylesheet" />
<link href="{pigcms{$static_path}css/base.css" rel="stylesheet" />
<script src="{pigcms{$static_path}js/rem.js"></script>
<script type="text/javascript">
var locationUrl="{pigcms{:U('Appoint/search_result')}";
var sort_id="{pigcms{$_GET['sort_id']}";
var page="{pigcms{$_GET['page']}";
var cat_id="{pigcms{$_GET['cat_id']}";
var keyword="{pigcms{$_GET['keyword']}"
</script>
</head>

<body>
<article class="sort">
    <section class="categroy_table ">
        <ul class="categroy_title clearfix">
            <volist name="sort_array" id="vo">
                <li data-sort-id="{pigcms{$vo.sort_id}" <if condition="$vo['sort_id'] eq $now_sort_array['sort_id']">class="active"</if> onclick="list_location($(this));return false;"><a href="javascript:void(0)">{pigcms{$vo.sort_value}</a></li>
            </volist>
        </ul>
    </section>
    <section class="categroy_table">
        <if condition="$merchant_workers_list['worker_list']">
        <ul class="categroy_craft">
        <volist name="merchant_workers_list['worker_list']" id="vo">
            <a href="{pigcms{:U('workerDetail',array('merchant_workers_id'=>$vo['merchant_worker_id']))}"><li class="clearfix">
                <div class="head_img"><img src="{pigcms{$config.site_url}/upload/appoint/{pigcms{$vo.avatar_path}" /></div>
                <div class="cate_txt">
                    <p class="name">{pigcms{$vo.name}</p>
                    <p>
                    
                    <if condition="$vo['order_num'] neq 0">
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
                    
                    <if condition="$vo['order_num'] neq 0">
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
</article>

<script src="{pigcms{:C('JQUERY_FILE')}"></script>
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
	
	if(keyword){
		locationUrl+='&keyword='+keyword;
	}
	
	location.href=locationUrl;
}
</script>
</body>
</html>
