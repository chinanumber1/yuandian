<include file="Food:header" />
<script type="text/javascript" src="{pigcms{$static_path}meal/js/dialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}meal/js/scroller.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}meal/js/dmain.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}meal/js/menu.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
<body onselectstart="return true;" ondragstart="return false;">
<style>
.menu_detail .btndiv1 {
    position: absolute;
    right: 14px;
    margin-top: 5px;
    width: 78px;
    height: 25px;
}
.menu_detail .btn.del {
    background-position: -27px -44px;
}
.menu_detail .btn.active {
    background-color: #f9f9f9;
}
.menu_detail .num {
    line-height: 25px;
    text-align: center;
    border-width: 1px 0;
}
.menu_detail .btn, .menu_detail .num {
    float: left;
    width: 25px;
    height: 25px;
    background-color: #fff;
    border-width: 1px;
    -webkit-border-image: url(../tpl/Wap/default/static/takeout/image/border.gif) 2 stretch;
}
.menu_detail .btn.add {
    background-position: 0 -44px;
}
.menu_detail .btn.active {
    background-color: #f9f9f9;
}
.menu_detail .btn {
    display: inline-block;
    background: url(../tpl/Wap/default/static/takeout/image/s.png) no-repeat;
    background-size: 150px auto;
}



#speaker{
	top:0;
    width: 100%;
    height: 40px;
    line-height: 40px;
    position: fixed;
    z-index: 980;
    background-color: #fffddf;
    opacity: 0.95;
    overflow: hidden;
    box-shadow:0px 0px 2px #222;
    -webkit-box-shadow:0px 0px 2px #222;
}
#s-word{
	font-size: 13px;
	width: 82%;
	height: 40px;
	position: fixed;
	left: 40px;
	
}
#s-icon{
	width: 20px;
	height: 20px;
	position: fixed;
	top: 10px;
	left: 10px;
	background-color: #fffddf;
	background-size: 20px;
	background-repeat: no-repeat;
	background-image: url(../tpl/Wap/default/static/takeout/image/speaker.png);
}
#s-fork{
	width: 20px;
	height: 20px;
	position: fixed;
	top: 10px;
	right: 10px;
	background-color: #fffddf;
	background-size: 20px;
	background-repeat: no-repeat;
	background-image: url(../tpl/Wap/default/static/takeout/image/yellowfork.png);
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$('.mylovedish').click(function(){
		var id = parseInt($(this).find('.thisdid').val());
		var islove = 0;
		if ($(this).parents('li').attr('class') == 'like') {
			islove = 1;
		}
		$.post("{pigcms{:U('Food/dolike', array('mer_id' => $mer_id, 'store_id' => $store_id))}", {meal_id:id,islove:islove}, function(msg){});
	});
	$('#s-fork').click(function(){
		$('#speaker').hide();
		$('#l-nav').css({'top':0});
		$('#right').css({'top':0});
		$('.menu section, .g_nav').css('margin-top', '0px');
	});
	<if condition="!empty($store['store_notice'])">
	$('.menu section, .g_nav').css('margin-top', '40px');
	</if>
	$("img.lazy_img").lazyload({ threshold :180, container: $("#usermenu")});
});

var islock=false;
function next()
{
	totalPrice = parseFloat($.trim($('#allmoney').text()));
	totalNum = parseInt($.trim($('#menucount').text()));
	if((totalNum>0) && (totalPrice>0)){
		var data=getMenuChecklist();//[{'id':id,'count':count},{'id':id,'count':count}]
		if((data.length>0) && !islock){
			islock=true;
			$('#nextstep').removeClass('orange show').addClass('gray disabled');
			$.ajax({
				type: "POST",
				url: "{pigcms{:U('Food/processOrder', array('mer_id' => $mer_id, 'store_id' => $store_id))}",
				data: {"cart":data},
				async:true,
				success: function(res){
					islock=false;
					$('#nextstep').removeClass('gray disabled').addClass('orange show');
					if (res.error ==0) { 
					  window.location.href = "{pigcms{:U('Food/cart', array('mer_id' => $mer_id, 'store_id' => $store_id, 'orid' => $orid))}";
					} else {
					  alert(res.msg);
					}
				},
				dataType: "json"
			  });
			}else{
				return false;
			}
		}else{
			return false;
		}
}
</script>
<div data-role="container" class="container menu">
	<if condition="!empty($store['store_notice'])">
	<div id="speaker">
		<div id="s-icon"></div>
		<span id="s-word"><marquee behavior="scroll" scrollamount="5" direction="left" width="100%" style="width: 100%;">{pigcms{$store['store_notice']}</marquee></span>
		<div id="s-fork"></div>
	</div>
	</if>
	<section data-role="body">
		<div class="left">
			<div class="top">
				<div id="ILike"><a><span class="icon hartblckgray"></span>我喜欢</a></div>
			</div>
			<div class="top">
				<div id="all_dish"><a><span></span>全部商品</a></div>
			</div>
			<div class="content">
				<ul id="typeList"><!--class="on"-->
					<volist name="sortlist" id="so">
					<li id="li_type{pigcms{$so['sort_id']}">{pigcms{$so['sort_name']}</li>
					</volist>
				</ul>
			</div>
		</div>
		<div class="right" id="usermenu">
			<div class="all" id="menuList">
			<if condition="!empty($meals)">
				<volist name="meals" id="rowset">
					<ul id="ul_type{pigcms{$rowset['sort_id']}">
						<volist name="rowset['list']" id="meal">
						<li id="dish_li{pigcms{$meal['meal_id']}" <if condition="$meal['like']">class="like"</if>>
						 <div class="licontent">
							<div class="span showPop">
								<if condition="!empty($meal['image'])">
								<img alt="" class="lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{$meal['image']}" url="{pigcms{$meal['image']}"/>
								<else />
								<img src="../static/images/nopic.jpg">
								</if>
							</div>
							<div class="menudesc showPop">
								<h3>{pigcms{$meal['name']}</h3>
								<p class="salenum">已售<span class="sale_num"> {pigcms{$meal['sell_count']} </span><span class="theunit"><if condition="!empty($meal['unit'])">{pigcms{$meal['unit']}<else/>份</if></span></p>
								<p class="mylovedish"> <span class="icon hart"><input autocomplete="off" class="thisdid" type="hidden" value="{pigcms{$meal['meal_id']}"></span></p>
								<div class="info">{pigcms{$meal['des']|htmlspecialchars_decode=ENT_QUOTES}</div>
							</div>
							<div class="price_wrap">
								<strong>￥<span class="unit_price">{pigcms{$meal['price']}</span><input type="hidden" class="tureunit_price" value="{pigcms{$meal['price']}"></strong>
								<div class="fr" max="{pigcms{$meal['max']}">
									 <a href="javascript:void(0);" class="btn plus" data-num="{pigcms{$meal['num']}"></a>
								</div>
								<input autocomplete="off" class="number" type="hidden" name="dish[{pigcms{$meal['meal_id']}]" value="0">
							</div>
						</div>
						</li>
						</volist>
					</ul>
				</volist>
			</if>
			</div>
		</div>
	</section>
</div>
<footer data-role="footer">			
	<nav class="g_nav">
		<div>
			<span class="cart"></span>
			<span> <span class="money">￥<label id="allmoney">0</label> </span>/<label id="menucount">0</label>份</span>
			<a href="javascript:next();" class="btn gray disabled" id="nextstep">选好了</a>
		</div>
	</nav>
</footer>
	<div class="menu_detail" id="menuDetail">
		<img style="display: none;">
		<div class="nopic"></div>
		<!--a href="javascript:void(0);" class="comm_btn" id="detailBtn">来一份</a-->
		
		<div class="showfixd">
		<div class="btndiv1"><span><a class="btn del active"></a><span class="num">1</span></span><a class="btn add active" id="detailBtn" max="93"></a></div>
		<dl>
			<dt>价格：</dt>
			<dd class="highlight">￥<span class="price"></span></dd>
		</dl>
		</div>
		<p class="sale_desc">月售<span class="sale_num"></span>份</p>
		<dl>
			<dt>介绍：</dt>
			<dd class="info"></dd>
		</dl>
	</div>
<!--div class="menu_detail" id="menuDetail">
	<img style="display: none;">
	<div class="nopic"></div>
	<a href="javascript:void(0);" class="comm_btn" id="detailBtn">来一份</a>
	<dl>
		<dt>价格：</dt>
		<dd class="highlight">￥<span class="price"></span></dd>
	</dl>
	<p class="sale_desc"></p>
	<dl class="desc">
		<dt>介绍：</dt>
		<dd class="info"></dd>
	</dl>
</div-->
<include file="kefu" />
<script type="text/javascript">
window.shareData = {  
            "moduleName":"Food",
            "moduleID":"0",
            "imgUrl": "{pigcms{$store.image}", 
            "sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Food/menu',array('mer_id' => $mer_id, 'store_id' => $store_id))}",
            "tTitle": "{pigcms{$store.name}",
            "tContent": "{pigcms{$store.txt_info}"
};
</script>
{pigcms{$shareScript}

</body>
</html>
