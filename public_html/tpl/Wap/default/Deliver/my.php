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
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
	<script src="{pigcms{$static_path}js/convertor.js"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
    <style>
	    dl.list dd.dealcard {
	        overflow: visible;
	        -webkit-transition: -webkit-transform .2s;
	        position: relative;
	    }
	    .dealcard.orders-del {
	        -webkit-transform: translateX(1.05rem);
	    }
	    #orders .dealcard-block-right {
			margin-left:1px;
	        position: relative;
	    }
	    .dealcard .dealcard-brand {
	        margin-bottom: .18rem;
	    }
	    .dealcard small {
	        font-size: .24rem;
	        color: #9E9E9E;
	    }
	    .dealcard weak {
	        font-size: .24rem;
	        color: #999;
	        position: absolute;
	        bottom: 0;
	        left: 0;
	        display: block;
	        width: 100%;
	    }
	    .dealcard weak b {
	        color: #FDB338;
	    }
	    .dealcard weak a.btn{
	        margin: -.15rem 0;
	    }
	    .dealcard weak b.dark {
	        color: #fa7251;
	    }
	    .hotel-price {
	        color: #ff8c00;
	        font-size: .24rem;
	        display: block;
	    }
	    .del-btn {
	        display: block;
	        width: .45rem;
	        height: .45rem;
	        text-align: center;
	        line-height: .45rem;
	        position: absolute;
	        left: -.85rem;
	        top: 50%;
	        background-color: #EC5330;
	        color: #fff;
	        -webkit-transform: translateY(-50%);
	        border-radius: 50%;
	        font-size: .4rem;
	    }
	    .no-order {
	        color: #D4D4D4;
	        text-align: center;
	        margin-top: 1rem;
	        margin-bottom: 2.5rem;
	    }
	    .icon-line {
	        font-size: 2rem;
	        margin-bottom: .2rem;
	    }

	    .order-icon {
	        display: inline-block;
	        width: .5rem;
	        height: .5rem;
	        text-align: center;
	        line-height: .5rem;
	        border-radius: .06rem;
	        color: white;
	        margin-right: .25rem;
	        margin-top: -.06rem;
	        margin-bottom: -.06rem;
	        background-color: #F5716E;
	        vertical-align: initial;
	        font-size: .3rem;
	    }
	    .order-all {
	        background-color: #2bb2a3;
	    }
	    .order-zuo,.order-jiudian {
	        background-color: #F5716E;
	    }
	    .order-fav {
	        background-color: #0092DE;
	    }
	    .order-card {
	        background-color: #EB2C00;
	    }
	    .order-lottery {
	        background-color: #F5B345;
	    }
	    .color-gray{
	    	color:gray;
	    	border-color:gray;
	    }
	    .color-gray:active{
	    	background-color:gray;
	    }
		#nav-dropdown{height: 1.7rem;}
		#filtercon select{height: 100%;line-height: normal;width:100%;}
		#filtercon{margin: 0 .15rem;}
.find_div {
margin: .15rem 0;
}
	#filtercon input{background-color: #fff;
		width: 100%;
		border: none;
		background: rgba(255, 255, 255, 0);
		outline-style: none;
		display: block;
		line-height: .28rem;
		height: 100%;
		font-size: .28rem;
		padding: 0
}
		#find_submit{
			position: absolute;
			right: 0rem;
			top: .15rem;
			width: 1.2rem;
			height: .7rem;;
			-webkit-box-sizing: border-box;
		}
 .dealcard-block-right li{
    font-size: .266rem;
font-weight: 400;
 }
.dealcard-block-right .dth{font-weight: bold;}
 .ulrightdiv{
	float: right;
	position: relative;
	top: -60px;
	margin-right: 15px;
	}
	dl.list .dd-padding{padding: .28rem 0.1rem;}
	.red{color:red;}
.top-btn-a a{color: #fff;margin-top: 10px;}
.top-btn-a .lb{margin-left: 20px;}
.top-btn-a .rb{float: right;margin-right: 20px;}
.dealcard-block-right{padding: 0 10px;}
#orders a{color: #333;}
#orders .td a{color: green;}
.find_type_div{
	position: absolute;
left: 0rem;
width: 1.7rem;
height: .7rem;
text-align: center;
background: white;
}
.find_txt_div{
vertical-align: middle;
position: relative;
margin-right: 1.3rem;
margin-left:1.8rem;
border-radius: .06rem;
border: 1px #CCC solid;
height: .7rem;
line-height: .7rem;
}
  .dealcard-block-right li.btm_li{
     margin-bottom: .18rem;
 }
 
 .deliver_list {
 	border-buttom: 0px solid #fff;
 	border-top:1px solid #e5e5e5;
 }
 .dealcard-block-right {
 	margin-buttom:10px;
 }
 .diver_list_get {
 	margin-top: 0.3rem;
 	width: 1.5rem;
 	float: right;
 	height: 1rem;
 	background: #EE3968;
 	font-size: 0.4rem;
 	text-align: center;
 	//border-radius: 50%;
 	margin-right:0.3rem;
 	cursor: pointer;
 }
 .diver_list_get em {
 	color: lightgoldenrodyellow;
 	font-style: normal;
 	line-height: 1rem;
 }

 
.navbar ul li a{color:#000;}
.navbar ul {
    position: fixed;
    z-index: 900;
    left: 0;
    right: 0;
    margin: auto;
    display: block;
    width: 100%;
    height: 51px;
    display: -webkit-box;
    display: box;
    -webkit-box-orient: horizontal;
    background-color: #FAFAFC;
    box-shadow: 0 0 2px 1px #ccc;
}
.navbar ul li {
    width: auto!important;
    height: 100%;
    position: static!important;
    margin: 20px;
    border-radius: 0!important;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    -webkit-box-flex: 1;
    box-flex: 1;
    -webkit-box-sizing: border-box;
    box-shadow: none!important;
    background: none;
}
</style>
</head>
<body>
		<header  class="navbar" style="background:#FAFAFC;border-bottom: 1px solid #FAFAFC;">
			<ul>
		        <li>
		            <a >
		            <p style="font-size: 10px;">今日抢单数(<b>{pigcms{$count}</b>)</p>
		            </a>
		        </li>
		        <li>
		            <a>
		            <p style="font-size: 10px;">在线支付(<b>{pigcms{$online_money}</b>)元</p>
		            </a>
		        </li>
		        <li>
		            <a>
		            <p style="font-size: 10px;">货到付款(<b>{pigcms{$offline_money}</b>)元</p>
		            </a>
		        </li>
		    </ul>
        </header>
	    <div id="content">
	    	<if condition="$list">
	    	<volist name="list" id="vo">
		    <dl class="list supply_{pigcms{$vo.supply_id}" data-id="{pigcms{$vo.supply_id}">
				<dd class="dealcard dd-padding" style="border-bottom:none;border-top:1px solid #ccc;">
					<div>
						<ul class="dealcard-block-right" style="width:3.8rem; float:left; margin-bottom:0.3rem; margin-left:0">
							<li class="btm_li">
								<span class="dth">订单编号：</span>
								<span class="ttd">{pigcms{$vo['real_orderid']}</span>
							</li>
							<li class="btm_li">
								<span class="dth">店铺名称：</span>
								<span class="ttd">{pigcms{$vo['store_name']}</span>
							</li>
							<li class="btm_li">
								<span class="dth">日期：</span>
								<span class="ttd">{pigcms{$vo['create_time']}</span>
							</li>
							<li class="btm_li">
								<span class="dth">顾客姓名：</span>
								<span class="ttd">{pigcms{$vo['name']}</span>
							</li>
							<li class="btm_li"><span class="dth">顾客电话：</span><span class="td"><a  href="tel:{pigcms{$vo.phone}" onclick="stopPropagation()">{pigcms{$vo['phone']}</a></span></li>
							<li class="btm_li"><span class="dth">取货地址：</span>
							<span style="color: red">{pigcms{$vo['from_site']}</span></li>
							<li><span class="dth">收货地址：</span>
							<span style="color: red">{pigcms{$vo['aim_site']}</span></li>
							<li><span class="dth">支付方式：</span>
							<span style="color: green">{pigcms{$vo['pay_method']}</span></li>
							<li><span class="dth">应收取现金：</span>
							<span style="color: red">{pigcms{$vo['deliver_cash']}</span></li>
							<li>
								<span class="dth">预定时间：</span>
								<span class="ttd">{pigcms{$vo['appoint_time']}</span>
							</li>
							<li>
								<span class="dth">客户备注信息：</span>
								<span class="ttd">{pigcms{$vo['note']}</span>
							</li>
							<if condition="$vo['end_time']">
							<li><span class="dth">送达时间：</span>{pigcms{$vo['end_time']|date="Y-m-d H:i:s",###}</li>
							</if>
						</ul>
						<if condition="$vo.status eq 5">
						<span class="diver_list_get delete" style="background:#efefef;" data-id="{pigcms{$vo.supply_id}">
							<em style="color:#bbb">删记录</em>
						</span>
						<else/>
						<span class="diver_list_get grab" data-id="{pigcms{$vo.supply_id}">
							<em>已送达</em>
						</span>
						</if>
					</div>
				</dd>
				<div style="clear:both"></div>
			</dl>
			</volist>
			<else/>
			<dl class="list supply_{pigcms{$vo.supply_id}" data-id="{pigcms{$vo.supply_id}">
				<dd class="dealcard dd-padding" style="border-bottom:none;border-top:1px solid #ccc;text-align:center;">
					暂无需要配送的订单，快去抢单吧~
				</dd>
				<div style="clear:both"></div>
			</dl>
			</if>
		</div>
		<include file="Deliver:footer"/>
		<script>
			$(function(){
				var DeliverListUrl = "{pigcms{:U('Deliver/my')}";
				var mark = 0;
		
				function grab(e) {
					if (mark) {
						return false;
					}
					mark = 1;
					e.stopPropagation();
					var supply_id = $(this).attr("data-id");
					$.post(DeliverListUrl, "supply_id="+supply_id, function(json){
						mark = 0;
						if (json.status) {
							layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:'配送成功~',btn: ['确定'],end:function(){}});
						} else {
							layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:'系统出错~',btn: ['确定'],end:function(){}});
						}
						var url = location.href;
						location.href = url;
					});
				}

				function detail(e) {
					if (mark) {
						return false;
					}
					mark = 1;
					e.stopPropagation();
					var supply_id = $(this).attr("data-id");
					var DetailUrl = "{pigcms{:U('Wap/Deliver/detail', array('supply_id'=>'d%'))}";
					location.href = DetailUrl.replace(/d%/, supply_id);
				}

				$('.delete').click(function(e){
					e.stopPropagation();
					var supply_id = $(this).attr("data-id");
					layer.open({
					    content: '您是要删除该条已完成的配送记录吗，删除后就不再显示了，但是不影响您的接单统计？',
					    btn: ['确认', '取消'],
					    shadeClose: false,
					    yes: function(){
					    	layer.closeAll();
		 					$.post("{pigcms{:U('Deliver/del')}", {supply_id:supply_id}, function(json){
		 						if (json.status) {
		 							$('.supply_' + supply_id).hide();
		 						} else {
		 							layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:json.info,btn: ['确定'],end:function(){}});
		 						}
		 					}, 'json');
					    }, no: function(){
					        layer.open({content: '你选择了取消', time: 1});
					    }
					});
				});
				$(".grab").bind("click", grab);
				$(".list").bind("click", detail);
			});
		</script>
</body>
</html>