var cookie_index = 'pc_mall_cart', goodsCart = [];
$(function(){
    $(document).on('click', '.changeNum button', function(){
        var num = parseInt($('#nums').val());
        if ($(this).hasClass('less')) {
            num --;
        } else {
            num ++;
        }
        if (num < 1) {
            num = 1;
        }
        $('#nums').val(num);
    });
    //规格属性的选择
    $('.masColor').on('click', 'li.pull-left', function(){
        var type = $(this).parents('ul').data('type'), num = $(this).parents('ul').data('num');
        if (type == 'spec') {
            $(this).addClass('active').siblings().removeClass('active');
            check_select(false);
        } else if (type == 'properties') {
            if (num == 1) {
                $(this).addClass('active').siblings().removeClass('active');
            } else {
                var select = $(this).parents('ul').find('active').size();
                if (select >= num) {
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                    } else {
                        $(this).addClass('active');
                    }
                } else {
                    $(this).addClass('active');
                }
            }
            check_select(false);
        } else {
            return false;
        }
    });
    //立刻购买
    $('#nowBuy').click(function(){
        var productParam = '';
        if ($('.masColor').size() > 0) {//有规格属性
           var params = check_select(true);
           if (params == false) {
               return false;
           } else {
               productParam = params.data;
           }
        }
        var num = parseInt($('#nums').val());
        var maxNum = parseInt(cartGoods.maxNum);
        
        if (maxNum > 0 && maxNum < num) {
            if (cartGoods.isSeckill != false) {
                layer.msg('每单可享受' + maxNum + '份限时优惠价，超出恢复原价');
            } else {
                layer.msg('每单限购' + maxNum + '份');
                return false;
            }
        }
        
        var intStock = parseInt(cartGoods.productStock);
        if(intStock != -1 && (intStock == 0 || intStock - num < 0)){
            layer.msg('没有库存了');
            return false;
        }
        
        cartGoods.count = $('#nums').val();
        cartGoods.productParam = productParam;
        $.post('/index.php?g=Mall&c=Index&a=ajaxMall', {'pc_mall_cart':JSON.stringify([cartGoods])}, function(res){
            if (res.error) {
                layer.msg(res.msg);
            } else {
                location.href = '/mall/order/' + cartGoods.store_id;
            }
        }, 'json');
    });
    
    $('.cart ').mouseover(function(e){
        $(".cartContent").show();
            $('.cartContent').mouseout(function(e){
                $(".cartContent").hide();
            });
    });
    //添加到购物车
    $('#addCart').click(function(){
        var productParam = '';
        var params = check_select(true);
        if (params == false) {
            return false;
        }
        var this_index = format_cart_data(params.index_key);
        var num = parseInt($('#nums').val());
        var maxNum = parseInt(cartGoods.maxNum);
        
        if (this_index != null) {
            num += parseInt(goodsCart[this_index].count);
        }
        var isTips = true;
        if (maxNum > 0 && maxNum < num) {
            if (cartGoods.isSeckill != false) {
                layer.msg('每单可享受' + maxNum + '份限时优惠价，超出恢复原价');
                isTips = false;
            } else {
                layer.msg('每单限购' + maxNum + '份');
                return false;
            }
        }
        var intStock = parseInt(cartGoods.productStock);
        if(intStock != -1 && (intStock == 0 || intStock - num < 0)){
            layer.msg('没有库存了');
            return false;
        }
     
        if (this_index != null) {
            goodsCart[this_index].count = num;
        } else {
            cartGoods.count = $('#nums').val();
            cartGoods.productParam = params.data;
            goodsCart.push(cartGoods);
        }
        // if (isTips) {
        //     layer.msg(parseInt($('#nums').val()) + '份加入成功');
        // }
        window.sessionStorage.setItem(cookie_index, JSON.stringify(goodsCart));
        initCart();
        $('.cartContent').show();
        setTimeout(function(){$(".cartContent").hide();},2000);//2秒后执行该方法
    });
});

function check_select(is_check)
{
    var productParam = [], goods_id = $('.masColor').data('goods_id'), index_key = cartGoods.productId, spec_ids = [], is_false = false;
    if ($('.spec').size() > 0) {
        $('.masColor ul.spec_ul').each(function(){
            var type = $(this).data('type'), fid = $(this).data('id'), fname = $(this).data('name'), datas = null, select_num = $(this).data('num');
            if (type == 'spec') {
                var num = 0;
                $(this).find('li').each(function(){
                    if ($(this).hasClass('active')) {
                        num = 1;
                        var id = $(this).data('id'), name = $(this).data('name');
                        datas = {
                                type:'spec',
                                spec_id:fid,
                                id:id,
                                name:name
                        };
                        index_key += '_s_' + id;
                        spec_ids.push(id);
                        productParam.push(datas);
                    }
                });
                if (num < 1 && is_check) {
                    layer.msg(fname + '规格必须选择一个');
                    is_false = true;
                }
            }
        });
    }
    if ($('.property').size() > 0) {
        $('.masColor ul.properties_ul').each(function(){
            var type = $(this).data('type'), fid = $(this).data('id'), fname = $(this).data('name'), datas = null, select_num = $(this).data('num');
            var temp_data = [], num = 0;
            $(this).find('li').each(function(){
                if ($(this).hasClass('active')) {
                    num ++;
                    temp_data.push({'id':$(this).data('id'), 'list_id':fid, 'name':$(this).data('name')});
                    index_key += '_v_' + $(this).data('id');
                }
            });
            if (num < 1 && is_check) {
                layer.msg(fname + '属性至少选择一个');
                is_false = true;
            }
            
            if (spec_ids.length > 0) {
                if (spec_ids.length > 1) {
                    var str = spec_ids.join('_');
                } else {
                    var str = spec_ids[0];
                }
                if (goodsDetail[str] != undefined) {
                    var t_properties = goodsDetail[str].properties;
                    for (var i in t_properties) {
                        if (t_properties[i].id == fid) {
                            select_num = t_properties[i].num;
                        }
                    }
                }
            }
            
            if (num > select_num && is_check) {
                layer.msg(fname + '属性最多选择' + select_num + '个');
                is_false = true;
            }
            if (temp_data.length > 0) {
                datas = {type:'properties', data:temp_data};
                productParam.push(datas);
            }
        });
    }
    if (spec_ids.length > 0) {
        if (spec_ids.length > 1) {
            var str = spec_ids.join('_');
        } else {
            var str = spec_ids[0];
        }
        
        if (goodsDetail[str] != undefined) {
            if (cartGoods.isSeckill != false) {
                cartGoods.productPrice = goodsDetail[str].seckill_price;
                cartGoods.maxNum = goodsDetail[str].max_num;
                cartGoods.oldPrice = goodsDetail[str].price;
                cartGoods.productStock = goodsDetail[str].stock_num;
            } else {
                cartGoods.productPrice = goodsDetail[str].price;
                cartGoods.maxNum = goodsDetail[str].max_num;
                cartGoods.oldPrice = goodsDetail[str].old_price;
                cartGoods.productStock = goodsDetail[str].stock_num;
            }
            $('#price').html(cartGoods.productPrice);
            $('#oprice').html(cartGoods.oldPrice);
            if (cartGoods.productStock == -1) {
                $('#stock').html('充足');
            } else {
                $('#stock').html(cartGoods.productStock);
            }
            
        }
    }
    if (is_false) {
        return false;
    } else {
        return {index_key:index_key, data:productParam};
    }
}

function format_cart_data(index)
{
    goodsCart = $.parseJSON(window.sessionStorage.getItem(cookie_index));
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