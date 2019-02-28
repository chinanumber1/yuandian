<!DOCTYPE html>
<html lang="en">
<head>
<title>{pigcms{$config.gift_alias_name}列表</title>
<include file="Public:gift_header" />

<script>var cat_fid = '1', ajax_url = '{pigcms{:U("Gift/ajax_gift")}', gift_detail = '{pigcms{:U("Gift/gift_detail")}', score_name = '{pigcms{$config["score_name"]}';
var order = "{pigcms{$_GET['order']}";
var type = "{pigcms{$_GET['type']}";
var cat_id = "{pigcms{$_GET['cat_id']}";
</script>
</head>

<body>
<div class="lodingCover">
    <div class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<nav class="topNav filterNav">
    <ul class="box">
        <!--li class="b-flex">
            <a <if condition='!empty($_GET["type"]) && ($_GET["type"] == "hot")'>class="active"</if> href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'type'=>'hot'))}">
                <span>热门</span>
            </a>
        </li-->
		
		<li class="b-flex">
            <p>
				<if condition='$_GET["type"] eq "new"'>
					<span>今日新品</span>
				<elseif condition='$_GET["type"] eq "integral"'/>
					<span>高端生活</span>
				<else />
					<span>{pigcms{$gift_category_detail['cat_name']}</span>
				</if>
                
            </p>
        </li>
        <li class="b-flex">
            <a id="gift_sort" <if condition='!empty($_GET["order"]) && (in_array($_GET["order"],array("integral_desc","integral_asc")))'>class="active"</if> href="javascript:void(0)">
                <span>{pigcms{$config['score_name']}值 <if condition='$_GET["order"] eq "integral_desc"'><i class="down fa fa-long-arrow-down"></i><elseif condition='$_GET["order"] eq "integral_asc"' /><i class="down fa fa-long-arrow-up"></i></if></span>
            </a>
        </li>
        <!--li class="b-flex">
            <a <if condition='!empty($_GET["type"]) && ($_GET["type"] == "new")'>class="active"</if> href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'type'=>'new'))}">
                <span>新品</span>
            </a>
        </li-->
    </ul>
</nav>

<section class="list">
    <ul>
    </ul>
</section>

<script id="giftListBoxTpl" type="text/html">
{{# for(var i in d){ }}
<li class="item item2" onclick="location.href='{{ gift_detail }}&gift_id={{ d[i].gift_id }}'">
	<div class="wrap">
		<div class="fl i-pic">
			<img src="/upload/system/gift/{{ d[i].wap_pic_list[0] }}"/>
		</div>
		<div class="ofh desc">
			<div class="wrap pr">
				<h2>{{ d[i].gift_name }}</h2>
                {{# if (d[i].exchange_type == 0 || d[i].exchange_type == 2) { }}
					<p>{{ d[i].payment_pure_integral }} <em>{{ score_name }}</em>
				{{# } else { }}
					<p>{{ d[i].payment_integral }} <em>{{ score_name }}</em> {{ d[i].payment_money }} <em>元</em></p>
				{{# } }}
				<a href="{{ gift_detail }}&gift_id={{ d[i].gift_id }}" class="aButton pa">马上兑换</a>
				<small class="tip">已兑换
                {{# if (d[i].exchanged_num > 0) { }}
					<em>{{ d[i].exchanged_num }}</em>
				{{# } else { }}
					<em>{{ d[i].sale_count }}</em>
				{{# } }}
				件</small>
			</div>
		</div>
	</div>
</li>
{{# } }}
</script>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
<script src="{pigcms{$static_path}gift/js/iscroll.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/gift.js" charset="utf-8"></script>
<script type="text/javascript">
window.shareData = {  
			"moduleName":"Gift",
			"moduleID":"0",
			"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
			"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Gift/gift_list',array('cat_id'=>$_GET['cat_id']))}",
			"tTitle": "{pigcms{$config.gift_alias_name}列表",
			"tContent": "{pigcms{$config.site_name}"
};
</script>
{pigcms{$shareScript}
</body>
</html>