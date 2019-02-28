<include file="web_head"/>
<style type="text/css">
	.ok_search {
	    transform: rotateY(0deg);
	    -webkit-transform: rotateY(0deg);
	    -moz-transform: rotateY(0deg);
	}
	.pc_search {
	    background: rgba(0,0,0,0.78);
	    width: 100%;
	    position: absolute;
	    z-index: 1000;
	    top: 0;
	    /*transform: rotateY(90deg);
	    -webkit-transform: rotateY(90deg);*/
	    -moz-transform: rotateY(90deg);
	    -webkit-transition: -webkit-transform 0.3s ease-out 0s;
	    -moz-transition: -moz-transform 0.3s ease-out 0s;
	    transition: transform 0.3s ease-out 0s;
	}
	.close {
	    height: 2em;
	    color: #fff;
	    padding-right: 0.8em;
	    cursor: pointer;
	    width: 15%;
	    float: right;
	    position: absolute;
	    top: 0px; right: 0px;
	    z-index: 99
	}
	.close span {
	    display: inline-block;
	    font-size: 1em;
	    width: 1.5em;
	    height: 1.5em;
	    background: #fff;
	    color: #333;
	    float: right;
	    text-align: center;
	    line-height: 1.5em;
	    margin-top: 0.5em;
	    border-radius: 0.8em;
	}
	.tj {

	    margin-left: 20px;
	    font-size: 0.89em;
	    padding-top: 1em;
	    margin-right: 20px;
	}
	.tj dl {
	    overflow-x: auto;
	    border-bottom: 1px solid #444;

	}
	.tj dl dt {
	    display: inline-block;
	    float: left;
	    margin: 0.5em 0.3em;
	    margin-right: 0;
	    padding: 0.1em;
	    color: #999;
	}
	.tj dl dd.cattsel {
	    background: #5bc0de;
	}
	.tj dl dd {
	    display: inline-block;
	   /* float: left;*/
	    color: #fff;
	    margin: 0.5em 0.3em;
	    padding: 0.1em 0.3em;
	    cursor: pointer;
	}
	.tj dl dd input[type='radio'] {
	    display: none;
	}
	.mdd {
	    font-size: 0.89em;
	    padding: 0.5em 0.4em;
	    margin: 0.4em 0.3em;
	    width: 85%;
	    background: #8f8f8f;
	    border: 1px solid #333;
	}
	.btn-tj {
	    font-size: 1em;
	    text-align: center;
	    width: 90%;
	    display: block;
	    margin: 0 auto;
	    color: #fff;
	    border-radius: 0.3em;
	    background: #008CD6;
	    padding: 0.6em;
	    margin-top: 1em;
	}
	a:visited {
	    text-decoration: none;
	    color: #353535;
	}
	.over{  height: 37px; overflow-y: hidden; overflow-x: auto;  white-space: nowrap;}
</style>
<div class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div id="index" class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div id="screen" class="pull-right right_img right_bottom m-t-n-md">筛选</div>
	<div class="clearfix"></div>
</div>
<div class="app-content with-header text-left text-md">
<!--	搜索	-->
<aside id="search" class="pc_search ok_search" style="display:none;">
    <form action="" method="post" id="sxForm">
        <div id="close" class="close"><span>X</span></div>
        <div id="tjsx">
            <div class="w-p-60 color-while m-l m-t">筛选条件</div>
            <div class="tj">
                <dl id="zuowei" class="block2">
	                    <dt>车型：</dt>
	                    <div class="over">
		                    <dd class="cattsel">不限
		                        <input name="driver_seat" type="radio" value="0" checked="">
		                    </dd>
		                    <volist name="category" id="vo">
								<dd>{pigcms{$vo['category_name']}
			                        <input name="driver_seat" type="radio" value="{pigcms{$vo['category_id']}">
			                    </dd>

		                    </volist>
                    	</div>
                </dl>
                <dl id="feiyong" class="block2">
                    <dt>运费：</dt>
					<div class="over">
	                    <dd class="cattsel">不限
	                        <input name="driver_price" type="radio" value="1000000" checked="">
	                    </dd>
	                    <dd>0-50元
	                        <input name="driver_price" type="radio" value="50">
	                    </dd>
	                    <dd>50-100元
	                        <input name="driver_price" type="radio" value="100">
	                    </dd>
	                    <dd>100-200元
	                        <input name="driver_price" type="radio" value="200">
	                    </dd>
	                    <dd>≥200元
	                        <input name="driver_price" type="radio" value="1000">
	                    </dd>
	                </div>
                </dl>
                <dl class="block2">
                    <dt>目的地：</dt>
                    <div style="padding-left: 62px;">
                    	<input id="mudi" type="text" name="driver_destination" placeholder="请填写目的地" class="mdd">
                    </div>
                </dl>
            </div>
            <div id="submit" class="text-center padding-lr5"><div class="btn btn-info w-full">提交信息</div></div>
        </div>
    </form>
</aside>
<script>
    $(".pc_search").height($("#page").height())
    $(".right_img").click(function() {
        $(".pc_search").addClass("ok_search");
    })
    $(".close").click(function() {
        $(".pc_search").removeClass("ok_search");
    })
    $(".tj dl dd").click(function() {
        $(this).attr("class", "cattsel");
        $(this).siblings("dd").removeAttr("class");
        changeAtt(this)
    })
	$("#close").click(function() {
        $("#search").hide();
    })
    $("#screen").click(function() {
        var screenHeight	=	$(document).height();
        $("#search").css("height",screenHeight);
        $("#search").show();
    })
    $('#submit').click(function(){
        page	=	1;
		var remain_number	=	$("#zuowei dd.cattsel input").val();			//座位
		var ride_price		=	$("#feiyong dd.cattsel input").val();			//车费
		var destination		=	$("input[name='driver_destination']").val();	//目的地
		list(true,2,destination,ride_price,remain_number);
		$("#search").hide();
    })
    function changeAtt(t) {
        t.lastChild.checked = 'checked';
    }
</script>
	<div class="padding-tb5 bg-gray"></div>
	<div class="m-b-xxl"><div id="newList"></div></div>
</div>
<div id="footer" class="app-footer b-t bgBottom">
	<div id="launch" class="btn b-r col-xs-6 padding-tb10"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlBox}" /> 发起众包</div>
	<div id="my_launch" class="btn b-l col-xs-6 padding-tb10"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlMe}" /> 我的众包</div>
</div>
<include file="web_footer"/>
<script>
var page	=	1;
var defaultImg	=	'{pigcms{$defaultImg}';
var crowdsourcing	=	'{pigcms{$crowdsourcing}';
$('#return_index').on('click',function(){
	window.history.go(-1);
});
$('#launch').on('click',function(){
	location.href =	"{pigcms{:U('add')}";
});
$('#my_launch').on('click',function(){
	location.href =	"{pigcms{:U('my_launch')}";
});
if(crowdsourcing	== 0){
	var rideList	=	'<div class="text-center m-t m-b">未开通众包平台</div>';
	$('#newList').append(rideList);
	$('#screen').remove();
	$('#launch').remove();
	$('#my_launch').remove();
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
			destination	=	$('#destination').text();
			ride_price	=	$('#ride_price').text();
			remain_number	=	$('#remain_number').text();
			list(false,1,destination,ride_price,remain_number);
		}else{
			$('#jia').remove();
		}
	}
});
function	list(is,search,destination,ride_price,remain_number){
	a.busy();
	$.ajax({
		type : "post",
		url : "{pigcms{:U('index_json')}",
		dataType : "json",
		data:{
			ride_price		:	ride_price,
			remain_number	:	remain_number,
			destination		:	destination,
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
						url +='&package_id='+ride_list[x].package_id+'&status=1';
						rideList	+='<div class="m-b-sm">';
						rideList	+='	<a href="'+url+'">';
						rideList	+='	<div class="padding-lr5 m-l-xs m-t-sm text-md">'+ride_list[x].package_title+'</div>';
						rideList	+='	<div class="padding-lr5 padding-tb5">';
						rideList	+='		<div class="col-xs-2 m-l-n m-t-xs" style="width:70px;">';
						rideList	+='			<img class="avatar" src="'+ride_list[x].avatar+'" />';
						rideList	+='			<div class="text-xs m-t-xs text-center">'+ride_list[x].user_name+'</div>';
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
						rideList	+=	'<div class="hide"><div id="destination">'+destination+'</div><div id="ride_price">'+ride_price+'</div><div id="remain_number">'+remain_number+'</div></div>';
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

var motify = {
	checkIos:function(){
        if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
            return true;
        }else{
            return false;
        }
    },
    checkAndroid:function(){
        if(/(android)/.test(navigator.userAgent.toLowerCase())){
            return true;
        }else{
            return false;
        }
    },
	checkLifeApp:function(){
        if(/(pigcmso2oreallifeapp)/.test(navigator.userAgent.toLowerCase()) || (/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase()))){
            return true;
        }else{
            return false;
        }
    },
	getLifeAppVersion:function(){
		var reg = /versioncode=(\d+),/;
		var arr = reg.exec(navigator.userAgent.toLowerCase());
		if(arr == null){
			return 0;
		}else{
			return parseInt(arr[1]);
		}
	},
	getAndroidVersion:function(){
		var index = navigator.userAgent.indexOf("Android");
		if(index >= 0){
			var androidVersion = parseFloat(navigator.userAgent.slice(index+8));
			if(androidVersion > 1){
				return androidVersion;
			}else{
				return 100;
			}
		}else{
			return 100;
		}
	}
}
if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50 && (motify.checkIos() || motify.checkAndroid())){
	$('#return_index').off('click');
	if(motify.checkAndroid()){
		window.lifepasslogin.hideWebViewHeader(false);
		/* $('#return_index').on('click',function(){
			window.lifepasslogin.webViewGoBack();
		}); */

		$('#return_index').on('click',function(){
			window.lifepasslogin.closeWebView();
		});
	}else{
		$('body').append('<iframe src="pigcmso2o://hideWebViewHeader/false" style="display:none;"></iframe>');
		$('#return_index').on('click',function(){
			$('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none;"></iframe>');
		});
	}
}

</script>