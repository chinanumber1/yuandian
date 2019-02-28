<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-qrcode"></i>
				<a href="{pigcms{:U('Promote/index')}">商家推广</a>
			</li>
			<li class="active">渠道二维码管理</li>
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
			
					<button class="btn btn-success" onclick="CreateShop()">添加二维码消息</button>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="120">编号</th>
									<th>标题</th>
									<th>二维码</th>
									<th>添加时间</th>
									<th>最后修改时间</th>
									<th width="100">状态</th>
									<th width="200" style="text-align:center;">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$chanel_list">
									<volist name="chanel_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.chanel_id}</td>
											<td>{pigcms{$vo.title}</td>
											<td><if condition="($vo.qrcode_id) and ($vo.status  neq 1) ">二维码不可用<else /><a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_qrcode&type=chanel&id={pigcms{$vo.chanel_id}" class="see_qrcode">查看二维码</a></if></td>
											<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
											<td>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
											<td>
											<if condition="$vo['status'] eq 0"><label class="btn btn-warning">审核中</label>
											<elseif condition="$vo['status'] eq 1" /><label class="btn btn-success">已通过</label>
											<else/><label class="btn btn-danger">被拒绝</label></if>
											</td>
											<td class="button-column">
												<a class="green" style="padding-right:8px;" href="{pigcms{:U('ChanelList/edit', array('chanel_id'=>$vo['chanel_id']))}" >
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>　
												<a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('ChanelList/del',array('chanel_id'=>$vo['chanel_id']))}">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>　
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="11" >您没有添加过渠道消息！</td></tr>
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
		window.location.href = "{pigcms{:U('ChanelList/add')}";
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
