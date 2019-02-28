<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <title>任务列表</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
    	<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">var post_url = "{pigcms{:U('Customer/do_work')}";</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/customer.js?211" charset="utf-8"></script>
		<style>
			.village_my nav.order_list section p{padding-left:0px;}
		    .orderindex li {
		        display: inline-block;
		        width: 25%;
		        text-align:center;
		        position: relative;
		    }
			
			.orderindex li.active {
		        color:#06c1bb
		    }
			
		    .orderindex li .react {
		        padding: .28rem 0;
		    }
		    .orderindex .text-icon {
		        display: block;
		        font-size: .4rem;
		        margin-bottom: .18rem;
		    }
		    .work {
			    border: 1px solid #e5e5e5;
			    color: #666;
			    border-radius: 4px;
			    display: block;
			    line-height: .3rem !important;
			    margin: .01rem .01rem 0 0;
		        color: #06c1bb;
		        float:right;
   				padding: .2rem .5rem;
			}
			.list_title{
				min-height: 78px;
			}
			.order-num{ height:1rem; line-height:1rem; color:#9E9E9E; padding-left:.2rem}
			.border {
			    position: absolute;
			    bottom: 0;
			    left: 0;
			    background: #06c1bb;
			    width: auto;
			    height: 2px;
			    width: 25%;
			    -webkit-transition: 0.3s ease;
			    transition: 0.3s ease;
			}
		</style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide">任务列表</header>
    </if>

		<div id="container">
			<div id="scroller">
				<div style="text-align:center;background-color:#fff;padding:10px 0;">
					<button id="untreated" style="font-size:16px;width:45%;background-color:#00c4ac;border:1px solid #00c4ac;padding:8px;color:#fff;margin-right:-4px;z-index:100;-moz-border-radius:6px 0 0 6px;-webkit-border-radius:6px 0 0 6px;border-radius:6px 0 0 6px;">未处理</button>
				
					<button id="processed" style="font-size:16px;width:45%;background-color:#fff;border:1px solid #00c4ac;padding:8px;color:#00c4ac;margin-left:-4px;-moz-border-radius:0 6px 6px 0;-webkit-border-radius:0 6px 6px 0;border-radius:0 6px 6px 0;">已处理</button>
				</div>
			<div class="village_my">
				<nav class="order_list">
					<!--volist name="repair_list" id="vo">
						<section class="link-url" data-url="{pigcms{:U('Worker/detail',array('pigcms_id' => $vo['pigcms_id']))}">
							<div class="list_title">
							<if condition="$vo['status'] lt 2">
							<div class="work do_work" style="float:right" data-id="{pigcms{$vo['pigcms_id']}">接任务</div>
							<elseif condition="$vo['status'] eq 2" />
							<div class="work" style="float:right">去处理</div>
							</if>
							<p class="order-num">{pigcms{$vo.content|msubstr=###,0,20}</p>
							</div>
							<p class="money">
							<if condition="$vo['status'] lt 2">
							<font color="red">未接任务</font>
							<elseif condition="$vo['status'] eq 2" />
							<font color="red">未处理</font>
							<elseif condition="$vo['status'] gt 2" />
							<font color="green">已处理</font>
							</if>
							<em>{pigcms{$vo.time|date='Y-m-d H:i',###}</em></p>
						</section>
					</volist-->
				</nav>
                <if condition="!$is_app_browser">
                    <div id="pullUp" style="bottom:-60px;">
                        <img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
                    </div>
                </if>
			</div>
			</div>
		</div>
		<script id="indexRecommendBoxTpl" type="text/html">


		{{# for(var i = 0, len = d.length; i < len; i++){ }}
			<section class="link-url" data-url="{{ d[i].url }}">
			<div class="list_title">
				{{# if(d[i].status == 0) { }}
				<div class="work" style="float:right">去处理</div>
				{{# } }}
				<p class="order-num">{{ d[i].content }}</p>
			</div>
			<p class="money">
			{{# if(d[i].status == 0) { }}
			<font color="red">未指派</font>
			{{# }else if(d[i].status == 1) { }}
			<font color="red">已指派</font>
			{{# }else if(d[i].status == 2) { }}
			<font color="red">已受理</font>
			{{# }else if(d[i].status > 2) { }}
			<font color="green">已处理</font>
			{{# } }}
			<em>{{ d[i].time }}</em></p>
			</section>
		{{# } }}
		</script>
		{pigcms{$shareScript}
	</body>
</html>