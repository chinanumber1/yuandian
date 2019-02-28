<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('House/village')}" class="on">小区列表</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('House/village_add')}','添加小区',520,350,true,false,false,addbtn,'add',true);">添加小区</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('House/village_import')}','添加小区',450,150,true,false,false,importbtn,'add',true);">导入小区</a>
					<if condition="$config['buy_sms'] eq 1">
						<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('House/sms_add_all')}','添加短信',520,350,true,false,false,addbtn,'add',true);">添加短信</a>
					</if>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('House/village')}" method="get">
							<input type="hidden" name="c" value="House"/>
							<input type="hidden" name="a" value="village"/>
							筛选: 
							<if condition="$admin_area neq 3">
								<div id="choose_pca" province_idss="{pigcms{$_GET.province_idss}" city_idss="{pigcms{$_GET.city_idss}" area_id="{pigcms{$_GET.area_id}" style="display:inline"></div>
							</if>
							<input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="village_name" <if condition="$_GET['searchtype'] eq 'village_name'">selected="selected"</if>>小区名称</option>
								<option value="property_name" <if condition="$_GET['searchtype'] eq 'property_name'">selected="selected"</if>>物业名称</option>
								<option value="property_phone" <if condition="$_GET['searchtype'] eq 'property_phone'">selected="selected"</if>>物业电话</option>
								<option value="account" <if condition="$_GET['searchtype'] eq 'account'">selected="selected"</if>>社区账号</option>
								<option value="village_id" <if condition="$_GET['searchtype'] eq 'village_id'">selected="selected"</if>>社区ID</option>
							</select>
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
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>ID</th>
								<th>小区名称</th>
								<th>最后登录时间</th>
								<th>访问</th>
								<th>账单</th>
								<th>{pigcms{$config.house_market_name}账单</th>
								<th class="textcenter">状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($village_list)">
								<volist name="village_list" id="vo">
									<tr>
										<td>{pigcms{$vo.village_id}</td>
										<td>{pigcms{$vo.village_name}</td>
										<td><if condition="$vo['last_time']">{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}<else/>从未登录</if></td>
										<td>
											<if condition="$vo['status'] neq 2">
												<a href="{pigcms{:U('House/village_login',array('village_id'=>$vo['village_id']))}" target="_blank">访问</a>
											<else/>
												禁止状态
											</if>
										</td>
										<td><a href="{pigcms{:U('House/village_money_list',array('village_id'=>$vo['village_id']))}">查看账单</a></td>
										<td><a href="{pigcms{:U('House/market_order',array('village_id'=>$vo['village_id']))}">查看{pigcms{$config.house_market_name}账单</a></td>
										<td class="textcenter"><if condition="$vo['status'] eq 1"><font color="green">正常</font><elseif condition="$vo['status'] eq 0"/><font color="red" title="等待小区管理员登录社区后台完善信息">待完善信息</font><else/><font color="red">禁止</font></if></td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('House/village_edit',array('village_id'=>$vo['village_id']))}','编辑小区信息',600,370,true,false,false,editbtn,'edit',true);">编辑</a></td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="9">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
		<script type="text/javascript" src="{pigcms{$static_path}js/area_pca.js"></script>
<include file="Public:footer"/>