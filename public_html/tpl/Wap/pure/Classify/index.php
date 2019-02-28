<!DOCTYPE html>
<html lang="en">
<head>
    <title>{pigcms{$config.classify_name}</title>
    <include file="Public:classify_header" />
	
<!--<section class="topSearch">-->
    <!--<div class="wrap">-->
        <!--<div class="searchNow clearfix">-->
            <!--<button class="fr"><i class="fa  fa-search"></i></button>-->
            <!--<div class="inputRow ofh">-->
                <!--<input placeholder="找你所找 寻你所寻" type="text"/>-->
            <!--</div>-->
        <!--</div>-->
    <!--</div>-->
<!--</section>-->
<style type="text/css">
.topSearchImg{ height:60px}
.slider{
	background-color: white;
	border-bottom: 1px solid #edebeb;
	margin-bottom: 10px;
}
.icon-list .icon {
  float: left;
  width: 25%;
  text-align: center;
}
.icon-list.num10 .icon {
  float: left;
  width: 25%;
  text-align: center;
}
.icon-list .icon > a {
  padding-top: 12px;
  display: block;
}
.icon-list .icon-circle {
  display: block;
  margin: auto;
  width: 40px;
  height: 40px;
  text-align: center;
  color: white;
  margin-bottom: 3px;
}
.icon-list.num10{
	margin-top: 7px;
}
.icon-list .icon-circle img {
  width: 100%;
  height: 100%;
}
.icon-list .icon-desc {
  text-align: center;
  color: #666;
}
.icon-list.num10 .icon-desc {
	font-size:12px;
}
.slider .swiper-pagination{
	bottom:2px;
}
.slider .swiper-pagination-switch{
	background-color: white;
	border:1px solid #d8d8d8;
	width:8px;
	height:8px;
	margin-right: 5px;
	opacity: 1;
}
.slider .swiper-active-switch{
	background: #06c1ae;
	border:1px solid #06c1ae;
}

.slider .swiper-pagination-bullet-active{
	background: #06c1ae;
	border:1px solid #06c1ae;
}
.icon-desc{
	 overflow: hidden;
	 white-space:nowrap;
	text-overflow: ellipsis;
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
}
</style>
<section class="topSearchImg">
    <a href="{pigcms{:U('search')}">
        <img src="{pigcms{$static_path}classify/images/placeholder/sc.png"/>
    </a>
</section>



<if condition="!empty($classify_index_ad)">
	<section class="scroll banner">
		<!-- Swiper -->
		<div class="swiper-container swiper-container-banner">
			<div class="swiper-wrapper">
				<volist name="classify_index_ad" id="adimg">
					<div class="swiper-slide" onclick="location.href='{pigcms{$adimg['url']}'">
						<img src="{pigcms{$adimg['pic']}" >
					</div>
				</volist>
			</div>
			<div class="swiper-pagination swiper-pagination-banner"></div>
		</div>
	</section>
</if>
<if condition="$wap_classify_slider  ">

						
						
		<section class="slider">
			<div class="swiper-container swiper-container2" style="height: 178px; cursor: -webkit-grab;">
					<div class="swiper-wrapper" >
				<volist name="wap_classify_slider" id="slider">
					<div class="swiper-slide">
						<ul class="icon-list num10">
						<volist name="slider" id="voo">
								<li class="icon">
									<a href="{pigcms{$voo['url']}">
										<span class="icon-circle">
											<img src="{pigcms{$voo.pic}">
										</span>
										<span class="icon-desc">{pigcms{$voo.name}</span>
									</a>
								</li>
							</volist>										
						</ul>				
					</div>
				</volist>
					</div>
					
					<if condition="count($wap_classify_slider) gt 1">
				<div class="swiper-pagination swiper-pagination2">
					</if>
				</div>
			</div>
										
		</section>
		</if>

<section class="indexList">
    <ul>

	<if condition="!empty($Zcategorys)">
	 <volist name="Zcategorys" id="zv">
        <li>
            <div class="hd">
                <div class="wrap">
                    <a href="{pigcms{:U('Classify/SelectSub',array('cid'=>$zv['cid']))}#ct_item_{pigcms{$zv['cid']}" name="ct_item_{pigcms{$zv['cid']}" class="fr" style="margin-top:0.4rem"><img src="{pigcms{$static_path}classify/images/icon/write.png"/> 发布信息</a>
                    <h2><if condition="$zv['cat_pic']"><i><img src="{pigcms{$config.site_url}/upload/system/{pigcms{$zv.cat_pic}"/></i></if><span <if condition='!empty($zv["font_color"])'>style="color:{pigcms{$zv['font_color']}"</if>>{pigcms{$zv['cat_name']}</span></h2>
                </div>
            </div>
            <div class="bd">
                <div class="wrap">
                    <div class="row no-gutter tc">
					<if condition="!empty($zv['subdir'])">
					  <php>$tt=count($zv['subdir']);</php>
					  <volist name="zv['subdir']" id="sv" mod="3" key="m">
                        <div class="col-33"><a href="{pigcms{:U('Classify/Lists',array('cid'=>$sv['cid']))}">{pigcms{$sv['cat_name']}</a></div>
						</volist>
						</if>
                    </div>
                </div>
            </div>
        </li>
        </volist>
   </if>
    </ul>
</section>

<section class="ftHeight"></section>

<include file="Public:classify_footer" />
<script>
    var swiper = new Swiper('.swiper-container-banner', {
        loop:true,
        autoplay: 5000,//可选选项，自动滑动
        // 如果需要分页器
        pagination: '.swiper-pagination-banner'
    });
	
	  var swiper = new Swiper('.swiper-container2', {


			<if condition="count($wap_classify_slider) gt 1">
			loop:true,
			</if>
			grabCursor: true,
			paginationClickable: true,
			simulateTouch:false,
			 pagination: '.swiper-pagination2'
    });

window.shareData = {
	"moduleName":"Classify",
	"moduleID":"0",
	"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
	"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Classify/index')}",
	"tTitle": "{pigcms{$config.classify_name}首页",
	"tContent": "{pigcms{$config.classify_name}"
};

</script>
{pigcms{$shareScript}
	{pigcms{$coupon_html}
</body>
</html>