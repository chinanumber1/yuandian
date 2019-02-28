
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>订单详情</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
     <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/wap_pay_check.css" rel="stylesheet"/>
	<style>
    .btn-wrapper {
        margin: .28rem .2rem;
		position:fixed; bottom:0; right:0; width:100%;
		border-top:1px solid #e5e5e5;
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
	.share-user div{
		float:left;
		height:1.5rem;
		margin-bottom:5px;
	}
	.share-user span{
		display:block;
		text-align:center;
	}
	
	.img_list{
		width:1.2rem;
		height:1.2rem;
		border-radius:50%; overflow:hidden;
	}
	.dealcard{ background:#f4f4f4; height:2rem}
	#index .order-top{ margin-top:0}
	.dealcard .dealcard-brand{ height:.8rem; line-height:.8rem;overflow: hidden;}
	.dealcard .price{ height:1rem; line-height:1rem; color:#000}
	.dealcard-footer{ font-weight:bold; margin-top:15px; padding-left:.1rem}
	.dealcard-img{ left:.1rem; top:.1rem}
	.more .more-after-order-top{ position:static;}
	.btn-wrapper{ background:#fff; height:1rem; margin:.3rem 0 0 0;}
	.order-cancel,.order-pay{   display:block; float:right; margin-right:.2rem; border-radius:4px; padding:.2rem; margin-top:.1rem}
	.order-cancel{border:1px solid #e5e5e5;color:#666}
	.order-pay{  color:#06c1bb; border:1px solid rgb(214, 247, 246)}
	
</style>
</head>
<body id="index">
        <div id="tips" class="tips"></div>
		
		<dl class="list order-top">
				<dd>
					<dl>
						<dd>
			                <a href="{pigcms{:U('Wap/Index/index',array('token'=>$now_group['mer_id']))}" class="react">
			                    <div class="more more-weak more-order-top">
			                        <h6>{pigcms{$now_group.merchant_name}</h6>
			                    </div>
			                </a>
		                </dd>
					</dl>
				</dd>
			</dl>
		
		<a href="{pigcms{$now_order.url}">
			<dl class="list">
				<dd class="dd-padding">
					<div>
						<div class="dealcard">
							<div class="dealcard-img imgbox" style="background:none;"><img src="{pigcms{$now_order.list_pic}" style="width:100%;height: 1.58rem;"/></div>
							<div class="dealcard-block-right">
								<div class="dealcard-brand single-line">{pigcms{$now_order.s_name}</div>
								<div class="price">
									<span class="strong">￥{pigcms{$now_order.price}</span>&nbsp;×&nbsp;<span>{pigcms{$now_order.num}</span>
								</div>
							</div>
						</div>
						<div class="dealcard-footer">
							<span>实付款：￥{pigcms{$now_order['total_money']}</span>
						</div>
					</div>
				</dd>
			</dl>
		</a>
        <div class="wrapper-list">
			<dl class="list" style="border-bottom:none;"></dl>
			<dl class="list">
				<dd>
					<dl>
						<dd>
			                <a class="react" href="{pigcms{:U('Group/branch',array('group_id'=>$now_order['group_id']))}">
			                    <div class="more more-weak">
			                        <h6>商家信息</h6>
			                        <span class="more-after">查看</span>
			                    </div>
			                </a>
		                </dd>
					</dl>
				</dd>
			</dl>
			
			<if condition="$now_order['tuan_type'] neq 2  AND $now_order['paid']">
				<php>if($now_group['group_share_num']!=0&&$group_share_num<$now_group['group_share_num']&&$now_order['is_share_group']!=2){</php>
					<dl class="list coupons">
						<dd>
							<dl>
								<dd class="dd-padding coupons-code">参团份数还未达到 {pigcms{$now_group['group_share_num']} 份的份数要求</dd>
								<dd class="dd-padding coupons-code" id="share_num">
									还差 {pigcms{$now_group['group_share_num']-$group_share_num} 份就可以成团&nbsp;<php>if($now_order['status']!=3){</php><strong id="countdown">&nbsp;&nbsp;</strong>秒后刷新数据<php>}</php>
								</dd>
							</dl>
						</dd>
					</dl>
				<php>}elseif($now_order['is_share_group']==2||(($now_group['open_num']<=$now_group['sale_count']&&$now_order['open_num']!=0)||$now_group['open_num']==0)||($now_group['open_now_num']<=$now_group['sale_count']&&$now_group['open_now_num']!=0)){</php>
				<dl class="list coupons">
					<dd>
						<dl>
							<dt>{pigcms{$config.group_alias_name}券</dt>
								<php>if($now_order['pass_array']){</php>
								<volist name="pass_array" id="vv">
									<dd class="dd-padding coupons-code">
										消费密码: <php>if($vv['status'] == 2){</php><font color="red">无法查看</font><php>}else{</php>{pigcms{$vv.group_pass}<php>}</php> <small><php>if($vv['status']==0){</php>未消费<php>}elseif($vv['status']==1){</php>已消费<php>}elseif($vv['status']==3){</php><font color="red">还需支付：{pigcms{$vv['need_pay']} 元</font><php>}elseif($vv['status']==2){</php><font color="red">已退款</font><php>}</php></small>
									</dd>
								</volist>
								<php>}else{</php>
									<dd class="dd-padding coupons-code">
										消费密码: {pigcms{$now_order.group_pass_txt} <small>{pigcms{$now_order.status_txt}</small>
									</dd>
								<php>}</php>
								<dd class="dd-padding coupons-code">
									消费二维码: <a id="see_storestaff_qrcode">查看二维码</a>
								</dd>
						</dl>
						
					</dd>
				</dl>
				<php>}elseif($now_group['open_now_num']>0){</php>
				<dl class="list coupons">
					<dd>
						<dl>
							<dd class="dd-padding coupons-code">参团份数还未达到 {pigcms{$now_group['open_now_num']} 份的份数要求</dd>
							<dd class="dd-padding coupons-code">
								还差 {pigcms{$now_group['open_now_num']-$now_group['sale_count']} 份就可以成团
							</dd>
							
						</dl>
					</dd>
				</dl>
				
				<php>}elseif($now_order['is_share_group']!=2){</php>
				<dl class="list coupons">
					<dd>
						<dl>
							<dd class="dd-padding coupons-code">参团份数还未达到 {pigcms{$now_group['open_num']} 份的份数要求</dd>
							<dd class="dd-padding coupons-code">
								还差 {pigcms{$now_group['open_num']-$now_group['sale_count']} 份就可以成团
							</dd>
						</dl>
					</dd>
				</dl>
				<php>}</php>
				<php>if($share_user){</php>
				<dl class="list coupons">
					<dd>
						<dl>
							<dd class="dd-padding coupons-code share-user">
							<h6 style="margin-bottom:5px;">购买用户：</h6>
								<div id="user-list">
									<volist name="share_user" id="vo">
										<div id="uid-{pigcms{$vo.uid}" data-id="{pigcms{$vo.uid}">
											<if condition="$key lt 10">
											<img class="img_list" src="{pigcms{$vo.img}" />
											<span >{pigcms{$vo.name}</span>
											<else />
											<span >......</span>
											</if>
										</div>
									</volist>
								</div>
							</dd>
							
						</dl>
					</dd>
				</dl>
				<php>}</php>
			<elseif condition="$now_order['paid']" />
				<php> if(!$now_order['is_pick_in_store']){</php>
				<dl class="list coupons">
					<dd>
						<dl>
							<dt>快递信息</dt>
							<dd class="dd-padding coupons-code">
								收货人：{pigcms{$now_order.contact_name}
							</dd>
							<dd class="dd-padding coupons-code">
								地址：{pigcms{$now_order.adress}
							</dd>
							<dd class="dd-padding coupons-code">
								邮编：{pigcms{$now_order.zipcode}
							</dd>
							<dd class="dd-padding coupons-code">
								电话：{pigcms{$now_order.phone}
							</dd>
							<if condition="$now_order['express_type']">
								<dd class="dd-padding coupons-code">
									快递公司：{pigcms{$now_order.express_info.name}
								</dd>
								<dd class="dd-padding coupons-code">
									快递单号：{pigcms{$now_order.express_id}<small><a href="http://m.kuaidi100.com/index_all.html?type={pigcms{$now_order.express_info.code}&postid={pigcms{$now_order.express_id}&callbackurl=<?php echo 'http://'.urlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>" target="_blank" style="color:#1B9C46;">查看物流信息</a></small>
								</dd>
							</if>
						</dl>
					</dd>
				</dl>
				<php>}else{</php>
				<dl class="list coupons">
					<dd>
						<dl>
							<dt>自取地址</dt>
		
							<dd class="dd-padding coupons-code">
								地址：{pigcms{$now_order.adress}
							</dd>

							<dd class="dd-padding coupons-code">
								电话：{pigcms{$now_order.phone}
							</dd>
							
						</dl>
					</dd>
				</dl>
				<php>}</php>
			</if>
			<dl class="list">
				<dd>
					<dl>
						<dt>订单详情</dt>
						<ul class="ul">
							<li>订单编号：{pigcms{$now_order.real_orderid}</li>
							<li>下单时间：{pigcms{$now_order.add_time|date='Y-m-d H:i',###}</li>
							<li>手机号：{pigcms{$now_order.phone}</li>
								
							<if condition="$now_order.coupon_type eq 'mer'"><li>商家优惠券：{pigcms{$now_order.coupon_price}元</li><elseif condition="$now_order.coupon_type eq 'system'" /><li>平台优惠券：{pigcms{$now_order.coupon_price} 元</li></if>
							<if condition="$now_order['score_deducte'] gt 0"><li>{pigcms{$config['score_name']}抵扣费用：{pigcms{$now_order.score_deducte}元</li></if>
							<if condition="$now_order['third_id'] eq '0' AND $now_order['pay_type'] eq 'offline'">
								<li>线下需向商家付金额：<font color="red">￥{pigcms{$now_order['total_money']-$now_order['wx_cheap']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price']}元</font></li>
							<elseif condition="($now_order['pay_type']=='offline' AND $now_order['paid'] AND !empty($now_order['third_id'])) OR ($now_order['pay_type']!='offline' AND $now_order['paid'])"/>
								<li>付款方式：{pigcms{$now_order.pay_type_txt}</li>
								<li>付款时间：{pigcms{$now_order.pay_time|date='Y-m-d H:i',###}</li>
							   <if condition="!empty($now_order['use_time'])">
								<li>消费时间：{pigcms{$now_order.use_time|date='Y-m-d H:i',###}</li>
							  </if>
							</if>
							<if condition="$now_order['status'] eq 3 OR $now_order['status'] eq 6">
							<li>
							已退款：<font color="red">{pigcms{$now_order['refund_total']}</font>元<if condition="$now_order['refund_fee']">(手续费：{pigcms{$now_order['refund_fee']})</if>
							</li>
							</if>
						</ul>
					</dl>
				</dd>
			</dl>
			<dl style="display:block; height:20px"></dl>
			<dl class="list coupons" id="share-url" style="display:none">
				<dd>
					<dl>
						<dd class="dd-padding coupons-code">
							<input type="text" class="input-weak" name="share-url" value="{pigcms{$config.site_url}{pigcms{:U('Group/detail',array('group_id'=>$now_group['group_id'],'fid'=>$now_order['order_id']))}">
						</dd>
					</dl>
				</dd>
			</dl>
				<if condition=" $now_order.paid eq 1 AND $now_order['status'] neq 3 AND $now_order['is_share_group']!=2">
				<div class="btn-wrapper">
					<span class="btn btn-larger btn-block" id="share_group" style="background-color:#00D618;"  onclick="<php>if($is_wexin_browser){</php>_system._guide(true)<php>}else{</php>copy()<php>}</php>"><if condition="$now_order['is_share_group'] AND $is_wexin_browser eq 0">复制以上链接分享<else />获取分享链接</if></span>
				</div>
			</if>
			<if condition="$now_order['status'] gt 4">
				<div class="btn-wrapper">
					<span class="order-cancel" style="background-color:#BBB9B5;">订单已失效</span>
				</div>
			<elseif condition="$now_order['status'] eq 3 OR $now_order['status'] eq 4" />
				<div class="btn-wrapper">
					<span class="order-cancel">订单已取消</span>
				</div>
			<elseif condition="$now_order['status'] eq 2" />
				<div class="btn-wrapper">
					<span class="order-cancel">订单已完成</span>
				</div>
			<elseif condition="empty($now_order['paid'])" />
				<div class="btn-wrapper">			
					<span onclick="window.location.href='{pigcms{:U('Pay/check',array('type'=>'group','order_id'=>$now_order['order_id']))}'" class="order-pay" style="margin-bottom:15px;">付款</span>
					<a id="cancel_order" class="order-cancel">取消订单</a>
				</div>
			<elseif condition="$now_order['status'] eq 0"/>
				<div class="btn-wrapper">
					<span onclick="window.location.href='{pigcms{:U('My/group_order_refund',array('order_id'=>$now_order['order_id']))}'" class="order-cancel">取消订单</span>
				</div>
			<elseif condition="$now_order['status'] eq 1"/>
				<div class="btn-wrapper">
					<span onclick="window.location.href='{pigcms{:U('My/group_feedback',array('order_id'=>$now_order['order_id']))}'" class="order-cancel">评价</span>
				</div>
			</if>
					</div>
		
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_public}js/jquery.qrcode.min.js"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>		
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<style type="text/css">
		button{width:100%;text-align:center;border-radius:3px;}
		.button2{font-size:16px;padding:8px 0;border:1px solid #adadab;color:#000000;background-color: #e8e8e8;background-image:linear-gradient(to top, #dbdbdb, #f4f4f4);background-image:-webkit-gradient(linear, 0 100%, 0 0, from(#dbdbdb),to(#f4f4f4));box-shadow: 0 1px 1px rgba(0,0,0,0.45), inset 0 1px 1px #efefef; text-shadow: 0.5px 0.5px 1px #ffffff;}
		.button2:active{background-color: #dedede;background-image: linear-gradient(to top, #cacaca, #e0e0e0);background-image:-webkit-gradient(linear, 0 100%, 0 0, from(#cacaca),to(#e0e0e0));}
		#mess_share{margin:15px 0;}
		#share_1{float:left;width:49%;}
		#share_2{float:right;width:49%;}
		#mess_share img{width:22px;height:22px;}
		#cover{display:none;position:absolute;left:0;top:0;z-index:18888;background-color:#000000;opacity:0.7;}
		#guide{display:none;position:absolute;right:18px;top:5px;z-index:19999;}
		#guide img{width:260px;height:180px;}
		</style>
		<script type="text/javascript">
			var flag=false;
			
						
			var _system={
				$:function(id){return document.getElementById(id);},
		   _client:function(){
			  return {w:document.documentElement.scrollWidth,h:document.documentElement.scrollHeight,bw:document.documentElement.clientWidth,bh:document.documentElement.clientHeight};
		   },
		   _scroll:function(){
			  return {x:document.documentElement.scrollLeft?document.documentElement.scrollLeft:document.body.scrollLeft,y:document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop};
		   },
		   _cover:function(show){
			  if(show){
				 this.$("cover").style.display="block";
				 this.$("cover").style.width=(this._client().bw>this._client().w?this._client().bw:this._client().w)+"px";
				 this.$("cover").style.height=(this._client().bh>this._client().h?this._client().bh:this._client().h)+"px";
			  }else{
				 this.$("cover").style.display="none";
			  }
		   },
			_guide:function(click){
				  this._cover(true);
				  this.$("guide").style.display="block";
				  this.$("guide").style.top=(_system._scroll().y+5)+"px";
				  window.onresize=function(){_system._cover(true);_system.$("guide").style.top=(_system._scroll().y+5)+"px";};
				if(click){_system.$("cover").onclick=function(){
					 _system._cover();
					 _system.$("guide").style.display="none";
				 _system.$("cover").onclick=null;
				 window.onresize=null;
				  };
			  }
			  //is_share_group();
		   },
		   _zero:function(n){
			  return n<0?0:n;
		   }
		}
		</script>
		<link href="{pigcms{$static_path}/css/footer.css" rel="stylesheet"/>
		<div style="height:10px"></div>
		<div class="top-btn"><a class="react"><i class="text-icon">⇧</i></a></div>
		<script>
			$(function(){
				$('input[name="share-url"]').select();
				$('#cancel_order').click(function(){
					if(confirm('您确定取消订单吗？取消后不能恢复！')){
						window.location.href = "{pigcms{:U('My/group_order_del',array('order_id'=>$now_order['order_id']))}";
					}
				});
				$('#see_storestaff_qrcode').click(function(){
					var qrcode_width = $(window).width()*0.6 > 200 ? 200 : $(window).width()*0.6;
					layer.open({
						title:['消费二维码','background-color:#8DCE16;color:#fff;'],
						content:'生成的二维码仅限提供给商家店铺员工扫描验证消费使用！<br/><br/><div id="qrcode"></div>',
						success:function(){
							$('#qrcode').qrcode({
								width:qrcode_width,
								height:qrcode_width,
								text:"{pigcms{$config.site_url}/wap.php?c=Storestaff&a=group_qrcode&order_id={pigcms{$now_order.order_id}&id={pigcms{$now_order.group_pass}"
							});
						}
					});
					$('.layermbox0 .layermchild').css({width:qrcode_width+30+'px','max-width':qrcode_width+30+'px'});
				});
			});
			
			function copy(){
				<if condition="$is_shared.is_shared eq 0 AND $is_wexin_browser eq 0">
					is_share_group();
				</if>
				$('#share-url').css('display','block');
				$('input[name="share-url"]').select();
				$('#share_group').html('复制以上链接分享给好友');
			}
			
			function is_share_group(){
				$.post('{pigcms{:U('My/is_group_share')}', {order_id:{pigcms{$now_order.order_id},uid:{pigcms{$now_order.uid}}, function(data, textStatus, xhr) {
					
					$('#share_group').css('background-color','#BBB9B5');
					$('#share_group').removeAttr('onclick');
					
				});
			}
		</script>
		<div id="cover"></div>
		<div id="guide"><img src="{pigcms{$static_path}/images/guide1.png"></div>
		<script type="text/javascript">
			
			window.shareData = {  
				"moduleName":"Group",
				"moduleID":"0",
				"imgUrl": "http://www.group.com/upload/group/000/000/001/56af04b89154e.jpg", 
				"sendFriendLink": "http://www.group.com/wap.php?g=Wap&c=Group&a=detail&group_id=183&fid=14692",
				"tTitle": "【团购】图片拍卖",
				"tContent": "137****5048邀请你参加团购，享受优惠"
			};
		</script>
		</body>
</html>