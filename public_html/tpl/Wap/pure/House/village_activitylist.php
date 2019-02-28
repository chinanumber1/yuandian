<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>社区活动</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?213"/>
<link href="{pigcms{$static_path}css/css_whir2.css" rel="stylesheet"/>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>

<!--[if lte IE 9]>
<script src="{pigcms{$static_path}scripts/html5shiv.min.js"></script>
<![endif]-->
<style type="text/css">
body{ background:#fff}
.activities .activities_end a.gray{ background:#ccc}

</style>
</head>
<body>
<if condition="!$is_app_browser">
    <header class="pageSliderHide"><div id="backBtn"></div>社区活动</header>
</if>	
<div class="Hfarea hfjd_hd">
   <ul class="clr red">
      <li class="on" >正在进行</li>
      <li>往期活动</li>
   </ul>
</div>
 <div class="Description activities activities-default">
      <dl class="activities-dl">
	  <if condition='$activity_list["running_activity"]'>
	  <volist name='activity_list["running_activity"]' id="running_activity">
			<dd class="activities-dd" onclick="location.href='{pigcms{:U('village_activity',array('village_id'=>$_GET['village_id'],'id'=>$running_activity['id']))}'">
				<div class="activities_top clr">
					<div class="activities_left fl"><img src="{pigcms{$config.site_url}/upload/activity/{pigcms{$running_activity['pic'][0]}"><span class="xj"></span></div>
					<div class="activities_right">
						<h3>{pigcms{$running_activity['title']}</h3>
						<p>{pigcms{$running_activity['content']|strip_tags}</p>
					</div>
				</div>
				<div class="activities_end clr">
				<ul>
					<li class="on">开始时间：<if condition='($running_activity.stop_apply_time) gt 0'>{pigcms{$running_activity.apply_end_time|date='Y-m-d',###}<else />{pigcms{$running_activity.activity_start_time|date='Y-m-d',###}</if></li>
					<li class="add">已报名 <span class="pigcms-text-danger">{pigcms{$running_activity.now_apply_sum}</span> 人</li>
					
				</ul>
				
				<if condition='$running_activity["apply_fee"] neq 0.00'>
					<ul>
						<li>报名费用：{pigcms{$running_activity.apply_fee}元</li>			
					</ul>
				</if>
				
					<if condition='time() lt $running_activity["apply_end_time"]+86400'>
						<a href="javascript:void(0)">我要报名</a>
					<else />
						<a href="javascript:void(0)" class="gray">已截止</a>
					</if>
				</div>   
			</dd>
		</volist>
		<else />
			<dd class="activities-dd" >
				<div class="activities_top_s clr" >
					<img src="{pigcms{$static_path}/images/bg_222.png" />
				</div>
			</dd>
		</if>
      </dl>
  </div>
  <div class="Description activities activities-default" style="display: none;">
      <dl class="activities-dl">
	  <if condition='$activity_list["stop_activity"]'>
	  <volist name='activity_list["stop_activity"]' id="stop_activity">
		<dd class="activities-dd" onclick="location.href='{pigcms{:U('village_activity',array('village_id'=>$_GET['village_id'],'id'=>$stop_activity['id']))}'">
			<div class="activities_top clr">
				<div class="activities_left fl"><img src="{pigcms{$config.site_url}/upload/activity/{pigcms{$stop_activity['pic'][0]}"><span class="ks"></span></div>
				<div class="activities_right">
					<h3>{pigcms{$stop_activity['title']}</h3>
					<p>{pigcms{$stop_activity['content']|strip_tags}</p>
				</div>
			</div>
			<div class="activities_end clr">
				<ul>
					<li class="on">{pigcms{$stop_activity.activity_start_time|date='Y-m-d',###}</li>
					<li class="add">已报名 <span class="pigcms-text-danger">{pigcms{$stop_activity.now_apply_sum}</span> 人</li>
				</ul>
					<a href="javascript:void(0)" class="hui">已结束</a>
			</div>   
		</dd>
		</volist>
		<else />
			<dd class="activities-dd" >
				<div class="activities_top_s clr">
					<img src="{pigcms{$static_path}/images/bg_222.png" />
				</div>
			</dd>
		</if>
		</if>
      </dl>
  </div>
 
</body> 

 <script type="text/javascript">
   $(function(){
    $(".Hfarea li").click(function(){
    var index=$(this).index()
    $(this).addClass("on").siblings().removeClass("on");
    $(".Description").eq(index).show().siblings(".Description").hide();
    })

 })
 
 $(function(){
	$('#backBtn').click(function(){
		history.back(-1);
	});
	
});
  </script>


</html>



