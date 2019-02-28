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
					<li class="urlLink" data-url="{pigcms{:U('meal_list')}">
						<div class="icon order"></div>
						<div class="text">订单列表</div>
					</li>
					<li class="urlLink cur" data-url="{pigcms{:U('table')}">
						<div class="icon table"></div>
						<div class="text">桌台列表</div>
					</li>
				</ul>
			</div>
			<div class="rightMain">
				<div class="alert alert-block alert-success">
					<p>
						餐台的使用状态只是当前餐台的使用状态，方便给当前来就餐的顾客安排座位<strong>【特别注意如果有客户预定了该餐桌，且已经支付，那么该客户预定餐桌前后2小时都不会被其他人预定了】</strong>
					</p>
				</div>
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>编号ID</th>
								<th>桌台号</th>
								<th>容纳人数</th>
								<th>使用状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$list">
								<volist name="list" id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td>{pigcms{$vo.pigcms_id}</td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.num}</td>
										<td>
											<label class="statusSwitch" style="display:inline-block;">
												<input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-id="{pigcms{$vo.pigcms_id}" <if condition="$vo['status'] eq 1">checked="checked" data-status="OPEN"<else/>data-status="CLOSED"</if>/>
												<span class="lbl"></span>
											</label>
										</td>
										<td class="">
											<a data-title="桌台使用情况" class="green handle_btn" href="{pigcms{:U('Store/table_order',array('id'=>$vo['pigcms_id']))}">桌台预定详情</a>
										</td>
									</tr>
								</volist>
							<else/>
								<tr class="odd"><td class="button-column" colspan="8" >无内容</td></tr>
							</if>
						</tbody>
					</table>
					{pigcms{$pagebar}
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