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
		<script type="text/javascript">var root_url = '{pigcms{$config.site_url}/wap.php?c=Shop&a=', cartid = '{pigcms{$_GET["cartid"]}', store_id = '{pigcms{$store["store_id"]}';</script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}shop/js/sync.js?t=1" charset="utf-8"></script>
        <link href="{pigcms{$static_path}shop/css/sync.css?t=1" rel="stylesheet"/>
    </head>
    <body>
        <section class="many">
            <div class="many_top">
                <div class="h2" style="display:none;">
                    <span>添加订购人</span>
                </div>
                <p>如需商家分开打包或邀请好友多人点单请点击添加订购人；如果正在操作时返回上一页，则已选数据会被清空。</p>
            </div>
            <div class="many_end">
                <div class="tit">拼单列表</div>
                <ul id="spell"></ul>
            </div>
            <div class="many_bot">
                <div class="price totalPrice"></div>
                <div class="go" style="cursor: pointer;">去结算</div>
            </div>
            <div class="none_img" ><img src="{pigcms{$static_path}shop/images/none_03.jpg"></div>
        </section>
        <div class="add_to">
            <ul>
                <li class="li1 spell">
                    <h2>分袋打包</h2>
                    <p>让商家分开打包商品</p>
                </li>
                <li class="li2">
                    <h2>邀请微信好友点单</h2>
                    <p>发送微信链接，邀请好友一起点单</p>
                </li>
            </ul>
        </div>

        <div class="settlement go_settlement">
            <div class="set_bj"></div>
            <div class="con">
                <h2>确认结算</h2>
                <p>结算时，订单会被锁定，如需继续点单，需返回解锁订单</p>
                <div class="button clr">
                    <div class="fl cancel">取消</div>
                    <div class="fr indeed">确认</div>
                </div>
            </div>
        </div>

        <div class="locking">
            <div class="lock_n">
                <div class="lock_tab">
                    <div class="lock_bj"></div>
                    <p>点单已锁定</p>
                    <div class="unlock">解锁并继续点单</div>
                </div>
            </div>
        </div>

        <div class="settlement go_unlock">
            <div class="set_bj"></div>
            <div class="con">
                <h2>确认解锁</h2>
                <p>解锁后，你可以和好友继续一起点单</p>
                <div class="button clr">
                    <div class="fl cancel">否，去支付</div>
                    <div class="fr indeed">确认</div>
                </div>
            </div>
        </div>
        <div class="mask"></div>
        <div class="mask_white"></div>
		<div id="cover"></div>
		<div id="guide"><img src="{pigcms{$static_path}images/guide1.png"></div>
        
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
                    {{# if (d[i].status == 0) { }}
                    <div class="fr del" style="cursor: pointer;" data-index="{{ d[i].index }}">删除Ta</div>
                    {{# } }}
                    {{# if (d[i].from == 0 && d[i].status == 0) { }}
                    {{# if (d[i].data != null && d[i].data != '') { }}
                    <a class="fr repair" style="cursor: pointer;" data-href="{{ d[i].add_cart_url }}">修改商品</a>
                    {{# } else { }}
                    <a class="fr repair" style="cursor: pointer;" data-href="{{ d[i].add_cart_url }}">添加商品</a>
                    {{# } }}
                    {{# } }}
                </dd>
                {{# if (d[i].data != '' && d[i].data != null) { }}
                {{# for (var ii in d[i].data) { }}
                <dd class="list clr">
                    <div class="name fl">{{ d[i].data[ii].productName }}</div>
                    <div class="price fr">￥{{ d[i].data[ii].productPrice }}</div>
                    <div class="num fr">x{{ d[i].data[ii].count }}</div>
                </dd>
                {{# } }}
                {{# } else { }}
                <dd class="list clr">还没有添加商品</dd>
                {{# } }}
            </dl>
        </li>
        {{# } }}
    </script>
    <script type="text/javascript">
    window.shareData = {
    	"moduleName":"Shop",
    	"moduleID":"0",
    	"imgUrl": "{pigcms{$store['images']}", 
    	"sendFriendLink": "{pigcms{$share_url}",
    	"tTitle": "有人一起拼单么？",
    	"tContent": "我正在【{pigcms{$store['name']}】拼单，有一起的么？"
    };
    </script>
    {pigcms{$shareScript}
    </body>
</html>