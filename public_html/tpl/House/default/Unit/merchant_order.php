<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/index')}">物业管理</a>
            </li>
            <li class="active">物业商家流水</li>
        </ul>
    </div>
	<style type="text/css">
		.mainnav_title {line-height:40px;/* height:40px; */border-bottom:1px solid #eee;color:#31708f;}
		.mainnav_title a {color:#004499;margin:0 5px;padding:4px 7px;background:#d9edf7;}
		.mainnav_title a:hover ,.mainnav_title a.on{background:#498CD0;color:#fff;text-decoration: none;}
	</style>
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Unit/merchant_order',array('type'=>'group','village_id'=>$_GET['village_id']))}" <if condition="$_GET.type eq 'group' OR !isset($_GET['type'])">class="on"</if>>{pigcms{$config.group_alias_name}流水</a>
			<a href="{pigcms{:U('Unit/merchant_order',array( 'type' => 'meal','village_id'=>$_GET['village_id']))}" <if condition="$_GET.type eq 'meal'">class="on"</if>>{pigcms{$config.meal_alias_name}流水</a>
			<a href="{pigcms{:U('Unit/merchant_order',array('type' => 'shop','village_id'=>$_GET['village_id']))}" <if condition="$_GET.type eq 'shop'">class="on"</if>>{pigcms{$config.shop_alias_name}流水</a>
			<a href="{pigcms{:U('Unit/merchant_order',array('type' => 'appoint','village_id'=>$_GET['village_id']))}" <if condition="$_GET.type eq 'appoint'">class="on"</if>>{pigcms{$config.appoint_alias_name}流水</a>
		</ul>
	</div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row search_table">
				<div style="margin:10px;">
					
					 <form id="myform" method="post" action="{pigcms{:U('Unit/merchant_order')}" >
						<input type="hidden" name="type" value="{pigcms{$_GET.type}">
						<div style="float:left"><font color="#000">时间筛选 ：</font></div>
						<input type="text" class="input fl" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$begin_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;&nbsp;
						<input type="text" class="input fl" name="end_time" style="width:120px; margin-left:20px" id="d4311" value="{pigcms{$end_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;&nbsp;
						<input type="submit" class="button">
					</form>
					
					
				<div>		
				<br>
				<div >本页实际支付总金额：<strong style="color: red" id="total_money"></strong> 平台返点（返点比例{pigcms{$rebate_percent}%）：<strong style="color: red" id="money_rebate"></strong></div>
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">商家名称</th>
                                    <th width="5%">订单ID</th>
                                    <th width="5%">订单描述</th>
                                    <th width="5%">联系方式</th>
                                    <th width="5%">订单总价</th>
                                    <th width="10%">实际支付</th>
                                    <th width="10%">支付时间</th>
									
                                </tr>
                            </thead>
                            <tbody >
                                <if condition="$order_list">
                                    <volist name="order_list" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td>{pigcms{$vo.mer_name}</td>
                                            <td>{pigcms{$vo.order_id}</td>
                                            <td>{pigcms{$vo.des}</td>
                                            <td>{pigcms{$vo.phone}</td>
                                            <td>{pigcms{$vo.total_money|floatval}</td>
                                            <td class="money" data-money="{pigcms{$vo.pay_in_fact|floatval}">{pigcms{$vo.pay_in_fact|floatval}</td>
                                            <td>{pigcms{$vo.pay_time|date='Y-m-d H:i:s',###}</td>
                                         
                                          
                                        </tr>
                                    </volist>
									
									<tr class="odd">
										<td colspan="16" id="show_count"></td>
									</tr>
									<tr><td class="textcenter pagebar" colspan="12">{pigcms{$page}</td></tr>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="12" >没有订单</td></tr>
                                </if>
							</if>
                            </tbody>
                        </table>
                        {pigcms{$list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
	var total_money = 0;
	$('.money').each(function(index,val){
		total_money+=$(val).data('money');
	});
	$('#total_money').html(total_money.toFixed(2))
	var percent = Number('{pigcms{$rebate_percent}');
	$('#money_rebate').html((total_money*percent/100).toFixed(2));
});
</script>
<include file="Public:footer"/>
