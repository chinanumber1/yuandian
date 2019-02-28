<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>店员中心</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/wap_pay_check.css" rel="stylesheet"/>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
	<style>
    .btn-wrapper {
        margin: .28rem .2rem;
    }
    .hotel-price {
        color: #ff8c00;
        font-size: 12px;
        display: block;
    }
    .dealcard .line-right {
        display: none;
    }
    .agreement li {
        display: inline-block;
        width: 50%;
        box-sizing: border-box;
        color: #666;
    }

    .agreement li:nth-child(2n) {
        padding-left: .14rem;
    }

    .agreement li:nth-child(1n) {
        padding-right: .14rem;
    }

    .agreement ul.agree li {
        height: .32rem;
        line-height: .32rem;
    }

    .agreement ul.btn-line li {
        vertical-align: middle;
        margin-top: .06rem;
        margin-bottom: 0;
    }

    .agreement .text-icon {
        margin-right: .14rem;
        vertical-align: top;
        height: 100%;
    }

    .agreement .agree .text-icon {
        font-size: .4rem;
        margin-right: .2rem;
    }


    #deal-details .detail-title {
        background-color: #F8F9FA;
        padding: .2rem;
        font-size: .3rem;
        color: #000;
        border-bottom: 1px solid #ccc;
    }

    #deal-details .detail-title p {
        text-align: center;
    }

    #deal-details .detail-group {
        font-size: .3rem;
        display: -webkit-box;
        display: -ms-flexbox;
    }

    .detail-group .left {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        display: block;
        padding: .28rem 0;
        padding-right: .2rem;
    }

    .detail-group .right {
        display: -webkit-box;
        display: -ms-flexbox;
        -webkit-box-align: center;
        -ms-box-align: center;
        width: 1.2rem;
        padding: .28rem .2rem;
        border-left: 1px solid #ccc;
    }

    .detail-group .middle {
        display: -webkit-box;
        display: -ms-flexbox;
        -webkit-box-align: center;
        -ms-box-align: center;
        width: 1.7rem;
        padding: .28rem .2rem;
        border-left: 1px solid #ccc;
    }

    ul.ul {
        list-style-type: initial;
        padding-left: .4rem;
        margin: .2rem 0;
    }

    ul.ul li {
        font-size: .3rem;
        margin: .1rem 0;
        line-height: 1.5;
    }
    .coupons small{
        float: right;
        font-size: .28rem;
    }
    strong {
        color: #FDB338;
    }
    .coupons-code {
        color: #666;
        text-indent: .2rem;
    }
    .voice-info {
        font-size: .3rem;
        color: #eb8706;
    }
</style>
</head>
<body id="index" data-com="pagecommon">
        <div id="tips" class="tips"></div>
        <div class="wrapper-list" style="padding-bottom: 10px;">
			<h4 style="margin-top:.3rem;">{pigcms{$now_order.s_name} </h4><a class="btn" style="float:right;margin-right: 12px;margin-right: 15px;top:-.7rem;;position: relative;" href="{pigcms{:U('Storestaff/group_edit',array('order_id'=>$now_order['order_id']))}">返 回</a>
			
			<dl class="list coupons">
				<dd style="overflow:visible;">
					<dl>
						<dt style="overflow:visible;">订单详情</dt>
						<dd class="dd-padding coupons-code">
							订单编号： <span>{pigcms{$now_order.order_id}</span>
						</dd>
						<dd class="dd-padding coupons-code">
							{pigcms{$config.group_alias_name}商品： <span><a href="{pigcms{:U('Group/detail',array('group_id'=>$now_order['group_id']))}" target="_blank">{pigcms{$now_order.s_name}</a></span>
						</dd>
						<dd class="dd-padding coupons-code">
							订单类型： <span><if condition="$now_order['tuan_type'] eq '0'">{pigcms{$config.group_alias_name}券<elseif condition="$now_order['tuan_type'] eq '1'"/>代金券<else/>实物</if></span>
						</dd>
						<dd class="dd-padding coupons-code">
							消费码 <span style="float:right;margin-right:15px">操作 <if condition="$now_order['status'] eq 0 AND $un_use_num eq $now_order['num']"><a style="color: blue;" href="javascript:void(0);" onclick="group_verify_btn({pigcms{$now_order.order_id},$(this));return false;" title="查看商品详情">全部验证</a><elseif condition="($now_order.status eq 1 OR $now_order.status eq 2)"/><font color="green">全部已消费</font></if></span>
						</dd>
						<volist name="pass_array" id="vo">
							<dd class="dd-padding coupons-code">
							{pigcms{$vo.group_pass} <span style="float:right;margin-right:15px"><if condition="$vo.status eq 0" ><font color="red">未消费</font>&nbsp;<a href="javascript:void(0);" onclick="group_array_verify({pigcms{$now_order.order_id},'{pigcms{$vo.group_pass}',this);" title="查看商品详情">验证消费</a><elseif condition="$vo.status eq 3" /><font color="red">还需支付：{pigcms{$vo.need_pay} 元</font>&nbsp;<a href="javascript:void(0);" onclick="group_array_verify({pigcms{$now_order.order_id},'{pigcms{$vo.group_pass}',this);" title="查看商品详情">验证付款</a><elseif condition="$vo.status eq 2" /><font color="red">已退款</font><elseif condition="$now_order.status eq 1 OR $now_order.status eq 2" /><font color="green">已消费</font><else /><font color="green">已消费</font></if></span>
							</dd>
						</volist>
					</dl>
				</dd>
			</dl>	
			
		</div>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<!---<include file="Storestaff:footer"/>--->
		<script type="text/javascript">
		
			function group_array_verify(order_id,group_pass,val){
				$('a').removeAttr('onclick');
				$.post("{pigcms{:U('Storestaff/group_array_verify')}",{order_id:order_id,group_pass:group_pass},function(result){
					window.location.href = window.location.href;
				});
			}
			
			function group_verify_btn(oid,obj){
				var verify_btn = obj;
				verify_btn.attr('disabled','disabled');
				verify_btn.html('验证中..');
				$.get("{pigcms{:U('Storestaff/group_verify')}&order_id="+oid,function(result){
					if(result.status == 1){
						layer.open({
							title:['成功提示：','background-color:#FF658E;color:#fff;'],
							content:result.info,
							btn: ['确定'],
							end:function(){window.location.href = window.location.href;}
						});
					}else{
						verify_btn.html('验证消费');
						layer.open({
							title:['错误提示：','background-color:#FF658E;color:#fff;'],
							content:result.info,
							btn: ['确定'],
							end:function(){}
						});
					}
				});
				return false;
			}
			$(function(){
				<if condition="$now_order['paid'] eq 1 && $now_order['status'] eq 0">var fahuo=1;<else/>var fahuo=0;</if>
				$('#express_id_btn').click(function(){
					if(fahuo == 1){
						if(confirm("您确定要提交快递信息吗？提交后订单状态会修改为已发货。")){
							express_post();
						}
					}else{
						express_post();
					}
				});
				$('#merchant_remark_btn').click(function(){
					$(this).prop('disabled',true);
					$.post("{pigcms{:U('Storestaff/group_remark',array('order_id'=>$now_order['order_id']))}",{merchant_remark:$('#merchant_remark').val()},function(result){
						if(result.status == 0){						
							$('#merchant_remark_btn').prop('disabled',false);
							alert(result.info);
						}else{
							window.location.href = window.location.href;
						}
					});
				});
				function express_post(){
					$('#express_id_btn').prop('disabled',true);
					$.post("{pigcms{:U('Storestaff/group_express',array('order_id'=>$now_order['order_id']))}",{express_type:$('#express_type').val(),express_id:$('#express_id').val()},function(result){
						if(result.status == 1){
							fahuo=0;
							window.location.href = window.location.href;
						}else{
							$('#express_id_btn').prop('disabled',false);
							alert(result.info);
						}
					});
				}
			});
		</script>
</body>
</html>