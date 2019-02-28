<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>活动在线报名表</title>
	<link href="{pigcms{$static_path}activity/css/baoming2014.css?tc=155709" type="text/css" rel="stylesheet" />
	<script src="{pigcms{$static_path}activity/js/common.js"></script>
	<script src="{pigcms{$static_public}js/jquery-1.9.1.min.js"></script>
	<script src="{pigcms{$static_public}js/layer/layer.js"></script>
</head>
<body>
	<div class="hdb">
		<div class="hdbt clearfix"> <strong>在线报名</strong> <span>提交报名信息，参与同城活动</span> </div>
		<div class="content">
			<span class="sp_a">怎么称呼您：<span class="tips" id="chkbaotruename"></span></span>
			<div class="hdbc">
				<span class="sp_b po_re">
					<input name="truename" id="truename" type="text" class="input0" value=""/>
					<s class="s"></s>
				</span>
			</div>
			<span class="sp_a">怎么联系您：<span class="tips" id="chkbaochrtel" style="visibility: visible;"></span></span>
			<div class="hdbc">
				<span class="sp_d po_re">
					<input name="qq" id="qq" type="text" class="input1" value=""/>
					<s class="s2">QQ：</s>
				</span>
				<span class="sp_d po_re">
					<input name="phone" id="phone" type="text" class="input1" value=""/>
					<s class="s"></s>
					<s class="s2">手机：</s>
				</span>
			</div>
			<span class="sp_a">您还有什么想对我们说的：</span>
			<div class="hdbc">
				<span class="sp_b po_re">
					<input name="message" id="message" type="text" value="" class="input0" />
					<s class="s"></s>
				</span>
			</div>
		
			<div class="hdbtn">
				<input type="hidden" name="activeid" id="activeid" value="32">
				<button onclick="checkbaoming()" class="submit">提交报名</button>
				<div class="xieyibtn">
					<input type="checkbox" name="check" id="check" value="checkbox" checked />
					我已经阅读并接受
					<a href="javascript:showxieyi('XIEYI');" class="blue_font">活动协议</a>
				</div>
			</div>

			<div class="xieyi" id="XIEYI" style="display:none;">
				<p class="hd">活动协议</p>
				<textarea disabled="disabled" readonly="readonly" class="text11">协议</textarea>
				<a href="javascript:showxieyi('XIEYI');" class="close">关闭</a>
			</div>
		</div>
	</div>
</body>
</html>

<script>
var index = parent.layer.getFrameIndex(window.name);
	function checkbaoming(){
		var uid = "{pigcms{$user_session['uid']}";
		if(!uid){
			layer.msg('请先登录然后进行报名');
			return false;
		}
		if($("#truename").val()==""){
			$("#chkbaotruename").html('<font color=red >* 对不起,请填写您的真实姓名!</font>');
			return false;
		}
		if($("#truename").val().length<2){
			$("#chkbaotruename").html('<font color=red >* 请正确填写您的真实姓名!</font>');
			return false;
		}	
		$("#chkbaotruename").html('<font color="green">√ 符合要求!</font>');

		if($("#phone").val()==""){
			$("#chkbaochrtel").html('<font color=red >* 对不起,请填写您的手机号码!</font>');
			return false;
		}
		$("#chkbaochrtel").html('<font color="green">√ 符合要求!</font>');

		if(!$('[name=check]:checkbox').is(':checked')){//判断是否选中
			alert('请确认阅读并接受活动协议！');
        }

        var truename = $("#truename").val();
        var qq = $("#qq").val();
        var phone = $("#phone").val();
        var message = $("#message").val();
        var a_id = "{pigcms{$_GET['a_id']}";
        var baomingUrl = "{pigcms{:U('Activity/activity_baoming')}";
        $.post(baomingUrl,{'truename':truename,'qq':qq,'phone':phone,'message':message,'a_id':a_id},function(data){
        	if(data.error == 1){
        		parent.layer.msg(data.msg);
        		parent.self_baoming();
                parent. Show_TabADSMenu(1,3,5);
                parent.layer.close(index);
        	}else{
        		parent.layer.msg(data.msg);
        	}
        },'json');


	}
</script>