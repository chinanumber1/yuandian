<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Config/pick')}">自提点管理</a>
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
					<button class="btn btn-success" onclick="CreateShop()">添加自提点</button>
					<a href="/pick.php?g=Merchant&c=Pick&a=login" class="btn btn-success" target="_blank">自提点登录</a>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="120">编号</th>
									<th>省份</th>
									<th>城市</th>
									<th>区域</th>
									<th>自提点名称</th>
									<th>自提点电话</th>
									<th>登录秘钥</th>
									<th width="200" style="text-align:center;">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$pick_addr">
									<volist name="pick_addr" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$key}</td>
											<td>{pigcms{$vo['area_info']['province']}</td>
											<td>{pigcms{$vo['area_info']['city']}</td>
											<td>{pigcms{$vo['area_info']['area']}</td>
											<td>{pigcms{$vo.name}</td>
											<td><if condition ="$vo.phone eq 0">无<else />{pigcms{$vo.phone}</if></td>
											<td style="text-align:center;">
												<a title="操作订单" class="green handle_btn" href="{pigcms{:U('Config/see_pick_pwd',array('pick_id'=>$vo['pick_addr_id']))}">查看</a>
											</td>
											<td style="text-align:center;">
											<if condition="$vo['pick_addr_id']">
												<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Config/pick_address_edit',array('id'=>$vo['pick_addr_id']))}">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>　　
												<a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('Config/pick_address_del',array('id'=>$vo['pick_addr_id']))}">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</if>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="11" >您没有添加过自提点！</td></tr>
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
		$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看登录秘钥',
				padding: 0,
				width: 520,
				height: 80,
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
	function CreateShop(){
		window.location.href = "{pigcms{:U('Config/pick_address_add')}";
	}
</script>
<include file="Public:footer"/>
