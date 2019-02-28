<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Village_money/money_list')}">社区余额</a>
			</li>
			<li class="active">余额列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<style>
		.my_money span{
			padding: 9px 42px;
			border: 1px solid #fff;
			margin-left: 10px;
			color:#fff;
			border-radius:1px;
		}
		.my_money a:hover {text-decoration:none;}
	</style>
	<div class="page-content">
        <div class="page-content-area">
        	
            <div class="row">
					<div style="margin-top:10px;width:100%;height:240px;background-color:#81d2cf;margin-bottom: 20px;">
						<p style="text-align:center;font-family: 'Arial Normal', 'Arial';font-weight: 400;font-style: normal;font-size: 36px;color: #FFFFFF;padding-top: 36px;">
							￥{pigcms{$village.money|floatval}
						</p>
						<p style="text-align:center;    padding-top: 36px;" class="my_money">
							<if condition="in_array(61,$house_session['menus'])">
								<a href="{pigcms{:U('recharge')}"/><span >充值</span></a>　
							<else/>
								<button  disabled="disabled" style="padding:2px 14px; background-color: #81d2cf;border: 0px;cursor:not-allowed;"><span>充值</span></button>
							</if>

							<if condition="in_array(62,$house_session['menus'])">
								<a href="{pigcms{:U('withdraw')}"/><span >申请提现</span></a>　
							<else/>
								<button  disabled="disabled" style="padding:2px 14px; background-color: #81d2cf;border: 0px;cursor:not-allowed;"><span>提现</span></button>
							</if>
						</p>
					</div>

                <div class="col-xs-12">
                
					<form action="{pigcms{:U('Stroe/group_list')}" method="get"> 
					<input type="hidden" name="c" value="Village_money"/>
					<input type="hidden" name="a" value="money_list"/>
				
				搜索: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}" style="margin-left: 5px;"/>
					<select name="searchtype" style="margin-left: 5px;">
						<option value="order_id" <if condition="$_GET['searchtype'] eq 'order_id'">selected="selected"</if>>订单编号</option>
					</select>
					
					<select name="type" style="margin-left: 5px;">
					
						<volist name="alias_name" id="vo">
							<option value="{pigcms{$key}" <if condition="$_GET['type'] eq $key">selected=selected</if>>{pigcms{$vo}</option>
						</volist>
					</select>
					<font color="#000">日期筛选：</font>
					<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
					<input type="text" class="input-text" name="end_time" style="width:120px;margin-left: 5px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
				
					　
					<input type="submit" value="查询" class="btn btn-success" style="padding:2px 14px;"/>　&nbsp;
					<if condition="in_array(63,$house_session['menus'])">
						<a class="btn btn-success" style="padding:2px 14px;" href="{pigcms{:U('Village_money/village_money_export',$_GET)}">导出</a>
					<else/>
						<button class="btn btn-success" disabled="disabled" style="padding:2px 14px;">导出</button>
					</if>
					<!-- <if condition="in_array(61,$house_session['menus'])">
						<a href="{pigcms{:U('recharge')}" class="btn btn-success" style="padding:2px 14px;margin-left: 12px;"/>充值</a>　
					<else/>
						<button class="btn btn-success" disabled="disabled" style="padding:2px 14px;">充值</button>
					</if>
					<if condition="in_array(62,$house_session['menus'])">
						<a href="{pigcms{:U('withdraw')}" class="btn btn-success" style="padding:2px 14px;"/>提现</a>　
					<else/>
						<button class="btn btn-success" disabled="disabled" style="padding:2px 14px;">提现</button>
					</if> -->　
					&nbsp;
					<if condition="$config['buy_sms'] eq 1">
						<if condition="in_array(248,$house_session['menus'])">
						<a href="{pigcms{:U('sms_note')}" class="btn btn-success" style="padding:2px 14px;"/>我的短信</a>
						<else/>
							<button class="btn btn-success" disabled="disabled" style="padding:2px 14px;">我的短信</button>
						</if>
					</if>
				</form>
				<p>&nbsp;</p>当前社区余额 : ￥{pigcms{$village.money|floatval}
				<div class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>订单号</th>
									<th>订单类型</th>
									<th>订单详情</th>
									<th>数量</th>
									<th>总额</th>
									<th>平台佣金<font color="red" size="1">(提现代表手续费)</font></th>
									<th>当前社区余额</th>
									<th>对账时间</th>
									
								</tr>
							</thead>
							<tbody>
								<volist name="order_list" id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td width="100">{pigcms{$vo.order_id}</td>
										<td width="100">{pigcms{$alias_name[$vo['type']]}</td>
										<td width="100">{pigcms{:msubstr($vo['desc'],0,50,true,'utf-8')}</td>
										<td width="100">{pigcms{$vo.num|floatval}</td>
										<td width="100"><if condition="$vo.income eq 1"><font color="#2bb8aa">+{pigcms{$vo.money|floatval}</font><elseif condition="$vo.income eq 2" /><font color="#f76120">-{pigcms{$vo.money|floatval}</font></if></td>
										<td width="150">{pigcms{$vo.system_take|floatval}<if condition="$vo['system_take'] gt 0" >（抽成比例 {pigcms{$vo.percent|floatval} %）</if></td>
									
										<td width="100">{pigcms{$vo.now_village_money|floatval}</td>
										<td width="150">
									
											{pigcms{$vo['use_time']|date='Y-m-d H:i:s',###}
										
										</td>
									</tr>
								</volist>
							</tbody>
						</table>
					{pigcms{$pagebar}
					</div>
                
               
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">

	function exports(){
		var order_type = $('select[name="order_type"]').val();
		var order_id = $('input[name="order_id"]').val();
		var begin_time = $('input[name="begin_time"]').val();
		var end_time = $('input[name="end_time"]').val();

		if(order_type=='all'&&order_id!=''){
			alert('该分类下没有不能填订单ID');
		}else{
			var export_url ="{pigcms{:U('Village_money/export',array('village_id'=>$village_id, 'type' => 'income'))}&order_type="+order_type+'&order_id='+order_id+'&begin_time='+begin_time+'&end_time='+end_time;
			window.location.href = export_url;
		}
	}
</script>
<include file="Public:footer"/>

