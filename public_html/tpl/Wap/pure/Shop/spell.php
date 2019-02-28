<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>多人点单</title>
        <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name='apple-touch-fullscreen' content='yes'/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript">var root_url = '{pigcms{$config.site_url}/wap.php?c=Shop&a=', store_id = '{pigcms{$store_id}', cartid = '{pigcms{$cartid}', avatar = '{pigcms{$avatar}', openid = '{pigcms{$openid}', name='{pigcms{$nickname}', is_same = true;</script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}shop/js/spell.js" charset="utf-8"></script>
        <link href="{pigcms{$static_path}shop/css/sync.css" rel="stylesheet"/>
    </head>
    <body>
    <section class="many">
        <div class="many_top">
            <div class="title">
                <div class="img">
                    <img src="">
                </div>
                <div class="title_h2"></div>
                <div class="title_p">1号订购人邀请您一起点单</div><!-- 1号订购人付款成功，等待对方接单;  1号订购人付款成功，对方配送中; 1号订购人订单完成    -->
                <div class="choose" style="display:none">选购商品</div>
            </div>
        </div>
        <div class="many_end many_me">
            <ul></ul>
        </div>
        <div class="many_end allData">
            <div class="tit">拼单列表</div>
            <ul>
                <li>
                    <dl>
                        <dd class="h2 clr">
                            <div class="fl name">其他费用</div> 
                        </dd>
                        <dd class="list clr">
                            <div class="name fl">配送费</div>
                            <div class="price fr">￥11.5</div>
                            <div class="num fr">x1</div>
                        </dd>
                        <dd class="list consumption clr ">享受优惠费后,我一共消费:<i>￥100</i></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </section>
    
	<script id="myData" type="text/html">
        <li>
            <dl>
                <dd class="h2 clr">
                    {{# if (d.avatar != '') { }}
                    <i><img src="{{ d.avatar }}"></i>
                    {{# } else { }}
                    <i>{{ d.index }}</i>
                    {{# } }}
                    <div class="fl name">
                        {{ d.name }}
                        <div class="fame me">我</div>
                    </div>
                    {{# if (d.status == 0) { }}
                    <div class="fr del" style="cursor: pointer;">不订了</div>
                    <a class="fr repair" style="cursor: pointer;" href="{{ d.add_cart_url }}">修改商品</a>
                    {{# } }}
                </dd>
                {{# for (var i in d.data) { }}
                <dd class="list clr">
                    <div class="name fl">{{ d.data[i].productName }}</div>
                    <div class="price fr">￥{{ d.data[i].productPrice }}</div>
                    <div class="num fr">x{{ d.data[i].count }}</div>
                </dd>
                {{# } }}
            </dl>
        </li>
    </script>
	<script id="allData" type="text/html">
        {{# for (var i in d) { }}
        <li>
            <dl>
                <dd class="h2 clr">
                    {{# if (d[i].avatar != '') { }}
                    <i><img src="{{ d[i].avatar }}"></i>
                    {{# } else { }}
                    <i>{{ d[i].index }}</i>
                    {{# } }}
                    <div class="fl name">
                        {{ d[i].name }}
                        {{# if (d[i].index == 1) { }}
                        <div class="fame fq">发起</div>
                        {{# } else if (d[i].from == 1) { }}
                        <div class="fame wx">微信</div>
                        {{# } }}
                    </div>
                    {{# if (d[i].status == 0 && is_same) { }}
                    <div class="fr repair copyme" style="cursor: pointer;" data-index="{{ d[i].index }}">和ta点一样</div>
                    {{# } }}
                </dd>
                {{# for (var ii in d[i].data) { }}
                <dd class="list clr">
                    <div class="name fl">{{ d[i].data[ii].productName }}</div>
                    <div class="price fr">￥{{ d[i].data[ii].productPrice }}</div>
                    <div class="num fr">x{{ d[i].data[ii].count }}</div>
                </dd>
                {{# } }}
            </dl>
        </li>
        {{# } }}
    </script>
    </body>
</html>