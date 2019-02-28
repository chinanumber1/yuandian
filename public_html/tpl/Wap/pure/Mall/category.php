<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>商城分类</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mall.css"/>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<body>
	<section class="search">
		<input type="text" placeholder="搜索商品名称" class="se_input">
		<a href="javascript:void(0)" id="search">搜索</a>
	</section>
	<section class="foodleft">
		<div class="foodnav">
		<ul>
		<volist name="category_list" id="row">
		<li><a href="javascript:void(0)" data-cat_id="{pigcms{$row['id']}" <if condition="empty($key)">class="on"</if>>{pigcms{$row['name']}</a></li>
		</volist>
		</ul>
		</div>
	</section>
	
	<section class="foodright">
		<volist name="category_list" id="rowset">
			<dl class="foodright-{pigcms{$rowset['id']} clr"  data-cat_id="{pigcms{$rowset['id']}">
			<dt>{pigcms{$rowset['name']}</dt>
			<volist name="rowset['son_list']" id="vo">
			<dd><a href="{pigcms{:U('Mall/goods_list', array('cat_fid' => $rowset['id'], 'cat_id' => $vo['id']))}">{pigcms{$vo['name']}</a></dd>
			</volist>
			</dl>
		</volist>
	</section> 
	<!-- 底部 -->
	<include file="footer"/>
</body>
</html>
<script type="text/javascript">
//背景单窗高度  

/*左侧滚动条*/
$(function() {
	var hi = $(window).height(), w = $(window).width();
	$(".foodnav, .foodright").css("height", hi - 98);
	$(".se_input").width(w - 100);
	var myScroll2 = new IScroll('.foodnav',{ click: true });
	$(".foodright").scroll(function(){
		var top = $(".foodright").scrollTop(), menu = $(".foodnav"), item = $(".foodright dl"), onid = "";
		item.each(function() {
			var n = $(this);
			var itemtop = $('.foodright-'+$(this).data('cat_id')).offset().top-$('.foodright').offset().top+$('.foodright').scrollTop();
			if (top > itemtop - 80) {
				onid = n.data('cat_id');
			}
		});
		var link = menu.find(".on");
		link.removeClass("on");
		menu.find("[data-cat_id="+onid+"]").addClass("on");
	});
	
	$(document).on('click','.foodnav a',function(){
		$('.foodright').animate({scrollTop:$('.foodright-'+$(this).data('cat_id')).offset().top-$('.foodright').offset().top+$('.foodright').scrollTop()},500);
	});
	$(".foodright dl").last().css("min-height",hi-50);

	$('#search').click(function(){
		if ($('.se_input').val().length > 0) {
			location.href = "{pigcms{:U('Mall/search')}&key=" + $('.se_input').val();
		}
	});
});
</script>