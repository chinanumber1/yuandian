<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>评价订单</title>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/pingjia.css"/>
	<script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
	<header>
		<a href="{pigcms{:U('Yuedan/my_order')}" class="ft"><i></i></a>
		<span>填写评论</span>
		<a href="javascript:;" onclick="comment_sub()">发送</a>
	</header>
		<form action="{pigcms{:U('comment_data')}" method="post" id="comment_form">
			<div class="content">
				<div class="card_header">
					<img src="{pigcms{$serviceInfo.listimg}"/>
					<span>{pigcms{$serviceInfo.title}</span>
				</div>
				<hr />
				<div class="stars">
					<span class="add"><i class="active" style="width: 100%;"></i></span>
					<span class="fen">5.0分</span>
					<input type="hidden" name="total_grade" value="10" id="total_grade">
				</div>
				<div class="service_date">
					<div class="message">
						<span>内容:</span>
						<textarea name="content" id="content" rows="4" cols="3" placeholder="说说你的其他要求" maxlength="150"></textarea>
					</div>
					<p class="length"><span>0</span>/150</p>
				</div>
				<input type="hidden" name="order_id" value="{pigcms{$_GET['order_id']}">
			</div>
		</form>

<script type="text/javascript" charset="utf-8">
	$('.stars .add').click(function(e){
		var width=e.clientX-30;
		if(width<=15){
			$('.add i').css('width','10%');
			$('.stars .fen').text('0.5分');
			$("#total_grade").val(1);
		}else if(width<=30){
			$('.add i').css('width','20%');
			$('.stars .fen').text('1.0分');
			$("#total_grade").val(2);
		}
		else if(width<=45){
			$('.add i').css('width','30%');
			$('.stars .fen').text('1.5分');
			$("#total_grade").val(3);
		}
		else if(width<=60){
			$('.add i').css('width','40%');
			$('.stars .fen').text('2.0分');
			$("#total_grade").val(4);
		}
		else if(width<=75){
			$('.add i').css('width','50%');
			$('.stars .fen').text('2.5分');
			$("#total_grade").val(5);
		}
		else if(width<=90){
			$('.add i').css('width','60%');
			$('.stars .fen').text('3.0分');
			$("#total_grade").val(6);
		}
		else if(width<=105){
			$('.add i').css('width','70%');
			$('.stars .fen').text('3.5分');
			$("#total_grade").val(7);
		}
		else if(width<=120){
			$('.add i').css('width','80%');
			$('.stars .fen').text('4.0分');
			$("#total_grade").val(8);
		}
		else if(width<=135){
			$('.add i').css('width','90%');
			$('.stars .fen').text('4.5分');
			$("#total_grade").val(9);
		}else if(width<=150){
			$('.add i').css('width','100%');
			$('.stars .fen').text('5.0分');
			$("#total_grade").val(10);
		}
		
	});
	$('.message textarea').keyup(function(e){
		var text=$(this).val();
		var len=text.length;
		$('.length span').text(len);
	});

	function comment_sub(){
		var content = $(".content").html();
		if(!content){
			alert('请输入评论内容');
			return false;
		}
		$("#comment_form").submit();
	}
</script>
</body>
</html>