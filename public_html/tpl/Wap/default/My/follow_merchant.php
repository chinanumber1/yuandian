<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>我关注商家列表</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <style>
	    dl.list dd.dealcard {
	        overflow: visible;
	        -webkit-transition: -webkit-transform .2s;
	        position: relative;
	    }
	    .dealcard.orders-del {
	        -webkit-transform: translateX(1.05rem);
	    }
	    .dealcard-block-right {
	        height: 1.68rem;
	        position: relative;
	    }
	    .dealcard .dealcard-brand {
	        margin-bottom: .18rem;
	    }
	    .dealcard small {
	        font-size: .24rem;
	        color: #666;
	    }
	    .dealcard weak {
	        font-size: .24rem;
	        color: #999;
	        position: absolute;
	        bottom: 8px;
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
	</style>
</head>
<body id="index">
        <div id="tips" class="tips"></div>
		<if condition="$follow_list">
	    <div style="margin-top:.2rem;">
		    <dl class="list" id="orders">
		    	<dd>
		    		<dl>
		    			<volist name="follow_list" id="order">
							<dd class="dealcard dd-padding" onclick="window.location.href = '{pigcms{$order['url']}';">
					            <div class="dealcard-img imgbox" style="width: 2.8rem;">
					            	<img src="{pigcms{$order.img}" style="width:140px;height:84px;"/>
					            </div>
			                    <div class="dealcard-block-right" style="margin-left:3rem;">
			                        <div class="dealcard-brand single-line">{pigcms{$order['name']}</div>
			                        <small>粉丝数：{pigcms{$order['fans_count']}</small>
			                        <br/>
			                        <small>
			                        </small>
			                        <weak>
		                        		<a class="btn btn-weak color-gray" data-mer_id="{pigcms{$order['mer_id']}">取消关注</a>
			                        </weak>
			                    </div>
			                </dd>
				        </volist>
				    </dl>
		    	</dd>
		    </dl>
		</div>
		</if>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script type="text/javascript">
function drop_confirm(msg, url)
{
	if (confirm(msg)) {
		window.location.href = url;
	}
}
$(document).ready(function(){
	var flag = false;
	$('.btn').click(function(){
		if (flag) return false;
		if (!confirm('你确定要取消关注该商家吗？')) return false;
		var mer_id = $(this).attr('data-mer_id'), obj = $(this).parents('.dd-padding');
		flag = true;
		$.get("{pigcms{:U('My/cancel_follow')}", {'mer_id':mer_id}, function(response){
			if (response.status == 1) {
				obj.remove();
			} else {
				alert(response.info);
			}
			flag = false;
		}, 'json');
		return false;
	});
});
</script>

<include file="Public:footer"/>
{pigcms{$hideScript}
</body>
</html>