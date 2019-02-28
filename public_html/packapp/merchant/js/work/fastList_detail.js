mui.init();
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var store_id=$.getUrlParam('store_id');
var order_id=$.getUrlParam('order_id');
var ashop=common.getCache('ashop');
$('.ashop').text(ashop);
$('title').html(ashop+'订单详情');
common.http('WapMerchant&a=shopOrderDetail',{'client':client,'store_id':store_id,'order_id':order_id},function(data){
    console.log(data);
    laytpl(document.getElementById('allTpl').innerHTML).render(data, function(html){
        $('.g_details').html(html);
    });
});
