<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>留言中心</title>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/message.css"/>
    <script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
</head>
<style>
	.msgtime{
		color: #989595;
		font-size: 13px;
	}
</style>
<body>
	<header style="z-index: 999;">
		<a href="JavaScript:history.back(-1)" class="ft"><i></i></a>
		<span>留言中心</span>
	</header>
	<div class="content">
		<div class="dolog message" style="z-index: 999;">
			<textarea name="content" id="content" rows="4" cols="3" maxlength="100" ></textarea>
			<div class="send_out after">
				<p class="length ft"><span>0</span>/100</p>
				<button class="rg" onclick="sendMsg()">发送</button>
			</div>
		</div>
		<div class="dolog_lists after">

			<volist name="messageList" id="vo">
				<if condition="$vo['sort'] eq 'left'">
					<div class="ft after">
						<ul>
							<li><img src="{pigcms{$vo.avatar}"/><span class="span posi">{pigcms{$vo.nickname}</span><span class="posi msgtime">{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</span></li>
							<li class="widths ft"><span>{pigcms{$vo.content}</span></li>
						</ul>
					</div>
				<else/>
					<div class="right after">
						<ul>
							<li><span class="posi msgtime">{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</span><span class="posi">{pigcms{$vo.nickname}</span><img src="{pigcms{$vo.avatar}"/></li>
							<li class="widths rg"><span>{pigcms{$vo.content}</span></li>
						</ul>
					</div>
				</if>
			</volist>

		</div>
	</div>

	<script type="text/javascript" charset="utf-8">
		$('.message textarea').keyup(function(e){
			var text=$(this).val();
			var len=text.length;
			$('.length span').text(len);
		});

		function sendMsg(){

			var content = $("#content").val();
			if(!content){
				return false;
			}
			var type = "1";
			var order_id = "{pigcms{$_GET['order_id']}";
			var ajax_message_data_url = "{pigcms{:U('Yuedan/ajax_message_data')}";
			$.post(ajax_message_data_url,{type:type,order_id:order_id,content:content},function(data){
				if(data.error == 1){
					location.href = location.href;
				}else{	
					alert(data.msg);
				}
			},'json');

			// var html = '<div class="right after"> <ul> <li><span class="posi">2017-06-05 11:43:06</span><span class="posi">miss李</span><img src="{pigcms{$static_path}yuedan/imanges/4-_22.png"/></li> <li class="widths rg"><span>'+content+'</span></li> </ul> </div>'

			// $(".dolog_lists").prepend(html);
			var content = $("#content").val('');
		}
		
	</script>
</body>
</html>