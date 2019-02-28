<html lang="zh-CN">
 <head> 
  <meta charset="utf-8" /> 
  <title>扫码购物</title> 
  <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width" /> 
  <meta http-equiv="pragma" content="no-cache" /> 
  <meta name="apple-mobile-web-app-capable" content="yes" /> 
  <meta name="apple-touch-fullscreen" content="yes" /> 
  <meta name="apple-mobile-web-app-status-bar-style" content="black" /> 
  <meta name="format-detection" content="telephone=no" /> 
  <meta name="format-detection" content="address=no" /> 
  <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/shop_cart.css" /> 
  <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/scan_shop_cart.css" /> 
    <script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script type="text/javascript">var noAnimate = true;</script>
<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
 </head>
 <body> 
  <section class="public pageSliderHide"> 
   <div class="return link-url" data-url-type="openLeftWindow" data-url="{pigcms{:U('Shop/index')}" data-closewebview="true"></div> 
   <div class="content">
    扫码购物
   </div> 
  </section> 
  <section class="homepage"> 
   <div class="h44"></div> 
   <section class="scanning"> 
    <div class="sao qrcodeBtn"> 
     <i></i>
     <span>扫一扫购物</span> 
    </div> 
    <div class="sweep"></div> 
    <div class="estate"> 
     <h4>{pigcms{$now_store.name}</h4> 
     <ul class="clr"> 
      <li class="member">会员号：<if condition="$card_info"><span id="cartinfo-card_id">ID:{pigcms{$card_info.id}</span> <a href="javascript:;" id="get_card">查看&gt;</a><else /><span id="cartinfo-card_id">无</span> <a href="javascript:;" id="get_card">去领卡&gt;</a></if></li> 
      <li class="balance" style="font-size:12px">余额：<span>￥<span id="cartinfo-card_money">{pigcms{$now_user.now_money|floatval}</span></span></li> 
     </ul> 
    </div> 
   </section> 
   <section class="tit_list" > 
    <ul>  
	
	<volist name="product_list.goods_list" id="vo">
     <li data-goods_id="{pigcms{$vo.goods_id}" data-name="{pigcms{$vo.name}" data-unit="{pigcms{$vo.unit}" data-num="{pigcms{$vo.num}" data-price="{pigcms{$vo.price}" data-number="{pigcms{$vo.number}"  data-stock="{pigcms{$vo.stock_num}" data-maxnum="{pigcms{$vo.max_num}" data-isseckill="{pigcms{$vo.is_seckill_price}"> 
      <div class="w50"> 
       <h2>{pigcms{$vo.name}</h2> 
       <p>{pigcms{$vo.number}</p> 
      </div> 
      <div class="w50 clr"> 
       <span class="w40">{pigcms{$vo.num}</span> 
       <span class="w60">￥{pigcms{$vo.price}</span> 
      </div> 
	  </li> 
	</volist>

     
    </ul> 
   </section> 
   <section class="membership" style="display:none"> 
    <div class="member_top"> 
     <div class="h2">
      会员卡
     </div> 

    </div> 
    <div class="member_end" > 
     <div class="mem_top">
      <span>会员信息</span>
     </div> 
     <div class="mem_end"> 
      <div class="card_info "> 
       <ul class="clr"> 
        <li> <h2>会员卡号</h2> <p id="card_info-card_id">&nbsp;</p> </li> 
        <li> <h2>实体卡号</h2> <p id="card_info-physical_id">&nbsp;</p> </li> 
        <li> <h2>会员姓名</h2> <p id="card_info-name">&nbsp;</p> </li> 
        <li> <h2>会员性别</h2> <p id="card_info-sex">&nbsp;</p> </li> 
        <li> <h2>手机号码</h2> <p id="card_info-phone">&nbsp;</p> </li> 
        <li> <h2>会员折扣</h2> <p class="c2e"><span id="card_info-discount">&nbsp;</span>折</p> </li> 
        <li> <h2>可用积分</h2> <p class="cf7" id="card_info-card_score">&nbsp;</p> </li> 
        <li> <h2>储值余额</h2> <p class="ce9">￥<span id="card_info-card_money">0</span></p> </li> 
       </ul> 
      </div> 
  
     </div> 
    </div> 
    <div class="del"></div> 
   </section> 
   <section class="popup scan_code pop_wx"> 
    <div class="h2">
     <span>用户微信扫码</span>
    </div> 
    <div class="cot"> 

     <div class="inb">
      领取或者查找自己的会员卡
     </div> 
    </div> 
    <div class="del"></div> 
   </section> 
   <div class="wx_mask"></div> 
   <section class="seek empty"> 
    <div class="within"> 
     <h2>清空订单</h2> 
     <div class="con"> 
      <div class="img"> 
       <img src="images/tct_06.jpg" /> 
      </div> 
      <div class="tit"> 
       <p class="on">重要提示</p> 
       <p class="p">清空后当前已选择的商品与会员数据将会被删除，<span class="red">确定清空吗？</span></p> 
      </div> 
      <div class="button"> 
       <div class="clr p20"> 
        <div class="fl close shut">
         点错了
        </div> 
        <div class="fr ensure shut">
         确定清空
        </div> 
       </div> 
      </div> 
     </div> 
    </div> 
    <div class="del shut"></div> 
   </section> 
   <div class="bot_balance clr pageSliderHide"> 
    <div class="fl w60 clr"> 
     <div class="fl joint">
      共
      <span id="totalNum">{pigcms{$product_list.total_num|intval}</span>件
     </div> 
     <div class="fl total">
      合计：￥
      <span id="totalPrice">{pigcms{$product_list.total_price|floatval}</span>
     </div> 
    </div> 
    <div class="fl w40 jiesuan link-url" data-url="{pigcms{:U('Shop/scan_confirm_order')}&store_id={pigcms{$_GET['store_id']}">
     结算
    </div> 
   </div> 
   <div class="jump_list pageSliderHide"> 
    <ul class="jump_ul"> 
     <li class="sp"> <a href="{pigcms{:U('My/shop_order_list')}">订单</a> </li> 
     <li class="qk"> <a href="javascript:void(0)" >清空</a> </li> 
    </ul> 
    <div class="more on">
     <span>收起</span>
    </div> 
   </div> 
  </section> 
  <section class="cable_show"> 
   <section class="hunt"> 
    <div class="hunt_input"> 
     <div class="c_input so"> 
      <input type="search" class="sp_sear" /> 
      <div class="clean">
       <div></div>
      </div> 
     </div> 
     <div class="hunt_remove">
      取消
     </div> 
    </div> 
   </section> 
   <section class="sp_foodright foods_list" style="height: 581px; border-top: 1px solid rgb(238, 238, 238);"> 
    <dl></dl> 
   </section> 
  </section> 
  <div class="mask"></div> 

  <section class="revise" data-goods_id='' data-price='' data-stock='' data-maxnum='' style="display: none;"> 
   <div class="p30"> 
    <div class="rev_top"> 
     <div class="p75" style="padding-left:0;"> 
      <h2 class="rev_name"></h2> 
      <p class="rev_number"></p> 
     </div> 
    </div> 
    <div class="rev_ul"> 
     <ul> 
      <li class="clr"> 
       <div class="fl">
        单价：
       </div> 
       <div class="fr rev_price">
        
       </div> </li> 
      <li class="clr"> 
       <div class="fl">
        数量：
       </div> 
       <div class="fr clr"> 
        <a href="javascript:void(0)" class="fl jian">-</a> 
        <input class="fl rev_input rev_num"  type="tel" value="" /> 
        <a href="javascript:void(0)" class="fl jia">+</a> 
       </div> </li> 
      <li class="clr"> 
       <div class="fl">
        总额：
       </div> 
       <div class="fr rev_total">
       
       </div> </li> 
     </ul> 
    </div> 
    <div class="sib clr"> 
     <div class="del del_this">
      删除
     </div> 
    </div> 
   </div> 
   <div class="dels"></div> 
  </section> 

  <script type="text/javascript">
  var  cookie_index = 'shop_cart',cookie_buy_index = 'buy_shop_cart',noAnimate=true,store_id =Number('{pigcms{$_GET['store_id']}');
    	//展开收起
		$(".jump_list .more").click(function(){
			if($(".jump_ul").is(":hidden")){
				$(".jump_ul").slideDown(),$(this).find("span").text("收起"),$(this).addClass("on");
			}else{
				$(".jump_ul").slideUp(),$(this).find("span").text("展开"),$(this).removeClass("on"); 
			}
		});
    var wheight=$(window).height();
    $('.tit_list').height(wheight-222);
		cookie_index +='_'+store_id;
		$.cookie(cookie_index,'')
		$('.tit_list li').click(function(e){
			var name = $(this).data('name')
			var num = $(this).data('num')
			var price = $(this).data('price')
			var unit = $(this).data('unit')
			var number = $(this).data('number')
			var goods_id = $(this).data('goods_id')
			var stock = $(this).data('stock')
			var maxNum = $(this).data('maxnum')
			var isSeckill = $(this).data('isseckill')
		
			$('.rev_name').html(name)
			$('.rev_num').val(num)
			$('.rev_price').html(price+'元/'+unit)
			$('.revise').data('goods_id',goods_id);
			$('.revise').data('price',price);
			$('.revise').data('stock',stock);
			$('.revise').data('maxnum',maxNum);
			$('.revise').data('isseckill',isSeckill);
			$('.rev_total').html('￥'+Number(price)*Number(num).toFixed(2))
			$('.mask').show();
			$('.revise').show();
			$('.revise .dels,.mask').click(function(e){
				$('.revise').hide();
				$('.mask').hide();
				window.location.reload()
			});
			
			
			
			$('.revise .del_this').click(function(e){
				var  tmp_cart_arr = {};
				for(var i = 0; i<40; i++){
					var cart_content = $.cookie(cookie_index + '_' + i);
					if(cart_content!=null){
						cart_content = $.parseJSON(cart_content);
						
						$.each(cart_content,function(index,val){
							if(val.productId == goods_id){
								  delete cart_content[index]
							}
						})
						
						tmp_cart_arr = cart_content;
						
						$.cookie(cookie_index + '_' + i,JSON.stringify(tmp_cart_arr));
						$.cookie(cookie_buy_index + '_' + i,JSON.stringify(tmp_cart_arr));
					}
				}	
				window.location.reload()
			});
		
		});
		
		$('.qk').click(function(){
			for(var i = 0; i<40; i++){
				$.cookie(cookie_index + '_' + i, null);
			}
			motify.log('清除成功');
			window.location.reload();
		})
		$('.rev_ul a').click(function(){
			var now_good_id = $('.revise').data('goods_id');
			var now_price = $('.revise').data('price');
			var now_stock = $('.revise').data('stock');
			var now_maxNum = $('.revise').data('maxnum');
			var now_isSeckill = $('.revise').data('isseckill');
			console.log(now_maxNum)
			if($(this).hasClass("jia")){
				if(parseInt($('.rev_input').val())<now_stock ||now_stock==-1 ){
					var number = parseInt($('.rev_input').val())+1;
				}
			}else{
				var number = parseInt($('.rev_input').val());
				if(number>1){
					number--;
				}
			};
			
			var flag_edit_stock = true;
			if (now_maxNum > 0 && now_maxNum < number) {
				if (now_isSeckill) {
					motify.log('每单可享受' + now_maxNum + '份限时优惠价，超出恢复原价');
				} else {
					motify.log('每单限购' + now_maxNum + '份');
					flag_edit_stock = false;
				}
			}
        
        
			if (now_stock != -1 && number > now_stock) {
				motify.log('库存不足，不能购买！');
				flag_edit_stock = false;
			}
			if(flag_edit_stock){
				$('.rev_input').val(number);
				$('.rev_total').html('￥'+Number(now_price)*Number(number).toFixed(2))
				var  tmp_cart_arr = {};
				for(var i = 0; i<40; i++){
					var cart_content = $.cookie(cookie_index + '_' + i);
					if(cart_content!=null){
						cart_content = $.parseJSON(cart_content);
						console.log(cart_content) 
						$.each(cart_content,function(index,val){
							if(val.productId == now_good_id){
								cart_content[index]['count'] = number
							}
						})
						
						tmp_cart_arr = cart_content;
						
						$.cookie(cookie_index + '_' + i,JSON.stringify(tmp_cart_arr));
						$.cookie(cookie_buy_index + '_' + i,JSON.stringify(tmp_cart_arr));
					}
				}		
			}
			
		});
		
		
		$('#get_card').click(function(){
			$.post('{pigcms{:U('My_card/ajax_get_card')}',{store_id:'{pigcms{$store_id}'},function(date){
				if(date.status==1){
					var card =date.info;
					
					$('#card_info-card_id').html(card.id)
					$('#card_info-physical_id').html(card.physical_id?card.physical_id:'&nbsp')
					card.nickname && $('#card_info-name').html(card.nickname?card.nickname:'&nbsp')
					if(card.sex==1){
						
					$('#card_info-sex').html('男')
					}else if(card.sex==2){
						$('#card_info-sex').html('女')
					}else{
						$('#card_info-sex').html('未知')
					}
					$('#card_info-phone').html(card.phone?card.phone:'&nbsp')
					$('#card_info-discount').html(card.discount?card.discount:'&nbsp')
					$('#card_info-card_score').html(card.card_score?card.card_score:'&nbsp')
					$('#card_info-card_money').html(card.card_money?card.card_money:'&nbsp')
					$('.mask').show();
					$('.membership').show();
					
					
				}else{
					
					motify.log(date.info);
				}
			},'json')
		})
		$('.mask').click(function(){
			$('.mask').hide();
			$('.membership').hide();
		})
		
		var cart_date = {};
		
		$('.qrcodeBtn').click(function(){
			if(motify.checkWeixin()){
				motify.log('正在调用二维码功能');
				wx.scanQRCode({
					desc:'scanQRCode desc',
					needResult:1,
					scanType:["qrCode","barCode"],
					success:function (res){
						GoodsbyScan('{pigcms{$store_id}',res)
					},
					error:function(res){
						motify.log('微信返回错误！请稍后重试。',5);
					},
					fail:function(res){
						motify.log('无法调用二维码功能');
					}
				});
			}else{
				motify.log('您不是微信访问，无法使用二维码功能');
			}
		});
		
		function GoodsbyScan(store_id,result){
			if(result.resultStr.indexOf("http")>-1){
				location.href=result.resultStr
			}else{
				var res_arr = result.resultStr.split(',');
				
				$.post("{pigcms{:U('Shop/scanGood')}", {'store_id':store_id, 'good_id':res_arr[1]}, function(response){
					
					if(typeof(response.url)!='undefined'){
						location.href=response.url 
					}else{
						alert(response.info)
					}
				},'json');
			}
		}
		function outputObj(obj) {  
		var description = "";  
		for (var i in obj) {  
			description += i + " = " + obj[i] + "\n";  
		}  
		alert(description);  
	}  
    </script> 
 </body>
 {pigcms{$hideScript}
</html>