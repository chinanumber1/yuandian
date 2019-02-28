<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="white">
<meta name="format-detection" content="telephone=no">
<title>店员中心APP</title>
<link rel="stylesheet" type="text/css" href="/app_down/wap/css/base.css">
<link rel="stylesheet" type="text/css" href="/app_down/wap/css/download.css">
<link rel="stylesheet" type="text/css" href="/app_down/wap/css/idangerous.swiper.css">
<script type="text/javascript" src="/app_down/wap/js/jquery-1.7.1.min.js"></script>
<script src="/app_down/wap/js/fakeLoader.min.js"></script>
<script src="/app_down/wap/js/download.js"></script>
<script>
        $(document).ready(function(){
            $(".fakeloader").fakeLoader({
                timeToHide:1200,
                bgColor:"#2ecc71",
                spinner:"spinner1"
            });
        });
    </script>
<link rel="stylesheet" href="/app_down/wap/css/fakeLoader.css">
<div class="container">
    <div class="fakeloader"></div>
</div>
</head>
<body>
<div  class="swiper-container screen">
    <div class="layer"></div>
    <aside class="download_content">
        <div class="jiandou"></div>
        <ul>
            <li><i>1</i>点击右上角<span><img src="/app_down/wap/images/1.png"></span>按钮</li>
            <li><i>2</i>选择<img src="/app_down/wap/images/2.png"></li>
            <li><i>3</i>点击下载按钮进行下载</li>
            <li>知道了</li>
        </ul>
    </aside>
    <article class="swiper-wrapper">
        <section class="header swiper-slide section-1 swiper-slide-visible swiper-slide-active">
            <div class="banner">
                <div class="banner_content">
                    <div class="logo"><img src="/app_down/wap/images/downapp-3_07.png"></div>
                    <h1>店员中心APP</h1>
                    <p>订单验证，快速、精准、效率<br>
                        掌心里的店铺，手指上的营销</p>
                </div>
            </div>
        </section>
        <section class="swiper-slide section-2 ">
            <div class="swiper_content">
                <h2>消费验证</h2>
                <p>消费密码、二维码验券 一键验证 快捷方便</p>
                <div class="section_img"><img src="/app_down/wap/images/downapp-3_26.png"></div>
            </div>
        </section>
        <section class="swiper-slide section-3 ">
            <div class="swiper_content">
                <h2>订单详情</h2>
                <p>订单内容、支付状态、评价内容等 订单详情随时查看</p>
                <div class="section_img"><img src="/app_down/wap/images/downapp-3_39.png"></div>
            </div>
        </section>
        <section class="swiper-slide section-4 ">
            <div class="swiper_content">
                <h2>订单查找</h2>
                <p>支持多条件查找订单 消费密码、ID、昵称、手机号等</p>
                <div class="section_img"><img src="/app_down/wap/images/downapp-3_47.png"></div>
            </div>
        </section>
        <section class="swiper-slide section-5 ">
            <div class="swiper_content">
                <h2>一站多用</h2>
                <p>团购、快店、预约 订单验证一站齐全</p>
                <div class="section_img"><img src="/app_down/wap/images/downapp-3_55.png"></div>
            </div>
        </section>
        <section class="swiper-slide section-6" ></section>
    </article>
    <div class="swiper-pagination"></div>
</div>
<script src="/app_down/wap/js/idangerous.swiper-2.0.min.js"></script> 
<script>
    $(function(){
        $(".screen").height($(window).height());
        $(".swiper-slide").height($(window).height());
    })
	var window_height=$(window).height();
 
	
	/*  var mySwiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        slidesPerView: 3,
        paginationClickable: true,
        spaceBetween: 30
    });	*/
   var mySwiper = new Swiper('.swiper-container',{
        mode: 'vertical',
		 pagination: '.swiper-pagination',
        paginationClickable: true,
        onSlideNext:function(swiper){
            if(swiper.activeIndex ==5){
                window.location.reload();
            }
        }
    });
</script>
<footer>
    <button class="iosapp_down" url="{pigcms{$app_config.staff_ios_url}"><i></i>iPhone下载</button>
    <button class="android_down" url="{pigcms{$app_config.staff_android_url}"><i></i>Android下载</button>
</footer>
</body>
</html>