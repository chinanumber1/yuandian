
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>我的结算用户列表</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
   
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?211"/>
	<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js?232s"></script>

<script type="text/javascript">
$(document).ready(function(e) {
    // 设定每一行的宽度=屏幕宽度+按钮宽度
    $(".line-scroll-wrapper").width($(".line-wrapper").width() + $(".line-btn-delete").width());
    // 设定常规信息区域宽度=屏幕宽度
    $(".line-normal-wrapper").width($(".line-wrapper").width());
    // 设定文字部分宽度（为了实现文字过长时在末尾显示...）
    $(".line-normal-msg").width($(".line-normal-wrapper").width() - 280);

    // 获取所有行，对每一行设置监听
    var lines = $(".line-normal-wrapper");
    var len = lines.length; 
    var lastX, lastXForMobile;

    // 用于记录被按下的对象
    var pressedObj;  // 当前左滑的对象
    var lastLeftObj; // 上一个左滑的对象

    // 用于记录按下的点
    var start;

    // 网页在移动端运行时的监听
    for (var i = 0; i < len; ++i) {
        lines[i].addEventListener('touchstart', function(e){
            lastXForMobile = e.changedTouches[0].pageX;
            pressedObj = this; // 记录被按下的对象 

            // 记录开始按下时的点
            var touches = event.touches[0];
            start = { 
                x: touches.pageX, // 横坐标
                y: touches.pageY  // 纵坐标
            };
        });

        lines[i].addEventListener('touchmove',function(e){
            // 计算划动过程中x和y的变化量
            var touches = event.touches[0];
            delta = {
                x: touches.pageX - start.x,
                y: touches.pageY - start.y
            };

            // 横向位移大于纵向位移，阻止纵向滚动
            if (Math.abs(delta.x) > Math.abs(delta.y)) {
                event.preventDefault();
            }
        });

        lines[i].addEventListener('touchend', function(e){
            if (lastLeftObj && pressedObj != lastLeftObj) { // 点击除当前左滑对象之外的任意其他位置
                $(lastLeftObj).animate({marginLeft:"0"}, 500); // 右滑
                lastLeftObj = null; // 清空上一个左滑的对象
            }
            var diffX = e.changedTouches[0].pageX - lastXForMobile;
            if (diffX < -150) {
                $(pressedObj).animate({marginLeft:"-80px"}, 500); // 左滑
                lastLeftObj && lastLeftObj != pressedObj && 
                    $(lastLeftObj).animate({marginLeft:"0"}, 500); // 已经左滑状态的按钮右滑
                lastLeftObj = pressedObj; // 记录上一个左滑的对象
            } else if (diffX > 150) {
              if (pressedObj == lastLeftObj) {
                $(pressedObj).animate({marginLeft:"0"}, 500); // 右滑
                lastLeftObj = null; // 清空上一个左滑的对象
              }
            }
        });
    }

    // 网页在PC浏览器中运行时的监听
    for (var i = 0; i < len; ++i) {
        $(lines[i]).bind('mousedown', function(e){
            lastX = e.clientX;
            pressedObj = this; // 记录被按下的对象
        });

        $(lines[i]).bind('mouseup', function(e){
            if (lastLeftObj && pressedObj != lastLeftObj) { // 点击除当前左滑对象之外的任意其他位置
                $(lastLeftObj).animate({marginLeft:"0"}, 500); // 右滑
                lastLeftObj = null; // 清空上一个左滑的对象
            }
            var diffX = e.clientX - lastX;
            if (diffX < -150) {
                $(pressedObj).animate({marginLeft:"-80px"}, 500); // 左滑
                lastLeftObj && lastLeftObj != pressedObj && 
                    $(lastLeftObj).animate({marginLeft:"0"}, 500); // 已经左滑状态的按钮右滑
                lastLeftObj = pressedObj; // 记录上一个左滑的对象
            } else if (diffX > 150) {
              if (pressedObj == lastLeftObj) {
                $(pressedObj).animate({marginLeft:"0"}, 500); // 右滑
                lastLeftObj = null; // 清空上一个左滑的对象
              }
            }
        });
    }
});
</script>
<style type="text/css">
* { margin: 0; padding: 0; }
.line-wrapper { width: 100%; height: 80px;  font-size: 12px; border-bottom: 1px solid #e5e5e5; }
.line-scroll-wrapper { white-space: nowrap; height: 80px; clear: both; }
.line-btn-delete { float: left; width: 80px; height: 80px; }
.line-btn-delete button { width: 100%; height: 100%; background: red; border: none; font-size: 17px; font-family: 'Microsoft Yahei'; color: #fff; }
.line-normal-wrapper { display: inline-block;     /* line-height: 100px; */ float: left;/*padding-top: 4px; */ }
.line-normal-icon-wrapper {     float: right;
    width: 120px;
    height: 34px;
    margin-right: 12px;
    margin-top: 33px; }
.line-normal-icon-wrapper img { width: 120px; height: 78px; }
.line-normal-avatar-wrapper { width: 72px; height: 72px; float: left; margin-left: 12px; }
.line-normal-avatar-wrapper img { width: 65px; height: 65px; border-radius: 50%; }
.line-normal-left-wrapper { float: left; /* overflow: hidden;*/ padding: 6px 0px 0px 0px;}
.line-normal-info-wrapper { float: left; /*margin-left: 10px; */}
.line-normal-user-name { height: 28px; line-height: 28px; color: #4e4e4e; margin-top: 7px; }
.line-normal-msg { height: 28px; line-height: 28px;  text-overflow:ellipsis; color: #4e4e4e;  }
.line-normal-time { height: 28px; line-height: 28px; color: #999; margin-top: 11px; }
.title{
	height: 30px;
    width: 100%;
    font-size: 19px;
   border-bottom: 1px solid #e5e5e5;
	}
</style>
</head>
<body>
<div class="title">
	我的结算用户({pigcms{$res.spread_change_user_list|count})
</div>
<volist name="res.spread_change_user_list" id="vo">
<div class="line-wrapper" >
  <div class="line-scroll-wrapper">
    <div class="line-normal-wrapper" date-type="{pigcms{$vo.spread_count}" date-uid="{pigcms{$vo.uid}">
      <div class="line-normal-left-wrapper">
        <div class="line-normal-avatar-wrapper"><img src="{pigcms{$vo.avatar}" /></div>
        <div class="line-normal-info-wrapper">
          <div class="line-normal-user-name">{pigcms{$vo.nickname}</div>
          <div class="line-normal-msg">他有{pigcms{$vo.spread_count}个推广用户</div>
        </div>
      </div>
      <div class="line-normal-icon-wrapper">结算佣金总额:<if condition="empty($vo['spread_money'])">0.00<else />{pigcms{$vo.spread_money}</if></div>
    </div>
    <div class="line-btn-delete"><button class="unbind" date-id="{pigcms{$vo.uid}">解绑</button></div>
  </div>
</div>
</volist>

</body>
<script>
	$('.line-normal-wrapper').click(function(){
			if($(this).attr('date-type')>0){
				window.location.href = "{pigcms{:U('My/spread_user_list')}&uid="+$(this).attr('date-uid');
			}else{
				layer.open({
					title:['错误提示','background-color:#8DCE16;color:#fff;'],
					content:'该用户没有推广用户',
					btn: ['确定'],
				});
			}
		});

	$('.unbind').click(function(){
		var uid = $(this).attr('date-id');
		layer.open({
			content: '解除后，您将不再结算对用户的推广佣金'
			,btn: ['确定', '取消']
			,skin: 'footer'
			,yes: function(index){
				 $.ajax({
					url: '{pigcms{:U('My/unbind_spread_change')}',
					type: 'POST',
					dataType: 'json',
					data:{uid:uid},
					
					success:function(data){
						if(!data.error_code){
							 layer.open({
								content: data.msg
							,btn: ['确定']
							,yes:function(index){
								window.location.reload();
							}
						  });
						
						}else{
							layer.open({
							content: data.msg
							,btn: ['确定']
						  });
						}
					}
				});
			}
		});
})
</script>
</html>