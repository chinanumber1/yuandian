<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>确认订单</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/wap_pay_check.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/weixin_pay.css" rel="stylesheet"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
	<link href="{pigcms{$static_path}css/check.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/way.css" rel="stylesheet"/>
</head>
<body>


<?php if($is_app_browser && in_array($app_browser_type,array('android','ios')) && ($_REQUEST['app_version'] ? $_SESSION['app_version'] : $_SESSION['app_version']  || $app_version >= 60) ){ ?>
 <script type="text/javascript">
    <if condition="$app_browser_type eq 'android'">
        window.lifepasslogin.payCheck("{pigcms{$order_info['order_type']}","{pigcms{$order_info['order_id']}");
        layer.open({type: 2});
        function ReturnLastPay(){
            history.back();
        }
		function ReturnLastPage(){
            history.back();
        }
    <else/>
		<if condition="$app_version egt 60">
			$('body').append('<iframe src="pigcmso2o://gopay/{pigcms{$order_info.order_type}/{pigcms{$order_info.order_id}" style="display:none"></iframe>');
		<else/>
			$('body').append('<iframe src="pigcmso2o://gopay/<?php $arr=array('type'=>$order_info['order_type'],'order_id'=>$order_info['order_id']); echo base64_encode(json_encode($arr)); ?>" style="display:none"></iframe>');
		</if>
        function payCheck(){
           window.location.reload();
        }
    </if>
 </script>
    <?php }else{ ?>
		<script type="text/javascript">
        var  now_money = Number("{pigcms{$now_user.now_money}");
        var  order_type = '{pigcms{$_GET['type']}';
        var  merchant_money = Number("{pigcms{$merchant_balance}");
        var  wx_cheap =Number("<?php if($cheap_info['can_cheap']){ ?>{pigcms{$cheap_info.wx_cheap}<?php }else{?>0<?php }?>");
        var  extra_price =Number("{pigcms{$order_info.extra_price}");
        var  open_extra_price =Number("{pigcms{$config.open_extra_price}");
        var  score_get_percent =Number("{pigcms{$config.user_score_get}");
        var  extra_price_name ="{pigcms{$config.extra_price_alias_name}";
        var  order_extra_price =Number("{pigcms{$order_info.order_extra_price}");
        var  no_discount_money =Number("{pigcms{$order_info.no_discount_money}");
        var  score_count = Number("{pigcms{$score_count}");
        var  score_deducte = Number("{pigcms{$score_deducte}");
		var  score_percent = Number("{pigcms{$config.user_score_use_percent}");
        var  score_can_use_count = Number("{pigcms{$score_can_use_count}");
        var  merc_price = Number("<?php if($card_coupon['discount']>0){ ?>{pigcms{$card_coupon.discount}<?php }else{?>0<?php }?>"); 
		var  sysc_price = Number("<?php if($system_coupon['discount']>0){ ?>{pigcms{$system_coupon.discount}<?php }else{?>0<?php }?>");
        var  total_money = Number("{pigcms{$order_info.order_total_money}");
        var  card_discount = Number("<?php if($card_info['discount']){ ?>{pigcms{$card_info.discount}<?php }else{?>10<?php }?>");
        var can_discount_table_money = Number("{pigcms{$order_info.order_info.can_discount_table_money}");
		if(order_type=='shop'||order_type=='mall'){
			var freigh_money = Number("{pigcms{$order_info.freight_charge}");
			total_money = (total_money-freigh_money)*card_discount/10+freigh_money-wx_cheap-merc_price;
		}else{			
			total_money = (total_money-no_discount_money)*card_discount/10-wx_cheap-merc_price+no_discount_money;
		}
		
		total_money+=order_extra_price;
	
        var  need_pay;
		var mer_coupon_html;
		var system_coupon_html;
		var sysc_price_tmp = sysc_price;
		var merc_price_tmp = merc_price;
        $(document).ready(function() {
			mer_coupon_html = $('#mer_coupon').html();
			system_coupon_html = $('#system_coupon').html();
			check_money(total_money,sysc_price,merchant_money);
			//点击事件
			$("#use_score,#use_balance,#use_merchant_money").bind("click", function () {

				check_money(total_money,sysc_price,merchant_money);
				if($("#use_score").is(':checked')==true){
					$('#score_money_title').css('display','block');
					$('#score_money_title .pay-wrapper').html('使用平台{pigcms{$config['score_name']}抵扣'+score_deducte+'元');
				}else{
					$('#score_money_title').css('display','none');
				}
			});

			$("form").submit(function() {
				$("button.mj-submit").attr("disabled", "disabled");
				$("button.mj-submit").html("正在处理...");
			});
			$('#pay-methods-panel').find('.list .dd-padding').click(function(){
					
				if($(this).find('input').val()=='offline'){
					//window.location.href=window.location.href+'&pay_type=offline';
					var mer_coupon_none = '<a class="react" ><div class="more more-weak"><h6>商家优惠券</h6><span class="more-after">无可用优惠券</span></div></a>';
					var system_coupon_none = '<a class="react" ><div class="more more-weak"><h6>平台优惠券</h6><span class="more-after">无可用优惠券</span></div></a>';
					$('#system_coupon').html(system_coupon_none);
					$('#mer_coupon').html(mer_coupon_none);
					check_money(Number("{pigcms{$order_info.order_total_money}"),0,0);
					sysc_price = 0;
					merc_price = 0;
					$('#pay_in_fact').html('实际支付：<b style="color:red">￥'+(Number("{pigcms{$order_info.order_total_money}")+merc_price).toFixed(2)+'</b>');
					$('input[name="card_id"]').attr('disabled','disabled');
					$('input[name="coupon_id"]').attr('disabled','disabled');
				}else{
					//window.location.reload();
					$('#system_coupon').html(system_coupon_html);
					$('#mer_coupon').html(mer_coupon_html);
					total_money = Number("{pigcms{$order_info.order_total_money}");
					if(order_type=='shop'||order_type=='mall'){
						var freigh_money = Number("{pigcms{$order_info.freight_charge}");
						total_money = (total_money-freigh_money)*card_discount/10+freigh_money-wx_cheap-merc_price_tmp;
					}else{			
						total_money = (total_money-no_discount_money)*card_discount/10-wx_cheap-merc_price_tmp+no_discount_money;
					}
					check_money(total_money,sysc_price_tmp,merchant_money);
					var pay_infact_ = (total_money-sysc_price).toFixed(2);
					if(pay_infact_<0){
						pay_infact_ = 0;
					}
					//$('#pay_in_fact').html('实际支付：<b style="color:red">￥'+pay_infact_+'</b>');
					$('input[name="card_id"]').removeAttr('disabled');
					$('input[name="coupon_id"]').removeAttr('disabled');
					sysc_price = sysc_price_tmp ;
					merc_price = merc_price_tmp;
				}
			})
			
			if($('#pay-methods-panel').find('.list .dd-padding input').val()=='offline'){
				
				//window.location.href=window.location.href+'&pay_type=offline';
				var mer_coupon_none = '<a class="react" ><div class="more more-weak"><h6>商家优惠券</h6><span class="more-after">无可用优惠券</span></div></a>';
				var system_coupon_none = '<a class="react" ><div class="more more-weak"><h6>平台优惠券</h6><span class="more-after">无可用优惠券</span></div></a>';
				$('#system_coupon').html(system_coupon_none);
				$('#mer_coupon').html(mer_coupon_none);
		
				check_money(Number("{pigcms{$order_info.order_total_money}"),0,0);
				sysc_price = 0;
				merc_price = 0;
				$('#pay_in_fact').html('实际支付：<b style="color:red">￥'+(total_money+merc_price).toFixed(2)+'</b>');
				$('input[name="card_id"]').attr('disabled','disabled');
				$('input[name="coupon_id"]').attr('disabled','disabled');
			}else{
				$('#system_coupon').html(system_coupon_html);
				$('#mer_coupon').html(mer_coupon_html);
				total_money = Number("{pigcms{$order_info.order_total_money}");
					if(order_type=='shop'||order_type=='mall'){
						var freigh_money = Number("{pigcms{$order_info.freight_charge}");
						total_money = (total_money-freigh_money)*card_discount/10+freigh_money-wx_cheap-merc_price_tmp;
					}else{			
						total_money = (total_money-no_discount_money)*card_discount/10-wx_cheap-merc_price_tmp+no_discount_money;
					}
					
					check_money(total_money,sysc_price_tmp,merchant_money);
				var pay_infact_ = (total_money-sysc_price).toFixed(2);
					if(pay_infact_<0){
						pay_infact_ = 0;
					}
				//$('#pay_in_fact').html('实际支付：<b style="color:red">￥'+pay_infact_+'</b>');
				$('input[name="card_id"]').removeAttr('disabled');
				$('input[name="coupon_id"]').removeAttr('disabled');
				sysc_price = sysc_price_tmp ;
				merc_price = merc_price_tmp;
			}
			
			
			$('#alter_score').click(function(){
			
				$('#change_score').show();
				$('#pwd_bg').show();
				
			});
			$('.cancle').click(function(){
				$('#pwd_bg').hide();
				$('.pwd_verify').hide();
			})
			
			var score_money_sure = 0;
			$('.jia').click(function(){
				var score_change = $('input[name="score_change"]').val();
				if(score_can_use_count>=(Number(score_change)+1)){
					var score_counts = Number(score_change)+1;
				}else{
					var score_counts = score_can_use_count;
				}
				$('input[name="score_change"]').val(score_counts.toFixed(2));
				score_money_sure = (score_counts/score_percent).toFixed(2);
				$('.verify_lspan').html('￥'+score_money_sure);
			});
			$('.jian').click(function(){
				var score_change = $('input[name="score_change"]').val();
				if(Number(score_change)-1>=0){
					var score_counts = Number(score_change)-1;
				}else{
					var score_counts = 0;
				}
				$('input[name="score_change"]').val(score_counts.toFixed(2));
				score_money_sure = (score_counts/score_percent).toFixed(2);
				$('.verify_lspan').html('￥'+score_money_sure);
			});
			
			$('#score_sure').click(function(){
				$('#pwd_bg').hide();
				$('.pwd_verify').hide();
				var score_count_sure = $('input[name="score_change"]').val();
				score_deducte = score_money_sure;
				$('input[name="score_used_count"]').val(score_count_sure);
				$('input[name="score_deducte"]').val(score_deducte);
				$('#score_label').html('使用<font color="red">'+score_count_sure+'</font>个'+extra_price_name+'，抵扣：<font color="red">'+score_money_sure+'</font>元 ');
				$('#use_score').removeAttr('checked');
				$('#use_balance').removeAttr('checked');
				show_money(1,1,1,1,total_money);
				
			});
			
			$('#score_value').blur(function(){
				if(isNaN($(this).val())|| Number($(this).val())<0){
					alert('非法输入');
					window.location.reload();
				}
				if(Number($(this).val())>score_can_use_count){
					$('#change_score .tips').html('最多使用'+score_can_use_count+'个'+extra_price_name);
					$('input[name="score_change"]').val(score_can_use_count);
				}else{
					var changed_score = $(this).val();
					score_money_sure = (changed_score/score_percent).toFixed(2);
					$('.verify_lspan').html('￥'+score_money_sure);
					$('input[name="score_change"]').val(Number(changed_score).toFixed(2));
					$('#change_score .tips').html('');
				}
			});
			
			$('.mt[readonly]').click(function(){
				// alert('您勾选的支付方式已足够支付订单金额')
				layer.open({
					content: '您勾选的支付方式已足够支付订单金额',
					btn: ['知道了'],
					shadeClose: false,
					yes: function(){
						layer.closeAll();
					}
				});
			})

        });

        //显示
		function show_money(use_merchant_money,use_score,use_balance,pay_title,money){
			console.log(use_score)
			if($('#use_merchant_money').is(':checked')==true){ 
				$("input[name='use_merchant_balance']").attr('value',1);
			}else{
				$("input[name='use_merchant_balance']").attr('value',0);
			}

			if($("#use_balance").is(':checked')==true&&use_balance){
				$("input[name='use_balance']").attr('value',1);
			}else{
				$("input[name='use_balance']").attr('value',0);
			}

			if($("#use_score").is(':checked')==true){
				var score_money = score_deducte;
				$("input[name='use_score']").attr('value',1);
			}else{
				var score_money = 0;
				$("input[name='use_score']").attr('value',0);
			}

			if(money==0){
				$('#need_pay_title').css('display','none');
			}else{
				$('#need_pay_title').css('display','block');
			}
			if(!pay_title){ //弹出来的支付方式
				$('#system_coupon').html(system_coupon_html);
				$('#mer_coupon').html(mer_coupon_html);
				$('#balanceBox').css('margin-bottom','+60px');
				$('#normal-fieldset').css('display','none');
				$('#normal-fieldset input[type="radio"]').removeAttr('checked');
				
			}else{
				$('#balanceBox').css('margin-bottom','0px');
				if($('#normal-fieldset').css('display')=='none'){
					$('#normal-fieldset input[type="radio"]:first').attr('checked','checked');
				}
				$('#normal-fieldset').css('display','block');
				
			}
			if(!use_merchant_money){
				$('#merchant_money').css('color','#C1B9B9');
				$('#use_merchant_money').removeAttr('checked');
				$('#use_merchant_money').attr('readonly','readonly');
				$("input[name='use_merchant_balance']").attr('value',0);
			}else{
				if(merchant_money>0){
					$('#merchant_money').css('color','#666666');
					$('#use_merchant_money').removeAttr('readonly');
				}else{
					$("input[name='use_merchant_balance']").attr('value',0);
				}
			}
			if(!use_score){
				$("input[name='use_score']").attr('value',0);
				$('#score_money').css('color','#C1B9B9');
				$('#use_score').removeAttr('checked');
				$('#use_score').attr('readonly','readonly');
				
			}else{
				if(score_deducte>0){
					$('#score_money').css('color','#666666');
					$('#use_score').removeAttr('readonly');
				}else{
					$('.mt .score_money').css('display','none');
					$("input[name='use_score']").attr('value',0);
				}
			}
			if(!use_balance){
				$("input[name='use_balance']").attr('value',0);
				$('#balance_money').css('color','#C1B9B9');
				$('#use_balance').removeAttr('checked');
				$('#use_balance').attr('readonly','readonly');
			}else{
				if(now_money>0){
					$('#balance_money').css('color','#666666');
					$('#use_balance').removeAttr('readonly');
				}else{
					$("input[name='use_balance']").attr('value',0);
				}
			}
			$('.need-pay').html(Number(money).toFixed(2))
			var extra_price_str = '';

			if(total_money-score_money-sysc_price<0){
				$('#pay_in_fact').html('实付款 0 元'+extra_price_str);
			}else{
				if(open_extra_price==1&&score_money>0){
					extra_price_str = $('input[name="score_change"]').val()+'元宝';
					$('#pay_in_fact').html('实际支付：<b style="color:red">￥'+(total_money-sysc_price-score_money).toFixed(2)+'+'+extra_price_str+'</b>');
				}else{
					var pay_infact_ = (total_money-sysc_price).toFixed(2);

                    if(typeof card_discount !="undefined" && card_discount > 0){
                        var can_discount_table_money_card = can_discount_table_money * (card_discount/10);
                    }

					if($('#mer_table_discount').text()){
                        var reduce_money = (can_discount_table_money_card - (can_discount_table_money_card * parseFloat($('#mer_table_discount').text() / 10)).toFixed(2)).toFixed(2);
					    pay_infact_ = parseFloat(pay_infact_ - reduce_money - score_money ).toFixed(2);
					}else{
                        pay_infact_ = parseFloat(pay_infact_  - score_money ).toFixed(2);
					}

					if(pay_infact_<0){
                        pay_infact_ = 0;
                    }

					$('#pay_in_fact').html('实际支付：<b style="color:red">￥'+pay_infact_+'</b>');
				}
                // $('.need-pay').html(Number(pay_infact_).toFixed(2))
			}
			var pay_score = 0
			if($("#use_score").is(':checked')==true){
				
				var pay_score = $('input[name="score_change"]').val();
			}
			get_score(pay_score);
		}
		
		function get_score(pay_score){
			var give_score = 0;
			if(pay_score==extra_price){
				give_score =  0;
			}else if(order_type=='cash'||order_type=='store'){
				give_score =(total_money*score_get_percent.toFixed(2)); 
			
			}else if(pay_score==0&&extra_price>0){
				give_score =(total_money*score_get_percent.toFixed(2));
			
			}else if(pay_score<extra_price){
				give_score = extra_price-pay_score;
			}
			
			$('#give_score').html('可获得'+give_score+extra_price_name)

		}

		function check_money(total_money,sysc_price,merchant_money){
			
			console.log('-----------------')
			if(total_money>0){
				if($('#use_merchant_money').is(':checked')==true){   								//使用商家余额
		
					$("input[name='use_merchant_balance']").attr('value',1);
					if(sysc_price>0){    
						var total_money_tmp = total_money-sysc_price;
						
						
						if($("#use_balance").is(':checked')==true){
							if($("#use_score").is(':checked')==true){
								total_money_tmp -= score_deducte;
								total_money_tmp=total_money_tmp.toFixed(2)
							}
							if(total_money_tmp==0){
								show_money(0,1,0,0,0); 
							}else{
								if(merchant_money>=total_money_tmp){
									show_money(1,1,0,0,0);                // 显示 {pigcms{$config['score_name']}，余额，支付方式
								}else if(now_money>=total_money_tmp-merchant_money){
									show_money(1,1,1,0,0);                // 
								}else if(now_money==0){
									show_money(1,1,0,1,total_money_tmp-merchant_money);
								}else{
									show_money(1,1,1,1,total_money_tmp-merchant_money-now_money);
								}
							}
						}else{
							
							if($("#use_score").is(':checked')==true){
								total_money_tmp -= score_deducte;
								total_money_tmp=total_money_tmp.toFixed(2)
							}
							if(total_money_tmp==0){
								show_money(0,1,0,0,0); 
							}else{
								if(merchant_money>=total_money_tmp){
									show_money(1,1,0,0,0);
								}else{
									if(now_money==0){
										show_money(1,1,0,1,total_money_tmp-merchant_money);
									}else{
										show_money(1,1,1,1,total_money_tmp-merchant_money);
									}
								}
							}
						}  
						if(total_money_tmp<0){
							show_money(0,0,0,0,0);        
						}

					}else{  																			//不使用系统优惠券
					
						var total_money_tmp = total_money;
						
						if($("#use_balance").is(':checked')==true){
							if($("#use_score").is(':checked')==true){
								total_money_tmp -= score_deducte;
								total_money_tmp=total_money_tmp.toFixed(2)
							}
							
							if(total_money_tmp==0){
								show_money(0,1,0,0,0); 
							}else{
								if(merchant_money>=total_money_tmp){
									show_money(1,1,0,0,0);
								}else if(now_money>=total_money_tmp-merchant_money){
									
									show_money(1,1,1,0,0);
								}else if(now_money==0){
									show_money(1,1,0,1,total_money_tmp-merchant_money);
								}else{
									show_money(1,1,1,1,total_money_tmp-merchant_money-now_money);
								}
							}
						}else{
							if($("#use_score").is(':checked')==true){
								total_money_tmp -= score_deducte;
								total_money_tmp=total_money_tmp.toFixed(2)
							}
							if(total_money_tmp==0){
								show_money(0,1,0,0,0); 
							}else{
								
								if(merchant_money>=total_money_tmp){
									show_money(1,1,0,0,0);
								}else{
									if(now_money==0){
										show_money(1,1,0,1,total_money_tmp-merchant_money);
									}else{
										show_money(1,1,1,1,total_money_tmp-merchant_money);
									}
								}
							}
						}  
					}
				}else{  			
					//不使用商家余额
					$("input[name='use_merchant_balance']").attr('value',0);	
					if(sysc_price>0){  				//使用系统优惠券
						var total_money_tmp = total_money-sysc_price; 
						
						if($("#use_balance").is(':checked')==true){
						
							if($("#use_score").is(':checked')==true){
								total_money_tmp -= score_deducte;
								total_money_tmp=total_money_tmp.toFixed(2)
							
							}
							if(total_money_tmp==0){
								show_money(0,1,0,0,0); 
							}else{
								if(now_money>=total_money_tmp){
									show_money(1,1,1,0,0);
								}else if(now_money==0){
									show_money(1,1,0,1,total_money_tmp);
								}else{
									show_money(1,1,1,1,total_money_tmp-now_money);
								}
							}
						}else{
							if($("#use_score").is(':checked')==true){
								total_money_tmp -= score_deducte;
								total_money_tmp=total_money_tmp.toFixed(2)
							}
							if(total_money_tmp==0){
								show_money(0,1,0,0,0); 
							}else{
							
								if(now_money==0){
									show_money(1,1,0,1,total_money_tmp);
								}else{
									show_money(1,1,1,1,total_money_tmp);
								}
							}
						} 
						if(total_money_tmp<0){
							show_money(0,0,0,0,0);        
						}
					}else{   																		//不使用系统优惠券
						var total_money_tmp = total_money;
						
						if($("#use_balance").is(':checked')==true){
							if($("#use_score").is(':checked')==true){
								total_money_tmp -= score_deducte;
								total_money_tmp=total_money_tmp.toFixed(2)
							}
							if(total_money_tmp==0){
								show_money(0,1,0,0,0); 
							}else{
								if(now_money>=total_money_tmp){
									show_money(0,1,1,0,0);
								}else if(now_money==0){
									show_money(1,1,0,1,total_money_tmp);
								}else{
									show_money(1,1,1,1,total_money_tmp-now_money);
								}
							}
						}else{

							if($("#use_score").is(':checked')==true){
								total_money_tmp -= score_deducte;
								total_money_tmp=total_money_tmp.toFixed(2)
							}
							if(total_money_tmp==0){
								show_money(0,1,0,0,0); 
							}else{
								if(now_money==0){
									
									show_money(1,1,0,1,total_money_tmp);
								}else{
									show_money(1,1,1,1,total_money_tmp);
								}
							}
						} 
					}
				}
			}else{
				show_money(0,0,0,0,0); //积分，商家余额，余额都隐藏
			}
		}
		
	<if condition="$config['twice_verify']">var twice_verify = true;<else />var twice_verify = false;</if>
	<if condition="$_SESSION['user']['verify_end_time']">var verify_end_time = {pigcms{$_SESSION['user']['verify_end_time']};</if>
    </script>
	<script language="javascript">
	function bio_verify(){
		layer.open({type:2,content:'页面加载中',shadeClose:false});
		var pay_type = $('input:radio:checked').val();
		$("button.mj-submit").attr("disabled", "disabled");
		$("button.mj-submit").html("正在处理...");
		var use_score= $("input[name='use_score']").val();
		var use_balance= $("input[name='use_balance']").val();
		//var  merchant_money = Number("{pigcms{$merchant_balance}");
		if(twice_verify&&(merchant_money!=0||use_balance==1||use_score==1)){
			if(typeof(wxSdkLoad) != "undefined"){
				wx.invoke('getSupportSoter', {}, function (res) {
				  if(res.support_mode=='0x01'){
					wx.invoke('requireSoterBiometricAuthentication', {
					  auth_mode: '0x01',
					  challenge: 'test',
					  auth_content: '请将指纹验证'  //指纹弹窗提示
					}, function (res) {
						if(res.err_code==0&&pay_type=='weixin'){
							callpay();
						}else if(res.err_code==0){
							layer.closeAll();
							$('#pay-form').submit();
						}else if (res.err_code==90009){
							layer.closeAll();
							$('#pwd_bg').css('display','block');
							$('#pwd_verify').css('display','block');
						}else{
							alert(res.err_code);
							$("button.mj-submit").removeAttr("disabled");
							$("button.mj-submit").html("确认支付");
						}
					})
				  }else{
					 // 密码验证
					 layer.closeAll();
					$('#pwd_bg').css('display','block')		
					$('#pwd_verify').css('display','block')		
				  }
				})
			}else{		
				layer.closeAll();
				$('#pwd_bg').css('display','block');
				$('#pwd_verify').css('display','block');
			}
		 
		}else{
			layer.closeAll();
			var res = callpay();
			if(res){
				$('#pay-form').submit();
			}
		}
	}
	
	//微信弹程支付
	function callpay(){
		var pay_type = $('input:radio:checked').val();
		if(typeof(pay_type)!='undefined'){
			if(pay_type!='weixin'){
				$('#pay-form').submit();
				return true;
			}else if(pay_type=='weixin'){
				var orderid_info = {pigcms{:json_encode($orderid_info)};
				var pay_money = <?php if($order_info['order_type']=='recharge'){ ?> total_money<?php }else{ ?>need_pay<?php }?>;
				var short_orderid = {pigcms{$order_info.order_id};
				$("button.mj-submit").attr("disabled", "disabled");
				$("button.mj-submit").html("正在处理...");
				var param;
				$.ajax({
					url: "{pigcms{:U('Pay/go_pay')}",
					type: 'POST',
					dataType: 'json',
					data: $('#pay-form').serialize(),
					beforeSend: function(){
						layer.open({type:2,content:'支付加载中',shadeClose:false});
					},
					success: function(date){
						layer.closeAll();
						layer.open({type:1,content:'',shadeClose:false});
					
						if(date.error == 0){
								param =  date.weixin_param;
								WeixinJSBridge.invoke("getBrandWCPayRequest",param,function(res){
									WeixinJSBridge.log(res.err_msg);
									if(res.err_msg=="get_brand_wcpay_request:cancel"){
										window.location.reload();
									}else if(res.err_msg=="get_brand_wcpay_request:ok"){
										setTimeout("window.location.href = '"+date.redirctUrl+"'",200);
										layer.closeAll();
										layer.open({type:2,content:'跳转加载中',shadeClose:false});
									}else{
										$("button.mj-submit").removeAttr("disabled");
										$("button.mj-submit").html("确认支付");
									}
								});
						}else{
							layer.open({content:date.info,btn:['确定'],yes:function(){
							
								if(date.url!=''){
									window.location.href=date.url;
								}else{
									window.location.reload();
								}
							}});
							$("button.mj-submit").removeAttr("disabled");
							$("button.mj-submit").html("确认支付");
						}
					}
				});
				return false;
			}
		}else{
			return true;
		}
	}	
	
	
	

</script>
		<script>layer.open({type:2,content:'页面加载中',shadeClose:false});</script>
        <div id="tips" class="tips"></div>
        <div class="wrapper-list">
			<dl class="list">
			    <dd>
			        <dl>
						 <dd class="kv-line-r dd-padding">
							<if condition="$order_info.order_type neq 'weidian'AND  $order_info.order_type neq 'store' AND  $order_info.order_type neq 'wxapp' AND $order_info['order_type'] neq 'recharge' AND $order_info['img']"><img src="{pigcms{$order_info.img}" style="width:80px;height:80px;"></if>
							<div>
								<p style="margin-left: 20px;"><if condition="$now_village">【{pigcms{$now_village.village_name}】</if>{pigcms{$order_info.order_name}</p>
								<if condition="$order_info.order_price gt 0 && $order_info['order_num'] gt 0">
									<p style="margin-left: 20px;margin-top: 10px;">￥ {pigcms{$order_info.order_price}
									<if condition="$order_info.is_head gt 0">(团长优惠)</if>
									<if condition="$order_info.extra_price gt 0 AND $config.open_extra_price eq 1">
										+{pigcms{$order_info.extra_pay_price}{pigcms{$config.extra_price_alias_name}
									</if>
									X {pigcms{$order_info.order_num}</p>
								</if>
								<if condition="$order_info.order_txt_type"><p style="margin-left: 20px;margin-top: 5px;">{pigcms{$order_info.order_txt_type}</p></if>
							
							</div>
						 </dd>
						 <dd class="kv-line-r dd-padding">
							<h6>订单总计</h6><p><b style="color:red">￥{pigcms{$order_info.order_total_money}<if condition="$order_info.extra_price gt 0 AND $config.open_extra_price eq 1 ">+{pigcms{$order_info.extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if> 	
							<if condition="$order_info.no_discount_money gt 0"><font size="1">(不可优惠￥{pigcms{$order_info.no_discount_money|floatval})</font></if></b></p>
					
						</dd>
			        </dl>
			    </dd>
			</dl>
			<if condition="$config['open_score_discount'] eq 1 AND $now_merchant['user_discount_type'] neq 0 AND $order_info['discount_status'] eq true">
				<div class=" parent parent1 after" <if condition="($now_merchant['user_discount_type'] eq 2  OR $now_merchant['user_discount_type'] eq 3 ) AND $_GET['discount_type'] neq 'coupon' ">style="display: none;"</if>>
					<div class="way_content">
						<p><i></i></p>
						<div class="way_right after " <if condition="$now_merchant['user_discount_type'] neq 3">style="padding-bottom: 0.474576rem;"</if>>
							<!-- <p class="change active rg"><span></span></p> -->
							<h2>优惠通道</h2>
							<p class="font_style">此通道除了不可获取平台积分，其他优惠均可享受</p>
						</div>
					</div>
					<if condition="$now_merchant['user_discount_type'] eq 3" ><p class="xiugai rg">修改通道></p></if>
				</div>
				<!--积分通道-->
				<div class="parent parent2 after"  <if condition="$now_merchant['user_discount_type'] eq 1 OR $now_merchant['user_discount_type'] eq 3 AND $_GET['discount_type'] neq 'score'">style="display: none;"</if>>
					<div class="integral">
						<p><i></i></p>
						<div class="integral_right after" <if condition="$now_merchant['user_discount_type'] neq 3">style="padding-bottom: 0.474576rem;"</if>>
							<h2>积分通道</h2>
							<p class="font_style">此通道支付时只可获得平台积分，其他商家会员卡折扣，平台/商家优惠劵等优惠均不可享受</p>
						</div>
					</div>
					<if condition="$now_merchant['user_discount_type'] eq 3"><p class="xiugai rg">修改通道></p></if>
				</div>
			</if>
			<if condition="$order_info['order_type'] != 'recharge' OR $order_info['order_type'] != 'weidian' ">
				<php>if($order_info['order_type']!='recharge' ){</php>
				<div id="balanceBox">
					<h4>结算信息</h4>
					<dl class="list">
						<dd>
							<dl>
								<php>//if($now_merchant['use_discount']){</php>
								<if condition="$card_info AND $card_info.discount lt 10 AND $card_info.discount gt 0">
									<dd class="kv-line-r dd-padding">
										<h6>会员卡折扣</h6><p  style="color:red;">{pigcms{$card_info.discount}折</p>
									</dd>
								</if>
								<if condition="$cheap_info['can_cheap']">
									<dd class="kv-line-r dd-padding">
										<h6>微信优惠</h6><p  style="color:red;">￥{pigcms{$cheap_info.wx_cheap}</p>
									</dd>
								</if>
                                <!-- 定制桌台优惠 "($name eq 1) OR ($name gt 100) "-->
                                <if condition="$tablesDiscount AND $tablesDiscount['mer_discount'] gt 0" >
                                    <dd class="kv-line-r dd-padding">
                                        <h6>桌次优惠</h6><p  style="color:red;"><span id="mer_table_discount">{pigcms{$tablesDiscount.mer_discount}</span>折（部分菜品不参与折扣）</p>
                                    </dd>
                                </if>

								<if condition="($_GET['type'] neq 'weidian' && $_GET['type'] neq 'plat')|| ($_GET['type'] == 'plat' && $order_info['pay_merchant_coupon'])">
									<?php if(empty($notCard)){ ?>
										
										<php>if($_GET['unmer_coupon'] && !$ban_mer_coupon){</php>
										
										
										<dd id="mer_coupon">
											<a class="react" href="{pigcms{:U('My/select_card',($coupon_url?$coupon_url :$_GET))}&coupon_type=mer" >
												<div class="more more-weak">
													<h6>商家优惠券</h6>
													<span class="more-after">不使用</span>
												</div>
											</a>
										</dd>
										<php>}else if(empty($card_coupon) || $ban_mer_coupon){</php>
										<dd id="mer_coupon">
											<a class="react" >
												<div class="more more-weak">
													<h6>商家优惠券</h6>
													<span class="more-after">无可用优惠券</span>
												</div>
											</a>
										</dd>
										<php>}else{</php>
										<dd id="mer_coupon">
											<a class="react" href="{pigcms{:U('My/select_card',($coupon_url ? $coupon_url :$_GET))}&coupon_type=mer">
												<div class="more more-weak">
													<h6>商家优惠券</h6>
													<span class="more-after" style="color:red;"><?php if($card_coupon){ ?>满{pigcms{$card_coupon.order_money}减{pigcms{$card_coupon.discount}<?php }else{ ?>使用优惠券<?php } ?></span>
												</div>
											</a>
										</dd>
										<php>}</php>
									<?php } ?>
								</if>
								<if condition="($_GET['type'] neq 'weidian' && $_GET['type'] neq 'plat') || ($_GET['type'] == 'plat' && $order_info['pay_system_coupon'])">
									<?php if(empty($notCard)){ ?>
										<dd id="system_coupon">
										<php>if($_GET['unsys_coupon']){</php>
											<dd >
												<a class="react" href="{pigcms{:U('My/select_card',($coupon_url?$coupon_url :$_GET))}&coupon_type=system" >
													<div class="more more-weak">
														<h6>平台优惠券</h6>
														<span class="more-after">不使用</span>
													</div>
												</a>
											</dd>
										<php>}else if(empty($system_coupon)){</php>
									
											<a class="react" >
												<div class="more more-weak">
													<h6>平台优惠券</h6>
													<span class="more-after">无可用优惠券</span>
												</div>
											</a>
											<?php }else{ ?>	
											
											<a class="react" href="{pigcms{:U('My/select_card',($coupon_url ? $coupon_url :$_GET))}&coupon_type=system">
												<div class="more more-weak">
													<h6>平台优惠券</h6>
													<span class="more-after"  style="color:red;"><?php if($system_coupon){ ?><php>if($system_coupon['order_money']>0){</php>满{pigcms{$system_coupon.order_money}
													<php>}</php>
													<php>if($system_coupon['is_discount']==1){</php>打{pigcms{$system_coupon.discount_value|floatval}折 
													<php>}else{</php>
													减{pigcms{$system_coupon.discount}
													<php>}</php><?php }else{ ?>使用优惠券<?php } ?></span>
												</div>
											</a>
											<?php } ?>
										</dd>
									<?php } ?>
								</if>
											<php>//}</php>
								<if condition="$score_deducte gt 0">
									<dd class="dd-padding" id="score_money">
										<label class="mt" ><span id="score_label">本单可使用<php>if($config['open_extra_price']==1){</php>{pigcms{$config.extra_price_alias_name}<php>}else{</php>{pigcms{$config['score_name']}<php>}</php><font color="red">{pigcms{$score_can_use_count}个</font>,可抵扣金额<font color="red">￥{pigcms{$score_deducte|floatval=###}</font></span> <span class="pay-wrapper"><input type="checkbox" class="mt" value="1" id="use_score" name="use_score"  <php>if($score_can_use_count==0){</php> readonly="readonly" value="1"<php>}else{</php>  value="0" checked="checked" <php>}</php>><p style="display:block;float:right;"></p></span></label><php> if($config['open_extra_price']==1){</php><a id="alter_score">修改</a><php>}</php>
									</dd>
								</if>
								<?php if($merchant_balance){ ?>
									
									<dd class="dd-padding" id="merchant_money" <if condition="$merchant_balance eq 0 OR $card_coupon.discount gt $order_info.order_total_money ">style="color: #C1B9B9;"</if>>
										<label class="mt"><span class="pay-wrapper">使用商家会员卡余额支付<br>剩余<font color="red">￥{pigcms{$merchant_balance}</font><input type="checkbox" class="mt"  id="use_merchant_money" name="use_merchant_money" <if condition="$merchant_balance eq 0 OR $card_coupon.discount gt $order_info.order_total_money "> disabled="disabled" value="1"<else /> value="0" checked="checked" </if> ></span></label>
									</dd>
								<?php } ?>
								<?php if($order_info['order_type'] != 'plat' || $order_info['pay_system_balance']){ ?>
									<dd class="dd-padding" id="balance_money" <if condition="$now_user.now_money eq 0 OR $merchant_balance gt $order_info.order_total_money ">style="color: #C1B9B9;"</if>>
										<label class="mt"><span class="pay-wrapper">使用余额支付<br><font color="red">可用余额￥{pigcms{$now_user.now_money}</font>
										<php>if(!isset($balance_disable)){</php>
										<input type="checkbox" class="mt"  id="use_balance" name="use_balance"<if condition="$now_user['now_money'] eq 0 OR $merchant_balance gt $order_info['order_total_money'] ">readonly="readonly" value="1"<else /> value="0" checked="checked" </if>>
										<php>}</php>
										</span></label>
									</dd>
								<?php } ?>
								<dd class="dd-padding" id="balance_money" >
									<!--<label class="mt" id="score_money_title" style="display:none"><span class="pay-wrapper"></span></label>-->
									<label class="mt">
									<if condition="$config.open_extra_price eq 1"><span style="float: left;" class="pay-wrapper" id="give_score"></span></if>
									<span style="float: right;" class="pay-wrapper" id="pay_in_fact"></span>
									<if condition="$_GET['type'] eq 'shop'"><br><span  class="pay-wrapper" style="float: right;font-size:10px;color:#ccc9c9;margin-top:2px">(运费不参与打折)</span></if></label>
								</dd>
							</dl>
						</dd>
					</dl>
				</div>
				<php>}</php>
			</if>
	
			<form action="/source{pigcms{:U('Pay/go_pay',array('showwxpaytitle1'=>1))}" method="POST" id="pay-form" class="pay-form" >
				<input type="hidden" name="order_id" value="{pigcms{$order_info.order_id}"/>
				<input type="hidden" name="order_type" value="{pigcms{$order_info.order_type}"/>
				<input type="hidden" name="card_id" value="{pigcms{$card_coupon.id}"/>
				<input type="hidden" name="coupon_id" value="{pigcms{$system_coupon.id}"/>
				<input type="hidden" name="use_score" value="0"/>
				<input type="hidden" name="use_balance" <if condition="$order_info['order_type'] eq 'recharge' OR $now_user['now_money'] eq 0 OR  ($order_info['order_type'] eq 'plat' && !$order_info['pay_system_balance'])">value="1"<else /> value="0" </if>/>
				<input type="hidden" name="score_used_count" value="{pigcms{$score_can_use_count}">
				<input type="hidden" name="score_deducte" value="{pigcms{$score_deducte}">
				<input type="hidden" name="score_count" value="{pigcms{$score_count}">
				<input type="hidden" name="use_merchant_balance" <if condition="$order_info['order_type'] eq 'recharge' OR $merchant_blance eq 0 OR ($order_info['order_type'] eq 'plat' && !$order_info['pay_merchant_balance'])">value="1"<else /> value="0" </if>>
				<input type="hidden" name="merchant_balance" value="{pigcms{$merchant_balance}">
				<input type="hidden" name="balance_money" value="{pigcms{$now_user.now_money}">
				<input type="hidden" name="discount_type" value="{pigcms{$_GET['discount_type']}">
				
					
				<div id="pay-methods-panel" class="pay-methods-panel">
					<div id="normal-fieldset" class="normal-fieldset" style="height: 100%;display:none;margin-bottom: 60px;" >
						<h4 style="margin: .3rem .2rem .2rem;">选择支付方式</h4>
						<dl class="list">
							<volist name="pay_method" id="vo">
								
								<php>if($pay_offline || $key != 'offline'){</php>
									<dd class="dd-padding">
										<label class="mt"><i class="bank-icon icon-{pigcms{$key}"></i><span class="pay-wrapper">{pigcms{$vo.name}<input type="radio" class="mt" value="{pigcms{$key}"  <if condition="$i eq 1">checked="checked"</if> name="pay_type"></span></label>
									</dd>
								<php>}</php>
							
							</volist>
						</dl>
					</div>
					
					<div style="background-color: #FFFFFF; height: 53px;position: fixed;bottom: 0;left: 0;right: 0;z-index: 900;-webkit-tap-highlight-color: rgba(0, 0, 0, 0);height: 49px;width: 100%;">
					<div id="need_pay_title" style="    position: absolute;margin-top: 18px;margin-left: 0.3rem;">
						还需在线支付 <div style="font-weight:bold;color:red;display: inline;">￥<div class="need-pay" style="display:inline;">
							</div>
						</div> 
						</div>
						<button type="button" onclick="bio_verify()" style="float: right;height: 100%;width: 50%;background-color: #06c1ae;color: #fff;border: none;">确认支付</button>
					</div>
				</div>
			</form>
		</div>
		
		<link href="{pigcms{$static_path}css/check.css" rel="stylesheet"/>
		<div id="pwd_bg" style="height: 921px;" style="display:block">

		</div>
		<div id="pwd_verify" class="pwd_verify" style="display:none" >
			<div class="pwd_menu" >
				<span class="cancle"><img src="{pigcms{$static_path}images/twice_cancel.png"></span><p>密码验证</p>
			</div>
				<input type="hidden" id="pwd_type" name="type" value="1">
			<div class="verify_pwd">
				<p class="tips"></p>
				<input type="password"  autocomplete="off"  id="pwd" placeholder="输入登录密码" name="pwd" value="">
				<a id="forget_pwd" href="{pigcms{:U('Login/forgetpwd')}"><p class="forget_pwd">忘记密码?</p></a>
			</div>
			<div class="verify_sms" style="display:none;">
				<span style="color:#5E5E5E;font-size: 12px;">验证码将发送您手机：</span><span id="verify_phone" style="color:#006600;font-size: 12px;"></span>
				<input type="text" name="sms_code" autocomplete="off"  placeholder="输入验证码" value="">
				<button onclick="sendsms(this)">发送短信</button>
				<p></p>
			</div>
			<div class="verify_button" id="verify"> 
				<p>验证</p>
			</div>
		</div>
	<!-- 加 -->
		<style type="text/css">
			.verify_jg{ display: inline-block; height: 27px; padding:50px 0; }
			.verify_l{ float: left; line-height: 27px; color: red }
			.verify_lspan{ font-size: 20px; float: left; }
			.verify_lspan1{margin: 0 5px; float: left;}
			.plus{ float: left; }
			.plus a{width: 30px;float: left;height: 25px;border: #e5e5e5 1px solid;text-align: center;color: #232326;font-size: 20px;line-height: 25px;font-family: "Arial";}
			.plus input{width: 80px;text-align: center;float: left;border: #e5e5e5 1px solid;border-left: none;border-right: none;height: 25px;font-size: 14px;border-radius: 0px;font-size: 16px;color: red;}
		</style>

		<div class="pwd_verify" style="display: none;" id="change_score">
			<div class="pwd_menu" >
				<span class="cancle"><img src="{pigcms{$static_path}images/twice_cancel.png"></span><p><php>if($config['open_extra_price']==1){</php>{pigcms{$config.extra_price_alias_name}<php>}else{</php>{pigcms{$config['score_name']}<php>}</php>修改</p>
			</div>
			<div class="verify_pwd">
				<p class="tips"></p>
				<div class="verify_jg">
					<div class="verify_l">
						<span class="verify_lspan">￥{pigcms{$score_deducte}</span>
						<span class="verify_lspan1">+</span>
					</div>
					<div class="plus">
						<a href="javascript:void(0)" class="jian">-</a>
							<input type="text" id="score_value" name="score_change" value="{pigcms{$score_can_use_count}" >
						<a href="javascript:void(0)" class="jia">+</a>
					</div>
				</div>
			</div>
			<div class="verify_button" id="score_sure" style="background-color: #06c1ae;color: #fff;"> 
				<p style="color: #fff;">确定</p>
			</div>
		</div>
		<!-- 积分通道弹层样式 -->
		
		<if condition="$config['open_score_discount'] eq 1 AND $now_merchant['user_discount_type'] eq 3 AND $order_info['discount_status'] eq true">
			<div class="mask">
				<div class="content">
					<div class="content_header">
						<p>选择优惠通道 <span>( 2选1且必选 )</span></p>
					</div>
					<div class="way_content parent">
						<p><i></i></p>
						<div class="way_right after">
							<p class="change <if condition="$_GET['discount_type'] eq 'coupon'">active</if> rg"><span></span></p>
							<h2>优惠通道</h2>
							<p class="font_style">此通道除了不可获取平台积分，其他优惠均可享受</p>
						</div>
					</div>
					<!--积分通道-->
					<div class="integral parent">
						<p><i></i></p>
						<div class="integral_right after">
							<p class="change  <if condition="$_GET['discount_type'] eq 'score'">active</if> rg "><span></span></p>
							<h2>积分通道</h2>
							<p class="font_style">此通道支付时只可获得平台积分，其他商家会员卡折扣，平台/商家优惠劵等优惠均不可享受</p>
						</div>
					</div>
				</div>
			</div>
		</if>
	<!-- 加 -->
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}js/bioauth_.js"></script>

		<script>
			if($('#balanceBox dd dl dd').size() == 0){
				$('#balanceBox').hide();
			}
			layer.closeAll();
			var showBuyBtn = true;
		</script>
		<script>
				
				//首次加载弹出
				<if condition="$config['open_score_discount'] eq 1 AND $now_merchant['user_discount_type'] eq 3 AND !isset($_GET['discount_type'])">
					$('.mask').show();
				</if>
				$('.parent').click(function(e){
					$('.mask').show();
				});
				reg_discount = /&discount_type=(score|coupon)/;
				//优惠
				$('.mask .way_content').click(function(e){
					$(this).find('.change').addClass('active').parents('.parent').siblings('.parent').find('.change').removeClass('active');
					$('.mask').hide();
					$('.parent1').show();
					$('.parent2').hide();
					if(reg_discount.test(window.location.href)){
						tmp_url = window.location.href;
						window.location.href =tmp_url.replace(reg_discount, "&discount_type=coupon");
					}else{						
						window.location.href = window.location.href+"&discount_type=coupon";
					}
					
				});
				//积分
				$('.mask .integral').click(function(e){
					$(this).find('.change').addClass('active').parents('.parent').siblings('.parent').find('.change').removeClass('active');
					$('.mask').hide();
					$('.parent1').hide();
					$('.parent2').show();
					if(reg_discount.test(window.location.href)){
						//去除多以的参数
						tmp_url = window.location.href;
						tmp_url = tmp_url.replace(reg_discount, "&discount_type=score");
						tmp_url = tmp_url.replace(/&coupon_type=(system|mer)/, '');
						tmp_url = tmp_url.replace(/&unsys_coupon=1/, '');
						tmp_url = tmp_url.replace(/&unmer_coupon=1/, '');
						window.location.href =tmp_url.replace(/&coupon_type=(system|mer)/,'');
					}else{						
						window.location.href = window.location.href+"&discount_type=score";
					}

				});
				
				
				
		</script>
		<if condition="$cheap_info['can_buy'] heq false">
			<script>layer.open({title:['提示：','background-color:#FF658E;color:#fff;'],content:'您必须关注公众号后才能购买本单！<br/>长按图片识别二维码关注：<br/><img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id={pigcms{$order_info['order_id']+2000000000}" style="width:230px;height:230px;"/>',shadeClose:false});$('button.mj-submit').remove();var showBuyBtn = false;</script>
		</if>
		<script>if(showBuyBtn){$('button.mj-submit').show();}</script>
		<php>$no_footer = true;</php>
		<include file="Public:footer"/>
{pigcms{$hideScript}

<?php } ?>
<script type="text/javascript">
	if(typeof wx != "undefined"){
		// wx.ready(function(){
			// if(window.__wxjs_environment === 'miniprogram'){
				// go_wxapp_pay();
			// }
		// });
		wx.miniProgram.getEnv(function(res) {
			if(res.miniprogram){
				go_wxapp_pay();
			}
		});
	}
	
	function go_wxapp_pay(){
		layer.open({
			content: '正在跳转至小程序支付页面，支付完成将跳转至个人中心。 '
			,btn: ['继续支付', '放弃支付']
			,shadeClose:false
			,yes: function(index){
				wx.miniProgram.navigateTo({url: '/pages/pay/index?order_id={pigcms{$order_info.order_id}&type={pigcms{$order_info.order_type}<if condition="$_GET['from'] eq 'otherWxapp'">&from=otherWxapp</if>'});
			}
			,no: function(index){
				history.go(-1);
			}
		});
		wx.miniProgram.navigateTo({url: '/pages/pay/index?order_id={pigcms{$order_info.order_id}&type={pigcms{$order_info.order_type}<if condition="$_GET['from'] eq 'otherWxapp'">&from=otherWxapp</if>'});
	}
</script>
</body>
</html>