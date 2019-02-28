<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('AppRedpack/index')}" class="on">红包周期发放列表</a>
				
					<a href="{pigcms{:U('AppRedpack/setting')}">红包配置</a>
				
					
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('AppRedpack/index')}" method="get">
							<input type="hidden" name="c" value="AppRedpack"/>
							<input type="hidden" name="a" value="index"/>
							<font color="#000">日期筛选：</font>
							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
							<input type="submit" value="查询" class="button"/>　　
					
						用户总{pigcms{$config.score_name}：<if condition="$user_balance['close']">{pigcms{$user_balance['score']|floatval}<else/>0</if>个
						&nbsp;&nbsp;&nbsp;&nbsp;已经领取的红包金额：<if condition="$user_balance['redpack_money']">{pigcms{$user_balance['redpack_money']|floatval}<else/>0</if>元
						</form>
					</td>
				</tr>
			</table
			><form name="myform" id="myform" action="" method="post">
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
							<col/>
							<col/>
							
						</colgroup>
						<thead>
							<tr>
								<th>ID</th>
								<th>周期</th>
								<th>总收入</th>
								<th>总积分</th>
								<th>红包金额</th>
								<th>红包比例</th>
								<th>已领取</th>
								<th>领取列表</th>
								<th>添加时间</th>
				
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($redpack_list)">
								<volist name="redpack_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{:date('Y/m/d H',substr($vo['time'],0,10))}时—{pigcms{:date('Y/m/d H',substr($vo['time'],10,10))}时</td>
										<td>{pigcms{$vo.all_money|floatval}</td>
										<td>{pigcms{$vo.all_score|floatval}</td>
										<td>{pigcms{$vo.redpack_money|floatval}</td>
										<td>{pigcms{$vo.redpack_percent|floatval}</td>
										<td>{pigcms{$vo.had_pull|floatval}</td>
										<td><a href="{pigcms{:U('hadpull_list',array('id'=>$vo['id']))}">领取列表</a></td>
										<td>{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</td>
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