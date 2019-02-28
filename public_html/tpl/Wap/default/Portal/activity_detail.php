<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>{pigcms{$activityInfo.title}</title>
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/news2016-mb.css">
	<link href="{pigcms{$static_path}portal/css/pageScroll.css" rel="stylesheet">
	<link href="{pigcms{$static_path}portal/css/comment-mb.css" rel="stylesheet">
	<style>.tab-cont { overflow:hidden; display:none;}</style>
	<script src="{pigcms{$static_path}portal/js/share.js"></script>
	<link rel="stylesheet" href="{pigcms{$static_path}portal/share_style0_32.css">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
	<script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
	<script src="{pigcms{$static_path}portal/js/wap_common_2015.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
	<!--必须在现有的script外-->
	<script>
		var isapp ="0";
		var YDB;
		if(isapp === '1'){
			YDB = new YDBOBJ();
		}
	</script>
</head>
<body class="<if condition="$like_app_browser">like_app</if>">
	<div id="pageMain">
		<div class="header">
	        <a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back">返回</a>
	        <a href="{pigcms{:U('Wap/My/index')}" class="my <if condition="$user_session['uid']">ico_ok</if>" id="login_ico">我的</a>
	        <div class="type" id="nav_ico">导航</div>
	        <span id="ipageTitle" style="">同城活动</span>
	        <include file="Portal:top_nav"/>
	    </div>
		

		<div class="p_main" style="bottom:40px;">
			<div class="banner">
				<img src="{pigcms{$config.site_url}/upload/portal/{pigcms{$activityInfo.pic}" alt=""></div>
			<div class="city_info">
				<h3 style="padding-right: 0;">{pigcms{:msubstr($activityInfo['title'],0,26)}</h3>
				<p>
					<span class="source">网友自发</span>

					<if condition="$activityInfo['over_time'] lt 0">
						活动已经结束了
					<elseif condition="$activityInfo['over_time'] eq 0"/>
						<span class="s" data-iskill="2">今日截止</span> 
					<else/>
						<span class="s" data-iskill="2">{pigcms{$activityInfo.over_time}</span> 天后截止
					</if>
					<span class="s"> <span class="num" id="signup">{pigcms{$activityInfo.already_sign_up}</span> </span> 人已报名
				</p>
			</div>
			<div class="active_condit">
				<dl> <dt>时间</dt> <dd>{pigcms{$activityInfo.time}</dd> </dl>
				<dl> <dt>名额</dt> <dd><if condition="$activityInfo['number'] eq 0">不限<else/>{pigcms{$activityInfo.number}</if></dd> </dl>
				<dl> <dt>费用</dt> <dd>{pigcms{$activityInfo.price}</dd> </dl>
			</div>
			<div class="city_o_info">
				<!-- <dl> <dt>所在区域</dt> <dd>{pigcms{$activityInfo.place}</dd> </dl> -->
				<dl> <dt>报名截止</dt> <dd>{pigcms{$activityInfo.enroll_time|date="Y/m/d H:i:s",###}</dd> </dl>
				<dl> <dt>带队团长</dt> <dd>{pigcms{$activityInfo.leader}</dd> </dl>
				<i class="pass{pigcms{$activityInfo.state}"></i>
			</div>
			<div class="city_o_info">
				<dl>
					<dt>活动地点</dt>
					<dd>
						<a href="javascript:void(0);" class="address">
							<span style="display: inline-block;width: 70%;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">{pigcms{$activityInfo.place}</span>
							<!-- <s class="s"></s> -->
						</a>
					</dd>
				</dl>
			</div>
			<!--活动介绍-->
			<div class="slide_tabs" id="wrapper2">
				<ul id="scroller2" style="width: 400px; transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
					<li class="select">
						<a href="javascript:void(0);">活动介绍</a>
					</li>
					<li class="">
						<a href="javascript:void(0);">活动赞助商</a>
					</li>
					<li>
						<a href="javascript:void(0);">活动报道</a>
					</li>
					<li>
						<a href="javascript:void(0);">报名网友</a>
					</li>
				</ul>
				<div class="more" id="iscrollto">
					<!-- <span></span> -->
				</div>
			</div>
			<div class="active_intro" style="margin-top:0; border-top:0 none;">
				<div class="tab-cont" style="display: block;">
					<div class="con" id="resizeIMG">
						{pigcms{$activityInfo.introduction|htmlspecialchars_decode}
					</div>
				</div>
				<div class="tab-cont" style="display: none;">
					<div class="con" id="resizeIMG2">
						{pigcms{$activityInfo.sponsor|htmlspecialchars_decode}
					</div>
				</div>
				<div class="tab-cont" style="display: none;">
					<div class="con" id="resizeIMG3">
						{pigcms{$activityInfo.report|htmlspecialchars_decode}
					</div>
				</div>
				<div class="tab-cont" style="display: none;">
					<div class="con baomingUser">
						<ul class="clearfix">
							<volist name="activitySignList" id="voList">
								<li class="item">
									<ul>
										<li class="lia">
											<if condition="$voList['uid'] eq 0">
                                                <img src="{pigcms{$static_path}portal/images/user_small.gif" alt="" />
                                            <elseif condition = "$voList['avatar'] eq ''"/>
                                                <img src="{pigcms{$static_path}portal/images/user_small.gif" alt="" />
											<else/>
												<img src="{pigcms{$voList.avatar}" alt="" />
											</if>
										</li>
										<li class="lib"> <em>会员：</em> {pigcms{$voList.nickname} </li>
										<!-- <li class="lic">报名时间：2015/5/8 21:25:17</li> -->
									</ul>
								</li>
							</volist>
						</ul>
					</div>
				</div>
			<div class="d_more current">展开更多<em></em></div>
		</div>
		<!--网友评论-->
		<div class="user_reviews">
			<div class="title">
				<span>网友评论</span>
				<div class="ComentNum" id="show_total_revert">{pigcms{$recommentCount}</div>
			</div>
			<div id="">
			<!-- showcomment -->
				<div id="total_revert" data-num="{pigcms{$recommentCount}">

					<if condition="$recommentList">
						<volist name="recommentList" id="vo">
							<div class="comment_item">
								<div class="comment_face">
                                    <if condition="$vo['avatar'] eq ''">
                                        <img src="{pigcms{$static_path}portal/images/user_small.gif" alt="" />
                                    <else/>
                                        <img src="{pigcms{$vo.avatar}" alt="">
                                    </if>

                                </div>
								<div class="coment_box">
									<div class="comment_user clearfix">
										<span class="userName">{pigcms{$vo.nickname}</span>
										<p class="date">{pigcms{$vo.dateline|date="Y/m/d H:i:s",###}</p>
									</div>
									<p class="comment_content">{pigcms{$vo.msg}</p>
								</div>
							</div>
						</volist>
					<else/>
						<div style="text-align: center; padding: 15px; font-size: 18px;">暂无评论</div>
					</if>
				</div>
			</div>
		</div>

</div>



</div>

<!-- 分享 -->
<include file="Portal:fenxiang"/>

<div class="reply_box page_srcoll" id="pageOther">
	<div class="inner">
		<span class="title">
			<span id="replyName">发表评论</span>
		</span>
		<div class="return_close" id="closeReply">返回</div>
		<div class="cmt_txt2" id="cmt_txt" placeholder="想说点什么~" contenteditable="true"></div>
		<input type="submit" onclick="recomment()" class="rsubmit" value="发表">
	</div>
</div>

<script>
	function recomment(){
		var uid = "{pigcms{$user_session['uid']}";
		var msg = $("#cmt_txt").html();
		var target_id = "{pigcms{$activityInfo.a_id}";
		var recommentUrl = "{pigcms{:U('Portal/recomment')}";
		if(!uid){
			layer.open({
				content: '请先登录'
				,skin: 'msg'
				,time: 2  
			});
			return false;
		}
		if(!msg){
			layer.open({
				content: '请输入评论内容！'
				,skin: 'msg'
				,time: 2  
			});
			return false;
		}
		if(!target_id){
			layer.open({
				content: '数据异常！'
				,skin: 'msg'
				,time: 2  
			});
			return false;
		}
		
		$.post(recommentUrl,{'target_id':target_id,'msg':msg},function(data){
			if(data.error == 1){
				layer.open({
					content: data.msg
					,btn: ['确定']
					,yes: function(index){
						location.reload();
					}
				});
			}else{
				layer.open({
				    content: data.msg
				    ,skin: 'msg'
				    ,time: 2 
			  	});
			}
		},'json')
	}
</script>

<div class="footFixed">
<div class="reply_hd clearfix" id="reply_hd">
	<ul>
		<li class="p20"> <span class="share" id="share2015">分享</span> </li>
		<li class="p20"> <span class="num" id="show_total_revert1">{pigcms{$recommentCount}</span> </li>
		<li class="p30"> <a <if condition="$activityInfo['over_time'] lt 0">style="background-color: #a4a4a4;"</if>  href="javascript:void(0);" class="baoming" onclick="return showwybaoming();">我要报名</a> </li>
		<li class="p30"> <a href="#" id="openReply" class="btn">写评论</a> </li>
	</ul>
</div>
</div>
<script src="{pigcms{$static_path}portal/js/iscroll-probe.js"></script>
<script src="{pigcms{$static_path}portal/js/jquery.form.js"></script>
<script src="{pigcms{$static_path}portal/js/bootstrap.min.js"></script>
<script src="{pigcms{$static_path}portal/js/cropper.min.js"></script>
<script src="{pigcms{$static_path}portal/js/scrollHe.js"></script>
<script src="{pigcms{$static_path}portal/js/wap_upimgOne.js"></script>
<script src="{pigcms{$static_path}portal/js/emotData.js"></script>
<script src="{pigcms{$static_path}portal/js/wap_comments_2017.js"></script>
<script src="{pigcms{$static_path}portal/js/wap_common.js"></script>
 


<style>
	.btn_2{
		    display: block;
		    width: 100%;
		    color: #fff;
		    background-color: #fb9031;
		    text-align: center;
		    line-height: 50px;
		    height: 50px;
		    border: 0 none;
		    outline: 0;
	}
</style>
<script>


window['ACTIVEID'] = '{pigcms{$activityInfo.a_id}';

(function($){
	$('#scroller2').css('width',(90*$('#scroller2').find('li').length)+40+'px'); 
	var myScroll = new IScroll('#wrapper2', {
		scrollX: true,
		scrollY: false,
		click:true,
		keyBindings: true
	});
	
	$('#iscrollto').click(function(){
		myScroll.scrollBy(-100,0,500)
	});
	IDC.resizeIMG(document.getElementById('resizeIMG'),parseInt($(window).width())-40,480);
	IDC.resizeIMG(document.getElementById('resizeIMG2'),parseInt($(window).width())-40,480);
	IDC.resizeIMG(document.getElementById('resizeIMG3'),parseInt($(window).width())-40,480);
	
	
	$('#share2015').share2015();
})(jQuery);


function showwybaoming(){

	//页面层
	layer.open({
		type: 1
		,content: '<div class="register-form" style="margin:0; padding-top:10px;"><p class="box"><span class="form_label">真实姓名：</span><span class="form_control"><input type="text" placeholder="请输入" name="truename" id="truename" value="" class="codebox2-input"></span></p> <p class="box"><span class="form_label">联系电话：</span><span class="form_control"><input type="text" placeholder="请输入" name="phone" id="phone" class="codebox2-input"></span></p> <p class="box"><span class="form_label">联系QQ：</span><span class="form_control"><input type="text" placeholder="请输入" name="qq" id="qq" value="" class="codebox2-input"></span></p>  <p class="box"><span class="form_label">简短附言：</span><span class="form_control"><textarea name="message" id="message" class="codebox2-textarea" rows="2" data-max="800" placeholder="请输入您的备注信息"></textarea></span></p> <ul class="ul"> <li><input class="btn_2" type="submit" onclick="checkbaoming()" value="提交报名"></li> <li><input class="btn_2" style="background-color: #515151;" onclick="layer.closeAll();" type="button" value="取消"></li> </ul> </div>'
		,anim: 'up'
		,shadeClose: true
		,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 300px; padding:10px 0; border:none;'
	});


	
}

function checkbaoming(){

	var truename = $("#truename").val();
    var qq = $("#qq").val();
    var phone = $("#phone").val();
    var message = $("#message").val();
    var a_id = "{pigcms{$_GET['a_id']}";

	if("{pigcms{$activityInfo['over_time']}" < 0 ){
		layer.open({
			content: '活动已经结束了！'
			,skin: 'msg'
			,time: 2 
		});
		layer.closeAll();
		return false;
	}

	if($("#truename").val()==""){
		layer.open({
			content: '请填写您的真实姓名！'
			,skin: 'msg'
			,time: 2 
		});
		return false;
	}

	if($("#truename").val().length<2){
		layer.open({
			content: '请正确填写您的真实姓名'
			,skin: 'msg'
			,time: 2 
		});
		return false;
	}	

	if($("#phone").val()==""){
		layer.open({
			content: '请填写您的联系电话'
			,skin: 'msg'
			,time: 2 
		});
		return false;
	}
    
    var baomingUrl = "{pigcms{:U('Portal/activity_baoming')}";
    $.post(baomingUrl,{'truename':truename,'qq':qq,'phone':phone,'message':message,'a_id':a_id},function(data){
    	if(data.error == 1){
    		layer.open({
				content: data.msg
				,skin: 'msg'
				,time: 2 
			});
    		self_baoming();
            $("#scroller2").find("li").eq(3).addClass("select").siblings().removeClass("select");
            $(".tab-cont").eq(3).show().siblings(".tab-cont").hide();;
    		layer.closeAll();
    	}else{
    		// parent.layer.msg(data.msg);
            layer.open({
                content: data.msg
                ,skin: 'msg'
                ,time: 2
            });
    	}
    },'json');
	  
	
}

var ind_a = 0;
var tab_cont = $(".tab-cont");
window.onload = function(){
	tab_cont.each(function(){
		if($(this).height() > 300){
			$(this).css({"height":300});
		}
	});
	tab_cont.eq(0).show();
}
$("#scroller2 li").bind("click",function(){
  ind_a = $(this).index();
  var tabH = $('.tab-cont').eq(ind_a).height();
  $(this).addClass("select").siblings().removeClass("select");
  tab_cont.eq(ind_a).show().siblings(".tab-cont").hide();
  if(tabH == 300){
	 $(".d_more").removeClass("current"); 
  }else{
	  $(".d_more").addClass("current");
  }
});

$('.d_more').on('click',function(){
	var hCur = $(this).hasClass("current");	
	if(!hCur){
		$(this).addClass("current");
		$('.tab-cont').eq(ind_a).css({"height":"auto"});	
		}	
	else{
		$(this).removeClass("current");
		$('.tab-cont').eq(ind_a).css({"height":300});	
		}	
 });
 

</script>
{pigcms{$shareScript}
<script type="text/javascript">
	window.shareData = {
		"moduleName":"Home",
		"moduleID":"0",
		"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Portal/activity_detail',array('a_id'=>$_GET['a_id']))}",
		"tTitle": "同城活动 - {pigcms{$activityInfo.title}",
		"tContent": "{pigcms{$activityInfo.title}"
	};
</script>
<script>
    function self_baoming(){
        var self_nickname = '{pigcms{$user_session.nickname}';
        var self_avatar = '{pigcms{$user_session.avatar}';
        if(self_nickname == ''){
            self_nickname = '游客';
        }
        if(self_avatar == ''){
            self_avatar = '{pigcms{$static_path}images/new_my/pic-default.png';
        }
        $('.con.baomingUser .clearfix').prepend('<li class="item"><ul><li class="lia"><img src='+ self_avatar +' alt=""></li><li class="lib">'+self_nickname+'</li></ul></li>');
        var num = $('#signup').text();
        num = parseInt(num);
        $('#signup').text(num+1);
    }
</script>
</body>
</html>