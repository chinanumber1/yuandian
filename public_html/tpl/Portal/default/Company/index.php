<include file="Public/header" />
<link href="{pigcms{$static_path}css/merchants2013.css" type="text/css" rel="stylesheet" />
<div class="content w-1200 clearfix">
	</div>
	<div class="banner_bg">
		<div class="w-1200 po_re">
			<div class="search clearfix">
				<div>
					<input class="s_ipt" type="text" id="_key" value="{pigcms{$_GET['wd']}" placeholder="输入您要查找的商家关键字" />
					<button class="s_btn" onclick="txt_search()" >搜索</button>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		// 区域搜索
		function area_click(obj){
			$(obj).addClass('selected').siblings('a').removeClass('selected');
			do_search();
		}

		// 排序
		function order_click(obj){
			$(obj).parent('li').addClass('cur').siblings('li').removeClass('cur');
			do_search();
		}

		// 是否签约商家
		function isverify_click(obj){
			var flag = $(obj).parent('li').hasClass('cur');
			if(flag){
				$(obj).parent('li').removeClass('cur');
			}else{
				$(obj).parent('li').addClass('cur');
			}
			do_search();
		}

		// 公司名称搜索
		function txt_search(){
			do_search();
		}

		// 执行搜索
		function do_search(){
			var search_url = "{pigcms{:U('Company/index')}";
			var area_id = $('#div_area').children('a[class="selected"]').attr('area_id');
			var order_flag = $('#ul_order li[class="cur"]').children('a').attr('order_flag');
			if(area_id != undefined){
				search_url += '&area_id='+area_id;
			}
			if(order_flag != undefined){
				search_url += '&order_flag='+order_flag;
			}
			// 签约商家
			var isverify = $('#s_f_1').hasClass('cur')?1:0;
			if(isverify){
				search_url += '&isverify='+isverify;
			}
			var title = $.trim($('#_key').val());
			if(title != ''){
				search_url += '&wd='+title;
			}
			window.location.href = search_url;
		}


	</script>
	<div class="w-1200">
		<div class="grid_k02 clearfix">
			<div class="col_main" id="fixed_can"><div class="main_wrap">
				<div class="quyu filter">
					<div class="line line2" id="div_area">
						<span class="sp">区域：</span>
						<a href='javascript:;' onclick="area_click(this)" area_id="0" <if condition="$area_id eq 0">class='selected'</if>>全部区域</a>
						<if condition="$area_list">
						<volist name="area_list" id="vo">
						<a href='JavaScript:;' onclick="area_click(this)" area_id="{pigcms{$vo.area_id}" <if condition="$area_id eq $vo['area_id']">class="selected"</if> >{pigcms{$vo.area_name}</a>
						</volist>
						</if>
						<div class="small display0"></div>
					</div>
				</div>
				<div class="mod_filter po_re">
					<div class="sortbar clearfix">
						<ul class="left" id="ul_order">
							<li class="tit">排序方式：</li>
							<li <if condition="$order_flag eq 0">class="cur"</if> ><a href="javascript:;" onclick="order_click(this)" order_flag="0" >智能排序<span class="top"></span></a></li>
							<li <if condition="$order_flag eq 1">class="cur"</if> ><a href="javascript:;" onclick="order_click(this)" order_flag="1" >人均<span class="top"></span></a></li>
							<li <if condition="$order_flag eq 2">class="cur"</if> ><a href="JavaScript:;" onclick="order_click(this)" order_flag="2" >好评<span class="top"></span></a></li>
						</ul>
						
						<div class="business">
							<ul>
								<li id="s_f_1" class="<if condition="$_GET['isverify'] eq 1">cur</if>"><a href="javascript:;" onclick="isverify_click(this)">签约商家</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="company">
					<ul id="hover_bg">
						<if condition="$shop_list">
						<volist name="shop_list" id="vo">
						<li class="item clearfix">
							<if condition="$vo['isverify'] eq 1">
							<p class="qianyue display1">已签约</p>
							</if>
							<div class="img"><a href="/shop/{pigcms{$vo.store_id}.html" target="_blank"><img src="{pigcms{$vo.image}" /></a></div>
							<div class="txt">
								<div class="title clearfix">
									<h3><a href="/shop/{pigcms{$vo.store_id}.html" target="_blank">{pigcms{$vo.name}</a></h3>
									<if condition="$vo['have_group'] eq 1">
									<span class="tuan display1">团</span>
									</if>
									<span class="quan display0">券</span>
									<if condition="$vo['store_discount'] neq 0">
									<span class="zhe display1">{pigcms{$vo.store_discount}折</span>
									</if>
									<!--
									<span class="po">置顶</span>
									-->
									<div class="mobile"><a href="javascript:void(0);">手机下单</a>
										<div class="pos"><img src="/index.php?g=Index&c=Recognition&a=see_qrcode&type=shop&id={pigcms{$vo.store_id}" /><s class="s"></s></div>
									</div>
								</div>
								
								<p class="info2">
									<!--
									<span class="i_star i_star_5">5星</span>
									-->
									<a href="/shop/comment/{pigcms{$vo.store_id}.html" target="_blank" class="cmt">{pigcms{$vo.reply_count}条评论</a><span class="xf display1">人均<s>&yen;</s><em>{pigcms{$vo.permoney}</em></span></p>
								<p class="info">{pigcms{$vo.cat_name}　<span class="chraddress">{pigcms{$vo.adress}</span></p>
								
							</div>
							<div class="btn po_ab">
								<a href="/shop/{pigcms{$vo.store_id}.html" target="_blank" class="go">进入店铺</a>
							</div>
						</li>
						</volist>
						</if>
					</ul>
					<div class="pageNavigation" style="border:1px solid #eee; border-top:0 none;">{pigcms{$pagebar}</div>
				</div>
			</div></div>
			<div class="col_sub">
				<div id="fixed">
					<div class="list_nav_2017" id="list_nav_2013">
						<ul>
							<li class="all"><a href="{pigcms{:U('Company/index')}" class="t">全部商家</a></li>
							<if condition="$all_shop_category">
							<volist name="all_shop_category" id="vo">
							<li class="item <?=$pid==$vo['cat_id']?'open_foreven':'';?>" id="item_14"><span class="sp"><a href="{pigcms{:U('Company/index',array('pid'=>$vo['cat_id']))}">{pigcms{$vo.cat_name}</a><s class="rights"></s></span>
								<ul id="items0" class="ul none">
									<li><a href="{pigcms{:U('Company/index',array('pid'=>$vo['cat_id']))}">全部分类</a></li>
									<if condition="$vo['son_list']">
									<volist name="vo['son_list']" id="vv">
									<li <?=$vv['cat_id']==$cid?'class="cur"':'';?>><a href="{pigcms{:U('Company/index',array('cid'=>$vv['cat_id'],'parent_id'=>$vo['cat_id']))}" >{pigcms{$vv.cat_name}</a></li>
									</volist>
									</if>
								</ul>
							</li>
							</volist>
							</if>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
<include file="Public/footer" />

<script src="{pigcms{$static_path}public/js/select.jQuery.js"></script>
<script src="{pigcms{$static_path}public/js/dropDown2017.js"></script>
<script>
window['isKuaiDianNav'] = true;
window['isKuaiDianUrl'] = '';
$(function() {
    $('#fixed_menu').rmenuShow2016();
	$('#mySle').selectbox();
	$(document).modCity();
	$('#fabu').showMore();
	$('#weixin').showMore();
	//$('#fixed').fixed($('#fixed_can'));
	
	$('#s_e_0').addClass('cur');
	$('#s_f_0').addClass('cur');
	if('1'==='0'){
		// $('#s_f_1').find('a').attr('href','/c_index_a0_b0_c0_d0_e0_f0_g0_h0_i0_p1.html')
	}
	$('#hover_bg').find('.item').hover(function(){
		$(this).addClass('hover');
	},function(){
		$(this).removeClass('hover');
	}).end().find('.mobile').hover(function(){
		$(this).toggleClass('open');
	}).end().find('.weixin').each(function(){
		if($(this).attr('data-img') !== ''){
			$(this).addClass('hasImg').hover(function(){
				$(this).toggleClass('open');
			});
		}
	});
	
	//$('#list_nav_2013').find('.cur').parent().parent().addClass('open_foreven');
	if('0' !=='' && '0' !=='0'){
		$('#item_0').addClass('open_foreven');
	}
	
});
</script>
</body>
</html> 