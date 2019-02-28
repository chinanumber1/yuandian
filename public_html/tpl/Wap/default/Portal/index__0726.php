<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>{pigcms{$config.site_name}</title>
	<meta name="keywords" content="">
	<meta name="description" content="">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/index2017-mb.css">

	<script>
		window['ipageTitleTxt'] = '{pigcms{$config.site_name}';
		var site_remen = "";
	</script>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
	<script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
	<script src="{pigcms{$static_path}portal/js/wap_common_2015.js"></script>
	<!--必须在现有的script外-->
	<script>
		var isapp ="0";//在现有的js内:是否app平台
		var YDB;
		if(isapp === '1'){
			YDB = new YDBOBJ();
		}
	</script>
	<style>
		#slide .cell img {
		    width: 100%;
		    height: 160px;
		}
		 .span_wid{
            display: inline-block;
            width:80%;
            overflow: hidden;
            text-overflow:ellipsis;
            white-space: nowrap;
        }
	</style>
</head>
<body>
	<div id="pageMain">
		<div class="top_gg po_re" id="top_gg" style="display:none;">
			<span class="close po_ab">关闭</span>
		</div>
		<div class="header">
			<a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back" style="display: none;">返回</a>
			<a href="{pigcms{:U('Wap/My/index')}" class="my <if condition="$user_session['uid']">ico_ok</if>" id="login_ico">我的</a>
			<div class="type" id="nav_ico" style="display: none;">导航</div>
			<span id="ipageTitle" style="">{pigcms{$config.site_name}</span>
		</div>
		
		<div class="nav_index_bottom">
			<ul>
				<li class="current">
					<a href="{pigcms{:U('Portal/index')}">
						<span class="home"></span>
						首页
					</a>
				</li>
				<li class="current">
					<a href="{pigcms{:U('Shop/index')}">
						<span class="nearby"></span>
						外卖
					</a>
				</li>
				<li class="current">
					<a href="{pigcms{:U('Classify/fabu')}">
						<span class="sm"></span>
						发布
					</a>
				</li>
				<li>
					<a href="{pigcms{:U('Wap/My/index')}">
						<span class="mine"></span>
						我的
					</a>
				</li>
			</ul>
		</div>
		<div class="content wrapper">
			<div id="slide" class="clearfix" style="width: 360px;">
				<div id="content" style="width: 1080px; transform: translate3d(-360px, 0px, 0px) scale(1);">


					<volist name="portal_wap_index" id="vo">
						<div class="cell" style="width: 360px;">
							<a href="{pigcms{$vo.url}">
								<img src="{pigcms{$vo.pic}" alt=""></a>
							<span class="title">{pigcms{$vo.name}</span>
						</div>
					</volist>
					
				</div>
				<ul id="indicator">
					<volist name="portal_wap_index" id="k_vo">
						<li></li>
					</volist>
				</ul>
			</div>
			<span class="prev" id="slide_prev" style="display:none">上一张</span>
			<span class="next" id="slide_next" style="display:none">下一张</span>
			<ul id="nav_APP_data" style="display:none;"></ul>
			


			<div class="viewport">
				<div id="nav_Node" class="nav_APP showNavApp">
					<div id="scroller" class="clearfix" style="width: 720px; transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
						<div class="slide" style="width: 360px;">
							<ul class="clearfix">
								<li>
						            <a href="{pigcms{:U('Classify/index')}">
						                便民信息
						                <s class="s" style="background-color:#ff5f45; background-image:url({pigcms{$static_path}portal/images/201603031032450876840.png);"></s>
						            </a>
						        </li>
						        <li>
						            <a href="{pigcms{:U('Appoint/index')}">
						                家政服务
						                <s class="s" style="background-color:#34aef4; background-image:url({pigcms{$static_path}portal/images/201603031031173057056.png);"></s>
						            </a>
						        </li>
						        <li>
						            <a href="{pigcms{:U('Ride/ride_list')}">
						                顺风车
						                <s class="s" style="background-color:#5adcc8; background-image:url({pigcms{$static_path}portal/images/201603031035348719045.png);"></s>
						            </a>
						        </li>
						        <li>
						            <a href="{pigcms{:U('Service/index')}">
						                便捷跑腿
						                <s class="s" style="background-color:#d81e06; background-image:url({pigcms{$static_path}portal/images/201701091601176021129.png);"></s>
						            </a>
						        </li>
						        <li>
						            <a href="{pigcms{:U('Portal/activity')}">
						                激情活动
						                <s class="s" style="background-color:#7778b5; background-image:url({pigcms{$static_path}portal/images/201701101210535444969.png);"></s>
						            </a>
						        </li>
						        <li>
						            <a href="{pigcms{:U('Portal/yellow')}">
						                本地黄页
						                <s class="s" style="background-color:#87d140; background-image:url({pigcms{$static_path}portal/images/201603031036361226034.png);"></s>
						            </a>
						        </li>
						        <li>
						            <a href="{pigcms{:U('Portal/article')}">
						                新闻资讯
						                <s class="s" style="background-color:#1bca4c; background-image:url({pigcms{$static_path}portal/images/201603031028335841178.png);"></s>
						            </a>
						        </li>
						        <li>
						            <a href="{pigcms{:U('Portal/tieba')}">
						                问答专区
						                <s class="s" style="background-color:#34aef4; background-image:url({pigcms{$static_path}portal/images/201603031041224861590.png);"></s>
						            </a>
						        </li>
							</ul>
						</div>

					</div>
				</div>
				<div id="indicator2" style="width: 17px;" class=" iScrollLoneScrollbar">
					<div id="dotty" style="transition-duration: 0ms; display: block; transform: translate(0px, 0px) translateZ(0px); transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1);"></div>
				</div>
			</div>

			<div class="news">
				<div class="inner1" id="txtScrollNode">
					<ul class="inner2" style="height: 120px; position: absolute; top: 0px;">
						<volist name="news_list" id="vo">
							<li>
								<a href="{pigcms{:U('Systemnews/news',array('id'=>$vo['id']))}" style="color:;">{pigcms{$vo.title}</a>
							</li>
						</volist>
					</ul>
				</div>
			</div>



			<div class="module module_1" id="tab_01">
				<div class="select_01 select_index tab-hd">
					<ul>
						<li class="item current">
							<a href="javascript:void(0);">便民信息</a>
						</li>
						<li class="item">
							<a href="javascript:void(0);">最新资讯</a>
						</li>
						<li class="item">
							<a href="javascript:void(0);">问答专区</a>
						</li>
						<li class="item">
							<a href="javascript:void(0);">精华推荐</a>
						</li>
					</ul>
				</div>
				<div class="bd pic_1_list tab-cont">
					<ul>
						<volist name="hot_news" key="k" id="vo">
							<li>
								<a href="{pigcms{:U('Portal/article_detail',array('aid'=>$vo['aid']))}">
									<img src="{pigcms{$vo.thumb}" class="pic" alt="">
									<h3><span class="span_wid">{pigcms{$vo.title} </span></h3>
									<p class="p_l_r clearfix">
										<span class="left">{pigcms{$vo.dateline|date="m-d H:i",###}</span>
										<span class="right">人气：{pigcms{$vo.PV}</span>
									</p>
								</a>
							</li>
						</volist>
					</ul>
				</div>
				<div class="bd pic_1_list tab-cont">
					<ul>
						<volist name="hot_news" key="k" id="vo">
							<li>
								<a href="{pigcms{:U('Portal/article_detail',array('aid'=>$vo['aid']))}">
									<img src="{pigcms{$vo.thumb}" class="pic" alt="">
									<h3><span class="span_wid">{pigcms{$vo.title} </span></h3>
									<p class="p_l_r clearfix">
										<span class="left">{pigcms{$vo.dateline|date="m-d H:i",###}</span>
										<span class="right">人气：{pigcms{$vo.PV}</span>
									</p>
								</a>
							</li>
						</volist>
					</ul>
				</div>
				<div class="bd pic_1_list tab-cont" style="display: none;">
					<ul>
						<volist name="newsTieList" id="vo">
							<li <if condition="$vo['pic'] eq ''"> style="padding-left: 0px;"</if>>
								<a href="{pigcms{:U('Portal/tieba_detail',array('tie_id'=>$vo['tie_id']))}">
									<if condition="$vo['pic'] neq ''"><img src="{pigcms{$vo.pic}" class="pic" alt=""></if>
									<h3><span class="span_wid">{pigcms{$vo.title} </span><if condition="$vo['is_essence'] eq 1"><span class="ico_jh display1">精华</span></if></h3>
									<p class="p_l_r clearfix">
										<span class="left">{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</span>
										<span class="right">跟帖：{pigcms{$vo.reply_sum}　人气：{pigcms{$vo.pageviews}</span>
									</p>
								</a>
							</li>
						</volist>
					</ul>
				</div>
				<div class="bd pic_1_list tab-cont" style="display: none;">
					<ul>
						<volist name="essenceList" id="vo">
							<li <if condition="$vo['pic'] eq ''"> style="padding-left: 0px;"</if>>
								<a href="{pigcms{:U('Portal/tieba_detail',array('tie_id'=>$vo['tie_id']))}">
									<if condition="$vo['pic'] neq ''"><img src="{pigcms{$vo.pic}" class="pic" alt=""></if>
									<h3><span class="span_wid">{pigcms{$vo.title} </span><if condition="$vo['is_essence'] eq 1"><span class="ico_jh display1">精华</span></if></h3>
									<p class="p_l_r clearfix">
										<span class="left">{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</span>
										<span class="right">跟帖：{pigcms{$vo.reply_sum}　人气：{pigcms{$vo.pageviews}</span>
									</p>
								</a>
							</li>
						</volist>
					</ul>
				</div>
			</div>
			<div class="zdy_html2015">
				<div class="zd_html">
					<a href="{pigcms{$portal_wap_index_banner[0]['url']}"><img style="height: 73px;" src="{pigcms{$portal_wap_index_banner[0]['pic']}" alt="{pigcms{$portal_wap_index_banner[0]['name']}"></a>
				</div>
			</div>
			<div class="module module_2">
				<div class="hd clearfix">
					<span class="tit">
						<s class="s s_4"></s>
						最近活动
					</span>
					<a class="more" href="{pigcms{:U('activity')}">更多</a>
				</div>
				<div class="bd pic_3_list">
					<ul>
						<volist name="activityList" id="vo">
							<li>
								<a href="{pigcms{:U('Portal/activity_detail',array('a_id'=>$vo['a_id']))}">
									<div class="pic"> <sup class="bm{pigcms{$vo.state}"></sup>
										<img style="height: 106px;" src="{pigcms{$config.site_url}/upload/portal/{pigcms{$vo.pic}" alt=""></div>
									<h3>
										<span class="span_wid">{pigcms{$vo.title} </span>
										<span class="bao">{pigcms{$vo.already_sign_up}人已报</span>
									</h3>
									<div class="clearfix">
										<p class="time">{pigcms{$vo.time}</p>
										<p class="address">{pigcms{$vo.place}</p>
									</div>
								</a>
							</li>
						</volist>
					</ul>
				</div>
			</div>
			<div class="zdy_html2015">
				<div class="zd_html">
					<a href="{pigcms{$portal_wap_index_banner[1]['url']}"><img style="height: 73px;" src="{pigcms{$portal_wap_index_banner[1]['pic']}" alt="{pigcms{$portal_wap_index_banner[1]['name']}"></a>
				</div>
			</div>
			<div class="module module_2" style="padding-bottom:10px;">
				<div id="tab_03">
					<div class="hd clearfix">
						<span class="tit">
							<s class="s s_5"></s>
						</span>
						<a class="more" href="{pigcms{:U('Wap/Shop/index')}">更多</a>
						<div class="select_index2 tab-hd">
							<ul>
								<li class="item current">
									<a href="javascript:void(0);">口碑排行</a>
								</li>
								<!-- <li class="item">
									<a href="javascript:void(0);">最新入驻</a>
								</li> -->
							</ul>
						</div>
					</div>
					<div class="bd pic_4_list">
						<ul class="tab-cont clearfix">
							<volist name="near_shop_list" id="vo">
								<li>
									<a href="{pigcms{:U('Shop/classic_shop',array('shop_id'=>$vo['store_id']))}">
										<div class="pic">
											<img src="{pigcms{$vo.image}"  alt="{pigcms{$vo.name}" style="height: 49px;"></div>
										<h3><span class="span_wid">{pigcms{$vo.name} </span></h3>
									</a>
								</li>
							</volist>
						</ul>
					</div>
				</div>
		
			</div>

			<div class="index2017_bottom">
				<ul class="clearfix">
					<li class="li_01">
						<a href="{pigcms{:U('Wap/Classify/index')}">
							<s class="s"></s>
							<p class="big">找信息</p>
							<p class="small">只有想不到，没有找不到</p>
						</a>
					</li>
					<li class="li_02">
						<a href="{pigcms{:U('Wap/Classify/index')}">
							<s class="s"></s>
							<p class="big">租房子</p>
							<p class="small">全城真实有效出租房源</p>
						</a>
					</li>
					<li class="li_03">
						<a href="{pigcms{:U('Wap/Classify/index')}">
							<s class="s"></s>
							<p class="big">买房子</p>
							<p class="small">寻找的不是间房，是梦想</p>
						</a>
					</li>
					<li class="li_04">
						<a href="{pigcms{:U('Wap/Classify/index')}">
							<s class="s"></s>
							<p class="big">找工作</p>
							<p class="small">找工作 唯快不破</p>
						</a>
					</li>
				</ul>
			</div>
			<div class="zdy_html2015">
				<div class="zd_html">
					<a href="{pigcms{$portal_wap_index_banner[2]['url']}"><img style="height: 73px;" src="{pigcms{$portal_wap_index_banner[2]['pic']}" alt="{pigcms{$portal_wap_index_banner[2]['name']}"></a>
				</div>
			</div>
		</div>
<div class="windowIframe" id="windowIframe" data-loaded="0">
<div class="header">
	<a href="javascript:;" class="back close" style="display: none;">返回</a>
	<span id="windowIframeTitle"></span>
</div>
<div class="body" id="windowIframeBody"></div>
</div>
<div id="l-map" style="display:none;"></div>
<script src="{pigcms{$static_path}portal/js/wap_common.js"></script>

<script src="{pigcms{$static_path}portal/js/iscroll.js"></script>
<script src="{pigcms{$static_path}portal/js/json2.js"></script>
<script src="{pigcms{$static_path}portal/js/jquery.cookie.js"></script>
<script src="{pigcms{$static_path}portal/js/index_m2017.js"></script>
<script>

document.addEventListener('DOMContentLoaded',function(){
	loaded();
	$('#index_tg_list').img1bi1();
	$('#index_tg_list2').img1bi1();
	$('#index_tg_list3').img1bi1();
	$('#txtScrollNode').loopScrollTxt();
	$('.header .back').hide();
	$('#nav_ico').hide();
	$('#search_ico').show();
	var list = $('#content').find('.cell');
	if(list.length > 0){
		$('#slide').show();
		var txt = '';
		$('#content').find('.cell').each(function(i){
			if(i === 0){
				txt += '<li class="active">1</li>';
			}else{
				txt += '<li>'+(i+1)+'</li>';
			}
		});
		$('#indicator').html(txt);
		var w_w = $(window).width();
		setTimeout(function(){new C_Scroll({container:'slide',content:'content',ct:'indicator',size:w_w,intervalTime:5000,lazyIMG:!!0});},20);
	}
	IDC2.closeGG('top_gg');
	IDC2.tabADS($('#tab_01'));
	IDC2.tabADS($('#tab_02'));
	IDC2.tabADS($('#tab_03'));
	
	var companyList = $('#tab_03').find('img');
	var oneWidth = parseInt(companyList.eq(0).width()*2/3);
	companyList.css({'height':oneWidth+'px'});
},false);
</script>
{pigcms{$shareScript}
<script type="text/javascript">
	window.shareData = {
		"moduleName":"Home",
		"moduleID":"0",
		"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Portal/index')}",
		"tTitle": "{pigcms{$config.site_name}-门户首页",
		"tContent": "{pigcms{$config.site_name}-门户首页"
	};
</script>
</body>
</html>