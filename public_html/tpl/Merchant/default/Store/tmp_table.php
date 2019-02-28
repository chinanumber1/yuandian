<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		
		<script type="text/javascript" src="{pigcms{$static_path}js/swiper-3.3.1.jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/swiper-3.3.1.min.css"/>
		
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		
		<script type="text/javascript">
			var table_lock_url = "{pigcms{:U('tmp_table_lock')}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffTable.js"></script>
		
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/font-awesome.min.css"/>
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
					<li class="urlLink cur" data-url="{pigcms{:U('tmp_table')}">
						<div class="icon table"></div>
						<div class="text">桌台列表</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('queue')}">
						<div class="icon queue"></div>
						<div class="text">排号列表</div>
					</li>
				</ul>
			</div>
			<div class="rightMain tableMain">
				<div class="swiper-button-prev"></div>
				<div class="tabele_cat swiper-container">
					<ul class="swiper-wrapper">
						<volist name="table_type" id="vo">
							<li class="swiper-slide <if condition="$vo['id'] eq $now_table['id']">cur</if>" data-href="{pigcms{:U('tmp_table',array('type_id'=>$vo['id']))}">{pigcms{$vo.name}</li>
						</volist>
					</ul>
				</div>
				<div class="swiper-button-next"></div>
				<div class="table_tab_box">
					<div class="table_tab">
						<div class="tab_all cur">所有</div>
						<div class="tab_lock">锁定</div>
						<div class="tab_unlock">解锁</div>
					</div>
					<div class="table_search">
						<input type="text" placeholder="请输入桌台名称查询"/>
					</div>
				</div>
				<div class="table_body">
					<ul>
						<volist name="table_list" id="vo">
							<li class="<if condition="$vo['status'] eq 0">unlocked<else/>locked</if>" data-id="{pigcms{$vo.id}">
								<div class="cat_name">{pigcms{$vo.name}</div>
								<div class="cat_status">当前状态：<if condition="$vo['status'] eq 0">解锁<else/>锁定</if></div>
								<div class="cat_btn">
									<div class="cat_status_btn"><if condition="$vo['status'] eq 0">点击锁定<else/>点击解锁</if></div>
									<div class="cat_info_btn">
									<a title="查看餐桌预定详情"  data-title="餐桌预定详情" class="handle_btn" data-layer_id="edit_order" data-title="编辑订单" data-box_width="95%" data-box_height="95%" href="{pigcms{:U('book_list',array('table_id' => $vo['id']))}">
											查看预定
									</a>
									</div>
								</div>
							</li>
						</volist>
					</ul>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript">
	$(function(){
		/*店铺状态*/
		updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");
	});
	function updateStatus(dom1, dom2, status1, status2, attribute){
		$(dom1).each(function(){
			if($(this).attr("data-status")==status1){
				$(this).attr("checked",true);
			}else{
				$(this).attr("checked",false);
			}
			$(dom2).show();
		}).click(function(){
			var _this = $(this),
				type = 'open',
				id = $(this).attr("data-id");
			_this.attr("disabled",true);
			if(_this.attr("checked")){	//开启
				type = 'open';
			}else{		//关闭
				type = 'close';
			}
			$.ajax({
				url:"{pigcms{:U('Store/table_status')}",
				type:"post",
				data:{"type":type,"id":id,"status1":status1,"status2":status2,"attribute":attribute},
				dataType:"text",
				success:function(d){
					if(d != '1'){		//失败
						if(type=='open'){
							_this.attr("checked",false);
						}else{
							_this.attr("checked",true);
						}
						bootbox.alert("操作失败");
					}
					_this.attr("disabled",false);
				}
			});
		});
	}
	</script>
</html>