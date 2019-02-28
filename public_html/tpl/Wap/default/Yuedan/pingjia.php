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
		<a href="person_center.html" class="ft"><i></i></a>
		<span>填写评论</span>
		<a href="javascript:;">发送</a>
	</header>
		<div class="content">
		<div class="card_header">
			<img src="imanges/4-_22.png"/>
			<span>毛斯阿婆云南过桥米线</span>
		</div>
		<hr />
		<div class="stars">
			<span class="add"><i class="active"></i></span>
			<span class="fen">3.0分</span>
			
		</div>
		
		<div class="service_date">
			
			<div class="message">
				<span>留言:</span>
				<textarea name="" rows="4" cols="3" placeholder="说说你的其他要求" maxlength="150"></textarea>
			</div>
			<p class="length"><span>0</span>/150</p>
		</div>
	</div>

<script type="text/javascript" charset="utf-8">
	$('.stars .add').click(function(e){
		var width=e.clientX-30;
		if(width<=15){
			$('.add i').css('width','10%');
			$('.stars .fen').text('0.5分');
		}else if(width<=30){
			$('.add i').css('width','20%');
			$('.stars .fen').text('1.0分');
		}
		else if(width<=45){
			$('.add i').css('width','31%');
			$('.stars .fen').text('1.5分');
		}
		else if(width<=60){
			$('.add i').css('width','42%');
			$('.stars .fen').text('2.0分');
		}
		else if(width<=75){
			$('.add i').css('width','52%');
			$('.stars .fen').text('2.5分');
		}
		else if(width<=90){
			$('.add i').css('width','62%');
			$('.stars .fen').text('3.0分');
		}
		else if(width<=105){
			$('.add i').css('width','72%');
			$('.stars .fen').text('3.5分');
		}
		else if(width<=120){
			$('.add i').css('width','82%');
			$('.stars .fen').text('4.0分');
		}
		else if(width<=135){
			$('.add i').css('width','93%');
			$('.stars .fen').text('4.5分');
		}else if(width<=150){
			$('.add i').css('width','100%');
			$('.stars .fen').text('5.0分');
		}
		
	});
	$('.message textarea').keyup(function(e){
		var text=$(this).val();
		var len=text.length;
		$('.length span').text(len);
	});
</script>
</body>
</html>