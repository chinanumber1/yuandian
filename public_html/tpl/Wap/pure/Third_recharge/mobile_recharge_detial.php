<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>话费充值订单详情</title>
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
            <a class="return link-url" href="{pigcms{:U('Wap/Third_recharge/mobile_recharge_list')}"></a>
            <div class="content">话费充值订单详情</div>
            <div class="ipho phone" data-phone="{pigcms{$config['site_phone']}"></div>
        </section>
      
        <section class="g_details p40">
            
            <div class="infor">
                <ul>
                    <li class="first ">
                        <a href="javascript:;">
                          
                            <div class="tit">话费充值<if condition="$now_order.old_money eq 0">{pigcms{$now_order.money|floatval}<else />{pigcms{$now_order.old_money|floatval}</if>元</div>
                        </a>
                    </li>
                </ul>
                
		
              
                <div class="answer clr">
                    <div class="fl">订单￥{pigcms{$order['total_money']|floatval} </div>
                    <div class="fr">应收总额: ￥{pigcms{$now_order.money|floatval}</div>
                </div>
                
            </div>
            <div class="infor">
                <ul>
                    <li class="clr first">
                        <div class="fl book">订单信息</div>
                    </li>
                    <li class="clr">
                        <div class="fl">订单编号</div>
                        <div class="fr">{pigcms{$order['business_id']}</div>
                    </li>
                  
                  
                </ul>
            </div>
             <if condition="$order['paid'] eq 1 ">
             <div class="infor">
                 <ul>
        	         <li class="clr first">
        	             <div class="fl branch">支付信息</div>
        	         </li>
                     <li class="clr">
                         <div class="fl">充值状态</div>
                         <div class="fr">{pigcms{$now_order.status_txt}</div>
                     </li>
					 <li class="clr">
                         <div class="fl">支付时间</div>
                         <div class="fr">{pigcms{$now_order['pay_time']|date='Y/m/d H:i:s',###}</div>
                     </li>
					
                     <li class="clr">
                         <div class="fl">支付方式</div>
                         <div class="fr">{pigcms{$order['pay_type_txt']}</div>
                     </li>
					<php>if(!$order['is_refund']){</php>
						 <li class="clr">
							 <div class="fl">应收总额</div>
							 <div class="p90">
								
								 <p class="e2c">￥{pigcms{$order['total_money']|floatval}</p>
							   
							 </div>
						 </li>
					<php>}</php>
        	        <php>if(!$order['is_refund']){</php>
        	         <if condition="$order['system_score_money'] gt 0">
                     <li class="clr">
                         <div class="fl">积分抵扣</div>
                         <div class="fr e2c">-￥{pigcms{$order['system_score_money']|floatval}（使用{pigcms{$order['system_score']|floatval}积分）</div>
                     </li>
                     </if>
                  
                     <if condition="$order['system_balance'] gt 0">
                     <li class="clr">
                         <div class="fl">平台余额支付</div>
                         <div class="fr e2c">-￥{pigcms{$order['system_balance']|floatval}</div>
                     </li>
                     </if>
                     <if condition="$order['pay_money'] gt 0">
                     <li class="clr">
                         <div class="fl">{pigcms{$order['pay_type_txt']}</div>
                         <div class="fr e2c">-￥{pigcms{$order['pay_money']|floatval}</div>
                     </li>
                     </if>
						 <php>}</php>
                 </ul>
             </div>
			 <div class="consume consumes">
                <ul class="clr">
					<if condition="$now_order['status'] eq 6 OR $now_order['status'] eq 12">
						 <li class="fr zlyd" data-url="{pigcms{:U('Third_recharge/mobile_recharge_wx_refund')}&order_id={pigcms{$now_order.order_id}">退款</li>
					<else />
						<li class="fr zlyd" data-url="{pigcms{:U('Third_recharge/mobile_recharge')}">再来一单</li>
					</if>
                </ul>
            </div>
            <else />
			 <div class="infor">
                 <ul>
        	         <li class="clr first">
        	              <div class="fl">充值状态</div>
                         <div class="fr">未支付</div>
        	         </li>
				</ul>
			</div>
			<div class="consume consumes">
                <ul class="clr">
                    <li class="fr zlyd" data-url="{pigcms{:U('Pay/check',array('order_id'=>$order['order_id'],'type'=>'plat'))}">去支付</li>
                </ul>
            </div>
			</if>
            
        </section> 
		
		
	
  
    </body>

	<if condition="$is_wexin_browser">
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
	
    $(document).on('click','.phone',function(event){
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


</script>