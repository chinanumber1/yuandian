<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>商家中心</title>
<link href="{pigcms{$static_path}css/mershop.css" rel="stylesheet"/>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/swiper-3.3.1.jquery.min.js"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>

<body>
    <section class="shops">
        <div class="shops_100">
            <div class="shop_back" style="background: url({pigcms{$static_path}images/xqt_02.jpg) 100% "></div>
            <div class="shops_top clr">
                <div class="img view_album fl" data-pics="<volist name="now_store['all_pic']" id="vo">{pigcms{$vo}<if condition="count($now_store['all_pic']) gt $i">,</if></volist>">
					<img src="{pigcms{$now_store.all_pic.0}" alt="{pigcms{$now_store.name}" width="100%" height="100%">
                    <p>{pigcms{:count($now_store['all_pic'])}张</p>
                </div>
                <div class="p100">
				
				<h2>{pigcms{$now_store.name}</h2>
				<if condition="$store_score">
					
                    <div class="score">
                        <div class="atar_Show" >
                            <p style="width:{pigcms{$store_score['score_all']/$store_score['reply_count']*20}%;"></p>
                        </div>
						
                        <span>{pigcms{:number_format($store_score['score_all']/$store_score['reply_count'],1)}</span>
                    </div>
				<else/>
					<span style="color:#999">暂无评分</span>
				</if>
                </div>
            </div>
        </div>
        <div class="shops_dz p8 bf botf1">
            <a href="{pigcms{:U('Group/addressinfo',array('store_id'=>$now_store['store_id']))}">
                <div class="dz_text">
                    {pigcms{$now_store.area_name}{pigcms{$now_store.adress}
                </div>
                <div class="shops_ck"><span>查看</span></div>
            </a>
        </div>
        <div class="shops_pho p8 bf">
            <div class="shop_a">
                <a href="tel:{pigcms{$now_store.phone}">{pigcms{$now_store.phone}</a>
            </div>
			<if condition="$config['pay_in_store']">
				<a href="{pigcms{:U('My/pay',array('store_id' => $now_store['store_id']))}" class="btn"><div class="shops_yb"><span>{pigcms{$config.cash_alias_name}</span></div></a>
			</if>
            
        </div>

        <div class="shops_url bf m8">
            <a href="{pigcms{:U('Wap/Shop/index')}#shop-{pigcms{$_GET['store_id']}" class="wm">{pigcms{$config.shop_alias_name}</a>
            <a href="{pigcms{:U('Wap/Foodshop/shop',array('store_id'=>$_GET['store_id']))}" class="dc">订餐点餐</a>
            <a href="{pigcms{:U('Wap/Foodshop/queue',array('store_id'=>$_GET['store_id']))}" class="ph">排号</a>
            <a href="{pigcms{$now_store.weidian_url}" class="ds">商家微电商</a>
        </div>

		<if condition="$store_group_list">
			 <div class="shop_this overflow_hi bf m8">
			<h2><span class="bt">本店{pigcms{$config.group_alias_name}({pigcms{:count($store_group_list)})</span></h2>
            <ul>
			<volist name="store_group_list" id="vo">
                <li>
                    <a href="{pigcms{$vo.url}">
						<php> if($vo['pin_num'] > 0){ </php><div class="pin_style"></div> <php> }  </php>
                        <div class="img fl">
                            <img src="./upload/group/{pigcms{:str_replace(',','/',$vo['pic'])}" width="100%" height="100%">
                        </div>
                        <div class="m100">
                            <div class="tit">{pigcms{$vo.group_name}</div>
                            <div class="clr text">
                                <div class="fl"><span>{pigcms{$vo['price']}</span>元<if condition="$vo.extra_pay_price neq ''">{pigcms{$vo.extra_pay_price}</if></div>
								<if condition="$vo['wx_cheap']"> <div class="fl text_wx">微信再减{pigcms{$vo.wx_cheap}元</div></if>
                                <div class="fr">已售{pigcms{$vo['sale_count']+$vo['virtual_num']}</div>
                            </div>
                        </div>
                    </a>
                </li>
			</volist>
				</ul>
				<a href="javascript:void(0)" class="more">
					<span>展开更多</span>
				</a>
			</div>
		</if>
       
		<if condition="$appoint_list">
        <div class="shop_this overflow_hi shop_bespoke m8  bf">
            <h2><span class="bt">本店预约（{pigcms{:count($appoint_list)}）</span></h2>
            <ul>
			<volist name="appoint_list" id="vo">
                <li>
                    <a href="{pigcms{:U('Wap/Appoint/detail',array('appoint_id'=>$vo['appoint_id']))}">
                        <div class="img fl">
                            <img src="{pigcms{$vo.cat_pic}" width="100%" height="100%">
                        </div>
                        <div class="m100">
                            <div class="tit">{pigcms{$vo.appoint_name}</div>
                            <div class="tit_jj">{pigcms{$vo.desc}</div>
                            <div class="clr text">
                                <div class="fl clr">
                                    <span class="fl">定金:￥</span>
                                    <span class="fl shop_jg">{pigcms{$vo.payment_money}</span>
                                    <span class="fl shop_dd">到店</span>
                                </div>
                                <div class="fr">已预约{pigcms{$vo.appoint_sum}</div>
                            </div>
                        </div>
                    </a>
                </li>
				</volist>
			<a href="javascript:void(0)" class="more">
                <span>展开更多</span>
            </a>
        </div>
		</if>
		<if condition="$activity_list">
        <div class="shop_this overflow_hi shop_avi m8 bf">
            <h2><span class="bt">活动</span></h2>
            <ul>
			<volist name="activity_list" id="vo">
                <li>
                    <a href="{pigcms{$config['site_url']}/wap.php?c=Wxapp&a=location_href&wxscan=1&id={pigcms{$vo.id}">
                        <div class="img fl">
                            <img src="{pigcms{$vo.image}" width="100%" height="100%">
                        </div>
                        <div class="m100">
                            <div class="shop_kj">{pigcms{$vo.title}</div>
                            <div class="shop_kj">{pigcms{$vo.info}</div>
                        </div>
                    </a>
                </li>
			</volist>
			</ul>
			<a href="javascript:void(0)" class="more">
                <span>展开更多</span>
            </a>
        </div>
		</if>
    
            


        <div class="shop_this shop_pj m8 bf">
            <h2>
                <a href="{pigcms{$now_store.reply_url}" class="clr">
                    <span class="fl bt">评价</span>
                    <if condition="$store_score">
						<div class="score">
							<div class="atar_Show" >
								<p style="width:{pigcms{$store_score['score_all']/$store_score['reply_count']*20}%;"></p>
							</div>
							
							<span>{pigcms{:number_format($store_score['score_all']/$store_score['reply_count'],1)}</span>
						</div>
					<else/>
						<span style="color:#999">暂无评分</span>
					</if>
						<div class="fr shop_app">{pigcms{$reply_count}人评价</div>
                </a>
            </h2>
            <dl>
				<volist name="reply_list" id="vo">
					<dd>
						<div class="shoppl_text p8 bf">
							<div class="pltext_top clr">
								<span class="fl">{pigcms{$vo.nickname}</span>
								<span class="fl ari">{pigcms{$vo.add_time|date='Y-m-d H:i',###}</span>
								<div class="score">
									<div class="atar_Show">
										<p style="width:{pigcms{$vo['score']/5*20}%;"></p>
									</div>
									<span style="display: none;">{pigcms{$vo['score']}</span>
								</div>
							</div>
							<div class="txt">{pigcms{$vo.comment}</div>
							<if condition="!empty($vo['pic'])">
							<div class="pho">
								<ul class="clr">
									
									<volist name="vo['pic']" id="vv">
										<php>$tmp_pic = str_replace(',','/',$vv['pic']);$tmp_path = explode('_',$vv['name']);</php>
										<li>
											<img src="./upload/reply/{pigcms{$tmp_path.0}/{pigcms{$tmp_pic}" width="100%"  height="100%">
										</li>
									</volist>
								</ul>
							</div>
							</if>
									
							<div class="shop_hf">{pigcms{$vo.merchant_reply_content}</div>
						</div>  
					</dd>
				</volist>
               
            </dl>
            
        </div>
    </section> 
</body>


<script type="text/javascript">
      /*评分*/
    $(".atar_Show p").each(function(index, element) {
        var num=$(this).parents(".score").find("span").text();
        var www=num*18;//
        $(this).css("width",www);
    });

    /*展开 收起*/
        $(".overflow_hi ul").each(function(){
                if($(this).find("li").length > 2){
                   $(this).siblings("a.more").show();
                   $(this).addClass("showMore");
                }else{ 
                    $(this).siblings("a.more").hide();
                }
            })
   
        $(".overflow_hi a.more").toggle(function(){
            $(this).addClass("morelv").find("span").text("收起更多");
            $(this).siblings("ul").addClass("hasMore")
        },function(){
            $(this).removeClass("morelv").find("span").text("展开更多");
            $(this).siblings("ul").removeClass("hasMore")
        })

    /*评论图片比例*/
    $(".shoppl_text .pho img").each(function(){
        $(this).height($(this).width());
    })

    /*t头部阴影宽度*/
    $(".shops .shops_top").width($(window).width()-30)
</script>


<!-- 查看大图 -->
<script type="text/javascript">
    $('.view_album').click(function(){
        // $('#buy_box').removeAttr('style');
        // show_buy_box = false;
        var album_more = $(this).attr('data-pics');
        var album_array = album_more.split(',');
        
        // if(is_weixin()){
        //     wx.previewImage({
        //         current:album_array[0],
        //         urls:album_array
        //     });
        // }else{
            var album_html = '<div class="albumContainer" style="display:block;">';
                album_html += '<div class="swiper-container">';
                album_html += '     <div class="swiper-wrapper">';
            $.each(album_array,function(i,item){
                album_html += '         <div class="swiper-slide">';
                album_html += '             <img src="'+item+'"/>';
                album_html += '         </div>';
            });
                album_html += '     </div>';
                album_html += '     <div class="swiper-pagination"></div><div class="swiper-close" onclick="close_swiper()">X</div>';
                album_html += '</div>';
            
            album_html += '</div>';
            $('body').append(album_html);
        
            mySwiper = $('.swiper-container').swiper({
                pagination:'.swiper-pagination',
                // loop:true,
                grabCursor: true,
                paginationClickable: true
            });
        // }
    });
    function close_swiper(){
            $('.albumContainer').remove();
            // show_buy_box = true;
        }

</script>


</html>



