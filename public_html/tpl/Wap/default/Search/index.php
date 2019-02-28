<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<if condition="$is_wexin_browser">
		<title><if condition="$_GET['type'] eq 'meal'">{pigcms{$config.meal_alias_name}<else/>{pigcms{$config.group_alias_name}</if>搜索</title>
	<else/>
		<title>搜索_{pigcms{$config.site_name}</title>
	</if>
	<meta name="description" content="{pigcms{$config.seo_description}">
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">

    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<style>
		#clear-history {
			margin-top: .2rem;
		}
		.search-wrapper {
			min-height: 5rem;
		}
		#search-form {
			margin-top: .2rem;
			margin-bottom: .2rem;
			height: .8rem;
			position: relative;
		}
		.nav-bar {
			border-top: 1px solid #ccc;
			margin-top: .2rem;;
			display: none;
		}
		.search-dom{
			position: absolute;
			left: 0;
			top: 0;
			width: 1.4rem;
			height: 100%;
			/*-webkit-box-sizing: border-box;*/
			text-align: center;
			/*border: 1px #CCC solid;*/
			background: white;
		}
		.search-dom select{
			/*width: 1.2rem;*/
			width:100%;
			height: 100%;
			border:none;
			outline:none;
		}
		.box-search {
			vertical-align: middle;
			position: relative;
			margin-right: 1.3rem;
			margin-left: 1.5rem;
			border-radius: .06rem;
			border: 1px #CCC solid;
			background: #FFF;
			height: .8rem;
			line-height: .8rem;
			padding: 0 .7rem 0 .7rem;
			-webkit-box-sizing: border-box;
		}
		.box-search.active {
			border-color: #2bb2a3;
		}
		#search-form button {
			position: absolute;
			right: 0;
			top: 0;
			width: 1.2rem;
			height: 100%;
			-webkit-box-sizing: border-box;
		}
		#search-form input[type='text'] {
			width: 100%;
			border: none;
			background: rgba(255, 255, 255, 0);
			outline-style: none;
			display: block;
			line-height: .28rem;
			height: 100%;
			font-size: .28rem;
			padding: 0;
		}
		#search-form .icon-search {
			position: absolute;
			left: .2rem;
			font-size: .4rem;
			color: #999;
		}
		.search-suggestion {
			border-top: 1px solid #ccc;
		}
		.search-suggestion .list-item {
			background-color: #FDFDFC;
			border-bottom: 1px solid #ccc;
		}
		.search-suggestion .list-item>a {
			padding: .3rem .4rem;
		}
		.search-suggestion .list-item .result-count {
			float: right;
			color: #999;
		}	
		
		.table {
		  min-height: .8rem;
		  position: relative;
		  overflow: hidden;
		  z-index: 0;
		}
		.table:before {
		  content: '';
		  position: absolute;
		  width: 25%;
		  left: 25%;
		  height: 100%;
		  border-left: 1px solid #ddd8ce;
		  border-right: 1px solid #ddd8ce;
		}
		.table:after {
		  content: '';
		  position: absolute;
		  width: 10%;
		  left: 75%;
		  height: 100%;
		  border-left: 1px solid #ddd8ce;
		  border-right: none; 
		}
		.table.table-t3:before{
		  width: 33.33%;
		  left: 33.33%; 
		}
		.table.table-t3:after {
		  border: none; 
		}
		.table li,
		.table h4 {
		  display: inline-block;
		  width: 25%;
		  height: .8rem;
		  line-height: .8rem;
		  font-size: .28rem;
		  text-align: center;
		  border-bottom: 1px solid #ddd8ce;
		  margin-bottom: -1px;
		  float: left;
		  position: relative;
		  z-index: 10; 
		}
		.table.table-t3 li,.table.table-t3 h4 {
		  width: 33.33%; 
		}
		.table h4 {
		  margin: 0;
		  margin-bottom: -1px;
		  height: 1.6rem;
		  line-height: 1.6rem;
		  color: #B7B7B7;
		  font-size: .8rem;
		}
	</style>
</head>
<body>
	<div id="container">
		<div id="tips"></div>
		<div class="search-wrapper">
			<div class="wrapper">
				<form id="search-form" action="<if condition="$_GET['type'] neq 'meal'">{pigcms{:U('Search/group')}<else/>{pigcms{:U('Search/meal')}</if>" method="post">
					<div class="search-dom">
						<select id="search-type">
							<option value="{pigcms{:U('Search/index',array('type'=>'group'))}" <if condition="$_GET['type'] neq 'meal'">selected="selected"</if>>{pigcms{$config.group_alias_name}</option>
							<option value="{pigcms{:U('Search/index',array('type'=>'meal'))}" <if condition="$_GET['type'] eq 'meal'">selected="selected"</if>>{pigcms{$config.meal_alias_name}</option>
						</select>
					</div>
					<div class="box-search">
						<i class="icon-search text-icon">⌕</i>
						<input id="keyword" type="text" name="w" placeholder="请输入搜索词" autocomplete="off" value=""/>
					</div>
					<button type="submit" class="btn" disabled="disabled" id="search-submit">搜索</button>
				</form>
				<if condition="$search_hot_list">
					<div id="search-hot">
						<h4 style="margin:.3rem 0;">搜索热词</h4>
						<ul class="box nopadding table table-t3">
							<volist name="search_hot_list" id="vo">
								<li><a class="hot-link react" href="{pigcms{$vo.url}">{pigcms{$vo.name}</a></li>
							</volist>
						</ul>
					</div>
				</if>
			</div>
		</div>
	</div>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_path}js/common_wap.js"></script>
	<script>
		$(function(){
			$('#search-type').change(function(){
				window.location.href = $(this).val();
			});
			$('#keyword').bind('input',function(){
				if($('#keyword').val().length > 0){
					$('#search-submit').prop('disabled',false);
				}else{
					$('#search-submit').prop('disabled',true);
				}
			});
			$('#search-form').submit(function(){
				$('#keyword').val($.trim($('#keyword').val()));
				if($('#keyword').val().length == 0){
					alert('请输入搜索词！');
					return false;
				}
			});
		});
	</script>
	<include file="Public:footer"/>
</body>
</html>