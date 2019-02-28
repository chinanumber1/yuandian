<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>我的优惠券</title>
<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/swiper-3.3.1.min.css"/>
<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/card_new.css"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_path}my_card/js/TouchSlide.1.1.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/swiper-3.3.1.jquery.min.js" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->

</head>
<body>
    <section class="Coupon">
        <div id="slideBox" class="slideBox">
            <div class="swiper-container bd" id="swiper-container3" >
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <ul class="end_ul">
                            <li>
								<if condition="$coupon_list[0]">
									<volist name="coupon_list[0]" id="vo">
									<dl class="Muse">
										<dd>
											<div class="Coupon_top clr">
												<div class="fl">
													<div class="fltop">
														<i>￥</i><em>{pigcms{$vo.discount}</em>
													</div>
													<div class="flend">
														满{pigcms{:floatval($vo['order_money'])}减{pigcms{$vo.discount}
													</div>
												</div>
												<div class="fr">
													<h2>{pigcms{$vo.name}</h2>
													<p>使用平台：{pigcms{$vo.platform}</p>
													<p>使用类别：<php>if($vo['cate_name']=='all'){echo "所有";}else{</php>{pigcms{$vo.cate_name}<php>}</php></p>
												</div>
											</div>
											
											<div class="Coupon_end">
												<div class="Coupon_x">
													<i>{pigcms{$vo.start_time|date='Y年m月d日',###}至{pigcms{$vo.end_time|date='Y年m月d日',###}</i>
													<!--<a href=""><em>立即购买</em></a>-->
												</div>
												<div class="Coupon_sm">
													<span class="on">使用说明</span>
													<div class="Coupon_text overflow">{pigcms{$vo.des}</div>
												</div>    
											</div> 
											<span class="several">{pigcms{$vo.get_num}张</span>
											<i class="bj"></i>    
										</dd>
									</dl>
									</volist>
								<else />
									<div style="margin-top:100px;text-align:center;"><span>您还没有优惠券</span></div>
								</if>
                                <div class="more"><a href="{pigcms{:U('My_card/merchant_coupon_list',array('mer_id'=>$_GET['mer_id']))}">更多好券，去领券中心看看<span></span></a></div>
                            </li>
                        </ul>
                    </div>
                    <div class="swiper-slide">
                        <ul class="end_ul">
                            <li>
								<if condition="$coupon_list[2]">
									<volist name="coupon_list[2]" id="vo">
									<dl class="Expired">
										<dd>
											<div class="Coupon_top clr">
												<div class="fl">
													<div class="fltop">
														<i>￥</i><em>{pigcms{$vo.discount}</em>
													</div>
													<div class="flend">
														满{pigcms{:floatval($vo['order_money'])}减{pigcms{$vo.discount}
													</div>
												</div>
												<div class="fr">
													<h2>{pigcms{$vo.name}</h2>
													<p>使用平台：{pigcms{$vo.platform}</p>
													<p>使用类别：<php>if($vo['cate_name']=='all'){echo "所有";}else{</php>{pigcms{$vo.cate_name}<php>}</php></p>
												</div>
											</div>
											<div class="Coupon_end">
												<div class="Coupon_x">
													<i>{pigcms{$vo.start_time|date='Y年m月d日',###}至{pigcms{$vo.end_time|date='Y年m月d日',###}</i>
												
												</div>
												<div class="Coupon_sm">
													<span class="on">使用说明</span>
													<div class="Coupon_text overflow">{pigcms{$vo.des}</div>
												</div>    
											</div> 
											<span class="several">{pigcms{$vo.get_num}张</span>
											<i class="bj"></i>    
										</dd>
										
									</dl>
									</volist>
								</if>
                                <div class="overdue">
                                    <span>以上是近期已过期的优惠券</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="swiper-slide">
                        <ul class="end_ul">
                            <li>
								<if condition="$coupon_list[1]">
									<volist name="coupon_list[1]" id="vo">
									<dl class="Use">
										<dd>
											<div class="Coupon_top clr">
												<div class="fl">
													<div class="fltop">
														<i>￥</i><em>{pigcms{$vo.discount}</em>
													</div>
													<div class="flend">
														满{pigcms{:floatval($vo['order_money'])}减{pigcms{$vo.discount}
													</div>
												</div>
												<div class="fr">
													<h2>{pigcms{$vo.name}</h2>
													<p>使用平台：{pigcms{$vo.platform}</p>
													<p>使用类别：<php>if($vo['cate_name']=='all'){echo "所有";}else{</php>{pigcms{$vo.cate_name}<php>}</php></p>
												</div>
											</div>
											<div class="Coupon_end">
												<div class="Coupon_x">
													<i>{pigcms{$vo.start_time|date='Y年m月d日',###}至{pigcms{$vo.end_time|date='Y年m月d日',###}</i>
												
												</div>
												<div class="Coupon_sm">
													<span class="on">使用说明</span>
													<div class="Coupon_text overflow">{pigcms{$vo.des}</div>
												</div>    
											</div> 
											<span class="several">{pigcms{$vo.get_num}张</span>
											<i class="bj"></i>    
										</dd>
									</dl>
									</volist>
								</if>
                                <div class="overdue">
                                    <span>以上是近期已使用的优惠券</span>
                                </div>
                            </li>
                        </ul>
                    </div>   
                </div> 
            </div>  
            <div class="swiper-container hd" id="swiper-container2" >
                <div class="swiper-wrapper">
                    <div class="swiper-slide active-nav">
                        未使用 (<i>3</i>)
                    </div>
                    <div class="swiper-slide">
                        已过期 (<i>3</i>)
                    </div>
                    <div class="swiper-slide">
                        已使用 (<i>3</i>)
                    </div>
                    
                </div>
            </div>
        </div> 
    </section>    
</body>

<script type="text/javascript">
    // 使用次数
	var u=0;
    var s=0;
    var e=0;
	$('.Muse dd .several').each(function(){
		var value = $(this).html().replace(/[^0-9]/ig,""); 
		u+=parseInt(value);
	});
	$('.Expired dd .several').each(function(){
		var value = $(this).html().replace(/[^0-9]/ig,""); 
		s+=parseInt(value);
	});
	$('.Use dd .several').each(function(){
		var value = $(this).html().replace(/[^0-9]/ig,""); 
		e+=parseInt(value);
	});
	
    
    
    $(".hd .swiper-slide:nth-child(1)").find("i").text(u)
    $(".hd .swiper-slide:nth-child(2)").find("i").text(s)
    $(".hd .swiper-slide:nth-child(3)").find("i").text(e)


    $(".Coupon_sm").each(function(){
        $(this).find("span").click(function(){
            if($(this).hasClass("on")){
                $(this).removeClass("on")
                $(this).siblings(".Coupon_text").removeClass("overflow"); 
                $(this).parents("dd").siblings().find(".Coupon_sm span").addClass("on");
                $(this).parents("dd").siblings().find(".Coupon_sm .Coupon_text").addClass("overflow"); 
            }else{
                $(this).addClass("on")
                $(this).siblings(".Coupon_text").addClass("overflow"); 
            }
            
        })
    })

</script>

<script> 
    var mySwiper2 = new Swiper('#swiper-container2',{
    watchSlidesProgress : true,
    watchSlidesVisibility : true,
    slidesPerView : 3,
    onTap: function(){
                mySwiper3.slideTo( mySwiper2.clickedIndex)
            }
    })
    var mySwiper3 = new Swiper('#swiper-container3',{
           autoHeight: true,

    onSlideChangeStart: function(){
                updateNavPosition()
            }
    })

    function updateNavPosition(){
            $('#swiper-container2 .active-nav').removeClass('active-nav')
            var activeNav = $('#swiper-container2 .swiper-slide').eq(mySwiper3.activeIndex).addClass('active-nav');
            if (!activeNav.hasClass('swiper-slide-visible')) {
                if (activeNav.index()>mySwiper2.activeIndex) {
                    var thumbsPerNav = Math.floor(mySwiper2.width/activeNav.width())-1
                    mySwiper2.slideTo(activeNav.index()-thumbsPerNav)
                }
                else {
                    mySwiper2.slideTo(activeNav.index())
                }   
            }
        }
</script>

</html>

 
   
  


 