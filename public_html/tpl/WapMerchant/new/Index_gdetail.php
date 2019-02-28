<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/wap_pay_check.css" rel="stylesheet"/>
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
#pigcms-header-left {font-size: 30px;}
</style>
<body>
	<!--头部结束-->
	<header class="pigcms-header mm-slideout">
		<a href="/index.php?g=WapMerchant&c=Index&a=gorder" id="pigcms-header-left" class="iconfont icon-left">
		</a>			
		<p id="pigcms-header-title">订单详情</p>
		<!--<a id="pigcms-header-right">操作日志</a>-->
	</header>

<div style="padding: 0.2rem;margin-top:1rem;margin-bottom:1rem">		
			<dl class="list coupons">
				<dd style="overflow:visible;">
					<dl>
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
							订单状态： <span>
							<if condition="$now_order['status'] eq 3">
								<font color="red">已取消</font>
							<elseif condition="$now_order['paid'] eq '1'" />
								<if condition="$now_order['third_id'] eq '0' AND $now_order['pay_type'] eq 'offline'">
									<font color="red">线下未付款</font>
								<elseif condition="$now_order['status'] eq '0'"/>
									<font color="green">已付款</font>&nbsp;
									<if condition="$now_order['tuan_type'] neq '2'">
									<php>if($now_order['tuan_type'] != 2){</php>
										<font color="red">未消费</font>
									<php>}else{</php>
										<font color="red">未发货</font>
									<php>}</php>
								<elseif condition="$now_order['status'] eq '1'"/>
									<php>if($now_order['tuan_type'] != 2){</php>
										<font color="green">已消费</font>
									<php>}else{</php>
										<font color="green">已发货</font>
									<php>}</php>&nbsp;
									<font color="red">待评价</font>
								<else/>
									<font color="green">已完成</font>
								</if>
							<else/>
								<font color="red">未付款</font>
							</if></span>
						</dd>
						<dd class="dd-padding coupons-code">
							数量： <span>{pigcms{$now_order.num}</span>
						</dd>
						<dd class="dd-padding coupons-code">
							单价： <span>{pigcms{$now_order.price}元</span>
						</dd>
						<dd class="dd-padding coupons-code">
							下单时间： <span>{pigcms{$now_order.add_time|date='Y-m-d H:i',###}</span>
						</dd>
						<dd class="dd-padding coupons-code">
							付款时间： <span>{pigcms{$now_order.pay_time|date='Y-m-d H:i:s',###}</span>
						</dd>
						<if condition="$now_order['status'] gt 0 && $now_order['status'] lt 3">
							<dd class="dd-padding coupons-code">
								<if condition="$now_order['tuan_type'] neq 2">消费<else/>发货</if>时间： <span>{pigcms{$now_order.use_time|date='Y-m-d H:i:s',###}</span>
							</dd>
						 <dd class="dd-padding coupons-code">操作店员：{pigcms{$now_order.last_staff}
						</dd>
						</if>
						<dd class="dd-padding coupons-code">
						支付方式：<span>{pigcms{$now_order.paytypestr}</span>
					    </dd>
						<dd class="dd-padding coupons-code">
							买家留言： <span>{pigcms{$now_order.delivery_comment}</span>
						</dd>
					</dl>
				</dd>
			</dl>
			<if condition="$now_order['paid'] eq '1'">
				<dl class="list coupons">
					<dd>
						<dl>
							<dt>用户信息</dt>
							<dd class="dd-padding coupons-code">
								用户ID： <span>{pigcms{$now_order.uid}</span>
							</dd>
							<dd class="dd-padding coupons-code">
								用户名： <span>{pigcms{$now_order.nickname}</span>
							</dd>
							<dd class="dd-padding coupons-code">
								订单手机号： <span><a href="tel:{pigcms{$now_order.phone}" style="color:blue;">{pigcms{$now_order.phone}</a></span>
							</dd>
							<dd class="dd-padding coupons-code">
								用户手机号： <span><a href="tel:{pigcms{$now_order.user_phone}" style="color:blue;">{pigcms{$now_order.user_phone}</a></span>
							</dd>
						</dl>
					</dd>
				</dl>
				<if condition="$now_order['tuan_type'] eq 2">
				<dl class="list">
					<dd>
						<dl>
							<dt>配送信息</dt>
							<dd class="dd-padding coupons-code">
								收货人：<span>{pigcms{$now_order.contact_name}</span>
							</dd>
							<dd class="dd-padding coupons-code">
								联系电话：<span>{pigcms{$now_order.phone}</span>
							</dd>
							<dd class="dd-padding coupons-code">
								配送要求：<span><switch name="now_order['delivery_type']">
								<case value="1">工作日、双休日与假日均可送货</case>
								<case value="2">只工作日送货</case>
								<case value="3">只双休日、假日送货</case>
								<case value="4">白天没人，其它时间送货</case>
							</switch></span>
							</dd>
							<dd class="dd-padding coupons-code">
								邮编：<span>{pigcms{$now_order.zipcode}</span>
							</dd>
							<dd class="dd-padding coupons-code">
								收货地址：<span>{pigcms{$now_order.adress}</span>
							</dd>
					<php>if(empty($now_order['store_id'])){</php>
							<dd class="dd-padding coupons-code">
							<p style="margin-left: -9px;margin-bottom: 10px;font-size: 15px;color: #333;">将订单归属于店铺：</p>
								<select id="order_store_id" style="border: 1px solid #ccc;width:60%;margin-left: 10px;padding-left: 5px;">
									<volist name="group_store_list" id="vo">
										<option value="{pigcms{$vo.store_id}">{pigcms{$vo.name}</option>
									</volist>
								</select>
								&nbsp;&nbsp;&nbsp;
								<button id="store_id_btn" class="btn">修改</button>
								
						</dd>
					<php>}</php>
						</dl>
					</dd>
				</dl>
				</if>
				<if condition="$now_order['paid'] eq '1'">
					<dl class="list coupons">
						<dd>
							<dl>
								<dt>额外信息</dt>
								<dd class="dd-padding coupons-code">
								 标记： <span><input type="text" class="input" id="merchant_remark" value="{pigcms{$now_order.merchant_remark}" style="width:45%;height: 25px;border: 1px solid #eee;padding: 0px 0px 5px 10px;"/>&nbsp;&nbsp;<button id="merchant_remark_btn" class="btn">修改</button></span>
								</dd>
							</dl>
						</dd>
					</dl>
				</if>
			</if>
		</div>
	</body>

		<script type="text/javascript">
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
					$.post("{pigcms{:U('Index/group_remark',array('order_id'=>$now_order['order_id']))}",{merchant_remark:$('#merchant_remark').val()},function(result){
						if(result.status == 0){						
							$('#merchant_remark_btn').prop('disabled',false);
							alert(result.info);
						}else{
							window.location.href = window.location.href;
						}
					});
				});
				$('#store_id_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Index/order_store_id',array('order_id'=>$now_order['order_id']))}",{store_id:$('#order_store_id').val()},function(result){
						$('#store_id_btn').html('修改').prop('disabled',false);
						alert(result.info);
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
	<include file="Public:footer"/>
</html>