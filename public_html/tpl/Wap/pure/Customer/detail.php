<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <title>任务详情</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/exif.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/imgUpload.js?210" charset="utf-8"></script>
		<script type="text/javascript">var post_url = "{pigcms{:U('Customer/do_work')}";</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/customer.js?210" charset="utf-8"></script>
		<style>
			section{margin-top:10px;padding:0 10px;}
			.header{margin-bottom:5px;font-size:16px;}
			.upload_list{background-color:white;}
			.upload_list img{width:100%;height:100%;}
			.gray{color:gray}
		</style>
		<script type="text/javascript">
			$(function(){
				$('.upload_list img').height($('.upload_list li:first').width()).width($('.upload_list li:first').width());
			});
		</script>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn" onclick="location.href='{pigcms{:U('Customer/index')}'"></div>{pigcms{$title}任务详情</header>
    </if>
		<div id="container">
			<div id="scroller" >
				<section>
					<span class="header">业主名称：</span>
					<span class="gray">{pigcms{$now_user_info.name}</span>
				</section>
				<section>
					<span class="header">业主电话：</span>
					<span class="gray"><a href="tel:{pigcms{$now_user_info.phone}">{pigcms{$now_user_info.phone}</a></span>
				</section>
				<section>
					<span class="header">业主地址：</span>
					<span class="gray">{pigcms{$now_user_info.address}</span>
				</section>
				<section>
					<p class="header">内容：<font class="gray" style="font-size:12px;">{pigcms{$repair_detail.time|date='Y-m-d H:i',###}</font></p>
					<p class="gray">{pigcms{$repair_detail.content}</p>
				</section>
				<if condition="$repair_detail['pic']">
					<section style="padding: 0">
						<p class="header" style="padding: 0 10px">图片：</p>
						<p>
							<ul class="upload_list clearfix">
								<volist name="repair_detail['picArr']" id="vo">
									<li class="upload_item">
										<img src="{pigcms{$config.site_url}/upload/house/{pigcms{$vo}"/>
									</li>
								</volist>
							</ul>
						</p>
					</section>
				</if>
				
				<if condition="$repair_detail['status'] eq 0">
					<form id="repair_form" onsubmit="return false;">
					<input type="hidden" name="pigcms_id" value="{pigcms{$repair_detail['pigcms_id']}" />
					<section>
						<span class="header">指派给：
						<select name="worker_id">
						<volist name="workers" id="worker">
						<option value="{pigcms{$worker['wid']}">{pigcms{$worker['name']}</option>
						</volist>
						</select>
						</span>
					</section>
					</form>
					<div class="area_btn"><input type="button" id="submit_btn" value="确定"></input>
				<elseif condition='$repair_detail["status"] eq 2' />
					<if condition="$repair_detail['msg']">
					<section>
						<p class="header">接单留言：</p>
						<p class="gray">{pigcms{$repair_detail.msg}</p>
					</section>
					</if>
					<if condition="$worker">
					<section>
						<p class="header">受理的人员：<font color="green">{pigcms{$worker['name']}</font>，电话<a href="tel:{pigcms{$worker['phone']}">{pigcms{$worker['phone']}</font></p>
						<p class="header">受理时间：<font color="gray" style="font-size:12px;">{pigcms{$repair_detail.status_time_2|date='Y-m-d H:i',###}</font></p>
					</section>
					</if>
				<elseif condition='$repair_detail["status"] eq 3' />
					<if condition="$repair_detail['msg']">
					<section>
						<p class="header">接单时间：<font color="gray" style="font-size:12px;">{pigcms{$repair_detail.status_time_2|date='Y-m-d H:i',###}</font></p>
						<p class="header">接单留言：</p>
						<p class="gray">{pigcms{$repair_detail.msg}</p>
					</section>
					</if>
					<if condition="$worker">
					<section>
						<p class="header">处理的人员：<font color="green">{pigcms{$worker['name']}</font>，电话<a href="tel:{pigcms{$worker['phone']}">{pigcms{$worker['phone']}</font></p>
						<p class="header">处理时间：<font color="gray" style="font-size:12px;">{pigcms{$repair_detail.reply_time|date='Y-m-d H:i',###}</font></p>
					</section>
					</if>
					<section>
						<p class="header">处理意见：</p>
						<p class="gray">{pigcms{$repair_detail.reply_content}</p>
					</section>
					<if condition="$repair_detail['reply_pic']">
						<section>
							<p class="header">处理图片：</p>
							<p>
								<ul class="upload_list clearfix">
									<volist name="repair_detail['reply_picArr']" id="vo">
										<li class="upload_item">
											<img src="{pigcms{$config.site_url}/upload/worker/{pigcms{$vo}"/>
										</li>
									</volist>
								</ul>
							</p>
						</section>
					</if>
				<elseif condition='$repair_detail["status"] eq 4' />
					<if condition="$repair_detail['msg']">
					<section>
						<p class="header">接单时间：<font color="gray" style="font-size:12px;">{pigcms{$repair_detail.status_time_2|date='Y-m-d H:i',###}</font></p>
						<p class="header">接单留言：</p>
						<p class="gray">{pigcms{$repair_detail.msg}</p>
					</section>
					</if>
						<if condition="$worker">
						<section>
							<p class="header">处理的人员：<font color="green">{pigcms{$worker['name']}</font>，电话<a href="tel:{pigcms{$worker['phone']}">{pigcms{$worker['phone']}</font></p>
							<p class="header">处理时间：<font color="gray" style="font-size:12px;">{pigcms{$repair_detail.reply_time|date='Y-m-d H:i',###}</font></p>
						</section>
						</if>
						<section>
							<p class="header">处理意见：</p>
							<p>{pigcms{$repair_detail.reply_content}</p>
						</section>
						<if condition="$repair_detail['reply_pic']">
							<section>
								<p class="header">处理图片：</p>
								<p>
									<ul class="upload_list clearfix">
										<volist name="repair_detail['reply_picArr']" id="vo">
											<li class="upload_item">
												<img src="{pigcms{$config.site_url}/upload/worker/{pigcms{$vo}"/>
											</li>
										</volist>
									</ul>
								</p>
							</section>
						</if>
						<section>
							<p class="header">评分：<font color="red" style="font-size:12px;">{pigcms{$repair_detail.score}</font></p>
							<p class="header">评论时间：<font color="gray" style="font-size:12px;">{pigcms{$repair_detail.comment_time|date='Y-m-d H:i',###}</font></p>
							<p class="header">评论内容：</p>
							<p class="gray">{pigcms{$repair_detail.comment}</p>
						</section>
						<if condition="$repair_detail['comment_pic']">
							<section>
								<p class="header">评论图片：</p>
								<p>
									<ul class="upload_list clearfix">
										<volist name="repair_detail['comment_picArr']" id="vo">
											<li class="upload_item">
												<img src="{pigcms{$config.site_url}/upload/house/{pigcms{$vo}"/>
											</li>
										</volist>
									</ul>
								</p>
							</section>
						</if>
				</if>
                <if condition="!$is_app_browser">
                    <div id="pullUp" style="bottom:-60px;">
                        <img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
                    </div>
                </if>
			</div>
		</div>
		{pigcms{$shareScript}
	</body>
</html>