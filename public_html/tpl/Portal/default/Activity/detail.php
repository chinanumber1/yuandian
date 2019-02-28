<include file="Public/header" />
<script src="{pigcms{$static_public}js/layer/layer.js"></script>
<link href="{pigcms{$static_path}activity/css/tghd2014.css" rel="stylesheet" />
<div class="content w-1200 clearfix">
	<div class="site_crumb clearfix">
		<span>当前位置：</span>
		<a href="">首页</a>
		<a href="{pigcms{:U('Activity/index')}">同城活动</a>
		<span class="cur_tit">{pigcms{:msubstr($activityInfo['title'],0,30)}</span>
	</div>
	<div id="content">
		<div class="pageHeader clearfix">
			<div class="title">{pigcms{:msubstr($activityInfo['title'],0,30)}</div>
			<div class="num">
				<span id="suc_num">{pigcms{$activityInfo.already_sign_up}</span> 人已报 </div>
		</div>
		<div class="grid_tghd_2014 clearfix" data-styleid="网友自发">
			<div class="col_main">
				<div class="main_wrap">
					<div class="txt_box clearfix">
						<span class="tz"> <strong>带队队长：</strong> <em class="yellow">{pigcms{$activityInfo.leader}</em> </span>
						<if condition="$activityInfo['over_time'] lt 0">
							<span class="bm"><span class="yellow" id="timenum"> 活动已经结束了 </span></span>
						<else/>
							<span class="bm"> 距报名截止还有 <span class="yellow" id="timenum">{pigcms{$activityInfo.over_time}</span> 天 </span>
						</if>
					</div>
					<div class="slide" style="height: 210px;">
						<img src="{pigcms{$config.site_url}/upload/portal/{pigcms{$activityInfo.pic}" alt="" />
						<s class="sk sk_{pigcms{$activityInfo.state}" data-iskill="2"><if condition="$activityInfo['state'] eq 2">正在召集<elseif condition="$activityInfo['state'] eq 3" />即将组团<elseif condition="$activityInfo['state'] eq 5"/>活动结束</if></s>
					</div>
					<div class="blank10"></div>
					<div class="txt_box">
						<p> <strong>活动地点：</strong> {pigcms{$activityInfo.place} </p>
						<p> <strong>活动时间：</strong> {pigcms{$activityInfo.time} </p>
						<p> <strong>活动费用：</strong> {pigcms{$activityInfo.price} </p>
						<p> <strong>活动名额：</strong> <if condition="$activityInfo['number'] eq 0">不限<else/>{pigcms{$activityInfo.number}</if>({pigcms{$activityInfo.enroll_time|date="Y/m/d H:i:s",###}前报名) </p>
					</div>
				</div>
			</div>
			<div class="col_sub">
				<div class="module_c">
					<div class="hd">
						<span class="title yahei">最新报名</span>
					</div>
					<div class="bd userBM">
						<ul class="clearfix">
							<volist name="activitySignList" id="vo">
								<li>
									<if condition="$vo['avatar'] eq ''">
										<img src="{pigcms{$static_path}activity/images/user_small.gif" alt="" />
									<else/>
										<img src="{pigcms{$vo.avatar}" alt="" />
									</if>
									<span class="username">{pigcms{$vo.nickname}</span>
								</li>
							</volist>
							<li>
								<img src="{pigcms{$static_path}images/qidainin.png" alt="">
								<span class="username">期待您..</span>
							</li>
						</ul>
					</div>
				</div>
				<div class="shop_btn">
					<if condition="$activityInfo['over_time'] gt 0">
						<a href="javascript:void(0);" onclick="baoming({pigcms{$activityInfo.a_id});" class="sys_btn">报名参加</a>
					</if>
					<a href="#myform" onClick="showComment();" class="sys_btn sys_btn_gray">参加讨论</a>
				</div>
			</div>
		</div>
		<div class="tg_box_s">
			<div class="hd clearfix">
				<ul class="tab">
					<li class="selected"><a href="#" onClick="Show_TabADSMenu(1,0,5);return false;" id="tabadmenu_10">活动介绍</a></li>
					<li><a href="#" onClick="Show_TabADSMenu(1,1,5);return false;" id="tabadmenu_11">活动赞助商</a></li>
					<li><a href="#" onClick="Show_TabADSMenu(1,2,5);return false;" id="tabadmenu_12">活动报道</a></li>
					<li><a href="#" onClick="Show_TabADSMenu(1,3,5);return false;" id="tabadmenu_13">报名网友</a></li>
					<li><a href="#" onClick="Show_TabADSMenu(1,4,5);return false;" id="tabadmenu_14">网友评论</a></li>
				</ul>
			</div>
			<div class="bd hd_xiangqing" id="tabadcontent_10">
				{pigcms{$activityInfo.introduction|htmlspecialchars_decode}
			</div>
			<div class="bd" style="display:none;" id="tabadcontent_11">
				{pigcms{$activityInfo.sponsor|htmlspecialchars_decode}
			</div>
			<div class="bd" style="display:none;" id="tabadcontent_12">
				{pigcms{$activityInfo.report|htmlspecialchars_decode}
			</div>

			<div class="bd userBM" style="display:none;" id="tabadcontent_13">
				<ul class="clearfix">
					<volist name="activitySignList" id="voList">
						<li>
							<if condition="$voList['avatar'] eq ''">
								<img src="{pigcms{$static_path}activity/images/user_small.gif" alt="" />
							<else/>
								<img src="{pigcms{$voList.avatar}" alt="" />
							</if>
							<span class="username">{pigcms{$voList.nickname}</span>
						</li>
					</volist>
				</ul>
			</div>

			<div class="bd" id="tabadcontent_14" style="display:none;">
				<div class="comment">
					<div class="inner">
						<div class="hds clearfix">
							<h6 class="left">全部评论</h6>
							<p class="right"> <em id="show_total_revert">{pigcms{$recommentCount}</em>
								条评论
							</p>
						</div>

						<volist name="recommentList" id="vo">
							<div class="comment_item">
								<div class="comment_face"><img src="{pigcms{$vo.avatar}" alt=""></div>
								<div class="comment_box">
									<div class="comment_user clearfix"><span class="right">{pigcms{$vo.dateline|date="Y/m/d H:i:s",###}</span> <span class="userName">{pigcms{$vo.nickname}</span></div>
									<p class="comment_content">{pigcms{$vo.msg}</p>
					
								</div>
							</div>
						</volist>

						

						

					</div>
					<div class="write2014">
						<div class="cmt_txt" id="cmt_txt" contenteditable="true"></div>
						<div class="cmt_control clearfix">
							<div class="right">
								<button type="submit" id="cmt_btn" onclick="cmtBtn()" class="cmt_btn">提交</button>
							</div>
						</div>
					</div>
					<script>
						function cmtBtn(){
							var cmtUrl = "{pigcms{:U('Activity/recomment')}";
							var uid = "{pigcms{$user_session['uid']}";
							if(!uid){
								layer.alert('请先登录');
								location.href("http://hf.group.com/index.php?g=Index&c=Login&a=index");
								return false;
							}
							var msg = $("#cmt_txt").html();
							if(!msg){
								return false;
							}
							var target_id = "{pigcms{$_GET['a_id']}";
							if(!target_id){
								return false;
							}

							$.post(cmtUrl,{'target_id':target_id,'msg':msg},function(data){
								if(data.error == 1){

									layer.msg(data.msg, {time: 500},function(){
										window.location.href  = "{pigcms{:U('detail',array('a_id'=>$_GET['a_id']))}";
									})

								}else{
									layer.msg(data.msg);
								}
							},'json')

						}
					</script>
				</div>
			</div>
		</div>
</div>
</div>

<div id="mask" style="display:none;"></div>
<div class="fixed_menu" id="fixed_menu">
	<ul>
		<if condition="$activityInfo['over_time'] gt 0">
			<li class="li_1">
				<a href="javascript:void(0);" onclick="baoming({pigcms{$activityInfo.a_id});" >在线报名</a>
			</li>
		</if>
		<li class="li_5" id="top">
			<a href="#">返回顶部</a>
		</li>
	</ul>
</div>
<include file="Public/footer"/>
<script>
	(function($){
		$('#fixed_menu').rmenuShow2016();
	})(jQuery);

	function showComment(){
		$('#tabadmenu_14').trigger('click');
	}

	function baoming(a_id){
		var uid = "{pigcms{$user_session['uid']}";
		if(!uid){
			// layer.msg('请先登录');
            location.href="http://hf.group.com/index.php?g=Index&c=Login&a=index";
			return false;
		}
		layer.open({
			type: 2,
			title:'',
			area: ['590px', '460px'],
			content: "{pigcms{:U('Activity/baoming')}&a_id="+a_id
		});
	}

    function self_baoming(){
	    var self_nickname = '{pigcms{$user_session.nickname}';
	    var self_avatar = '{pigcms{$user_session.avatar}';
	    if(self_avatar == ''){
            self_avatar = '{pigcms{$static_path}activity/images/user_small.gif';
	    }
	    $('.userBM ul').prepend('<li><img src="'+self_avatar+'" alt=""><span class="username">'+self_nickname+'</span></li>');
	    var num = $('#suc_num').text();
	    num = parseInt(num);
	    $('#suc_num').text(num+1);
    }
</script>
