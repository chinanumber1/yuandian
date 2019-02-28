<!DOCTYPE html>
<html lang="en">
<head>
    <title>分类信息订单</title>
<include file="Public:gift_header" />
</head>
<style>
.ormx_pro { padding: .1rem 0; border-bottom: 1px solid #ddd; -webkit-box-sizing: border-box; box-sizing: border-box; display: -webkit-box; display: -webkit-flex; display: flex; background:#fff;padding:.5rem .2rem .2rem .5rem; }
.ormx_pro img { display: block; padding-right: .1rem; width:80px; height:80px}
.ormxpm_price { left: 0; bottom: 0; font-size: 22px; color: #ff472e; font-weight: 700 }
</style>
<body>

<form action="{pigcms{:U('buy')}" method="post" onSubmit="return chk_submit()">
<section class="order">
    <section class="list-block">
        <ul>
			<li>
                <a href="{pigcms{:U('My/adress',array('classify_userinput_id'=>$_GET['classify_userinput_id'],'current_id'=>$now_user_adress['adress_id']))}" class="item-link item-content" class="item-link item-content">
                    <div class="item-inner">
                        <div class="item-title">更换送货地址</div>
                    </div>
                </a>
            </li>
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
        <div class="ormx_pro">
            <if condition='$classify_userinput_detail["imgs"]'><img src="{pigcms{$classify_userinput_detail["imgs"][0]}"></if>
            <div class="ormxp_mess">
                <p class="ormxpm_tit">{pigcms{:msubstr($classify_userinput_detail['description'] , 0 , 30)}</p>
                <p class="ormxpm_price"><i>￥</i>{pigcms{$classify_userinput_detail["assure_money"]}
                </p>
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
    </section>
</section>

<section class="ftHeight"></section>
<footer class="deatilFtBtn">
    <div class="number tc fl">
        <span class="dib tit">合计：</span><div class="jf-bidNum dib">
			<p><font class="payment_money">{pigcms{$classify_userinput_detail["assure_money"]}</font> <em>元</em></p>
        
        </div>
    </div>
    <div class="buyNow fl">
		<input type="hidden" name="classify_userinput_id" value="{pigcms{$_GET['classify_userinput_id']}" />
		<input type="hidden" name="is_source" value="1" />
		<input type="hidden" name="address_id" value="{pigcms{$_GET['adress_id'] ? $_GET['adress_id'] : $now_user_adress['adress_id']}" />
		<input type="hidden" name="num" value="1" />
		<button type="submit">立即付款</button>
    </div>
</footer>
</form>

<include file="Public:gift_footer" />
</body>
</html>