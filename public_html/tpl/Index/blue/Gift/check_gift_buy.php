<include file="Public:gift_header"/>
<section class="breadNav">
    <div class="w1200">
        <!--div class="crumbs">
            <a href="#">全部</a>
            <a href="#">2016年05月11日</a>
            <a href="#">无形资产</a>
        </div-->
    </div>
</section>
<section class="mainSection">

    <div class="w1200">
	<form action="{pigcms{:U('order')}" method="post" onsubmit="return check_submit()">
        <div class="myOrder">
            <div class="payOption">
                <div class="adressOption">
                    <h2>收货地址</h2>
                    <div class="adrrList">
                        <ul class="clearfix">
						<volist name='adress_list' id='address' >
                            <li class="JSaddress <if condition='$address.default eq 1'>on</if>" data-address-id='{pigcms{$address.adress_id}'>
                                <div class="wrap pr">
                                    <h3>{pigcms{$address.name}</h3>
                                    <p>{pigcms{$address.phone}</p>
                                    <p>{pigcms{$address.province_txt} {pigcms{$address.city_txt} {pigcms{$address.area_txt}</p>
                                    <p>{pigcms{$address.adress}</p>

                                    <!--a href="##" class="change">
                                        修改
                                    </a-->
                                </div>
                            </li>
                         </volist>
                        </ul>
                    </div>
                </div>
				
				<if condition='$gift_detail["exchange_type"] eq 1'>
					<!--div class="payment row clearfixl">
						<span class="fl title">支付方式</span>
						<div class="fl option">
							<label class="wx"><input type="radio" checked name="pay_type" value="1" /><img src="{pigcms{$static_path}gift/images/palceholder/weixin.png">微信</label>
							<label class="al"><input type="radio" name="pay_type" value="2" /><img src="{pigcms{$static_path}gift/images/palceholder/alipay.png">支付宝</label>
						</div>
					</div-->
				</if>
                <div class="distribution  row clearfixl">
                    <span class="fl title">配送方式</span>
                    <div class="fl option">
                        <span>快递配送（免运费）</span>
                    </div>
                </div>
                <div class="time row clearfixl">
                    <span class="fl title">配送时间</span>
                    <div class="fl option delivery_type">
                        <em class="dib on" data-delivery-type="1">工作日、双休日与假日均可送货</em>
                        <em class="dib" data-delivery-type="2">只工作日送货</em>
                        <em class="dib" data-delivery-type="3">双休日、假日送货：周六至周日</em>
                        <em class="dib" data-delivery-type="4">白天没人，其它时间送货</em>
                    </div>
                </div>
            </div>

            <div class="itemDetail mt30">
                <div class="wrap">
                    <div class="itemsList">
                        <ul>
							
                            <li class="clearfix">
                                <div class="name" style="overflow:visible">
                                    <div class="i-pic fl">
                                        <img src="{pigcms{$gift_detail['pc_pic_list'][0]['url']}"/>
                                    </div>
                                    <div class="desc ofh">
                                        <h3>{pigcms{$gift_detail['gift_name']}</h3>
                                        <p>已选择：{pigcms{$_GET['memo']|urldecode}</p>
                                    </div>
                                </div>
                                <div class="price">
								<if condition='$gift_detail["exchange_type"] eq 0'>
									<td class="price"><p class="bonus"><span>{pigcms{$gift_detail['payment_pure_integral']} <em>{pigcms{$config['score_name']}</em></span></p></td>
								<elseif condition='$gift_detail["exchange_type"] eq 1'/>
									<td class="price"><p class="bonus"><span>{pigcms{$gift_detail['payment_integral']} <em>{pigcms{$config['score_name']}</em></span><span><em>+</em>{pigcms{$gift_detail['payment_money']} <em>元</em></span></p></td>
								</if>
                                </div>
                                <div class="quantity">
                                    <em>×{pigcms{$_GET['num'] + 0}</em>
                                </div>
                            </li>
                            
                        </ul>
                    </div>

                </div>
            </div>
            <div class="pay mt20 clearfix">
                <span class="itemQuantity">共 <em>{pigcms{$_GET['num'] + 0}</em> 件商品</span>
                <button class="fr submitBtn" type="submit">提交订单</button>
                <div class="fr total">
				<if condition='$gift_detail["exchange_type"] eq 0'>
					 <span>合计：</span><p class="bonus dib"><span>{pigcms{$gift_detail['payment_pure_integral'] * $_GET['num']} <em>{pigcms{$config['score_name']}</em></span></p>
				<elseif condition='$gift_detail["exchange_type"] eq 1'/>
					 <span>合计：</span><p class="bonus dib"><span>{pigcms{$gift_detail['payment_integral'] * $_GET['num']}  <em>{pigcms{$config['score_name']}</em></span><span><em>+</em>{pigcms{$gift_detail['payment_money'] * $_GET['num']}  <em>元</em></span></p>
				</if>
                </div>
            </div>
        </div>
		
		<input type="hidden" id="adress_id" name="address_id" value="" />
		<input type="hidden" id="pay_type" name="pay_type" value="" />
		<input type="hidden" id="delivery_type" name="delivery_type" value="" />
		<input type="hidden" id="num" name="num" value="" />
		<input type="hidden" id="gift_id" name="gift_id" value="" />
		<input type="hidden" id="memo" name="memo" value="" />
		<input type="hidden" id="exchange_type" name="exchange_type" value="" />
</form>
<!--      暂时不需要，先注释 ——yang  <div class="recommend">
            <h3>同款推荐</h3>
            <div class="reScroll customScroll">
                <div class="showBox scrollList clearfix">
                    <ul>
						<volist name='recommend_gift_list["list"]' id='gift'>
							<li>
								<div class="i-pic">
									<img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['pc_pic_list'][0]}"/>
								</div>
								<div class="desc">
									<a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}" class="fr btn">马上兑换</a>
									<div class="info">
										<h4><a href="##">{pigcms{$gift_detail['gift_name']}</a> </h4>
										<if condition='in_array($gift["exchange_type"],array(0,2))'>
											<p class="bonus"><span>{pigcms{$gift['payment_pure_integral']} <em>{pigcms{$config['score_name']}</em></span></p>
										</if>
										<if condition='in_array($gift["exchange_type"],array(1,2))'>
											<p class="bonus"><span>{pigcms{$gift['payment_integral']} <em>{pigcms{$config['score_name']}</em></span><span><em>+</em>{pigcms{$gift['payment_money']}  <em>元</em></span></p>
										</if>
									</div>
								</div>
							</li>
						</volist>
                    </ul>
                </div>
                <div class="btn">
                    <a href="javascript:;" class="prev left"></a>
                    <a href="javascript:;" class="next right"></a>
                </div>
            </div>
        </div> -->
    </div>
</section>
<include file="Public:gift_footer"/>
<script type="text/javascript" language="javascript">
function check_submit(){
	if(confirm('是否确认进行兑换？')){
		var adress_id = $('.JSaddress.on').data('address-id');
		var pay_type = $('input[name="pay_type"]').val();
		var delivery_type = $('.delivery_type>.on').data('delivery-type');
		var num = "{pigcms{$_GET['num'] + 0}";
		var gift_id = "{pigcms{$_GET['gift_id'] + 0}";
		var memo = "{pigcms{$_GET['memo']}";
		var exchange_type = "{pigcms{$_GET['exchange_type']}";

		$('#adress_id').val(adress_id);
		$('#pay_type').val(pay_type);
		$('#delivery_type').val(delivery_type);
		$('#num').val(num);
		$('#gift_id').val(gift_id);
		$('#memo').val(memo);
		$('#exchange_type').val(exchange_type);
		return true;
	}else{
		return false;
	}
	
}
</script>