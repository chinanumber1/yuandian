<!DOCTYPE html>
<html lang="en">
<head>
    <title>分类搜索</title>
 <include file="Public:classify_header" />
 <style type="text/css">
.search_ajax{background-color:#FFF;display:none;width:100%;position:relative;border:none;z-index:0;top:0;left:0}.search_container .search_ajax a{width:100%;height:100%}.search_container .search_ajax ul{width:100%}.search_container .search_ajax ul li{color:#374565;height:44px;line-height:44px;padding:0 5px;border-bottom:1px solid #ececec;display:block;word-break:break-all}.search_container .search_ajax ul li span:first-child{font-size:16px;color:#374565;font-weight:700;padding-right:22px}.search_container .search_ajax ul li span:nth-child(2){font-size:11px;color:#b5bbc4}.search_container .search_ajax ul li:last-child{text-align:left;border-bottom:1px solid #ececec;padding:0 5px}.search_container .search_ajax ul li a{width:100%;height:auto;display:inline-block}.search_container .search_ajax ul li span.searchFont{color:#999;padding-right:0;font-weight:400}.search_container .search_ajax ul li span.searchDesk{color:#FF6C00;padding-right:0;font-size:16px;font-weight:400}
 </style>
 
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_path}classify/js/myhonepage.js"></script> 
<form action="" method="get" onsubmit="win.getData();return false;">
	<section class="topSearch">
		<div class="wrap">
			<div class="searchNow clearfix">
				<button class="fr"><i class="fa  fa-search"></i></button>
				<div class="inputRow ofh">
					<input type="text" name="key" class="input_keys" id="keyWords1" value="" onblur="win.blur()" onfocus="win.focus()" onkeyup="win.getData()" autocomplete="off" placeholder="找你所找，寻你所寻" style="color: rgb(55, 69, 101); font-size: 17px; text-indent: 5px;">
				</div>
			</div>
		</div>
		<input type="submit" style="display:none">
	</section>
</form>
<div class="search_ajax"> </div>
<section class="ftHeight"></section>

 <include file="Public:classify_footer" />
<script>
    var swiper = new Swiper('.swiper-container-banner', {
        loop:true,
        autoplay: 5000,//可选选项，自动滑动
        // 如果需要分页器
        pagination: '.swiper-pagination-banner'
    });
</script>

</body>
</html>