<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>评价列表</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/detail.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/laytpl.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/feedback.js?210" charset="utf-8"></script>
		<style>.introList{border-top:0px;}.introList.list{margin-bottom:0;}</style>
		<script type="text/javascript">
			var totalPage = {pigcms{$total},ajaxUrl = "<if condition="isset($_GET['order_type'])">{pigcms{:U('Group/ajaxFeedback',array('order_type'=>$_GET['order_type']))}<else />{pigcms{:U('Group/ajaxFeedback',array('group_id'=>$now_group['group_id']))}</if>";
		</script>
	</head>
	<body>
	<php>$no_footer = true;</php>
	<include file="Public:footer"/>
		<if condition="!empty($list) AND !isset($_GET['order_type'])">
			<section class="comment introList">
				<div class="titleDiv"><div class="title">评价<div class="rateInfo"><div class="starIconBg"><div class="starIcon" style="width:{pigcms{$now_group['score_mean']*20}%;"></div></div><div class="starText">{pigcms{$now_group.score_mean}</div></div><div class="right">{pigcms{$now_group.reply_count} 人评论</div></div></div>
			</section>
		</if>
		<div id="container">
			<div id="scroller">
				<if condition="!empty($list)">
					<div id="pullDown">
						<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
					</div>
					<section class="comment introList list">
						<dl>
							<volist name="list" id="vo">
								<dd>
									<div class="titleBar">
										<div class="nickname">{pigcms{$vo.nickname}</div><div class="dateline">{pigcms{$vo.add_time}</div><div class="rateInfo"><div class="starIconBg"><div class="starIcon" style="width:{pigcms{$vo['score']*20}%;"></div></div></div>
									</div>
									<div class="replyCon">
										<div class="textDiv">
											<div class="text">{pigcms{$vo.comment}</div>
										</div>
										<if condition="$vo['pics']">
											<ul class="imgList" data-pics="<volist name="vo['pics']" id="voo">{pigcms{$voo.m_image}<if condition="count($vo['pics']) gt $i">,</if></volist>">
												<volist name="vo['pics']" id="voo">
													<li><img src="{pigcms{$voo.s_image}"/></li>
												</volist>
											</ul>
										</if>
										<if condition="$vo['merchant_reply_content']">
										<div class="textDiv">
											<div class="text" style=" font-size: 12px;color: #C6895A;">商家回复：{pigcms{$vo.merchant_reply_content}</div>
										</div>
										</if>
									</div>
								</dd>
							</volist>
						</dl>
					</section>
					<if condition="$total gt 1">
						<div id="pullUp">
							<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
						</div>
					</if>
				</if>
				<script id="feedbackListBoxTpl" type="text/html">
					{{# for(var i = 0, len = d.list.length; i < len; i++){ }}
						<dd>
							<div class="titleBar">
								<div class="nickname">{{ d.list[i].nickname }}</div><div class="dateline">{{ d.list[i].add_time }}</div><div class="rateInfo"><div class="starIconBg"><div class="starIcon" style="width:{{ d.list[i].score*20 }}%;"></div></div></div>
							</div>
							<div class="replyCon">
								<div class="textDiv">
									<div class="text">{{ d.list[i].comment }}</div>
								</div>
								{{# if(d.list[i].pics){ }}
									<ul class="imgList" data-pics="{{# for(var j = 0, jlen = d.list[i].pics.length; j < jlen; j++){ }}{{ d.list[i].pics[j].s_image }}{{# if(jlen > i+1){ }},{{# } }}{{# } }}">
										{{# for(var j = 0, jlen = d.list[i].pics.length; j < jlen; j++){ }}
											<li><img src="{{ d.list[i].pics[j].s_image }}"/></li>
										{{# } }}
									</ul>
								{{# } }}
								{{# if(d.list[i].merchant_reply_content){ }}
								<div class="textDiv">
									<div class="text" style=" font-size: 12px;color: #C6895A;">商家回复：{{ d.list[i].merchant_reply_content }}</div>
								</div>
								{{# } }}
							</div>
						</dd>
					{{# } }}
				</script>
			</div>
		</div>
		<script type="text/javascript">
		window.shareData = {
					"moduleName":"Group",
					"moduleID":"0",
					"imgUrl": "",
					"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Group/feedback', array('group_id' => $now_group['group_id']))}",
					"tTitle": "【{pigcms{$now_group.merchant_name}】评价列表",
					"tContent": ""
		};
		</script>
		{pigcms{$shareScript}
	</body>
</html>