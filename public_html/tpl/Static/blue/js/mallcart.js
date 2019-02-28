var cookie_index = 'pc_mall_cart', goodsCart = [];

$(function(){
    initHtml();
    
    $(document).on('keyup', 'input', function(){
        if ($(this).val().length < 1) {
            $(this).val(1);
        }
        var num = parseInt($(this).val());
        
        if (num < 1) {
            num = 1;
        }
        var index_key = $(this).parents('.gitemH2').data('index');
        var this_index = format_cart_data(index_key);
        if (this_index == null) {
            return false;
        }
        var maxNum = parseInt(goodsCart[this_index].maxNum);
        
        if (maxNum > 0 && maxNum < num) {
            if (goodsCart[this_index].isSeckill != false) {
                layer.msg('每单可享受' + maxNum + '份限时优惠价，超出恢复原价');
            } else {
                layer.msg('每单限购' + maxNum + '份');
                return false;
            }
        }
        var intStock = parseInt(goodsCart[this_index].productStock);
        if(intStock != -1 && (intStock == 0 || intStock - num < 0)){
            layer.msg('没有库存了');
            return false;
        }
        goodsCart[this_index].count = num;
        
        var totalMoney = 0;
        var count = parseInt(goodsCart[this_index].count), maxNum = parseInt(goodsCart[this_index].maxNum);
        var price = parseFloat(goodsCart[this_index].productPrice), oldPrice = parseFloat(goodsCart[this_index].oldPrice)
        if (maxNum > 0 && count > maxNum) {
            totalMoney += price * maxNum;
            totalMoney += oldPrice * (count - maxNum);
        } else {
            totalMoney += price * count;
        }
        totalMoney = parseFloat(parseFloat(totalMoney).toFixed(2));
        $('#totalMoney_' + index_key).html(totalMoney);
        $('#awardLimit2' + index_key).data('count', num);
        $('#awardLimit2' + index_key).data('price', totalMoney);
        window.sessionStorage.setItem(cookie_index, JSON.stringify(goodsCart));
        initCart();
        $(this).val(num);
        totalOrder();
    });
    
    $(document).on('click', '.changeNum button', function(){
        
        var num = parseInt($(this).parents('.changeNum').find('input').val());
        if ($(this).hasClass('less')) {
            num --;
        } else {
            num ++;
        }
        if (num < 1) {
            num = 1;
        }
        
        var index_key = $(this).parents('.gitemH2').data('index');
        
        var this_index = format_cart_data(index_key);
        
        
        
        if (this_index == null) {
            return false;
        }
        var maxNum = parseInt(goodsCart[this_index].maxNum);
        
        if (maxNum > 0 && maxNum < num) {
            if (goodsCart[this_index].isSeckill != false) {
                layer.msg('每单可享受' + maxNum + '份限时优惠价，超出恢复原价');
            } else {
                layer.msg('每单限购' + maxNum + '份');
                return false;
            }
        }
        var intStock = parseInt(goodsCart[this_index].productStock);
        if(intStock != -1 && (intStock == 0 || intStock - num < 0)){
            layer.msg('没有库存了');
            return false;
        }
        goodsCart[this_index].count = num;
        
        var totalMoney = 0;
        var count = parseInt(goodsCart[this_index].count), maxNum = parseInt(goodsCart[this_index].maxNum);
        var price = parseFloat(goodsCart[this_index].productPrice), oldPrice = parseFloat(goodsCart[this_index].oldPrice)
        if (maxNum > 0 && count > maxNum) {
            totalMoney += price * maxNum;
            totalMoney += oldPrice * (count - maxNum);
        } else {
            totalMoney += price * count;
        }
        totalMoney = parseFloat(parseFloat(totalMoney).toFixed(2));
        $('#totalMoney_' + index_key).html(totalMoney);
        $('#awardLimit2' + index_key).data('count', num);
        $('#awardLimit2' + index_key).data('price', totalMoney);
        window.sessionStorage.setItem(cookie_index, JSON.stringify(goodsCart));
        initCart();
        $(this).parents('.changeNum').find('input').val(num);
        totalOrder();
    });
    
    
    $(document).on('click', '#clearCart', function(){
        goodsCart = [];
        window.sessionStorage.setItem(cookie_index, null);
        initCart();
        totalOrder();
        $('.nothing').show();
        $('.foodsCont').hide();
    });
    $(document).on('click', '.awardLimit', function(){
       if ($(this).is(':checked')) {
           if ($(this).parents('.goodsItem').siblings('.goodsItem').find('input[type="checkbox"]:checked').size() > 0) {
               $(this).prop('checked', false);
               layer.msg('暂时不支持跨店操作');
               return false;
           }
           $(this).parents('.goodsItem').find('input[type="checkbox"]').prop('checked', true);
       } else {
           $(this).parents('.goodsItem').find('input[type="checkbox"]').prop('checked', false);
       }
       totalOrder();
    });
    
    $(document).on('click', '.awardLimit2', function(){
       if ($(this).is(':checked')) {
           if ($(this).parents('.goodsItem').siblings('.goodsItem').find('input[type="checkbox"]:checked').size() > 0) {
               $(this).prop('checked', false);
               layer.msg('暂时不支持跨店操作');
               return false;
           }
           
           if ($(this).parents('.goodsItem').find('.awardLimit2').size() == $(this).parents('.goodsItem').find('.awardLimit2:checked').size()) {
               $(this).parents('.goodsItem').find('.awardLimit').prop('checked', true);
           } else {
               $(this).parents('.goodsItem').find('.awardLimit').prop('checked', false);
           }
       } else {
           if ($(this).parents('.goodsItem').find('.awardLimit').is(':checked')) {
               $(this).parents('.goodsItem').find('.awardLimit').prop('checked', false);
           }
       }
       totalOrder();
    });
    
    $(document).on('click', '.delate1', function(){
        var index_key = $(this).parents('.gitemH2').data('index');
        if ($(this).parents('.goodsItem').find('.gitemH2').size() > 1) {
            $(this).parents('.gitemH2').remove();
        } else {
            $(this).parents('.goodsItem').remove();
        }
        var this_index = format_cart_data(index_key);
        delete goodsCart[this_index];
        window.sessionStorage.setItem(cookie_index, JSON.stringify(goodsCart));
        initCart();
        totalOrder();
        var nowShopCart = goodsCart, tNum = 0;
        for (var i in nowShopCart) {
            if (nowShopCart[i] != null && nowShopCart[i].count > 0) {
                tNum ++;
            }
        }
        if (tNum == 0) {
            $('.nothing').show();
            $('.foodsCont').hide();
        }
    });
    
    
    $('#nowBuy').click(function(){
        var newcartGoods = [], flag = false;
        var store_id = 0, indexArr = [];
        $('.awardLimit2:checked').each(function(){
            var t_store = parseInt($(this).parents('.goodsItem').data('store_id'));
            if (store_id == 0) {
                store_id = t_store;
            } else if (store_id != t_store) {
                layer.msg('暂时不支持跨店操作');
                flag = true;
                return false;
            }
            var index_key = $(this).val();
            var this_index = format_cart_data(index_key);
            newcartGoods.push(goodsCart[this_index]);
            indexArr.push(index_key);
        });
        if (flag) return false;
        if (newcartGoods.length < 1) {
            layer.msg('请选择要支付购买的商品');
            return false;
        }
        $.post('/index.php?g=Mall&c=Index&a=ajaxMall', {'pc_mall_cart':JSON.stringify(newcartGoods)}, function(res){
            if (res.error) {
                layer.msg(res.msg);
            } else {
                for (var i in indexArr) {
                    var this_index = format_cart_data(indexArr[i]);
                    delete goodsCart[this_index];
                }
                window.sessionStorage.setItem(cookie_index, JSON.stringify(goodsCart));
                location.href = '/mall/order/' + store_id;
            }
        }, 'json');
    });
});




function format_cart_data(index)
{
    var this_index = null;
    for (var i in goodsCart) {
        if (goodsCart[i].count > 0) {
            var old_goodsCartKey = goodsCart[i].productId;
            if (goodsCart[i]['productParam'].length) {
                for (var pi in goodsCart[i]['productParam']) {
                    if (goodsCart[i]['productParam'][pi].type == 'spec') {
                        old_goodsCartKey += '_s_' + goodsCart[i]['productParam'][pi].id;
                    } else {
                        if (goodsCart[i]['productParam'][pi]['data'].length) {
                            for (var di in goodsCart[i]['productParam'][pi]['data']) {
                                old_goodsCartKey += '_v_' + goodsCart[i]['productParam'][pi]['data'][di].id;
                            }
                        }
                    }
                }
            }
            if (index == old_goodsCartKey) {
                this_index = i;
                break;
            }
        }
    }
    return this_index;
}

function totalOrder()
{
    var count = 0, price = 0;
    $('.awardLimit2:checked').each(function(){
        count += parseInt($(this).data('count'));
        price += parseFloat($(this).data('price'));
    });
    $('#selectCount').html(count);
    $('#selectPrice').html('￥' + parseFloat(parseFloat(price).toFixed(2)));
}



function initHtml()
{
    var nowShopCart = $.parseJSON(window.sessionStorage.getItem(cookie_index));
    var goodsNumber = 0, goodsCartMoney = 0, goods_price_list = [], goods_index_list = [], goodsCartPackCharge = 0;
    var cart_goods_html = '';
    goodsCart = [];
    
    var mallCartData = [];
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
                detail_name = '(' + detail_name + ')';
            }
            if (nowShopCart[i].productParam != '') {
                nowShopCart[i].productExtraPrice = 0;
            }
            nowShopCart[i].index = goodsCartKey;
            nowShopCart[i].detail_name = detail_name;
            
            if ( mallCartData[nowShopCart[i].store_id] == undefined) {
                mallCartData[nowShopCart[i].store_id] = {'store_id':nowShopCart[i].store_id, 'name':nowShopCart[i].name, 'data':[nowShopCart[i]]};
            } else {
                mallCartData[nowShopCart[i].store_id].data.push(nowShopCart[i]);
            }
            
//            console.log(nowShopCart[i])
//            cart_goods_html += format_cart_html(goodsCartKey, nowShopCart[i].productName, nowShopCart[i].productPrice, nowShopCart[i].image, nowShopCart[i].count, detail_name);
//            goodsNumber += parseInt(nowShopCart[i].count);
//            if (nowShopCart[i].maxNum > 0 && parseInt(nowShopCart[i].count) > nowShopCart[i].maxNum) {
//                goodsCartMoney += parseFloat(nowShopCart[i].productPrice) * parseInt(nowShopCart[i].maxNum);
//                goodsCartMoney += parseFloat(nowShopCart[i].oldPrice) * (parseInt(nowShopCart[i].count) - parseInt(nowShopCart[i].maxNum));
//            } else {
//                goodsCartMoney += parseFloat(nowShopCart[i].productPrice) * parseInt(nowShopCart[i].count);
//            }
//            goodsCartPackCharge += parseFloat(nowShopCart[i].productPackCharge) * parseInt(nowShopCart[i].count);
            goodsCart[i] = nowShopCart[i];
        }
    }
    
    
    if (mallCartData.length > 0) {
        var html = '';
        for (var store in mallCartData) {
            html += '<div class="goodsItem" data-store_id="' + mallCartData[store].store_id + '">';
            html += '<div class="gitemH1">';
            html += '<div class="checkbox checkbox-success checkbox-inline allItem">';
            html += '<input id="awardLimit_' + mallCartData[store].store_id + '" class="awardLimit" type="checkbox" value="' + mallCartData[store].store_id + '">';
            html += '<label for="awardLimit_' + mallCartData[store].store_id + '">' + mallCartData[store].name + '</label>';
            html += '</div>';
            html += '</div>';
            
            for (var goods in mallCartData[store].data) {
                var totalMoney = 0;
                var tempData = mallCartData[store].data[goods];
                var count = parseInt(tempData.count), maxNum = parseInt(tempData.maxNum);
                var price = parseFloat(tempData.productPrice), oldPrice = parseFloat(tempData.oldPrice)
                if (maxNum > 0 && count > maxNum) {
                    totalMoney += price * maxNum;
                    totalMoney += oldPrice * (count - maxNum);
                } else {
                    totalMoney += price * count;
                }
                totalMoney = parseFloat(parseFloat(totalMoney).toFixed(2));
                
                html += '<div class="gitemH2" id="mall_' + mallCartData[store].data[goods].index + '" data-index="' + mallCartData[store].data[goods].index + '">';
                html += '<div class="img1 ">';
                html += '<div class="checkbox checkbox-success checkbox-inline">';
                html += '<input id="awardLimit2' + mallCartData[store].data[goods].index + '" class="awardLimit2" data-count="' + mallCartData[store].data[goods].count + '" data-price="' + totalMoney + '" type="checkbox" value="' + mallCartData[store].data[goods].index + '">';
                html += '<label for="awardLimit2' + mallCartData[store].data[goods].index + '"></label>';
                html += '</div>';
                html += '<img src="' + mallCartData[store].data[goods].image + '" />';
                html += '<ul>';
                html += '<li>';
                html += '<span>' + mallCartData[store].data[goods].productName + '</span>';
                html += '</li>';
                html += '<li>' + mallCartData[store].data[goods].detail_name + '</li>';
                html += '</ul>';
                html += '</div>';
                html += '<div class="price1">';
                if (mallCartData[store].data[goods].isSeckill == true) {
                    html += '<b>原价：' + mallCartData[store].data[goods].oldPrice + '，优惠价：' + mallCartData[store].data[goods].productPrice + '</b>';
                } else {
                    html += '<b>' + mallCartData[store].data[goods].productPrice + '</b>';
                }
                
                html += '<div>';
                if (mallCartData[store].data[goods].isSeckill == true) {
                    if (mallCartData[store].data[goods].maxNum > 0) {
                        html += '<span>限时优惠，限' + mallCartData[store].data[goods].maxNum + '份优惠</span>';
                    } else {
                        html += '<span>限时优惠</span>';
                    }
                    
                }
                html += '</div>';
                html += '</div>';
                html += '<div class="nums1">';
                html += '<div class="changeNum">';
                html += '<button class="less active">-</button>';
                html += '<input type="number" value="' + mallCartData[store].data[goods].count + '">';
                html += '<button class="adds active">+</button>';
                html += '</div>';
                html += '</div>';
                html += '<div class="price2">';
                
                html += '<b id="totalMoney_' + mallCartData[store].data[goods].index + '">' + totalMoney + '</b>';
                html += '</div>';
                html += '<div class="delate1">';
                html += '<span>删除</span>';
                html += '</div>';
                html += '</div>';
            }
            html += '</div>';
        }
        $('.nothing').hide();
        $('.foodsCont').show();
        $('.payGoodsContent').html(html);
    } else {
        $('.nothing').show();
        $('.foodsCont').hide();
    }
}