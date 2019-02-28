<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{$config.shop_alias_name}订单列表</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <style>
		dl.list dd dl{ padding-left:0;}
		.dealcard-img{ margin-left:.2rem}
		dl.list dd{ border:none;}
	    dl.list dd.dealcard {
	        overflow: visible;
	        -webkit-transition: -webkit-transform .2s;
	        position: relative;
			background:rgba(242, 242, 242, 0.86);
	    }
	    .dealcard.orders-del {
	        -webkit-transform: translateX(1.05rem);
	    }
	    .dealcard-block-right {
	        height: 1.5rem;
	    }
	    .dealcard .dealcard-brand {
			margin-top:.18rem;
	        margin-bottom: .18rem;
			heigth:.5rem
	    }
	    .dealcard small {
	        font-size: .24rem;
	        color: #9E9E9E;
	    }
	    .dealcard weak {
	        font-size: .24rem;
	        color: #999;
	        position: absolute;
	        bottom: .15rem;
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
	    .orderindex li {
	        display: inline-block;
	        width: 25%;
	        text-align:center;
	        position: relative;
	    }

		.orderindex li.active {
	        color:#06c1bb
	    }

	    .orderindex li .react {
	        padding: .28rem 0;
	    }
	    .orderindex .text-icon {
	        display: block;
	        font-size: .4rem;
	        margin-bottom: .18rem;
	    }
	    .orderindex .amount-icon {
	        position: absolute;
	        left: 50%;
	        top: .16rem;
	        color: white;
	        background: #EC5330;
	        border-radius: 50%;
	        padding: .08rem .06rem;
	        min-width: .28rem;
	        font-size: .24rem;
	        margin-left: .1rem;
	        display: none;
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
	    .orderindex li .react.hover{
	    	color:#FF658E;
	    }
		.tabs {
		  z-index: 15;
		  position: relative;
		  background: #FFFFFF;
		  box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
		  box-sizing: border-box;
		  overflow: hidden;
		}
		.tabs-header {
		  position: relative;
		  overflow: hidden;
		}
		.tabs-header .border {
		  position: absolute;
		  bottom: 0;
		  left: 0;
		  background: #06c1bb;
		  width: auto;
		  height: 2px;
		  width:25%;
		  -webkit-transition: 0.3s ease;
				  transition: 0.3s ease;
		}

		.tabs-content .tab {
		  display: none;
		}
		.tabs-content .tab.active {
		  display: block;
		}

		.order-num{ height:1rem; line-height:1rem; color:#9E9E9E; padding-left:.2rem}
		.order-num a{ display:block; float:right; margin-right:.1rem}
		.order-num-foot span:first-child{display:block; float:left; color:#06c1bb; line-height:1rem}
		.order-num-foot span:last-child,.order-pay,.order-cancel{border:1px solid #e5e5e5;color:#666; padding:.2rem; border-radius:4px; display:block; float:right;line-height:.3rem; margin:.1rem .1rem 0 0; }
		.order-num-foot span.order-pay{ color:#06c1bb;padding:.2rem .5rem}

	</style>
</head>
<body id="index">
        <div id="tips" class="tips"></div>
		<dl class="list" style="margin-top:0px;">
		    <dd>
			<div class="tabs">
			<div class="tabs-header">
				<div class="border"></div>
				<ul class="orderindex">
					<li class="active"><a href="javascript:void(0)" tab-id="1" class="react">
						<span>全部</span>
					</a>
					</li><li data-status='-1'><a href="javascript:void(0)" tab-id="2" class="react ">
						<span>待付款</span>
					</a>
					</li><li data-status='1'><a href="javascript:void(0)" tab-id="3" class="react ">
						<span>未消费</span>
					</a>
					</li><li data-status='2'><a href="javascript:void(0)" tab-id="4" class="react " >
						<span>待评价</span>
					</a>
					</li>
				</ul>
				</div></div>
			</dd>
		</dl>
		<div style="margin-top:.2rem;">
		    <dl class="list tabs-content" id="orders">
				<div tab-id="1" class="tab active">

				<volist name="order_list" id="order">
					<dd>
						<dl>
							<dd class="order-num">订单编号：<span>{pigcms{$order.real_orderid}</span>
							<if condition="$order['paid'] eq 0">
							<a href="javascript:void(0)" onclick="del_order({pigcms{$order['order_id']})"><img src="{pigcms{$static_path}images/u282.png"></a>
							</if>
							<if condition="in_array($order['status'],array(2,3))">
							<a href="javascript:void(0)" onclick="del_order({pigcms{$order['order_id']})"><img src="{pigcms{$static_path}images/u282.png"></a>
							</if>
							</dd>
							<dd class="dealcard dd-padding goUrl" data-url="{pigcms{$order.order_url}">
								<div class="dealcard-img imgbox">
									<img src="{pigcms{$order.image}" style="width:100%;height:100%;"/>
								</div>
								<div class="dealcard-block-right">
									<div class="dealcard-brand single-line">{pigcms{$order.name}</div>
									<small>数量：{pigcms{$order.num}&nbsp;&nbsp;总价：{pigcms{$order['price']|floatval}  元</small>

								</div>
							</dd>
							<dd class="order-num order-num-foot">
								<if condition="$order['status'] eq 0">
									<span>未确认</span>
								<elseif condition="$order['status'] eq 1" />
									<span>已确认</span>
								<elseif condition="$order['status'] eq 2" />
									<span  href="{pigcms{:U('My/shop_feedback',array('order_id'=>$order['order_id']))}">去评价</span>
								<elseif condition="$order['status'] eq 3" />
									<span>已评价</span>
								<elseif condition="$order['status'] eq 4" />
									<span>已退款</span>
								<elseif condition="$order['status'] eq 5" />
									<span>已取消</span>
								<elseif condition="$order['status'] eq 6" />
								<elseif condition="$order['status'] eq 7" />
									<span>分配到自提点</span>
								<elseif condition="$order['status'] eq 8" />
									<span>发货到自提点</span>
								<elseif condition="$order['status'] eq 9" />
									<span>自提点接货</span>
								<elseif condition="$order['status'] eq 10" />
									<span>自提点发货</span>
								</if>

								<if condition="empty($order['paid']) AND ($order['status'] lt 2 OR $order['status'] eq 7 OR $order['status'] eq 9)" >
									<span class="order-pay" data-order_id="{pigcms{$order.order_id}">付款</span>
								<elseif condition="$order['status'] == 2"/>
									<span onclick="location.href='{pigcms{:U('My/shop_feedback',array('order_id'=>$order['order_id']))}'">评价</span>
								</if>
							</dd>
						</dl>
					</dd>
					<div style=" height:10px; background:#f0efed"></div>
				</volist>
				</div>
			<div tab-id="2" class="tab"></div>
			<div tab-id="3" class="tab"></div>
			<div tab-id="4" class="tab"></div>
		    </dl>
		</div>

		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" language="javascript">
			var activePos = $('.tabs-header .active').position();
			var url = "{pigcms{:U('ajax_shop_order_list')}"
			function changePos() {
				activePos = $('.tabs-header .active').position();
				$('.border').stop().css({
					left: activePos.left,
					width: $('.tabs-header .active').width()
				});
			}
			changePos();
			var tabHeight = $('.tab.active').height();
			function animateTabHeight() {
				tabHeight = $('.tab.active').height();
				$('.tabs-content').stop().css({ height: tabHeight + 'px' });
			}

			var tabItems = $('.tabs-header ul li');
			var tabCurrentItem = tabItems.filter('.active');
			$('.tabs-header a').on('click', function (e) {
				e.preventDefault();
				var tabId = $(this).attr('tab-id');

				$('.tabs-header a').stop().parent().removeClass('active');
				$(this).stop().parent().addClass('active');
				changePos();
				tabCurrentItem = tabItems.filter('.active');
				$('.tab').stop().fadeOut(300, function () {
					$(this).removeClass('active');
				}).hide();
				$('.tab[tab-id="' + tabId + '"]').stop().fadeIn(300, function () {
					var status = $('.tabs-header ul li.active').data('status');
					$.get(url,{'status':status,'store_id':{pigcms{$_GET.store_id|intval}},function(data){
						if(data.status){
							var shtml = '<dd>';
							var order_list = data['order_list'];
							for(var i in order_list){
								shtml += '<dl><dd class="order-num">订单编号：<span>'+order_list[i]["real_orderid"]+'</span>';
								if((order_list[i]['paid'] == 0)){
									shtml +='<a href="javascript:void(0)" onclick="del_order('+order_list[i]["order_id"]+')"><img src="{pigcms{$static_path}images/u282.png"></a>';
								}
								if((order_list[i]['status']==2) || (order_list[i]['status']==3)){
									shtml +='<a href="javascript:void(0)" onclick="del_order('+order_list[i]["order_id"]+')"><img src="{pigcms{$static_path}images/u282.png"></a>';
								}
								shtml += '</dd>';
								shtml += '<dd class="dealcard dd-padding goUrl" data-url="'+order_list[i]['order_url']+'">';
								shtml += '<div class="dealcard-img imgbox">';
								shtml += '<img src="'+order_list[i]['image']+'" style="width:100%;height:100%;"/>';
								shtml += '</div>';
								shtml += '<div class="dealcard-block-right">';
								shtml += '<div class="dealcard-brand single-line">'+order_list[i]['name']+'</div>';
								shtml += '<small>数量：'+order_list[i]['num']+'&nbsp;&nbsp;总价：'+order_list[i]['price']+' 元</small>';

								shtml += '</div></dd><dd class="order-num order-num-foot">	';

// 								var url = "{pigcms{:U('My/shop_order_refund')}";
// 								url +='&order_id='+order_list[i]['order_id']+'&mer_id='+order_list[i]['mer_id']+'&store_id='+order_list[i]['store_id'];


								if(order_list[i]['status']==0){
									shtml += '<span>未确认</span>';
								}else if(order_list[i]['status']==1){
									shtml += '<span>已确认</span>';
								}else if(order_list[i]['status']==2){
									var url = "{pigcms{:U('My/shop_feedback')}";
									url +='&order_id='+order_list[i]['order_id'];
									shtml +='<span onclick="location.href=\''+url+'\'">去评价</span>';
								}else if(order_list[i]['status']==3){
									shtml += '<span>已评价</span>';
								}else if(order_list[i]['status']==4){
									shtml += '<span>已退款</span>';
								}else if(order_list[i]['status']==5){
									shtml += '<span>已取消</span>';
								}else if(order_list[i]['status']==6){
								}else if(order_list[i]['status']==7){
									shtml += '<span>分配到自提点</span>';
								}else if(order_list[i]['status']==8){
									shtml += '<span>发货到自提点</span>';
								}else if(order_list[i]['status']==9){
									shtml += '<span>自提点接货</span>';
								}else if(order_list[i]['status']==10){
									shtml += '<span>自提点发货</span>';
								}


								 if(order_list[i]['paid'] == 0 && (order_list[i]['status'] < 2 || order_list[i]['status'] == 7)){
									 shtml +='<span data-order_id="'+order_list[i]['order_id']+'" class="order-pay">付款</span>';
								 }else if(order_list[i]['status'] == 2){
									 var url = "{pigcms{:U('My/shop_feedback')}";
									 url += '&order_id='+order_list[i]['order_id'];
									 shtml +='<span onclick="location.href=\''+url+'\'">评价</span>';
								 }else{
									 shtml+='<a></a>';
								 }
								shtml +='</dd></dl><div style=" height:10px; background:#f0efed"></div>';
							}
						}else{
							var shtml ='<dd><dd class="dealcard dd-padding" style=" text-align:center; background:#fff; width:100%">暂无订单</dd></dd>';
						}
						$('.tab[tab-id="' + tabId + '"]').html(shtml);
					},'json')
				});
			});


			function del_order(order_id){
				if(!order_id){
					return false;
				}
				layer.open({
					content:'确认删除？',
					btn: ['确定','取消'],
					yes:function(){
					   var del_url = "{pigcms{:U('ajax_shop_order_del')}";
						$.get(del_url,{'order_id':order_id},function(data){
							if(data['status']){
								location.reload();
							}
						},'json');
					}
				});
			}
		</script>
<!--include file="Public:footer"/-->
{pigcms{$hideScript}
<style>
	.goUrl,.order-pay{
		cursor:pointer;
	}
</style>
<script>
$("body").delegate(".goUrl","click",function(){
	var jpurl = $(this).data('url');
	if(!jpurl){
		return false;
	}
	if(typeof wx !=='undefined' && window.__wxjs_environment === 'miniprogram'){
		jpurl = jpurl.replace('http://','https://');
		if(jpurl.indexOf('https://') === -1){
			jpurl = 'https://'+location.host + jpurl;
		}

		var webview_title = '订单详情';
		wx.miniProgram.navigateTo({url:'/pages/webview/webview?webview_url='+encodeURIComponent(jpurl)+'&webview_title='+encodeURIComponent(webview_title)});
	}else{
		location.href = jpurl;
	}
});
$("body").delegate(".order-pay","click",function(){
	var order_id = $(this).data('order_id');
	if(typeof wx !=='undefined' && window.__wxjs_environment === 'miniprogram'){
		wx.miniProgram.navigateTo({url:'/pages/pay/index?order_id='+ order_id +'&type=shop'});
	}else{
		location.href = "{pigcms{:U('Pay/check',array('type'=>'shop'))}&order_id="+order_id;
	}
});
</script>
</body>
</html>