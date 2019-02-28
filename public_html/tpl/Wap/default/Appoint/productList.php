<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport"/>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<link href="{pigcms{$static_path}css/base.css" rel="stylesheet">
<link href="{pigcms{$static_path}css/cate_list.css" rel="stylesheet">
<link rel="stylesheet" href="{pigcms{$static_path}css/swiper.min.css" type="text/css">
<title>项目列表</title>
<script src="{pigcms{$static_path}js/rem.js"></script>
<script type="text/javascript">
var locationUrl="{pigcms{:U('Appoint/productList')}"+'&cat_id='+{pigcms{$_GET['cat_id']};
var sort_id="{pigcms{$_GET['sort_id']}"
var page="{pigcms{$_GET['page']}"
</script>

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
            <li class="active"><a href="{pigcms{:U('Appoint/productList',array('cat_id'=>$_GET['cat_id']))}">项目</a></li>
            <li><a href="{pigcms{:U('Appoint/workerList',array('cat_id'=>$_GET['cat_id']))}">技师</a></li>
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
        <if condition="$product_list['group_list']">
            <ul class="categroy_product clearfix">
                <volist name="product_list['group_list']" id="vo">
                    <li><a href="{pigcms{$vo.url}">
                        <div class="cate_img"><img src="{pigcms{$config.site_url}/upload/appoint/{pigcms{$vo['pic']}" /></div>
                        <p>{pigcms{$vo['appoint_name']}</p><p>
                        <if condition="$vo['appoint_type'] eq 0">
                        	<span class="imgLabel daodian"></span>
                        <else/>
                        	<span class="imgLabel shangmen"></span>
                        </if>
                        </p>
                        <p><span>￥<em>{pigcms{$vo['appoint_price']}</em></span><span><em>{pigcms{$vo['appoint_sum']}</em>人做过</span></p>
                        </a></li>
                </volist>
            </ul>
       <else/>
       	<div class="no-deals">暂无此类，请查看其他分类</div>
       </if>
    </section>
    <div style=" text-align:center">{pigcms{$product_list.pagebar}</div>
    <div class="shade hide"></div>
				<div class="loading hide">
			        <div class="loading-spin" style="top:91px;"></div>
			    </div>
</article>

<script type="text/javascript">
function list_location(obj){
	var now_sort_id = obj.attr('data-sort-id');
	
	if(now_sort_id){
		page=0;
		locationUrl += "&sort_id="+now_sort_id;
	}
	
	if(page){
		locationUrl += '&page='+page;
	}
	
	//locationUrl += "&sort_id="+now_sort_id+"&page="+page;
	location.href=locationUrl;
}
</script>
</body>
</html>
