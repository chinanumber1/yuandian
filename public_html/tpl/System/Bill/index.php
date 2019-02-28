<include file="Public:header"/>

		<div class="mainbox">
			<div class="mainnav_title">
			<form action="{pigcms{:U('Bill/index')}" method="get">
				<input type="hidden" name="c" value="Bill"/>
				<input type="hidden" name="a" value="index"/>
				<font color="#000">筛选：&nbsp;&nbsp;&nbsp;</font> 
				<select name="searchtype">
					<option value="name" <if condition="$_GET['name']">selected="selected"</if>>商户名称</option>
					<option value="mer_id" <if condition="$_GET['mer_id']">selected="selected"</if>>编号</option>
					<option value="phone" <if condition="$_GET['phone']">selected="selected"</if>>联系电话</option>
					<option value="accout" <if condition="$_GET['accout']">selected="selected"</if>>商户帐号</option>
				</select>
				&nbsp;&nbsp;
				<input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
				<input type="submit" value="查询" class="button"/>
			</form>
			</div>
			
				<input type="hidden" id="com_pay_money"name="money" value="">
				<input type="hidden" name="mer_id" value="{pigcms{$mer_id}">
				<input type="hidden" name="pay_type" value="{pigcms{$type}">
				<div class="table-list">
					<style>
					.table-list td{line-height:22px;padding-top:5px;padding-bottom:5px;}
					.bill_info{
						height:120px;
					}
					td {
						border-right:#eee 1px solid;
					}
					.table-list thead th {
						text-align:center;
					}
					.table-list .bill{
						width:15%;
						border-bottom: #9E9392 2px solid;
						text-align:center;
					}
					.bill ul{
						width: 50%;
						margin: 0 auto;
						text-align: left;
					}
					.bill li{
						color:#666666;
						font-size: 14px;
						border-bottom:1px solid #E8E6E6;
					}
					button{
						    margin: 5px;
						padding: 6px;
						background-color: rgba(255, 255, 255, 0);
						box-sizing: border-box;
						border-width: 1px;
						border-style: solid;
						border-color: rgba(121, 121, 121, 1);
						border-radius: 2px;
						-moz-box-shadow: none;
						-webkit-box-shadow: none;
						box-shadow: none;
						font-size: 16px;
						color: #666666;
						cursor:pointer
					}
					</style>
					<table id="table" width="100%" cellspacing="0" style="display:none;">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
						</colgroup>
						<thead>
							<tr>
							
								<th>商户名称</th>
								<th>编号</th>
								<th>联系电话</th>
								<th>上次更新</th>
								<th>最近一次对账时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
	
								<if condition="$merchant_list">
									<volist name="merchant_list" id="vo">
										<tr id="bill{pigcms{$vo.mer_id}" data-id="{pigcms{$vo.mer_id}">
											<td class="bill">{pigcms{$vo.name}</td>
											<td class="bill">{pigcms{$vo.mer_id}</td>
											<td class="bill">{pigcms{$vo.phone}</td>
											<td class="bill">
												<if condition="$vo.bill_time gt 0"><p style="text-align:left;"> 上次更新时间:<if condition="$vo.bill_period gt 0">{pigcms{$vo['bill_time']-$vo['bill_period']*86400|date="Y/m/d H:i",###}<else />{pigcms{$vo['bill_time']-$config['bill_period']*86400|date="Y/m/d H:i",###}</if></p></if>
												<p style="text-align:left;">对账周期：<if condition="$vo.bill_period gt 0">{pigcms{$vo.bill_period}<else />{pigcms{$config['bill_period']}</if> 天</p>
											</td>
											<td class="bill" style="width:30%;height:125px;">

												<ul>
													<php>if(!$vo['meal_time']&&!$vo['group_time']&&!$vo['appoint_time']&&!$vo['waimai_time']&&!$vo['shop_time']&&!$vo['store_time']&&!$vo['weidian_time']&&!$vo['wxapp_time']){</php>
														 暂无记录
													<php>}else{</php>
														<php>if($vo['meal_time']!=0){</php><li>{pigcms{$config.meal_alias_name}:{pigcms{$vo.meal_time|date="Y/m/d H:i",###}</li><php>}</php>
														<php>if($vo['group_time']!=0){</php><li>{pigcms{$config.group_alias_name}:{pigcms{$vo.group_time|date="Y/m/d H:i",###}</li><php>}</php>
														<php>if($vo['appoint_time']!=0){</php><li>{pigcms{$config.appoint_alias_name}:{pigcms{$vo.appoint_time|date="Y/m/d H:i",###}</li><php>}</php>
														<php>if($vo['waimai_time']!=0){</php><li>{pigcms{$config.waimai_alias_name}:{pigcms{$vo.waimai_time|date="Y/m/d H:i",###}</li><php>}</php>
														<php>if($vo['shop_time']!=0){</php><li>{pigcms{$config.shop_alias_name}:{pigcms{$vo.shop_time|date="Y/m/d H:i",###}</li><php>}</php>
														<php>if($vo['store_time']!=0){</php><li>到店:{pigcms{$vo.store_time|date="Y/m/d H:i",###}</li><php>}</php>
														<php>if($vo['weidian_time']!=0){</php><li>微店:{pigcms{$vo.weidian_time|date="Y/m/d H:i",###}</li><php>}</php>
														<php>if($vo['wxapp_time']!=0){</php><li>微信营销:{pigcms{$vo.wxapp_time|date="Y/m/d H:i",###}</li><php>}</php>
													<php>}</php>
												</ul>
											</td>
											<td class="bill"><a href="{pigcms{:U('Bill/order',array('mer_id'=>$vo['mer_id']))}"><button id="go_bill">去对账</button></a>
											<a href="{pigcms{:U('Bill/update_bill_period',array('mer_id'=>$vo['mer_id']))}" title=" 只有每次对账结束后点击左上角的“更新对账时间”&#10;按钮，此商家才能排序到最后，其他未对账的商家&#10;才能依次向前排，便于提醒平台未对账的商家有哪些。"><button>更新对账时间</button></a></td>
										</tr>
									</volist>
								<tr><td class="textcenter pagebar" colspan="16">{pigcms{$pagebar}</td></tr>		
						</tbody>
					</table>
				</div>
		</div>
		<img id="loading" src="{pigcms{$static_path}images/loading.gif" style="display:block;margin: 322px auto;"/>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<style>
	.need_bill{
		background:url({pigcms{$static_path}images/need_bill.png);
		background-size: 9px;
		background-repeat: no-repeat;
		background-position-x: 52px;
	}
</style>
<script>
	$(function(){
		var tr_length = $('#table tbody tr').length;
		$.each($('#table tbody tr'), function(index, val) {
			if(typeof($(this).attr('data-id'))!='undefined'){
				var mer_id =$(this).attr('data-id');
				$.ajax({
					url: '{pigcms{:U('Bill/check_unbill')}',
					type: 'POST',
					dataType: 'json',
					data: {mer_id: mer_id},
					success: function(data){
						if(data.unbill){
							//$('#bill'+mer_id).find('#go_bill').css('background','url({pigcms{$static_path}images/need_bill.png)');
							$('#bill'+mer_id).find('#go_bill').addClass('need_bill');
						}
					}
				});
			}
			if(index>=tr_length-1){
				show_bill_table();
			}
		});
		//var t=setTimeout("show_bill_table()",5000)
	});
	
	function show_bill_table(){
		 
		$('#loading').css('display','none');
		$('#table').css('display','block');
	}
</script>
<include file="Public:footer"/>