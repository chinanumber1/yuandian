<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Config/store')}">店铺管理</a>
			</li>
			<li class="active">店铺列表</li>
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
				    <if condition="empty($merchant_session['store_id'])">
					<button class="btn btn-success" onclick="CreateShop()">新建店铺</button>
					</if>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>编号</th>
									<th>排序</th>
									<th>店铺名称</th>
									<th>店铺基本信息</th>
									<!--th>联系电话</th>
									<th>店铺地址</th>
									<th>{pigcms{$config.meal_alias_name}</th>
									<th>{pigcms{$config.group_alias_name}</th>
									<th>{pigcms{$config.shop_alias_name}</th-->
									<th>店铺状态</th>
									<th>{pigcms{$config.shop_alias_name}资质审核状态  <if condition="$config['store_shop_auth'] eq 0"><b style="color:red">(平台未开启)</b></if></th>
									<th>微信二维码</th>
									<if condition="$config['store_open_wxapp_qrcode']">
										<th>小程序二维码</th>
									</if>
									<th>自定义页面</th>
									<th>店员管理</th>
									<th class="button-column" style="width:120px;">线下支付方式</th>
									<th class="button-column" style="width:100px;">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$store_list">
									<volist name="store_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.store_id}</td>
											<td>{pigcms{$vo.sort}</td>
											<td>{pigcms{$vo.name}</td>
											<td><a href="javascript:;" class="store_info_more" data-store_name="{pigcms{$vo.name}" data-phone="{pigcms{$vo.phone}" data-address="{pigcms{$vo.area_name} - {pigcms{$vo.adress}" data-hasmeal="<if condition="$vo['have_meal']">有<else/>无</if>" data-hasgroup="<if condition="$vo['have_group']">有<else/>无</if>" data-hasshop="<if condition="$vo['have_shop']">有<else/>无</if>" data-store_qrcode_url="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_qrcode&type=merchantstore&id={pigcms{$vo['store_id']}&img=1" data-store_status="{pigcms{$vo['status']}">查看</a></td>
											<!--td>{pigcms{$vo.phone}</td>
											<td>{pigcms{$vo.area_name} - {pigcms{$vo.adress}</td>
											<td><if condition="$vo['have_meal']">有<else/>无</if></td>
											<td><if condition="$vo['have_group']">有<else/>无</if></td>
											<td><if condition="$vo['have_shop']">有<else/>无</if></td-->
											<td>
												<switch name="vo['status']">
													<case value="0">关闭</case>
													<case value="1">正常</case>
													<case value="2">审核中</case>
												</switch>
											</td>
											<td>
												<switch name="vo['auth']">
													<case value="0"><b style="color:#723f23">未提交 </b>　<a href="{pigcms{:U('Config/auth_edit',array('id'=>$vo['store_id']))}">提交</a></case>
													<case value="1"><b style="color:blue">审核中</b>　<a href="{pigcms{:U('Config/auth_edit',array('id'=>$vo['store_id']))}">修改</a></case>
													<case value="2"><b style="color:red">已拒绝</b>　<a href="{pigcms{:U('Config/auth_edit',array('id'=>$vo['store_id']))}">修改</a></case>
													<case value="3"><b style="color:green">已通过</b>　<a href="{pigcms{:U('Config/auth_edit',array('id'=>$vo['store_id']))}">修改</a></case>
													<case value="4"><b style="color:blue">审核中</b>　<a href="{pigcms{:U('Config/auth_edit',array('id'=>$vo['store_id']))}">修改</a></case>
													<case value="5"><b style="color:red">已驳回</b>　<a href="{pigcms{:U('Config/auth_edit',array('id'=>$vo['store_id']))}">修改</a></case>
												</switch>
											</td>
											
											<if condition="$vo['status'] neq 2">
												<td>
													<a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_qrcode&type=merchantstore&id={pigcms{$vo['store_id']}&img=1" class="see_qrcode">查看二维码</a>
												</td>
												<if condition="$config['store_open_wxapp_qrcode']">
													<td>
														<a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition_wxapp&a=see_qrcode&type=merchantstore&id={pigcms{$vo['store_id']}&img=1" class="see_qrcode_wxapp">查看二维码</a>
													</td>
												</if>
												<td>
													<a href="{pigcms{:U('Diypage/index',array('store_id'=>$vo['store_id']))}">自定义页面</a>
												</td>
												<td>
													<a class="label label-sm label-info" title="店员管理" href="{pigcms{:U('Config/staff',array('store_id'=>$vo['store_id']))}">店员管理</a>
												</td>
												<td class="button-column">
													<a style="width: 60px;" class="label label-sm label-info" title="支付类型管理" href="{pigcms{:U('Config/pay', array('store_id'=>$vo['store_id']))}">查看</a>
												</td>
											<else/>
												<td>--</td>
												<if condition="$config['store_open_wxapp_qrcode']">
													<td>--</td>
												</if>
												<td>--</td>
												<td>--</td>
												<td>--</td>
											</if>
												
											
											<td class="button-column" nowrap="nowrap">
												<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Config/store_edit',array('id'=>$vo['store_id']))}">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>
												<if condition="empty($merchant_session['store_id'])">
												<a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('Config/store_del',array('id'=>$vo['store_id']))}">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
												</if>
											</td>
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
<script type="text/javascript">
$(function(){
	/*店铺状态*/
	updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");
	
	jQuery(document).on('click','#shopList a.red',function(){
		if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
	});
});
function CreateShop(){
	window.location.href = "{pigcms{:U('Config/store_add')}";
}
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
			url:"{pigcms{:U('Config/store_status')}",
			type:"post",
			data:{"type":type,"id":id,"status1":status1,"status2":status2,"attribute":attribute},
			dataType:"text",
			success:function(d){
				if(!d){		//失败
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
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
var shopName = '{pigcms{$config["shop_alias_name"]}', groupName = '{pigcms{$config["group_alias_name"]}', mealName = '{pigcms{$config["meal_alias_name"]}';
	$(function(){
		$('.see_qrcode').live('click',function(){
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
		$('.see_qrcode_wxapp').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看小程序二维码',
				padding: 0,
				width: 430,
				height: 430,
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
		
		$('.store_info_more').click(function(){
			var content = '<p>店铺名称：'+$(this).data('store_name')+'</p>';
				content+= '<p>联系电话：'+$(this).data('phone')+'</p>';
				content+= '<p>店铺地址：'+$(this).data('address')+'</p>';
				content+= '<p>' + mealName + '：'+$(this).data('hasmeal')+'</p>';
				content+= '<p>' + groupName + '：'+$(this).data('hasgroup')+'</p>';
				content+= '<p>' + shopName + '：'+$(this).data('hasshop')+'</p>';
			if($(this).data('store_status') != '2'){
				content+= '<p>店铺二维码：<a href="'+$(this).data('store_qrcode_url')+'" class="see_qrcode">查看二维码</a></p>';
			}
			art.dialog({
				title: '店铺基本信息',
				content: content,
				lock: true
			});
		});
		
		
	});
</script>
<script type="text/javascript">
	<if condition="$_GET['page_from'] eq 'shop_fitment' && $_GET['page'] elt 1">
		<if condition="count($store_list) eq 1">
			location.href = "{pigcms{:U('Diypage/index',array('store_id'=>$store_list[0]['store_id']))}";
		</if>
	</if>
</script>
<include file="Public:footer"/>
