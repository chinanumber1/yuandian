<!DOCTYPE html>
<html style="">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<title>{pigcms{$goods['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}foodshop/css/details.css" />
<script src="{pigcms{:C('JQUERY_FILE_190')}" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/foodshopmenudetail.js" charset="utf-8"></script>
<script>var store_id = '{pigcms{$order["store_id"]}', all_goods = '{pigcms{$all_goods}', cookie_index = 'foodshop_cart_{pigcms{$order["store_id"]}_order_{pigcms{$order["order_id"]}';
var open_extra_price = Number('{pigcms{$config.open_extra_price}'), stockNum = -1;
</script>
<script type="text/javascript">
    !function(e,t){function n(){var n=l.getBoundingClientRect().width;t=t||540,n>t&&(n=t);var i=100*n/e;r.innerHTML="html{font-size:"+i+"px;}"}var i,d=document,o=window,l=d.documentElement,r=document.createElement("style");if(l.firstElementChild)l.firstElementChild.appendChild(r);else{var a=d.createElement("div");a.appendChild(r),d.write(a.innerHTML),a=null}n(),o.addEventListener("resize",function(){clearTimeout(i),i=setTimeout(n,300)},!1),o.addEventListener("pageshow",function(e){e.persisted&&(clearTimeout(i),i=setTimeout(n,300))},!1),"complete"===d.readyState?d.body.style.fontSize="16px":d.addEventListener("DOMContentLoaded",function(e){d.body.style.fontSize="16px"},!1)}(640,640);
</script>
<style type="text/css">
.motifyShade{
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    bottom:0;
    padding: 0;
    z-index: 998;
    width: 100%;
}
.motify {
    display: none;
    position: fixed;
    top: 35%;
    left: 50%;
    width: 260px;
    padding: 0;
    margin: 0 0 0 -130px;
    z-index: 999;
    background: rgba(0, 0, 0, 0.8);
    color: #fff;
    font-size: 14px;
    line-height: 1.5em;
    border-radius: 6px;
    -webkit-box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
    box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
}
.motify .motify-inner {
    padding: 10px 10px;
    text-align: center;
    word-wrap: break-word;
}
</style>
</head>
<body>
    <header class="after">
        <a class="ft" href="{pigcms{:U('Foodshop/menu', array('order_id' => $order['real_orderid'], 'store_id' => $order['store_id']))}"></a>
    </header>
    <div class="contant">
        <div class="img">
            <img src="{pigcms{$goods['image']}" />
            <div class="price">
                <div class="price_left">
                    <p>{pigcms{$goods['name']}</p>
                    <div><span>￥</span><span id="show_price">{pigcms{$goods['price']|floatval}</span>/份</div>
                </div>
            </div>
        </div>
        <!--筛选菜品  -->
        <if condition="$goods['goods_detail']">
        <volist name="goods['goods_detail']" id="row">
        <div class="changeFood" data-num="{pigcms{$row['num']}">
            <p>{pigcms{:count($row['goods_list'])}选<span class="changes">{pigcms{$row['num']}</span></p>
            <ul>
                <volist name="row['goods_list']" id="gd">
                <li class="check " style="cursor: pointer;" data-unit="{pigcms{$gd['unit']}" data-price="{pigcms{$gd['price']|floatval}" data-name="{pigcms{$gd['name']}" data-goods_id="{pigcms{$gd['goods_id']}"><span>{pigcms{$gd['name']}</span> <span>1{pigcms{$gd['unit']}</span> <span>￥{pigcms{$gd['price']|floatval}</span> <span ><i></i></span></li>
                </volist>
            </ul>
        </div>
        </volist>
        </if>
        <div class="img_details">
            <p>使用说明</p>
            <div>{pigcms{$goods['note']}</div>
        </div>

        <div style="padding-bottom: 60px;"></div>
        <div class="bottom_length">
            <div>数量 :
                <p>
                    <i class="less"></i>
                    <input type="tel" value="1" id="num"/>
                    <b class="add"></b>
                </p>
            </div>
            <a href="javascript:void(0)" data-type="group" data-id="{pigcms{$goods['id']}" data-name="{pigcms{$goods['name']}" data-price="{pigcms{$goods['price']|floatval}" data-href="{pigcms{:U('Foodshop/menu', array('order_id' => $order['real_orderid'], 'store_id' => $order['store_id']))}" class="addCart">加入购物车</a>
        </div>
    </div>
</body>
</html>