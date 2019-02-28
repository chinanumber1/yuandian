<include file="Public:header"/>

<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Sub_card/index')}">免单套餐</a>
			</li>
			<li class="active">免单消费列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<!--a class="btn btn-success" href="{pigcms{:U('Card_new/card_edit')}" >编辑会员卡</a>
					<button class="btn btn-success handle_btn" data-title="新增会员" href="{pigcms{:U('Card_new/add_user')}" >新增会员</button-->
					
					<form action="{pigcms{:U('Sub_card/order_list')}" method="get">
						<input type="hidden" name="c" value="Sub_card"/>
						<input type="hidden" name="a" value="order_list"/>
						
						
						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<select name="searchtype">
								<option value="pass" <if condition="$_GET['searchtype'] eq 'pass'">selected="selected"</if>>消费码</option>
		
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>
							</select>
							<input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}" style="width:200px;"/>
						</div>
						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<font color="#000">日期筛选：</font>
							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;-&nbsp;			   
							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
						</div>
					
						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<input type="submit" value="查询" class="btn btn-success" style="padding:2px 14px;"/>　
							<!--a href="{pigcms{:U('Store/group_export',$_GET)}" class="down_excel" style="float:right;padding:8px 14px;border:1px solid #629b58;color:#629b58;">导出订单</a-->
						</div>
					</form>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>订单号</th>
								<th>用户信息</th>
								<th>订单金额</th>
						
								<th>消费码</th>
								<th>支付时间</th>
								<th>操作店员</th>
							</tr>
						</thead>
						<tbody>
							<volist name="order_list" id="vo">
								<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
									<td><div class="tagDiv">{pigcms{$vo.id}</div></td>
									<td>昵称：{pigcms{$vo['nickname']}<br/>手机：{pigcms{$vo['phone']}</td>
									<td>{pigcms{$vo['price']/$vo['free_total_num']|floatval}</td>
								
							
									<td>{pigcms{$vo.pass}</td>
									<td><if condition="$vo.use_time gt 0">{pigcms{$vo.use_time|date="Y-m-d H:i:s",###}</if></td>
									<td>{pigcms{$vo.last_staff}</td>
								</tr>
							</volist>
						</tbody>
					</table>
					{pigcms{$pagebar}
		
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
