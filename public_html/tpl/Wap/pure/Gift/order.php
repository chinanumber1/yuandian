<!DOCTYPE html>
<html lang="en">
<head>
    <title>{pigcms{$config.gift_alias_name}订单</title>
<include file="Public:gift_header" />
</head>
<script type="text/javascript" language="javascript">
		var exchange_type = {pigcms{$_GET['exchange_type']};
		var exchange_limit_num = {pigcms{$gift_detail.exchange_limit_num};
		var payment_pure_integral = {pigcms{$gift_detail['payment_pure_integral']};
		var payment_integral = {pigcms{$gift_detail['payment_integral']};
		var payment_money = {pigcms{$gift_detail['payment_money']};
		if(exchange_limit_num){
			var total_sku = exchange_limit_num;
		}else{
			var sku = parseInt({pigcms{$gift_detail.sku});
			var sale_count = parseInt({pigcms{$gift_detail.sale_count})
			var total_sku = sku - sale_count;
		}
	</script>
<body>

<form action="{pigcms{:U('buy')}" method="post" id="forms" onSubmit="return chk_submit()">
<section class="order">
    <section class="list-block">
        <ul>
		<if condition="!empty($now_user_adress)">
            <li>
                <a href="{pigcms{:U('My/adress',array('gift_id'=>$_GET['gift_id'],'current_id'=>$now_user_adress['adress_id'],'now_sku'=>$_GET['now_sku'],'exchange_type'=>$_GET['exchange_type']))}" class="item-link item-content">
                    <div class="item-inner">
                        <div class="item-title">请点击选择送货地址</div>
                    </div>
                </a>
            </li>
		<else />
			<li>
                <a href="{pigcms{:U('My/adress',array('gift_id'=>$_GET['gift_id'],'current_id'=>$now_user_adress['adress_id'],'now_sku'=>$_GET['now_sku'],'exchange_type'=>$_GET['exchange_type']))}" class="item-link item-content">
                    <div class="item-inner">
                        <div class="item-title">请点击添加送货地址</div>
                    </div>
                </a>
            </li>
		</if>
        </ul>
    </section>

	<if condition="!empty($now_user_adress)">
		<section class="address" <if condition='!empty($_GET["address_id"])'>style="display:none"<else />style="display:block"</if>>
			<ul>
				<li>
					<div class="wrap">
						<div class="textDesc">
							<div class="rowCell">
								<span class="fr">{pigcms{$now_user_adress['phone']}</span>
								<span>收货人：{pigcms{$now_user_adress['name']}</span>
							</div>
							<div class="rowCell">
								收货地址：{pigcms{$now_user_adress['province_txt']} {pigcms{$now_user_adress['city_txt']} {pigcms{$now_user_adress['area_txt']} {pigcms{$now_user_adress['adress']} {pigcms{$now_user_adress['detail']}
							</div>
						</div>
					</div>
				</li>
			</ul>
		</section>
		
		<section class="address" <if condition='empty($_GET["address_id"])'>style="display:none"<else />style="display:block"</if>>
			<ul>
				<li>
					<div class="wrap">
						<div class="textDesc">
							<div class="rowCell">
								<span class="fr">{pigcms{$now_user_adress['phone']}</span>
								<span>收货人：{pigcms{$now_user_adress['name']}</span>
							</div>
							<div class="rowCell">
								收货地址：{pigcms{$now_user_adress['province_txt']} {pigcms{$now_user_adress['city_txt']} {pigcms{$now_user_adress['area_txt']} {pigcms{$now_user_adress['adress']} {pigcms{$now_user_adress['detail']}
							</div>
						</div>
					</div>
				</li>
			</ul>
		</section>
	</if>
	
    <section class="orderDetails">
        <div class="item orderItem">
            <div class="wrap clearfix">
                <div class="fl i-pic">
                    <img src="{pigcms{$gift_detail['wap_pic_list'][0]['url']}">
                </div>
                <div class="ofh desc">
                    <div class="wrap pr">
                        <h2>{pigcms{$gift_detail['gift_name']}</h2>
						
						<if condition='$_GET["exchange_type"] eq 0'>
							<div class="ofh">
								<p>{pigcms{$gift_detail['payment_pure_integral']} <em>{pigcms{$config['score_name']}</em></p>
							</div>
						<elseif condition='$_GET["exchange_type"] eq 1'/>
							<div class="ofh">
								<p>{pigcms{$gift_detail['payment_integral']} <em>{pigcms{$config['score_name']}</em> + {pigcms{$gift_detail['payment_money']} <em>元</em></p>
							</div>
						</if>
                    </div>
                </div>
            </div>
        </div>
        <div class="numCtrl">
            <div class="wrap clearfix">
                <div class="jf-bidNum fr">
                    <span class="reduce">-</span><input type="text" value="{pigcms{$_GET['now_sku']}" id="now_sku"><span class="plus">+</span>
                </div>
                <span class="odTit">数量</span>
            </div>
        </div>
        <div class="orderRow">
            <ul>
                <li>
                    <div class="wrap">
                        <p class="fr">快递（免邮）</p>
                        <span>配送方式</span>
                    </div>
                </li>
            </ul>
        </div>

	<div class="orderRow">
            <ul>
                <li>
                    <div class="wrap">
						<p>配送时间</p>
                        <p class="radios">
							<label><input type="radio" name="delivery_type" value="1" checked="checked"/>&nbsp;&nbsp;&nbsp;&nbsp;<span>工作日、双休日与假日均可送货</span></label>
							<label><input type="radio" name="delivery_type" value="2" />&nbsp;&nbsp;&nbsp;&nbsp;<span>只工作日送货</span></label>
							<label><input type="radio" name="delivery_type" value="3" />&nbsp;&nbsp;&nbsp;&nbsp;<span>双休日、假日送货：周六至周日</span></label>
							<label><input type="radio" name="delivery_type" value="4" />&nbsp;&nbsp;&nbsp;&nbsp;<span>白天没人，其它时间送货</span></label>
						</p>
                    </div>
                </li>
            </ul>
        </div>
    </section>
</section>

<section class="ftHeight"></section>
<footer class="deatilFtBtn">
    <div class="number tc fl">
        <span class="dib tit">合计：</span><div class="jf-bidNum dib">
		<if condition='$_GET["exchange_type"] eq 0'>
			<p><font class="payment_pure_integral">{pigcms{$gift_detail['payment_pure_integral'] * $_GET['now_sku']}</font> <em>{pigcms{$config['score_name']}</em></p>
		<elseif condition='$_GET["exchange_type"] eq 1' />
			<p><font class="payment_integral">{pigcms{$gift_detail['payment_integral'] * $_GET['now_sku']}</font> <em>{pigcms{$config['score_name']}</em> + <font class="payment_money">{pigcms{$gift_detail['payment_money'] * $_GET['now_sku']}</font> <em>元</em></p>
		</if>
        
        </div>
    </div>
    <div class="buyNow fl">
		<input type="hidden" name="exchange_type" value="{pigcms{$_GET['exchange_type']}" />
		<input type="hidden" name="gift_id" />
		<input type="hidden" name="address_id" id="address_id" />
		<input type="hidden" name="num" />
		<input type="hidden" name="memo" />
		<button type="button" id="submits">立即兑换</button>
    </div>
</footer>
</form>
<include file="Public:gift_footer" />

<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
<script type="text/javascript" language="javascript">
	$('#submits').click(function(){
		layer.open({
			content: '确认进行兑换？',
			btn: ['确认', '取消'],
			shadeClose: true,
			yes: function(){
				var address_id = "{pigcms{$_GET['adress_id']}";
				if(!address_id){
					address_id = "{pigcms{$now_user_adress['adress_id']}";
				}
				
				$('input[name="address_id"]').val(address_id);
				
				var num = $('#now_sku').val();
				$('input[name="num"]').val(num);
				$('input[name="memo"]').val("{pigcms{$_GET['memo']}")
				$('input[name="gift_id"]').val("{pigcms{$_GET['gift_id']}");
				$('#forms').removeAttr('onSubmit');
				$('#forms').submit();
			}
		});
		return false;
	});

	// 获取地址栏参数
    function GetQueryString(name)
    {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return unescape(r[2]);
        return null;
    }

	$('.plus').click(function(){
		var now_num = $('#now_sku').val();
		now_num++;
		$('#now_sku').val(now_num);
        var exchange_type = GetQueryString("exchange_type");
        if (0 === exchange_type || '0' === exchange_type) {
            var price = parseInt("{pigcms{$gift_detail['payment_pure_integral']}");
            $('.payment_pure_integral').html(price * now_num);
        } else {
            var integral_price = parseInt("{pigcms{$gift_detail['payment_integral']}");
            var money_price = parseInt("{pigcms{$gift_detail['payment_money']}");
            var total_inegral = integral_price * now_num;
            var total_money = money_price * now_num;
            $('.payment_integral').html(total_inegral);
            $('.payment_money').html(total_money);
        }
	});
	
	
	$('.reduce').click(function(){
		var now_num = $('#now_sku').val();
		if(now_num > 1){
			now_num--;
			$('#now_sku').val(now_num);
            var exchange_type = GetQueryString("exchange_type");
            var price = parseInt("{pigcms{$gift_detail['payment_pure_integral']}");
            if (0 === exchange_type || '0' === exchange_type) {
                var price = parseInt("{pigcms{$gift_detail['payment_pure_integral']}");
                $('.payment_pure_integral').html(price * now_num);
            } else {
                var integral_price = parseInt("{pigcms{$gift_detail['payment_integral']}");
                var money_price = parseInt("{pigcms{$gift_detail['payment_money']}");
                var total_inegral = integral_price * now_num;
                var total_money = money_price * now_num;
                $('.payment_integral').html(total_inegral);
                $('.payment_money').html(total_money);
            }
		}
	});
	
	if(/(pigcmso2oreallifeapp)/.test(navigator.userAgent.toLowerCase()) || (/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase()))){
		$('.item-link.item-content').click(function(){
			var address_id = $('#address_id').val() == '' ? '0' : $('#address_id').val();
			if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
				$('body').append('<iframe src="pigcmso2o://getUserAddress/'+address_id+'" style="display:none;"></iframe>');
			}else{
				window.lifepasslogin.getUserAddress(address_id);
			}
			return false;
		});
	}
	
function callbackUserAddress(address){
	// alert(address);
	var addressArr = address.split('<>');
	$('#address_id').val(addressArr[0]);
	<php>
		$tmpGet = $_GET;
		unset($tmpGet['adress_id']);
	</php>
	// alert("{pigcms{:U('order',$tmpGet)}&adress_id="+addressArr[0]);
	window.location.href = "{pigcms{:U('order',$tmpGet)}&adress_id="+addressArr[0];
}
</script>
</body>
</html>