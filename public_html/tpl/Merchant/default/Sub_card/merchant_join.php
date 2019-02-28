<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Sub_card/index')}">免单套餐</a>
			</li>
			<li class="active">免单套餐列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<!--a class="btn btn-success" href="{pigcms{:U('Card_new/card_edit')}" >编辑会员卡</a>
					<button class="btn btn-success handle_btn" data-title="新增会员" href="{pigcms{:U('Card_new/add_user')}" >新增会员</button-->
					
					<form id="myform1" action="{pigcms{:U('Sub_card/index')}" method="get">
							<input type="hidden" name="c" value="Sub_card"/>
							<input type="hidden" name="a" value="index"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>名称</option>
								<option value="Sub_card_id" <if condition="$_GET['searchtype'] eq 'Sub_card_id'">selected="selected"</if>>免单ID</option>
							</select>
							<input type="submit" value="查询" class="button"/>
							
						</form>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
								<th>ID</th>
								<th>套餐名称</th>
								<th>套餐描述</th>
								<th>套餐价格</th>
								<th>总次数/剩余总次数</th>
								<th>一个商家免单次数</th>
								<th>可参与商家数量</th>
								<th>已通过商家数量</th>
								<th>有效期</th>
								<th>购买后有效天数</th>
								<th>抽成比例</th>
								
								<th class="textcenter">编辑</th>
							</tr>
							</thead>
							<tbody>
								<if condition="is_array($sub_card_list)">
								<volist name="sub_card_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.desc}</td>
										<td>{pigcms{$vo.price|floatval}</td>
										<td>{pigcms{$vo.free_total_num}</td>
										<td>{pigcms{$vo.mer_free_num}</td>
										<td>{pigcms{$vo.mer_join_num}</td>
										<td><if condition="$vo.join_num gt 0">{pigcms{$vo.join_num}<else />0</if></td>
										<td>{pigcms{$vo.start_time|date='Y-m-d',###} 到 {pigcms{$vo.end_time|date='Y-m-d',###}</td>
										<td>{pigcms{$vo.effective_days}天</td>
										<td>{pigcms{$vo.percent}</td>
										
									
										<td class="textcenter">
											<if condition="in_array($vo['id'],$join_card)">
												<php>if($mer_sub_card_list[$vo['id']]['status']==1){</php>
													 <font color="red">已通过</font>
												<php>}elseif($mer_sub_card_list[$vo['id']]['status']==2){</php>
													 <font color="red">已过期</font>
												<php>}elseif($mer_sub_card_list[$vo['id']]['status']==4){</php>
													 <font color="red">未通过</font>
												<php>}elseif($mer_sub_card_list[$vo['id']]['status']==0){</php>
													 <font color="red">审核中</font>
												<php>}</php>
											
											
											<else />
												<a title="去参与" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Group/order_detail',array('order_id'=>$vo['order_id']))}">
													<i class="ace-icon fa fa-search bigger-130"></i>去参与
												</a>
											</if>
										
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="14">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="14">列表为空！</td></tr>
							</if>
							</tbody>
						</table>
		
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
