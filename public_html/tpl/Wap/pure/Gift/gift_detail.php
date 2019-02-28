<!DOCTYPE html>
<html lang="en">
<head>
    <title>{pigcms{$config.gift_alias_name}详情</title>
<include file="Public:gift_header" />
	<script type="text/javascript" language="javascript">
		var exchange_limit_num = {pigcms{$gift_detail.exchange_limit_num};
		if(exchange_limit_num){
			var total_sku = exchange_limit_num;
		}else{
			var sku = parseInt({pigcms{$gift_detail.sku});
			var sale_count = parseInt({pigcms{$gift_detail.sale_count})
			var total_sku = sku - sale_count;
		}
	</script>
	<style type="text/css">
	.detailsSwiper .swiper-slide img{width: 100%;height:100% }
	.detail .desc .img-p img,.descMain img{ max-width:100%}
	</style>
</head>
<body>
<div class="lodingCover">
    <div class="spinner">

	<volist name='gift_detail["wap_pic_list"]' id='gift_image_info'>
        <div class="rect{pigcms{$i}"></div>
	</volist>
    </div>
</div>
<section class="scroll detailsSwiper">
    <!-- Swiper -->
    <div class="swiper-container swiper-container-banner" id="banner_hei">
        <div class="swiper-wrapper">
			<volist name='gift_detail["wap_pic_list"]' id='gift_image_info'>
				<div class="swiper-slide">
					<img style="height:100%;" src="{pigcms{$gift_image_info['url']}" >
					<!--div class="titles">
						<p></p>
					</div-->
				</div>
			</volist>
        </div>
        <div class="swiper-pagination swiper-pagination-banner"></div>
    </div>
</section>
<section class="detail">
    <div class="info">
        <div class="wrap pr">
			<if condition='!in_array($gift_detail["exchange_type"],array(1,2))'>
				<p class="price">{pigcms{$gift_detail['payment_pure_integral']} <em>{pigcms{$config['score_name']}</em></p>
			<else />
				<p class="price">{pigcms{$gift_detail['payment_integral']} <em>{pigcms{$config['score_name']}</em> + {pigcms{$gift_detail['payment_money']} <em>元</em></p>
			</if>

            <small class="note">重要提示：所有兑换礼品颜色随机发货</small>
            <small class="tip pa">已兑换
			<em><if condition='!empty($gift_detail["exchanged_num"])'>{pigcms{$gift_detail['exchanged_num']}<else />{pigcms{$gift_detail['sale_count']}</if>
			</em>

			件</small>
            <div class="attr">
                <div class="row">
                    <span class="fl">选择规格：</span>
                    <div class="ofh tags JSattr">
                        <div class="wraps memo">
							<volist name='gift_detail["specification"]' id='attr'>
								<a href="javascript:void(0)" <if condition='$i eq 1'>class="on"</if>>{pigcms{$attr}</a>
							</volist>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <span class="fl">兑换方式：</span>
                    <div class="ofh tags JSways">
                        <div class="wraps exchange_type">
							<if condition='$gift_detail["exchange_type"] eq 0'>
								<a href="javascript:void(0)" data-exchange-type="0" class="on">全{pigcms{$config['score_name']}</a>
							<elseif condition='$gift_detail["exchange_type"] eq 1' />
								<a href="javascript:void(0)" data-exchange-type="1" class="on">{pigcms{$config['score_name']}+现金</a>
							<else />
								<a href="javascript:void(0)" onclick="exchangeType(0)" data-exchange-type="0">全{pigcms{$config['score_name']}</a>
								<a href="javascript:void(0)" onclick="exchangeType(1)" data-exchange-type="1" class="on">{pigcms{$config['score_name']}+现金</a>
							</if>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="desc">
        <div class="hd">
            <h2>礼品详情</h2>
        </div>
        <div class="descMain">
            <div class="wrap">
                <p></p>
                <p class="img-p tc">
                    {pigcms{$gift_detail['gift_content']|html_entity_decode}
                </p>
            </div>
        </div>
    </div>

	<div class="desc">
        <div class="hd">
            <h2>发货清单</h2>
        </div>
        <div class="descMain">
            <div class="wrap">
                <p></p>
                <p class="img-p tc">
                    {pigcms{$gift_detail['invoice_content']|html_entity_decode}
                </p>
            </div>
        </div>
    </div>
</section>
<section class="ftHeight"></section>
<footer class="deatilFtBtn">
    <div class="number tc fl">
        <span class="dib tit">选择数量：</span><div class="jf-bidNum dib">
            <span class="reduce">-</span><input type="text" value="1" id="now_sku"><span class="plus">+</span>
        </div>
    </div>
    <div class="buyNow fl">
        <a href="javascript:void(0)">立即兑换</a>
    </div>
</footer>
<include file="Public:gift_footer" />
<script>
	$('#banner_hei').height($(window).width()*200/300);
    var swiper = new Swiper('.swiper-container-banner', {
        loop:true,
        autoplay: 5000,//可选选项，自动滑动
        // 如果需要分页器
        pagination: '.swiper-pagination-banner'
    });

    // 切换
    function exchangeType(type) {
        if (type == 0) {
            $html = "{pigcms{$gift_detail['payment_pure_integral']}<em>{pigcms{$config['score_name']}</em>";
            $('.price').html($html);
        } else {
            $html = "{pigcms{$gift_detail['payment_integral']} <em>{pigcms{$config['score_name']}</em> + {pigcms{$gift_detail['payment_money']} <em>元</em>";
            $('.price').html($html);
        }
    }



    $(function(){
        //如果需要赋值，请根据具体情况修改
        tagFilter(".JSattr");//选择规格
        tagFilter(".JSways");//兑换方式

       function tagFilter(obj){
           var tag=$(obj).find("a");
           tag.tap(function(){
               if($(this).hasClass("disabled") || $(this).hasClass("on") ){
                   return false;
               }else{
                   tag.removeClass("on");
                   $(this).addClass("on");
               }
           })
       }
    })



	$('.buyNow').click(function(){
		var order_url = "{pigcms{:U('order')}";
		var gift_id = "{pigcms{$gift_detail['gift_id']}";
		var now_sku = $('#now_sku').val();
		var exchange_type = $('.exchange_type>.on').attr('data-exchange-type');
		var memo = $('.memo>.on').html();

		location.href = order_url + '&gift_id=' + gift_id + '&now_sku=' + now_sku + '&exchange_type=' + exchange_type+'&memo='+memo;
	});
</script>

<script type="text/javascript">
window.shareData = {
			"moduleName":"Gift",
			"moduleID":"0",
			"imgUrl": "<if condition="$gift_detail['wap_pic_list'][0]['url']">{pigcms{$gift_detail['wap_pic_list'][0]['url']}<else/>{pigcms{$config.site_logo}</if>",
			"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Gift/gift_detail',array('gift_id'=>$_GET['gift_id']))}",
			"tTitle": "{pigcms{$gift_detail['gift_name']} - {pigcms{$config.gift_alias_name}详情",
			"tContent": "{pigcms{$config.site_name}"
};
</script>
{pigcms{$shareScript}
</body>
</html>