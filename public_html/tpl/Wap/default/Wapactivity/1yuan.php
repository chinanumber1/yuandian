<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<if condition="$is_wexin_browser or $is_app_browser">
		<title>活动详情</title>
	<else/>
		<title>活动详情-{pigcms{$config.site_name}</title>
	</if>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/group_detail_wap.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/idangerous.swiper.css" rel="stylesheet"/>
	<style>
		.swiper-slide{
			display: -webkit-box;
			display: -ms-flexbox;
			overflow: hidden;
			-webkit-box-align: center;
			-webkit-box-pack: center;
			-ms-box-align: center;
			-ms-flex-pack: justify;
		}
		.swiper-slide img{
			width:auto;
			height:auto;
			max-width:100%;
			max-height:90%;
		}
		.swiper-pagination{
			left: 0;
			top: 10px;
			text-align: center;
			bottom:auto;
			right:auto;
			width:100%;
		}
		.swiper-close{
			right:10px;
			top:5px;
			text-align:right;
			position:absolute;
			z-index:21;
			color:white;
			font-size:.7rem;
		}
		span.tag{
			background: #fdb338;
			color: #fff;
			line-height: 1.5;
			display: inline-block;
			padding: 0 .06rem;
			font-size: .24rem;
			border-radius: .06rem;
		}
		
		
		#enter_im_div {
		  bottom: 60px;
		  left:10px;
		  z-index: 11;
		  position: fixed;
		  width: 94px;
		  height:31px;
		}
		#enter_im {
		  width: 94px;
		  position: relative;
		  display: block;
		}
		a {
		  color: #323232;
		  outline-style: none;
		  text-decoration: none;
		}
		#to_user_list {
		  height: 16px;
		  padding: 7px 6px 8px 8px;
		  background-color: #00bc06;
		  border-radius: 25px;
		  /* box-shadow: 0 0 2px 0 rgba(0,0,0,.4); */
		}
		#to_user_list_icon_div {
		  width: 20px;
		  height: 16px;
		  background-color: #fff;
		  border-radius: 10px;
		}
		
		.rel {
		  position: relative;
		}
		.left {
		  float: left;
		}
		.to_user_list_icon_em_a {
		  left: 4px;
		}
		#to_user_list_icon_em_num {
		  background-color: #f00;
		}
		#to_user_list_icon_em_num {
		  width: 14px;
		  height: 14px;
		  border-radius: 7px;
		  text-align: center;
		  font-size: 12px;
		  line-height: 14px;
		  color: #fff;
		  top: -14px;
		  left: 68px;
		}
		.hide {
		  display: none;
		}
		.abs {
		  position: absolute;
		}
		.to_user_list_icon_em_a, .to_user_list_icon_em_b, .to_user_list_icon_em_c {
		  width: 2px;
		  height: 2px;
		  border-radius: 1px;
		  top: 7px;
		  background-color: #00ba0a;
		}
		.to_user_list_icon_em_a {
		  left: 4px;
		}
		.to_user_list_icon_em_b {
		  left: 9px;
		}
		.to_user_list_icon_em_c {
		  right: 4px;
		}
		.to_user_list_icon_em_d {
		  width: 0;
		  height: 0;
		  border-style: solid;
		  border-width: 4px;
		  top: 14px;
		  left: 6px;
		  border-color: #fff transparent transparent transparent;
		}
		#to_user_list_txt {
		  color: #fff;
		  font-size: 13px;
		  line-height: 16px;
		  padding: 1px 3px 0 5px;
		}
				.w-goods-price{
			color: #9E9E9E;
			font-size: .26rem;
			  height: .46rem;
		}
		/*.w-progressBar {
  margin-right: 50px;
}*/
		.w-progressBar .wrap {
  position: relative;
  margin-bottom: 8px;
  height: 5px;
  border-radius: 5px;
  background-color: #efeeee;
  overflow: hidden;
}
.w-progressBar .bar, .w-progressBar .color {
  display: block;
  height: 100%;
  border-radius: 4px;
}
.w-progressBar .bar {
  overflow: hidden;
}
.w-progressBar .color {
  width: 100%;
  background: #FFA538;
  background: -webkit-gradient(linear,left top,right top,from(#FFCB3D),to(#FF8533));
  background: -moz-linear-gradient(left,#FFCB3D,#FF8533);
  background: -o-linear-gradient(left,#FFCB3D,#FF8533);
  background: -ms-linear-gradient(left,#FFCB3D,#FF8533);
}
.w-progressBar .txt {
  overflow: hidden;
}
.w-progressBar li {
  float: left;
  color: #9E9E9E;
			font-size: .26rem;
}
.w-progressBar .txt b {
  font-weight: normal;
}
.w-progressBar .txt-r {
  float: right;
  border: 0;
  text-align: right;
}
.finish_tip {
  display: inline-block;
  margin-left: 30px;
  color: red;
}
.txt-blue {
  color: #0079fe;
}
.m-detail-userCodes {
  background: #f4f4f4;
  color: #999999;
}
.m-detail-userCodes-blank {
  text-align: center;
  color: #8f8f8f;
}
.w-bar {
  display: block;
  overflow: hidden;
  position: relative;
  color: #525252;
  background: #fff;
}
.w-bar-hint {
    font-size: .26rem;
  color: #8f8f8f;
}
.m-detail-record-list li {
  margin-bottom: 14px;
}
.m-detail-record-time {
  display: inline-block;
  margin-bottom: 14px;
  padding: 0 5px;
  font-size: 10px;
  line-height: 15px;
  background: #f4f4f4;
  border-radius: 15px;
  border: 1px solid #d5d5d5;
}
.m-detail-record-list .avatar {
  float: left;
  margin-top: 2px;
  border-radius: 50%;
  overflow: hidden;
}
.m-detail-record-list .text {
  margin-left: 45px;
}
.m-detail-record-list .text p{
    font-size: .228rem;
	  line-height: 1.9;
}
.f-breakword {
  white-space: normal;
  word-wrap: break-word;
  word-break: break-all;
}
.m-detail-record-list .address {
  display: inline-block;
  word-wrap: no-wrap;
  word-break: no-wrap;
}
.m-detail-record-list .num {
  color: #525252;
}
.txt-red {
  color: #db3652;
}
dl.list .dd-padding.m-detail-record-wrap {
  margin-left: 28px;
  padding: 10px 10px 0 0;
  border-left: 1px solid #d5d5d5;
  overflow:visible;
}
.m-detail-record-list {
  margin-left: -18px;
}
 .m-simpleFooter {
  position: fixed;
  z-index: 2;
  left: 0;
  right: 0;
  border: 1px solid #D4D4D4;
  background: rgba(240,240,240,.8);
    padding: 8px 10px;
  bottom: 0;
  border-width: 1px 0;
  line-height: 32px;
  height: 32px;
}
.w-button {
  text-align: center;
  white-space: nowrap;
  font-size: 14px;
  display: inline-block;
  vertical-align: middle;
  color: #fff;
  background: #3399FE;
  border-width: 0;
  border-style: solid;
  border-color: #1B7DE0;
  padding: 0 15px;
  text-align: center;
  height: 30px;
  line-height: 30px;
  border-radius: 3px;
  cursor: pointer;
  text-decoration: none!important;
  outline: none;
}
.w-button-main {
  background: #db3652;
  border-color: #b6243d;
}
.w-button-main:disabled,.w-button-main.btn-disabled {
  background-color: #dcdcdc;
  color: #999;
  border: 0;
}
.m-detail-buy .w-button {
  width: 112px;
}
#deal{
	padding-bottom:49px;
}
dl.list .dd-padding.m-detail-userCodes{
	padding:.28rem .4rem;
}
dl.list .dd-padding.m-detail-userCodes p{
	  font-size: .26rem;
	  text-align:left;
}
dl.list .dd-padding.m-detail-userCodes p b{
	display: inline-block;
	margin-right: 4px;
	font-weight: normal;
}
dl.list .dd-padding.m-detail-userCodes p a{
	color:#FF658E;
}
.w-msgbox-codes {
  color: #999;
}
.w-msgbox-codes .name {
  margin-bottom: 8px;
  line-height: 30px;
  border-bottom: 1px dotted #d4d4d4;
  position: relative;
}
.f-txtabb {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
.w-msgbox-codes .name h4 {
  padding-right: 70px;
  color: #3399FE;
  line-height: 30px;
    font-size: .265rem;
}
.w-msgbox-codes .name span {
  position: absolute;
  top: 0;
  right: 0;
}
.w-msgbox-codes p{
	line-height: 24px;
}
.w-msgbox-codes li {
  float: left;
  width: 63px;
  line-height: 22px;
    font-size: .245rem;
}
.m-detail-goods-result {
  margin: 7px 0 10px;
}
.m-detail-goods-result .w-record {
  padding: 10px;
  border: 1px solid #d5d5d5;
  border-bottom: 0;
  position: relative;
  font-size: 12px;
    color: #999;
}
.m-detail-goods-result .w-record-avatar {
	  float: left;
  margin-top: 3px;
  width: 45px;
  height: 45px;
}
.m-detail-goods-result .w-record-avatar img {
  width: 100%;
  height: 100%;
  display: inline;
  vertical-align: middle;
    border-radius: 4px;
}
.m-detail-goods-result .w-record-detail {
  margin-left: 60px;
}
.m-detail-goods-result .w-record-detail p{
	line-height: 1.71;
}
.txt-green {
  color: #528d00;
}
.m-detail-goods-result-luckyCode {
  padding: 10px 0 10px 20px;
  background: #db3652;
  color: #ffffff;
  line-height: 20px;
}
.m-detail-goods-result-luckyCode b {
  font-size: 0.35rem;
}
.w-button-simple {
  padding: 0 8px;
  height: 20px;
  line-height: 20px;
  font-size: 12px;
  font-weight: normal;
  color: #fff;
  background: #db3652;
  border: 0;
  border-radius: 20px;
}
.w-button-simple-white {
  background: #fff;
  color: #db3652;
}
.m-detail-goods-result-luckyCode .resultBtn {
  margin: -5px 0 0 10px;
}
	</style>
	<style>.msg-bg{background:rgba(0,0,0,.4);position:absolute;top:0;left:0;width:100%;z-index:998}.msg-doc{position:fixed;left:.16rem;right:.16rem;bottom:15%;border-radius:.06rem;background:#fff;overflow:hidden;z-index:999}.msg-hd{background:#f0efed;color:#333;text-align:center;padding:.28rem 0;overflow:hidden;font-size:.4rem;border-bottom:1px solid #ddd8ce}.msg-bd{font-size:.34rem;padding:.28rem;border-bottom:1px solid #ddd8ce}.msg-toast{background:rgba(0,0,0,.8);font-size:.4rem;color:#fff;border:0;text-align:center;padding:.4rem;-webkit-animation-name:pop-hide;-webkit-animation-duration:5s;border-radius:.12rem;bottom:60%;opacity:0;pointer-events:none}.msg-confirm,.msg-alert{-webkit-animation-name:pop;-webkit-animation-duration:.3s}.msg-option{-webkit-animation-name:slideup;-webkit-animation-duration:.3s}@-webkit-keyframes pop-hide{0%{-webkit-transform:scale(0.8);opacity:0}2%{-webkit-transform:scale(1.1);opacity:1}6%{-webkit-transform:scale(1)}90%{-webkit-transform:scale(1);opacity:1}100%{-webkit-transform:scale(0.9);opacity:0}}@-webkit-keyframes pop{0%{-webkit-transform:scale(0.8);opacity:0}40%{-webkit-transform:scale(1.1);opacity:1}100%{-webkit-transform:scale(1)}}@-webkit-keyframes slideup{0%{-webkit-transform:translateY(100%)}40%{-webkit-transform:translateY(-10%)}100%{-webkit-transform:translateY(0)}}.msg-ft{display:-webkit-box;display:-ms-flexbox;font-size:.34rem}.msg-ft .msg-btn{display:block;-webkit-box-flex:1;-ms-flex:1;margin-right:-1px;border-right:1px solid #ddd8ce;height:.88rem;line-height:.88rem;text-align:center;color:#2bb2a3}.msg-btn:last-child{border-right:0}.msg-option{background:0;bottom:55px;}.msg-option div:first-child,.msg-option .msg-option-btns:first-child .btn:first-child{border-radius:.06rem .06rem 0 0;border-top:0}.msg-option .btn{width:100%;background:#fff;border:0;color:#FF658E;border-radius:0}.msg-option .msg-bd{background:#fff;border-bottom:0}.msg-option .btn{height:.8rem;line-height:.8rem;border-top:1px solid #ccc}.msg-option-btns .btn:last-child{border-radius:0 0 .06rem .06rem;border-bottom:1px solid #ccc}.msg-option .msg-btn-cancel{padding:0;margin-top:.14rem;color:#FF658E;border-radius:.06rem}.msg-dialog .msg-hd{background-color:#fff}.msg-dialog .msg-bd{background-color:#f0efed}.msg-slide{background:0;bottom:0;left:0;right:0;border-radius:0;-webkit-animation-name:slideup;-webkit-animation-duration:.3s}</style>
</head>
<body id="index">
		<div id="deal" class="deal">
			<div class="list">
			    <div class="album view_album" data-pics="<volist name="now_activity['all_pic']" id="vo">{pigcms{$vo.m_image}<if condition="count($now_activity['all_pic']) gt $i">,</if></volist>">
			        <img src="{pigcms{$now_activity.all_pic.0.m_image}" alt="{pigcms{$now_activity.merchant_name}"/>
			        <div class="desc">点击图片查看相册</div>
			    </div>
			    <dl class="list list-in">
			        <dd class="dd-padding buy-desc">
			            <h1>{pigcms{$now_activity.name}</h1>
			            <p class="explain">{pigcms{$now_activity.title}</p>
			        </dd>
					<ul class="campaign-tip dd-padding">
						<p class="w-goods-price">总需：{pigcms{$now_activity.all_count} 人次</p>
						<if condition="!$now_activity['is_finish']">
							<div class="w-progressBar">
								<p class="wrap">
									<span class="bar" style="width:{pigcms{:ceil($now_activity['part_count']/$now_activity['all_count']*100)}%;"><i class="color"></i></span>
								</p>
								<ul class="txt">
									<li class="txt-l"><p><b>{pigcms{$now_activity.part_count}</b>&nbsp;已参与</p></li>
									<li class="txt-r"><p>剩余&nbsp;<b class="txt-blue">{pigcms{$now_activity['all_count']-$now_activity['part_count']}</b></p></li>
								</ul>
							</div>
						<else/>
							<div class="m-detail-goods-result">
                                <div class="w-record">
                                    <i class="ico ico-label ico-label-winner"></i>
                                    <div class="w-record-avatar">
										<img width="90" height="90" src="{pigcms{$lottery_user.avatar}"/>
                                    </div>
                                    <div class="w-record-detail">
                                        <p class="f-breakword">获奖者：<a class="txt-blue">{pigcms{$lottery_user.nickname}</a>&nbsp;<span class="txt-green">({pigcms{$lottery_user.last_ip_txt})</span></p>
                                        <p>用户ID：{pigcms{$lottery_user.uid} (唯一不变标识)</p>
                                        <p>本期参与：{pigcms{:count($lottery_part_list)}人次　<a class="m-detail-userCodes-viewWinnerCodesBtn txt-blue" href="javascript:void(0)">查看Ta的号码</a></p>
                                        <p>揭晓时间：{pigcms{$now_activity.finish_time|date='Y-m-d H:i:s',###}</p>
                                    </div>
                                </div>
                                <p class="m-detail-goods-result-luckyCode">幸运号码：<b>{pigcms{$now_activity['lottery_number']}</b><a class="resultBtn w-button w-button-simple w-button-simple-white" href="{pigcms{:U('Wapactivity/calc',array('id'=>$now_activity['pigcms_id']))}">查看计算详情</a></p>
                            </div>
						</if>
					</ul>
					<dd class="dd-padding m-detail-userCodes">
						<if condition="$lottery_user_list">
							<p>您参与了：<span class="txt-red">{pigcms{:count($lottery_user_list)}</span>人次</p>
							<p class="codes">夺宝号码：<volist name="lottery_user_list" id="vo" offset="0" length="6"><b>{pigcms{$vo['number']+10000000}</b></volist><a class="m-detail-userCodes-viewCodesBtn" href="javascript:void(0)">查看全部</a></p>
						<else/>
							<p class="m-detail-userCodes-blank">您没有参与本次夺宝哦！</p>
						</if>
					</dd>
			    </dl>
			</div>
			<dl class="list">
			    <dd>
			        <dl>
			            <dt>商家信息</dt>
			            <dd class="dd-padding">
							<div class="merchant">
							    <div class="biz-detail">
									<a class="react" href="{pigcms{:U('Index/index',array('token'=>$now_activity['mer_id']))}">
										<h5 class="title single-line"> {pigcms{$now_merchant.name}</h5>
										<div class="address single-line">电话：{pigcms{$now_merchant.phone}</div>
									</a>
							    </div>
							    <div class="biz-call">
							        <a class="react phone" href="javascript:void(0);" data-phone="{pigcms{$now_merchant.phone}"><i class="text-icon">✆</i></a>
							    </div>
							</div>
			            </dd>
			        </dl>
			    </dd>
			</dl>
			<dl class="list">
			    <dd>
			        <dl>
			            <dd class="dd-padding">
							<a href="{pigcms{:U('Wapactivity/intro',array('id'=>$now_activity['pigcms_id']))}" class="w-bar more">详细介绍 <span class="w-bar-hint">( 建议在wifi下查看 )</span><span class="w-bar-ext"><b class="ico-next"></b></span></a>
			            </dd>
			        </dl>
			    </dd>
			</dl>
			<dl id="deal-details" class="list">
			    <dt>所有参与记录 </dt>
                <dd class="dd-padding m-detail-record-wrap">
                    <ul class="m-detail-record-list">
						<volist name="part_list" id="vo">
							<volist name="vo" id="voo" key="j">
								<li>
									<if condition="$j eq 1">
										<div class="m-detail-record-time">{pigcms{$voo.time|date='Y-m-d',###}</div>
										<div class="clearfix">
									</if>
										<div class="avatar"><img width="35" height="35" src="{pigcms{$voo.avatar}"/></div>
										<div class="text"><p class="f-breakword"><span class="txt-blue">{pigcms{$voo.nickname}</span>&nbsp;<span class="address">({pigcms{$voo.ip_txt} IP：{pigcms{$voo.ip})</span></p><p><span class="num">参与了<span class="txt-red">{pigcms{$voo.part_count}</span>人次</span>&nbsp;{pigcms{$voo.time|date='Y-m-d H:i:s',###}.{pigcms{$voo.msec}</p></div>
									<if condition="$j eq 1">
										</div>
									</if>
								</li>
							</volist>
						</volist>
					</ul>
                </dd>
			</dl>
		</div>
		<div class="m-simpleFooter m-detail-buy">
			<div class="m-simpleFooter-text" style="text-align:center;">
				<if condition="!$now_activity['is_finish']">
					<a id="quickBuy" class="w-button w-button-main" href="{pigcms{:U('Wapactivity/buy',array('id'=>$now_activity['pigcms_id']))}">立即参与</a>
				<else/>
					<a class="w-button w-button-main btn-disabled" href="javascript:void(0);" disabled="disabled">已经结束</a>
				</if>
			</div>
		</div>
		<div id="user_partDom" style="display:none;">
			<div class="w-msgbox-codes">
				<div class="name">
					<h4 class="f-txtabb">{pigcms{$now_activity.name}</h4>
					<span><b class="txt-red">{pigcms{:count($lottery_user_list)}</b>人次</span>
				</div>
				<p>以下是你的所有夺宝号码：</p>
				<ul class="clearfix">
					<volist name="lottery_user_list" id="vo">
						<li <if condition="$voo['number'] eq $now_activity['lottery_number']">class="txt-red"</if>>{pigcms{$vo['number']+10000000}</li>
					</volist>
				</ul>
			</div>
		</div>
		<div id="lottery_partDom" style="display:none;">
			<div class="w-msgbox-codes">
				<div class="name">
					<h4 class="f-txtabb">{pigcms{$now_activity.name}</h4>
					<span><b class="txt-red">{pigcms{:count($lottery_part_list)}</b>人次</span>
				</div>
				<p>以下是奖品获得者的所有夺宝号码：</p>
				<ul class="clearfix">
					<volist name="lottery_part_list" id="vo">
						<li <if condition="$voo['number'] eq $now_activity['lottery_number']">class="txt-red"</if>>{pigcms{$vo['number']}</li>
					</volist>
				</ul>
			</div>
		</div>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>	
		<script src="{pigcms{$static_path}js/idangerous.swiper.min.js"></script>
		<script src="{pigcms{$static_public}js/fastclick.js"></script>
		<script>var static_path="{pigcms{$static_path}";var collect_url="{pigcms{:U('Collect/collect')}";var group_id="{pigcms{$now_group.group_id}";</script>
		<script src="{pigcms{$static_path}js/group_detail.js"></script>
		
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script>
			$(function(){
				$('.view_album').height($(window).width()*334/360);
				
				var from_recharge = "{pigcms{$_GET['r']}"
				if(from_recharge!=''&&from_recharge){
					layer.open({
						content: "恭喜你，参加成功！",
						btn: ['确定'],
						shadeClose: false,
						yes:function(){
							window.location.href = "{pigcms{:U('Wapactivity/detail',array('id'=>$now_activity['pigcms_id']))}";
						}
					});
				}
			});
			FastClick.attach(document.body);
			$('.m-detail-userCodes-viewCodesBtn').click(function(){	
				var tipHeight = $(window).height()-91;
				$('.w-msgbox-codes').css({'height':tipHeight+'px','overflow-y':'auto'});
				
				layer.open({title:false,content:$('#user_partDom').html(),style:'width:100%;max-width:100%;height:100%;',btn: ['关闭']});
			});
			$('.m-detail-userCodes-viewWinnerCodesBtn').click(function(){	
				var tipHeight = $(window).height()-91;
				$('.w-msgbox-codes').css({'height':tipHeight+'px','overflow-y':'auto'});
				
				layer.open({title:false,content:$('#lottery_partDom').html(),style:'width:100%;max-width:100%;height:100%;',btn: ['关闭']});
			});
		</script>
		<php>$no_footer = true;</php>
    	<include file="Public:footer"/>
		<script type="text/javascript">
		window.shareData = {  
			"moduleName":"Wapactivity",
			"moduleID":"0",
			"imgUrl": "{pigcms{$now_activity.all_pic.0.m_image}", 
			"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Wapactivity/detail', array('id' => $now_activity['pigcms_id']))}",
			"tTitle": "【{pigcms{$config.site_name}】{pigcms{$now_activity.name}",
			"tContent": "{pigcms{$now_activity.title}"
		};
		</script>
{pigcms{$shareScript}
</body>
</html>