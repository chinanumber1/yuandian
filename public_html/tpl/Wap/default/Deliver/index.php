<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>店员中心</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
    <style>
	    dl.list dd.dealcard {
	        overflow: visible;
	        -webkit-transition: -webkit-transform .2s;
	        position: relative;
	    }
	    .dealcard.orders-del {
	        -webkit-transform: translateX(1.05rem);
	    }
	    #orders .dealcard-block-right {
			margin-left:1px;
	        position: relative;
	    }
	    .dealcard .dealcard-brand {
	        margin-bottom: .18rem;
	    }
	    .dealcard small {
	        font-size: .24rem;
	        color: #9E9E9E;
	    }
	    .dealcard weak {
	        font-size: .24rem;
	        color: #999;
	        position: absolute;
	        bottom: 0;
	        left: 0;
	        display: block;
	        width: 100%;
	    }
	    .dealcard weak b {
	        color: #FDB338;
	    }
	    .dealcard weak a.btn{
	        margin: -.15rem 0;
	    }
	    .dealcard weak b.dark {
	        color: #fa7251;
	    }
	    .hotel-price {
	        color: #ff8c00;
	        font-size: .24rem;
	        display: block;
	    }
	    .del-btn {
	        display: block;
	        width: .45rem;
	        height: .45rem;
	        text-align: center;
	        line-height: .45rem;
	        position: absolute;
	        left: -.85rem;
	        top: 50%;
	        background-color: #EC5330;
	        color: #fff;
	        -webkit-transform: translateY(-50%);
	        border-radius: 50%;
	        font-size: .4rem;
	    }
	    .no-order {
	        color: #D4D4D4;
	        text-align: center;
	        margin-top: 1rem;
	        margin-bottom: 2.5rem;
	    }
	    .icon-line {
	        font-size: 2rem;
	        margin-bottom: .2rem;
	    }

	    .order-icon {
	        display: inline-block;
	        width: .5rem;
	        height: .5rem;
	        text-align: center;
	        line-height: .5rem;
	        border-radius: .06rem;
	        color: white;
	        margin-right: .25rem;
	        margin-top: -.06rem;
	        margin-bottom: -.06rem;
	        background-color: #F5716E;
	        vertical-align: initial;
	        font-size: .3rem;
	    }
	    .order-all {
	        background-color: #2bb2a3;
	    }
	    .order-zuo,.order-jiudian {
	        background-color: #F5716E;
	    }
	    .order-fav {
	        background-color: #0092DE;
	    }
	    .order-card {
	        background-color: #EB2C00;
	    }
	    .order-lottery {
	        background-color: #F5B345;
	    }
	    .color-gray{
	    	color:gray;
	    	border-color:gray;
	    }
	    .color-gray:active{
	    	background-color:gray;
	    }
		#nav-dropdown{height: 1.7rem;}
		#filtercon select{height: 100%;line-height: normal;width:100%;}
		#filtercon{margin: 0 .15rem;}
.find_div {
margin: .15rem 0;
}
	#filtercon input{background-color: #fff;
		width: 100%;
		border: none;
		background: rgba(255, 255, 255, 0);
		outline-style: none;
		display: block;
		line-height: .28rem;
		height: 100%;
		font-size: .28rem;
		padding: 0
}
		#find_submit{
			position: absolute;
			right: 0rem;
			top: .15rem;
			width: 1.2rem;
			height: .7rem;;
			-webkit-box-sizing: border-box;
		}
 .dealcard-block-right li{
    font-size: .266rem;
font-weight: 400;
 }
.dealcard-block-right .dth{font-weight: bold;}
 .ulrightdiv{
	float: right;
	position: relative;
	top: -60px;
	margin-right: 15px;
	}
	dl.list .dd-padding{padding: .28rem 0.1rem;}
	.red{color:red;}
.top-btn-a a{color: #fff;margin-top: 10px;}
.top-btn-a .lb{margin-left: 20px;}
.top-btn-a .rb{float: right;margin-right: 20px;}
.dealcard-block-right{padding: 0 10px;}
#orders a{color: #333;}
#orders .td a{color: green;}
.find_type_div{
	position: absolute;
left: 0rem;
width: 1.7rem;
height: .7rem;
text-align: center;
background: white;
}
.find_txt_div{
vertical-align: middle;
position: relative;
margin-right: 1.3rem;
margin-left:1.8rem;
border-radius: .06rem;
border: 1px #CCC solid;
height: .7rem;
line-height: .7rem;
}
  .dealcard-block-right li.btm_li{
     margin-bottom: .18rem;
 }
 
 .deliver_list {
 	border-buttom: 0px solid #fff;
 	border-top:1px solid #e5e5e5;
 }
 .dealcard-block-right {
 	margin-buttom:10px;
 }
 .diver_list_get {
 	width: 1.5rem;
 	float: right;
 	height: 1.5rem;
 	background: #EE3968;
 	font-size: 0.8rem;
 	text-align: center;
 	border-radius: 50%;
 	margin-right:0.3rem;
 }
 .diver_list_get em {
 	color: lightgoldenrodyellow;
 	font-style: normal;
 	line-height: 1.5rem;
 }
</style>
</head>
<body>
		<header  class="navbar">
            <h1 class="nav-header">配送系统 - {pigcms{$config.site_name}</h1>
        </header>
	    <div>
		    <dl class="list" id="orders">
				<dd class="dealcard dd-padding" onclick="Jumpto({pigcms{$vo['order_id']})" style="border-bottom:none;border-top:1px solid #ccc;">
					<div>
						<ul class="dealcard-block-right" style="width:3.8rem; float:left; margin-bottom:0.3rem;">
							<li class="btm_li">
								<span class="dth">顾客姓名：</span>
								<span class="ttd">原委委</span>
							</li>
							<li class="btm_li"><span class="dth">顾客电话：</span><span class="td"><a  href="tel:{pigcms{$vo.phone}" onclick="stopPropagation()">18336903373</a></span></li>
							<li class="btm_li"><span class="dth">取货地址：</span>
							<span style="color: red">上地三街西口红状元</span></li>
							<li><span class="dth">收货地址：</span>
							<span style="color: red">上地三街嘉华大厦</span></li>
						</ul>
						<span class="diver_list_get">
							<em>抢</em>
						</span>
					</div>
				</dd>
				<div style="clear:both"></div>
				<dd class="dealcard dd-padding" onclick="Jumpto({pigcms{$vo['order_id']})" style="border-bottom:none;border-top:1px solid #ccc;">
					<div>
						<ul class="dealcard-block-right" style="width:3.8rem; float:left; margin-bottom:0.3rem;">
							<li class="btm_li">
								<span class="dth">顾客姓名：</span>
								<span class="ttd">雷胖子</span>
							</li>
							<li class="btm_li"><span class="dth">顾客电话：</span><span class="td"><a  href="tel:{pigcms{$vo.phone}" onclick="stopPropagation()">18336903373</a></span></li>
							<li class="btm_li"><span class="dth">取货地址：</span>
							<span style="color: red">上地三街西口红状元</span></li>
							<li><span class="dth">收货地址：</span>
							<span style="color: red">上地三街嘉华大厦</span></li>
						</ul>
						<span class="diver_list_get">
							<em>抢</em>
						</span>
					</div>
				</dd>
				<div style="clear:both"></div>
			</dl>
		</div>
		<include file="Deliver:footer"/>
</body>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript">
    function Jumpto(oid){
		window.location.href="{pigcms{:U('Storestaff/meal_edit')}&order_id="+oid;
	}
	function toJmupURl(vv){
	   vv=$.trim(vv);
	   if(vv=='st'){
	     window.location.href="{pigcms{:U('Storestaff/meal_list')}&st=1";
	   }
	}
	function stopPropagation(e){
	  e = e || window.event; 
	  if(e.stopPropagation) { //W3C阻止冒泡方法  
        e.stopPropagation();  
       } else {  
        e.cancelBubble = true; //IE阻止冒泡方法  
       } 
	}
	$('#find_type').change(function(){
		if($(this).val() != '1'){
			$('#find_value').val($('#find_value').val().replace(/\s+/g,""));
		}else{
			$('#find_value').val($('#find_value').val().replace(/\s+/g,"").replace(/(\d{4})/g,'$1 '));
		}
	});
	$('#find_value').keyup(function(){
		if($('#find_type').val() == '1'){
			if($(this).val().substr(-1) == ' '){
				$(this).val($(this).val().substr(0,($(this).val().length-1)));
			}else{
				$(this).val($(this).val().replace(/\s+/g,"").replace(/(\d{4})/g,'$1 '));
			}
		}
	});
	$('#find_submit').click(function(){
		var find_value = $('#find_value');
		find_value.val($.trim(find_value.val()));
		var find_type = $.trim($('#find_type').val());
		if(find_type && find_value.val().length < 1){
			alert('请输入查找内容！');
			find_value.focus();
			return false;
		}
		
		window.document.find_form.submit();
		return false;
	});
</script>
{pigcms{$shareScript}
<script type="text/javascript">
function is_mobile(){
	var ua = navigator.userAgent.toLowerCase();
	if ((ua.match(/(iphone|ipod|android|ios|ipad)/i))){
		if(navigator.platform.indexOf("Win") == 0 || navigator.platform.indexOf("Mac") == 0){
			return false;
		}else{
			return true;
		}
	}else{
		return false;
	}
}
function is_weixin(){
    var ua = navigator.userAgent.toLowerCase();
    if(is_mobile() && ua.indexOf('micromessenger') != -1){  
        return true;  
    } else {  
        return false;  
    }  
}
function getParam(url,name){ 
	var reg = new RegExp("[&|?]"+name+"=([^&$]*)", "gi"); 
	var a = reg.test(url); 
	return a ? RegExp.$1 : ""; 
}
$('#qrcode_btn').click(function(){
	if(is_weixin()){
		wx.scanQRCode({
			needResult:1,
			scanType:["qrCode"],
			success:function (res){
				/*
				 * URL提示：
				 *    /wap.php?c=Storestaff&a=group_qrcode&id=(14位消费码)
				 *    /wap.php?c=Storestaff&a=meal_qrcode&id=(订单ID)
				 */
				var result = res.resultStr;
				if(result.indexOf('http://') !== 0){
					layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:'您扫描的内容 “ <font color="red">'+result+'</font> ” 不是有效的验证二维码',btn: ['确定'],end:function(){}});
				}else{
					var ctype = getParam(result,'a'),id = getParam(result,'id'),c = getParam(result,'c');
					var actMode='meal_qrcode';
					if(ctype == 'group_qrcode') actMode='group_qrcode';
					if((ctype != 'group_qrcode' && ctype != 'meal_qrcode') || id== '' || c != 'Storestaff'){
						layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:'您扫描的内容不是有效的验证二维码',btn: ['确定'],end:function(){}});
					}else{
						layer.open({
							title:['提示：','background-color:#FF658E;color:#fff;'],
							content:'初次检测订单属于 <font color="red">'+(ctype == 'group_qrcode' ? '团购' : '订餐')+'</font> 订单，您是要验证消费或查看订单？',
							btn: ['验证消费', '查看订单'],
							shadeClose: false,
							yes: function(){
								layer.open({
									type: 2,
									content: '验证消费中，请稍后'
								});
								$.getJSON("/wap.php?g=Wap&c=Storestaff&a="+actMode,{type:ctype,id:id,ajax:1},function(ret){ 
									if(!ret.error){
										layer.open({
											title:['成功提示：','background-color:#FF658E;color:#fff;'],
											content:'验证成功！是否要刷新页面？',
											btn: ['确定','取消'],
											yes: function(index){
												window.location.href='/wap.php?g=Wap&c=Storestaff&a=meal_list';
												layer.close(index);
											}
										});
									}else{
										layer.open({
											title:['错误提示：','background-color:#FF658E;color:#fff;'],
											content:ret.msg,
											btn: ['确定'],
											end:function(){
												window.location.href='/wap.php?g=Wap&c=Storestaff&a=meal_list';
											}
										});
									}
								});
							}, no: function(){
								if(ctype == 'group_qrcode'){
									window.location.href = "{pigcms{:U('Storestaff/group_edit')}&order_id="+getParam(result,'order_id');
								}else{
									window.location.href = "{pigcms{:U('Storestaff/meal_edit')}&order_id="+getParam(result,'id');
								}
							}
						});
					}
				}	
			}
		});
	}else{
		layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:'您使用的不是微信浏览器，此功能无法使用！您可以使用浏览器自带的或其他扫描二维码工具进行扫描',btn: ['确定'],end:function(){}});
	}
	return false;
});
</script>
</html>