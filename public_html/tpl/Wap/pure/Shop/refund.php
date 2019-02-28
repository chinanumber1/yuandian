<html lang="zh-CN">
<head>
<meta charset="utf-8">
<title>申请售后</title>
<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width">
<meta http-equiv="pragma" content="no-cache">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/customer.css" />
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/ajaxfileupload.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
<body>
    <section class="public">
        <a class="return link-url" id="goBackUrl" href="javascript:window.history.go(-1);"></a>
        <div class="content">申请售后</div>
    </section>
    <div class="content" <if condition="$expire eq 1">style="display: none"</if>>
        <div class="returnGood">
            <span>请选择退款商品</span>
            <a type="button" href="{pigcms{:U('Shop/refundStatus', array('order_id' => $order['order_id']))}">退款进度</a>
        </div>
        <div class="selectAll">
            <p class="do active">
                <i></i>全选
            </p>
            <div>实际支付:<span>￥{pigcms{$order['price']|floatval}</span></div>
        </div>
        <div class="foods">
            <php> $refundPrice = 0; </php>
            <volist name="goodsList" id="goods">
            <div class="foodItem">
                <div class="itemLeft">
                    <div class="img do active" data-price="{pigcms{:floatval($goods['pay_price'] * $goods['num'])}">
                        <i></i>
                        <img src="{pigcms{$goods['image']}" />
                    </div>
                    <div class="text">
                        <dl>
                            <dt>{pigcms{$goods['name']}</dt>
                            <dd>￥{pigcms{$goods['pay_price']|floatval}</dd>
                        </dl>
                    </div>
                </div>
                <div class="itemRight">
                    <div class="zi rev_ul">
<!--                         <span class="fl jian">-</span> -->
                        <input class="fl rev_input" type="tel" disabled value="{pigcms{$goods['num']}" data-price="{pigcms{$goods['pay_price']|floatval}" data-detail_id="{pigcms{$goods['id']}" data-goods_id="{pigcms{$goods['goods_id']}">
<!--                         <span class="fl jia">+</span> -->
                    </div>
                </div>
                <php> $refundPrice += $goods['num'] * $goods['pay_price']; </php>
            </div>
            </volist>
        </div>
        <div class="tuiMoney">退款金额:
            <span id="refundMoney">￥{pigcms{:floatval($refundPrice)}</span>
            <input type="hidden" name="order_id" value="{pigcms{$order['order_id']}" />
        </div>
        <div class="cost">不含配送费、打包费、优惠金额</div>
        <div class="explain">
            <span>退款说明:</span>
            <textarea name="reason" id="reason" rows="3" cols="3" maxlength="30" placeholder="选填，最多支持30个字"></textarea>
        </div>
        <div class="upload">
            <h4>上传凭证</h4>
            <ul class="imgsUp" id="upload_list">
                <li class="fail">
                    <div class="up_img">
                        <i></i>
                        <span>上传凭证</span>
                        <p>(最多3张)</p>
                    </div>
                    <input type="file" accept="image/*" id="imgUploadFile" onchange="imgUpload()" name="imgFile" value="选择文件上传"/>
                </li>
            </ul>
        </div>
        <div style="padding-bottom: 60px;"></div>
        <div class="bottom">提交</div>
    </div>
    <div class="exceed" <if condition="$expire eq 0">style="display: none"</if>>
        <h4>{pigcms{$expire_tips}</h4>
        <a href="{pigcms{:U('Shop/refundStatus', array('order_id' => $order['order_id']))}">查看退款进度</a>
    </div>
<script type="text/javascript">
function totalMoney()
{
    var money = 0;
    $('.itemLeft .do').each(function(){
        if (($(this).hasClass('active'))) {
            money += parseFloat($(this).data('price'));
        }
    });
    money = parseFloat(money.toFixed(2));
    return money;
}
$(document).ready(function(){
    $('.selectAll .do').click(function(){
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('.itemLeft .do').removeClass('active');
        } else {
            $(this).addClass('active');
            $('.itemLeft .do').addClass('active');
        }
        $('#refundMoney').html('￥' + totalMoney());
    });
    $('.itemLeft .do').click(function(){
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('.selectAll .do').removeClass('active');
        } else {
            $(this).addClass('active');
            var flag = true;
            $('.itemLeft .do').each(function(){
                if (!($(this).hasClass('active'))) {
                    flag = false;
                }
            });
            if (flag) {
                $('.selectAll .do').addClass('active');
            }
        }
        $('#refundMoney').html('￥' + totalMoney());
    });
    
    $('.rev_ul span').click(function(){
        if ($(this).hasClass("jia")) {
            var number = parseInt($('.rev_input').val())+1;
            $(this).prev().val(number);
        } else {
            var number = parseInt($(this).next().val());
            if (number>1) {
                number--;
            }
            $(this).next().val(number);
        };
    });
    $(document).on('click', '.del', function() {
        $(this).parents('.a1').remove();
        let sum = $("#upload_list .a1").length;
        let svm = $('.fail').length;
        if (sum < 3 && svm <= 0) {
            $("#upload_list").prepend('<li class="fail"><div class="up_img"><i></i><span>上传凭证</span><p>(最多3张)</p></div><input type="file" accept="image/*" id="imgUploadFile" onchange="imgUpload()" name="imgFile" value="选择文件上传"/></li>');
        }
    });
    $('.bottom').click(function(){
        var goods = [];
        $('.foods .active').each(function(){
            goods.push({'num':$(this).parents('.foodItem').find('.rev_input').val(), 'detail_id':$(this).parents('.foodItem').find('.rev_input').data('detail_id')});
        });
        console.log(goods);
        var images = [];
        $("input[class='images']").each(function(i, el) {
            images.push($(this).val());
            console.log($(this).val());
        });
        images = images.join();
        
        $.post("{pigcms{:U('Shop/saveRefund')}", {'goods':goods, 'order_id':$('input[name="order_id"]').val(), 'images':images, 'reason':$('#reason').val()}, function(res){
            if (res.errcode == 1) {
                layer.open({content:res.msg, skin:'msg', time:2});
            } else {
                location.href="{pigcms{:U('Shop/refundStatus')}&order_id=" + $('input[name="order_id"]').val();
            }
        }, 'json');
    });
});


function imgUpload(){
    $.ajaxFileUpload({
        url:"{pigcms{:U('Shop/ajax_upload_file')}",
        secureuri:false,
        fileElementId:'imgUploadFile',
        dataType: 'json',
        success: function (data) {
            if(data.error == 2){
                $("#upload_list").append('<li class="a1"><img src="'+data.url+'"/><i class="del"></i><input type="hidden" class="images" name="img[]" value="'+data.url+'" /></li>');
                 let sum = $("#upload_list .a1").length;
                 if(sum >= 3){
                    $('.fail').remove();
                    return false;
                }
            } else {
                layer.open({content: data.msg ,btn: ['确定']});
            }
        }
    }); 
}

</script>
</body>
</html>