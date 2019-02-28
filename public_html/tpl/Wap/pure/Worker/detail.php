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
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?555" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/exif.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/imgUpload.js?210" charset="utf-8"></script>
		<script type="text/javascript">var post_url = "{pigcms{:U('Worker/do_work')}", location_url = "{pigcms{:U('Worker/detail', array('pigcms_id' => $repair_detail['pigcms_id']))}",post_follow_url = "{pigcms{:U('Worker/do_follow')}", contentDetail = true;</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/worker.js?20180723" charset="utf-8"></script>
		<style>
			section{margin-top:10px;padding:0 10px;}
			.header{margin-bottom:5px;font-size:16px;}
			.upload_list{background-color:white;}
			.upload_list img{width:100%;height:100%;}
			.gray{color:gray}
			#submit_follow{
				 background-color: #04BE02;
			    color: white;
			    float: right;
			    border: 0px;
			    border-radius: 5px;
			    height: 25px;
			    width: 55px;
			}

		
		</style>
		<script type="text/javascript">
			$(function(){
				$('.upload_list img').height($('.upload_list li:first').width()).width($('.upload_list li:first').width());
			});
		</script>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn" onclick="location.href='{pigcms{:U('Worker/index')}'"></div>任务详情</header>
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
				<if condition="$repair_detail['status'] lt 2">
					<div class="area_btn"><input type="button" id="submit_btn" class="do_work" data-id="{pigcms{$repair_detail['pigcms_id']}"  value="接任务"/></div>
				<elseif condition="$repair_detail['status'] eq 2" />

					<section>
						<p class="header">接单留言：</p>
						<p class="gray">{pigcms{$repair_detail.msg}</p>
					</section>
					<section>
						<p class="header">跟进内容：</p>
						<if condition="$follow">
							<volist name="follow" id="vo">
								<p class="gray">{pigcms{$vo['time']} - {pigcms{$vo['content']}</p>
							</volist>
						</if>
					</section>
					<form id="repair_form" onsubmit="return false;"  class="village_repair">
						<section>
							<textarea id="followcontent" class="newarea" name="followcontent" placeholder="请填写跟进内容"></textarea>
							<input type="button" id="submit_follow" value="提交" style="margin-top: 4px" />
							
						</section>
						<section style="margin-top: 40px">
							<input type="hidden" name="pigcms_id" value="{pigcms{$repair_detail['pigcms_id']}" />
							<input type="hidden" name="status" value="3" />
							<textarea id="j_cmnt_input" class="newarea" name="content" placeholder="请填写处理意见"></textarea>
							<div class="pic_tip" id="uploadNum">还可上传<span class="leftNum orange">8</span>张图片，已上传<span class="loadedNum orange">0</span>张(非必填)</div>
							<div class="upload_box"> 
								<ul class="upload_list clearfix" id="upload_list"> 
									<li class="upload_action">
										<img src="{pigcms{$config.site_url}/tpl/Wap/default/static/classify/upimg.png"/>
										<input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" id="fileImage" name=""/>
									</li> 
								</ul> 
							</div>
						</section>
					</form>
					<div class="area_btn"><input type="button" id="submit_btn" value="提交处理"/></div>
				<else />
					<section>
						<p class="header">接单留言：</p>
						<p class="gray">{pigcms{$repair_detail.msg}</p>
					</section>
					<section>
						<p class="header">跟进内容：</p>
						<if condition="$follow">
							<volist name="follow" id="vo">
								<p class="gray">{pigcms{$vo['time']} - {pigcms{$vo['content']}</p>
							</volist>
						</if>
					</section>
					<section>
						<p class="header">处理意见：<font class="gray" style="font-size:12px;">{pigcms{$repair_detail.reply_time|date='Y-m-d H:i',###}</font></p>
						<p class="gray">{pigcms{$repair_detail.reply_content}</p>
					</section>
					<if condition="$repair_detail['reply_pic']">
						<section style="padding: 0">
							<p class="header" style="padding: 0 10px">处理图片：</p>
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
					<if condition="$repair_detail['status'] eq 4" >
						<section>
							<p class="header">评分：<font color="red" style="font-size:12px;">{pigcms{$repair_detail.score}</font></p>
							<p class="header">评论内容：<font class="gray" style="font-size:12px;">{pigcms{$repair_detail.comment_time|date='Y-m-d H:i',###}</font></p>
							<p class="gray">{pigcms{$repair_detail.comment}</p>
						</section>
						<if condition="$repair_detail['comment_pic']">
							<section style="padding: 0">
								<p class="header" style="padding: 0 10px">处理图片：</p>
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