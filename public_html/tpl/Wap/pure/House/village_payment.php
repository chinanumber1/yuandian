<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<title></title>
		<style>
			*{margin: 0;padding: 0;}
			body{background: #F7F7F7;}
			header{
				background: white;
				font-size: 14px;color: #333;
				padding:12px 0 12px 3%;
				position: fixed;
				top: 0;
				width: 97%;
				font-weight: 700;
				border-bottom: 1px solid #eee;
			}
			.content{margin-top: 55px;padding-bottom: 50px;}
			.list_items{background: white;margin-bottom: 12px;width: 100%;}
			.item{
				width: 94%;
				display: -webkit-flex;
				display: flex;
				-webkit-box-pack: justify;
				-webkit-justify-content: space-between;
				justify-content: space-between;
				-webkit-box-align: center;
				-webkit-align-items: center;
				align-items: center;
				padding: 10px 3%;
				border-bottom: 1px solid #F4F4F4;
				color: #333;
				font-size: 16px;
			}
			.item>p{
				width:64px;
				height: 30px;
				border-radius: 30px;
				border:1px solid #eee;
				font-size: 0;
				line-height: 30px;
			}
			.item>p>i{
				display: inline-block;
				width: 30px;
				height: 30px;
				border-radius: 50%;
				border:1px solid #eee;
				margin-top: -1px;
				
			}
			.item>p.active{
				text-align: right;
				background: #06C1AE;
				border-color: #06C1AE;
			}
			.item>p.active>i{
				border-color: #fff;
				background: #fff;
			}
			footer{
				position: fixed;
				bottom: 0;
				width: 100%;
				height: 50px;
				line-height: 50px;
				background: white;
				font-size: 0;
				
			}
			footer .total{
				display: inline-block;
				width: 60%;
				margin-left: 4%;
				color: #333;
				font-size: 16px;
				border-top: 1px solid #eee;
			}
			footer .total span{color: #06C1AE;}
			.pay{
				display: inline-block;
				width: 36%;
				font-size: 14px;
				text-align: center;
				background: #06C1AE;
				color: #fff;
				border-top:1px solid #06C1AE;
			}
		</style>
	</head>
	<body>
		<header> 待缴费用 ( 单位: 元 )</header>	
		<div class="content">
			<div class="list_items">
				<div class="item">
					<span>2017-8-18</span>
					<p><i></i></p>
				</div>
				<div class="item">
					<span>车库管理员</span>
					<span class="money">20</span>
				</div>
			</div>
			<div class="list_items">
				<div class="item">
					<span>2017-8-18</span>
					<p><i></i></p>
				</div>
				<div class="item">
					<span>车库管理员</span>
					<span class="money">20</span>
				</div>
			</div>
			<div class="list_items">
				<div class="item">
					<span>2017-8-18</span>
					<p><i></i></p>
				</div>
				<div class="item">
					<span>车库管理员</span>
					<span class="money">20</span>
				</div>
			</div>
			<div class="list_items">
				<div class="item">
					<span>2017-8-18</span>
					<p><i></i></p>
				</div>
				<div class="item">
					<span>车库管理员</span>
					<span class="money">20</span>
				</div>
			</div>
			<div class="list_items">
				<div class="item">
					<span>2017-8-18</span>
					<p><i></i></p>
				</div>
				<div class="item">
					<span>车库管理员</span>
					<span class="money">20</span>
				</div>
			</div>
			<div class="list_items">
				<div class="item">
					<span>2017-8-18</span>
					<p ><i></i></p>
				</div>
				<div class="item">
					<span>车库管理员</span>
					<span class="money">20</span>
				</div>
			</div>
		</div>
		<footer>
			<div class="total">合计: ￥ <span>0.00</span></div>
			<div class="pay">马上支付</div>
		</footer>
		<script src="jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			$('.content').off('click','.list_items p').on('click','.list_items p',function(e){
				if($(this).is('.active')){
					$(this).removeClass('active');
				}else{
					$(this).addClass('active');
				}
				var sum=0;
				$.each($('.content .list_items'), function(i,val){
					if($(this).find('p').is('.active')){
						sum+=parseFloat($(this).find('.money').text());
					}
				});
				$('.total span').text(sum);
			});
		</script>
</html>
