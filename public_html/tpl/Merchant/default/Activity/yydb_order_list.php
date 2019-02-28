<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-empire"></i>
				商家推广
			</li>
			<li class="active"><a href="{pigcms{:U('Activity/index')}">平台活动列表</a></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<form action="{pigcms{:U('Activity/yydb_order_list')}" method="get">
					<input type="hidden" name="c" value="Activity"/>
					<input type="hidden" name="a" value="yydb_order_list"/>
					<input type="hidden" name="activity_id" value="{pigcms{$activity_id}"/>
					<font color="#000">日期筛选：</font>
					<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
					<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
					
				
					<input type="submit" value="查询" class="button"/>　　
					
					<a class="btn btn-success"  href="{pigcms{:U('Activity/export',$_GET)}"  style="float:right;">导出订单</a>
				</form>
				<div class="col-xs-12">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th id="shopList_c1" width="50">编号</th>
									<th id="shopList_c1" width="50">用户姓名</th>
									<th id="shopList_c1" width="100">用户手机</th>
									<th id="shopList_c0" width="50">数量</th>
									<th id="shopList_c0" width="50">金额</th>
									<th id="shopList_c0" width="50">购买时间</th>
							
								</tr>
							</thead>
							<tbody>
								<if condition="$list">
									<volist name="list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.pigcms_id}</td>
											<td>{pigcms{$vo.nickname}</td>
											<td title="{pigcms{$vo.title}">{pigcms{$vo.phone}</td>
											<td>{pigcms{$vo.part_count}</td>
											<td>￥{pigcms{$vo.part_count}</td>
											
											<td>
											{pigcms{$vo.time|date='Y-m-d H:i:s',###}
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
		</div>
	</div>
</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
	$(function(){
		$('.yiyuan_handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看获奖人信息',
				padding: 0,
				width: 720,
				height: 520,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4',
			});
			return false;
		});
		
		$('#group_id').change(function(){
			$('#frmselect').submit();
		});
	});
	function CreateShop(){
		window.location.href = "{pigcms{:U('Activity/add')}";
	}
</script>
<include file="Public:footer"/>
