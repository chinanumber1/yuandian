<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name='apple-touch-fullscreen' content='yes'/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<meta name="format-detection" content="telephone=no"/>
<meta name="format-detection" content="address=no"/>
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$shop['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/css_whir.css?a=1123688"/>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>

<script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script>

<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<style type="text/css">
.details{ position: relative; }
.collection{ position: absolute; width: 35px; height: 35px; border-radius: 100%; background: url({pigcms{$static_path}images/3.png) center no-repeat; z-index: 999; top:10px; right: 10px; }
.collection:after{ display: block;  content: ''; width: 16px; height: 16px; background: url({pigcms{$static_path}images/2.png) center no-repeat; background-size: 16px; position: absolute; ); top: 50%; left: 50%;  margin: -8px; }
.collection.on:after{ background: url({pigcms{$static_path}images/1.png) center no-repeat; background-size: 16px; }
.albumContainer {position: fixed;width: 100%;height: 100%;left: 0;top: 0;background: #000;z-index: 1000;display: none;overflow: hidden;-webkit-transform-origin: 0px 0px;opacity: 1;-webkit-transform: scale(1,1);}
.albumContainer .swiper-close {right: 0px;top: 0px;text-align:right;padding-right: 10px;line-height: 50px;position: absolute;z-index: 21;width: 50px; height: 50px;color: white;font-size: 20px; font-family: "Arial";}
.albumContainer .swiper-pagination {position: absolute;z-index: 20;left: 0;top: 20px;text-align: center;width: 100%;height: 20px;}
.albumContainer .swiper-slide img{ width: 100%; max-height: 85% }
.albumContainer .swiper-wrapper{ padding-top: 50px; }
.albumContainer .swiper-pagination-switch{ background: #fff; opacity: 1; }
.albumContainer .swiper-active-switch {background: #06c1ae;}
.albumContainer .swiper-wrapper{
    position: relative;
    width: 100%;
    height: 100%;
    z-index: 1;
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-transition-property: -webkit-transform;
    -moz-transition-property: -moz-transform;
    -o-transition-property: -o-transform;
    -ms-transition-property: -ms-transform;
    transition-property: transform;
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
}
.swiper-container,.swiper-wrapper,.swiper-slide{
  width: 100%;
  height: 100%;
}
.swiper-container {
  margin: 0 auto;
  position: relative;
  overflow: hidden;
  -webkit-backface-visibility: hidden;
  -moz-backface-visibility: hidden;
  -ms-backface-visibility: hidden;
  -o-backface-visibility: hidden;
  backface-visibility: hidden;
  z-index: 1;
}
.swiper-slide{
	  float: left;
}
.swiper-pagination {
  position: absolute;
  z-index: 20;
  left: 0px;
  width: 100%;
  text-align: center;
  bottom:4px;
}
.swiper-pagination-switch {
  display: inline-block;
  width: 6px;
  height: 6px;
  border-radius: 8px;
  background: black;
  margin-right:5px;
  opacity: 0.14;
  cursor: pointer;
}
.swiper-active-switch {
  background: #06c1ae;
  opacity: 1;
}

.banner{
	height: 119px;;
}
.banner img{
	height:100%;
	width:100%;
}
.slider{
	background-color: white;
	border-bottom: 1px solid #edebeb;
	margin-bottom: 10px;
}

.icon-list .icon {
  float: left;
  width: 25%;
  text-align: center;
}
.icon-list.num10 .icon {
  float: left;
  width: 20%;
  text-align: center;
}
.icon-list .icon > a {
  padding-top: 12px;
  display: block;
}
.icon-list .icon-circle {
  display: block;
  margin: auto;
  width: 40px;
  height: 40px;
  text-align: center;
  color: white;
  margin-bottom: 3px;
}
.icon-list.num10 .icon-circle {
	width:40px;
	height:40px;
}
.icon-list .icon-circle img {
  width: 100%;
  height: 100%;
}
.icon-list .icon-desc {
  text-align: center;
  color: #999;
}
.icon-list.num10 .icon-desc {
	font-size:12px;
}
.slider .swiper-pagination{
	bottom:2px;
}
.swiper-pagination-bullet{
	background-color: white;
	border:1px solid #d8d8d8;
	width:5px;
	height:5px;
	margin-right: 5px;
	opacity: 1;
}
.swiper-pagination-bullet-active{
	background: #06c1ae;
	border:1px solid #06c1ae;
}
.tag{
	    display: inline-block;
    margin-left: 3px;
    border: 1px solid #f58300;
    color: #f58300;
    padding: 1px 3px;
    border-radius: 2px;
    font-size: 12px;
    line-height: 12px;
}
</style>

<script type="text/javascript">
  $(function(){
    $(".collection").click(function(){
    	var store_id = "{pigcms{$_GET['store_id']}";
		var collectionUrl = "{pigcms{:U('Foodshop/store_collection')}";
		var uid = "{pigcms{$user_session['uid']}";
		if(!uid){
			layer.open({content:'请先登录再进行收藏！',btn: ['确定'],end:function(){location.href="{pigcms{:U('Login/index')}";}});
			return false;
		}
		if($(this).hasClass("on")){
			$(this).removeClass("on");
			$.post(collectionUrl,{'store_id':store_id},function(data){
				layer.open({
                    content: data.msg
                    ,skin: 'msg'
                    ,anim: 'up'
                    ,time: 2 
                });
			},'json');
		}else{
        	$(this).addClass("on");
			$.post(collectionUrl,{'store_id':store_id},function(data){
				layer.open({
                    content: data.msg
                    ,skin: 'msg'
                    ,anim: 'up'
                    ,time: 2 
                });
			},'json');
      	}
    })
  })
</script>
<body>

	<div class="collection <if condition="$collection_info">on</if>"></div>

  <header class="headshop" style="background: url({pigcms{$shop['image']}) center top no-repeat; background-size: 100% 100%;" data-pics="{pigcms{$shop['pic_str']}">
       <div class="shop_bot">
          <span> {pigcms{$shop['name']}</span>
          <if condition="$shop['is_park']">
          <span class="cheb"><img src="{pigcms{$static_path}images/xqtc_06.png"></span>
          </if>
          <span style="float:right;margin-right: 16px;"> <if condition="$shop['pic_count'] gt 0">{pigcms{$shop['pic_count']}张</if></span>
       </div>
  </header>

  <section class="Storenav" style="display:none">
     <ul class="clr">
		<if condition="$shop['is_book']">
		<li>
			<a href="{pigcms{:U('Foodshop/book_order', array('store_id' => $shop['store_id']))}">
				<span class="head_upper"><img src="{pigcms{$static_path}images/book.png"></span>
				<span class="head_lower">订桌点餐</span>
			</a>
		</li>
		</if>
		<if condition="$shop['is_queue']">
        <li>
          <a href="{pigcms{:U('Foodshop/queue', array('store_id' => $shop['store_id']))}">
             <span class="head_upper"><img src="{pigcms{$static_path}images/xqym_05.png"></span>
             <span class="head_lower">排号</span>
          </a>
       </li>
		</if>
		<if condition="$config['pay_in_store']">
        <li>
          <a href="{pigcms{:U('My/pay', array('store_id' => $shop['store_id']))}">
             <span class="head_upper"><img src="{pigcms{$static_path}images/xqym_07.png"></span>
             <span class="head_lower">{pigcms{$config.cash_alias_name}</span>
          </a>
       </li>
	   </if>
		<if condition="$shop['is_takeout']">
        <li>
          <a href="{pigcms{:U('Shop/index')}#shop-{pigcms{$shop['store_id']}">
             <span class="head_upper"><img src="{pigcms{$static_path}images/xqym_09.png"></span>
             <span class="head_lower">{pigcms{$config.shop_alias_name}</span>
          </a>
       </li>
		</if>
	   <if condition="$card_info AND $card_info['self_get'] eq 1 ">
		<li>
          <a href="{pigcms{:U('My_card/merchant_card',array('mer_id'=>$shop['mer_id']))}">
             <span class="head_upper"><img src="{pigcms{$static_path}images/merchant_card.png"></span>
             <span class="head_lower">会员卡</span>
          </a>
       </li>
       </if>
     </ul>
	
  </section>
		<if condition="$wap_index_slider">
			<section class="slider" >
				<div class="<if condition="count($wap_index_slider) gt 1">swiper-container</if> swiper-container2" <if condition="$slider_num elt ($wap_index_slider_number/2)">style="height:84px;"<else />style="height:168px;"</if>>
					<div class="swiper-wrapper">
						<volist name="wap_index_slider" id="vo">
							<div class="swiper-slide">
								<ul class="icon-list num{pigcms{$wap_index_slider_number}">
									<volist name="vo" id="voo">
										
										<li class="icon">
											<a href="{pigcms{$voo.url}">
												<span class="icon-circle">
													<img src="{pigcms{$voo.pic}">
												</span>
												<span class="icon-desc">{pigcms{$voo.name}</span>
											</a>
										</li>
									</volist>
								</ul>
							</div>
						</volist>
					</div>
					<if condition="count($wap_index_slider) gt 1">
					<div class="swiper-pagination swiper-pagination2"></div>
					</if>
				</div>
			</section>
		</if>
	<section class="BusinessHours clr">
        <if condition="$mer_discount">
        <div class="">
            <img src="./static/images/discount_2.png" style="width:18px; height:20px;margin-top:8px; margin-right:10px;float:left">
            <span style="color: #a01615;padding-right: 10px;">{pigcms{$mer_discount}折优惠</span></div>
        </if>
		<if condition="$merchant['isverify']">
			<img src="./static/images/rec_2.png" style="width:18px; height:20px;margin-top:8px; margin-right:10px;float:left">
			<span style="float:left">认证商家</span>
			</br>
		</if>
		<if condition="$shop['is_close']">
			<span class="on fl">营业时间：{pigcms{$shop['business_time']}</span>
			<span class="fr rig">未营业</span>
		<else />
			<span class="on on1 fl">营业时间：{pigcms{$shop['business_time']}</span>
		</if>
	</section>
<if condition="$shop['wifi_account']">
  <section  class="wifi clr">
     <div class="wifi_left fl">
       <span class="wifi_top"></span>
       <span class="wifi_font" style="display: none;">点击按钮连接无线网</span>
       <span class="wifi_font on" >{pigcms{$shop['wifi_account']}</span>
     </div>
     <div class="wifi_right fr">
       <a href="javascript:void(0)" style="display: none;">微信wifi</a>
       <span href="javascript:void(0)" class="on" >密码：{pigcms{$shop['wifi_password']}</span>
     </div>
  </section>
</if>
 <section class="purchase_list">
    <div class="navBox_list m10_list">
       <dl>
		<volist name="shop['group_list']" id="group">
         <dd class="Menulink clr">
            <a href="{pigcms{$group['url']}">
              <div class="Menulink_img fl">
                <img class="on" src="{pigcms{$group['list_pic']}">
               <if condition="$group['pin_num'] eq 0"> <span class="MenuGroup"></span><else /><span class="PinGroup"></span></if>
              </div>
              <div class="Menulink_right">
                <h2>{pigcms{$group['name']}</h2>
                <div class="MenuPrice">
					<span class="PriceF"><i>￥</i><em>{pigcms{$group['price']}<if condition="$group.extra_pay_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$group.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if></em></span>
                  <span class="PriceT">门市价:￥{pigcms{$group['old_price']}</span>
					
				  <php>if($group['wx_cheap']>0){</php> 
				  <span class="tag">微信再减{pigcms{$group.wx_cheap|floatval}元</span><php>}</php>
                  <span class="PriceS">{pigcms{$group['sale_txt']}</span>
				  
                </div>
              </div>
            </a>
         </dd>
         </volist>
       </dl>
       <div class="more">
         <span>查看其他<i></i>个{pigcms{$config.group_alias_name}</span>
       </div>
      </div>
 </section>

<if condition="$coupon_list">
<section class="Coupon">
   <div class="Coupon_top">
     优惠券
   </div>
   <div class="swiper-container swiper-container1">
        <div class="swiper-wrapper " id="coupon" >
			<volist name="coupon_list" id="vo">
				<div class="swiper-slide coupon" date-type ="{pigcms{$vo.coupon_id}"  data-wxcardid="{pigcms{$vo.wx_cardid}" data-tickettime="{pigcms{$vo.wx_ticket_addtime}" data-sign="{pigcms{$vo.cardsign}" data-status="{pigcms{$vo.status}" data-allow_new="{pigcms{$vo.allow_new}"> 
					
					<div class="Coupon_ntop fl">
						<div class="Coupon_ntop_span">
						  <i>￥</i><em>{pigcms{$vo.discount|floatval}</em>
						</div>
						<div class="Coupon_ntop_span1">
						  满{pigcms{$vo.order_money|floatval}元使用
						</div>
					</div>
					<div class="Coupon_nend fl">
						<div class="Coupon_ntop_span">
						{pigcms{$vo.name}
						</div>
						<div class="Coupon_ntop_span1">
						  <p>使用时间</p>
						  <p style="font-size:10px;">{pigcms{$vo.start_time|date='Y-m-d',###}至{pigcms{$vo.end_time|date='Y-m-d',###}</p>
						</div>
					</div>
					<div class="Coupon_Receive fr">立即领取</div>

				</div>
			</volist>
		</div>
	</div>
</section>
</if>
<if condition="$shop['is_book']">
<section class="groom_dishes">
	<!-- <if condition="$goods_list">
	<div class="dishes_top">推荐菜</div>
	<a href="{pigcms{:U('Foodshop/show_menu', array('store_id' => $shop['store_id']))}">
	<div class="dishes_bot">
		<volist name="goods_list" id="goods">
		<span>{pigcms{$goods['name']}</span>　
		</volist>
	</div>
	</a>
	</if>
	<div class="dishes_All">
		<a href="{pigcms{:U('Foodshop/show_menu', array('store_id' => $shop['store_id']))}">本店所有菜品</a>
	</div> -->
	<a class="dish_header clr" href="{pigcms{:U('Foodshop/show_menu', array('store_id' => $shop['store_id']))}"><b class="ft">推荐商品</b><p class="fr"><span>全部商品</span> <i></i></p></a>
	<div class="photo_sroll">
		<div class="dish_content clr">
            <volist name="goods_list" id="goods">
			<a class="fl" href="javascript:void(0)">
				<dl>
					<dt><img src="{pigcms{$goods['pic_arr'][0]['url']['image']}" alt=""></dt>
					<dd>{pigcms{$goods['name']}</dd>
				</dl>
			</a>
            </volist>
		</div>
	</div>
	
</section>
</if>
<section class="Moreinfor">
	<div class="Moreinfor_top">更多信息</div>
	<div class="Moreinfor_bot">
	<ul>
		<php>$phones = explode(' ', $shop['phone']);</php>
		<li class="pho">
		<volist name="phones" id="phone">
		<a href="tel:{pigcms{$phone}">{pigcms{$phone}</a>
		</volist>
		</li>
		<!-- <li class="place">{pigcms{$shop['business_time']}</li> -->
		<li class="time"><a href="{pigcms{:U('Foodshop/addressinfo', array('store_id' => $shop['store_id']))}">{pigcms{$shop['adress']} <span class="fr more"></span></a></li>
	</ul>
	</div>
</section>

<!-- 新增html -->
<if condition="$reply_list">
<section class="details_evaluate">
	<div class="Moreinfor_top">评价</div>
	<ul style="padding-bottom:10px;">
	<volist name="reply_list" id="reply">
	<li>
		<div class="details_evaluate_top clr">
			<div class="evaluate_left">
				<h3>{pigcms{$reply['nickname']}</h3>
				<span>{pigcms{$reply['add_time']}</span>
			</div>
			<div class="evaluate_right">
				<div class="atar_Show">
					<p></p>
				</div>
				<span><i>{pigcms{$reply['score']|floatval}</i>分</span>
			</div>  
		</div>
		<div class="details_evaluate_end">{pigcms{$reply['comment']}</div>
		<if condition="$reply['merchant_reply_content']">
			<div class="details_evaluate_end" style="font-size:12px;background:#F8F8F8;padding:10px;"><span style="color:#FF532A;">商家回复：</span><span>{pigcms{$reply['merchant_reply_content']}</span></div>
		</if>
	</li>
	</volist>
	</ul>
	<if condition="$reply_count gt 3">
	<div class="stillmore">
		<a href="{pigcms{:U('Foodshop/reply', array('store_id' => $shop['store_id']))}" class="clr">
			<span>查看全部评价（{pigcms{$reply_count}）</span><em></em>
		</a>
	</div>
	</if>
</section>
</if>
<!-- 新增html end -->
<script>
	<if condition="!$is_wexin_browser">
		var is_wexin_browser = false;
	<else />
		var is_wexin_browser = true;
	</if>
	var is_new =Number("{pigcms{$isnew}");
</script>
<script type="text/javascript">
//图片横向滑动width
	var dish_length=$('.dish_content>a').length;
	$('.dish_content').width(dish_length*110);
function close_swiper(){
	$('.albumContainer').remove();
}
//奖票滑动
var myswiper5 = new Swiper('.swiper-container1', {direction : 'horizontal',  freeMode : true, freeModeMomentumRatio : 0.5, slidesPerView : 'auto'});
$(function(){
	<if condition="count($wap_index_slider) gt 1">
	if($('.swiper-container2').size() > 0){
		var mySwiper2 = $('.swiper-container2').swiper({
			pagination:'.swiper-pagination2',
			loop:true,
			grabCursor: true,
			paginationClickable: true,
			simulateTouch:false
		});
	}
	</if>
	
	$(".m10_list").each(function(){
		var height = $(this).height();
		if (height > 221) {
			$(this).css({"height":"221px","overflow":"hidden"})
		} else {
			$(this).find(".more").hide();
		}
	});
	$(".m10_list .more").click(function(){
		$(this).hide();
		$(this).parents(".m10_list").css("height","auto");
	});
	$(".m10_list .more i").text(parseFloat($(".m10_list dd").length) - 2);
	
	// 清除边框
	$(".navBox_list dl").each(function(){
		$(this).find("dd.Menulink").last().css("border-bottom","none");
	});
	
	$('#coupon .coupon ').click(function(event) {
		var tmp= $(this);
		  $.ajax({
		 url: "{pigcms{:U('Systemcoupon/ajax_check_login')}",
                type: 'POST',
                dataType: 'json',
                data: {isnew:$(tmp).data('allow_new')},
                success:function(data){
					
					
				 if(data.error_code>0){
                     layer.open({
                        content: data.msg
                        ,btn: ['我知道了']
                      });

                     if(data.error_code==1){

                        if(!is_wexin_browser){
                          window.location.href="{pigcms{:U('Login/index')}";
                        }else{
                          window.location.href="{pigcms{:U('My/index')}";
                        }
                     }
                }else{
					
					
						if($(this).data('wxcardid')!=''&&tmp.data('status')==1 && is_wexin_browser){	
							 layer.open({
								title:[
									'是否同步到微信优惠券',
									'background-color:#8DCE16; color:#fff;'
								],
								content: '同步微信优惠券，您可以在微信卡包中查看'
								,btn: ['是', '否']
								,yes: function(index){
									var cardlist = [];
									var i = 0;
									
										var wxcardid = $(tmp).data('wxcardid');
										var sign = $(tmp).data('sign');
										var tickettime = $(tmp).data('tickettime');
										var status = $(tmp).data('status');
										var allow_new = Number($(tmp).data('allow_new'));
										if(wxcardid!=''&&status==1&&(is_new&&allow_new||!allow_new)){
											cardlist.push({'cardId':wxcardid,'cardExt':'{"code": "", "openid": "", "timestamp":"'+tickettime+'","signature":"'+sign+'"}'});
										}
									
									if(cardlist){
										wx.addCard({
											cardList: 
											cardlist
										 ,
											success: function (res) {
												layer.open({
													content: '已成功同步到微信卡包'
													,btn: ['我知道了']
												  });
											
												window.location.reload();
											}
										});
									}
								}
								,no:function(index){
									ajax_had_pull(tmp)
								}
							});
							
						}else{
							ajax_had_pull(tmp)
						}
				}
		    }
			});
		
				
	});

	$('.headshop').click(function(){
		var album_more = $(this).data('pics');
		if (album_more == '') return false;
		var album_array = album_more.split(',');
		if(/(micromessenger)/.test(navigator.userAgent.toLowerCase())){
			wx.previewImage({
				current:album_array[0],
				urls:album_array
			});
		}else{
			var album_html = '<div class="albumContainer" style="display:block;">';
			album_html += '<div class="swiper-container">';
			album_html += '<div class="swiper-wrapper">';
			$.each(album_array,function(i,item){
				album_html += '<div class="swiper-slide">';
				album_html += '<img src="'+item+'"/>';
				album_html += '</div>';
			});
			album_html += '</div>';
			album_html += '<div class="swiper-pagination"></div><div class="swiper-close" onclick="close_swiper()">X</div>';
			album_html += '</div>';
			album_html += '</div>';
			$('body').append(album_html);
			mySwiper_big = $('.albumContainer .swiper-container').swiper({
				pagination:'.albumContainer .swiper-pagination',
				loop:true,
				grabCursor: true,
				paginationClickable: true
			});
		}
	});
	$(".evaluate_right").each(function() {
	   $(this).find("p").css("width", parseFloat($(this).find("i").text()) * 18);
	});
});
function ajax_had_pull(tmp){
		$.ajax({
			url: "{pigcms{:U('My_card/had_pull')}",
			type: 'POST',
			dataType: 'json',
			data: {coupon_id:tmp.attr('date-type')},
			success:function(data){
				if(data.error_code){
					if(data.error_code == 1 || data.error_code==2){
						 layer.open({
						content: data.msg
						,btn: ['我知道了']
					  });
						 window.location.reload();
					}else if(data.error_code==3){
						 layer.open({
						content: data.msg
						,btn: ['我知道了']
					  });
						tmp.find('.rob').html('<div class="Already Alreadyon"></div>');
						tmp.find('.right').addClass('rightEnd');
						tmp.find('.collar').html('已抢光');
					}else {
						 layer.open({
						content: data.msg
						,btn: ['我知道了']
					  });
						
					}
				}else{
					tmp.find('.rob').html('<div class="Already"></div>');
					tmp.find('.right').addClass('rightAl');
					tmp.find('.collar').html('已领取');
					
					 layer.open({
						content: data.msg
						,btn: ['我知道了']
					  });
				}
			}
		});
	}
</script>
<script type="text/javascript">
	window.shareData = {
		"moduleName":"Foodshop",
		"moduleID":"0",
		"imgUrl": "{pigcms{$shop['image']}", 
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Foodshop/shop', array('store_id' => $shop['store_id']))}",
		"tTitle": "{pigcms{$shop['name']} - {pigcms{$config.site_name}",
		"tContent": "{pigcms{$shop['name']}"
	};
</script>
{pigcms{$shareScript}



<if condition="$is_app_browser">
    <script type="text/javascript">
        window.lifepasslogin.shareLifePass("{pigcms{$shop['name']} - {pigcms{$config.site_name}","{pigcms{$shop['name']}","{pigcms{$shop['image']}","{pigcms{$config.site_url}{pigcms{:U('Foodshop/shop', array('store_id' => $shop['store_id']))}");
    </script>
</if>
</body>
</html>