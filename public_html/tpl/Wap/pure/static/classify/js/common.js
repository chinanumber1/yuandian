/**
 * Created by tanytree on 2016/07/2.
 */
document.body.addEventListener('touchstart', function () { });
window.onload=function(){
    window.setTimeout(function(){
        $(".lodingCover").remove();
    },600)
}

$(function(){
    //滚动筛选
    $(".scrollNav ul li").tap(function(){
        $(".scrollNav ul li").removeClass("on");
       $(this).addClass("on");
    });
});