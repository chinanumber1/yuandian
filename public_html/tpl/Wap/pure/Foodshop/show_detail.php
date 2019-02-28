<!DOCTYPE html>
<html style="font-size：20px;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>{pigcms{$goods['name']}</title>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}foodshop/css/details.css" />
    <script src="js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
    	!function(e,t){function n(){var n=l.getBoundingClientRect().width;t=t||540,n>t&&(n=t);var i=100*n/e;r.innerHTML="html{font-size:"+i+"px;}"}var i,d=document,o=window,l=d.documentElement,r=document.createElement("style");if(l.firstElementChild)l.firstElementChild.appendChild(r);else{var a=d.createElement("div");a.appendChild(r),d.write(a.innerHTML),a=null}n(),o.addEventListener("resize",function(){clearTimeout(i),i=setTimeout(n,300)},!1),o.addEventListener("pageshow",function(e){e.persisted&&(clearTimeout(i),i=setTimeout(n,300))},!1),"complete"===d.readyState?d.body.style.fontSize="16px":d.addEventListener("DOMContentLoaded",function(e){d.body.style.fontSize="16px"},!1)}(640,640);
    </script>
</head>
<body>
	<header class="after">
		<a class="ft" href="javascript:history.back();"></a>
	</header>
	<div class="contant">
        <div class="img">
            <img src="{pigcms{$goods['pic_arr'][0]['url']['image']}" />
            <div class="price">
                <div class="price_left">
                    <p>{pigcms{$goods['name']}</p>
                    <div><span>￥</span><span id="show_price">{pigcms{$goods['price']|floatval}</span>/{pigcms{$goods['unit']}</div>
                </div>
                <if condition="!empty($goods['label'])">
                <div class="price_right">
                    <span>{pigcms{$goods['label']}</span>
                </div>
                </if>
            </div>
        </div>
        <volist name="goods['spec_list']" id="spec">
        <div class="size sku" data-id="{pigcms{$spec['id']}" data-num="1" data-name="{pigcms{$spec['name']}" data-type="spec">
            <p>{pigcms{$spec['name']}:</p>
            <div class="size_list">
                <volist name="spec['list']" id="val">
                <button class="big" data-id="{pigcms{$val['id']}" data-name="{pigcms{$val['name']}" data-goods_id="{pigcms{$goods['goods_id']}">{pigcms{$val['name']}</button>
                </volist>
            </div>
        </div>
        </volist>
        <volist name="goods['properties_list']" id="prop">
        <div class="practice sku" data-id="{pigcms{$prop['id']}" data-num="{pigcms{$prop['num']}" data-name="{pigcms{$prop['name']}" data-type="properties">
            <p>{pigcms{$prop['name']}:</p>
            <div class="practice_list">
                <volist name="prop['val']" id="name">
                <button data-id="{pigcms{$prop['id']}" data-num="{pigcms{$prop['num']}" data-name="{pigcms{$name}">{pigcms{$name}</button>
                </volist>
            </div>
        </div>
        </volist>
        
        <div class="img_details">
            <p>商品详情</p>
            {pigcms{$goods['des']}
        </div>
	</div>
	<script type="text/javascript">
		//大小按钮点击
		$('body').off('click','.size button').on('click','.size button',function(e){
			$(this).addClass('active').siblings('button').removeClass('active');
		});
		//做法按钮点击
		$('body').off('click','.practice button').on('click','.practice button',function(e){
			if($(this).is('.active')){
				$(this).removeClass('active');
			}else{
				$(this).addClass('active');
			}
		});
		
	</script>
</body>
</html>