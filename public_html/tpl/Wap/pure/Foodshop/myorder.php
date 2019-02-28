<!DOCTYPE html>
<html style="">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<title>{pigcms{$foodshop['name']}-已点过的菜</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}foodshop/css/already.css" />
<script src="{pigcms{:C('JQUERY_FILE_190')}" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
!function(e,t){function n(){var n=l.getBoundingClientRect().width;t=t||540,n>t&&(n=t);var i=100*n/e;r.innerHTML="html{font-size:"+i+"px;}"}var i,d=document,o=window,l=d.documentElement,r=document.createElement("style");if(l.firstElementChild)l.firstElementChild.appendChild(r);else{var a=d.createElement("div");a.appendChild(r),d.write(a.innerHTML),a=null}n(),o.addEventListener("resize",function(){clearTimeout(i),i=setTimeout(n,300)},!1),o.addEventListener("pageshow",function(e){e.persisted&&(clearTimeout(i),i=setTimeout(n,300))},!1),"complete"===d.readyState?d.body.style.fontSize="16px":d.addEventListener("DOMContentLoaded",function(e){d.body.style.fontSize="16px"},!1)}(640,640);
</script>
</head>
<body>
    <div class="bg_contanir">
        <div class="content">
            <header>
                <a href="javascript:history.back();"></a>
            </header>
            <p>
                <span>
                    <i>点过的菜</i>
                </span>
            </p>
            <if condition="$orders">
            <div class="rang_list">
                <ul>
                    <volist name="orders" id="order">
                    <a href="{pigcms{:U('Foodshop/goods_detail', array('goods_id' => $order['goods_id'], 'order_id' => $order['real_orderid']))}">
                         <li>
                            <div class="img_text">
                                <img src="{pigcms{$order['show_image']}" />
                                <dl>
                                    <dt>{pigcms{$order['name']}</dt>
                                    <dd>已点{pigcms{$order['num']|floatval}{pigcms{$order['unit']}</dd>
                                </dl>
                            </div>
                        </li>
                    </a>
                   
                    </volist>
                </ul>
            </div>
            <else />
            <p class="no_dishes">您暂未点过菜品，去下单试试吧！</p>
            </if>
        </div>
    </div>
</body>
</html>