<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Card_new/index')}">会员卡</a>
			</li>
			<li class="active">会员卡用户分组</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					
					<button class="btn btn-success handle_btn" data-title="新增分组" href="{pigcms{:U('Card_new/add_card_group')}" >新增分组</button>
				
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th id="shopList_c1" width="100">分组ID</th>
									<th id="shopList_c1" width="100">分组名称</th>
									<th id="shopList_c1" width="100">分组注释</th>
									<th id="shopList_c1" width="100">分组用户数量</th>
					
									<th id="shopList_c1" width="180">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$card_group_list">
									<volist name="card_group_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.id}</td>
											<td>{pigcms{$vo.name}</td>
											<td>{pigcms{$vo.des}</td>
											<td>{pigcms{$vo.user_count}</td>
								
											<td class="button-column" nowrap="nowrap">
											
											<a title="查看详情" class="green handle_btn" data-title="查看详情" style="padding-right:8px;" href="{pigcms{:U('Card_new/add_card_group',array('gid'=>$vo['id']))}">
												<i class="ace-icon fa fa-search bigger-130">编辑</i>
											</a>
											<a title="查看分组用户" class= "green  " data-title="查看分组用户" style= href="""padding-right:8px;" href="{pigcms{:U('Card_new/card_group_user_list',array('gid'=>$vo['id']))}">
												<i class="ace-icon fa fa-search bigger-130">查看分组用户</i>
											</a>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="8" >无内容</td></tr>
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
