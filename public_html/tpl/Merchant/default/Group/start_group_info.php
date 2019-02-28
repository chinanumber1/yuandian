<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Group/start_group_list')}">团购小组列表</a>
				
			</li>
			<li class="active">
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="javascript:void(0)">团购小组详情</a>
				
			</li>
			
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
				
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
								
									<th>参团用户昵称</th>
									<th>手机</th>
							
									<th>购买时间</th>
									<th>消费状态</th>
									<th>团员身份</th>
									<th>订单详情</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$buyer_list">
									<volist name="buyer_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										
											<td>{pigcms{$vo['nickname']}</td>
											<td>{pigcms{$vo['phone']}</td>
								
											<td><if condition="$vo.type eq 0">{pigcms{$vo['pay_time']|date="Y-m-d H:i:s",###}</if></td>
											<td><if condition="$vo.type eq 0"><if condition="$vo.status eq 0"><font color="green">未消费</font><elseif condition="$vo.status eq 1 OR $vo.status eq 2" /><font color="blue">已消费</font><elseif condition="$vo.status eq 3" /><font color="red">已退款</font><elseif condition="$vo.status eq 6" /><font color="orange">部分退款</font></if></if></td>
											<td><if condition="$vo.is_head gt 1"><font color="green">团长</font><elseif condition="$vo.type eq 1" /><font color="red">机器人(商家手动生成)</font><else /><font color="blue">成员</font></if></td>
											<td><if condition="$vo.type eq 0">
												<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Group/order_detail',array('order_id'=>$vo['order_id']))}">
													<i class="ace-icon fa fa-search bigger-130"></i>
												</a></if>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="11" >没有团购小组</td></tr>
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
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	
	$(function(){
		$('.group_name').hover(function(){
			var top = $(this).offset().top;
			var left = $(this).offset().left+$(this).width()+10;
			$('body').append('<div id="group_name_div" style="position:absolute;z-index:5555;background:white;top:'+top+'px;left:'+left+'px;border:1px solid #ccc;padding:10px;"><div style="margin-bottom:10px;"><b>商品标题：</b>'+$(this).data('title')+'</div><div><b>商品图片：</b><img src="'+$(this).data('pic')+'" style="width:180px;"/></div></div>');
		},function(){
			$('#group_name_div').remove();
		});	
		$('.handle_btn').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看渠道二维码',
				padding: 0,
				width: 700,
				height: 500,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		});
	});
</script>
<include file="Public:footer"/>
