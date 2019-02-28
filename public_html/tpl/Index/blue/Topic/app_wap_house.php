<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="format-detection" content="telephone=no">
    <title>社区O2O</title>
    <link rel="stylesheet" type="text/css" href="/app_down/wap/css/base.css">
    <link rel="stylesheet" type="text/css" href="/app_down/wap/css/download.css">
    <link rel="stylesheet" type="text/css" href="/app_down/wap/css/idangerous.swiper.css">
    <script type="text/javascript" src="/app_down/wap/js/jquery-1.7.1.min.js"></script>
    <script src="/app_down/wap/js/download.js"></script>
    <script src="/app_down/wap/js/fakeLoader.min.js"></script>
    <script>
    $(document).ready(function() {
        $(".fakeloader").fakeLoader({
            timeToHide: 1200,
            bgColor: "#2ecc71",
            spinner: "spinner1",

        });
    });
    </script>
    <link rel="stylesheet" href="/app_down/wap/css/fakeLoader.css">
    <div class="container">
        <div class="fakeloader"></div>
    </div>

    <body>
        <div class="swiper-container screen">
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
                            <div class="logo"><img src="/app_down/wap/images/app4_09.png"></div>
                            <h1>社区中心</h1>
                            <p>物业缴费、社区服务、生活商圈、邻里交流
                                <br> 智能化的社区生活，综合化的生活商圈
                            </p>
                        </div>
                    </div>
                </section>
                <section class="swiper-slide section-2 ">
                    <div class="swiper_content">
                        <h2>物业功能全覆盖</h2>
                        <p>物业缴费不再拥挤，在线报修无需排队</p>
                        <div class="section_img"><img src="/app_down/wap/images/app4_25.png"></div>
                    </div>
                </section>
                <section class="swiper-slide section-3 ">
                    <div class="swiper_content">
                        <h2>生活商圈尽在于此</h2>
                        <p>社区周边餐饮娱乐服务，在线下单快捷便利</p>
                        <div class="section_img"><img src="/app_down/wap/images/app4_34.png"></div>
                    </div>
                </section>
                <section class="swiper-slide section-4 ">
                    <div class="swiper_content">
                        <h2>构建社区大家庭</h2>
                        <p>社区新闻通知事实推送，邻里交流更显温馨</p>
                        <div class="section_img"><img src="/app_down/wap/images/app4_45.png"></div>
                    </div>
                </section>
                <section class="swiper-slide section-5 ">
                    <div class="swiper_content">
                        <h2>社区智能化设施</h2>
                        <p>智能门禁系统开启智慧社区</p>
                        <div class="section_img"><img src="/app_down/wap/images/app4_58.png"></div>
                    </div>
                </section>
                <section class="swiper-slide section-6"></section>
            </article>
            <div class="swiper-pagination"></div>
        </div>
        <script src="/app_down/wap/js/idangerous.swiper-2.0.min.js"></script>
        <script>
        $(function() {
            $(".screen").height($(window).height());
            $(".swiper-slide").height($(window).height());
        })
        var window_height = $(window).height();


        /*  var mySwiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        slidesPerView: 3,
        paginationClickable: true,
        spaceBetween: 30
    }); */
        var mySwiper = new Swiper('.swiper-container', {
            mode: 'vertical',
            pagination: '.swiper-pagination',
            paginationClickable: true,
            onSlideNext: function(swiper) {
                if (swiper.activeIndex == 5) {
                    window.location.reload();
                }
            }
        })
        var i = 0;
        </script>
        <footer>
            <button class="iosapp_down" url="https://itunes.apple.com/cn/app/%E5%B0%8F%E7%8C%AA%E7%A4%BE%E5%8C%BA%E4%B8%AD%E5%BF%83/id1124366223?mt=8"><i></i>iPhone下载</button>
            <button class="android_down" url="http://hf.pigcms.com/house_down/app/house.apk"><i></i>Android下载</button>
        </footer>
    </body>

</html>
