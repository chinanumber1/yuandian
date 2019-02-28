<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>付款确认 - {pigcms{$config.seo_title}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
<script type="text/javascript">
        var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
        var  score_count = Number("{pigcms{$score_count}");
        var  now_money = Number("{pigcms{$now_user.now_money}");
		var  extra_price =Number("{pigcms{$order_info.extra_price}");
        var  open_extra_price =Number("{pigcms{$config.open_extra_price}");
        var  extra_price_name ="{pigcms{$config.extra_price_alias_name}";
        var  order_extra_price =Number("{pigcms{$order_info.order_extra_price}");
        var  score_percent = Number("{pigcms{$user_score_use_percent}");
        var  score_deducte = Number("{pigcms{$score_deducte}");
        var  score_can_use_count = Number("{pigcms{$score_can_use_count}");
        var  total_money = Number("{pigcms{$order_info.order_total_money}");
		total_money+=order_extra_price;
        var  need_pay = total_money;
        $(document).ready(function(){
			//判断是否勾选了余额
			if($("#use_balance").is(':checked')==true){
				if($("#use_score").is(':checked')==true){
					need_pay_tmp = total_money-score_deducte;
				}
				if(now_money>need_pay){
					$('.imgradio').find('input').removeAttr('checked');
					need_pay_tmp=0;
					$('#need-pay').css('display', 'block');
					$('.need_pay').html(need_pay_tmp.toFixed(2));
					
				}else{					
					need_pay_tmp = total_money-now_money;
					$('#pay_bank_list').css('display', 'block');
					$('#need-pay').css('display', 'block');
					$('.need_pay').html(need_pay_tmp.toFixed(2));
				}
			}else{
				if($("#use_score").is(':checked')==true){
					need_pay_tmp = total_money-score_deducte;
				}else{
					need_pay_tmp = total_money;
				}
				if(need_pay_tmp>0){
					$('#pay_bank_list').css('display', 'block');
					$('#need-pay').css('display', 'block');
					$('.need_pay').html(need_pay_tmp.toFixed(2));
				}
			}
			
		//是否勾选了
			if($("#use_score").is(':checked')==true){
				if($("#use_balance").is(':checked')==true){
					need_pay_tmp=total_money-score_deducte-now_money;
				}else{
					need_pay_tmp=total_money-score_deducte;
				}
	
				if(need_pay_tmp<=0){
					$('.imgradio').find('input').removeAttr('checked');
					$('#need-pay').css('display', 'block');
					$('.need_pay').html('0.00');
					$('#pay_bank_list').css('display', 'none');
				}else{
				
					$('#pay_bank_list').css('display', 'block');
					$('#need-pay').css('display', 'block');
					$('.need_pay').html(need_pay_tmp.toFixed(2));
				}
			}else{
				if($("#use_balance").is(':checked')==true){
					need_pay_tmp=total_money-now_money;
				}else{
					need_pay_tmp=total_money;
				}
				
				if(need_pay_tmp<=0){
					$('.need_pay').html('0.00');
					$('#pay_bank_list').css('display', 'none');
				}else{
					
					$('#pay_bank_list').css('display', 'block');
					$('#need-pay').css('display', 'block');
					$('.need_pay').html(need_pay_tmp.toFixed(2));
				}
			}
			
			//监听勾选
        
			$("#use_score").bind("click", function () {
				
				if($("#use_score").is(':checked')==true){
					console.log(score_deducte)
					if(total_money-score_deducte<=0){
						need_pay_tmp=0;
						$('.imgradio').find('input').removeAttr('checked');
						$('#use_balance').removeAttr('checked');
						$('#use_balance').attr('disabled','disabled');
						$('#pay_bank_list').css('display', 'none');
					}else{
						if($("#use_balance").is(':checked')==true){
							need_pay_tmp = total_money-now_money-score_deducte;
							
							if(need_pay_tmp<0){
								need_pay_tmp=0;
								$('.imgradio').find('input').removeAttr('checked');
								$('#pay_bank_list').css('display', 'none');
							}
						}else{
							
							need_pay_tmp=total_money-score_deducte;
						}
						
					}
					
					$('.need_pay').html(need_pay_tmp.toFixed(2));
					$("input[name='use_score']").attr('value',1);
					
				}else if($("#use_score").is(':checked')==false){  
					if($("#use_balance").is(':checked')==true){
						need_pay_tmp = total_money-now_money;
						if(need_pay_tmp<0){
							need_pay_tmp=0;
							$('.imgradio').find('input').removeAttr('checked');
							$('#pay_bank_list').css('display', 'none');
						}
					}else{
						need_pay_tmp = total_money;
					}             
					if(need_pay_tmp>0){
							$('.imgradio').find('input:first').attr('checked','checked');
						if(now_money!=0){							
							$('#use_balance').removeAttr('disabled');
						}
						$('#pay_bank_list').css('display', 'block');
					}
				
					$('.need_pay').html(need_pay_tmp.toFixed(2));
					$("input[name='use_score']").attr('value',0);
				}
			});
            
			//监听勾选余额
			$('#use_balance').bind("click", function () {
				if($("#use_score").is(':checked')==true){
					need_pay_tmp =total_money-score_deducte;
				}else{
					need_pay_tmp =total_money;
				}
				
				if($("#use_balance").is(':checked')==true){
						
					if(now_money>need_pay_tmp){
						$('.imgradio').find('input').removeAttr('checked');
						$('#pay_bank_list').css('display', 'none');
						$('.need_pay').html('0.00');
					}else{
						var need_pay_tmp = need_pay_tmp-now_money;
						$('#pay_bank_list').css('display', 'block');
						$('.need_pay').html(need_pay_tmp.toFixed(2));
					}
					$("input[name='use_balance']").attr('value',1);
				}else if($("#use_balance").is(':checked')==false){
				
					if(need_pay_tmp>0){
						$('.imgradio').find('input:first').attr('checked','checked');
						$('#pay_bank_list').css('display', 'block');
						$('#need-pay').css('display', 'block');
						$('.need_pay').html(need_pay_tmp.toFixed(2));
					}else{
						$('#need-pay').css('display', 'block');
						$('.need_pay').html('0.00');
					}
					$("input[name='use_balance']").attr('value',0);
				}
			});
			
			
			var score_money_sure = 0;
			$('#plus').click(function(){
				var score_change = $('#score_change').val();
				if(score_can_use_count>=(Number(score_change)+1)){
					var score_counts = Number(score_change)+1;
				}else{
					var score_counts = score_can_use_count;
				}
				$('#score_change').val(score_counts.toFixed(2));
				score_deducte = score_money_sure;
				score_money_sure = (score_counts/score_percent).toFixed(2);
				$('#use_score').removeAttr('checked');
				$('#score_deducte_t').html('￥'+score_money_sure);
				$('#score_deducte').val(score_money_sure);
				
				if($("#use_balance").is(':checked')==true){
					need_pay_tmp=total_money-score_deducte-now_money;
				}else{
					need_pay_tmp=total_money-score_deducte;
				}
			});
			$('#minus').click(function(){
	
				var score_change = $('#score_change').val();
				if(Number(score_change)-1>=0){
					var score_counts = Number(score_change)-1;
				}else{
					var score_counts = 0;
				}
				$('#score_change').val(score_counts.toFixed(2));
				score_money_sure = (score_counts/score_percent).toFixed(2);
				score_deducte = score_money_sure;
				$('#use_score').removeAttr('checked');
				$('#score_deducte_t').html('￥'+score_money_sure);
				$('#score_deducte').val(score_money_sure);
				
				if($("#use_balance").is(':checked')==true){
					need_pay_tmp=total_money-score_deducte-now_money;
				}else{
					need_pay_tmp=total_money-score_deducte;
				}
			});
			
		
			
			$('#score_change').blur(function(){
				if(isNaN($(this).val())||Number($(this).val())<0){
					alert('非法输入');
					window.location.reload();
				}
				if(Number($(this).val())>score_can_use_count){
					alert('最多使用'+score_can_use_count+'个'+extra_price_name);
					$('#score_change').val(score_can_use_count);
				}else{
					var changed_score = $(this).val();
					score_money_sure = (changed_score/score_percent).toFixed(2);
					score_deducte = score_money_sure;
					$('.verify_lspan').html('￥'+score_money_sure);
					$('#score_change').val(Number(changed_score).toFixed(2));
					$('#score_deducte_t').html('￥'+score_money_sure);
					$('#score_deducte').val(score_money_sure);
					
					
				}
				$('#use_score').removeAttr('checked');
				$('.need_pay').text(total_money);
			});
			
        });
          
           
            
</script>
<script src="{pigcms{$static_path}js/common.js"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/buy-process.css" />

<!--[if IE 6]>
<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">
   /* EXAMPLE */
   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');

   /* string argument can be any CSS selector */
   /* .png_bg example is unnecessary */
   /* change it to what suits you! */
</script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css"> 
		body{behavior:url("{pigcms{$static_path}css/csshover.htc"); 
		}
		.category_list li:hover .bmbox {
filter:alpha(opacity=50);
	 
			}
  .gd_box{	display: none;}
</style>
<![endif]-->
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
	<style>
		.bank_logo {background: url({pigcms{$static_path}images/bank_logos.png) left top no-repeat;}
		.payment-bank {
			margin-top: 10px;
			border: 1px solid #DFDFDF;
			padding: 5px 0 10px 20px;
			background-color: #F5F5F5;
		}
		.payment-banktit {
			height: 20px;
			line-height: 20px;
			margin-top: 5px;
			padding: 5px 0;
			font-family: \5b8b\4f53;
			cursor: pointer;
		}
		.payment-banktit b {
			display: inline-block;
			height: 20px;
			padding-left: 17px;
			color: #333;
			font-size: 14px;
		}
		.payment-bankcen {
			padding-top: 10px;
		}
		.bank {
			width: 786px;
			padding: 15px 0 0 20px;
		}
		.payment-bankcen .bank{
			padding-top: 0;
			width: 1210px;
		}
		.imgradio li {
			padding-left: 20px;
			width: 112px;
			height: 32px;
			float: left;
			position: relative;
			margin: 0 25px 15px 0;
			_display: inline;
			_zomm: 1;
		}
		.imgradio li input {
			position: absolute;
			left: 0;
			top: 10px;
		}
		.imgradio li label{
			cursor:pointer;
		}
		.payment-bankcen .bank .imgradio li {
			margin-right: 45px;
		}
		.clr {
			height: 0;
			font-size: 0;
			line-height: 0;
			clear: both;
			overflow: hidden;
		}
		.form-submit {
			margin: 30px 0 20px;
		}
		
		
#bd {
  width: 1210px;
  margin: 0 auto;
  padding: 10px 0 65px;
    border-top: 3px solid #fe5842;
  margin-top:20px;
}

#content {
  float: left;
  width: 1210px;
  _display: inline;
  padding: 0;
}
.cf {
  zoom: 1;
}		
		
.sysmsgw {
  width: 1150px;
  margin: 10px auto 0;
}
		
.common-tip {
  position: relative;
  margin-bottom: 10px;
  padding: 10px 30px;
  border: 1px #F5D8A7 solid;
  border-radius: 2px;
  background: #FFF6DB;
  font-size: 14px;
  text-align: center;
  color: #666;
  zoom: 1;
}
		
a.see_tmp_qrcode {
  color: #EE3968;
  text-decoration: none;
}
.mainbox {
  border: none;
  padding: 0;
  padding-bottom: 60px;
}	
		
		
	</style>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/up.card.css" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/bank_icon.css" />
</head>
<body id="deal-buy" class="pg-buy pg-buy-process">
	<include file="Public:header_top"/>
	<div id="doc" class="bg-for-new-index">
		<div class="sysmsgw common-tip" id="sysmsg-error" style="display:none;"></div>
		<div id="bdw" class="bdw" style="min-height:700px;">
    		<div id="bd" class="cf">
			    <div id="content">
			    	<div>
			    		<div class="buy-process-bar-container">
						    <ol class="buy-process-desc steps-desc">
						        <li class="step step--current">
						            1. 提交订单
						        </li>
						        <li class="step">
						            2. 选择支付方式
						        </li>
						        <li class="step">
						            3. 购买成功
						        </li>
						    </ol>
						    <div class="progress">
						        <div class="progress-bar" style="width:66.66%"></div>
						    </div>
						</div>
			    	</div>
					<if condition="$order_info['order_type'] != 'recharge'">
						<div class="sysmsgw common-tip" style="margin-bottom:20px;" id="sysmsg-error">					
							<div class="sysmsg">							
								<span class="J-msg-content"><span class="J-tip-status tip-status"></span>在微信中付款才可以使用商家优惠券和商家会员卡。&nbsp;
								<if condition="$order_info['order_type'] eq 'group'">
								<a class="see_tmp_qrcode" href="{pigcms{:U('Index/Recognition/see_tmp_qrcode',array('qrcode_id'=>2000000000+$order_info['order_id']))}">查看订单微信二维码</a>
								<elseif condition="$order_info['order_type'] eq 'shop'" />
								<a class="see_tmp_qrcode" href="{pigcms{:U('Index/Recognition/see_tmp_qrcode',array('qrcode_id'=>3500000000+$order_info['order_id']))}" target="_blank">查看订单微信二维码</a>
								<else/>
								<a class="see_tmp_qrcode" href="{pigcms{:U('Index/Recognition/see_tmp_qrcode',array('qrcode_id'=>3000000000+$order_info['order_id']))}" target="_blank">查看订单微信二维码</a>
								</if>
								</span>
								<span class="close common-close">关闭</span>
							</div>					
						</div>
					</if>
			        <form action="{pigcms{:U('Pay/go_pay')}" method="post" id="deal-buy-form" class="common-form J-wwwtracker-form">
			            <div class="mainbox cf" style="min-height:0px;">
			            	<div class="table-section summary-table">
			                    <table cellspacing="0" class="buy-table">
			                        <tr class="order-table-head-row">
			                        	<th class="desc">项目</th>
			                        	<th class="unit-price">单价</th>
                                                        <th class="amount">数量</th>
                                                        <th class="col-total">总价</th>
			                    	</tr>
				                    <volist name="order_info['order_content']" id="vo">
				                        <tr>
					                        <td class="desc">{pigcms{$vo.name}</td>
					                        <td class="money J-deal-buy-price">
					                            ￥<span id="deal-buy-price">{pigcms{$vo.price}<if condition="$config.open_extra_price eq 1 AND $vo.extra_price gt 0">+{pigcms{$vo.extra_price}{pigcms{$config.extra_price_alias_name}</if></span>
					                        </td>
					                        <td class="deal-component-quantity ">{pigcms{$vo.num}</td>
					                        <td class="money total rightpadding col-total">
												￥<span id="J-deal-buy-total">{pigcms{$vo.money}<if condition="$config.open_extra_price eq 1 AND $vo.extra_price gt 0">+{pigcms{$vo['extra_price']*$vo['num']}{pigcms{$config.extra_price_alias_name}</if></span>
											</td>
					                    </tr>
				                    </volist>
			                        <tr>
										<td>
			                        	<if condition="!empty($leveloff) AND is_array($leveloff)">
											<span style="float: right;">会员等级<strong style="color:#EA4F01;">{pigcms{$leveloff['lname']}</strong> &nbsp;{pigcms{$leveloff['offstr']}</span>   
										</if>							
										</td>
				                        <td colspan="3" class="extra-fee total-fee rightpadding">
											<strong><if condition="!empty($leveloff) AND is_array($leveloff)">优惠后</if>订单总额</strong>：
				                            <span class="inline-block money">
				                                ￥<strong id="deal-buy-total-t">{pigcms{$order_info.order_total_money}<if condition="$config.open_extra_price eq 1 AND $order_info.extra_price gt 0">+{pigcms{$order_info.extra_price}{pigcms{$config.extra_price_alias_name}</if></strong>
				                            </span>
				                        </td>
			                    	</tr>
			                    	<if condition="$score_count gt 0">
										<tr>
											<td style="text-align:left;"  class="deal-component-quantity ">
												<strong>帐户可用{pigcms{$config.score_name}</strong>：
												<span class="inline-block money" style="color:#EA4F01;">
													<strong class="deal-buy-total-t">{pigcms{$now_user.score_count}</strong>
													<input type="hidden" name="score_count" value="{pigcms{$now_user.score_count}">
												</span>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<strong>本单可用{pigcms{$config.score_name}</strong>：
												<button for="J-cart-minus" class="minus" id="minus" data-action="-" type="button">-</button><input type="text"  name="score_used_count"  autocomplete="off" class="f-text J-quantity J-cart-quantity" maxlength="9" name="q" data-max="{pigcms{$score_can_use_count}" data-min="0"  id="score_change" value="{pigcms{$score_can_use_count}"/><button for="J-cart-add" class="item plus" data-action="+" type="button" id="plus">+</button>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<strong>{pigcms{$config.score_name}可抵扣现金</strong>：
												<span class="inline-block money" style="color:#EA4F01;">
														<strong id="score_deducte_t">￥{pigcms{$score_deducte|floatval=###}</strong>
														<input type="hidden" id="score_deducte" name="score_deducte" value="{pigcms{$score_deducte}">
												</span>
												
												
											</td>
										
										
											<td colspan="3" class="extra-fee total-fee rightpadding">
												使用{pigcms{$config.score_name}抵扣:<input type="checkbox" id ="use_score" name="use_score" value="1" <if condition="($score_checkbox eq 1) || (!empty($_GET['type']) && ($_GET['type'] == 'gift'))"> checked="checked" </if><if condition="($score_can_use_count eq 0) ||(($_GET['type'] == 'gift')) "> disabled="disabled" </if>>
											</td>
										</tr>
									</if>
								<if condition="$order_info['order_type'] != 'recharge'">
									<tr>						
										<td style="text-align:left;">
											<php>if(!isset($balance_disable)){</php>
												<input type="checkbox" class="mt"  id="use_balance" name="use_balance"<if condition="$now_user['now_money'] eq 0 OR $merchant_balance gt $order_info['order_total_money'] ">disabled="disabled" value="1"<else /> value="1" checked="checked" </if>/>
											<php>}</php>
											<strong>可用余额</strong>：
											<span class="inline-block money" style="color:#EA4F01;">
												￥<strong id="deal-buy-total-t">{pigcms{$now_user.now_money}</strong>
											</span>									
										</td>
									</tr>
								</if>
									 
			                	</table>
			            	</div>
			            </div>
					
						<if condition="$order_info['order_type'] != 'recharge'">
							<div id="need-pay" >
								<strong>还需支付</strong>：
								<span class="inline-block money" style="font-size:20px;color:#EA4F01;">
									￥<strong id="deal-buy-total-t" class="need_pay"><if condition="$pay_money lt 0">0.00<else />{pigcms{$pay_money}</if></strong>
								</span>
							</div>
						</if>
						
						<div id="pay_bank_list" style="display:none;">
							<div class="payment-bank">
								<div class="payment-banktit">
									<b class="open">选择支付方式</b>
								</div>	
								<div class="payment-bankcen">
									<div class="bank morebank">
										<ul class="imgradio">
											<volist name="pay_method" id="vo">
												<php>if($pay_offline || $key != 'offline'){</php>
												<li>
													<label>
														<input type="radio" name="pay_type" value="{pigcms{$key}" <if condition="$i eq 1">checked="checked"</if>>
														<img src="{pigcms{$static_public}images/pay/{pigcms{$key}.gif" width="112" height="32" alt="{pigcms{$vo.name}" title="{pigcms{$vo.name}"/>
													</label>
												</li>
												<php>}</php>
											</volist>
										</ul>
										<div class="clr"></div>
									</div>
								</div>
								<div class="clr"></div>
							</div>
						</div>
						
						<div class="tab_content" id="bankCode" style="display:none">
							<div class="content_main cg_card clearfix">
								<div class="ebank_important" id="eBanksDiv" style="">
									
									<ul class="ebank_logo dn" id="allBanksLogo" style="display: block;">
										<volist name="bankCode" id="vo">
											<li>
												<input type="radio" name="bankCode" data-bankcode="01" id="{pigcms{:strtoupper($key)}" value="{pigcms{$key}">
												<span class="bank_logo bank_{pigcms{$key}">
												</span>
											</li>
										</volist>
										<li>
											<input type="radio" name="bankCode" data-bankcode="egb" id="EGB" value="egb">
											<span class="bank_logo " style="background:url({pigcms{$static_path}images/hf_bank_logo.jpg) no-repeat; background-size: 100% 84%;"></span>
										</li>
									
										
									</ul>
									<div class="clear">
									</div>
								
									<div id="bankInfoes" class="pay_info">
									</div>
								
								
								</div>
							</div>
						</div>
						
						<div class="form-submit">
							<input type="hidden" name="order_id" value="{pigcms{$order_info.order_id}"/>
				    		<input type="hidden" name="order_type" value="{pigcms{$order_info.order_type}"/>
				    		<input type="hidden" id="bank_code" name="bank_code" value=""/>
			                <input id="J-order-pay-button" type="submit" class="btn btn-large btn-pay" name="commit" value="去付款"/><br/>
			            </div>
			    	</form>
				</div>
    		</div>
    		<!-- bd end -->
		</div>
	</div>

	<script type="text/javascript">
		var orderid = 0;
		var confirm_pay_url = '';
		$(function(){
			$("form").submit(function() {
			   $("#J-order-pay-button").val("正在处理...");
			   $("#J-order-pay-button").attr("disabled", "disabled");
			});
			$('#sysmsg-error .close').click(function(){
				$('#sysmsg-error').remove();
			});
			
			$('input[name="pay_type"]').click(function(){
				if($(this).val()  == 'allinyun_gateway' ){
					art.dialog({
						title: '请选择支付银行',
						id: 'weixin_pay_qrcode',
						width:'800px',
						opacity:'0.4',
						lock:true,
						fixed: true,
						resize: false,
						content: $('#bankCode').html(),
						okVal:'去支付',
						ok:function(){
							
							$('#deal-buy-form').submit();
						},
						cancel: function(){
							$('input[name="pay_type"]').attr('checked',false);
						},
					});
					$('.bank_logo').click(function(){
						$('input[name="bankCode"]').attr('checked',false);
						$(this).siblings('input[name="bankCode"]').attr('checked',true);
						$('#bank_code').val($(this).siblings('input[name="bankCode"]').val())
					});
				}
			})
			
			
			
			$('.see_tmp_qrcode').click(function(){
				var qrcode_href = $(this).attr('href');
				art.dialog.open(qrcode_href+"&"+Math.random(),{
					init: function(){
						var iframe = this.iframe.contentWindow;
						window.top.art.dialog.data('login_iframe_handle',iframe);
					},
					id: 'login_handle',
					title:'请使用微信扫描二维码',
					padding: 0,
					width: 430,
					height: 433,
					lock: true,
					resize: false,
					background:'black',
					button: null,
					fixed: false,
					close: null,
					left: '50%',
					top: '38.2%',
					opacity:'0.4'
				});
				return false;
			});
			$('#deal-buy-form').submit(function(){	
		
				if($('input[name="pay_type"]:checked').val() == 'weixin' || $('input[name="pay_type"]:checked').val() == 'weifutong' ){
					art.dialog({
						title: '提示信息',
						id: 'weixin_pay_tip',
						opacity:'0.4',
						lock:true,
						fixed: true,
						resize: false,
						content: '正在获取微信支付相关信息，请稍等...'
					});
					$.post($('#deal-buy-form').attr('action'),$('#deal-buy-form').serialize(),function(result){
						art.dialog.list['weixin_pay_tip'].close();			
						if(result.status == 1){
							orderid = result.orderid;
							art.dialog({
								title: '请使用微信扫码支付',
								id: 'weixin_pay_qrcode',
								width:'350px',
								opacity:'0.4',
								lock:true,
								fixed: true,
								resize: false,
								content: '<p style="margin-top:20px;margin-bottom:20px;text-align:center;font-size:16px;color:black;">请使用微信扫描二维码进行支付</p><p style="text-align:center;"><img src="{pigcms{:U('Recognition/get_own_qrcode')}&qrCon='+result.info+'" style="width:240px;height:240px;"></p><p style="text-align:center;margin-top:20px;margin-bottom:20px;"><input id="J-order-weixin-button" type="button" class="btn btn-large btn-pay" value="已支付完成"/></p>',
								cancel: function(){
									$("#J-order-pay-button").val("去付款");
									$("#J-order-pay-button").removeAttr("disabled");
								},
							});
						}else{
							$("#J-order-pay-button").val("去付款");
							$("#J-order-pay-button").removeAttr("disabled");
							art.dialog({
								title: '错误提示：',
								id: 'weixin_pay_error',
								opacity:'0.4',
								lock:true,
								fixed: true,
								resize: false,
								content: result.info
							});
							
						}
					});
					return false;
				}else if($('input[name="pay_type"]:checked').val() == 'scan_alipay' ){
					
					art.dialog({
						title: '提示信息',
						id: 'weixin_pay_tip',
						opacity:'0.4',
						lock:true,
						fixed: true,
						resize: false,
						content: '正在获取支付宝支付相关信息，请稍等...'
					});
					$.post($('#deal-buy-form').attr('action'),$('#deal-buy-form').serialize(),function(result){
						art.dialog.list['weixin_pay_tip'].close();			
						if(result.status == 1){
							orderid = result.orderid;
							confirm_pay_url = result.confirm_pay_url
							art.dialog({
								title: '请使用支付宝扫码支付',
								id: 'weixin_pay_qrcode',
								width:'350px',
								opacity:'0.4',
								lock:true,
								fixed: true,
								resize: false,
								content: '<p style="margin-top:20px;margin-bottom:20px;text-align:center;font-size:16px;color:black;">请使用支付宝扫描二维码进行支付</p><p style="text-align:center;"><img src="{pigcms{:U('Recognition/get_own_qrcode')}&qrCon='+result.info+'" style="width:240px;height:240px;"></p><p style="text-align:center;margin-top:20px;margin-bottom:20px;"><input id="J-order-scan_alipay-button" type="button" class="btn btn-large btn-pay" value="已支付完成"/></p>',
								cancel: function(){
									$("#J-order-pay-button").val("去付款");
									$("#J-order-pay-button").removeAttr("disabled");
								},
							});
						}else{
							$("#J-order-pay-button").val("去付款");
							$("#J-order-pay-button").removeAttr("disabled");
							art.dialog({
								title: '错误提示：',
								id: 'scan_alipay_pay_error',
								opacity:'0.4',
								lock:true,
								fixed: true,
								resize: false,
								content: result.info
							});
							
						}
					});
					return false;
				}else if($('input[name="pay_type"]:checked').val() == 'allinyun_alipay' ){
					art.dialog({
						title: '提示信息',
						id: 'weixin_pay_tip',
						opacity:'0.4',
						lock:true,
						fixed: true,
						resize: false,
						content: '正在获取支付宝支付相关信息，请稍等...'
					});
					$.post($('#deal-buy-form').attr('action'),$('#deal-buy-form').serialize(),function(result){
						art.dialog.list['weixin_pay_tip'].close();			
						if(result.status == 1){
							orderid = result.orderid;
							art.dialog({
								title: '请使用支付宝扫码支付',
								id: 'weixin_pay_qrcode',
								width:'350px',
								opacity:'0.4',
								lock:true,
								fixed: true,
								resize: false,
								content: '<p style="margin-top:20px;margin-bottom:20px;text-align:center;font-size:16px;color:black;">请使用支付宝扫描二维码进行支付</p><p style="text-align:center;"><img src="{pigcms{:U('Recognition/get_own_qrcode')}&qrCon='+result.info+'" style="width:240px;height:240px;"></p><p style="text-align:center;margin-top:20px;margin-bottom:20px;"><input id="J-order-alipay-button" type="button" class="btn btn-large btn-pay" value="已支付完成"/></p>',
								cancel: function(){
									$("#J-order-pay-button").val("去付款");
									$("#J-order-pay-button").removeAttr("disabled");
								},
							});
						}else{
							$("#J-order-pay-button").val("去付款");
							$("#J-order-pay-button").removeAttr("disabled");
							art.dialog({
								title: '错误提示：',
								id: 'weixin_pay_error',
								opacity:'0.4',
								lock:true,
								fixed: true,
								resize: false,
								content: result.info
							});
							
						}
					});
					return false;
				}else if($('input[name="pay_type"]:checked').val() == 'allinyun_gateway' ){
					
					if($('input[name="bankCode"]:checked').length==1){
						return true;
					}else{
						art.dialog({
							title: '提示信息',
							id: 'gateway_pay_tip',
							opacity:'0.4',
							lock:true,
							fixed: true,
							resize: false,
							content: '请选择支付银行'
						});
						$("#J-order-pay-button").val("去付款");
							$("#J-order-pay-button").removeAttr("disabled");
						return false;
					}
				}
				
			});
			$('#J-order-weixin-button').live('click',function(){
				window.location.href="{pigcms{:U('Pay/weixin_back',array('order_type'=>$order_info['order_type']))}&order_id="+orderid+'&pay_type='+$('input[name="pay_type"]:checked').val();
			});
			$('#J-order-alipay-button').live('click',function(){
				window.location.href="{pigcms{:U('Deposit/allinyun_back',array('order_type'=>$order_info['order_type']))}&source=0&order_id={pigcms{$order_info['order_type']}_"+orderid+'&uid={pigcms{$now_user.uid}';
			});
			
			$('#J-order-scan_alipay-button').live('click',function(){
				window.location.href = confirm_pay_url;
			});
		});
	</script>
	<include file="Public:footer"/>
</body>
</html>
