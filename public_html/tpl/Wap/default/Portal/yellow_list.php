<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>便民黄页栏目首页-网上电话114-{pigcms{$config.site_name}</title>
	<meta name="keywords" content="便民黄页栏目关键词,关键词,关键词,关键词,关键词,关键词,关键词,关键词">
	<meta name="description" content="便民黄页栏目介绍">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/bm-mb.css">
	<style type="text/css">.filter2 .inner_child .num { display:none!important;}</style>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
	<script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
	<script src="{pigcms{$static_path}portal/js/wap_common_2015.js"></script>
	<!--必须在现有的script外-->
</head>
<body class="<if condition="$like_app_browser">like_app</if>">
	<div id="pageMain">
		<div class="header">
			<a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back">返回</a>
			<div class="search" id="search_ico" onclick="showNewPage(&#39;搜索&#39;,searchHtml,newPageSearch);" style="">搜索</div>
			<a href="{pigcms{:U('Wap/My/index')}" class="my <if condition="$user_session['uid']">ico_ok</if>" id="login_ico" style="display: none;">我的</a>
			<div class="type" id="nav_ico">导航</div>
			<span id="ipageTitle" style="">便民黄页</span>
		    <include file="Portal:top_nav"/>
		</div>
	

		<div class="nav_bm_bottom">
			<ul>
				<li> <a href="{pigcms{:U('Wap/Portal/index')}"> <span class="home"></span> 首页 </a> </li>
				<li> <a href="{pigcms{:U('Wap/My/index')}"> <span class="mine"></span> 我的 </a> </li>
			</ul>
		</div>
		<div class="o_main mar_b_50">

			<div class="filter2" id="filter2">
				<ul class="tab clearfix">
					<li class="item">
						<a href="javascript:void(0);">
							<span id="show_categories">{pigcms{$cat_name}</span> <em></em>
						</a>
					</li>
					<li class="item">
						<a href="javascript:void(0);">
							<span id="show_district">全部区域</span> <em></em>
						</a>
					</li>
				</ul>

				<div class="inner" style="display:none;">
					<ul>
						<li class="all">
							<a href="{pigcms{:U('Portal/yellow_list')}" id="s_colname_" class="t <if condition="$pid eq ''">selected</if>">类别不限</a>
						</li>
						<volist name="all_category_list" id="vo">
							<li class="item <if condition="$vo['cat_id'] eq $pid">hasUL_current</if> hasUL">
								<a href="javascript:void(0);" class="hasUlLink">
									{pigcms{$vo.cat_name}
								</a>
								<ul style="display:;">
									<li>
										<a href="{pigcms{:U('Portal/yellow_list',array('pid'=>$vo['cat_id']))}" data-id="376" id="s_376" class="allCat ">全部分类</a>
									</li>
									<volist name="vo['category_list']" id="vv">
										<li>
											<a href="{pigcms{:U('Portal/yellow_list',array('cid'=>$vv['cat_id'],'parent_id'=>$vo['cat_id']))}"  <if condition="$vv['cat_id'] eq $cid">class="selected"</if> >{pigcms{$vv.cat_name}</a>
										</li>
									</volist>
								</ul>
							</li>
						</volist>
					</ul>
				</div>
				<div class="inner" style="display:none;">
					<a href="{pigcms{:U('Portal/yellow_list')}" data-id="0" id="s_areaid_0" <if condition="$area_id eq ''">class="selected"</if> >全部区域</a>
					<volist name="area_list" id="vo">
						<a href="{pigcms{:U('Portal/yellow_list',array('area_id'=>$vo['area_id']))}" <if condition="$area_id eq $vo['area_id']">class="selected"</if> >{pigcms{$vo.area_name}</a>
					</volist>
				</div>
				<div class="inner_parent" id="parent_container" style="display:none;">
					<div class="innercontent"></div>
				</div>
				<div class="inner_child" id="inner_container" style="display:none;">
					<div class="innercontent"></div>
				</div>
			</div>
			<div class="fullbg" id="fullbg" style="display:none;"> <i class="pull2"></i> </div>

			<div class="new_active n_mb">
				<div class="info_list">
					<ul>
						<if condition="is_array($yellow_list)">
							<volist name="yellow_list" id="vo">
								<li>
									<a href="{pigcms{:U('Portal/yellow_detail',array('id'=>$vo['id']))}">
										<div class="pic">
											<img src="{pigcms{$vo.logo}"  alt="{pigcms{$vo.title}"></div>
										<div class="con">
											<h3>
												{pigcms{$vo.title}
											</h3>
											<p>{pigcms{$vo.address}</p>
										</div>
									</a>
									<a href="tel:{pigcms{$vo.tel}" class="tel">{pigcms{$vo.tel}</a>
								</li>
							</volist>

							<div class="pageNav2">
								{pigcms{$pagebar}
							</div>

						<else/>
							<li style="text-align: center;padding:10px 0 0 0;">暂无数据</li>
						</if>
								
					</ul>
					
				</div>
			</div>
			<p style="display:none;"></p>
		</div>
		<div class="windowIframe" id="windowIframe" data-loaded="0">
			<div class="header">
				<a href="javascript:;" class="back close">返回</a>
				<span id="windowIframeTitle"></span>
			</div>
			<div class="body" id="windowIframeBody"></div>
		</div>
		<div id="l-map" style="display:none;"></div>
		<script src="{pigcms{$static_path}portal/js/wap_common.js"></script>
		<script src="{pigcms{$static_path}portal/js/purl.js"></script>
		<script src="{pigcms{$static_path}portal/js/iscroll.js"></script>
		<script>
			var searchHtml = '<div class="searchbar2">'+
				'<form id="myform" action="" method="get">'+
					'<input type="hidden" name="c" value="Portal">'+
                    '<input type="hidden" name="a" value="yellow_list">'+
					'<input type="text" name="keyword" id="keyword" class="s_ipt" value="{pigcms{$_GET.keyword}" placeholder="输入关键字" />'+
					'<input type="submit" class="s_btn po_ab" value="搜索">'+
				'</form></div>';

			function newPageSearch(){
				$('#windowIframe .back').show();
			}
			(function($){
				$('#login_ico').hide();
				$('#search_ico').show();
				var s_a_txt = '';
				var url_obj = {'colname':'0'};
				if(url_obj['colname'] !== '' && url_obj['colname'] !== '0'){
					
					$('#s_'+url_obj['colname']).addClass('selected').parent().parent().parent().addClass('hasUL_current');
					
					$('#show_categories')
					s_a_txt = $("#s_colname_"+url_obj['colname']+" a").html();
					$('#show_categories').html(s_a_txt).parent().parent().attr('data-hasbigid',$('#s_'+url_obj['colname']).parent().parent().parent().attr('categoryid'));
				}
				showFilter({ibox:'filter2',content1:'parent_container',content2:'inner_container',fullbg:'fullbg',showsmall:true});
				
			})(jQuery);

		</script>

	</div>
</body>
</html>