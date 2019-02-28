<include file="Public:header"/>
<style>
	img{height:30px;width:60px;}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Sub_card/index')}" >免单套餐列表</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Sub_card/add')}','添加免单',800,500,true,false,false,addbtn,'add_sub_card',true);">添加免单</a>
					<a href="{pigcms{:U('Sub_card/order_list')}"  class="on">免单订单列表</a>		
				</ul>
			</div>
			
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>ID</th>
								<th>消费码</th>
								<th>店铺名称</th>
								
								<th>消费时间</th>
								<th class="textcenter">消费详情</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($order_list)">
								<volist name="order_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.pass}
										</td>
										<td>{pigcms{$vo.store_name}</td>
										
										
										<td><if condition="$vo.use_time gt 0">{pigcms{$vo.use_time|date='Y-m-d H:i:s',###}</if></td>
										<td class="textcenter"><if condition="$vo['status'] eq 1"><font color="red">已消费</font><else /><font color="green">未消费</font></if></td>
									</tr>
								</volist>
							
							<else/>
								
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	$(function(){
		$('#indexsort_edit_btn').click(function(){
			$(this).prop('disabled',true).html('提交中...');
			$.post("/merchant.php?g=Merchant&c=Config&a=merchant_indexsort",{group_indexsort:$('#group_indexsort').val(),indexsort_groupid:$('#indexsort_groupid').val()},function(result){
				alert('处理完成！正在刷新页面。');
				window.location.href = window.location.href;
			});
		});
		$('.see_qrcode').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看渠道二维码',
				padding: 0,
				width: 430,
				height: 433,
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