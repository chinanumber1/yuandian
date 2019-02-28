<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Index/account')}" >账号列表</a>|
					<a href="{pigcms{:U('Index/authority_group')}" class="on">权限套餐</a>|
					<a href="javascript:void(0);"  onclick="window.top.artiframe('{pigcms{:U('Index/authority_add')}','添加权限分组',800,500,true,false,false,addbtn,'add',true);">添加权限套餐</a>
				</ul>
			</div>
			
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup><col> <col> <col> <col width="240" align="center"> </colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>名称</th>
								<th>添加时间</th>
								
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($list)">
								<volist name="list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.name}</td>
										<td><if condition="$vo['add_time']">{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}<else/>无</if></td>
										<td class="textcenter">
									
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Index/authority_add',array('id'=>$vo['id']))}','编辑管理账号信息',800,500,true,false,false,editbtn,'edit',true);">编辑</a>
										
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="4">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="4">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script type="text/javascript" src="{pigcms{$static_path}js/area_pca.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
var test;
$(document).ready(function(){
	$('.see_qrcode').live('click', function(){
		test = art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'扫描二维码绑定微信号',
			padding: 0,
			width: 430,
			height: 433,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: function(){clearInterval(t);},
			left: '50%',
			top: '38.2%',
			opacity:'0.4'
		});
		var id = $(this).attr('data-id'), obj = $(this);
	 	var t = window.setInterval(function(){
			$.get("{pigcms{:U('Index/check_account')}", {id:id},  function(result){
				if (result.error_code == 0) {
					test.close();
					clearInterval(t);
					obj.parent('td').html(result.nickname).siblings('td').children('.cancel').show();
				}
			}, 'json');
		},3000);
		return false;
	});

	$('.cancel').click(function(){
		var id = $(this).attr('data-id'), obj = $(this);
		obj.attr('disabled', true);
		$.get("{pigcms{:U('Index/cancel_account')}", {id:id}, function(result){
			obj.attr('disabled', false);
			if (result.error_code == 1) {
				alert(result.msg);
			} else {
				var qrcode_id = 3890000000 + id;
				obj.hide().parent('td').siblings('.nickname').html('<a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_tmp_qrcode&qrcode_id=' + qrcode_id + '&img=1" data-id="' + id + '" class="see_qrcode" style="color:green">绑定微信号</a>');
			}
		}, 'json');
	});
});
</script>
<include file="Public:footer"/>