<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <title>发布评论</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
    	<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<!--script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script-->
		<script type="text/javascript" src="{pigcms{$static_path}js/exif.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/imgUpload.js?210" charset="utf-8"></script>
		
		<style>
    h6 {
        font-size: .3rem;
        font-weight: normal;
        margin-bottom: .2rem;
    }
    .btn-wrapper {
        margin: .28rem .2rem;
    }
    .score {
        position: relative;
    }
    .score:before,
    .score span:after {
        content: '★★★★★';
        position: absolute;
        font-family: 'base_icon';
        font-size: .5rem;
        color: #e9e9e9;
        letter-spacing: .4rem;
        line-height: 1em;
        left: 0;
    }
    .score input {
        opacity: .0;
        width: 100%;
        height: 100%;
        -webkit-appearance: initial;
        outline: none;
        z-index: 3;
    }
    .score label {
        display: inline-block;
        width: .84rem;
        height: .5rem;
    }
    .score span {
        position: absolute;
        visibility: hidden;
        color: #f49231;
        top: 0;
        left: 0;
        width: 100%;
        line-height: 1.9em;
        font-size: 14px;
        pointer-events: none;
        text-align: right;
    }

    .score span:after {
        color: #f49231;
    }
    .score input:checked + span {
        visibility: visible;
    }
    .score_1:checked + span:after {
        content: '★';
    }
    .score_2:checked + span:after {
        content: '★★';
    }
    .score_3:checked + span:after {
        content: '★★★';
    }
    .score_4:checked + span:after {
        content: '★★★★';
    }
    textarea {
        width: 100%;
    }
    .react .kv-line {
        margin: 0;
    }
</style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>发布评论</header>
	<else />
		<style type="text/css">
			#container{top:0}
		</style>
    </if>
		<div id="container">
			<div id="scroller" class="village_repair">
			<form id="repair_form" method="post"  onsubmit="return false;">
			<dl class="list">
				<dd class="dd-padding">
					<h6>评分</h6>
					<div class="score">
						<label>
							<input type="radio" class="score_1" value="1" name="score"/>
							<span><b>1</b>分</span>
						</label>
						<label>
							<input type="radio" class="score_2" value="2" name="score"/>
							<span><b>2</b>分</span>
						</label>
						<label>
							<input type="radio" class="score_3" value="3" name="score"/>
							<span><b>3</b>分</span>
						</label>
						<label>
							<input type="radio" class="score_4" value="4" name="score"/>
							<span><b>4</b>分</span>
						</label>
						<label>
							<input type="radio" class="score_5" value="5" name="score" checked="checked"/>
							<span><b>5</b>分</span>
						</label>
					</div>
				</dd>
			</dl>
			<dl class="list">
				<dd>
					<dl>
						<dd class="dd-padding">
							<textarea name="comment" class="input-weak" placeholder="填写评论内容" style="height:4.2em;text-indent:0rem;"></textarea>
						</dd>

						 <dd class="item uploadNum" id="uploadNum">还可上传<span class="leftNum orange">8</span>张图片，已上传<span class="loadedNum orange">0</span>张(非必填)</dd> 
						<dd class="item"> 
						 <div class="upload_box"> 
						  <ul class="upload_list clearfix" id="upload_list"> 
						   <li class="upload_action"> 
						   <img src="{pigcms{$config.site_url}/tpl/Wap/default/static/classify/upimg.png"/>
						   <input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" id="fileImage" name="" /> </li> 
						  </ul> 
						 </div>
						</dd>
					</dl>
				</dd>
			</dl>
			<div class="btn-wrapper"><button type="button" id="submit_btn" class="btn btn-larger btn-block btn-strong" style="background-color: #04BE02;">发布</button></div>
		</form>
			</div>
		</div>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_comment.js" charset="utf-8"></script>
		{pigcms{$shareScript}
	</body>
</html>