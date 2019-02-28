<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Robot/index')}">拼团机器人管理</a>
			</li>
			<li class="active">机器人列表</li>
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
				
					<a class="btn btn-success handle_btn" href="{pigcms{:U('add_robot')}" style="float:left;margin-bottom:5px;">添加机器人</a>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="120">编号</th>
									<th>机器人名称</th>
									<th>机器人头像</th>
									<th>添加时间</th>
									<th width="200" style="text-align:center;">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$robot_list">
									<volist name="robot_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.id}</td>
										
											<td>{pigcms{$vo.robot_name}</td>
											<td><img style="width:60px;height:60px;" src="{pigcms{$vo.avatar}"></td>
											<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
										
											<td style="text-align:center;"><a href="{pigcms{:U('del',array('id'=>$vo['id']))}">删除</a></td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="11" >没有内容</td></tr>
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
				title:'添加机器人',
				padding: 0,
				width: 800,
				height: 300,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
		
				left: '50%',
				top: '38.2%',
				opacity:'0.4',
				close: function () {
					window.location.href = window.location.href;
				}
			});
			return false;
		});
	});

</script>


<include file="Public:footer"/>
