var cookie_index = 'pc_mall_cart', search_type = 0, totalNum = 0;
$(function(){
    $('.logosRight ul li').click(function(e){
        $(this).addClass('active').siblings().removeClass('active');
        var text1=$(this).text();
        if(text1=="商品"){
            $('#searchkey').attr('placeholder','请输入商品名称');
        }else{
            $('#searchkey').attr('placeholder','请输入店铺名称');
        }
    });
    $('#search').click(function(){
        var key = $('#searchkey').val();
        if (key.length > 0) {
            location.href = '/mall/search/' + $('.logosRight ul li.active').data('type') + '?key=' + key;
        }
    });
    initCart();
    
    $(document).on('click', '.glyphicon-remove-circle', function(){
        var index = $(this).parents('li').data('index');
        $(this).parents('li').remove();
        initCart(index);
        typeof initHtml === "function" ? initHtml() : false;
    });
    $(document).on('click', '#goCart', function(){
        if (totalNum > 0) {
            location.href = '/mall/cart';
        }
    });
    $(window).scroll(function(e){
        if($(document).scrollTop()>$(window).height()){
            $('.returnTop').show();
            
        }else{
             $('.returnTop').hide();
        }

    });
    $('.returnTop').click(function(e){
        $("html,body").animate({scrollTop:0}, 600);
    });
});

function format_cart_html(index, name, price, image, num, detail)
{
    var html = '';
    html += '<li data-index="' + index +'">';
    html += '<div class="footPic">';
    html += '<img src="' + image + '" />';
    html += '<dl>';
    html += '<dt>' + name +'</dt>';
    html += '<dd>' + detail + num + '</dd>';
    html += '</dl>';
    html += '</div>';
    html += '<div class="footMoney">';
    html += '<span>￥' + price + '</span>';
    html += '<i class="glyphicon glyphicon-remove-circle"></i>';
    html += '</div>';
    html += '</li>';
    return html;
}

function initCart(index)
{
    var nowShopCart = $.parseJSON(window.sessionStorage.getItem(cookie_index));
    var goodsNumber = 0, goodsCartMoney = 0, goods_price_list = [], goods_index_list = [], goodsCartPackCharge = 0;
    var cart_goods_html = '', mallCart = [];
    for (var i in nowShopCart) {
        if (nowShopCart[i] != null && nowShopCart[i].count > 0) {
            var detail_name = '', goodsCartKey = nowShopCart[i].productId, pre = '';
            if (nowShopCart[i]['productParam'].length) {
                for (var pi in nowShopCart[i]['productParam']) {
                    if (nowShopCart[i]['productParam'][pi].type == 'spec') {
                        goodsCartKey += '_s_' + nowShopCart[i]['productParam'][pi].id;
                        detail_name += pre + nowShopCart[i]['productParam'][pi].name;
                        pre = ',';
                    } else {
                        if (nowShopCart[i]['productParam'][pi]['data'].length) {
                            for (var di in nowShopCart[i]['productParam'][pi]['data']) {
                                goodsCartKey += '_v_' + nowShopCart[i]['productParam'][pi]['data'][di].id;
                                detail_name += pre + nowShopCart[i]['productParam'][pi]['data'][di].name;
                                pre = ',';
                            }
                        }
                    }
                }
            }
            if (pre == ',') {
                detail_name = detail_name + ' * ';
            }
            if (nowShopCart[i].productParam != '') {
                nowShopCart[i].productExtraPrice = 0;
            }
            if (typeof index != undefined && goodsCartKey == index) {
                continue;
            }
            cart_goods_html += format_cart_html(goodsCartKey, nowShopCart[i].productName, nowShopCart[i].productPrice, nowShopCart[i].image, nowShopCart[i].count, detail_name);
           
            goodsNumber += parseInt(nowShopCart[i].count);
            if (nowShopCart[i].maxNum > 0 && parseInt(nowShopCart[i].count) > nowShopCart[i].maxNum) {
                goodsCartMoney += parseFloat(nowShopCart[i].productPrice) * parseInt(nowShopCart[i].maxNum);
                goodsCartMoney += parseFloat(nowShopCart[i].oldPrice) * (parseInt(nowShopCart[i].count) - parseInt(nowShopCart[i].maxNum));
            } else {
                goodsCartMoney += parseFloat(nowShopCart[i].productPrice) * parseInt(nowShopCart[i].count);
            }
//            goodsCartPackCharge += parseFloat(nowShopCart[i].productPackCharge) * parseInt(nowShopCart[i].count);
            mallCart.push(nowShopCart[i]);
        }
    }
    window.sessionStorage.setItem(cookie_index, JSON.stringify(mallCart));
    
    var html = '<i class="glyphicon glyphicon-menu-up"></i><div class="cardHide"  style="display:none; text-align:center;padding-top: 120px;font-size: 16px;">购物车空空如也！快去购物哦。</div>';
    html += '<ul class="foots scrolls">';
    html += cart_goods_html;
    html += '</ul>';
    html += '<div class="cartCar">';
    html += '<p>商品合计: ￥<span>' + parseFloat(parseFloat(goodsCartMoney + goodsCartPackCharge).toFixed(2)) + '</span></p>';
    html += '<button type="button" id="goCart">去购物车结算</button>';
    html += '</div>';
    $('.cartContent').html(html);
    totalNum = goodsNumber;
    if (goodsNumber > 0) {
        $('.length').html(goodsNumber).show();
         $('.cardHide').hide();
    } else {
        $('.length').html(0).show();
        $('.cardHide').show();
    }
}