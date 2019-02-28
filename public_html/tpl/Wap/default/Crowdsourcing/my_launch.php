<include file="web_head"/>
<div class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div id="index" class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div id="screen" class="pull-right right_img right_bottom m-t-n-md"></div>
	<div class="clearfix"></div>
</div>
<div class="app-content with-header text-left text-md">
	<div class="padding-tb5 bg-gray"></div>
	<div class="m-b-xxl"><div id="newList"></div></div>
</div>
<div id="footer" class="app-footer b-t bgBottom">
	<div id="my_launch" class="btn b-r col-xs-6 padding-tb10 btn-success"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlBox}" /> 我的发起</div>
	<div id="my_response" class="btn b-l col-xs-6 padding-tb10"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlMe}" /> 我的抢单</div>
</div>
<include file="web_footer"/>
<script>
var page	=	1;
var defaultImg	=	'{pigcms{$defaultImg}';
var crowdsourcing	=	'{pigcms{$crowdsourcing}';
$('#return_index').on('click',function(){
	location.href =	"{pigcms{:U('index')}";
});
$('#my_launch').on('click',function(){
	location.href =	"{pigcms{:U('my_launch')}";
});
$('#my_response').on('click',function(){
	location.href = "{pigcms{:U('my_response')}";
});
if(crowdsourcing	== 0){
	var rideList	=	'<div class="text-center m-t m-b">未开通众包平台</div>';
	$('#newList').append(rideList);
	$('#screen').remove();
	$('#my_launch').remove();
	$('#my_response').remove();
}else{
	list(true);
}
$('#index').on('click',function(){
	location.href = "javascript:scroll(0,0)";
});
$(window).scroll(function(){
	if($(window).scrollTop() == $(document).height() - $(window).height()){
		$('#mais').remove();
		var jia	=	'';
    	jia	+=	'<div id="jia" class="text-center m-t m-b">正在加载</div>';
    	$('#newList').append(jia);
		if($('#is_null').length < 1){
			list(false,1);
		}else{
			$('#jia').remove();
		}
	}
});
function	list(is,search){
	a.busy();
	$.ajax({
		type : "post",
		url : "{pigcms{:U('index_json')}",
		dataType : "json",
		data:{
			user_id	:	1,
			page	:	page,
		},
		async:is,
		success : function(result){
			var rideList	=	'';
			if(result.result){
				var	ride_list	=	result.result;
				var	ride_list_length	=	ride_list.length;
				if(ride_list_length){
					page++;
					for(var x=0;x<ride_list_length;x++){
						var url	=	"{pigcms{:U('details')}";
						url +='&package_id='+ride_list[x].package_id+'&status=2';
						rideList	+='<div class="m-b-sm">';
						rideList	+='	<a href="'+url+'">';
						rideList	+='	<div class="padding-lr5 pull-left m-l-xs m-t-sm text-md">'+ride_list[x].package_title+'</div>';
						if(ride_list[x].package_status != 2){
							if(ride_list[x].order_status == 0){
								rideList	+='	<div class="padding-lr5 pull-right m-l-xs m-t-sm text-md text-primary">等待抢单</div>';
							}else if(ride_list[x].order_status == 1){
								rideList	+='	<div class="padding-lr5 pull-right m-l-xs m-t-sm text-md text-primary">等待收货</div>';
							}else if(ride_list[x].order_status == 2){
								rideList	+='	<div class="padding-lr5 pull-right m-l-xs m-t-sm text-md text-primary">等待送货</div>';
							}else if(ride_list[x].order_status == 3){
								rideList	+='	<div class="padding-lr5 pull-right m-l-xs m-t-sm text-md text-success">等待付款</div>';
							}else if(ride_list[x].order_status == 4){
								rideList	+='	<div class="padding-lr5 pull-right m-l-xs m-t-sm text-md text-success">已完成</div>';
							}
						}else if(ride_list[x].package_status == 2){
							rideList	+='	<div class="padding-lr5 pull-right m-l-xs m-t-sm text-md text-danger">已关闭</div>';
						}
						rideList	+='	<div class="clearfix"></div>';
						rideList	+='	<div class="padding-lr5 padding-tb5">';
						rideList	+='		<div class="col-xs-2 m-l-n m-t-xs" style="width:70px;">';
						rideList	+='			<img class="avatar" src="'+ride_list[x].avatar+'" />';
						rideList	+='			<div class="text-xs text-center m-t-xs">'+ride_list[x].user_name+'</div>';
						rideList	+='		</div>';
						rideList	+='		<div class="col-xs-6 no-padder text-base">';
						rideList	+='			<div class="m-t-sm"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlStar}" />'+ride_list[x].package_start+'</div>';
						rideList	+='			<div class="m-t-sm"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlEnd}" />'+ride_list[x].package_end+'</div>';
						rideList	+='		</div>';
						rideList	+='		<div class="col-xs-3 text-right no-padder pull-right m-t text-sm">';
						rideList	+='			<div><span class="text-md color-orange">'+ride_list[x].package_money+'</span>&nbsp;&nbsp;运费</div>';
						rideList	+='			<div class="color-green"><span class="text-md">'+ride_list[x].package_deposit+'</span>&nbsp;&nbsp;押金</div>';
						rideList	+='		</div>';
						rideList	+='		<div class="clearfix"></div>';
						rideList	+='	</div>';
						if(ride_list[x].is_authentication == 1){
							rideList	+='	<div class="padding-lr5 m-t-sm m-l-xs pull-left text-base"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlVyet}" /> 需要认证</div>';
						}else{
							rideList	+='	<div class="padding-lr5 m-t-sm m-l-xs pull-left text-base"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlV}" /> 不需要认证</div>';
						}
						rideList	+='	<div class="pull-right m-t-sm m-r-sm text-base"><img class="thumb-xxxs" src="'+ride_list[x].car_type_img+'" /> 需要'+ride_list[x].car_type_name+'</div><div class="clearfix"></div>';
						rideList	+='	</a>';
						rideList	+='</div>';
						rideList	+='<div class="padding-tb5 bg-gray"></div>';
					}
					if(ride_list_length <= 9){
						rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有众包了<p>点击({pigcms{$index})可以返回顶部</p></div>';
					}else{
						rideList	+=	'<div id="mais" class="text-center m-t m-b">上拉会有更多众包哦<p class="text-xs color-black2">点击({pigcms{$index})可以返回顶部</p></div>';
					}
				}else{
					rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有众包了<p>点击({pigcms{$index})可以返回顶部</p></div>';
				}
			}else if(result.errorCode != '0'){
				rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">'+result.errorMsg+'</div>';
			}else{
				if(search == 1){
					if(page == 1){
						rideList	+=	'<div id="is_null" class="text-center m-t m-b">没有众包了</div>';
					}else{
						rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有众包了<p>点击({pigcms{$index})可以返回顶部</p></div>';
					}
				}else if(search == 2){
					if(page == 1){
						rideList	+=	'<div id="is_null" class="text-center m-t m-b">未搜索到众包</div>';
					}else{
						rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有众包了<p>点击({pigcms{$index})可以返回顶部</p></div>';
					}
				}
			}
			$('#jia').remove();
			if(search == 2){
				$('#newList').html(rideList);
			}else{
				$('#newList').append(rideList);
			}
			a.busy(0);
		},
		error:function(){
			a.msg('页面错误，请联系管理员');
			a.busy(0);
		}
	})
}
</script>