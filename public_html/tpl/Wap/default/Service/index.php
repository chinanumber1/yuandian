<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>服务快派首页</title>
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="telephone=no" name="format-detection">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0,user-scalable=no">
    <meta name="baidu-site-verification" content="Rp99zZhcYy">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="{pigcms{$static_path}service/css/basic.css" rel="stylesheet" type="text/css">
    <script src="{pigcms{$static_path}service/js/jquery-2.1.4.js"></script>
    <link href="{pigcms{$static_path}service/css/index.css" rel="stylesheet" type="text/css">
    <link href="{pigcms{$static_path}service/css/m_seo.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210">
    <script src='{pigcms{$static_path}service/js/json2.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/basic.js?t=58d24290'></script>
    <script src='{pigcms{$static_path}service/js/md5.min.js?t=58a16a34'></script>
    <script src='{pigcms{$static_path}service/js/newcode-src.js?t=58a16a34'></script>
			<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
    <!-- 回到顶部 start -->
    <style>
        .l-flex .l-operate-link {
            display: inline-block;
            width: 45%;
            height: 3.241177263rem;
            line-height: 3.241177263rem;
            text-align: center;
            color: #fff;
            font-size: 1.11541285rem;
            padding: 0 1.062796336rem;
            margin-left: 4%;
            /* border-radius: 2.262796336rem; */
            border-radius: 0.4rem;
        }
    </style>
</head>
<body class="android">
<div class="clear"></div>
<div class="pagewrap" id="mainpage">
    <div class="clear"></div>
    <div class="main" style="">
        <!--div class="l-index-banner clearfix" id="l-index-banner" style="height: 154px;">
            <div id="banner_con" style="width: 721px; margin-left: -361px;">
                <volist name="service_index_lunbo" id="vo">
                    <a href="{pigcms{$vo.url}" class="img_cell" style=""><img src="{pigcms{$vo.pic}" class="l-banner-img"></a>
                </volist>
            </div>
            <div class="index-banner-tab" id="index-banner-tab">
                <volist name="service_index_lunbo" id="k_vo">
                    <span class="tab" tabid="{pigcms{$key}"></span>
                </volist>
            </div>
        </div-->
		
		<if condition="$service_index_lunbo">
			<section id="banner_hei" class="banner">
				<div class="swiper-container swiper-container1">
					<div class="swiper-wrapper">
						<volist name="service_index_lunbo" id="vo">
							<div class="swiper-slide">
								<a href="{pigcms{$vo.url}">
									<img src="{pigcms{$vo.pic}"/>
								</a>
							</div>
						</volist>
					</div>
					<div class="swiper-pagination swiper-pagination1"></div>
				</div>
			</section>
		</if>
         <div class="l-guild">
            <div class="line-title">
                <span class="line-title-txt">一键发布需求，服务快捷方便！</span>
            </div>
            <div class="l-guild-content">
                <img src="{pigcms{$static_path}service/images/step_title_img3.png"></div>
        </div>
        <div class="l-operate">
            <div class="l-flex">
                <a class="l-operate-link l-demand" href="{pigcms{:U('Service/cat_list')}">
                    <i class="ico ico-demand"></i>
                    发布需求
                </a>
                <a class="l-operate-link l-serve" href="{pigcms{:U('Service/search_cate')}">
                    <i class="ico ico-serve"></i>
                    发布服务
                </a>
            </div>
        </div>

        <div class="seo">
            <div class="line-title">
                <span class="line-title-txt">选择以下需求分类快捷发布</span>
            </div>
            <ul class="seo-list">
                
                <volist name="catList" id="vo">
                    <li class="seo-li">
                        <div class="l-title">
                            <a class="seo-publish" href="{pigcms{:U('Service/cat_list',array('cid'=>$vo['cid']))}">
                                <i class="ico ico-edit"></i>
                                发布
                            </a>
                            <div class="l-title-con">
                                <span class="l-left">
                                    {pigcms{$vo.cat_name}
                                    <i class="ico ico-arrow"></i>
                                </span>
                            </div>
                        </div>
                        <div class="l-seo-con">
                            <div class="l-classify clearfix">
                                <volist name="vo['catList']" id="vovo">
                                    <a href="{pigcms{:U('Service/publish_detail',array('cid'=>$vovo['cid'],'type'=>$vovo['type']))}">{pigcms{$vovo.cat_name}</a>
                                </volist>
                            </div>
                        </div>
                    </li>
                </volist>
            </ul>
        </div>
  
    </div>

</div>

<!-- <footer class="footerMenu wap" id="metu_show1" style="display:none;">
        <ul>
            <li>
                <a class="active" href="javascript:void(0);"><em class="home"></em><p>首页</p></a>
            </li>
            <li>
                <a href="{pigcms{:U('Service/cat_list')}"><em class="group"></em><p>发布需求</p></a>
            </li>
          
            <li>
                <a href="{pigcms{:U('Service/need_list')}"><em class="store"></em><p>我的需求</p></a>
            </li>
            <li>
                <a href="{pigcms{:U('My/index')}"><em class="my"></em><p>个人中心</p></a>
            </li>
        </ul>   
</footer>

<footer class="footerMenu wap" id="metu_show2" style="display:none;">
        <ul>
            <li>
                <a class="active" href="javascript:void(0);"><em class="home"></em><p>首页</p></a>
            </li>
            <li>
                <a href="{pigcms{:U('Service/search_cate')}"><em class="group"></em><p>发布服务</p></a>
            </li>

            <li>
                <a href="{pigcms{:U('Service/trade')}"><em class="store"></em><p>报价列表</p></a>
            </li>
            <li>
                <a href="{pigcms{:U('Service/provider_home')}"><em class="my"></em><p>商户主页</p></a>
            </li>
        </ul>   
</footer>
 -->
<style>
    
    .fl { float: left; display: inline; }
    .bottom{ background: #fff; position: fixed; left:0px; bottom:0px; width: 100%; box-shadow: 0px 0px 25px 3px #d8dce0; z-index: 100000;}
    .bottom .bottom_n li { width: 20%; text-align: center; }
    .bottom .bottom_n li a{ width: 100%; display: block;  text-align: center; font-size: 12px; color: #757575; padding-top: 35px;margin-bottom: 5px;}


    .bottom .bottom_n li.xq a{ background: url({pigcms{$static_path}service/images/home/xq.png) center 6px no-repeat;  background-size: 24px 23px; }
    .bottom .bottom_n li.xqon a{ background: url({pigcms{$static_path}service/images/home/xqon.png) center 6px no-repeat;  background-size: 24px 23px; color:#06c1ae; }

    .bottom .bottom_n li.gr a{ background: url({pigcms{$static_path}service/images/home/gr.png) center 6px no-repeat;  background-size: 24px 23px; }
    .bottom .bottom_n li.gron a{ background: url({pigcms{$static_path}service/images/home/gron.png) center 6px no-repeat;  background-size: 24px 23px; color:#06c1ae; }


    .bottom .bottom_n li.home i{ display: inline-block; width: 47px; height: 47px; border-radius: 100%;  background: url({pigcms{$static_path}service/images/home/home.png) center no-repeat #e0e0e0; background-size: 21px 19px; top: -20px; left: 50%; margin-left: -27px;  border: #fff 4px solid; position: absolute; box-shadow: 0px -10px 20px -5px #d8dce0}
    .bottom .bottom_n li.home a{  display:block; position: relative; }
    .bottom .bottom_n li.homeon i{ background: url({pigcms{$static_path}service/images/home/homeon.png) center no-repeat #06c1ae; background-size: 21px 19px;  }
    .bottom .bottom_n li.homeon a{ color: #06c1ae;}


    
    .bottom .bottom_n li.bj a{ background: url({pigcms{$static_path}service/images/home/bj.png) center 6px no-repeat;  background-size: 20px 23px; }
    .bottom .bottom_n li.bjon a{ background: url({pigcms{$static_path}service/images/home/bjon.png) center 6px no-repeat;  background-size: 20px 23px; color:#06c1ae; }

    .bottom .bottom_n li.sh a{ background: url({pigcms{$static_path}service/images/home/sh.png) center 6px no-repeat;  background-size: 20px 23px; }
    .bottom .bottom_n li.shon a{ background: url({pigcms{$static_path}service/images/home/shon.png) center 6px no-repeat;  background-size: 20px 23px; color:#06c1ae; }
.banner img {
    width: 100%;
    height: 100%;
}
</style>
<section class="bottom">
        <div class="bottom_n">
            <ul>
                <li class="xq fl">
                    <a href="{pigcms{:U('Service/need_list')}">需求</a>
                </li>
                <li class="gr fl">
                    <a href="{pigcms{:U('My/index')}">我的</a>
                </li>
                <li class="home homeon fl">
                    <a href="{pigcms{:U('Service/index')}"><i></i>首页</a>
                </li>
                <li class="bj fl">
                    <a href="{pigcms{:U('Service/trade')}">报价</a>
                </li>
                <li class="sh fl">
                    <a href="{pigcms{:U('Service/provider_home')}">服务商</a>
                </li>
            </ul>
        </div>
</section>
<script type="text/javascript">
    /*banner*/
    $(function(){
       var banner_height	=	$(window).width()/320;
		banner_height	=	 Math.ceil(banner_height*119);
		
		$("#banner_hei").css('height',banner_height);
		
		if($('.swiper-container1').size() > 0){
			var mySwiper = $('.swiper-container1').swiper({
				pagination:'.swiper-pagination1',
				loop:true,
				grabCursor: true,
				paginationClickable: true,
				autoplay:3000,
				autoplayDisableOnInteraction:false,
				simulateTouch:false
			});
		}
	
      
    });

    
</script>
<script type="text/javascript">
window.shareData = {
	"moduleName":"Home",
	"moduleID":"0",
	"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
	"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Service/index')}",
	"tTitle": "服务快派 - {pigcms{$config.site_name}",
	"tContent": "{pigcms{$config.site_name}"
};
</script>
{pigcms{$shareScript}
</body>
</html>