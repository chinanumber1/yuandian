<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>{pigcms{$config.cash_alias_name}订单详情</title>
        <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name='apple-touch-fullscreen' content='yes'/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
        <link href="{pigcms{$static_path}shop/css/order_detail.css" rel="stylesheet"/>
        <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
    </head>
    <body>
	
        <section class="public">
            <a class="return link-url" href="javascript:window.history.go(-1);"></a>
            <div class="content">订单详情</div>
            <div class="ipho phone" id="phone" data-phone="{pigcms{$store['phone']}"></div>
        </section>
      
        <section class="g_details p40">
            
            <div class="infor">
                <ul>
                    <li class="first storext">
                        <a href="{pigcms{:U('Merchant/shop')}&store_id={pigcms{$store['store_id']}">
                            <div class="img">
                                <img src="{pigcms{$store['image']}">
                            </div>
                            <div class="tit">{pigcms{$store['name']}</div>
                        </a>
                    </li>
                </ul>
                
		
              
                <div class="answer clr">
                    <div class="fl">订单￥{pigcms{$order['total_price']|floatval} 优惠-￥{pigcms{$order['discount_price']|floatval}</div>
                    <div class="fr">应收总额: ￥{pigcms{$order['price']|floatval}</div>
                </div>
                
            </div>
            <div class="infor">
                <ul>
                    <li class="clr first">
                        <div class="fl book">订单信息</div>
                    </li>
                    <li class="clr">
                        <div class="fl">订单编号</div>
                        <div class="fr">{pigcms{$order['order_id']}</div>
                    </li>
					<if condition="$order['ticketNum']">
						<li class="clr">
							<div class="fl">单张票价格</div>
							<div class="fr">￥{pigcms{$order['ticketPrice']|floatval=###}</div>
						</li>
						<li class="clr">
							<div class="fl">购票数量</div>
							<div class="fr">{pigcms{$order['ticketNum']}张</div>
						</li>
						<if condition="floatval($order['ticketInsure'])">
							<li class="clr">
								<div class="fl">单张票保险价</div>
								<div class="fr">￥{pigcms{$order['ticketInsure']|floatval=###}</div>
							</li>
						</if>
					</if>
                </ul>
            </div>
             <if condition="$order['paid'] eq 1">
             <div class="infor">
                 <ul>
        	         <li class="clr first">
        	             <div class="fl branch">支付信息</div>
        	         </li>
                     <li class="clr">
                         <div class="fl">支付时间</div>
                         <div class="fr">{pigcms{$order['pay_time']|date='Y/m/d H:i:s',###}</div>
                     </li>
                     <li class="clr">
                         <div class="fl">支付方式</div>
                         <div class="fr">{pigcms{$order['pay_type_str']}</div>
                     </li>
        	         <li class="clr">
        	             <div class="fl">应收总额</div>
        	             <div class="p90">
        	                
        	                 <p class="e2c">￥{pigcms{$order['price']|floatval}</p>
        	               
        	             </div>
        	         </li>
					  <if condition="$order['no_discount_money'] gt 0">
        	         <li class="clr">
        	             <div class="fl">不可优惠金额</div>
        	             <div class="p90">
							<p class="e2c">-￥{pigcms{$order['no_discount_money']|floatval}</p>
						 
						   <p class="kdsize">不可优惠金额不参与优惠</p>
						 </div>
        	         </li>
        	         </if>
        	         <if condition="$order['card_discount'] gt 0 AND $order['card_discount'] neq 10">
        	         <li class="clr">
        	             <div class="fl">商家会员卡折扣</div>
        	             <div class="p90">
                            <p class="e2c">-￥{pigcms{$order['price']-($order['price']-$order['no_discount_money'])*$order['card_discount']/10-$order['no_discount_money']|round=###,2}（{pigcms{$order['card_discount']}折）</p>
                          
                         </div>
        	         </li>
        	         </if>
					 <if condition="$order['card_price'] gt 0">
        	         <li class="clr">
        	             <div class="fl">商家优惠券</div>
        	             <div class="fr e2c">-￥{pigcms{$order['card_price']|floatval}</div>
        	         </li>
        	         </if>
        	         <if condition="$order['coupon_price'] gt 0">
        	         <li class="clr">
        	             <div class="fl">平台优惠券</div>
        	             <div class="fr e2c">-￥{pigcms{$order['coupon_price']|floatval}</div>
        	         </li>
        	         </if>
					
        	         
        	         <if condition="$order['score_deducte'] gt 0">
                     <li class="clr">
                         <div class="fl">积分抵扣</div>
                         <div class="fr e2c">-￥{pigcms{$order['score_deducte']|floatval}（使用{pigcms{$order['score_used_count']|floatval}积分）</div>
                     </li>
                     </if>
                     <if condition="$order['card_give_money'] gt 0">
                     <li class="clr">
                         <div class="fl">会员卡赠送余额支付</div>
                         <div class="fr e2c">-￥{pigcms{$order['card_give_money']|floatval}</div>
                     </li>
                     </if>
                     <if condition="$order['merchant_balance'] gt 0">
                     <li class="clr">
                         <div class="fl">商家余额支付</div>
                         <div class="fr e2c">-￥{pigcms{$order['merchant_balance']|floatval}</div>
                     </li>
                     </if>
                     <if condition="$order['balance_pay'] gt 0">
                     <li class="clr">
                         <div class="fl">平台余额支付</div>
                         <div class="fr e2c">-￥{pigcms{$order['balance_pay']|floatval}</div>
                     </li>
                     </if>
                     <if condition="$order['payment_money'] gt 0">
                     <li class="clr">
                         <div class="fl">{pigcms{$order['pay_type_str']}</div>
                         <div class="fr e2c">-￥{pigcms{$order['payment_money']|floatval}</div>
                     </li>
                     </if>
                 </ul>
             </div>
            </if>
            <div class="consume consumes">
                <ul class="clr">
                    <li class="fr zlyd" data-url="{pigcms{:U('My/pay', array('store_id' => $order['store_id']))}">再来一单</li>
                </ul>
            </div>
        </section> 
		<if condition="$config.open_share_lottery eq 1 AND $order.paid  eq 1 AND !empty($lottery)  AND $lottery_info.status neq 1">
			<div class="float-open" id="float-open"  <if condition=" $lottery.status eq 0  OR $order.share_status eq 0 ">style="display:none;"</if> ><a class="open-btn" href="javascript:void(0);"><img src="{pigcms{$static_path}shop/images/lottery.png"></a></div>
		<elseif condition="$config.open_share_lottery eq 0 AND $order.paid  eq 1 AND $config.share_coupon eq 1 AND   $config.get_coupon_must_share eq 0 AND !empty($share_coupon)" />
			<div class="float-open" id="send_friend" ><a class="open-btn" href="javascript:void(0);"><img src="{pigcms{$static_path}shop/images/share_coupon.png"></a></div>
		</if>

		
		 <if condition="$config.open_share_lottery eq 0 AND $order.paid  eq 1 AND  ($order['share_status'] eq 0 OR $config.get_coupon_must_share eq 0)  AND $config.share_coupon eq 1 AND $config.share_coupon_num gt 0 ">
            <div class="coupon_share mongolia_layer coupon_my" <if condition="$config.get_coupon_must_share eq 0 OR $order.show_lottery_first gt 0">style="display:none"</if>></div>
            <div class="coupon_share Coupon coupon_my" <if condition="$config.get_coupon_must_share eq 0">style="display:none"</if> >
                <span class="delate_money"  style="float:right"></span>
				<h3>恭喜您获得{pigcms{$config.share_coupon_num}张优惠劵</h3>
                <p>赶快把优惠劵分享给大伙伴们抢吧<if condition="$config.get_coupon_must_share eq 1">,分享后您自己可获得{pigcms{$config.share_coupon_get_num}张优惠劵</if></p>
                <button class="btn" id="share_btn">立即分享</button>
            </div>
         </if>
		  <if condition="$config.open_share_lottery eq 0 AND $order.paid  eq 1  AND  ($order['share_status'] eq 1 OR $config['get_coupon_must_share'] eq 0) AND $config.share_coupon eq 1 AND $config.share_coupon_num gt 0 AND $order.show_lottery_first eq 0 AND !empty($share_coupon)">
            <div class="coupon_share mongolia_layer" ></div>
            <div class="coupon_share Coupon" >
                <span class="delate_money" style="float:right"></span>
				<h3>恭喜您获得{pigcms{$config.share_coupon_get_num}张平台优惠劵</h3>
                <p>已存入您的账号</p>
                <button class="btn" id="use_right_now">立即查看</button>
            </div>
         </if>
  
    </body>

	<script>
	
	
			window.shareData = {
				"moduleName":"Store",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else />{pigcms{$config.site_logo}</if>", 
				"sendFriendLink": "<if condition="$config.share_coupon eq 1 AND $config.open_share_lottery eq 0">{pigcms{$config.site_url}{pigcms{:U('Share_lottery/share_coupon',array('order_id'=>$order['order_id'],'type'=>'store'))}<else />{pigcms{$config.site_url}{pigcms{:U('My/pay', array('store_id' => $order['store_id']))}</if>",
				"tTitle": "<if condition="$config.share_coupon eq 1  AND $config.open_share_lottery eq 0">{pigcms{$config.share_coupon_title}<else />{pigcms{$config.cash_alias_name|default="快速买单"} - {pigcms{$config.site_name}</if>",
				"tContent": "<if condition="$config.share_coupon eq 1 AND $config.open_share_lottery eq 0">{pigcms{$config.share_coupon_num}张优惠劵，快来抢啊！<else />{pigcms{$config.seo_description}</if>"
			};
			
			function call_back(){

				<if condition="$config.open_share_lottery eq 0 AND $order.paid  eq 1 AND $config.share_coupon eq 1 ">
					$.post('{pigcms{$config.site_url}{pigcms{:U('My/ajax_share_friend')}', {order_type:'store',order_id: {pigcms{$order['order_id']}}, function(data, textStatus, xhr) {
						if(!data.status){
							layer.open({content:data.info,btn: ['确定']});
						}
						// window.location.reload();
					},'json');
				
				</if>
				
				<if condition="$config.open_share_lottery eq 1 AND $order.paid  eq 1 AND !empty($lottery)  AND $lottery_info.status neq 1" >
					$.post('{pigcms{$config.site_url}{pigcms{:U('My/ajax_share_friend')}', {order_type:'store',order_id: {pigcms{$order['order_id']}}, function(data, textStatus, xhr) {
						if(data.status){
							$('#float-open').show(); 
							alert(data.info)
						}
					},'json');
				</if>
			}
			
			
			<if condition="$config.open_share_lottery eq 0 AND $order.paid  eq 1 AND   $config.share_coupon eq 1 AND $order['share_status'] eq 0 AND $config.get_coupon_must_share eq 0">
					$.post('{pigcms{$config.site_url}{pigcms{:U('My/ajax_share_friend')}', {order_type:'store',order_id: {pigcms{$order['order_id']}}, function(data, textStatus, xhr) {
						if(!data.status){
							//layer.open({content:data.info,btn: ['确定']});
						}
						//window.location.reload();
					},'json');
				
				</if>
			<if condition="$config.open_share_lottery eq 1 AND $order.paid  eq 1  AND $order['share_status'] eq 0 AND $config.lottery_must_share eq 0">
					$.post('{pigcms{$config.site_url}{pigcms{:U('My/ajax_share_friend')}', {order_type:'store',order_id: {pigcms{$order['order_id']}}, function(data, textStatus, xhr) {
						if(data.status){
							$('#float-open').show(); 

						}
						//window.location.reload();
					},'json');
				
				</if>
			$('.delate_money').click(function(){
                $('.mongolia_layer').hide();
                $('.Coupon').hide();
			})
			$('#share_btn').click(function(){
                $('.mongolia_layer').hide();
                $('.Coupon').hide();
					shareFriend();
					
				
			})
			
			$('#use_right_now').click(function(){
				window.location.href='{pigcms{:U('Share_lottery/my_get_coupon',array('order_id'=>$order['order_id'],'type'=>'store'))}';
			})
			
			

			  
		</script>
		<if condition="($config['open_share_lottery'] eq 1 OR $config.share_coupon eq 1) AND $order.paid  eq 1  ">
	{pigcms{$shareScript}
	</if>
</html>
<style>
	.lottery_before{
		color:#999;
		border-color:#999;
	}
	.lottery{
		color:red;
		border-color:red;
	}
	.float-open{
		right: 0px;
		height: 60;
		padding: 4px 4px 4px 6px;
		width: 60px;
		z-index: 99;
		top: 50%;
		position: fixed;
	}
</style>
<script>
$(document).ready(function(){
	$('.consumes ul li').click(function(){
        location.href = $(this).data('url');
    });
	
	
	$('#send_friend').click(function(){
		$('.coupon_my').show();
		
	})
	
	$('#float-open').click(function(){
		$.post('{pigcms{$config.site_url}{pigcms{:U('My/ajax_share_friend')}', {order_type:'store',order_id: {pigcms{$order['order_id']}}, function(data, textStatus, xhr) {
			if(data.status){
					window.location.href=data.url;
			}else{
					alert(data.info)
					window.location.reload();
			}
		},'json');
	});
	
   $('#phone').click(function(){
        if($(this).attr('data-phone')){
            var tmpPhone = $(this).attr('data-phone').split(' ');
            var msg_dom = '<div class="msg-bg"></div>';
            msg_dom+= '<div id="msg" class="msg-doc msg-option">';
            msg_dom+= '<div class="msg-bd">'+($(this).data('phonetitle') ? $(this).data('phonetitle') : '拨打电话')+'</div>';
            for(var i in tmpPhone){
                msg_dom+= '<div class="msg-option-btns"><a class="btn msg-btn" href="tel:'+tmpPhone[i]+'">'+(tmpPhone.length == 1 && $(this).data('phonetip') ? $(this).data('phonetip') : tmpPhone[i])+'</a></div>';
            }
            msg_dom+= '     <button class="btn msg-btn-cancel" type="button">取消</button>';
            msg_dom+= '</div>'; 
            $('body').append(msg_dom);
        }
        event.stopPropagation();
    });
    $(document).on('click','.msg-btn-cancel,.msg-bg',function(){
        $('.msg-doc,.msg-bg').remove();
    });
});


$(".public").each(function(){
    $(this).css("top",$(".t_remind").height())
});
$(".defrayal").each(function(){
    $(this).css("top",$(".t_remind").height()+44);
});
$(".h105").each(function(){
    $(this).css("height",105 + $(".t_remind").height());
})

function appbackmonitor(){
	if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
		$('body').append('<iframe src="pigcmso2o://closeWebView" style="display:none;"></iframe>');
	}else{
		window.lifepasslogin.closeWebView();
	}
}

function shareFriend(){
	if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
		$('body').append('<iframe src="pigcmso2o://shareWeChat" style="display:none;"></iframe>');
	}else{
		window.lifepasslogin.shareWeChat(
			"<if condition="$config.share_coupon eq 1  AND $config.open_share_lottery eq 0">{pigcms{$config.share_coupon_title}<else />{pigcms{$config.cash_alias_name|default="快速买单"} - {pigcms{$config.site_name}</if>"
			,"<if condition="$config.share_coupon eq 1 AND $config.open_share_lottery eq 0">{pigcms{$config.share_coupon_num}张优惠劵，快来抢啊！<else />{pigcms{$config.seo_description}</if>"
			,"<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else />{pigcms{$config.site_logo}</if>"
			,"<if condition="$config.share_coupon eq 1 AND $config.open_share_lottery eq 0">{pigcms{$config.site_url}{pigcms{:U('Share_lottery/share_coupon',array('order_id'=>$order['order_id'],'type'=>'store'))}<else />{pigcms{$config.site_url}{pigcms{:U('My/pay', array('store_id' => $order['store_id']))}</if>"
		);
	}
}



</script>