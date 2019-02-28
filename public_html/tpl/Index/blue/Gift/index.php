<include file="Public:gift_header"/>
<section class="banner">
    <div class="flashBox">
        <ul>
			<volist name='gift_slider_list' id='slider'>
				<li style="background-image: url('{pigcms{$slider.pic}');cursor:pointer" onclick="location.href='{pigcms{$slider.url}'">
				</li>
			</volist>
        </ul>
        <ol>
			<volist name='gift_slider_list' id='slider'>
				<li class=""></li>
			</volist>
        </ol>
        <div class="bannerBtn">
            <div class="w1200">
                <a href="javascript:;" class="prev"></a><a href="javascript:;" class="next"></a>
            </div>
        </div>
    </div>
</section>


<section class="processTag">
    <div class="w1200">
        <div class="pTit fl">
        </div>
        <div class="midUl">
            <ul>
                <li class="i1">
                    <a href="##">
                        <div class="icon">
                            <i></i>
                        </div>
                        <div class="iconText">
                            <h3>挑选礼品</h3>
                            <p>第一步</p>
                        </div>
                    </a>
                </li>
                <li class="i2">
                    <a href="##">
                        <div class="icon">
                            <i></i>
                        </div>
                        <div class="iconText">
                            <h3>兑换方式</h3>
                            <p>第二步</p>
                        </div>
                    </a>
                </li>
                <li class="i3">
                    <a href="##">
                        <div class="icon">
                            <i></i>
                        </div>
                        <div class="iconText">
                            <h3>支付完成</h3>
                            <p>第三步</p>
                        </div>
                    </a>
                </li>
                <li class="i4">
                    <a href="##">
                        <div class="icon">
                            <i></i>
                        </div>
                        <div class="iconText">
                            <h3>收货信息</h3>
                            <p>第四步</p>
                        </div>
                    </a>
                </li>
                <li class="i5">
                    <a href="##">
                        <div class="icon">
                            <i></i>
                        </div>
                        <div class="iconText">
                            <h3>完成兑换</h3>
                            <p>第五步</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</section>

<section class="mainSection">
	<if condition="$good_gift_category_list['list']">
    <div class="secRow indexRow1">
        <div class="w1200">
			
            <div class="iTitle clearfix">
                <h3 class="fl title"><i class="dib"></i>热门分类</h3><p class="fl subTitle">挑选您想要的礼品</p>
            </div>
            <div class="rowCell">
                <ul>
					<volist name='good_gift_category_list["list"]' id='good_gift_cagtegory'>
						<li>
							<a href="{pigcms{:U('gift_list',array('cat_id'=>$good_gift_cagtegory['cat_id']))}">
								<div class="categoryText">
									<h2>{pigcms{$good_gift_cagtegory['cat_name']}</h2>
									<p>{pigcms{$good_gift_cagtegory['desc']|msubstr=0,16}</p>
								</div>
								<img src="{pigcms{$config.site_url}/upload/system/{pigcms{$good_gift_cagtegory['cat_pic']}" width="298px" height="198px"/>
							</a>
						</li>
                   </volist>
                </ul>
            </div>

        </div>
    </div>
	</if>
    <div class="secRow indexRow2">
        <div class="w1200">
            <div class="iTitle clearfix">
                <h3 class="fl title"><i class="dib"></i>热销礼品</h3><p class="fl subTitle">精品礼品任您选</p>
            </div>

            <div class="rowCell">
                <div class="hotRank fr">
                    <h2><strong>NEW</strong>新品推荐</h2>
                    <ul class="hotList">
						<volist name='new_gift_list["list"]' id='gift'>
							<li>
								<i class="rankNum">{pigcms{$i}</i>
								<a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}">
									<div class="i-pic fr">
										<img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['pc_pic_list'][0]}"/>
									</div>
									<div class="proText">
										<h3>{pigcms{$gift.gift_name|msubstr=0,9}</h3>
										<if condition='in_array($gift["exchange_type"],array(0,2))'><p>{pigcms{$config['score_name']}:{pigcms{$gift.payment_pure_integral}<!--em>{pigcms{$config['score_name']}</em--></p></if>
										<if condition='in_array($gift["exchange_type"],array(1,2))'>
										 <p>现金+:{pigcms{$gift.payment_integral}<!--em>{pigcms{$config['score_name']}</em--><em>+</em>￥{pigcms{$gift.payment_money}<em></em></p>
										</if>
									</div>
								</a>
							</li>
						</volist>
                    </ul>
                </div>
                <div class="leftCell">
                    <div class="row-up clearfix">
                        <ul>
							 <li class="secondLi">
                                <ol>
								<volist name='gift_list["list"]' id='gift' offset='0' length='2'>
                                    <li>
                                        <div class="wrap">
                                            <div class="proInfo">
                                                <h3>{pigcms{$gift.gift_name|msubstr=0,15}</h3>
                                                <if condition='in_array($gift["exchange_type"],array(0,2))'><p>{pigcms{$config['score_name']}：{pigcms{$gift.payment_pure_integral}<em>{pigcms{$config['score_name']}</em></p></if>
											<if condition='in_array($gift["exchange_type"],array(1,2))'>
											 <p>现金+：{pigcms{$gift.payment_integral}<em>{pigcms{$config['score_name']}</em><em>+</em>{pigcms{$gift.payment_money}<em>元</em></p></if>
                                                <a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}" class="exchangeBtn dib">马上兑换</a>
                                            </div>
											<img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['pc_pic_list'][0]}" />
                                        </div>
                                    </li>
                                 </volist>  
                                </ol>
                            </li>
							
                            <li class="secondLi">
                                <ol>
								<volist name='gift_list["list"]' id='gift' offset='2' length='2'>
                                    <li>
                                        <div class="wrap">
                                            <img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['pc_pic_list'][0]}" />
                                            <div class="proInfo">
                                                <h3>{pigcms{$gift.gift_name|msubstr=0,15}</h3>
                                                <if condition='in_array($gift["exchange_type"],array(0,2))'><p>{pigcms{$config['score_name']}：{pigcms{$gift.payment_pure_integral}<em>{pigcms{$config['score_name']}</em></p></if>
											<if condition='in_array($gift["exchange_type"],array(1,2))'>
											 <p>现金+：{pigcms{$gift.payment_integral}<em>{pigcms{$config['score_name']}</em><em>+</em>{pigcms{$gift.payment_money}<em>元</em></p></if>
                                                <a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}" class="exchangeBtn dib">马上兑换</a>
                                            </div>
                                        </div>
                                    </li>
                                 </volist>  
                                </ol>
                            </li>

                            <li class="normalLi">
								<volist name='gift_list["list"]' id='gift' offset='4' length='1'>
									<div class="wrap">
										<img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['pc_pic_list'][0]}" width="227" height="182"/>
										<div class="proInfo">
											<h3>{pigcms{$gift.gift_name|msubstr=0,9}</h3>
											<if condition='in_array($gift["exchange_type"],array(0,2))'><p>{pigcms{$config['score_name']}：{pigcms{$gift.payment_pure_integral}<em>{pigcms{$config['score_name']}</em></p></if>
											<if condition='in_array($gift["exchange_type"],array(1,2))'>
											 <p>现金+：{pigcms{$gift.payment_integral}<em>{pigcms{$config['score_name']}</em><em>+</em>{pigcms{$gift.payment_money}<em>元</em></p></if>
											<a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}" class="exchangeBtn dib">马上兑换</a>
										</div>
									</div>
								</volist>
                            </li>
                        </ul>
                    </div>
                    <div class="row-down clearfix">
                        <ul>
							<volist name='gift_list["list"]' id='gift' offset='5' length='4'>
								<li class="normalLi">
									<div class="wrap">
										<img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['pc_pic_list'][0]}" width="227" height="182"/>
										<div class="proInfo">
											<h3>{pigcms{$gift.gift_name|msubstr=0,9}</h3>
											<if condition='in_array($gift["exchange_type"],array(0,2))'><p>{pigcms{$config['score_name']}：{pigcms{$gift.payment_pure_integral}<em>{pigcms{$config['score_name']}</em></p></if>
											<if condition='in_array($gift["exchange_type"],array(1,2))'>
											 <p>现金+：{pigcms{$gift.payment_integral}<em>{pigcms{$config['score_name']}</em><em>+</em>{pigcms{$gift.payment_money}<em>元</em></p></if>
											<a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}" class="exchangeBtn dib">马上兑换</a>
										</div>
									</div>
								</li>
                            </volist>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="secRow indexRow3">
        <div class="w1200">
            <div class="iTitle clearfix">
                <div class="fr rightTagAndBtn">
                    <!--a class="lastBtn fr" href="{pigcms{:U('Gift/gift_list',array('cat_id'=>2))}"></a>
            <         <div class="tagGroup ofh">
					<volist name='good_gift_category_list["list"]' id='good_gift_cagtegory' offset='0' length='4'>
                        <a href="{pigcms{:U('gift_list',array('cat_id'=>$good_gift_cagtegory['cat_id']))}">
                            {pigcms{$good_gift_cagtegory['cat_name']}
                        </a>
                    </volist>
                    </div> -->
                </div>
                <h3 class="fl title"><i class="dib"></i>高端生活</h3><p class="fl subTitle">用{pigcms{$config['score_name']}换取美好生活</p>
            </div>
            <div class="rowCell clearfix">
				
				
                <div class="list">
                    <ul>
					<volist name='integral_gift_list["list"]' id='gift' offset = '0' length='10'>
                        <li>
                            <div class="wrap">
                                <a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}">
                                    <div class="proInfo">
                                        <h3>{pigcms{$gift.gift_name|msubstr=0,15}</h3>
                                        <h4>{pigcms{$gift.intro|msubstr=0,15}</h4>
                                        <if condition='in_array($gift["exchange_type"],array(0,2))'><p>{pigcms{$config['score_name']}：{pigcms{$gift.payment_pure_integral}<em>{pigcms{$config['score_name']}</em></p></if>
											<if condition='in_array($gift["exchange_type"],array(1,2))'>
											 <p>现金+：{pigcms{$gift.payment_integral}<em>{pigcms{$config['score_name']}</em><em>+</em>{pigcms{$gift.payment_money}<em>元</em></p></if>
                                    </div>
                                    <div class="i-pic">
                                        <img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['pc_pic_list'][0]}"/>
                                    </div>
                                </a>
                            </div>
                        </li>
                   </volist> 
                    </ul>
                </div>
            </div>
        </div>
    </div>
	<if condition='$gift_index_list'>
		<volist name='gift_index_list' id='index_list'>
    <div class="secRow indexRow4 customScroll">
		<div class="w1200">
			<div class="iTitle clearfix">
				<div class="fr rightTagAndBtn">
					<a class="lastBtn fr" href="{pigcms{:U('Gift/gift_list',array('cat_id'=>$index_list['cat_id']))}"></a>
					<div class="flashBtn fr clearfix">
						<a class="left" href="javascript:;"></a>
						<a class="right" href="javascript:;"></a>
					</div>
				</div>
				<h3 class="fl title"><i class="dib"></i>{pigcms{$index_list['cat_name']}</h3><p class="fl subTitle">{pigcms{$index_list['desc']}</p>
			</div>
			<div class="rowCell clearfix">
				<div class="list scrollList">
					<ul>
					<volist name='index_list["gift_list"]' id='gift' offset='0'>
						<li>
							<div class="wrap">
								<a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}">
									<div class="proInfo">
										<h3>{pigcms{$gift.gift_name|msubstr=0,12}</h3>
										<h4>{pigcms{$gift.intro|msubstr=0,12}</h4>
										<if condition='in_array($gift["exchange_type"],array(0,2))'><p>{pigcms{$config['score_name']}：{pigcms{$gift.payment_pure_integral}<em>{pigcms{$config['score_name']}</em></p></if>
											<if condition='in_array($gift["exchange_type"],array(1,2))'>
											 <p>现金+：{pigcms{$gift.payment_integral}<em>{pigcms{$config['score_name']}</em><em>+</em>{pigcms{$gift.payment_money}<em>元</em></p></if>
									</div>
									<div class="i-pic">
										<img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['pc_pic_list'][0]}"/>
									</div>
								</a>
							</div>
						</li>
					</volist>
					</ul>
				</div>
			</div>
		</div>
    </div>
	</volist>
	</if>
</section>

<include file="Public:gift_footer"/>
<script>
$('.customScroll').each(function(){
	getcustomScroll($(this))
});

function getcustomScroll(obj){
		var left=obj.find(".left");
		var right=obj.find(".right");
		var i=0;
		var t=null;
		var n=4;

		var li=obj.find(".scrollList ul li");
		var len=li.length;
		var w=li.outerWidth(true);
		var scrollUl=obj.find(".scrollList ul");
		scrollUl.width(w*len);
		var page_count = Math.ceil(len / n);
		var pageW=w*n;


		left.bind('click',function(){
			prevBtn();
			Scroll();
		});
		right.bind('click',function(){
			nextBtn();
			Scroll();
		});
		function nextBtn() {
			i++;
			if (i == page_count) {
				i = 0
			}
		}
		function prevBtn() {
			i--;
			if (i < 0) {
				i = page_count - 1
			}
		}
		function Scroll(){
			scrollUl.stop().animate({
					'margin-left': -pageW * i + 'px'
				},
				1000);
		}
	}
</script>