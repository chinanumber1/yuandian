<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<script id="listBannerSwiperTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<div class="swiper-slide">
			<a href="{{ d[i].url }}">
				<img src="{{ d[i].pic }}" alt="{{ d[i].name }}"/>
			</a>
		</div>
	{{# } }}
</script>
<script id="listSliderSwiperTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		{{# if(i%8 == 0){ }}
			<div class="swiper-slide">
				<ul class="icon-list">
		{{# } }}
					<li class="icon">
						<a href="{{ d[i].url }}">
							<span class="icon-circle">
								<img src="{{ d[i].pic }}" onerror="this.src='/static/images/empty.png'"/>
							</span>
							<span class="icon-desc">{{ d[i].name }}</span>
						</a>
					</li>
		{{# if(i != 0 && ((i+1)%8 == 0 || i+1 == len)){ }}		
				</ul>
			</div>
		{{# } }}
	{{# } }}
</script>
<script id="listRecommendTpl" type="text/html">
	<div class="recommendBox">
		{{# if(d[0]){ }}
			<div class="recommendLeft link-url" data-url="{{ d[0].url }}">
				<img src="{{ d[0].pic }}" alt="{{ d[0].name }}"/>
			</div>
		{{# } }}
		<div class="recommendRight">
			{{# if(d[1]){ }}
				<div class="recommendRightTop link-url" data-url="{{ d[1].url }}">
					<img src="{{ d[1].pic }}" alt="{{ d[1].name }}"/>
				</div>
			{{# } }}
			{{# if(d[2]){ }}
				<div class="recommendRightBottom link-url" data-url="{{ d[2].url }}">
					<img src="{{ d[2].pic }}" alt="{{ d[2].name }}">
				</div>
			{{# } }}
		</div>
	</div>
</script>
<script id="listShopTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<dd class="{{ d[i].is_mult_class == '1' ? 'link-url' : 'page-link' }}" data-url="{{# if(d[i].is_mult_class == 1){ }}?c=Shop&a=classic_shop&shop_id={{ d[i].id }}{{# }else{ }}shop-{{ d[i].id }}{{# } }}" data-url-type="openRightFloatWindow" {{# if(d[i].is_close){ }}style="opacity:0.6;"{{# } }}>
			<div class="dealcard-img imgbox">
				{{# if(d[i].isverify == 1){ }}
					<img src="./static/images/kd_rec.png" style="    width: 41px;height: 15px;position: absolute;z-index: 15;margin: 2px 0 0 0;">
				{{# } }}
				<img src="{{ d[i].image }}" alt="{{ d[i].name }}" width="90" height="60">
				{{# if(d[i].is_close){ }}<div class="closeTip">休息中</div>{{# } }}
			</div>
			<div class="dealcard-block-right">
				<div class="brand">{{ d[i].name }}<em class="location-right">{{# if(user_long != '0'){ }}{{ d[i].range }}{{# } }}</em></div>
				<div class="title {{# if(!d[i].delivery){ }}pick{{# } }}">
					<span class="star">
						{{#
							var tmpScore = parseFloat(d[i].star);
							if(tmpScore>0){
								for(var tmpI=0;tmpI<5;tmpI++){ if(tmpScore >= tmpI+1){ }}<i class="full"></i>{{# }else if(tmpScore > tmpI){ }}<i class="half"></i>{{# }else{ }}<i></i>{{# } }
							}else{
						}}
							<i class="full"></i><i class="full"></i><i class="full"></i><i class="half"></i><i></i>
						{{#
							}
						}}
					</span>
                    {{# if (d[i].month_sale_count > 0) { }}
                    <span>已售{{ d[i].month_sale_count }}单</span>
                    {{# } else if (d[i].is_new) { }}
                    <span>新店上市</span>
                    {{# } else { }}
                    <span>　</span>
                    {{# } }}
					{{# if(d[i].delivery){ }}
                        {{# if(d[i].deliver_type != 5){ }}
                        <em class="location-right">{{ d[i].delivery_time }}{{ d[i].delivery_time_type }}</em>
                        {{# } }}
					{{# }else{ }}
						<em class="location-right ziti">门店自提</em>
					{{# } }}
				</div>
				{{# if(d[i].delivery){ }}
					<div class="price">
						<span>起送价 ￥{{ d[i].delivery_price }}</span><span class="delivery">{{# if(d[i].delivery_money==0){ }}免配送费 {{# }else{ }} 配送费 ￥{{ d[i].delivery_money }} {{# } }}</span>
						{{# if(d[i].delivery_system){ }}
							<em class="location-right">{{ deliverName }}</em>
						{{# }else{ }}
                            {{# if(d[i].deliver_type == 5){ }}
							<em class="location-right merchant_send">快递配送</em>
                            {{# } else { }}
							<em class="location-right merchant_send">商家配送</em>
                            {{# } }}
						{{# } }}
					</div>
				{{# } }}
			</div>
				{{# if(d[i].coupon_count > 0){ }}
					<div class="coupon {{# if(d[i].coupon_count > 2){ }}hasMore{{# } }}">
						<ul>
							{{# var tmpCouponList = parseCoupon(d[i].coupon_list,'array');  }}
							{{# if(tmpCouponList['invoice']){ }}
								<li><em class="merchant_invoice"></em>{{ tmpCouponList['invoice'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['discount']){ }}
								<li><em class="merchant_discount"></em>{{ tmpCouponList['discount'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['minus']){ }}
								<li><em class="merchant_minus"></em>{{ tmpCouponList['minus'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['newuser']){ }}
								<li><em class="newuser"></em>{{ tmpCouponList['newuser'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['delivery']){ }}
								<li><em class="delivery"></em>{{ tmpCouponList['delivery'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['system_minus']){ }}
							<li><em class="system_minus"></em>{{ tmpCouponList['system_minus'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['system_newuser']){ }}
								<li><em class="system_newuser"></em>{{ tmpCouponList['system_newuser'] }}</li>
							{{# } }}
                            {{# console.log(tmpCouponList); }}
							{{# if(tmpCouponList['isDiscountGoods']){ }}
								<li><em class="isDiscountGoods"></em>{{ tmpCouponList['isDiscountGoods'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['isdiscountsort']){ }}
								<li><em class="merchant_discount"></em>{{ tmpCouponList['isdiscountsort'] }}</li>
							{{# } }}
						</ul>
						{{# if(d[i].coupon_count > 2){ }}
							<div class="more">{{ d[i].coupon_count }}个活动</div>
						{{# } }}
					</div>
				{{# } }}
		</dd>
	{{# } }}
</script>

<script id="shopProductLeftBarTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<dd id="shopProductLeftBar-{{ d[i].cat_id }}" data-cat_id="{{ d[i].cat_id }}" {{# if(i==0){ }}class="active"{{# } }}>{{ d[i].cat_name }}</dd>
	{{# } }}
</script>
<script id="shopProductRightBarTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		{{# if(d[i].product_list.length > 0){ }}
			<dd id="shopProductRightBar-{{ d[i].cat_id }}" data-cat_id="{{ d[i].cat_id }}">
				<div class="cat_name">{{ d[i].cat_name }}　{{# if(d[i].sort_discount){ }}<div class="cat_discount">{{ d[i].sort_discount }}折</div><div class="cat_discount bred">折扣不同享</div>{{# } }}</div>
				<ul>
					{{# for(var j = 0, jlen = d[i].product_list.length; j < jlen; j++){ }}
						<li class="product_{{ d[i].product_list[j].product_id }}" data-unit="{{ d[i].product_list[j].unit }}" data-product_id="{{ d[i].product_list[j].product_id }}" data-product_price="{{ d[i].product_list[j].product_price }}" data-product_name="{{ d[i].product_list[j].product_name }}" data-stock="{{ d[i].product_list[j].stock }}" data-packing_charge="{{ d[i].product_list[j].packing_charge }}"  data-extra_price="{{ d[i].product_list[j].extra_pay_price }}" data-extra_price_name="{{ d[i].product_list[j].extra_pay_price_name }}" data-max_num="{{ d[i].product_list[j].max_num }}" data-min_num="{{ d[i].product_list[j].min_num }}" data-o_price="{{ d[i].product_list[j].o_price }}" data-is_seckill="{{ d[i].product_list[j].is_seckill_price }}" data-limit_type="{{ d[i].product_list[j].limit_type }}">
							<div class="position_img">
								{{# if(d.length >= 3 && jlen>=3){ }}
									<img src="{pigcms{$static_public}images/blank.gif" data-original="{{ d[i].product_list[j].product_image }}" class="lazy"/>
								{{# }else{ }}
									<img src="{{ d[i].product_list[j].product_image }}"/>
								{{# } }}
							</div>
							<div class="product_text">
								<div class="title">{{ d[i].product_list[j].product_name }}</div>
								{{# if (d[i].product_list[j].product_sale > 0) { }}
								<div class="sale">已售{{ d[i].product_list[j].product_sale }}{{ d[i].product_list[j].unit }} 好评{{ d[i].product_list[j].product_reply }}</div>
								{{# } else if (d[i].product_list[j].is_new) { }}
								<div class="sale">新品上市 好评{{ d[i].product_list[j].product_reply }}</div>
								{{# } else { }}
								<div class="sale"> 好评{{ d[i].product_list[j].product_reply }}</div>
								{{# } }}
								{{# if(d[i].product_list[j].has_format){ }}
									<div class="price">￥{{ d[i].product_list[j].product_price }}  {{# if(d[i].product_list[j].spec_value){ }}起{{# } }}{{# if(d[i].product_list[j].extra_pay_price>0&&open_extra_price==1){ }}+{{ d[i].product_list[j].extra_pay_price }}{{ d[i].product_list[j].extra_pay_price_name }}{{# } }}
                                    {{# if (d[i].product_list[j].min_num > 1) { }}
                                    <span style="color: #333;text-decoration: none;">{{ d[i].product_list[j].min_num }}{{ d[i].product_list[j].unit }}起购</span>
                                    {{# } }}
                                    </div>
								{{# }else{ }}
									<div class="price">￥{{ d[i].product_list[j].product_price }}{{# if(d[i].product_list[j].is_seckill_price){ }}<span>￥{{ d[i].product_list[j].o_price }}</span>{{# } }}{{# if(d[i].product_list[j].extra_pay_price>0&&open_extra_price==1){ }}+{{ d[i].product_list[j].extra_pay_price }}{{ d[i].product_list[j].extra_pay_price_name }}{{# } }}
                                    {{# if (d[i].product_list[j].min_num > 1) { }}
                                    <span style="color: #333;text-decoration: none;">{{ d[i].product_list[j].min_num }}{{ d[i].product_list[j].unit }}起购</span>
                                    {{# } }}
                                    </div>
								{{# } }}
                                
								{{# if(d[i].product_list[j].is_seckill_price && d[i].product_list[j].limit_type == 0){ }}
									<div class="skill_discount" style="margin-top: 5px;">限时优惠{{# if(d[i].product_list[j].max_num > 0){ }},限{{ d[i].product_list[j].max_num }}{{ d[i].product_list[j].unit }}优惠{{# } }}</div>
								{{# } else if (d[i].product_list[j].max_num > 0) { }}
                                    <div class="skill_discount" style="margin-top: 5px;">限购{{ d[i].product_list[j].max_num }}{{ d[i].product_list[j].unit }}</div>
                                {{# } }}
							</div>
							{{# if(d[i].product_list[j].has_format){ }}
								<div class="product_btn" >
									可选规格
								</div>
							{{# }else{ }}
								<div class="product_btn plus"></div>
								<div class="bgPlusBack"></div>
								<div class="bgMinBack"></div>
							{{# } }}
						</li>
					{{# } }}
				</ul>
			</dd>
		{{# } }}
	{{# } }}
</script>

<script id="searchGoodsTpl" type="text/html">		
	<div id="GoodsSearchBar">
	<ul>
		{{# for(var i = 0 ; i < d.length; i++){ }}
				<li class="product_{{ d[i].goods_id }}" data-unit="{{ d[i].unit }}" data-product_id="{{ d[i].goods_id }}" data-product_price="{{ d[i].price }}" data-product_name="{{ d[i].name }}" data-stock="{{ d[i].stock_num }}" data-packing_charge="{{ d[i].packing_charge }}"  data-max_num="{{ d[i].max_num }}" data-o_price="{{ d[i].o_price }}" data-is_seckill="{{ d[i].is_seckill_price }}" data-limit_type="{{ d[i].limit_type }}">
					<div class="position_img">
						{{# if(d.length >= 3 && i>=3){ }}
							<img src="{pigcms{$static_public}images/blank.gif" data-original="{{ d[i].image }}" class="lazy"/>
						{{# }else{ }}
							<img src="{{ d[i].image }}"/>
						{{# } }}
					</div>
					<div class="product_text">
						<div class="title" style="padding-left: 0px;">{{ d[i].name }}</div>
						{{# if (d[i].sell_mouth > 0) { }}
						<div class="sale">已售{{ d[i].sell_mouth }}{{ d[i].unit }} 好评{{ d[i].reply_count }}</div>
						{{# } else if (d[i].is_new) { }}
						<div class="sale">新品上市 好评{{ d[i].reply_count }}</div>
						{{# } else { }}
						<div class="sale"> 好评{{ d[i].reply_count }}</div>
						{{# } }}
						{{# if(d[i].has_format){ }}
							<div class="price">￥{{ d[i].price }} 起{{# if(d[i].extra_pay_price>0&&open_extra_price==1){ }}+{{ d[i].extra_pay_price }}{{ d[i].extra_pay_price_name }}{{# } }}</div>
						{{# }else{ }}
							<div class="price">￥{{ d[i].price }}{{# if(d[i].is_seckill_price){ }}<span>￥{{ d[i].o_price }}</span>{{# } }}{{# if(d[i].extra_pay_price>0&&open_extra_price==1){ }}+{{ d[i].extra_pay_price }}{{ d[i].extra_pay_price_name }}{{# } }}</div>
						{{# } }}
						{{# if(d[i].is_seckill_price && d[i].limit_type == 0){ }}
							<div class="skill_discount" style="margin-top: 5px;">限时优惠{{# if(d[i].max_num > 0){ }},限{{ d[i].max_num }}{{ d[i].unit }}优惠{{# } }}</div>
						{{# } else if (d[i].max_num > 0) { }}
							<div class="skill_discount" style="margin-top: 5px;">限购{{ d[i].max_num }}{{ d[i].unit }}</div>
						{{# } }}
					</div>
					{{# if(d[i].has_format){ }}
						<div class="product_btn" >
							可选规格
						</div>
					{{# }else{ }}
						<div class="product_btn plus addCon" style="z-index:10"></div>
						<div class="bgPlusBack"></div>
						<div class="bgMinBack"></div>
					{{# } }}
				</li>
		{{# } }}
			</ul>
	</div>
</script>

<script id="shopProductTopBarTpl" type="text/html">
		<li data-cat_id="0" class="active">全部分类</li>
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<li data-cat_id="{{ d[i].cat_id }}" data-name="{{ d[i].cat_name }}" data-discount="{{ d[i].sort_discount }}">{{ d[i].cat_name }}{{# if(d[i].sort_discount){ }}<span>({{ d[i].sort_discount }}折优惠)</span>{{# } }}</li>
	{{# } }}
</script>
<script id="shopProductBottomBarTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		{{# if(d[i].product_list.length > 0){ }}
			{{# for(var j = 0, jlen = d[i].product_list.length; j < jlen; j++){ }}
				<li class="product_{{ d[i].product_list[j].product_id }} product_cat_{{ d[i].cat_id }}" data-unit="{{ d[i].product_list[j].unit }}" data-product_id="{{ d[i].product_list[j].product_id }}" data-product_price="{{ d[i].product_list[j].product_price }}" data-product_name="{{ d[i].product_list[j].product_name }}" data-stock="{{ d[i].product_list[j].stock }}" data-packing_charge="{{ d[i].product_list[j].packing_charge }}" data-extra_price="{{ d[i].product_list[j].extra_pay_price }}" data-extra_price_name="{{ d[i].product_list[j].extra_pay_price_name }}" data-max_num="{{ d[i].product_list[j].max_num }}" data-min_num="{{ d[i].product_list[j].min_num }}" data-o_price="{{ d[i].product_list[j].o_price }}" data-is_seckill="{{ d[i].product_list[j].is_seckill_price }}" data-limit_type="{{ d[i].product_list[j].limit_type }}"  data-user_buy_num="{{ d[i].product_list[j].user_buy_num }}">
					<div class="position_img">
						{{# if(d.length >= 3){ }}
							<img src="{pigcms{$static_public}images/blank.gif" data-original="{{ d[i].product_list[j].product_image }}" class="lazy"/>
						{{# }else{ }}
							<img src="{{ d[i].product_list[j].product_image }}"/>
						{{# } }}
					</div>
					<div class="product_text">
						<div class="title">{{ d[i].product_list[j].product_name }}</div>
						{{# if(d[i].product_list[j].is_seckill_price && d[i].product_list[j].limit_type == 0){ }}
							<div class="skill_discount" style="margin-top: -21px;">限时优惠{{# if(d[i].product_list[j].max_num > 0){ }},限{{ d[i].product_list[j].max_num }}{{ d[i].product_list[j].unit }}优惠{{# } }}</div>
						{{# } else if (d[i].product_list[j].max_num > 0) { }}
                            <div class="skill_discount" style="margin-top: -21px;">限购{{ d[i].product_list[j].max_num }}{{ d[i].product_list[j].unit }}</div>
                        {{# } }}
                        
                        {{# if (d[i].product_list[j].min_num > 1) { }}
                              <div style="color: #333;text-decoration: none;font-size:12px;margin-top: -21px;float: right;">{{ d[i].product_list[j].min_num }}{{ d[i].product_list[j].unit }}起购</div>
                        {{# } }}
						{{# if (d[i].product_list[j].product_sale > 0) { }}
						<div class="sale">已售{{ d[i].product_list[j].product_sale }}{{ d[i].product_list[j].unit }} 好评{{ d[i].product_list[j].product_reply }}</div>
						{{# } else if (d[i].product_list[j].is_new) { }}
						<div class="sale">新品上市 好评{{ d[i].product_list[j].product_reply }}</div>
						{{# } else { }}
						<div class="sale"> 好评{{ d[i].product_list[j].product_reply }}</div>
						{{# } }}
						{{# if(d[i].product_list[j].has_format){ }}
							<div class="price">￥{{ d[i].product_list[j].product_price }} 起{{# if(d[i].product_list[j].extra_pay_price>0&&open_extra_price==1){ }}+{{ d[i].product_list[j].extra_pay_price }}{{ d[i].product_list[j].extra_pay_price_name }}{{# } }}</div>
						{{# }else{ }}
							<div class="price">￥{{ d[i].product_list[j].product_price }}{{# if(d[i].product_list[j].is_seckill_price){ }}<span>￥{{ d[i].product_list[j].o_price }}</span>{{# } }}{{# if(d[i].product_list[j].extra_pay_price>0&&open_extra_price==1){ }}+{{ d[i].product_list[j].extra_pay_price }}{{ d[i].product_list[j].extra_pay_price_name }}{{# } }}</div>
						{{# } }}
					</div>
					{{# if(d[i].product_list[j].has_format){ }}
						<div class="product_btn">
							可选规格
						</div>
					{{# }else{ }}
						<div class="product_btn plus"></div>
					{{# } }}
				</li>
			{{# } }}
		{{# } }}
	{{# } }}
</script>
<script id="listCategoryListTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<li data-cat_id="{{ d[i].cat_id }}" data-cat_url="{{ d[i].cat_url }}" {{# if(d[i].son_list && d[i].son_list.length > 0){ }}data-has-sub="true"{{# }else{ }} onclick="list_location($(this));return false;" {{# } }} class="listCat-{{ d[i].cat_url }} {{# if(d[i].son_list && d[i].son_list.length > 0){ }}right-arrow-point-right{{# } }} {{# if(i == 0){ }}active{{# } }}">
			<span data-name="{{ d[i].cat_name }}">{{ d[i].cat_name }}</span>
			{{# if(d[i].son_list && d[i].son_list.length > 0){ }}
				<span class="quantity"><b></b></span>		
				<div class="sub_cat hide">
					<ul class="dropdown-list sub-list">
						<li class="listCat-{{ d[i].cat_url }} isSon" data-cat_id="{{ d[i].cat_id }}" data-cat_url="{{ d[i].cat_url }}" onclick="list_location($(this));return false;"><div><span class="sub-name" data-name="{{ d[i].cat_name }}">全部</span></div></li>
						{{# for(var j = 0, jlen = d[i].son_list.length; j < jlen; j++){ }}
							<li class="listCat-{{ d[i].son_list[j].cat_url }} isSon" data-cat_id="{{ d[i].son_list[j].cat_id }}" data-cat_url="{{ d[i].son_list[j].cat_url }}" onclick="list_location($(this));return false;"><div><span class="sub-name" data-name="{{ d[i].son_list[j].cat_name }}">{{ d[i].son_list[j].cat_name }}</span></div></li>
						{{# } }}
					</ul>
				</div>
			{{# } }}
		</li>
	{{# } }}
</script>
<script id="listSortListTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<li data-sort_url="{{ d[i].sort_url }}" {{# if(i == 0){ }}class="active"{{# } }} onclick="list_location($(this));return false;"><span data-name="{{ d[i].name }}">{{ d[i].name }}</span><em></em></li>
	{{# } }}
</script>
<script id="listTypeListTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<li data-type_url="{{ d[i].type_url }}" {{# if(i == 0){ }}class="active"{{# } }} onclick="list_location($(this));return false;"><span data-name="{{ d[i].name }}">{{ d[i].name }}</span><em></em></li>
	{{# } }}
</script>
<script id="listAddressListTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<dd data-long="{{ d[i].long }}" data-lat="{{ d[i].lat }}" data-name="{{ d[i].street }}" data-id="{{ d[i].id }}">
			<div class="name">{{ d[i].street }} {{ d[i].house }}</div>
			<div class="desc">{{ d[i].name }} {{ d[i].phone }}</div>
		</dd>
	{{# } }}
</script>
<script id="productFormatTpl" type="text/html">
	{{# for(var i in d){ }}
		<div class="row clearfix">
			<div class="left">{{ d[i].name }}</div>
			<div class="right fl">
				<ul>
					{{# var k = 0; for(var j in d[i].list){ }}
						<li class="fl {{# if(k == 0){ }}active{{# } }}" data-spec_list_id="{{ d[i].list[j].id }}"  data-spec_id="{{ d[i].list[j].sid}}">{{ d[i].list[j].name }}</li>
					{{#  k++; } }}
				</ul>
			</div>
		</div>
	{{# } }}
</script>
<script id="productPropertiesTpl" type="text/html">
	{{# for(var i in d){ }}
		<div class="row clearfix productProperties_{{ d[i].id }}" data-label_name="{{ d[i].name }}" data-num="{{ d[i].num }}">
			<div class="left">{{ d[i].name }}</div>
			<div class="right fl">
				<ul>
					{{# var k = 0; for(var j in d[i].val){ }}
						<li class="fl {{# if(k == 0 && d[i].num == 1){ }}active{{# } }}" data-label_list_id="{{ i }}" data-label_id="{{ j }}">{{ d[i].val[j] }}</li>
					{{#  k++; } }}
				</ul>
			</div>
		</div>
	{{# } }}
</script>
<script id="productSwiperTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<div class="swiper-slide">
			<img src="{{ d[i].url }}"/>
		</div>
	{{# } }}
</script>
<script id="productCartBoxTpl" type="text/html">
	<dl>
		<dt class="clearfix">购物车<div id="shopProductCartDel">清空</div></dt>
		{{#
			var packCharge = 0;
			for(var i in d){
				packCharge += d[i].productPackCharge*d[i].count;
		}}
			<dd class="clearfix cartDD" data-unit="{{ d[i].unit }}" data-product_id="{{ d[i].productId }}" data-product_price="{{ d[i].productPrice }}" data-product_name="{{ d[i].productName }}" data-stock="{{ d[i].productStock }}" data-packing_charge="{{ d[i].productPackCharge }}" data-extra_price="{{ d[i].productExtraPrice }}" data-extra_price_name="{{ d[i].productExtraPriceName }}" data-max_num="{{ d[i].maxNum }}"  data-min_num="{{ d[i].minNum }}" data-o_price="{{ d[i].oldPrice }}" data-is_seckill="{{ d[i].isSeckill }}" data-limit_type="{{ d[i].limit_type }}">
				<div class="cartLeft {{# if(d[i].productParam.length > 0){ }}hasSpec{{# } }}">
					<div class="name">{{ d[i].productName }}</div>
					{{# if(d[i].productParam.length > 0){ }}
						{{# 
							var tmpParam = [];
							for(var j in d[i].productParam){
								if(d[i].productParam[j].type == 'spec'){
									tmpParam.push(d[i].productParam[j].name);
								}else{
									for(var k in d[i].productParam[j].data){
										tmpParam.push(d[i].productParam[j].data[k].name);
									}
								}
							}
							var tmpParamStr = tmpParam.join(' ');
						}}
						<div class="spec" data-product_id="{{ i }}">{{ tmpParamStr }}</div>
					{{# } }}
				</div>
				<div class="cartRight">
					<div class="product_btn plus cart"></div>
					<div class="product_btn number cart productNum-{{ i }}">{{ d[i].count }}</div>
					<div class="product_btn min cart"></div>
					<div class="price">￥{{ d[i].productPrice }}{{#  if(d[i].productExtraPrice>0&&open_extra_price ){ }}+{{ d[i].productExtraPrice }}{{ d[i].productExtraPriceName }}{{# } }}</div>
				</div>
			</dd>
		{{# 
			}
			if(packCharge > 0){
		}}
			<dd>{{ nowShop.store.pack_alias }}&nbsp;<font color="red">￥<span id="packChargeCount">{{ parseFloat(packCharge.toFixed(2)) }}</span></font></dd>
		{{#		
			}
		}}
        <dd id="spell_tip"><a href="javasript:void(0);">商品如需要分开打包，请使用多人点单</a></dd>
	</dl>
</script>
<script id="shopReplyTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<dd>
			<div class="avatar">
				<img src="{{# if(d[i].avatar!= ''){}}{{ d[i].avatar }}{{# }else{ }}/static/images/portrait.jpg{{# } }}"/>
			</div>
			<div class="right">
				<div class="nickname">{{ d[i].nickname }}<div class="time">{{ d[i].add_time_hi }}</div></div>
				<div class="star">
					店铺&nbsp;&nbsp;{{# for(var j=1;j<=5;j++){ }}{{# if(d[i].score >= j){ }}<i class="full"></i>{{# }else{ }}<i></i>{{# } }}{{# } }}{{# if (d[i].deliver_score > 0) { }}配送{{ d[i].deliver_score }}星{{# } }}
				</div>
				<div class="content">{{ d[i].comment }}</div>
				{{# if(d[i].goods){ }}
					{{# var tmpGoods = d[i].goods; }}
					<div class="recommend clearfix">
						{{# for(var k in tmpGoods){ }}
							<div>{{ tmpGoods[k] }}</div>
						{{# } }}
					</div>
				{{# } }}
				{{# if(d[i].merchant_reply_time != '0'){ }}
					<div class="reply">
						<div class="title">店铺回复:<div class="time">{{ d[i].merchant_reply_time_hi }}</div></div>
						<div class="reply_content">{{ d[i].merchant_reply_content }}</div>
					</div>
				{{# } }}
			</div>
		</dd>
	{{# } }}
</script>