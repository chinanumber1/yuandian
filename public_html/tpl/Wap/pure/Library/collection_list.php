<!DOCTYPE html>
<html style="font-size: 20px;">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>快递代收</title>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/express_service_list.css"/>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
	<script src="{pigcms{$static_path}js/jquery-1.8.3.min.js" type="text/javascript" charset="utf-8"></script>
	<style type="text/css">
    	.express li{
    		width: 94%;
			padding: 10px 3% 10px 3%;
			display: -webkit-flex;
		    display: flex;
		    -webkit-box-pack: justify;
		    -webkit-justify-content: space-between;
		    justify-content: space-between;
		    -webkit-box-align: center;
		    -webkit-align-items: center;
		    align-items: center;
		    background: #FFFFFF;
		    border-bottom:1px solid #F5F5F5;
    	}
    </style>
</head>
<body>
	<header class="mui-bar mui-bar-nav clear">
		<a href="{pigcms{:U('fetch_code')}" class="mui-pull-left return_add"></a>
		<h1 class="mui-title">快递代收</h1>

		<a href="javascript:void(0);" onclick="confirm_receipt_all({pigcms{$info.phone},{pigcms{$info.village_id})" class="mui-pull-right yi_parts">全部取件</a>
	</header>
	<div class="contanir">
		<div class="all_conent" >

			<div style=" color: #06c1ae;">待取快递</div>
			<div class="currency all_express" >
				<p class="odd_numbers clear" >
					<span class="ft">快递单号:{pigcms{$info.express_no}</span>
					<a href="javascript:;" class="rg">
						<if condition='$info["status"] eq 0'>
							未取件
						<elseif condition='$info["status"] eq 1' />
							已取件（业主）
						<else />
							已取件（社区）
						</if>
					</a>
				</p>
				<h3>{pigcms{$info.express_name}</h3>
				<if condition='$info["memo"]'>
					<p  class="remark">备注 : {pigcms{$info.memo}</p>
				</if>

				<p  class="arrive_time">到件时间 : {pigcms{$info.add_time|date='Y-m-d H:i:s',###}</p>
				<p  class="order">
					<if condition='$info["status"] eq 0'>
						<button type="button" onclick="confirm_receipt({pigcms{$info.id})">确认取件</button>
					</if>
				</p>
			</div>


			<if condition="is_array($list)">
			<!-- <div style="border: 3px; border:1px solid #06c1ae; border-radius: 8px;"> -->
				<!-- <div style="color: red; width: 100%; background-color: #06c1ae; height: 30px; font-size: 18px; border-radius: 2px;">全部待取</div> -->
				<div style=" color: #06c1ae;">全部待取</div>
				<volist name="list" id="vo">
					<div class="currency all_express" style="width: 90%; margin:10px 5%;">
						<p class="odd_numbers clear">
							<span class="ft">快递单号:{pigcms{$vo.express_no}</span>
							<a href="javascript:;" class="rg">
								<if condition='$vo["status"] eq 0'>
									未取件
								<elseif condition='$vo["status"] eq 1' />
									已取件（业主）
								<else />
									已取件（社区）
								</if>
							</a>
						</p>
						<h3>{pigcms{$vo.express_name}</h3>
						<if condition='$vo["memo"]'>
							<p class="remark">备注 : {pigcms{$vo.memo}</p>
						</if>

						<p class="arrive_time">到件时间 : {pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</p>
						<!-- <p class="arrive_time">快递类型 : {pigcms{$vo.express_name}</p> -->
						<p class="order">
							<if condition='$vo["status"] eq 0'>
								<button type="button" onclick="confirm_receipt({pigcms{$vo.id})">确认取件</button>
							</if>
						</p>
					</div>
				</volist>
			<!-- </div> -->
			</if>
		</div>
	</div>
	<script>
		function confirm_receipt_all(phone,village_id){
			var confirm_receipt_url = "{pigcms{:U('confirm_receipt_all')}";
			layer.open({
				content: '你确定要全部取件吗？'
				,btn: ['确定', '取消']
				,yes: function(index){
					$.post(confirm_receipt_url,{phone:phone,'village_id':village_id},function(data){
						if(data.error == 1){
							alert(data.msg);
							location.href = "{pigcms{:U('fetch_code')}";
						}else{
							alert(data.msg);
							
						}
					},'json');
				}
			});
		}
		function confirm_receipt(id){
			var confirm_receipt_url = "{pigcms{:U('confirm_receipt')}";
			layer.open({
				content: '你确定收到快递了吗？'
				,btn: ['确定', '取消']
				,yes: function(index){
					$.post(confirm_receipt_url,{id:id},function(data){
						if(data.error == 1){
							alert(data.msg);
							// location.reload();
							location.href = "{pigcms{:U('fetch_code')}";
						}else{
							alert(data.msg);
						}
					},'json');
				}
			});
		}
	</script>
</body>
</html>