<include file="Public:header"/>
<style>
	img{height:30px;width:60px;}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Sub_card/index')}" >免单套餐列表</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Sub_card/add')}','添加免单',800,500,true,false,false,addbtn,'add_sub_card',true);">添加免单</a>
					<a href="{pigcms{:U('Sub_card/order_list')}"  class="on">免单订单列表</a>		
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Sub_card/order_list')}" method="get">
							<input type="hidden" name="c" value="Sub_card"/>
							<input type="hidden" name="a" value="order_list"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="order_id" <if condition="$_GET['searchtype'] eq 'order_id'">selected="selected"</if>>订单ID</option>
								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>用户昵称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>用户手机</option>
							</select>
							<font color="#000">日期筛选：</font>
							<input type="text" class="input-text" name="begin_time" style="width:80px;" id="begin_time"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
							<input type="text" class="input-text" name="end_time" style="width:80px;" id="end_time" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
							<input type="submit" value="查询" class="button"/>
						</form>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>订单ID</th>
								<th>用户信息</th>
								<th>套餐名称</th>
								<th>套餐价格</th>
								<th>购买时间</th>
								<th class="textcenter">消费详情</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($order_list)">
								<volist name="order_list" id="vo">
									<tr>
										<td>{pigcms{$vo.order_id}</td>
										<td>{pigcms{$vo.nickname}
											<br>
											{pigcms{$vo.phone}
										</td>
										<td>{pigcms{$vo.order_name}</td>
										
										<td>{pigcms{$vo.money|floatval}</td>
										<td><if condition="$vo.pay_time gt 0">{pigcms{$vo.pay_time|date='Y-m-d H:i:s',###}</if></td>
										<td class="textcenter"><a href="{pigcms{:U('Sub_card/consume_list',array('order_id'=>$vo['order_id']))}">查看</a></td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="7">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="7">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	$(function(){
		$('#indexsort_edit_btn').click(function(){
			$(this).prop('disabled',true).html('提交中...');
			$.post("/merchant.php?g=Merchant&c=Config&a=merchant_indexsort",{group_indexsort:$('#group_indexsort').val(),indexsort_groupid:$('#indexsort_groupid').val()},function(result){
				alert('处理完成！正在刷新页面。');
				window.location.href = window.location.href;
			});
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