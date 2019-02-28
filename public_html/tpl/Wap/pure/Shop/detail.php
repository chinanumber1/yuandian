<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta name="format-detection" content="telephone=no"/>
<meta charset="utf-8">
<title>{pigcms{$now_goods['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mall.css"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/swiper.min.css"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/scan.css"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/shop_cart.css"/>
<!-- <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script> -->
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script type="text/javascript">var noAnimate = true;</script>
<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
<script>var goods_list = '{pigcms{$goods_detail}', reply_url = "{pigcms{:U('Mall/reply', array('goods_id' => $now_goods['goods_id']))}", save_url = "{pigcms{:U('Shop/scan_shop_cart', array('store_id' => $store['store_id']))}", shopReplyUrl = "{pigcms{$config.site_url}/index.php?g=Index&c=Reply&a=ajax_get_list&order_type=3&parent_id={pigcms{$store['store_id']}";var open_extra_price =Number("{pigcms{$config.open_extra_price}"); var extra_price_name ="{pigcms{$config.extra_price_alias_name}";var store_id =Number("{pigcms{$store['store_id']}");</script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
<script type="text/javascript" src="{pigcms{$static_path}js/sanbuygoods.js"></script>
</head>
<body style="max-width: 640px">
	<section class="details"> 
		<div class="homepage">
			<div class="swiper-container swiper-container1">
				<div class="swiper-wrapper">
					<volist name="now_goods['pic_arr']" id="vo">
					<div class="swiper-slide" ><a href="javascript:;"><img src="{pigcms{$vo['url']}" width=100% height="0"></a></div>
					</volist>
				</div> 
				<div class="swiper-pagination"></div>    
			</div>
		</div>
		<div class="title title_data" data-is_seckill="{pigcms{$now_goods['is_seckill_price']|intval}" data-pack="{pigcms{$now_goods['packing_charge']|floatval}" data-price="{pigcms{$now_goods['price']|floatval}" data-name="{pigcms{$now_goods['name']}" data-stock="{pigcms{$now_goods['stock_num']}" data-goods_id="{pigcms{$now_goods['goods_id']}" data-store_id="{pigcms{$store['store_id']}" data-extra_pay_price="{pigcms{$now_goods['extra_pay_price']}" data-max_num="{pigcms{$now_goods['max_num']}" data-o_price="{pigcms{$now_goods['old_price']|floatval}">
			<h2>{pigcms{$now_goods['name']}</h2>
			<div class="title_Price">
				<span class="tuftof">￥<i>{pigcms{$now_goods['price']|floatval}<if condition="$config.open_extra_price eq 1 AND $now_goods.extra_pay_price gt 0">+{pigcms{$now_goods.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if></i></span>
				<span class="tuftofdw">/{pigcms{$now_goods['unit']}</span>
				<!-- <del>原价{pigcms{$now_goods['old_price']|floatval}</del> -->
				<if condition="$now_goods['is_seckill_price']">
				<del>原价{pigcms{$now_goods['old_price']|floatval}</del>
				<span class="Discount">限时优惠<php>if ($now_goods['max_num'] > 0) { echo " ,限" . $now_goods['max_num'] . $now_goods['unit'] . "优惠";}</php></span>
                <elseif condition="$now_goods['max_num'] gt 0" />
                <span class="Discount">限购{pigcms{$now_goods['max_num']}{pigcms{$now_goods['unit']}</span>
				</if>
				<if condition="$now_goods['stock_num'] eq -1">
				<span class="Surplus fr">库存充足</span>
				<else />
				<span class="Surplus fr">剩余{pigcms{$now_goods['stock_num']}</span>
				</if>
			</div>
			
		</div>
	</section>
	<if condition="!(empty($now_goods['spec_list']) AND empty($now_goods['properties_list']))">
	<section class="Choice"> 
		<a href="javascript:void(0)" class="clr show_detail">
			<div class="fl">
				<span class="opt">选择</span> 
				<span class="namic">规格/属性</span>
			</div>
		</a>
	</section>
	</if>
	<if condition="$store['coupon_list']">
	<section class="Disyh clr"> 
		<span class="fl">优惠</span>
		<div class="p75">
			<volist name="store['coupon_list']['system_newuser']" id="sn">
			<div class="benefit clr">
				<i class="fl shou">首</i><p> 平台首单满{pigcms{$sn['money']}元减{pigcms{$sn['minus']}元</p>
			</div>
			</volist>
			
			<volist name="store['coupon_list']['system_minus']" id="sm">
			<div class="benefit clr">
				<i class="fl jian">减</i><p> 平台优惠满{pigcms{$sm['money']}元减{pigcms{$sm['minus']}元</p>
			</div>
			</volist>
			
			<if condition="$store['coupon_list']['discount']">
			<div class="benefit clr">
				<i class="fl zhe">折</i><p> 商家{pigcms{$store['coupon_list']['discount']}折优惠</p>
			</div>
			</if>
			
			<volist name="store['coupon_list']['newuser']" id="n">
			<div class="benefit clr">
				<i class="fl shou2">首</i><p> 商家首单满{pigcms{$n['money']}元减{pigcms{$n['minus']}元</p>
			</div>
			</volist>
			<volist name="store['coupon_list']['minus']" id="m">
			<div class="benefit clr">
				<i class="fl hui">惠</i><p> 商家优惠满{pigcms{$m['money']}元减{pigcms{$m['minus']}元</p>
			</div>
			</volist>
		</div>
		<a href="javascript:void(0)" class="more"><span>展开更多</span></a>
	</section>
	</if>
	
	<section class="evaluate evaluates">
		<dl>
			<dt class="clr">
				<span class="fl">店铺评价(0)</span>
				<span class="fr">查看全部<i></i></span>
			</dt>
		</dl>
	</section> 



	<section class="Imagetext">
		<div class="Imagetext_top">图文详情</div>
		<div class="textcon">{pigcms{$now_goods['des']}</div>
	</section>


	<section class="purchase clr">
		<div class="fl purchase_left clr">
			<div class="fl store qrcodeBtn" >
				<i></i>
				<p>继续购物</p>
			</div>
			<div class="fl car" onClick="location.href='{pigcms{:U('Shop/scan_shop_cart')}&store_id={pigcms{$store.store_id}&mer_id={pigcms{$store.mer_id}'">
				<i></i>
				<p>购物车</p>
				<em class="Number"></em>
			</div>
		</div>
		<div class="fr purchase_right clr">
			<a href="javascript:void(0);" class="fl cars Choice_n" data-store_id="{pigcms{$store['store_id']}" data-type="add">加入购物车</a>
			<a href="javascript:void(0);" class="fl shops Choice_n" data-store_id="{pigcms{$store['store_id']}" data-type="buy">立即购买</a> 
		</div>
	</section>

	<!-- 规格选项 -->
	<section class="Speci">
		<div class="title">
			<h2>{pigcms{$now_goods['name']}</h2>
			<div class="title_Price clr">
				<span class="tuftof">￥<i id="show_format_price">{pigcms{$now_goods['price']|floatval}<if condition="$config.open_extra_price eq 1 AND $now_goods.extra_pay_price gt 0">+{pigcms{$now_goods.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if></i></span>
				<!-- <span class="tuftofdw">/1束</span>
				<del>原价300</del> -->
				<if condition="$now_goods['stock_num'] eq -1">
				<span class="Surplus fl" id="show_stock_num">库存充足 </span>
				<else />
				<span class="Surplus fl" id="show_stock_num">剩余{pigcms{$now_goods['stock_num']} </span>
				</if>
                <if condition="$now_goods['is_seckill_price']">
                <span class="Discount">限时优惠<php>if ($now_goods['max_num'] > 0) { echo '<span id="showDiscount"> ,限<b id="showMax">' . $now_goods['max_num'] . "</b>" . $now_goods['unit'] . "优惠</span>";}</php></span>
                <elseif condition="$now_goods['max_num'] gt 0" />
                <span class="Discount" id="showDiscount">限购<b id="showMax">{pigcms{$now_goods['max_num']}</b>{pigcms{$now_goods['unit']}</span>
                </if>
			</div>
			<a href="javascript:void(0)" class="del"></a>
		</div>
		<volist name="now_goods['spec_list']" id="spec">
		<div class="cations clr spec">
			<span class="fl cas_left">{pigcms{$spec['name']}</span>
			<div class="p35">
				<ul data-id="{pigcms{$spec['id']}" data-name="{pigcms{$spec['name']}" data-num="1" data-type="spec">
					<volist name="spec['list']" id="vo">
					<li data-id="{pigcms{$vo['id']}" data-name="{pigcms{$vo['name']}">{pigcms{$vo['name']}</li>
					</volist>
				</ul>
			</div>
		</div>
		</volist>
		<volist name="now_goods['properties_list']" id="property">
		<div class="cations clr property">
			<span class="fl cas_left">{pigcms{$property['name']}</span>
			<div class="p35">
				<ul data-id="{pigcms{$property['id']}" data-name="{pigcms{$property['name']}" data-num="{pigcms{$property['num']}" data-type="properties">
					<volist name="property['val']" id="po" key="pi">
					<li data-id="{pigcms{$pi}" data-name="{pigcms{$po}">{pigcms{$po}</li>
					</volist>
				</ul>
			</div>
		</div>
		</volist>
		<div class="cations clr">
			<span class="fl cas_left">数量</span>
			<div class="wrap">
				<div class="plus clr">
					<a href="javascript:void(0)" class="jian">-</a>
					<input type="text" value="1" readonly="readonly">
					<a href="javascript:void(0)" class="jia">+</a>
				</div>
			</div>
		</div>
		<!-- 新增 -->
		<div class="purchase_right  purchase_right100 clr">
			<a href="javascript:void(0);" class="fl cars two_btn" style="display:none;" data-store_id="{pigcms{$store['store_id']}" data-type="add">加入购物车</a>
			<a href="javascript:void(0);" class="fl shops two_btn" style="display:none;" data-store_id="{pigcms{$store['store_id']}" data-type="buy">立即购买</a>
			<a href="javascript:void(0);" class="fl shops shops100 one_btn" style="display:none;" data-store_id="{pigcms{$store['store_id']}" data-type="add">确定</a>
		</div>

	</section>
	<div class="mask"></div>
	<div class="mask2"></div>

	<div class="business">
		<if condition="$kf_url">
		<div class="manufactor">
			<a href="{pigcms{$kf_url}">联系客服</a>
		</div>
		</if>
		<div class="factor">
			<ul>
				<li><h2>拨打电话</h2></li>
				<volist name="store['phone']" id="phone">
				<li><a href="tel:{pigcms{$phone}">{pigcms{$phone}</a></li>
				</volist>
			</ul>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			if(window.__wxjs_is_wkwebview){
					window.addEventListener("pageshow", function(){
						 goodsNumber = 0;

						init_goods_menu();
					},false);
				}else{
					init_goods_menu();
				}
		}); 
		$(".mask2").height($(window).height());
		$(".contactlx").click(function(){
			var tc=$(".manufactor").height()+$(".factor").height()
			$(".business").height(tc).css({"margin-top":-tc/2,"top":"50%"})
			$(".mask2").show();
		})
		$(".mask2").click(function(){
			$(this).hide();
			$(".business").css("top","-100%")
		})
		
		$('.qrcodeBtn').click(function(){
			if(motify.checkWeixin()){
				motify.log('正在调用二维码功能');
				wx.scanQRCode({
					desc:'scanQRCode desc',
					needResult:1,
					scanType:["qrCode","barCode"],
					success:function (res){
						GoodsbyScan('{pigcms{$store['store_id']}',res)
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
	</script>

</body>
<script id="shopReplyTpl" type="text/html">
{{# for(var i = 0, len = d.length; i < len; i++){ }}
<dd>
	<div class="title clr">
		<h2 class="fl clr">
			<img src="{{# if(d[i].avatar!= ''){}}{{ d[i].avatar }}{{# }else{ }}/static/images/portrait.jpg{{# } }}" width=20 height=20 class="fl">
			<span class="fl">{{ d[i].nickname }}</span>
		</h2>
		<div class="atar_Show fr">
			<p tip="{{ d[i].score }}"></p>
		</div>
	</div>
	<div class="content">{{ d[i].comment }}</div>
	{{# if(d[i].goods && false){ }}
		{{# var tmpGoods = d[i].goods; }}
		<div class="attr">点赞商品：
		{{# for(var k in tmpGoods){ }}
			{{ tmpGoods[k] }} 
		{{# } }}
		</div>
	{{# } }}
	<div class="attr clr">
		<span>发表日期：{{ d[i].add_time_hi }}</span>
	</div>
	{{# if(d[i].merchant_reply_time != '0'){ }}
	<div class="date">
		<div class="data_n">
			<div class="data_top clr">
				<h2 class="fl">商家回复</h2>
				<span class="fr">{{ d[i].merchant_reply_time_hi }}</span>
			</div>
			<p>{{ d[i].merchant_reply_content }}</p>
		</div>
	</div>
	{{# } }}
</dd>
{{# } }}
</script>
<script type="text/javascript">
window.shareData = {
			"moduleName":"Mall",
			"moduleID":"0",
			"imgUrl": "{pigcms{$now_goods['pic_arr'][0]['url']}",
			"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Mall/detail', array('goods_id' => $now_goods['goods_id']))}",
			"tTitle": "{pigcms{$now_goods['name']}",
			"tContent": "{pigcms{$now_goods['name']}"
};
</script>
{pigcms{$shareScript}
</html>