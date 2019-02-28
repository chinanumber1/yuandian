<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Card_new/index')}">会员卡</a>
			</li>
			<li class="active">会员卡优惠券</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<button class="btn btn-success" onclick="CreateShop()">新增优惠券</button>
					<a class="btn btn-success" href="{pigcms{:U('Card_new/send_coupon')}">派发优惠券</a>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th id="shopList_c1" width="100">ID</th>
									<th id="shopList_c1" width="100">名称</th>
									<th id="shopList_c1" width="100">图片</th>
									<th id="shopList_c1" width="100">二维码</th>
									<th id="shopList_c1" width="100">使用平台</th>
									<th id="shopList_c1" width="100">使用类别</th>
									<th id="shopList_c1" width="100">使用分类</th>
									<th id="shopList_c1" width="100">总数</th>
									<th id="shopList_c1" width="100">已领取</th>
									<th id="shopList_c1" width="100">已使用</th>
									<th id="shopList_c1" width="100">起始时间</th>
									<th id="shopList_c1" width="100">满减条件</th>
									<th id="shopList_c1" width="100">只允许新用户</th>
									<th id="shopList_c1" width="100">状态</th>
									<th id="shopList_c1" width="100">编辑</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$coupon_list">
									<volist name="coupon_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td>{pigcms{$vo.coupon_id}</td>
										<td>{pigcms{$vo.name}</td>
										<td><img src="{pigcms{$vo.img}" style="width:30px;height:30px"></td>
										<td><if condition="!empty($vo['wx_cardid'])">
											<a href="{pigcms{:U('Card_new/see_qrcode',array('id'=>$vo['coupon_id'],'wx_qrcode'=>1))}" class="see_qrcode" >查看二维码</a>
											<elseif condition="$vo.is_wx_card" />
												同步微信卡包失败
											<else />
												没有同步到微信
											</if>
										</td>
										<td><volist name="vo.platform" id="vv">{pigcms{$platform[$vv]}&nbsp;&nbsp;</volist></td>
										<td><if condition="$vo.cate_name eq 'all'">全部类别<else />{pigcms{$category[$vo['cate_name']]}</if></td>
										<td><if condition="$vo.cate_id eq '0'">全部分类<else />{pigcms{$vo['cate_id']}</if></td>
										<td>{pigcms{$vo.num}</td>
										<td>{pigcms{$vo.had_pull}</td>
										<td>{pigcms{$vo.use_count}</td>
										<td>{pigcms{$vo.start_time|date='Y-m-d',###} 到 {pigcms{$vo.end_time|date='Y-m-d',###}</td>
										<td>满 {pigcms{$vo.order_money} 减 {pigcms{$vo.discount} 元</td>
										<td class="textcenter"><if condition="$vo['allow_new'] eq '1'"><font color="green">是</font><else /><font color="red">否</font></if></td>
										<td class="textcenter"><if condition="$vo['status'] eq 1"><font color="green">启用</font><elseif condition="$vo['status'] eq 2"/><font color="blue">超过期限</font><elseif condition="$vo['status'] eq 3" /><font color="black">领完了</font><else /><font color="red">不启用</font></if></td>
										<td class="textcenter"><a href="{pigcms{:U('Card_new/edit_coupon',array('coupon_id'=>$vo['coupon_id']))}"> 编辑</a>
										<if condition="$vo.status neq 1">|<a href="{pigcms{:U('Card_new/delete_coupon',array('coupon_id'=>$vo['coupon_id']))}"> 删除</a></if>
										
										</td>
									
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="15" >无内容</td></tr>
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
				title:'编辑',
				padding: 0,
				width: 800,
				height: 600,
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
				title:'查看二维码',
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
	
	function CreateShop(){
		window.location.href = "{pigcms{:U('Card_new/add_coupon')}";
	}
	
</script>
<include file="Public:footer"/>
