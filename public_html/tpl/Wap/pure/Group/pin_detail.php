<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{pigcms{$config.group_alias_name}详情</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-touch-fullscreen" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<meta name="format-detection" content="telephone=no"/>
<meta name="format-detection" content="address=no"/>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
<script type="text/javascript"><if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if></script>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/pin_group.css?2102"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/detail.css?213"/>
<script type="text/javascript" src="{pigcms{$static_path}js/swiper-3.3.1.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
<script type="text/javascript" src="{pigcms{$static_path}js/pin_detail.js?216" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->

<style type="text/css">
  .details{ position: relative; }
  .collection{ position: absolute; width: 35px; height: 35px; border-radius: 100%; background: url({pigcms{$static_path}images/3.png) center no-repeat; z-index: 9999; top:10px; right: 10px; }
  .collection:after{ display: block;  content: ''; width: 16px; height: 16px; background: url({pigcms{$static_path}images/2.png) center no-repeat; background-size: 16px; position: absolute; ); top: 50%; left: 50%;  margin: -8px; }
  .collection.on:after{ background: url({pigcms{$static_path}images/1.png) center no-repeat; background-size: 16px; }
</style>

<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>


<script type="text/javascript">
    $(function(){
        $(".collection").click(function(){
            var uid = "{pigcms{$user_session['uid']}";
            if(!uid){
                layer.open({content:'请先登录再进行收藏！',btn: ['确定'],end:function(){location.href="{pigcms{:U('Login/index')}";}});
                return false;
            }

            if($(this).hasClass("on")){
                $(this).removeClass("on");
                $.post('{pigcms{:U('My/ajax_group_collect')}', {id: {pigcms{$_GET['group_id']},type:'group_detail',action:'del'}, function(data, textStatus, xhr) {
                    layer.open({
                        content: data.info
                        ,skin: 'msg'
                        ,anim: 'up'
                        ,time: 2 //2秒后自动关闭
                    });
                });
            }else{
                $(this).addClass("on");
                $.post('{pigcms{:U('My/ajax_group_collect')}', {id: {pigcms{$_GET['group_id']},type:'group_detail',action:'add'}, function(data, textStatus, xhr) {
                    layer.open({
                        content: data.info
                        ,skin: 'msg'
                        ,anim: 'up'
                        ,time: 2 //2秒后自动关闭
                    });
                });
            }
        })
    })
</script>

</head>

<body>
   <section class="details">
   		<div class="swiper-container">
			<div class="swiper-wrapper imgBox">
			
				<volist name="now_group['img_arr']" id="vo">
				 <div class="swiper-slide" >
					  <img src="{pigcms{$vo}">
				 </div>
				</volist>
			</div> 
			<div class="swiper-pagination"></div>  
    	</div>
      <div class="collection <if condition="$is_collect">on</if>"></div>
   </section>

   <section class="details_h">
   	 	<h2>{pigcms{$now_group.group_name}</h2>
   	 	<p>{pigcms{$now_group.intro}</p>
   	 	<div class="details_Price clr">
   	 		<span class="details_Price_l"><i>￥</i><em>{pigcms{$now_group.price}<if condition="$config.open_extra_price eq 1 AND $now_group.extra_pay_price gt 0">+{pigcms{$now_group.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if></em></span>
   	 		<span class="details_Price_r">该团拼团时限为{pigcms{$now_group.pin_effective_time}小时</span>
        <!-- 距结束 隐藏-->
        <span class="details_Price_r details_Price_r_dis" style="display: none;">距结束  
          <em>    
             <span id="day_show1" style="display: none;">0天</span>
             <strong id="hour_show1"><s id="h1"></s>00</strong> : <strong id="minute_show1"><s></s>00</strong> : <strong id="second_show1"><s></s>00</strong>
           </em>
        </span>
   	 	</div>
   </section>
	<if condition="$now_group['no_refund'] eq 1">
		<section class="details_Group">
			*  该{pigcms{$config.group_alias_name}为抢购商品，支付后不可退款
	   </section>
	<else />
	   <section class="details_Group">
			*  开团支付成功后邀请<span>{pigcms{$now_group['pin_num']-1}人</span>参团，人数不足自动退款
	   </section>
   </if>

   
		<php> if($group_share_info){</php> 
	   <section class="details_group">
		<div class="details_group_top">
   			<div class="margin_group_top">参加别人的团,买买买</div>
   		</div>
   		<div class="details_group_end">
       <a href="{pigcms{:U('Group/buy',array('group_id'=>$now_group['group_id'],'type'=>2,'gid'=>$_GET['gid']))}" class="clr">
	  
			<if condition="$group_share_info['avatar']">
   		   <div class="group_left" style="position:relative;">
    		   <!-- <img src="{pigcms{$static_path}img/group_head.png" style="position: absolute;width: 36px;margin-left: -10px;height: 18px;" /> -->
         
    		   <img src="<if condition="$group_share_info['avatar'] neq ''">{pigcms{$group_share_info.avatar}<else />{pigcms{$static_public}img/images/nohead.png</if>">
		   
		   </div>
		   </if>
         <div class="group_zh">
           <!--div class="group_zh_top clr">
             <i>{pigcms{$group_share_info['nickname']}</i>
             <em>剩余时间 
             <strong id="hour_show"></strong></em>
           </div>
           <div class="group_zh_end clr">
             <i>￥<span>{pigcms{$now_group.price}</span></i>
             <em>差{pigcms{$group_share_info['complete_num']-$group_share_info['num']}人成团，点击参团</em>
           </div-->
		   
		   <div class="group_zh_end clr" id="join_group" data-end_time="{pigcms{$group_share_info.end_time}" > 
             <em style=";float:left">{pigcms{$group_share_info.nickname} 邀您参团,差{pigcms{$group_share_info['complete_num']-$group_share_info['num']}人成团</em>
             <i style="font-size:18px;margin-top:11px;float:right">￥<span style="font-size:18px">{pigcms{$now_group.price}</span></i><br>
			 <em style="float: left;font-size: 10px;color: #f3a9a5;">剩余<strong id="hour_show"></strong></em>
           </div>
         </div>
         <div class="group_right">立即<br>参团</div>
       </a> 
   		</div>
	   </section>
			 <php>}else if($can_join){</php>
		
		<section class="details_group">
		<div class="details_group_top">
   			<div class="margin_group_top">{pigcms{:count($can_join)}人在开团，参加别人的团买买买</div>
   		</div>
		  <!--div class="details_group_end_dis">
		   <a href="{pigcms{:U('Group/buy',array('group_id'=>$now_group['group_id'],'type'=>2))}" class="clr">
			 <div class="group_end2_left">￥<i>{pigcms{$now_group.price}</i></div>
			 <div class="group_end2_right">差{pigcms{$now_group['pin_need_num']}人成团，立即参团</div>
		   </a> 
		  </div-->	
			<volist name="can_join" id="vo">
			 <div class="details_group_end pin_list" id="pin_group_{pigcms{$i}" data-end_time="{pigcms{$vo.end_time}"  >
				<div class="group_left" style="position:relative;">
				   <!-- <img src="{pigcms{$static_path}img/group_head.png" style="position: absolute;width: 36px;margin-left: -10px;height: 16px;" /> -->
			
				   <img src="<if condition="$vo['avatar'] neq ''">{pigcms{$vo.avatar}<else />{pigcms{$static_public}img/images/nohead.png</if>">
			   </div>
				<a href="{pigcms{:U('Group/buy',array('group_id'=>$vo['group_id'],'gid'=>$vo['id']))}" class="clr">
					<div class="group_zh">
						<div class="group_zh_end clr">
							<em style="font-size: 12px;float:left;font-weight: bold;">差{pigcms{$vo['complete_num']-$vo['num']}人成团,剩余<strong id="hour_show_{pigcms{$i}"></strong></em>
							<i style="font-size:14px;margin-top:11px;float:right">￥<span style="font-size:14px">{pigcms{$now_group.price}</span></i><br>
							<em style="float: left;font-size: 10px;color: #f3a9a5;">{pigcms{$vo.nickname}</em>
						</div>
					</div>
			 <div class="group_right" style="line-height: 30px;padding: 8px 2px;font-size: 14px;">去参团</div>
				</a>
			</div>
			</volist>
	   </section>
	 <php>}</php>
		
		
   <section class="details_list clr" <if condition="$_GET['gid'] gt 0 AND $group_share_info">style="display:none;"</if>>
   		<div class="details_listL">
   		 <a href="{pigcms{:U('Group/buy',array('group_id'=>$now_group['group_id'],'type'=>3))}"  style="color:#fff;">
   			<h2>￥{pigcms{$now_group.price}<if condition="$now_group.extra_pay_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$now_group.extra_pay_price|floatval}{pigcms{$config.extra_price_alias_name}</if></h2>
   			<p>{pigcms{$now_group.pin_num}人团</p>
   		 </a>	
   		</div>
   		<div class="details_listr">
   		<a href="{pigcms{:U('Group/buy',array('group_id'=>$now_group['group_id'],'type'=>1))}" style="color:#fff;">
   			<h2>￥{pigcms{$now_group.old_price}<if condition="$now_group.extra_pay_old_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$now_group.extra_pay_old_price|floatval}{pigcms{$config.extra_price_alias_name}</if></h2>
   			<p>单独购买</p>
   		</a>	
   		</div>
   </section>
	<php>if($now_group['group_refund_fee']!=100){</php>
   <section class="details_tips">
     <div class="details_tips_top">相关提示</div>
     <div class="details_tips_end">
     <p>1. 拼团成功前，开团人（团长）在拼团时限内不允许取消订单，参团人允许取消订单。</p>
     <p>2. 拼团成功后，开团人（团长）不得取消订单，参团人取消订单则收取一定的手续费。</p>
	 </div>
   </section>
	<php>}else{</php>
	<section class="details_tips">
     <div class="details_tips_top">相关提示</div>
     <div class="details_tips_end">
     <p>1. 拼团成功前，开团人（团长）在拼团时限内不允许取消订单，参团人不允许取消订单。</p>
     <p>2. 拼团成功后，开团人（团长）不得取消订单，参团人不允许取消订单。</p>
	 </div>
   </section>
	<php>}</php>
   <section class="details_purchase">
     <div class="details_purchase_top"><span>拼购玩法</span></div>
     <div class="details_purchase_end">
        <ul class="clr">
            <li> 
              <span>1</span>
              <p class="text">选择心仪商品</p> 
            </li>
            <li> 
              <span>2</span>
              <p class="text">支付开团或参团</p> 
            </li>
            <li> 
              <span>3</span>
              <p class="text">邀请好友参团</p> 
            </li>
            <li> 
              <span>4</span>
              <p class="text">达到人数团购成功</p> 
            </li>
        </ul>
        <dl>
          <dd><span>1.开团 : </span>选择可开团商品，点击“发起X人团”按钮，付款后即为开团成功;</dd>
          <dd><span>2.参团 : </span>进入朋友分享的页面，点击“立即参团”按钮，付款后即为参团成功，多人同时支付时，支付成功时间较早的人获得参团资格;</dd>
          <dd><span>3.成团 : </span>在开团或参团成功后，点击“分享团购”将页面分享给好友，凑齐人数即为成团，此时商家会开始接单;</dd>
          <dd><span>4.组团失败 : </span>在有效时间内未凑齐人数，即为组团失败，此时将自动退款;</dd>
        </dl>
        <a href="javascript:void(0)" class="details_Open"><span>展开</span><img src="{pigcms{$static_path}images/ptxqt_03.png"></a>
     </div>
   </section>
	<if condition="$now_group['store_list']">
   <section class="details_Branch">
        <ul>
			<volist name="now_group['store_list']" id="vo">
            <li>
              <a href="{pigcms{:U('Group/shop',array('store_id'=>$vo['store_id']))}">
                <div class="Branch_top clr"><h2>{pigcms{$vo.name}</h2><span>{pigcms{$vo.range}</span></div>
                <div class="Branch_end clr">
                  <h2>{pigcms{$vo.adress} <if condition="$key eq 0"><i>离我最近</i></if></h2>
                </div>
              </a> 
            <a href="tel:{pigcms{$vo.phone}" class="Branch_pho"></a> 
            </li>
			</volist>
            
        </ul>
        <div class="more">
         <span>查看全部<i>{pigcms{:count($now_group['store_list'])}</i>家分店</span><em></em>
       </div>    
   </section>
	</if>


  <if condition="$now_group['cue_arr']">
    <section class="details_notice m10">
      <div class="details_purchase_top"><span>预订须知</span></div>
        <ul>
          <volist name="now_group['cue_arr']" id="vo" key="k">
            <li>{pigcms{$vo.key} : {pigcms{$vo.value}</li>
          </volist>
        </ul>
      <a href="javascript:void(0)" class="details_Open details_kai"><span>展开</span><img src="{pigcms{$static_path}images/ptxqt_03.png"></a> 
    </section>
  </if>

  
	<section class="details_notice m10 detail introList" >
		<div class="details_purchase_top"><span>本单详情</span></div>
		 <div class="content">
		   <p>{pigcms{$now_group.content}</p>
		</div>
	</section>
	
	<php>if($now_group['reply_count']>0){</php>
   <section class="details_comment">
      <a href="{pigcms{:U('Group/feedback',array('group_id'=>$now_group['group_id']))}" class="clr">
       <div  class="details_comment_top">
          <div class="atar_Show">
            <p></p>
          </div>
          <span>{pigcms{$now_group.score_mean}</span>
       </div>
       <div class="details_comment_end">
         评论（{pigcms{$now_group.reply_count}）<em></em>
       </div>
      </a> 
   </section>

	
   <section class="details_evaluate">
      <div class="details_purchase_top"><span>评价</span></div>
      <ul>
	  <volist name="reply_list" id="vo">
        <li>
          <div class="details_evaluate_top clr">
             <div class="evaluate_left">
                <h3>{pigcms{$vo.nickname}</h3>
                <span>{pigcms{$vo.add_time}</span>
             </div>
             <div class="evaluate_right">
                <div class="atar_Show">
                  <p tip="{pigcms{$vo.score}" ></p>
                </div>
             </div>  
          </div>
          <div class="details_evaluate_end">
            {pigcms{$vo.comment}
          </div>
        </li>
		</volist>
        
      </ul>
	  <if condition="$now_group['reply_count'] gt 3">
      <div class="stillmore">
         <a href="{pigcms{:U('Group/feedback',array('group_id'=>$now_group['group_id']))}" class="clr">
           <span>查看全部评价（{pigcms{$now_group['reply_count']}）</span><em></em>
         </a>
      </div>  
	  </if>
   </section>
   
		   <php>}</php>
	
	<script type="text/javascript">
	
	
		var myswiper1 = $('.swiper-container').swiper({
			 pagination: '.swiper-pagination',
			 direction : 'horizontal',  
			 centeredSlides : true,      
         slidesPerView: 'auto'})

	 $(".details_purchase .details_Open").each(function(){
	   $(this).click(function(){
		if($(".details_purchase_end dl").is(":hidden")){
		   $(".details_purchase_end dl").slideDown();
		   $(this).addClass("details_Close");
		   $(".details_Close span").text("收起");
		}
	  else{
		$(".details_purchase_end dl").slideUp();
		$(".details_Close span").text("展开");
		$(this).removeClass("details_Close");
	  }
	 })
	 })

	$(".details_Branch .Branch_end h2").width($(window).width()-80);

	// 查看其它团购
		   $(".details_Branch").each(function(){
			$(".details_Branch .more").width(($(window).width()-24))
			var height=$(this).height();
			 if(height>223)
			  {$(this).css({"height":"223px","overflow":"hidden"})}
			  else{ $(this).find(".more").hide()}
		   })
		  $(".details_Branch .more").click(function(){
			 $(this).hide();
			 $(this).parents(".details_Branch").css("height","auto");  
		  })
		  var len= $(".details_Branch li").length
		  $(".details_Branch .more i").text(len); 

		 $(".details_Branch li").last().css("border-bottom","none");
	 // 显示分数
		  $(".details_comment p").each(function(index, element) {
			var num=$(".details_comment_top span").text();
			var www=num*20;//
			$(this).css("width",www);
		});

		  $(".details_evaluate p").each(function(index, element) {
			var num=$(this).attr("tip");
			var www=num*18;//
			$(this).css("width",www);
		});  
	// 预订须知
		 $(".details_notice ul").each(function(){
			var height=$(this).height();
			 if(height>223)
			  {$(this).css({"height":"223px","overflow":"hidden"})}
			  else{ $("a.details_kai").hide()}
		   })
		  $(".details_notice .details_kai").click(function(){
			 $(this).hide();
			 $(".details_notice ul").css("height","auto");  
		  })
		  

     
</script>

<!-- 倒计时 -->
<script type="text/javascript">
var intDiff = parseInt(3600);//倒计时总秒数量
function timer(intDiff){
  window.setInterval(function(){
  var day=0,
    hour=0,
    minute=0,
    second=0;//时间默认值    
  if(intDiff > 0){
    day = Math.floor(intDiff / (60 * 60 * 24));
    hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
    minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
    second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
  }
  if (minute <= 9) minute = '0' + minute;
  if (second <= 9) second = '0' + second;
  if (hour <= 9) hour = '0' + hour;
  $('#day_show').html(day+"天");
  $('#hour_show').html('<s id="h"></s>'+hour);
  $('#minute_show').html('<s></s>'+minute);
  $('#second_show').html('<s></s>'+second);
  $('#day_show1').html(day+"天");
  $('#hour_show1').html('<s id="h"></s>'+hour);
  $('#minute_show1').html('<s></s>'+minute);
  $('#second_show1').html('<s></s>'+second);
  intDiff--;
  }, 1000);
} 
	
$(function(){
	$('.pin_list').each(function(i,index){
		
		addTimer('hour_show_'+(i+1),$(this).data('end_time'))
	}); 
});

var addTimer = function(){
var list = [],
  interval;
  
return function(id,timeStamp){
  if(!interval){
	interval = setInterval(go,1);
  }
  list.push({ele:document.getElementById(id),time:timeStamp});
}

function go() { 
  for (var i = 0; i < list.length; i++) { 
	list[i].ele.innerHTML = changeTimeStamp(list[i].time); 
	if (!list[i].time) 
	  list.splice(i--, 1); 
  } 
}

function changeTimeStamp(timeStamp){
  var distancetime = new Date(timeStamp*1000).getTime() - new Date().getTime();
  if(distancetime > 0){ 
　　
   // var ms = Math.floor(distancetime%1000);
	var sec = Math.floor(distancetime/1000%60);
	var min = Math.floor(distancetime/1000/60%60);
	var hour =Math.floor(distancetime/1000/60/60%24);
	var day =Math.floor(distancetime/1000/60/60/24);
	var day_txt  = '';
	if(day>=1){
		day_txt = day+'天 ';
	}
	if(sec<10){
	  sec = "0"+ sec;
	}
	if(min<10){
	  min = "0"+ min;
	}
	if(hour<10){
	  hour = "0"+ hour;
	}
	
	return day_txt+hour + ":" +min + ":" +sec ;
  }else{
	window.location.reload();
  }  
}        
}();	
	<if condition="$group_share_info">
		addTimer('hour_show',$('#join_group').data('end_time'))
	</if>

</script>
	<script>
		window.shareData={
			"moduleName":"Group",
			"moduleID":"0",
			"imgUrl": "<if condition="$now_group['img_arr'][0]">{pigcms{$now_group.img_arr.0}<else/>{pigcms{$config['wechat_share_img']}</if>",
			"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Group/detail', array('group_id' => $now_group['group_id']))}",
			"tTitle": "{pigcms{$now_group.price}元 【{pigcms{$now_group.s_name}】",
			"tContent": "{pigcms{$now_group['intro']|msubstr=0,40}"
		};
		<if condition="$is_wexin_browser">
			$('.imgBox img').click(function(){
				var album_array = [];
				$('.imgBox img').each(function(index,val){
					album_array.push($(this).attr('src'));
				});
				
				wx.previewImage({
					current:album_array[0],
					urls:album_array
				});
			});
		</if>
	</script>
	<php>$no_footer=true;</php>
	<include file="Public:footer"/>
	{pigcms{$shareScript}
	<include file="kefu" />
	<if condition="$is_app_browser">
		<script type="text/javascript">
			window.lifepasslogin.shareLifePass("{pigcms{$now_group.price}元 【{pigcms{$now_group.s_name}】","{pigcms{$now_group.intro}","<if condition="$now_group['img_arr'][0]">{pigcms{$now_group.img_arr.0}<else/>{pigcms{$config['wechat_share_img']}</if>","{pigcms{$config.site_url}{pigcms{:U('Group/detail', array('group_id' => $now_group['group_id']))}");
		</script>
	</if>

</body>




</html>



