<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>个人相册</title>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/photo.css"/>
	</head>
	<body>
		<div class="header">
			<a href="javascript:history.back(-1)"></a>
			<span>相册</span>
		</div>
		<div class="content">
			<volist name="service_photos" id="vo">
				<!-- <img src="{pigcms{$vo}"/> -->
				<p class="img_click" data-src="{pigcms{$vo}" style="margin-left:3.3%; display:inline-block; width:45%;background: transparent url({pigcms{$vo}) no-repeat 0% 0px;background-size:cover; height:130px;text-align: center;    margin-bottom: 10px;"></p>
			</volist>
		</div>

		<div class="mask">
			<div class="hide">
				<ul class="clear">
					<volist name="service_photos" id="vovo">
						<if condition="$key eq '0'"><li style="display: block;" class="ft"><else/><li class="ft"></if><img src="{pigcms{$vovo}" alt="" /></li>
					</volist>

				</ul>
			</div>
			
		</div>
		<script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			var length=$('.hide ul li').length;
			$('li').on('touchstart',function(event){
			    //screenWidth:屏幕分辨率宽度
			    var screenWidth = $(window).width();
			    var that = this;
			    console.log(that);
			   
                
			    //获取ul下的li总数
			    var liCount = $('ul li').length;
			    //index:获取当前被点击的图片的索引值
			    var index = $(that).index();
			    var len1=length-1;
			    //最小滑动距离，当左右滑动距离小于这个值时，图片返回原位置，不产生向左或者向右切换图片的效果
			    var minMoveDis = 100;
			    //获取点击x坐标
			    var _touch = event.originalEvent.targetTouches[0];
			    var clickX = _touch.pageX;
			    $("li").on('touchmove',function(event){
			        //移动过程中，距离最开始点击位置的X距离
			        var _sectouch = event.originalEvent.targetTouches[0];
			        var distance = _sectouch.pageX - clickX ;
			        var moveX = distance*(-1) + screenWidth * index * (-1);
	                //滑动事件结束时
	                $('li').on('touchend',function(){
                		if (0 < distance < minMoveDis ) {
	                    }
	                    if(distance >=minMoveDis ){//左滑
	                    	if(index==0){
	                    		$('.clear li:eq('+(length-1)+')').show().siblings('li').hide();
	                    		$('.clear').css('margin-top',-($('.clear li:eq('+(length-1)+') img').height()/2));
	                    	}else{
	                    		$('.clear li:eq('+(index-1)+')').show().siblings('li').hide();
	                    		$('.clear').css('margin-top',-($(that).prev().find('img').height()/2));
	                    	}
	                    	 
	                    }
	                    if(distance <=0){//右滑
	                    	if(index==len1){
	                    		$('.clear li:eq(0)').show().siblings('li').hide();
	                    		$('.clear').css('margin-top',-($('.clear li:eq(0) img').height()/2));
	                    	}else{
	                    		$('.clear li:eq('+(index+1)+')').show().siblings('li').hide();
	                    		$('.clear').css('margin-top',-($(that).next().find('img').height()/2));
	                    	}
	                    	
                    	}

                    
	                    $('li').off('touchmove');
	                });
	            });     
			});
			//蒙层点击
			$('.mask').click(function(e){
				$(this).hide();
			});
			//图片点击查看放大效果
			$('.content').off('click','.img_click').on('click','.img_click',function(e){
				var this_index=$(this).index();
				// console.log(this_index);
				$('.clear li:eq('+this_index+')').css('display','block').siblings('li').hide();
				$('.mask').show();
				var width=$('body').width();
				$('.hide ul li img').width(width);
				
				var li_width=$('.hide ul li').width();
				$('.clear').width(li_width*length);

				$.each($('.hide ul li'),function(i,val){
					if($(this).css('display')=="block"){
						var height1=$(this).find('img').height();
						console.log(height1);
						$('.clear').css('margin-top',-(height1/2));
					}
				});

				// $('.clear').css('margin-top',-($('.hide ul li img').height()/2));
				
			});
		</script>
</html>
