<include file="Public:header"/>

<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Sub_card/index')}">免单套餐</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Sub_card/sub_card_mer_sale')}">订单列表</a></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<!--a class="btn btn-success" href="{pigcms{:U('Card_new/card_edit')}" >编辑会员卡</a>
					<button class="btn btn-success handle_btn" data-title="新增会员" href="{pigcms{:U('Card_new/add_user')}" >新增会员</button-->
					
					<form id="myform1" action="{pigcms{:U('Sub_card/sub_card_mer_sale')}" method="get">
							<input type="hidden" name="c" value="Sub_card"/>
							<input type="hidden" name="a" value="sub_card_mer_sale"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>免单名称</option>
								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>用户昵称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>用户手机</option>
								<option value="store_name" <if condition="$_GET['searchtype'] eq 'store_name'">selected="selected"</if>>店铺名称</option>
							</select>
							<input type="submit" value="查询" class="button"/>
							
						</form>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover" style="table-layout:fixed">
							<thead>
								<tr>
								<th>订单ID</th>
								<th>店铺名称</th>
								<th>套餐名称</th>
								<th>套餐描述</th>
								<th>购买次数</th>
								<th>用户昵称</th>
								<th>用户手机</th>
								<th>购买时间</th>
								<th class="textcenter">详情</th>
						
							</tr>
							</thead>
							<tbody>
							<if condition="is_array($list)">
								<volist name="list" id="vo">
									<tr>
										<td>{pigcms{$vo.fid}</td>
										<td>{pigcms{$vo.store_name}</td>
										<td class="td_txt">{pigcms{$vo.name}</td>
										<td class="td_txt">{pigcms{$vo.desc}</td>
										<td>{pigcms{$vo.num}</td>
										<td>{pigcms{$vo.nickname}</td>
										<td>{pigcms{$vo.phone}</td>
										<td>{pigcms{$vo.add_time|date='Y-m-d',###} </td>
										<td class="textcenter">
											<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Sub_card/order_detail',array('id'=>$vo['fid'],store_id=>$vo['store_id']))}">
												<i class="ace-icon fa fa-search bigger-130">查看详情</i>
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
	.td_txt{
		width:300px; line-height:25px; text-overflow:ellipsis; white-space:nowrap; overflow:hidden;
	}
</style>
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
var cardid='';
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
				width: 900,
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
