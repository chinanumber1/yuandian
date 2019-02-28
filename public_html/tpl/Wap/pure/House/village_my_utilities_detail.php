<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <if condition="!$is_app_browser">
        <title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>水电煤上报详情</title>
        </if>
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
		<script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script>
		<style>
			section{margin-top:20px;padding:0 20px;}
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
        <header class="pageSliderHide"><div id="backBtn" onclick="location.href='{pigcms{:U('House/village_my_utilitieslists')}'"></div>水电煤上报详情</header>
    </if>
		<div id="container">
			<div id="scroller">
				<section>
					<p class="header">内容：</p>
					<p class="gray">{pigcms{$repair_detail.content}</p>
				</section>
				<if condition="$repair_detail['pic']">
					<section>
						<p class="header">图片：</p>
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
				<section>
					<p class="header">状态：
					<if condition='$repair_detail["status"] eq 0'>
						<font color="red">未受理</font>
					<elseif condition='$repair_detail["status"] eq 1' />
						<font color="green">物业已受理</font>
					<elseif condition='$repair_detail["status"] eq 2' />
						<font color="green">客服专员已受理</font>
					<elseif condition='$repair_detail["status"] eq 3' />
						<font color="green">客服专员已处理</font>
					<elseif condition='$repair_detail["status"] eq 4' />
						<font color="green">已评价</font>
					</if>
					</p>
				</section>
				
				<if condition='$repair_detail["status"] eq 0'>
				<elseif condition='$repair_detail["status"] eq 1' />
					<if condition="$worker">
					<section>
						<p class="header">指派给：<font color="green">{pigcms{$worker['name']}</font>，电话<a href="tel:{pigcms{$worker['phone']}">{pigcms{$worker['phone']}</font></p>
						<p class="header">指派时间：<font color="gray" style="font-size:12px;">{pigcms{$repair_detail.status_time_1|date='Y-m-d H:i',###}</font></p>
					</section>
					</if>
				<elseif condition='$repair_detail["status"] eq 2' />
					<if condition="$repair_detail['msg']">
					<section>
						<p class="header">接单留言：</p>
						<p class="gray">{pigcms{$repair_detail.msg}</p>
					</section>
					</if>
					<if condition="$worker">
					<section>
						<p class="header">受理的客服专员：<font color="green">{pigcms{$worker['name']}</font>，电话<a href="tel:{pigcms{$worker['phone']}">{pigcms{$worker['phone']}</font></p>
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
						<p class="header">处理的客服专员：<font color="green">{pigcms{$worker['name']}</font>，电话<a href="tel:{pigcms{$worker['phone']}">{pigcms{$worker['phone']}</font></p>
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
					<a href="{pigcms{:U('House/village_comment',array('pigcms_id' => $repair_detail['pigcms_id'], 'type' => 'utilities'))}" value="去评论"><div class="area_btn" style="font-size: 1.2rem;color: white;text-align: center;">去评论</div></a>
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
						<p class="header">处理的客服专员：<font color="green">{pigcms{$worker['name']}</font>，电话<a href="tel:{pigcms{$worker['phone']}">{pigcms{$worker['phone']}</font></p>
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