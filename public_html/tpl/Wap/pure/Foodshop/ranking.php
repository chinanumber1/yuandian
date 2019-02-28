<!DOCTYPE html>
<html style="">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<title>{pigcms{$foodshop['name']}-商品销量榜</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}foodshop/css/ranking.css" />
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
                <span></span>
            </p>
            <!--排行榜-->
            <if condition="!empty($goodsList)">
            <div class="rang_list">
                <ul>
                    <volist name="goodsList" id="val">
                    <a href="{pigcms{:U('Foodshop/goods_detail', array('goods_id' => $val['goods_id'], 'order_id' => $_GET['order_id']))}">
                       <li>
                            <div class="img_text">
                                <img src="{pigcms{$val['show_image']}" />
                                <dl>
                                    <dt>{pigcms{$val['name']}</dt>
                                    <!-- <dd>本店招牌</dd> -->
                                </dl>
                            </div>
                            <p class="volume">已售{pigcms{$val['sell_count']}{pigcms{$val['unit']}</p>
                            <div class="pai_dix">第 <span><span>{pigcms{$i}</span></span> 名</div>
                        </li> 
                    </a>
                    </volist>
                </ul>
            </div>
            <else />
            <p class="no_dishes">暂无内容</p>
            </if>
        </div>
    </div>
</body>
</html>