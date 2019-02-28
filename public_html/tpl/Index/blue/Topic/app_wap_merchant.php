<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="white">
<meta name="format-detection" content="telephone=no">
<title>商家中心APP</title>
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

<body class="swiper-container screen">
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
                <div class="logo"><img src="/app_down/wap/images/downapp-2_05.png"></div>
                <h1>商家中心APP</h1>
                <p>店铺管理、数据分析、店员安排<br/>
                    掌中平台，一切尽在您的掌控</p>
            </div>
        </div>
    </section>
    <section class=" swiper-slide section-2">
        <div class="swiper_content">
            <h2>营销指南</h2>
            <p>订单、粉丝、收入、浏览  数据分析面面俱到</p>
            <div class="section_img"><img src="/app_down/wap/images/downapp-2_16.png"></div>
        </div>
    </section>
    <section class="swiper-slide section-3 ">
        <div class="swiper_content">
            <h2>店铺管家</h2>
            <p>有团购 叫外卖 玩活动 我们不止省钱那么简单</p>
            <div class="section_img"><img src="/app_down/wap/images/downapp-2_37.png"></div>
        </div>
    </section>
    <section class="swiper-slide section-4 ">
        <div class="swiper_content">
            <h2>销售管理</h2>
            <p>团购订单 快店订单 商品管理 掌上营业轻松赚钱</p>
            <div class="section_img"><img src="/app_down/wap/images/downapp-2_46.png"></div>
        </div>
    </section>
    <section class="swiper-slide section-5 ">
        <div class="swiper_content">
            <h2>完美搭档</h2>
            <p>订单打印机 专属二维码 更多搭配由您选择</p>
            <div class="section_img"><img src="/app_down/wap/images/downapp-2_54.png"></div>
        </div>
    </section>
    <section class="swiper-slide section-6" ></section>
</article>
<div class="swiper-pagination"></div>
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
    <button class="iosapp_down" url="{pigcms{$app_config.mer_ios_url}"><i></i>iPhone下载</button>
    <button class="android_down" url="{pigcms{$app_config.mer_android_url}"><i></i>Android下载</button>
</footer>
</body>
</html>