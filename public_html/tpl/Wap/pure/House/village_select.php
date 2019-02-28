<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <if condition="!$is_app_browser">
        <title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>请选择户号</title>
        </if>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village_list.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">
			var backUrl="{pigcms{:U('House/village_list')}"+'&choose=1';
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_select.js?210" charset="utf-8"></script>
		<style>#container{top:57px;}</style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>请选择户号</header>
    </if>
		<div id="container">
			<div id="scroller">
				<section class="villageBox" id="villageBox">
					<dl>
						<volist name="bind_village_list" id="vo">
							<dd class="link-url" data-url="{pigcms{:U('House/village_select',array('village_id'=>$now_village['village_id'],'bind_id'=>$vo['pigcms_id'],'referer'=>urlencode($referer)))}">
								<div class="brand">{pigcms{$vo.address}<if condition='$vo["is_allow"]'><span style="color:red; display:block; float:right">&nbsp;&nbsp;已绑定</span></if></div>
                                <if condition="$vo.type eq 4">
                                    <div class="title">工作编号：{pigcms{$vo.usernum}</div>
                                    <else/>
                                    <div class="title">物业编号：{pigcms{$vo.usernum}</div>
                                </if>

							</dd>
						</volist>
					</dl>
				</section>
			</div>
		</div>
		{pigcms{$hideScript}
	</body>
</html>