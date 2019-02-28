<include file="web_head"/>
<div class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div id="index" class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div class="clearfix"></div>
</div>
<div class="app-content with-header text-left text-md">
	<div class="padding-tb5 bg-gray"></div>
	<div class="m-b-xxl"><div id="newList"></div></div>
</div>
<div id="footer" class="app-footer b-t bgBottom">
	<div id="my_launch" class="btn b-r col-xs-6 padding-tb10"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlBox}" /> 我的发起</div>
	<div id="my_response" class="btn btn-success b-l col-xs-6 padding-tb10"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlMe}" /> 我的抢单</div>
</div>
<include file="web_footer"/>
<script>
var page	=	1;
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
function	list(is){
	a.busy();
	$.ajax({
		type : "post",
		url : "{pigcms{:U('my_response_json')}",
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
						rideList	+='<div class="m-b-sm">';
						var url	=	"{pigcms{:U('my_response_details')}";
						url +='&order_id='+ride_list[x].order_id;
						rideList	+='	<a href="'+url+'">';
						rideList	+='		<div class="padding-lr5 m-l-xs m-t-sm pull-left text-base">抢单时间：'+ride_list[x].order_time_s+'</div>';
						if(ride_list[x].status == 5){
							rideList	+='		<div class="pull-right m-t-sm m-r-sm text-sm text-danger">'+ride_list[x].status_s+'</div>';
						}else{
							rideList	+='		<div class="pull-right m-t-sm m-r-sm text-sm text-success">'+ride_list[x].status_s+'</div>';
						}
						rideList	+='		<div class="clearfix"></div><div class="padding-lr5">';
						rideList	+='			<div class="col-xs-10 no-padder text-base">';
						rideList	+='				<div class="m-t-sm" style="word-break:break-all;word-wrap:break-word;"><img class="thumb-xxxs" src="'+ride_list[x].urlStar+'" /> '+ride_list[x].details.package_start+'</div>';
						rideList	+='				<div class="m-t-sm" style="word-break:break-all;word-wrap:break-word;"><img class="thumb-xxxs" src="'+ride_list[x].urlEnd+'" /> '+ride_list[x].details.package_end+'</div>';
						rideList	+='			</div>';
						rideList	+='			<div class="col-xs-2 text-right no-padder pull-right m-t text-sm">';
						rideList	+='				<div><img class="thumb-xxxs" src="'+ride_list[x].urlGo+'" /></div>';
						rideList	+='			</div>';
						rideList	+='			<div class="clearfix"></div>';
						rideList	+='		</div>';
						rideList	+='	</a>';
						rideList	+='</div>';
						rideList	+='<div class="padding-tb5 bg-gray"></div>';
					}
					if(ride_list_length <= 9){
						rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有了<p>点击({pigcms{$index})可以返回顶部</p></div>';
					}else{
						rideList	+=	'<div id="mais" class="text-center m-t m-b">上拉会有更多了<p class="text-xs color-black2">点击({pigcms{$index})可以返回顶部</p></div>';
					}
				}else{
					rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有了<p>点击({pigcms{$index})可以返回顶部</p></div>';
				}
			}else if(result.errorCode != '0'){
				rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">'+result.errorMsg+'</div>';
			}else{
				if(page == 1){
					rideList	+=	'<div id="is_null" class="text-center m-t m-b">没有了</div>';
				}else{
					rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有了<p>点击({pigcms{$index})可以返回顶部</p></div>';
				}
			}
			$('#jia').remove();
			$('#newList').append(rideList);
			a.busy(0);
		},
		error:function(){
			a.msg('页面错误，请联系管理员');
			a.busy(0);
		}
	})
}
</script>