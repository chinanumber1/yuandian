<include file="Public:header"/>

<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Sub_card/index')}">免单套餐</a>
			</li>
			<li class="active">参加套餐【{pigcms{$package_name}】的店铺</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
				
					<!--form id="myform1" action="{pigcms{:U('Sub_card/index')}" method="get
							<input type="hidden" name="c" value="Sub_card"/>
							<input type="hidden" name="a" value="index"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>名称</option>
								<option value="Sub_card_id" <if condition="$_GET['searchtype'] eq 'Sub_card_id'">selected="selected"</if>>免单ID</option>
							</select>
							<input type="submit" value="查询" class="button"/>
							
						</form-->
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="5">编号</th>
									<th width="10">店铺名称</th>
									<th width="40">描述</th>
									<th width="40">详细描述</th>
									<th width="5">是否要预约</th>
									<th width="10">申请时间</th>
									<th width="5">库存</th>
									<th width="5">销量</th>
									<th width="10">状态</th>
									<th width="15" class="textcenter">编辑</th>
								</tr>
							</thead>
							<tbody>
							<if condition="is_array($join_list)">
								<volist name="join_list" id="vo">
									<tr>
										<td width="5">{pigcms{$vo.id}</td>
										<td width="10">{pigcms{$vo.name}</td>
										<td width="40">{pigcms{$vo.desc|html_entity_decode}</td>
										<td width="40">{pigcms{$vo.desc_txt|html_entity_decode}</td>
										<td width="5"><if condition="$vo.appoint eq 1"><font color="blue">是</font><else /><font color="red">否</font></if></td>
										<td width="10">{pigcms{$vo.apply_time|date='Y-m-d',###}</td>
										<td width="5">{pigcms{$vo.sku}</td>
										<td width="5">{pigcms{$vo.sale_count}</td>
										<td width="10"><if condition="$vo.status eq 0"><font color="blue">待审核</font><elseif condition="$vo.status eq 1" /><font color="green">通过</font></if></td>
										
										
										<td width="15" class="textcenter">
											<if condition="$vo.status neq 1">
										
											<a title="删除" class="red  del" style="padding-right:8px;" href="javascript:void(0)" date-href="{pigcms{:U('Sub_card/del_join_store',array('id'=>$vo['id'],'store_id'=>$vo['store_id'],'sub_card_id'=>$vo['sub_card_id']))}">
												<i class="ace-icon fa fa-trash-o bigger-130"></i>删除
											</a>
											</if>
											<a title="编辑自定义导航" class="green" style="padding-right:8px;" href="{pigcms{:U('Sub_card/slider_list',array('id'=>$vo['id'],'store_id'=>$vo['store_id'],'sub_card_id'=>$vo['sub_card_id']))}">
												编辑自定义导航
											</a>
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="10">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="10">列表为空！</td></tr>
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
				close: null,
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
