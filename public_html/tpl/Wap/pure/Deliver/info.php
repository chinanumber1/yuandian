<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>已完成的订单</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/report_location.js" charset="utf-8"></script>
</head>
<body>
    <section class="MyEx">
        <div class="MyEx_top">
				<if condition="$deliver_session['store_id']">
                <span class="bjt" style="background: url({pigcms{$store['image']}) center no-repeat; background-size: contain;"></span>  
                <else />
                <span class="bjt" style="background: url(<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>) center no-repeat; background-size: contain;"></span>  
                </if>
            <h2>{pigcms{$deliver_session['name']}</h2>
            <span class="sj"><if condition="$deliver_session['store_id']">商家配送员<else />系统配送员</if></span>
            <if condition="$deliver_session['store_id']">
            <span class="mc"> {pigcms{$store['name']}</span>
            </if>
        </div>
        <div class="MyEx_end">
            <ul>
                <li class="cfe">
                    <h2>{pigcms{$finish_total}</h2>
                    <p>累计完成数量</p>
                </li>
                <li class="c65">
                    <h2>{pigcms{$total}</h2>
                    <p>累计抢单数量</p>
                </li>
                <li class="c66">
                    <h2>{pigcms{$distance}</h2>
                    <p>累计公里数</p>
                </li>
            </ul> 
           
        </div>
        <a href="javascript:void(0);" class="Setup"></a>
    </section>
    <section class="bottom">
        <div class="bottom_n">
            <ul>
                <li class="Statistics fl">
                    <a href="{pigcms{:U('Deliver/tongji')}">统计</a>
                </li>
                <li class="home fl">
                      <a href="{pigcms{:U('Deliver/index')}">
                        <i></i>首页
                      </a>
                </li>
                 <li class="My Myon fl">
                    <a href="{pigcms{:U('Deliver/info')}">我的</a>
                </li>
            </ul>
        </div>
    </section>
<script>
$(document).ready(function(){
	$('.Setup').click(function(){
		layer.open({
			title:['提示：','background-color:#FF658E;color:#fff;'],
			content:'确定退出吗？',
			btn: ['是', '否'],
			shadeClose: false,
			yes: function(){
				window.parent.location = "{pigcms{:U('Deliver/logout')}";
			}
		});
	});
});
</script>
</body>
</html>
