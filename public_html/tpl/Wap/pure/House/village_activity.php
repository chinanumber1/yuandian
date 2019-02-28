<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>活动详情</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?213"/>
<link href="{pigcms{$static_path}css/css_whir2.css" rel="stylesheet"/>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_path}js/swiper-3.3.1.jquery.min.js"></script>
<!--[if lte IE 9]>
<script src="{pigcms{$static_path}js/html5shiv.min.js"></script>
<![endif]-->
<style type="text/css">
.sign_bottom a.gray{ background:#ccc}
</style>

</head>
<body>
	<if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>活动详情</header>
    </if>
    <div class="details">
      <div class="swiper-container swiper-container4">
        <div class="swiper-wrapper">
			<volist name='now_activity["pic"]' id="pic">
				<div class="swiper-slide"><img style="width:100%;height:150px;" src="/upload/activity/{pigcms{$pic}"></div>
			</volist>
        </div>
        <div class="swiper-pagination swiper-pagination4"></div>
      </div>
      <div class="Eventbt">
        <h2>{pigcms{$now_activity["title"]}</h2>
        <p class="activity_money_icon" style="font-size:14px;">报名费用： <span class="pigcms-text-danger">{pigcms{$now_activity.apply_fee}</span> 元</p>
        <div class="Eventbt_end clr">
          <span class="fl">截止日期：{pigcms{$now_activity["apply_end_time"]|date='Y-m-d',###}</span>
          <span class="fr">已报名 <em class="pigcms-text-danger"><if condition='$now_activity["now_activity_apply_sum"] gt 0'>{pigcms{$now_activity["now_activity_apply_sum"]}<else />0</if></em> 人</span>
        </div>
      </div>
    </div>

  <div class="introduce">
    <div class="Popular">
        <div class="top">
          <span>活动介绍</span>
        </div>
    </div>
    <div class="p30">
       {pigcms{$now_activity["content"]}
    </div>
     
  </div>
  

  <div class="sign_bottom">
	<if condition='time() lt ($now_activity["apply_end_time"] + 86400)'>
      <if condition='$exist_activity gt 0 && $now_activity["is_repeat_join"] eq 0'>
        <a href="javascript:void(0)" class="gray">已报名</a>
      <else/>
        <a href="{pigcms{:U('village_activityapply',array('activity_id'=>$_GET['id'],'village_id'=>$_GET['village_id']))}">我要报名</a>
      </if>
	 <else />
	 <a href="javascript:void(0)" class="gray">报名已截止</a>
	 </if>
  </div>
</body> 

 <script type="text/javascript">
 var myswiper4 = new Swiper('.swiper-container4', {
      pagination: '.swiper-pagination4',
      direction : 'horizontal',
      paginationClickable :true,
      autoplay :'4000',
      autoplayDisableOnInteraction : false,
      paginationType : 'fraction',
      loop: true
    });
	
	
	 $(function(){
		$('#backBtn').click(function(){
			history.back(-1);
		});
		
	});
 </script>

</html>



