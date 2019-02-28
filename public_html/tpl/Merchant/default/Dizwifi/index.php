<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-cloud"></i>
			<li class="active">微硬件</li>
			<li class="active">微信链接WIFI</li>
		</ul>
	</div>
	<div class="alert alert-info" style="margin:10px;">
		<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>由于微信官方把连网二维码的链接加了防盗链导致无法直接显示二维码<br/>
		现在我们将这个链接以弹窗的形式在新的窗口打开，如果您的浏览器阻止弹窗式窗口，那么请您点击允许！<br/>
		如果在新的窗口还是无法显示，那么您将鼠标定位到地址栏，然后按回车键，【注意不是刷新】<br/>
		如果还是无法显示的话，那么把地址复制出来，重新打开其他浏览器，在里面输入这个地址打开！
		
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<div class="tab-content">
							<div class="tab-pane active">
								<a href="{pigcms{:U('Dizwifi/device')}" class="btn btn-success">增加WIFI设备</a>　　
								<a href="{pigcms{:U('Dizwifi/store')}" class="btn btn-success">查看店铺同步</a>
								<div id="shopList" class="grid-view">
									<table class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th width="100">店铺名称</th>
												<th width="100">无线网名称</th>
												<th width="100">连网二维码</th>
												<th width="100">主页</th>
												<th width="80" class="button-column">操作</th>
											</tr>
										</thead>
										<tbody>
											<if condition="$list">
												<volist name="list" id="row">
													<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
														<td>{pigcms{$row.shop_name}</td>
														<td>{pigcms{$row.ssid}</td>
														<td><a data-href="{pigcms{:U('Dizwifi/getcode',array('id'=>$row['id']))}" href="javascript:void(0);" class="see_qrcode">获取连网二维码</a></td>
														<td><a class="green" style="padding-right:8px;" href="{pigcms{:U('Dizwifi/sethomepage', array('id' => $row['id']))}" >设置商家主页</a></td>
														<td class="button-column">
															<a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('Dizwifi/DelDevice', array('id' => $row['id']))}">
																<i class="ace-icon fa fa-trash-o bigger-130"></i>删除路由
															</a>
														</td>
													</tr>
												</volist>
											<else/>
												<tr class="odd"><td class="button-column" colspan="5" >无内容</td></tr>
											</if>
										</tbody>
									</table>
								</div>
							</div>
						</div>
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
		$('.see_qrcode_blank').click(function(){
			$.post($(this).data('href'), function(response){
				if (response.status == 1) {
					window.open(response.info);
				} else {
					art.dialog({
						content: response.info
					});
				}
			});
		});
		$('.see_qrcode').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'获取连网二维码',
				padding: 0,
				width: 440,
				height: 620,
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
