<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title>{pigcms{$now_group.s_name} | {pigcms{$config.site_name}</title>
	<meta name="keywords" content="{pigcms{$now_group.merchant_name},{pigcms{$now_group.s_name},{pigcms{$config.site_name}" />
	<meta name="description" content="{pigcms{$now_group.intro}" />
	<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
	<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/buy-process.css" />
	<script type="text/javascript">
	 var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	</script>
	<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
	<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script src="{pigcms{$static_path}js/common.js"></script>
	<script src="{pigcms{$static_path}js/group_buy.js"></script>
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
	<script>var group_price={pigcms{$now_group.price};var finalprice={pigcms{$finalprice};<if condition="$user_session">var is_login=true;<else/>var is_login=false;var login_url="{pigcms{:U('Index/Login/frame_login')}";</if><if condition="$user_session['phone']">var has_phone=true;<else/>var has_phone=false;var phone_url="{pigcms{:U('Index/Login/frame_phone')}";</if>
	var extra_price = Number("{pigcms{$now_group.extra_pay_price}");
	var extra_price_name = "{pigcms{$config.extra_price_alias_name}";
	var open_extra_price = Number("{pigcms{$config.open_extra_price}");
	</script>
	
</head>
<body>
 <include file="Public:header_top"/>
	<div class="body pg-buy-process"> 
	
		<article>
			<div class="menu cf">
				<div class="menu_left hide">
					<div class="menu_left_top">全部分类</div>
					<div class="list">
						<ul>
							<volist name="all_category_list" id="vo" key="k">
								<li>
									<div class="li_top cf">
										<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
										<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
									</div>
									<if condition="$vo['cat_count'] gt 1">
										<div class="li_bottom">
											<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
												<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
											</volist>
										</div>
									</if>
								</li>
							</volist>
						</ul>
					</div>
				</div>
				<div class="menu_right cf">
					<div class="menu_right_top">
						<ul>
							<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
								<li class="ctur">
									<a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
								</li>
							</pigcms:slider>
						</ul>
					</div>
				</div>
			</div>
		</article>
		<article>
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
									<div class="progress-bar" style="width:33.33%"></div>
								</div>
							</div>
						</div>
						<form action="{pigcms{$config.site_url}/group/buy/{pigcms{$now_group.group_id}.html" method="post" id="deal-buy-form" class="common-form J-wwwtracker-form">
							<div class="mainbox cf">
								<div class="table-section summary-table">
									<table cellspacing="0" class="buy-table" id="menu_list">
										<tr class="order-table-head-row">
											<th class="desc">名称</th>
											<th class="unit-price">单价</th>
											<th class="amount">数量</th>
											<th class="col-total">总价</th>
										</tr> 
										<tr>
											<td class="desc">
												<a href="{pigcms{$now_group.url}" target="_blank">
													{pigcms{$now_group.merchant_name}：{pigcms{$now_group.group_name}
												</a>
											</td>
											<td class="money J-deal-buy-price">¥<span id="deal-buy-price">{pigcms{$now_group.price}<if condition="$now_group.extra_pay_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$now_group.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if></span></td>
											<td class="deal-component-quantity ">
												<button for="J-cart-minus" class="minus" data-action="-" type="button">-</button><input type="text" autocomplete="off" class="f-text J-quantity J-cart-quantity" maxlength="9" name="q" data-max="{pigcms{$now_group.once_max}" data-min="{pigcms{$now_group.once_min}" value="{pigcms{$num}"/><button for="J-cart-add" class="item plus" data-action="+" type="button">+</button>
											</td>
											<td class="money total rightpadding col-total">¥<span id="J-deal-buy-total">{pigcms{$total_price}<if condition="$now_group.extra_pay_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$now_group.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if></span></td>
										</tr>
										<if condition="!empty($leveloff)">
										<tr>
											<td class="desc" colspan="4">
											  <span>
											  您的会员等级是：<strong style="font-size:16px;color:#FF4907">{pigcms{$leveloff['lname']}</strong>
											  </span>
											  <span style="margin-left:450px;">{pigcms{$leveloff['offstr']}&nbsp;&nbsp;优惠后单价为 <strong style="font-size:16px;color:#FF4907">¥{pigcms{$leveloff['price']}</strong>
											  </span>
											  <span class="total-fee" style="left:320px"><strong style="font-size:16px;color:#FF4907">¥<span id="levelofftotal">{pigcms{$total_off_price}</span></strong></span>
											</td>
										 </tr>
										</if>
										
									<if condition="$now_group['tuan_type']==2">
										<tr>
											<td class="desc" colspan="3">
											 运费
											</td>
											<td class="col-total express_fee"  >
												{pigcms{$now_group['express_fee']|floatval}元<if condition="$now_group['express_template']['full_money'] gt 0">(满{pigcms{$now_group['express_template']['full_money']|floatval}包邮) </if>
											</td>
										 </tr>
										</if>
										<tr>
											<td></td>
											<td colspan="3" class="extra-fee total-fee rightpadding"><strong>
											应付金额</strong>：<span class="inline-block money">¥<strong id="deal-buy-total-t">{pigcms{$total_off_price}<if condition="$now_group.extra_pay_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$now_group.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if></strong></span>
											</td>
										</tr>
									</table>
								</div>
								
								<input id="J-deal-buy-cardcode" type="hidden" name="cardcode" maxlength="8" value=""/>	
								
	
							
								<if condition="$user_session">
									<if condition="$now_group['tuan_type'] eq 2">
											<php>if($now_group['pick_in_store']){</php><input type="checkbox" name="pick_in_store" id="pick_in_store" > 到店自提<php>}</php>
										<div id="deal-buy-delivery" class="blk-item delivery J-deal-buy-delivery">
											<h3 id="package">收货地址<span><a target="_blank" href="{pigcms{:U('User/Adress/index')}">管理</a></span></h3>
											<h3 id="pick_addr" style="display:none">自提点地址</h3>
											<div id="adress_frame_div">
												<iframe name="uploadFrame" src="{pigcms{:U('Index/Adress/frame')}"></iframe>
											</div>
											<input id="pick-address" type="hidden" name="pick_address" value=""/>
											<input id="buy-adress-id" type="hidden" name="adress_id" value=""/>
											<input id="pick_lng" type="hidden" name="pick_lng" value=""/>
											<input id="pick_lat" type="hidden" name="pick_lat" value=""/>
											<hr/>
											<h4>希望送货的时间</h4>
											<ul class="delivery-type">
												<li>
													<input checked="checked" id="delivery_type-1" value="1" type="radio" name="delivery_type" class="select-radio"/>
													<label for="delivery_type-1">工作日、双休日与假日均可送货</label>
												</li>
												<li>
													<input id="delivery_type-2" value="2" type="radio" name="delivery_type" class="select-radio"/>
													<label for="delivery_type-2">只工作日送货(双休日、假日不用送，写字楼/商用地址客户请选择)</label>
												</li>
												<li>
													<input id="delivery_type-3" value="3" type="radio" name="delivery_type" class="select-radio"/>
													<label for="delivery_type-3">只双休日、假日送货(工作日不用送)</label>
												</li>
												<li>
													<input id="delivery_type-4" value="4" type="radio" name="delivery_type" class="select-radio"/>
													<label for="delivery_type-4">白天没人，其它时间送货 (特别安排可能会超出预计送货天数)</label>
												</li>
											</ul>
											<hr/>
											<h4>配送说明<span>（快递公司由商家根据情况选择，请不要指定。其他要求配送公司会尽量协调）</span></h4>
											<input class="f-text comment" type="text" id="delivery_comment" name="delivery_comment" />
										</div>
									</if>
								</if>
								<if condition="$user_session['phone']">
									<div class="blk-mobile">
										<p>您绑定的手机号码：<span class="mobile" style="color:#EE3968;">{pigcms{$pigcms_phone}</span></p>
									</div>  
								</if>									
								<div class="form-submit shopping-cart">
									<input type="submit" class="clear-cart btn btn-large btn-buy" id="confirmOrder" value="提交订单" />
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</article>
	</div>
	
	<script>
	var full_money = Number("{pigcms{$now_group.express_template.full_money}");
	var total_money = 0;
	var express_fee_tmp = Number("{pigcms{$now_group.express_fee}");
	var express_fee = Number("{pigcms{$now_group.express_fee}");
	$(document).ready(function() {
		
		 $("#pick_in_store").bind("click", function () {
			if($("#pick_in_store").is(':checked')==true){
		
				$('#pick_addr').css('display','block');
	
				$('#package').css('display','none');
				$('#buy-adress-id').nextAll().css('display','none');
				$('#adress_frame_div').html('<iframe src="{pigcms{:U('Index/Adress/pick_address',array('mer_id'=>$now_group['mer_id'],'group_id'=>$now_group['group_id']))}"></iframe>');
			}else{
				$('#pick_addr').css('display','none');
	
				$('#package').css('display','block');
				$('#adress_frame_div').html('<iframe src="{pigcms{:U('Index/Adress/frame')}"></iframe>');
				$('#buy-adress-id').nextAll().css('display','block');
		
			}
		});

		
	});
	
	window.onload = function() { 
	if($("#pick_in_store").is(':checked')==true){
			$('#pick_addr').css('display','block');
			$('#package').css('display','none');
			$('#buy-adress-id').nextAll().css('display','none');

			$('#adress_frame_div').html('<iframe src="{pigcms{:U('Index/Adress/pick_address',array('mer_id'=>$now_group['mer_id'],'group_id'=>$now_group['group_id']))}"></iframe>');
		}
	
	}
	
		
	var iframeWin = window.frames['uploadFrame'];
	$(iframeWin).load(function () {
		$(iframeWin.document).find('.select-radio').change(function () {
			var address_id=$(this).val();
			$.post("{pigcms{:U('get_express_fee')}",{group_id:'{pigcms{$now_group['group_id']}',address_id:address_id,price:total_money},function(data){
				console.log(data)
				if(!data.error_code){
					express_fee = Number(data.express_fee);
					console.log(express_fee)
					$('#deal-buy-total-t,#levelofftotal').html((total_money+express_fee)+'元');
				}
			},'json')
		});
	});
		
	
	</script>
	<script>
		
	function change_adress_frame(frame_height){
		$('#adress_frame_div').height(frame_height).find('iframe').css({'opacity':'1','filter':'alpha(opacity=100)'});
	}
	function change_adress(adress_id,username,phone,province_txt,city_txt,area_txt,zipcode){
		$('#buy-adress-id').val(adress_id);
	}
	
	function change_pick_adress(adress_id,pick_name,phone,province,city,area,lng,lat){
		$('#pick-address').val(province+' '+city+' '+area+' '+pick_name+' ,自提点电话：'+phone );
		$('#pick_lng').val(lng)
		$('#pick_lat').val(lat)
	}
	</script>
	<include file="Public:footer"/>
</body>
</html>
