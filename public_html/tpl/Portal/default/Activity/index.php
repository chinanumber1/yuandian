<include file="Public/header" />
<link href="{pigcms{$static_path}activity/css/tghd2014.css" type="text/css" rel="stylesheet" />
<script src="{pigcms{$static_path}activity/js/common.js"></script>
<script src="{pigcms{$static_public}js/layer/layer.js"></script>
<div class="content w-1200 clearfix">
	<div class="site_crumb clearfix">
		<span>当前位置：</span>
		<a href="{pigcms{:U('Portal/Index/index')}">首页</a>
		<span class="cur_tit">同城活动</span>
	</div>
	<!-- 主体 -->
	<div id="content" class="v2013 tuanHD">
		<div class="tuangou_nav">
			<div class="hd clearfix">
				<ul class="tab">
					<li class="quanbu">
						<a href="{pigcms{:U('Activity/index')}">全部活动</a>
					</li>
					<volist name="activityCatgoryList" id="vo">
						<li <if condition="$_GET['cid'] eq $vo['cid']">class="current"</if>>
							<a href="{pigcms{:U('Activity/index',array('cid'=>$vo['cid']))}">{pigcms{$vo.cat_name}</a>
						</li>
					</volist>
				</ul>
			</div>
		</div>
		<div class="list">
			<div class="inner clearfix">
				<if condition="is_array($activityList)">
					<volist name="activityList" id="vo">
						<div class="item">
							<s class="po po_{pigcms{$vo.state}"><if condition="$vo['state'] eq 2">正在召集<elseif condition="$vo['state'] eq 3" />即将组团<elseif condition="$vo['state'] eq 5"/>活动结束</if></s>
							<p class="img">
								<a href="{pigcms{:U('Activity/detail',array('a_id'=>$vo['a_id']))}" target="_blank">
									<img src="{pigcms{$config.site_url}/upload/portal/{pigcms{$vo.pic}" alt="" />
								</a>
							</p>
							<div class="po_re">
								<div class="title"> <a href="{pigcms{:U('Activity/detail',array('a_id'=>$vo['a_id']))}" target="_blank">{pigcms{$vo.title}</a> </div>
								<div class="center"> <span class="suc_active" data-id="33">{pigcms{$vo.already_sign_up}</span> 人已报 </div>
							</div>
							<div class="txt">
								<p class="time"> 活动时间： <em>{pigcms{$vo.time}</em> </p>
								<p class="time"> 活动地点： <em>{pigcms{$vo.place}</em> </p>
								<p class="time"> 活动费用： <em>{pigcms{$vo.price}</em> </p>
								<p class="time"> 活动名额： <em><if condition="$vo['number'] eq 0">不限<else/>{pigcms{$vo.number}</if> ({pigcms{$vo.enroll_time|date="Y/m/d H:i:s",###}前报名)</em> </p>
								<p class="menu">
									<if condition="$vo['state'] neq 5">
										<a href="javascript:void(0);" onclick="baoming({pigcms{$vo.a_id});" class="sys_btn">报名参加</a>
									</if>
									<a href="{pigcms{:U('Activity/detail',array('a_id'=>$vo['a_id']))}" target="_blank"  class="sys_btn sys_btn_gray">活动详情</a>
								</p>
							</div>
						</div>
					</volist>
					<else/>
					<div style=" font-size:18px; text-align:center; padding:100px 0;" id="listEmpty">没有找到符合条件的信息</div>
				</if>
			</div>
			<div class="pageNavigation">
				{pigcms{$pagebar}
			</div>
		</div>
	</div>
	<!-- 主体 结束 -->
</div>
<script>
	function baoming(a_id){
		var uid = "{pigcms{$user_session['uid']}";
		if(!uid){
			layer.msg('请先登录然后进行报名');
			return false;
		}
		layer.open({
			type: 2,
			title:'',
			area: ['590px', '460px'],
			content: "{pigcms{:U('Activity/baoming')}&a_id="+a_id
		});
	}
</script>

<include file="Public/footer" />
