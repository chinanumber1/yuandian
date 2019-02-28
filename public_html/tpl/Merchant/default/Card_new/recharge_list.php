<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Card_new/index')}">会员卡</a>
			</li>
			<li class="active">会员卡消费记录</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="50">会员卡ID</th>
									<th width="50">会员名称</th>
									<th width="50">会员手机</th>
									<th width="50">时间</th>
									<th id="shopList_c1" width="100">详情</th>
									<th id="shopList_c1" width="100">金额增加（元）</th>
									<th id="shopList_c1" width="100">金额减少（元）</th>
									<th id="shopList_c1" width="100">{pigcms{$config['score_name']}增加（分）</th>
									<th id="shopList_c1" width="100">{pigcms{$config['score_name']}减少（分）</th>
									<th id="shopList_c1" width="100">优惠券增加（元）</th>
									<th id="shopList_c1" width="100">优惠券减少（元）</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$record">
									<volist name="record" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											
											<td width="50">{pigcms{$vo.card_id}</td>
											<td width="50">{pigcms{$vo.nickname}</td>
											<td width="50">{pigcms{$vo.phone}</td>
											<td width="50">{pigcms{$vo.time|date='Y-m-d H:i:s',###}</td>
											<td>{pigcms{$vo['desc']}</td>
											<td><font color="#2bb8aa"><if condition="$vo.money_add neq 0">+</if>{pigcms{$vo.money_add}</font></td>
											<td><font color="#f76120"><if condition="$vo.money_use neq 0">-</if>{pigcms{$vo.money_use}</font></td>
											<td><font color="#2bb8aa"><if condition="$vo.score_add neq 0">+</if>{pigcms{$vo.score_add}</font></td>
											<td><font color="#f76120"><if condition="$vo.score_use neq 0">-</if>{pigcms{$vo.score_use}</font></td>
											<td><font color="#2bb8aa"><if condition="$vo.coupon_add neq 0">+</if>{pigcms{$vo.coupon_add}</font></td>
											<td><font color="#f76120"><if condition="$vo.coupon_use neq 0">-</if>{pigcms{$vo.coupon_use}</font></td>
										</tr>
										
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="11" >无内容</td></tr>
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
<script type="text/javascript">
$(function(){
	jQuery(document).on('click','#shopList a.red',function(){
		if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
	});
});

function drop_confirm(msg, url)
{
	if (confirm(msg)) {
		window.location.href = url;
	}
}
</script>

<script>
	$(function(){
		$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:$(this).data('title'),
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
				opacity:'0.4'
			});
			return false;
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
		
		$('#group_id').change(function(){
			$('#frmselect').submit();
		});
	});
</script>
<include file="Public:footer"/>
