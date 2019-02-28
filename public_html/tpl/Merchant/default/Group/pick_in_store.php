<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Group/pick_in_store')}">团购到店自提</a>
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
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="120">编号</th>
									<th>区域1</th>
									<th>区域2</th>
									<th>区域3</th>
									<th>自提点名称</th>
									<th>自提点电话</th>
					
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
											<if condition="$vo['pick_addr_id']">
												<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Group/pick_address_edit',array('id'=>$vo['pick_addr_id']))}">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>　　
												<a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('Group/pick_address_del',array('id'=>$vo['pick_addr_id']))}">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</if>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="11" >您没有添加过商品！</td></tr>
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
	function CreateShop(){
		window.location.href = "{pigcms{:U('Group/pick_address_add')}";
	}
	$(function(){
		$('.group_name').hover(function(){
			var top = $(this).offset().top;
			var left = $(this).offset().left+$(this).width()+10;
			$('body').append('<div id="group_name_div" style="position:absolute;z-index:5555;background:white;top:'+top+'px;left:'+left+'px;border:1px solid #ccc;padding:10px;"><div style="margin-bottom:10px;"><b>商品标题：</b>'+$(this).data('title')+'</div><div><b>商品图片：</b><img src="'+$(this).data('pic')+'" style="width:180px;"/></div></div>');
		},function(){
			$('#group_name_div').remove();
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
