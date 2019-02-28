<include file="Public:header"/>
<style>
	img{height:30px;width:60px;}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Coupon/index')}">平台优惠券列表</a>
			
			<a href="{pigcms{:U('Coupon/send_coupon')}"  class="on" >派发优惠券</a>
		</ul>
	</div>
	<div id="nav" class="mainnav_title" style="margin-top:5px;">
		<ul  id="myTab">
			<a href="{pigcms{:U('send_coupon')}">
				等级派发
			</a>
			<a href="{pigcms{:U('send_all')}" >
				全部派发
			</a>
			<a data-toggle="tab" href="{pigcms{:U('send_person')}">
				个人派发
			</a>
			<a href="{pigcms{:U('weixin_send')}" >
				微信购买派发
			</a>
			<a href="{pigcms{:U('send_history')}" class="on">
				派发记录
			</a>
		</ul>
	</div>
			
			
				<div class="table-list">
					
					<table class="table table-striped table-bordered table-hover" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>优惠券名称</th>
								<th>派发时间</th>
								<th>派发对象</th>
								<th>派发结果</th>
							</tr>
						</thead>
						<tbody>
						<if condition="$history">
							<volist name="history" id="vo">
								<tr >
									<td style="width: 120px">{pigcms{$vo.id}</td>
									<td style="width: 120px">{pigcms{$vo.coupon_name}</td>
									<td style="width: 120px">{pigcms{$vo.add_time|date='Y-m-d',###}</td>
									<td style="width: 120px">{pigcms{$vo.nickname}</td>
									<td style="width: 120px"><if condition="$vo.error_code neq 0"><font color="red">派发失败</font>({pigcms{$vo.msg})<else /><font color="green">派发成功</font></if></td>
									
								</tr>
							</volist>
							<tr><td class="textcenter pagebar" colspan="5">{pigcms{$pagebar}</td></tr>
							
						<else />
							<tr ><td class="textcenter red" colspan="5" >无内容</td></tr>
						</if>
						</tbody>
					</table>
				
							
				</div>
	
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