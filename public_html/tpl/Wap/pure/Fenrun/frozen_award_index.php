<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>冻结奖励记录</title>
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
        <section class="wallet">
            <div class="operation frozen">
                <div class="name">冻结佣金（元）</div>
                <div class="num">{pigcms{$now_user.frozen_award_money|floatval}</div>
            </div>
            <div class="record">
                <div class="h2">冻结佣金记录</div>

                <dl>
                    <dd>
                        <div class="top clr">
                            <div class="fl yh"></div>
                            <a href="{pigcms{:U('Fenrun/award_list')}&type=1" class="fr more">查看明细</a> 
                        </div>
                        <div class="con">
                            <div class="clr recommend">
                                <div class="fl w245">
                                    <h2>{pigcms{$return.user_count}</h2>
                                    <p>推荐用户总数</p>
                                </div>
                                <div class="fl">
                                    <h2>{pigcms{$return.user_award_total|floatval}</h2>
                                    <p>推荐用户总佣金</p>
                                </div>
                            </div>
                            <div class="return">已解冻至可用总佣金<i>{pigcms{$return.user_free_total|floatval}</i></div>
                        </div>
                    </dd>
                   
                    <dd>
                        <div class="top clr">
                            <div class="fl sj"></div>
                            <a href="{pigcms{:U('Fenrun/award_list')}&type=2" class="fr more">查看明细</a> 
                        </div>
                        <div class="con">
                            <div class="clr recommend">
                                <div class="fl w245">
                                    <h2>{pigcms{$return.mer_count}</h2>
                                    <p>推荐商家总数</p>
                                </div>
                                <div class="fl">
                                    <h2>{pigcms{$return.mer_award_total|floatval}</h2>
                                    <p>推荐商家总佣金</p>
                                </div>
                            </div>
                            <div class="return">已解冻至可用总佣金<i>{pigcms{$return.mer_free_total|floatval}</i></div>
                        </div>
                    </dd>
                </dl>

                <!-- <div class="no_img">
                    <img src="images/wu_07.jpg">
                    <p>您当前还没有记录哟~</p>
                </div> -->
            </div>
        </section>
        


        <script src="{pigcms{$static_path}fenrun/js/fenrun.js"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		
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