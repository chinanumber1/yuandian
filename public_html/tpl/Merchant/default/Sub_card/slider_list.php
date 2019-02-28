<include file="Public:header"/>

<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Sub_card/index')}">免单套餐</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Sub_card/my_join',array('id'=>$_GET['sub_card_id']))}">我的参与</a></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
				
					<a class="btn btn-success handle_btn"  href="{pigcms{:U('Sub_card/add_slider',array('sub_card_id'=>$_GET['sub_card_id'],'store_id'=>$_GET['store_id']))}"  >添加导航</a>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="5">编号</th>
									<th width="10">导航名称</th>
									<th width="40">图片</th>
									<th width="5">链接</th>
									<th width="10">状态</th>
									<th width="15" class="textcenter">编辑</th>
								</tr>
							</thead>
							<tbody>
							<if condition="is_array($slider_list)">
								<volist name="slider_list" id="vo">
									<tr>
										<td width="5">{pigcms{$vo.id}</td>
										<td width="10">{pigcms{$vo.name}</td>
										<td width="40"><img src="/upload/slider/{pigcms{$vo.pic}" style="width:30px;height:30px"></td>
										<td width="5"><a href="{pigcms{$vo.url}" target="_blank">查看链接</a></td>
										
									
										<td width="10"><if condition="$vo.status eq 0"><font color="blue">禁止</font><elseif condition="$vo.status eq 1" /><font color="green">启用</font></if></td>
										
										
										<td width="15" class="textcenter">
											
											<a title="编辑自定义导航" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Sub_card/edit_slider',array('id'=>$vo['id'],'store_id'=>$vo['store_id'],'sub_card_id'=>$vo['sub_card_id']))}">
												编辑
											</a>
											|
											<a title="编辑自定义导航" class="green" style="padding-right:8px;" href="{pigcms{:U('Sub_card/slider_del',array('id'=>$vo['id']))}">
												删除
											</a>
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="9">列表为空！</td></tr>
							</if>
							</tbody>
						</table>
		
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.my_join{
		border: 1px solid #428bca;
		border-radius: 5px;
		padding: 0 6px;
	}
</style>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
$(function(){

	$('.del').click(function(){
		if(!confirm('确定要删除这条数据吗?不可恢复。')) {
			return false;
		}else{
			window.location.href=$(this).attr('date-href')
		}
	})
});

function drop_confirm(msg, url)
{
	if (confirm(msg)) {
		window.location.href = url;
	}
}
</script>

<script>
var cardid='';
	$(function(){
		$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'编辑',
				padding: 0,
				width: 720,
				height: 520,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: function(){
					window.location.reload();
				},
				left: '50%',
				top: '38.2%',
				opacity:'0.4'

			});

			return false;
		});

		$('.see_qrcode').click(function(){
			cardid=$(this).attr('data-id');
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
				opacity:'0.4',
				cancel: function () {
					cardid = '';
				}
			});
			return false;
		});

		 var time = setInterval(function(){
			if(cardid!=''){
				$.post("{pigcms{:U('Card_new/ajax_get_bind_status')}", {cardid:cardid }, function(data, textStatus, xhr) {
					data = eval('('+data+')');
					if(data.error_code==0){
						cardid='';
						window.location.reload();
					}
				});

			}
        },1000);


		$('#group_id').change(function(){
			$('#frmselect').submit();
		});
	});
</script>



</script>
<include file="Public:footer"/>
