<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>配送员系统</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
	<script type="text/javascript">
		var location_url = "{pigcms{:U('Deliver/ajaxFinish')}", del_url = "{pigcms{:U('Deliver/del')}", DetailUrl = "{pigcms{:U('Wap/Deliver/detail', array('supply_id'=>'d%'))}";
	</script>
	<script type="text/javascript" src="{pigcms{$static_path}js/deliver_finish.js?v=210" charset="utf-8"></script>
	<script src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/report_location.js" charset="utf-8"></script>
    <style>
        .acc span{
            position: relative;
            top:-40px;
        }
        .f14.c9.acc{
            height: 55px;
            padding-top: 5px;
        }
        .acc img{
            width:50px;
            height: 50px;
            margin:0 3px    ;
        }
        .mask{
            position: fixed;
            z-index: 1000;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, .3);
             
        }
        .add{
            display: table;
            font-family: Helvetica, arial, sans-serif;
            pointer-events: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }
        .hidden{
            display: none;
        }
         .section {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="Dgrab" id="container">
	<div class="scroller" id="scroller">
		<div class="pullDown" id="pullDown">
			<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
		</div>
		<div id="finish_list"></div>
		<div class="pullUp" id="pullUp">
			<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
		</div>
		<!-- 空白图 -->
			<div class="psnone" style="display: none;">
				<img src="{pigcms{$static_path}images/qdht_02.jpg">
			</div>
		<!-- 空白图 -->
	</div>

</div>
<script id="replyListBoxTpl" type="text/html">
{{# for(var i = 0, len = d.list.length; i < len; i++){ }}
<section class="robbed supply_{{ d.list[i].supply_id }}" data-id="{{ d.list[i].supply_id }}">
	<div class="Online c9 p10 f14 go_detail" data-id="{{ d.list[i].supply_id }}">
		<span>订单编号: {{ d.list[i].real_orderid }}</span>
		{{# if(d.list[i].pay_method == 1){ }}
		<a href="javascript:;" class="fr cd p10">在线支付</a>
		{{# } else { }}
		<a href="javascript:;" class="fr cd p10 on">货到付款</a>
		{{# } }}
	</div>
    {{# if (d.list[i].item == 3) { }}
	<div class="Title m10 go_detail" data-id="{{ d.list[i].supply_id }}">
        {{# if (d.list[i].server_type == 1) { }}
        <p class="f14 c9">商品类型：{{ d.list[i].goods_name }}</p>
        <p class="f14 c9">商品重量：{{ d.list[i].goods_weight }}千克</p>
        <p class="f14 c9">商品价格：{{ d.list[i].goods_price }}</p>
        {{# if (d.list[i].image.length > 0) { }}
        <p class="f14 c9 acc"><span>商品图片:</span> 
            {{# for (var im in d.list[i].image) { }}
        	<img class="img_click" src="{{ d.list[i].image[im] }}" alt="" >
            {{# } }}
   		</p>
        {{# } }}
        <div class="leaflets">帮送</div>
        {{# } else { }}
        <p class="f14 c9">商品名称：{{ d.list[i].goods_name }}</p>
        <p class="f14 c9">商品估价：{{ d.list[i].goods_price }}元</p>
        {{# if (d.list[i].image.length > 0) { }}
        <p class="f14 c9 acc"><span>商品图片:</span> 
            {{# for (var im in d.list[i].image) { }}
        	<img class="img_click" src="{{ d.list[i].image[im] }}" alt="" >
            {{# } }}
   		</p>
        {{# } }}
        <div class="leaflets">帮买</div>
        {{# } }}
		
	</div>
	  
	<div class="delivery m10">
        {{# if (d.list[i].from_site != '') { }}
		<p class="f14 c6 on">
			<a href="javascript:;" class="clr">
				<span class="fl">取</span>
				<em class="fl">{{ d.list[i].from_site }}</em>
			</a>
		</p>
        {{# } }}
		<p class="f14 c6 on1">
			<a href="{{ d.list[i].map_url }}" class="clr">
				<span class="fl">送</span>
				<em class="fl">{{ d.list[i].aim_site }}</em>
				<i class="cd f14 fl">查看路线</i>
			</a>
		</p>
	</div>
	<div class="Namelist p10 f14">
		<h2 class="f15 c3">{{ d.list[i].name }} <span class="c6"><a href="tel:{{ d.list[i].phone }}">{{ d.list[i].phone }}</a></span></h2>
        {{# if (d.list[i].server_type == 1) { }}
		<p class="c9">取货时间：{{ d.list[i].server_time }}</p>
        {{# } else { }}
        <p class="c9">期望{{ d.list[i].server_time }}分钟内能送达</p>
        {{# } }}
		<p class="red">额外小费：<i>{{ parseFloat(d.list[i].tip_price) }}</i>元</p>
		<p class="red">{{# if (parseInt(d.list[i].distance) > 0) { }}配送距离{{ d.list[i].distance }}公里，{{# } }}配送费{{ d.list[i].freight_charge }}元</p>
	</div>
    {{# } else { }}
        <div class="Title m10 go_detail" data-id="{{ d.list[i].supply_id }}">
            <h2 class="f16 c3">{{ d.list[i].store_name }}</h2>
            <p class="f14 c9">下单时间：{{ d.list[i].order_time }}</p>
            <p class="f14 c9">送达时间：{{ d.list[i].end_time }}</p>
			{{# if(d.list[i].get_type == 1){ }}
			<div class="leaflets">系统派单</div>
			{{# } }}
        </div>
        <div class="delivery m10">
            <p class="f14 c6 on">
                <a href="#" class="clr">
                    <span class="fl">取</span>
                    <em class="fl">{{ d.list[i].from_site }}</em>   
                </a>
            </p>
            <p class="f14 c6 on1">
                <a href="{{ d.list[i].map_url }}" class="clr">
                    <span class="fl">送</span>
                    <em class="fl">{{ d.list[i].aim_site }}</em>
                    <i class="cd f14 fl">查看路线</i>
                </a>    
            </p>
        </div>
        <div class="Namelist p10 f14">
            <h2 class="f15 c3">{{ d.list[i].name }} <span class="c6"><a href="tel:{{ d.list[i].phone }}">{{ d.list[i].phone }}</a></span></h2>
            <p class="c9">期望送达：{{ d.list[i].appoint_time }}</p>
            <p class="red">应收现金：<i>{{ d.list[i].deliver_cash }}</i>元</p>
            <p class="red">配送距离{{ d.list[i].distance }}公里，配送费{{ d.list[i].freight_charge }}元</p>
			{{# if(d.list[i].get_type == 2){ }}
			<div class="Order">订单来源于{{ d.list[i].change_name }}配送员</div>
			{{# } }}
        </div>
    {{# } }}
        <div class="sign_bottom">
            <a href="javascript:;" class="del" data-id="{{ d.list[i].supply_id }}">删除</a>
        </div>
	<div class="mask hidden img_close">
		<div class="add">
			<div class="section">
				<img class="img_close" src="" alt="" style="width: 100%;">
			</div>
		</div>
	</div>
</section>
{{# } }}
</script>
</section>

<script>

$('body').off('click','.img_click').on('click','.img_click',function(e){
      e.stopPropagation();
      var sic=$(this).prop('src');
      $('.mask').removeClass('hidden');
      $('.mask img').prop('src',sic);
      $('.img_close').click(function(e){
          $('.mask').addClass('hidden');
      });
});
</script>
<include file="menu"/>
</body>
</html>