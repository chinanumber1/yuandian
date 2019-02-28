<include file="Public:header"/>
<div class="main-content">
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li class="active">店铺导航管理</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div id="shopList" class="grid-view">
						<a class="btn btn-success handle_btn" href="{pigcms{:U('Foodshop/store_slider_add', array('store_id' => $_GET['store_id']))}" style="margin-bottom:5px;">添加导航</a>
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="50">排序</th>
									<th width="50">编号</th>
									<th width="50">名称</th>
									<th width="50">链接地址</th>
									<th class="button-column" width="150">	图片(以下为强制小图，点击图片查看大图)</th>
									<th class="button-column" width="140">状态</th>
									<th class="button-column" width="140">最后操作时间</th>
									<th class="button-column" width="140">操作</th>
									
								</tr>
							</thead>
							<tbody>
								<if condition="$slider_list">
									<volist name="slider_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td><div class="tagDiv">{pigcms{$vo.sort}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.id}</div></td>
											<td><div class="shopNameDiv">{pigcms{$vo.name}</div></td>
											<td><a href="{pigcms{$vo.url}" >访问链接</a></td>
											
											
											<td>
												<if condition="$vo['pic']">
													<img src="{pigcms{$config.site_url}/upload/slider/{pigcms{$vo.pic}" style="width:50px;height:50px;" class="view_msg"/>
												<else/>
													没有图片
												</if>
											</td>
									
											<td class="textcenter"><if condition="$vo.status eq 0">禁止<else />正常</if></td>
											<td class="textcenter">{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
											<td>
											
											<a class="handle_btn" href="{pigcms{:U('store_slider_add',array('id'=>$vo['id'],'store_id'=>$_GET['store_id']))}">编辑</a> | <a onclick="del('{pigcms{:U('slider_del',array('id'=>$vo['id']))}')" href="javascript:void(0)" >删除</a>
											</td>
										
											
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="12" >您没有添加导航</td></tr>
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
		$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'导航管理',
				padding: 0,
				width: 600,
				height: 370,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: function(){
					window.location.reload()
				},
				left: '50%',
				top: '38.2%',
				opacity:'0.4',
				
			});
			return false;
		});
		
		$('.view_msg').click(function(){
				window.top.art.dialog({
					padding: 0,
					title: '大图',
					content: '<img src="'+$(this).attr('src')+'" style="width:600px;height:400px;" />',
					lock: true
				});
			});
	});
	
	function del(url){
	
		if(confirm('确定删除？')){
			window.location.href=url
		}else{
			window.location.reload();
		}
	}
</script>
<include file="Public:footer"/>
