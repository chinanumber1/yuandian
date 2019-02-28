<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<if condition="$config['company_pay_open'] eq 1 or isset($config['company_pay_open'])">
					<a href="{pigcms{:U('Companypay/index')}" >提款列表</a>|
					<a href="{pigcms{:U('Config/index',array('galias'=>'companyPay','header'=>'Companypay/header'))}">企业付款配置</a>
					</if>
					<a href="{pigcms{:U('Companypay/withdraw_by_hand') }" class="on">手动提款</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Companypay/withdraw_by_hand')}" method="get">
							<input type="hidden" name="c" value="Companypay"/>
							<input type="hidden" name="a" value="withdraw_by_hand"/>
							<input type="hidden" name="export" value="0"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
														
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>联系电话</option>
								<option value="user" <if condition="$_GET['searchtype'] eq 'user'">selected="selected"</if>>用户ID</option>
								<option value="truename" <if condition="$_GET['searchtype'] eq 'truename'">selected="selected"</if>>真实姓名</option>
								
							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							支付状态: <select name="searchstatus">
								<option value="-1" <if condition="$_GET['searchstatus'] eq '-1'">selected="selected"</if>>全部</option>
								<option value="1" <if condition="$_GET['searchstatus'] eq '1'">selected="selected"</if>>已支付</option>
								<option value="2" <if condition="$_GET['searchstatus'] eq '2'">selected="selected"</if>>已取消</option>
							</select>
							<font color="#000">日期筛选：</font>
							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
							
							<button class="button" id="forms" onclick="return search();">查询</button>
							<button class="button" id="export" onclick="return exports();">导出excel</button>
						</form>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup><col> <col> <col> <col><col><col><col><col><col width="240" align="center"> </colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>付费类型</th>
								<th>提款人类型</th>
								<th>ID</th>
								<th>平台名称</th>
								<th>联系电话</th>
								<th>真实姓名</th>
								<th>账号</th>
								<th>账户信息</th>
								<th>金额</th>
								<th>描述</th>
								<th>添加时间</th>
								<th>支付时间</th>
								<th>状态</th>
							</tr>
						</thead>
						<tbody>
							
								<volist name="pay_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$pay_type[$vo['pay_type']]}</td>
										<td>{pigcms{$type[$vo['type']]}</td>
										<td>{pigcms{$vo.pay_id}</td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.phone}</td>
										<td>{pigcms{$vo.truename}</td>
										<td>{pigcms{$vo.account}</td>
										<td>{pigcms{$vo.remark}</td>
										<td>{pigcms{$vo['money']/100}</td>
										<td>{pigcms{$vo.desc}</td>
										<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
										<td><if condition="$vo['pay_time']">{pigcms{$vo.pay_time|date='Y-m-d H:i:s',###}<else/>无</if></td>
										
										<td>
										<if condition="$vo['status'] eq 1">
											<font color="green">已支付</font>
										<elseif condition="$vo['status'] eq 2"/><font color="red">已取消</font>|<a href="{pigcms{:U('Companypay/restore_withdraw',array('id'=>$vo['id'],'status'=>0))}"><font color="green">恢复</font></a>
										<elseif condition="$vo['status'] eq 4"/>
										<font color="red">已驳回</font>
										<else/>
											<font color="red">未支付</font>|<a href="{pigcms{:U('Companypay/restore_withdraw',array('id'=>$vo['id'],'status'=>1))}"><font color="black">确认支付</font></a>&nbsp;<a href="{pigcms{:U('Companypay/restore_withdraw',array('id'=>$vo['id'],'status'=>2))}"><font color="black">取消</font></a></if></td>
										
										<!--<td class="textcenter"><a href="{pigcms{:U('Merchant/order',array('mer_id'=>$vo['mer_id']))}">查看账单</a></td>-->
										<!--td class="textcenter"><a href="{pigcms{:U('Merchant/weidian_order',array('mer_id'=>$vo['mer_id']))}">微店账单</a></td-->
									</tr>
								</volist>
							<tr><td class="textcenter pagebar" colspan="16">{pigcms{$pagebar}</td></tr>	
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script>
	function exports(){
		$('input[name="export"]').val(1);
		return true;
	}
	function search(){
		$('input[name="export"]').val(0);
		return true;
	}
	
</script>
<include file="Public:footer"/>