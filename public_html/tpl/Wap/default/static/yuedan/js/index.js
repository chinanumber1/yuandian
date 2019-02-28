window.onload = function() {
   	var mySwiper = new Swiper('.swiper-container',{
     	direction:"horizontal",/*横向滑动*/  
        loop:true,/*形成环路（即：可以从最后一张图跳转到第一张图*/  
        pagination:".swiper-pagination",/*分页器*/   
        autoplay:3000/*每隔3秒自动播放*/  
   	});  
 }

