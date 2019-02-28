<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<if condition="$config['company_pay_open'] eq 1 or isset($config['company_pay_open'])">
					<a href="{pigcms{:U('Companypay/index')}" class="on">提款列表</a>|
					<a href="{pigcms{:U('Config/index',array('galias'=>'companyPay','header'=>'Companypay/header'))}">企业付款配置</a>
					</if>
					<a href="{pigcms{:U('Companypay/withdraw_by_hand')}">手动提款</a>
				</ul>
			</div>
			<div class="page_tips">
				<ol>
					<li>企业付款软件 对接文档&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E4%BC%81%E4%B8%9A%E4%BB%98%E6%AC%BE%E5%AF%B9%E6%8E%A5.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E4%BC%81%E4%B8%9A%E4%BB%98%E6%AC%BE%E5%AF%B9%E6%8E%A5.doc" target="_blank">（WORD版）</a></li>
				</ol>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Companypay/index')}" method="get">
							<input type="hidden" name="c" value="Companypay"/>
							<input type="hidden" name="a" value="index"/>
							<input type="hidden" name="export" value="0"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="pay_id" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>商ID</option>								
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>联系电话</option>
								<option value="user" <if condition="$_GET['searchtype'] eq 'user'">selected="selected"</if>>用户ID</option>
								<option value="house" <if condition="$_GET['searchtype'] eq 'house'">selected="selected"</if>>社区ID</option>
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
								<th>商家/用户ID</th>
								<th>联系电话</th>
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
										<td>{pigcms{$vo.pigcms_id}</td>
										<td>{pigcms{$vo.pay_type}</td>
										<td>{pigcms{$vo.pay_id}</td>
										<td>{pigcms{$vo.phone}</td>
										<td>{pigcms{$vo['money']/100}</td>
										<td>{pigcms{$vo.desc}</td>
										<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
										<td><if condition="$vo['pay_time']">{pigcms{$vo.pay_time|date='Y-m-d H:i:s',###}<else/>无</if></td>
										
										<td><if condition="$vo['status'] eq 1"><font color="green">已支付</font><elseif condition="$vo['status'] eq 2"/><font color="red">已取消</font>|<a href="{pigcms{:U('Companypay/restore',array('pigcms_id'=>$vo['pigcms_id'],'status'=>0))}"><font color="green">恢复</font></a><else/><font color="red">未支付</font>|<a href="{pigcms{:U('Companypay/restore',array('pigcms_id'=>$vo['pigcms_id'],'status'=>2))}"><font color="black">取消</font></a></if></td>
										
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