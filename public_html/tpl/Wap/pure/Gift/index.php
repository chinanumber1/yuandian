<!DOCTYPE html>
<html lang="en">
<head>
    <title>{pigcms{$config.gift_alias_name}首页</title>
<include file="Public:gift_header" />
<script type="text/javascript" language="javascript">
var ajax_gift_list_url = "{pigcms{:U('ajax_gift_list')}";
var site_gift_url = "{pigcms{$config.site_url}/upload/system/gift/";
var gift_list_url = "{pigcms{:U('gift_list')}";
var gift_detail_url = "{pigcms{:U('gift_detail')}"
var  score_name = "{pigcms{$config.score_name}";
</script>
<style type="text/css" language="javascript">
.rectMod{ height:100%}
</style>
<body>
<div class="lodingCover">
    <div class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<!--section class="top">
    <div class="wrap clearfix">
        <a href="##" class="fr topBtn"><i class="fa fa-search"></i></a>
    </div>
</section-->
<if condition='!empty($gift_adver_list)'>
	<section class="scroll" id="banner_hei">
		<div class="swiper-container swiper-container-banner"  style="height: 100%;">
			<div class="swiper-wrapper">
				<volist name='gift_adver_list' id='slider'>
					<div class="swiper-slide" onclick="location.href='{pigcms{$slider.url}'">
						<img style="height:100%" src="{pigcms{$slider.pic}" >
					</div>
				</volist>
			</div>
			<div class="swiper-pagination swiper-pagination-banner"></div>
		</div>	
	</section>
</if>


<if condition='!empty($now_user)'>
<nav class="topNav indexNav">
    <ul class="box">
        <li class="b-flex">
            <a href="##">
                <i><img src="{pigcms{$static_path}gift/images/i1.png"></i><span>{pigcms{$now_user['score_count']}分</span>
            </a>
        </li>
        <li class="b-flex">
            <a href="##">
                <i><img src="{pigcms{$static_path}gift/images/i2.png"></i><span>{pigcms{$now_user['now_money']}元</span>
            </a>
        </li>
        <li class="b-flex">
            <a href="{pigcms{:U('My/integral')}">
                <i><img src="{pigcms{$static_path}gift/images/i3.png"></i><span>兑换记录</span>
            </a>
        </li>
    </ul>
</nav>
</if>

<section class="index3rect">
    <div class="leftRect">
        <div class="r1 fl pr rectMod">
            <a href="{pigcms{:U('gift_list',array('type'=>'new'))}">
                <img class="pa" src="{pigcms{$static_path}gift/images/placeholder/q1.png"/>
                <div class="desc">
                    <h2>今日新品</h2>
                    <p>挑选您想要的礼品</p>
                </div>
            </a>
        </div>
        <div class="ofh br-l-eee">
            <div class=" r2">
                <a href="{pigcms{:U('fast_gift')}">
                    <div class=" pr rectMod">
                        <img class="pa" src="{pigcms{$static_path}gift/images/placeholder/q2.png"/>
                        <div class="desc">
                            <h2>我能兑换</h2>
                            <p>一目了然 想兑就兑</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="r2">
                <a href="{pigcms{:U('gift_list',array('type'=>'integral'))}">
                    <div class="br-t-eee pr rectMod">
                        <img class="pa" src="{pigcms{$static_path}gift/images/placeholder/q4.png"/>
                        <div class="desc">
                            <h2>高端生活</h2>
                            <p>用{pigcms{$config['score_name']}换取美好生活</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="scrollList">
    <section class="navThis pr">
        <nav class="scrollNav pa" id="scrollerBox">
            <div class="scrollerIn">
                <ul class="clearfix">
					<volist name='gift_category_list["list"]' id='gift_category'>
						<li data-cat-fid="{pigcms{$gift_category['cat_id']}" <if condition='$i eq 1'>class="on"</if>>
							<a href="javascript:void(0)">{pigcms{$gift_category['cat_name']}</a>
						</li>
					</volist>
                </ul>
            </div>
        </nav>
    </section>
        <div class="content-padded">
            <div class="row no-gutter" id="gift_list">
            </div>
        </div>
</section>
<include file="Public:gift_footer" />
<script>
    var swiper = new Swiper('.swiper-container-banner', {
        loop:true,
        autoplay: 5000,//可选选项，自动滑动
        // 如果需要分页器
        pagination: '.swiper-pagination-banner'
    });
</script>
<script type="text/javascript">
    //滚动导航
    $(function(){
		$('.scrollNav ul li').css({'width':'auto','padding':'0 5px'});
		var scrollWidth = 0;
		$.each($('#scrollerBox li'),function(i,item){
			scrollWidth += $(item).width();
		});
		$('.navThis .scrollerIn').width(scrollWidth);
		$('.navThis').css({'overflow-x':'auto'});
		var cat_fid = $(".scrollNav ul li.on").attr('data-cat-fid');
		get_gift_list(cat_fid,score_name);
    });

	if($('#banner_hei').size() > 0){
		var banner_height	=	$(window).width()/320;
		banner_height	=	 Math.ceil(banner_height*119);
		$('#banner_hei').height(banner_height);
	}
</script>

<script type="text/javascript">
window.shareData = {
	"moduleName":"Gift",
	"moduleID":"0",
	"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
	"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Gift/index')}",
	"tTitle": "{pigcms{$config.gift_alias_name}首页",
	"tContent": "{pigcms{$config.site_name}"
};
</script>
{pigcms{$shareScript}
	{pigcms{$coupon_html}
</body>
</html>