<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>推广奖励记录</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}fenrun/css/fenrun.css?215"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		
    <style>
	
	</style>
</head>

 <body>
        <section class="extension">
            <div class="top bw clr">
                <div class="li on fl">推广<if condition="$_GET['type'] eq 1">用户<elseif condition="$_GET['type'] eq 2" />商家</if>明细</div>
                <div class="li fr">解冻明细</div>
            </div>
            <div class="con">
                <ul style="display: none;" id="award">
					<volist name="award_list" id= "vo">
                    <li>
                        <h2>{pigcms{$vo.des},<if condition="$_GET['type'] eq 1">用户<elseif condition="$_GET['type'] eq 2" />商家</if>：{pigcms{$vo.spreadname} 电话：{pigcms{$vo.phone} |<i> ￥{pigcms{$vo.money}</i></h2>
                        <p>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</p>
                    </li>
					</volist>
                    
                </ul>
                <ul style="display: none;">
                    <volist name="free_list" id= "vv">
                    <li>
                        <h2>{pigcms{$vv.des}|<i> ￥{pigcms{$vv.money}</i></h2>
                        <p>{pigcms{$vv.add_time|date='Y-m-d H:i:s',###}</p>
                    </li>
					</volist>
                </ul>
            </div>
        </section>
        


        <script src="{pigcms{$static_path}fenrun/js/fenrun.js"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			$(".extension .con ul").css({"height":$(window).height()-114*per,"overflow-y":"auto","-webkit-overflow-scrolling":"touch"});
			$(".top .li").click(function(){
				var index=$(this).index();
				$(this).addClass("on").siblings(".li").removeClass("on");
				$(".con ul").eq(index).show().siblings("ul").hide();
			}).eq(0).trigger("click");
		</script>
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Home/index')}",
				"tTitle": "{pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.seo_description}"
			};
		</script>
    </body>

</html>