<include file="Public:header"/>

		<div class="mainbox">
			<div class="mainnav_title">
			<form action="{pigcms{:U('Bill/billed')}" method="get">
				<input type="hidden" name="c" value="Bill"/>
				<input type="hidden" name="a" value="billed"/>
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
		
						border-bottom: #9E9392 2px solid;
						text-align:center;
					}
					.bill ul{
						width: 56%;
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
						cursor: pointer ;

					}
					</style>
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
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
								<th>商户帐号</th>
								<th>联系电话</th>
								<th>最近一次对账时间</th>
								<th>最近一次对账数量</th>
								<th>最近一次对账金额</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
								<if condition="$merchant_list">
									<volist name="merchant_list" id="vo">
										<tr>
											<td class="bill">{pigcms{$vo.name}</td>
											<td class="bill">{pigcms{$vo.mer_id}</td>
											<td class="bill">{pigcms{$vo.account}</td>
											<td class="bill">{pigcms{$vo.phone}</td>
											<td class="bill" style="height:125px;">
												<ul>
													<php>if($vo['meal_time']!=0){</php><li>{pigcms{$config.meal_alias_name}:{pigcms{$vo.meal_time|date="Y/m/d H:i",###}</li><php>}</php>
													<php>if($vo['group_time']!=0){</php><li>{pigcms{$config.group_alias_name}:{pigcms{$vo.group_time|date="Y/m/d H:i",###}</li><php>}</php>
													<php>if($vo['appoint_time']!=0){</php><li>{pigcms{$config.appoint_alias_name}:{pigcms{$vo.appoint_time|date="Y/m/d H:i",###}</li><php>}</php>
													<php>if($vo['waimai_time']!=0){</php><li>{pigcms{$config.waimai_alias_name}:{pigcms{$vo.waimai_time|date="Y/m/d H:i",###}</li><php>}</php>
													<php>if($vo['shop_time']!=0){</php><li>{pigcms{$config.shop_alias_name}:{pigcms{$vo.shop_time|date="Y/m/d H:i",###}</li><php>}</php>
													<php>if($vo['store_time']!=0){</php><li>到店:{pigcms{$vo.store_time|date="Y/m/d H:i",###}</li><php>}</php>
													<php>if($vo['weidian_time']!=0){</php><li>微店:{pigcms{$vo.weidian_time|date="Y/m/d H:i",###}</li><php>}</php>
													<php>if($vo['wxapp_time']!=0){</php><li>微信营销:{pigcms{$vo.wxapp_time|date="Y/m/d H:i",###}</li><php>}</php>
													
												</ul>
											</td>
											<td class="bill" style="height:125px;">
												<ul>
													<php>if($vo['meal_time']!=0){</php><li>{pigcms{$config.meal_alias_name}:{pigcms{$vo['bill_info']['meal']['num']}个</li><php>}</php>
													<php>if($vo['group_time']!=0){</php><li>{pigcms{$config.group_alias_name}:{pigcms{$vo['bill_info']['group']['num']}个</li><php>}</php>
													<php>if($vo['appoint_time']!=0){</php><li>{pigcms{$config.appoint_alias_name}:{pigcms{$vo['bill_info']['appoint']['num']}个</li><php>}</php>
													<php>if($vo['waimai_time']!=0){</php><li>{pigcms{$config.waimai_alias_name}:{pigcms{$vo['bill_info']['waimai']['num']}个</li><php>}</php>
													<php>if($vo['shop_time']!=0){</php><li>{pigcms{$config.shop_alias_name}:{pigcms{$vo['bill_info']['shop']['num']}个</li><php>}</php>
													<php>if($vo['store_time']!=0){</php><li>到店:{pigcms{$vo['bill_info']['store']['num']}个</li><php>}</php>
													<php>if($vo['weidian_time']!=0){</php><li>微店:{pigcms{$vo['bill_info']['weidian']['num']}个</li><php>}</php>
													<php>if($vo['wxapp_time']!=0){</php><li>微信营销:{pigcms{$vo['bill_info']['wxapp']['num']}个</li><php>}</php>
													
												</ul>
											</td>
											<td class="bill" style="height:125px;">
												<ul>
													<php>if($vo['meal_time']!=0){</php><li>{pigcms{$config.meal_alias_name}:{pigcms{$vo['bill_info']['meal']['money']/100}元</li><php>}</php>
													<php>if($vo['group_time']!=0){</php><li>{pigcms{$config.group_alias_name}:{pigcms{$vo['bill_info']['group']['money']/100}元</li><php>}</php>
													<php>if($vo['appoint_time']!=0){</php><li>{pigcms{$config.appoint_alias_name}:{pigcms{$vo['bill_info']['appont']['money']/100}元</li><php>}</php>
													<php>if($vo['waimai_time']!=0){</php><li>{pigcms{$config.waimai_alias_name}:{pigcms{$vo['bill_info']['waimai']['money']/100}元</li><php>}</php>
													<php>if($vo['shop_time']!=0){</php><li>{pigcms{$config.shop_alias_name}:{pigcms{$vo['bill_info']['shop']['money']/100}元</li><php>}</php>
													<php>if($vo['store_time']!=0){</php><li>到店:{pigcms{$vo['bill_info']['store']['money']/100}元</li><php>}</php>
													<php>if($vo['weidian_time']!=0){</php><li>微店:{pigcms{$vo['bill_info']['weidian']['money']/100}元</li><php>}</php>
													<php>if($vo['wxapp_time']!=0){</php><li>微信营销:{pigcms{$vo['bill_info']['wxapp']['money']/100}元</li><php>}</php>
													
												</ul>
											</td>
											<td class="bill"><a href="{pigcms{:U('Bill/billed_list',array('mer_id'=>$vo['mer_id']))}"><button>查看全部明细</button></a></td>
										</tr>
									</volist>
								<tr><td class="textcenter pagebar" colspan="16">{pigcms{$pagebar}</td></tr>		
						</tbody>
					</table>
				</div>
		</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<include file="Public:footer"/>