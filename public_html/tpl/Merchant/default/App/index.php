<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('App/index')}">App在线打包</a>
			</li>
			<li class="active">App列表</li>
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
					<button class="btn btn-success" onclick="CreateShop()">创建应用</button>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="120">编号</th>
									<th>名称</th>
									<th>网址</th>
									<th>平台</th>
									<th>系统标题栏</th>
									<th>横竖屏</th>
									<th>创建时间</th>
									<th width="200" style="text-align:center;">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$app_list">
									<volist name="app_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.pigcms_id}</td>
											<td>{pigcms{$vo.name}</td>
											<td><a href="{pigcms{$vo.webUrl}" target="_blank">浏览网址</a></td>
											<td><if condition="$vo['appType']">IOS<else/>安卓</if></td>
											<td><if condition="$vo['hideTop']">隐藏<else/>显示</if></td>
											<td><if condition="$vo['screen']">横屏<else/>竖屏</if></td>
											<td>{pigcms{$vo.addTime|date='Y-m-d H:i:s',###}</td>
											<td style="text-align:center;"><a style="width:60px;" class="label label-sm label-info handle_btn" title="下载" href="{pigcms{:U('App/download',array('id'=>$vo['pigcms_id']))}">下载</a></td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="11" >您还没有创建过应用</td></tr>
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
<script>
	function CreateShop(){
		window.location.href = "{pigcms{:U('App/add')}";
	}
	$(function(){
		$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'下载应用',
				padding: 0,
				width: 350,
				height: 380,
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
