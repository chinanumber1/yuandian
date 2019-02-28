<include file="Public:header"/>
<style>
	img{height:30px;width:60px;}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Sub_card/index')}" class="on">免单套餐列表</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Sub_card/add')}','添加免单',800,500,true,false,false,addbtn,'add_sub_card',true);">添加免单</a>
					<a href="{pigcms{:U('Sub_card/order_list')}" >免单订单列表</a>	
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form id="myform1" action="{pigcms{:U('Sub_card/index')}" method="get">
							<input type="hidden" name="c" value="Sub_card"/>
							<input type="hidden" name="a" value="index"/>
							<if condition="$now_area['area_type'] lt 2 OR $system_session['level'] eq 2 ">
								选择城市：
								<div id="choose_pca" province_idss="{pigcms{$_GET.province_idss}" city_idss="{pigcms{$_GET.city_idss}"  style="display:inline"></div>
								
							</if>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>名称</option>
								<option value="id" <if condition="$_GET['searchtype'] eq 'id'">selected="selected"</if>>免单ID</option>
							</select>
							
							<input type="submit" value="查询" class="button"/>
							状态:
							<select name="status">
								<option value="-1" <if condition="$_GET['status'] eq -1 OR !isset($_GET['status'])">selected="selected"</if>>全部</option>
								<option value="1" <if condition="$_GET['status'] eq 1">selected="selected"</if>>启用</option>
								<option value="0" <if condition="$_GET['status'] eq 0 AND isset($_GET['status'])">selected="selected"</if>>关闭</option>
								<option value="3" <if condition="$_GET['status'] eq 3 AND isset($_GET['status'])">selected="selected"</if>>过期</option>
							</select>
						</form>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0" style="table-layout: fixed;">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>ID</th>
								<th>套餐名称</th>
								<th>套餐描述</th>
								<th>套餐价格</th>
								<th>总次数</th>
								<th>一个商家最多可免单次数</th>
								<th>通过审核商家数量</th>
								<th>用户选择商家最大数</th>
								<th>店铺最大参与数量</th>
								<th>已参与商家数量</th>
								<th>已参与店铺数量</th>
								<th>通过审核店铺数量</th>
								<th>购买有效期</th>
								<th>购买后有效天数</th>
								<th>使用区域</th>
								<th>抽成比例</th>
								<th>状态</th>
								<th class="textcenter">编辑</th>
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
										<td>{pigcms{$vo.mer_pass_num|floatval}</td>
										<td>{pigcms{$vo.user_mer_max_select}</td>
										<td>{pigcms{$vo.store_max_join_num}</td>
										<td>{pigcms{$vo.mer_join_num|floatval}</td>
										<td>{pigcms{$vo.store_join_num|floatval}</td>
										<td><if condition="$vo.join_num gt 0">{pigcms{$vo.join_num}<else />0</if></td>
										<td><if condition="$vo.buy_time_type eq 1">{pigcms{$vo.start_time|date='Y-m-d',###} 到 {pigcms{$vo.end_time|date='Y-m-d',###}<else />无限时</if></td>
										<td><if condition="$vo.use_time_type eq 1">{pigcms{$vo.effective_days}天<else />永久有效</if></td>
										<td><if condition="$vo.use_area eq 1"><a href="javascript:void(0);" class="edit_area" onclick="window.top.artiframe('{pigcms{:U('Sub_card/edit_area',array('sub_cardid'=>$vo['id']))}','编辑区域',600,400,true,false,false,null,'edit_area',true);"> 指定区域</a><else />全部区域</if></td>
										<td>{pigcms{$vo.percent}</td>
										<td>
										<if condition="$vo.status eq 0">
										<font color="red">关闭</font>
										<elseif condition="$vo.status eq 3" />
										<font color="red">已过期</font>
										<else />
										<font color="green">启用</font>
										|
										<php>if($vo['mer_pass_num']*$vo['mer_free_num'] >=$vo['free_total_num']){</php>
											<font color="green">可以购买</font>
										<php>}else{</php>
											<font color="red">参与店铺数量不足</font>
										<php>}</php>
											
										</if>
										
										</td>
										
										<td class="textcenter">
										
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Sub_card/add',array('id'=>$vo['id']))}','编辑免单信息',800,500,true,false,false,editbtn,'add_sub_card',true);">编辑</a> |
										<a href="{pigcms{:U('Sub_card/check_list',array('id'=>$vo['id']))}">审核列表</a>
										 |
										<a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Sub_card/del')}">删除</a>
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="18">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="18">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
		<style>
			.td_txt{
		width:300px; line-height:25px; text-overflow:ellipsis; white-space:nowrap; overflow:hidden;
	}
		</style>
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
		
		$('select[name="status"]').change(function(){
			window.location.href="{pigcms{:U('Sub_card/index')}"+$('#myform1').serialize();
		})
	});
	
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/area_pca.js?{pigcms{:time()}"></script>
<include file="Public:footer"/>