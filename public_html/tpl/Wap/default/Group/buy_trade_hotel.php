<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>提交订单</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name='apple-touch-fullscreen' content='yes'/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta name="format-detection" content="telephone=no"/>
	<meta name="format-detection" content="address=no"/>
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
		
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/idangerous.swiper.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/trade_hotel.css?21111" rel="stylesheet"/>
</head>
<style>
	.discount_price{
		float:right;
		margin-left:10px;
		display:none;
	}
	.J_campaign-value{
		text-decoration: line-through;
	}
</style>
<body style="height:100%;width:100%">
	<div id="tips" class="tips"></div>
	<form id="buy-form" action="{pigcms{:U('Group/buy',array('group_id'=>$now_group['group_id']))}" method="POST" class="wrapper-list" autocomplete="off">
		<div id="hotel-info-box" style="display:none;">
			<div class="hotel-info detail-date datefixed">
				<div class="getin_room_a">
					<span>入住</span>
					<span class="ol_night">共1晚</span>
					<span>离店</span>
				</div>
				<div class="getin_room_b">
					<span><em class="indate" data-date="{pigcms{$trade_hotel.time_dep_time}">{pigcms{$trade_hotel.show_dep_time}</em><em class="startweek"></em></span>
					<span class="getin_fen">|</span>
					<span><em class="outdate" data-date="{pigcms{$trade_hotel.time_end_time}">{pigcms{$trade_hotel.show_end_time}</em><em class="endweek"></em></span>
				</div>
			</div>
			<div class="detail-main type">
				<ul></ul>
			</div>
			<input type="hidden" name="dep-time" id="dep-time" value="{pigcms{$trade_hotel.dep_time}"/>
			<input type="hidden" name="end-time" id="end-time" value="{pigcms{$trade_hotel.end_time}"/>
			<input type="hidden" name="cat-id" id="cat-id" value=""/>
		</div>
		<div id="buy_form_box" style="display:none;">
			<div class="p-info p-info-top">
				<p class="type pname"></p>				
				<p class="type name"></p>
				<p></p>
				<p class="date"></p>
			</div>
			<dl class="list">
				<dd>
					<dl>
						<if condition="!empty($leveloff) AND $finalprice gt 0">
							<dd class="dd-padding kv-line-r">
								<span>会员等级：<strong style="font-size:16px;color:#FF4907">{pigcms{$leveloff['lname']}</strong></span>
								<span style="position: absolute;right: 8px;top: 15px;">优惠单价 <strong style="font-size:16px;color:#FF4907">{pigcms{$leveloff['price']}</strong>元
								</span>
							</dd>
						</if>
					
						
						<dd class="dd-padding kv-line-r quantity">
							<h6>房间数：</h6>
							<div class="kv-v">
								<div class="stepper" data-com="stepper">
									<button type="button" class="btn btn-weak minus" disabled="disabled">-</button>&nbsp;<input class="mt number" type="tel" name="quantity" min="1" max="" value="1"/>&nbsp;<button type="button" class="btn btn-weak plus">+</button>
								</div>
							</div>
						</dd>
						<dd class="dd-padding kv-line-r discount" id="discount_room_price" style="display:none">
							<h6>订房优惠：</h6>
							<span class="kv-v" id="discount_room">
								<span class="J_discount-room"></span>
								<span class="J_discount-price"></span>
							</span>
						</dd>
						<dd class="dd-padding kv-line-r">
							<h6>总价：</h6>
							<span class="kv-v" id="amount">
								<span class="J_campaign-value"></span>
								<span class="J_total-price"></span>
							</span>
						</dd>
					</dl>
				</dd>
			</dl>
			<h4>买家留言</h4>
			<dl class="list">
				<dd class="dd-padding">
					<input type="text" class="input-weak" style="width:100%" placeholder="点击给商家留言" name="delivery_comment"/>
				</dd>
			</dl>
			<h4>价格清单</h4>
			<dl class="list price-list"></dl>
			<h4>您绑定的手机号码</h4>
			<dl class="list" id="mobile-show">
				<dd>
					<if condition="$user_session['phone']">
						<a id="change-mobile" class="react" href="javascript:void(0);">
							<div>{pigcms{$pigcms_phone}</div>
						</a>
					<elseif condition="$is_app_browser"/>
						<a id="change-mobile" class="react">
							<div>{pigcms{$pigcms_phone}</div>
						</a>
					<else/>
						<a id="change-mobile" class="react" href="{pigcms{:U('My/bind_user',array('referer'=>urlencode($_SERVER['REQUEST_URI'])))}">
							<div>{pigcms{$pigcms_phone}</div>
						</a>
					</if>
				</dd>
			</dl>
			<input type="hidden" name="group_type" value="{pigcms{$_GET['type']}" /> 
			<div class="btn-wrapper">
				<button type="submit" class="btn btn-block btn-strong btn-larger mj-submit" style="display:none;">提交订单</button>
			</div>
		</div>
		<div id="J_Calendar" class="calendar" style="display:none;">
			<ul class="calendar-title bar">
				<li>周日</li>
				<li>周一</li>
				<li>周二</li>
				<li>周三</li>
				<li>周四</li>
				<li>周五</li>
				<li>周六</li>
			</ul>
		</div>
	</form>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_path}js/common_wap.js"></script>
	<script src="{pigcms{$static_path}js/fastclick.js"></script>
	<script id="listHotelTpl" type="text/html">
		{{# var ii = 0; for(var i in d){ii++; }}
			<li class="rooms room-cat-{{ d[i].cat_id }} {{# if(!d[i].has_room && typeof(d[i].has_room)!='undefined'){ }}no{{# }else if(ii == 1){ }}on{{# } }} ">
				<div class="wrap">
					<div class="left rpDetail" data-cat_id="{{ d[i].cat_id }}">
						<div class="pic tjclick">
							<img src="{{ d[i].cat_pic_list[0].s_image }}"/>
						</div>
						<div class="picroom-info">
							<div class="room">{{ d[i].cat_name }}</div>
							<div class="room-info"><span>{{ d[i].room_size }}</span><span>{{ d[i].bed_info }}</span></div>
						</div>
					</div>
					<div class="right">
						<div class="price">
							{{# if(d[i].min_price){ }}
								<span>￥</span><span class="num">{{ d[i].min_price }}</span>起
							{{# }else{ }}
								<span>暂无售价</span>
							{{# } }}
						</div>
						<div class="icon"></div>
					</div>
					<div class="de-btn"><i></i></div>
					<div class="rigit_activebg"></div>
				</div>
				<div class="info-list" {{# if(i == 0){ }}style="display:block;"{{# } }}>
					<ul>
						{{#
						if(d[i].son_list){
							for(var j = 0, len = d[i].son_list.length; j < len; j++){
								var voo = d[i].son_list[j];
						}}
							<li class="roomdetail">
								<div class="left" data-cat_id="{{ voo.cat_id}}">
									<div class="bra clearfix">{{ voo.cat_name }}</div>
									<div class="xstm">
						<span class="f_c49f">{{ voo.refund_txt }}</span>
									</div>
								</div>
								<div class="value">
									<div class="price">
										{{# if(voo.price_txt){ }}
											￥<span>{{ voo.price_txt }}</span><br>
											{{# if(voo.discount_room>0 && voo.discount_price_txt>0 ){ }} <span style="font-size: 12px;color: #ccc;">满{{ voo.discount_room }}间享￥{{ voo.discount_price_txt }}单价</span> {{# } }}
										{{# }else{ }}
											暂无售价
										{{# } }}
									</div>
								</div>
								<div class="book">
									{{# if(voo.stock_num && voo.price_txt){ }}
										<div class="btn2 btn2_center" data-cat_id="{{ voo.cat_id }}" data-book_day="{{ voo.book_day }}"><span>订</span></div>
									{{# }else if(!voo.price_txt){ }}
										<div class="btn3 btn2_center">无</div>									
									{{# }else{ }}
										<div class="btn3 btn2_center">满</div>
									{{# } }}
								</div>
							</li>
						{{# 
							}
						}
						}}
					</ul>
				</div>
			</li>
		{{# } }}
	</script>
	<script>
		FastClick.attach(document.body);
		var price = 0;
		var wx_cheap = {pigcms{$now_group['wx_cheap']*100};
		var finalprice = {pigcms{$finalprice};
		var discount_price_all  =0;
		var discount_room  = 0;
		
		
	</script>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
	<script>var showBuyBtn = true;</script>
    <if condition="!$is_app_browser">
	    <if condition="$_SESSION['openid']">
		    <switch name="config['weixin_buy_follow_wechat']">
			    <case value="0">
				    <php>if($now_group['wx_cheap']){</php>
                            <script>layer.open({title:['提示：','background-color:#8DCE16;color:#fff;'],content:'在微信中购买本单，每单减免 <b style="color:red;">{pigcms{$now_group.wx_cheap}元</b>！',btn:['好的'],shadeClose:false});</script>
				    <php>}</php>
			    </case>
			    <case value="1">
				    <php>if($now_group['wx_cheap']){</php>
					    <php>if($now_user['is_follow']){</php>
						    <script>layer.open({title:['提示：','background-color:#8DCE16;color:#fff;'],content:'在微信中购买本单，每单减免 <b style="color:red;">{pigcms{$now_group.wx_cheap}元</b>！',btn:['好的'],shadeClose:false});</script>
					    <php>}else{</php>
						    <script>layer.open({title:['提示：','background-color:#FF658E;color:#fff;'],content:'关注公众号后购买本单，每单减免 <b style="color:red;">{pigcms{$now_group.wx_cheap}元</b>！<br/>长按图片识别二维码关注：<br/><img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=see_qrcode&type=group&id={pigcms{$now_group.group_id}" style="width:230px;height:230px;"/>',shadeClose:false});</script>
					    <php>}</php>
				    <php>}</php>
			    </case>
			    <case value="2">
				    <php>if($now_user['is_follow']){</php>
					    <php>if($now_group['wx_cheap']){</php>
						    <script>layer.open({title:['提示：','background-color:#8DCE16;color:#fff;'],content:'在微信中购买本单，每单减免 <b style="color:red;">{pigcms{$now_group.wx_cheap}元</b>！',btn:['好的'],shadeClose:false});</script>
					    <php>}</php>
				    <php>}else{</php>
					    <script>layer.open({title:['提示：','background-color:#FF658E;color:#fff;'],content:'您必须关注公众号后才能购买本单！<br/>长按图片识别二维码关注：<br/><img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=see_qrcode&type=group&id={pigcms{$now_group.group_id}" style="width:230px;height:230px;"/>',shadeClose:false});$('button.mj-submit').remove();var showBuyBtn = false;</script>
				    <php>}</php>
			    </case>
		    </switch>
	    <elseif condition="$now_group['wx_cheap']"/>
		        <script>layer.open({title:['提示：','background-color:#8DCE16;color:#fff;'],content:'在微信中购买本单，每单减免 <b style="color:red;">{pigcms{$now_group.wx_cheap}元</b>！',btn:['好的'],shadeClose:false});</script>
	    </if>
    <elseif condition="$now_group['wx_cheap']"/>
            <script>layer.open({title:['提示：','background-color:#8DCE16;color:#fff;'],content:'在APP中购买本单，每单减免 <b style="color:red;">{pigcms{$now_group.wx_cheap}元</b>！',btn:['好的'],shadeClose:false});</script>
    </if>
	<script>if(showBuyBtn){$('button.mj-submit').show();}</script>
	<include file="Public:footer"/>
	{pigcms{$hideScript}
	<script src="https://cdn.bootcss.com/yui/3.18.1/yui/yui.js"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/common.js?216" charset="utf-8"></script>
	<script>
	
	$(function(){
			
			
			$("form").submit(function() {
				$("button.mj-submit").attr("disabled", "disabled");
				$("button.mj-submit").html("正在处理...");

			});
			
			
			wx_cheap= finalprice > 0 ? finalprice * 100 : wx_cheap;
			var quantity = $("input[name='quantity']");
			$('button.plus').click(function(){
		
				$('#tips').removeClass('tips-err').empty();
				var pigcms_now_quantity = parseInt(quantity.val());
				if(!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(pigcms_now_quantity)){
					$('#tips').addClass('tips-err').html('请输入正确的购买数量');
				}else if(pigcms_now_quantity + 1 >= quantity.attr('max') && quantity.attr('max') != '0'){
					$('#tips').addClass('tips-err').html('您最多能购买'+quantity.attr('max')+'单');
					quantity.val(quantity.attr('max'));
					$(this).prop('disabled',true);
				}else{
					quantity.val(pigcms_now_quantity+1);
					//console.log(pigcms_now_quantity)
					if(pigcms_now_quantity+1>=discount_room&&discount_room>0){
						$('.discount_price').show();
						$('.J_campaign-value').show()
						$('.J_campaign-value').html(price*(pigcms_now_quantity+1)/100+'元');
						$('.J_total-price').html(discount_price_all*100*(pigcms_now_quantity+1)/100+'元');
					}else{
						$('.discount_price').hide();
						$('.J_total-price').html(price*(pigcms_now_quantity+1)/100+'元');
					}
                    //$('.J_total-price').html(price*(pigcms_now_quantity+1)/100+'元');
					$('#wx_cheap').html(wx_cheap*(pigcms_now_quantity+1)/100);
					$('button.minus').prop('disabled',false);
				}
			});
			$('button.minus').click(function(){
				$('#tips').removeClass('tips-err').empty();
				var pigcms_now_quantity = parseInt(quantity.val());
				if(!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(pigcms_now_quantity)){
					$('#tips').addClass('tips-err').html('请输入正确的购买数量');
				}else if(pigcms_now_quantity - 1 < quantity.attr('min')){
					$('#tips').addClass('tips-err').html('您最少能购买'+quantity.attr('min')+'单');
				}else{
					
					if(pigcms_now_quantity-1 <= quantity.attr('min')){
						$(this).prop('disabled',true);
					}
					quantity.val(pigcms_now_quantity-1);
					if(pigcms_now_quantity-1<discount_room||discount_room<=0){
						//$('.J_total-price').html(discount_price_all*100*(pigcms_now_quantity-1)/100+'元');
						$('.J_total-price').html(price*(pigcms_now_quantity-1)/100+'元');
						$('.J_campaign-value').hide()
						$('.discount_price').hide();
					}else if(discount_room>0){
						 $('.J_total-price').html(discount_price_all*100*(pigcms_now_quantity-1)/100+'元');
						 $('.J_campaign-value').html(price*(pigcms_now_quantity-1)/100+'元')
						 $('.discount_price').show();
					}
					$('#wx_cheap').html(wx_cheap*(pigcms_now_quantity-1)/100);
					$('button.plus').prop('disabled',false);
				}
			});
			quantity.blur(function(){
				$('#tips').removeClass('tips-err').empty();
				var pigcms_now_quantity = parseInt(quantity.val());
				if(!/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(pigcms_now_quantity)){
					$('#tips').addClass('tips-err').html('请输入正确的购买数量');
				}else{
					if(quantity.attr('max') != '0' && pigcms_now_quantity == quantity.attr('max')){
						$('button.plus').prop('disabled',true);
					}else if(quantity.attr('max') != '0' && pigcms_now_quantity > quantity.attr('max')){
						$('#tips').addClass('tips-err').html('您最多能购买'+quantity.attr('max')+'单');
						$('button.plus').prop('disabled',true);
						quantity.val(quantity.attr('max'));
					}else{
						$('button.plus').prop('disabled',false);
					}
					if(pigcms_now_quantity == quantity.attr('min')){
						$('button.minus').prop('disabled',true);
					}else if(pigcms_now_quantity < quantity.attr('min')){
						$('#tips').addClass('tips-err').html('您最少能购买'+quantity.attr('min')+'单');
						$('button.minus').prop('disabled',true);
						quantity.val(quantity.attr('min'));
					}else{
						$('button.minus').prop('disabled',false);
					}
					console.log(quantity.val())
					console.log(discount_price_all)
					if(quantity.val()>=discount_room &&discount_room>0 ){
						$('.J_campaign-value').html(price*(parseInt(quantity.val()))/100+'元');
						$('.J_campaign-value').show();
						$('.J_total-price').html(discount_price_all*100*(parseInt(quantity.val()))/100+'元');
						$('.discount_price').show();
					}else{
						$('.discount_price').hide();
						
						$('.J_campaign-value').hide();
						
						$('.J_total-price').html(price*(parseInt(quantity.val()))/100+'元');
					}
				}
			});
		});
			
		var hotel_content = '{pigcms{:json_encode($hotel_list)}';
		var initialize_data = $.parseJSON(hotel_content.replace(/\r\n/g,"<BR>").replace(/\n/g,"<BR>"));
		//console.log(initialize_data)
		var week = ["日","一","二","三","四","五","六"];
		var oCal;
		YUI({
			modules: {
				'price-calendar': {
					fullpath: '{pigcms{$static_public}trip-calendar/price-calendar.js',
					type    : 'js',
					requires: ['price-calendar-css']
				},
				'price-calendar-css': {
					fullpath: '{pigcms{$static_public}trip-calendar/price-calendar.css',
					type    : 'css'
				}
			}
		}).use('price-calendar', function(Y) {
			
			/**
			 * 非弹出式日历实例
			 * 直接将日历插入到页面指定容器内
			 */
			oCal = new Y.PriceCalendar({
				container   : '#J_Calendar' //非弹出式日历时指定的容器（必选）
				// ,selectedDate: new Date       //指定日历选择的日期
				,count		: 3
				,afterDays	: 180
				<?php if($trade_hotel['time_dep_time']){ ?>
				,depDate	: '{pigcms{$trade_hotel.time_dep_time}'
				,endDate	: '{pigcms{$trade_hotel.time_end_time}'
				<?php } ?>
			});
			$('.price-calendar-bounding-box table td').click(function(){
				if($(this).hasClass('disabled')){
					return false;
				}else{
					if(($('.dep-date').size() > 0 && $('.end-date').size() > 0) || ($('.dep-date').size() == 0 && $('.end-date').size() == 0)){
						$('.dep-date').find('.mark').empty();
						$('.dep-date').removeClass('dep-date');
						$('.end-date').find('.mark').empty();
						$('.end-date').removeClass('end-date');
						oCal.set('endDate','');
						
						$('.selected-range').removeClass('selected-range');
						
						oCal.set('depDate',$(this).data('date'));
						$(this).addClass('dep-date').find('.mark').html('入住');
					}else if(oCal.get('depDate')){
						var nowTmpdate = $(this).data('date').replace(/-/g,'');
						var prevTmpdate = oCal.get('depDate').replace(/-/g,'');
					
						if(nowTmpdate < prevTmpdate){
							$('.dep-date').find('.mark').empty();
							$('.dep-date').removeClass('dep-date');
							oCal.set('depDate',$(this).data('date'));
							$(this).addClass('dep-date').find('.mark').html('入住');
						}else{
							var tmp_dep_data = $(this).attr('class');
							if(tmp_dep_data=='dep-date'){
								alert('不能选同一天'); 
							}else{
								oCal.set('endDate',$(this).data('date'));
								
								var depTmpdate = parseInt(oCal.get('depDate').replace(/-/g,''));
								var endTmpdate = parseInt(oCal.get('endDate').replace(/-/g,''));
								$(this).addClass('end-date').find('.mark').html('离店');
								for(var i = depTmpdate+1;i<endTmpdate;i++){
									var tmpI = i.toString();
									var tmpDate = tmpI.substr(0,4)+'-'+tmpI.substr(4,2)+'-'+tmpI.substr(6,2);
									$('td[data-date="'+tmpDate+'"]').addClass('selected-range');
								}
								setTimeout(function(){
									changeTime();
								},300);
							}
						}
					}
				}
			});
			$('#hotel-info-box .startweek').html('周'+week[oCal._toDate($('#hotel-info-box .indate').data('date')).getDay()]);
			$('#hotel-info-box .endweek').html('周'+week[oCal._toDate($('#hotel-info-box .outdate').data('date')).getDay()]);
			$('.detail-date').click(function(){
				$('#hotel-info-box').hide();
				$('#J_Calendar').show();
			});
			$('.rooms .rigit_activebg').live('click',function(){
				var rooms = $(this).closest('.rooms');
				if(rooms.hasClass('on')){
					rooms.removeClass('on');
					rooms.find('.info-list').removeAttr('style');
				}else{
					rooms.addClass('on');
				}
				return false;
			});
			$('.rooms .roomdetail .btn2').live('click',function(){
				var layerTip = layer.open({type: 2});
				var that = $(this);
				var book_day_ = parseInt(that.data('book_day'));
				var dep_time  = $('#dep-time').val()+"";
				var end_time  = $('#end-time').val()+"";
			
				ah = dep_time.substring(0,4);
				am = dep_time.substring(4,6);
				as = dep_time.substring(6,8);
				oDate1  =  new  Date(ah  +  '-'  +  am  +  '-'  +  as) 
				
				ah = end_time.substring(0,4);
			
				am = end_time.substring(4,6);
		
			
				as = end_time.substring(6,8);
			
				oDate2  =  new  Date(ah  +  '-'  +  am  +  '-'  +  as) 
				iDays  =  parseInt(Math.abs(oDate1  -  oDate2)  /  1000  /  60  /  60  /24)  
			
				if(iDays>book_day_&&book_day_!=0){
					layer.open({
						content: '最多可预订'+book_day_+'天'
						,btn:['关闭']
					});
					layer.close(layerTip);
					return false;
				}
				$.post("{pigcms{:U('ajax_get_trade_hotel_price')}",{group_id:{pigcms{$now_group.group_id},cat_id:$(this).data('cat_id'),dep_time:$('#dep-time').val(),end_time:$('#end-time').val()},function(result){
					result = $.parseJSON(result);
					//console.log(result)
					if(result.err_code){
						layer.open({
							content: result.err_msg
							,btn: ['关闭']
						});
					}
					layer.close(layerTip);
					var detailVal = {
						'cat_pname' : that.closest('.rooms').find('.picroom-info .room').html()
						,'cat_name' : that.closest('.roomdetail').find('.left .bra').html()
						,'cat_id' : that.data('cat_id')
						,'dep_date' : $('#hotel-info-box .indate').data('date')
						,'end_date' : $('#hotel-info-box .outdate').data('date')
						,'price' : result.price
						,'stock' : result.stock
						,'discount_room' : result.discount_room
						,'stock_list' : result.stock_list
					};
					//console.log(detailVal)
					showDetail(detailVal);
				});
				return false;
			});
			
			if($.cookie('allready_buy') && typeof($.cookie('allready_buy'))!='undefined'){
				
				
				$.post("{pigcms{:U('ajax_get_trade_hotel_price')}",{group_id:{pigcms{$now_group.group_id},cat_id:$.cookie('cat_id'),dep_time:$.cookie('dep_time'),end_time:$.cookie('end_time')},function(result){
					result = $.parseJSON(result);
		
					console.log(result)
					if(result.err_code){
						layer.open({
							content: result.err_msg
							,btn: ['关闭']
						});
					}
					layer.close();
					var detailVal = {
						'cat_pname' : $.cookie('cat_pname')
						,'cat_name' : $.cookie('cat_name')
						,'cat_id' : $.cookie('cat_id')
						,'dep_date' : $.cookie('dep_date')
						,'end_date' : $.cookie('end_date')
						,'price' : result.price
						,'stock' : result.stock
						,'discount_room' : result.discount_room
						,'stock_list' : result.stock_list
					};
					
					//window.location.href="{pigcms{:U('Group/buy',array('group_id'=>$_GET['group_id']))}#buy";
					showDetail(detailVal);
					
				});
				return false;
			}
			$('.rooms .rpDetail').live('click',function(){
				$('body').append('<div class="mask-layer"></div>');
				laytpl($('#listTypePopTpl').html()).render(initialize_data[$(this).data('cat_id')], function(html){
					$('body').append(html);
					$('.hpic_show').height($(window).width()*0.92*450/760);
					var productSwiper = $('.hpic_show').swiper({
						pagination:'.swiper-pagination',
						loop:true,
						grabCursor: true,
						paginationClickable: true,
						simulateTouch:false
					});
				});
				$('.mask-layer,.roomTypeInfo .htclose').one('click',function(){
					//$('body').css('overflow',' auto'); 

					$('.roomTypeInfo').remove();
					$('.mask-layer').remove();
				});
				
				$('.roomTypeInfo .bottom_btn span').click(function(){
					$('.room-cat-'+$(this).data('cat_id')).addClass('on');
					$('.roomTypeInfo .htclose').trigger('click');
				});
				//$('body').css('overflow',' hidden'); 

				
			});
			
			$('.roomdetail .left').live('click',function(){
				$('body').append('<div class="mask-layer"></div>');
				var cat_id = $(this).data('cat_id');
				for(var i in initialize_data){
					if(initialize_data[i].son_list){
						for(var k in initialize_data[i].son_list){
							if(initialize_data[i].son_list[k].cat_id==cat_id){
								tmp_data = initialize_data[i].son_list[k];
							}
							break;
						}
					}
				}
			
				laytpl($('#listsonTypl').html()).render(tmp_data, function(html){
					
					$('body').append(html);
					var productSwiper = $('.hpic_show').swiper({
						pagination:'.swiper-pagination',
						loop:true,
						grabCursor: true,
						paginationClickable: true,
						simulateTouch:false
					});
				});
				$('.mask-layer,.roomTypeInfo .htclose').one('click',function(){
					$('body').css('overflow','auto'); 
					$('.roomTypeInfo').remove();
					$('.mask-layer').remove();
				});
				
				$('.roomTypeInfo .bottom_btn span').click(function(){
					$('.room-cat-'+$(this).data('cat_id')).addClass('on');
					$('.roomTypeInfo .htclose').trigger('click');
				});
	
				$('#soninfo').height($('#son_content').height()*3); 
				$('body').css('overflow',' hidden'); 

			
				
			});
			
			laytpl($('#listHotelTpl').html()).render(initialize_data, function(html){
				$('.detail-main ul').html(html);
			});
			
			$(window).bind('hashchange',function(){
				if(location.hash == '' || location.hash == '#'){
					$('#buy_form_box').hide();
					$('#hotel-info-box').show();
				}
			});
		});
		function showDetail(detailVal){
			$('#buy_form_box .type.pname').html(detailVal.cat_pname);
			$('#buy_form_box .type.name').html(detailVal.cat_name);
			$('#cat-id').val(detailVal.cat_id);
			if($.cookie('allready_buy') && typeof($.cookie('allready_buy'))!='undefined'){
					
				var book_day = get_day($.cookie('dep_date'),$.cookie('end_date'));
				//计算入住离店时间
				var tmpInFormatDate = $.cookie('dep_date').replace(/-/g,'');

				var tmpOutFormatDate = $.cookie('end_date').replace(/-/g,'');
				$('#dep-time').val($.cookie('dep_time'));
				$('#end-time').val($.cookie('end_time'));
				$('#cat-id').val($.cookie('cat_id'));
				
			}else{
				
				var book_day = get_day($('.dep-date').data('date'),$('.end-date').data('date'));
				//计算入住离店时间
				var tmpInFormatDate = $('.dep-date').data('date').replace(/-/g,'');

				var tmpOutFormatDate = $('.end-date').data('date').replace(/-/g,'');
			}

			var tmpInFormatDateWeek = '周'+week[oCal._toDate(detailVal.dep_date).getDay()];
			var tmpOutFormatDateWeek = '周'+week[oCal._toDate(detailVal.end_date).getDay()];
			
			$('#buy_form_box .p-info .date').html(tmpInFormatDate.substr(4,2)+'月'+tmpInFormatDate.substr(6,2)+'日('+tmpInFormatDateWeek+') — '+tmpOutFormatDate.substr(4,2)+'月'+tmpOutFormatDate.substr(6,2)+'('+tmpOutFormatDateWeek+') 共'+(book_day)+'晚');
			
			$('#buy_form_box .J_total-price').html(detailVal.price+'元');
			discount_price_all = 0;
			if(Number(detailVal.discount_room)>0){
				$('#discount_room .J_discount-room').html(detailVal.discount_room+'间及以上');
				$('#discount_room .J_discount-price').html(detailVal.discount_price);
				$('#discount_room_price').show();
				discount_room = Number(detailVal.discount_room);
				$.each(detailVal.stock_list,function(index,val){
					if(val.discount_price>0){
						discount_price_all += Number(val.discount_price);
					}else{
						discount_price_all += Number(val.price);
					}
				});
			}
			$('input[name="quantity"]').val(1);
			$('.J_campaign-value').hide();
			if(Number(detailVal.discount_room)>0 && $('input[name="quantity"]').val()>=detailVal.discount_room){
				$('.J_campaign-value').html(detailVal.price+'元');
				$('.J_campaign-value').show();
				$('.J_total-price').html(discount_price_all+'元');
			}
			if(discount_price_all>0  && $('input[name="quantity"]').val()>detailVal.discount_room ){
				$('.J_campaign-value').html(detailVal.price+'元');
				$('.J_campaign-value').show(); 
				
			}
			//$('.J_total-price').html(discount_price_all+'元');
			$("input[name='quantity']").attr('max',detailVal.stock);
			$('button.plus').prop('disabled',false);
			price = finalprice >0 ? finalprice * 100 : detailVal.price*100;
			
			var priceHtml = '';

			for(var i in detailVal.stock_list){
				var tmpFormatDate = detailVal.stock_list[i].day.replace(/-/g,'');
				var discount_price_txt='';
				if (detailVal.stock_list[i].discount_price>0 ){
					discount_price_txt= '<div class="discount_price" >(优惠价格 '+detailVal.stock_list[i].discount_price+' 元)</div>';
				}
				priceHtml+= '<dd class="dd-padding"><div class="left">'+tmpFormatDate.substr(4,2)+'月'+tmpFormatDate.substr(6,2)+'日：</div><div class="right">'+detailVal.stock_list[i].price+'元'+discount_price_txt+'</div></dd>';
			}
			$('.price-list').html(priceHtml);
			
			$('#hotel-info-box').hide();
			$('#buy_form_box').show();
			  
			location.hash = 'buy';
			if(discount_price_all>0){
				if(Number($('input[name="quantity"]').val())>=Number(detailVal.discount_room) ){
					$('.discount_price').show();
				}else{
					$('.discount_price').hide();
				}
				// $('.discount_price').show();
			}
			
			// console.log(detailVal);
		}
		function get_day(dep_time,end_time){
			
			aDate  = dep_time.split("-")  
		   oDate1  =  new  Date(aDate[0]  +  '-'  +  aDate[1]  +  '-'  +  aDate[2]) 
		   aDate  =  end_time.split("-")  
		   oDate2  =  new  Date(aDate[0]  +  '-'  +  aDate[1]  +  '-'  +  aDate[2])  
		   iDays  =  parseInt(Math.abs(oDate1  -  oDate2)  /  1000  /  60  /  60  /24)  
		  return iDays;
		}
		function changeTime(){
			$('#hotel-info-box .indate').data('date',$('.dep-date').data('date'));
			$('#hotel-info-box .outdate').data('date',$('.end-date').data('date'));
			
			var tmpInFormatDate = $('.dep-date').data('date').replace(/-/g,'');
			$('#hotel-info-box .indate').html(tmpInFormatDate.substr(4,2)+'-'+tmpInFormatDate.substr(6,2));
			$('#dep-time').val(tmpInFormatDate);
			
			var tmpOutFormatDate = $('.end-date').data('date').replace(/-/g,'');
			$('#hotel-info-box .outdate').html(tmpOutFormatDate.substr(4,2)+'-'+tmpOutFormatDate.substr(6,2));
			$('#end-time').val(tmpOutFormatDate);
			
			$('#hotel-info-box .startweek').html('周'+week[oCal._toDate($('#hotel-info-box .indate').data('date')).getDay()]);
			$('#hotel-info-box .endweek').html('周'+week[oCal._toDate($('#hotel-info-box .outdate').data('date')).getDay()]);
			aDate  =  $('.dep-date').data('date').split("-")  
			oDate1  =  new Date( aDate[0]+  '-'  + aDate[1]  +  '-'  +  aDate[2]  ) 
		
			aDate  =  $('.end-date').data('date').split("-")  
			oDate2  =  new Date(aDate[0]+  '-'  + aDate[1]  +  '-'  +  aDate[2] )  
			iDays  =  parseInt(Math.abs(oDate1  -  oDate2)  /  1000  /  60  /  60  /24)  
		 
			$('#hotel-info-box .ol_night').html('共'+(iDays)+'晚');
			
			
			$('#J_Calendar').hide();
			$('#hotel-info-box').show();
			var layerTip = layer.open({type: 2});
			$.post("{pigcms{:U('ajax_get_trade_hotel_stock')}",{group_id:{pigcms{$now_group.group_id},dep_time:$('#dep-time').val(),end_time:$('#end-time').val()},function(result){
				initialize_data = $.parseJSON(result);
				//console.log(initialize_data);
				laytpl($('#listHotelTpl').html()).render(initialize_data, function(html){
					layer.close(layerTip);
					$('.detail-main ul').html(html);
				});
			});
		}
	</script>
	<script id="listTypePopTpl" type="text/html">
		<div class="type-pop-box roomTypeInfo newdetailhsize plugin-inited box-active plugin-show" style="position:fixed;">
			<div class="toptitle">
				<p><span class="htitle">{{ d.cat_name }}</span></p>
				<div class="htclose"><i class="cancel-icon"></i></div>
			</div>
			<div class="wrap page-content">
				<div class="swiper-container hpic_show">
					<div class="swiper-wrapper">
						{{# for(var i in d.cat_pic_list){ }}
							<div class="swiper-slide"><img src="{{d.cat_pic_list[i].m_image}}"/></div>
						{{# } }}
					</div>
					<div class="swiper-pagination"></div>
				</div>
				<div class="type-list">
					<p class="faclist">
						<span><i class="detail_fac_v0"></i>{{ d.breakfast_info }}</span>
						<span><i class="detail_fac_v1"></i>{{ d.window_info }}</span>
						<span><i class="detail_fac_v2"></i>{{ d.floor_info }}</span>
						<span><i class="detail_fac_v3"></i>{{ d.room_size }}</span>
						<span><i class="detail_fac_v4"></i>{{ d.bed_info }}</span>
						<span><i class="detail_fac_v6"></i>{{ d.network_info }}</span>
					</p>
					<p class="tip"></p>
				</div>
				{{# if(d.cat_info){ }}
				<div class="discount u-bt discountRoomInfo">
					<p class="clearfix">
						<span class="dct_tit">其他信息：</span>
						<span class="dct_txt">{{ d.cat_info }}</span>
					</p>
				</div>
				{{# } }}
			</div>
			<div class="bottom_btn"><span data-cat_id="{{ d.cat_id }}">查看房型报价</span></div>
		</div>
	</script>
	
	<script id="listsonTypl" type="text/html">
		<div class="type-pop-box roomTypeInfo newdetailhsize plugin-inited box-active plugin-show"  id="soninfo" style="position:fixed;">
			<div class="toptitle">
				<p><span class="htitle">{{ d.cat_name }}</span></p>
				<div class="htclose"><i class="cancel-icon"></i></div>
			</div>
			<div class="wrap page-content">
				
				
				<div class="discount u-bt discountRoomInfo" id="son_content">
					
					<p class="clearfix">
						<span class="dct_tit">价格：</span>
						<span class="dct_txt">{{# if(d.price_txt){ }}￥ {{ d.price_txt }} {{# }else{ }}暂无售价 {{# } }}</span>
					</p>
					
					<p class="clearfix" style="margin-top:10px">
						<span class="dct_tit">支持发票：</span>
						<span class="dct_txt">{{# if(d.has_receipt==1){ }} 支持 {{# }else{ }} 不支持{{# } }}</span>
					</p>
					<p class="clearfix" style="margin-top:10px"> 
						<span class="dct_tit">是否任意退：</span>
						<span class="dct_txt">{{# if(d.has_refund==0){ }} 任意退 {{# }else if(d.has_refund == 1){ }} 不可取消 {{# }else if(d.has_refund==2){  }} 入住{{ d.refund_hour }}小时前可退 {{# } }}</span>
					</p>
					{{# if(d.cat_info!=''){ }}
					<p class="clearfix" style="margin-top:10px"> 
						<span class="dct_tit">其他信息：</span>
						<span class="dct_txt">{{ d.cat_info }}</span>
					</p>
					{{# } }}
					
				</div>
				
			</div>
			<div class="bottom_btn"><span data-cat_id="{{ d.cat_id }}">查看房型报价</span></div>
		</div>
		
	</script>
</body>
</html>