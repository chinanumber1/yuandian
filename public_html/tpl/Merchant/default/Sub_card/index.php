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
								<option value="sub_card_id" <if condition="$_GET['searchtype'] eq 'sub_card_id'">selected="selected"</if>>免单ID</option>
							</select>
							<input type="submit" value="查询" class="button"/>
							
						</form>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover" style="table-layout:fixed">
							<thead>
								<tr>
								<th>ID</th>
								<th>套餐名称</th>
								<th width="200">套餐描述</th>
								<th>套餐价格</th>
								<th>总次数</th>
								<th>一个商家免单次数</th>
								<th>店铺最大参加数量</th>
								<th>已参与店铺数量</th>
								<th>通过审核店铺数量</th>
								<th>购买有效期</th>
								<th>购买后有效天数</th>
								<th>抽成比例</th>
								<th>状态</th>
								<th class="textcenter" width="200">编辑</th>
							</tr>
							</thead>
							<tbody>
							<if condition="is_array($sub_card_list)">
								<volist name="sub_card_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td class="td_txt">{pigcms{$vo.name}</td>
										<td class="td_txt">{pigcms{$vo.desc}</td>
										<td>{pigcms{$vo.price|floatval}</td>
										<td>{pigcms{$vo.free_total_num}</td>
										<td>{pigcms{$vo.mer_free_num}</td>
										<td>{pigcms{$vo.store_max_join_num}</td>
										<td>{pigcms{$vo.store_join_num}</td>
										<td><if condition="$vo.join_num gt 0">{pigcms{$vo.join_num}<else />0</if></td>
										<td><if condition="$vo.buy_time_type eq 1">{pigcms{$vo.start_time|date='Y-m-d',###} 到 {pigcms{$vo.end_time|date='Y-m-d',###}<else />无限时</if></td>
										<td><if condition="$vo.use_time_type eq 1">{pigcms{$vo.effective_days}天<else />永久有效</if></td>
										<td>{pigcms{$vo.percent}</td>
										<td>
											<if condition="$vo.status eq 0">
												<font color="red">未开启</font>
											<elseif condition="$vo.status eq 1" />
												<font color="green">进行中</font>
											<elseif condition="$vo.status eq 3" />
												<font color="red">已过期</font>
											</if>
										</td>
										
										<td class="textcenter"  width="200">
											<if condition="$vo['store_max_join_num'] neq 0 AND $vo['store_max_join_num'] eq $vo['store_join_num'] AND empty($vo['sub_card_id']) ">
												<font color="red">已满额</font>
											<elseif condition="empty($vo['sub_card_id']) AND $vo.status eq 1" />
												<a title="去参与" data-url="去参与" class="green " style="padding-right:8px;" href="{pigcms{:U('Sub_card/join_card',array('id'=>$vo['id']))}">
													<i class="ace-icon fa fa-search bigger-130"></i>去参与
												</a>
												
											</if>
												
											<if condition="!empty($vo['sub_card_id'])" >
												<a title="编辑" data-url="编辑" class="green " style="padding-right:8px;" href="{pigcms{:U('Sub_card/join_card',array('id'=>$vo['id']))}">
													<i class="ace-icon fa fa-search bigger-130"></i>编辑
												</a>
												<a class="my_join" href="{pigcms{:U('my_join',array('id'=>$vo['id']))}">我的参与</a>
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
				title:'编辑',
				padding: 0,
				width: 1400,
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
