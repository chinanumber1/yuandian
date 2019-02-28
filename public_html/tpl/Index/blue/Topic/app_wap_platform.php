<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="white">
<meta name="format-detection" content="telephone=no">
<title>平台APP</title>
<link rel="stylesheet" type="text/css" href="/app_down/wap/css/base.css">
<link rel="stylesheet" type="text/css" href="/app_down/wap/css/download.css">
<link rel="stylesheet" type="text/css" href="/app_down/wap/css/idangerous.swiper.css">

<script type="text/javascript" src="/app_down/wap/js/jquery-1.7.1.min.js"></script>
<script src="/app_down/wap/js/download.js"></script>
<script src="/app_down/wap/js/fakeLoader.min.js"></script>
<script>
        $(document).ready(function(){
            $(".fakeloader").fakeLoader({
                timeToHide:1200,
                bgColor:"#2ecc71",
                spinner:"spinner1",
  	   
            });
        });
    </script>
    <link rel="stylesheet" href="/app_down/wap/css/fakeLoader.css">
 <div class="container">
    <div class="fakeloader"></div>
</div> 

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
                <div class="logo"><img src="/app_down/wap/images/downapp-1_03.png"></div>
                <h1>平台APP</h1>
                <p>找美食、叫外卖、看电影、预约服务、生活缴费<br>
                    随时随地，时尚生活由您一键获取</p>
            </div>
        </div>
    </section>
    <section class="swiper-slide section-2 ">
    <div class="swiper_content">
        <h2>掌上城市</h2>
        <p>网罗全城生活快报  搜集身边优惠信息</p>
        <div class="section_img"><img src="/app_down/wap/images/downapp-1_23.png"></div>
        </div>
    </section>
    <section  class="swiper-slide section-3 ">
        <div class="swiper_content">
        <h2>省钱宝典</h2>
        <p>有团购 叫外卖 玩活动 我们不止省钱那么简单</p>
        <div class="section_img"><img src="/app_down/wap/images/downapp-1_36.png"></div>
        </div>
    </section>
    <section  class="swiper-slide section-4 ">
        <div class="swiper_content">
        <h2>预约服务</h2>
        <p>约家政 约美容 约健身 更多服务快人一步</p>
        <div class="section_img"><img src="/app_down/wap/images/downapp-1_45.png"></div>
        </div>
    </section>
    <section  class="swiper-slide section-5 ">
        <div class="swiper_content">
        <h2>支付生活</h2>
        <p>线上线下多种支付途径  线上生活缴费一应俱全</p>
        <div class="section_img"><img src="/app_down/wap/images/downapp-1_53.png"></div>
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
    })
	var i=0; 
 
</script>
<footer>
    <button class="iosapp_down" url="{pigcms{$app_config.ios_download_url}"><i></i>iPhone下载</button>
    <button class="android_down" url="{pigcms{$app_config.android_download_url}"><i></i>Android下载</button>
</footer>
</body>
</html>