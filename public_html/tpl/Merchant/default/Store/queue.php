<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="headerBox">
			<div class="txt">{pigcms{$config.meal_alias_name}</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink" data-url="{pigcms{:U('foodshop')}">
						<div class="icon order"></div>
						<div class="text">订单列表</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('tmp_table')}">
						<div class="icon table"></div>
						<div class="text">桌台列表</div>
					</li>
					<li class="urlLink cur" data-url="{pigcms{:U('queue')}">
						<div class="icon queue"></div>
						<div class="text">排号列表</div>
					</li>
				</ul>
			</div>
			<div class="rightMain">
                <div class="alert waring" style="background-color:#f9cdcd;border-color:#f9cdcd;color:#8c2a2a;display:none;">
                    <i class="ice-icon fa fa-volume-up bigger-130"></i>
                    <p>您有部分商品库存小于10,请及时 <a title="库存报警商品列表"  data-title="库存报警商品列表" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('foodshop_goods_stock')}">查看</a>！</p>
                </div>
				<if condition="$store['queue_is_open'] eq 0">
				<button class="btn btn-success" id="status">点击开启排号</button>
				<else />
				<button class="btn btn-success" id="status">点击关闭排号</button>
				</if>
				<br/>
				<br/>
				<br/>
				<div class="alert alert-block alert-success">
					<p>
						【叫号】：就是通知被叫号进店就餐，可以反复点击通知，直到您点击【跳号】跳过为止<br/>
						【跳号】：表示被叫号已经进入就餐或被叫号已经离开了，跳过他让下一个号成为被叫号<br/>
					</p>
				</div>
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th id="shopList_c1" width="50">桌台类型</th>
								<th id="shopList_c1" width="50">空闲桌台数</th>
								<th id="shopList_c0" width="80">排号人数</th>
								<th id="shopList_c5" width="50">下一个号</th>
								<th id="shopList_c5" width="50">被叫号</th>
								<th class="button-column">操作</th>
							</tr>
						</thead>
						<tbody>
							<volist name="queue_list" id="vo">
								<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>" id="queue_{pigcms{$vo.id}">
									<td>{pigcms{$vo.name}</td>
									<td>{pigcms{$vo.free}</td>
									<td>{pigcms{$vo.wait}</td>
									<td>{pigcms{$vo.next_number}</td>
									<td>{pigcms{$vo.now_number}</td>
									<td class="button-column" width="40">
										<a title="查看订单详情"  data-title="叫号" class="green queue_call" style="padding-right:8px;" data-id="{pigcms{$vo.id}">
											<i class="shortBtn">叫号</i>
										</a>
										<a title="跳过下一个号：{pigcms{$vo.next_number}"  data-title="订单详情" class="red queue_cancel" style="padding-right:8px;" data-number="{pigcms{$vo.next_number}" data-id="{pigcms{$vo.id}">
											<i class="shortBtn">跳号</i>
										</a>
									</td>
								</tr>
							</volist>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
<script>
$(function(){
	var status = false;
	$('#status').click(function(){
		if (status) return false;
		status = true;
		$.get('{pigcms{:U("Store/change_queue")}', function(response){
			status = false;
			if (response.status == 0) {
				layer.msg(response.info);
				return false;
			} else {
				$('#status').text(response.info);
			}
		}, 'json');
	});

	var queue_cancel = false;
	$('.queue_cancel').click(function(){
		if (queue_cancel) return false;
		queue_cancel = true;
		var number = $(this).data('number'), tid = $(this).data('id');
		layer.confirm('您确定要跳过被叫号吗？', {
			  btn: ['跳过','不跳'] //按钮
			}, function(){
			  $.post('{pigcms{:U("Store/queue_cancel")}', {'number':number, 'tid':tid}, function(response){
				  queue_cancel = false;
				  if (response.err_code) {
					  layer.msg(response.msg);
				  } else {
					  layer.msg(response.msg);
				  }
			  }, 'json');
			}, function(){
				 queue_cancel = false;
			});
	});

	var queue_call = false;
	$('body').append('<audio style="display:none;" id="playMp3Tip" controls="true" src=""></audio>');
	$('.queue_call').click(function(){
		if (queue_call) return false;
		queue_call = true;
		var tid = $(this).data('id');
		$.post('{pigcms{:U("Store/queue_call")}', {'tid':tid}, function(response){
			queue_call = false;
			if (response.err_code) {
				layer.msg(response.msg);
			} else {
				$('#playMp3Tip').attr('src', response.mp3).trigger('play');
				layer.msg(response.msg);
			}
		}, 'json');
	});

	setInterval(queue_list, 10000);
	check_foodshop_goods_stock();
});

function check_foodshop_goods_stock()
{
    $.get("{pigcms{:U('Store/check_foodshop_goods_stock')}", function(result){
        if(result.status == 1){
            $('.waring').show();
        } else {
            $('.waring').hide();
        }
        setTimeout(function(){
            check_foodshop_goods_stock();
        },6000);
    }, 'json');
}
function queue_list()
{
	$.get('{pigcms{:U("Store/queue_list")}', function(response){
		if (response.status) {
			$.each(response.data, function(i, data){
				$('#queue_' + data.id).find('td').eq(1).text(data.free);
				$('#queue_' + data.id).find('td').eq(2).text(data.wait);
				$('#queue_' + data.id).find('td').eq(3).text(data.next_number);
				$('#queue_' + data.id).find('td').eq(4).text(data.now_number);
				$('#queue_' + data.id).find('.queue_cancel').data('number', data.next_number).attr('title', data.next_number);
			});
		}
	}, 'json');
}
</script>
</html>