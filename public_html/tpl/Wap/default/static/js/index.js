$(function() {
  var width = $(window).width();
  var height = $(window).height();
  var bo_len=$(".booking_header li").length;
  var bo_li_width=$(".booking_header li").outerWidth(true);
  
  $(".booking_header").css("width",bo_li_width*bo_len+10)
});
$(function() {
  tab(".activity_title li", ".acticity_list> li", "active");
 
})

function tab(a, b, c) { //a 是点击的目标,,b 是所要切换的目标,c 是点击目标的当前样式
  var len = $(a);
  len.bind("click",
    function() {
      var index = 0;
      $(this).addClass(c).siblings().removeClass(c);
      index = len.index(this); //获取当前的索引
      $(b).eq(index).show().siblings().hide();
      return false;
    }).eq(0).trigger("click"); //浏览器模拟第一个点击
}

