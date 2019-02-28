<div class="footWrap clearfix" id="footer">
	<footer>
		<div class="foot_Mid clearfix">
			<div class="foot_MC">
				<ul class="clearfix">
					<pigcms:footer_link var_name="footer_link_list">
						<li>
							<a href="{pigcms{$vo.url}" target="_blank">{pigcms{$vo.name}</a>
						</li>
					</pigcms:footer_link>
				</ul>
			</div>
		</div>
		<div class="foot_Bot clearfix"> <p>{pigcms{:nl2br($config['site_show_footer'])} </p> </div>
	</footer>
</div>

<script src="{pigcms{$static_path}js/jquery.SuperSlide.2.1.1.js"></script>
<script src="{pigcms{$static_path}js/select.jQuery.js"></script>
<script>
$(function() {
	$('#mySle').selectbox();
	$(document).modCity();
	$('#fabu').showMore();
	$('#weixin').showMore();
	$('#tab01').TabADS();
	$('#iGo2Top').returnTop2014();
	$(".slide").slide({ mainCell:".bd ul",effect:"leftLoop",vis:1,scroll:1,autoPlay:true}).hover(function(){$(this).toggleClass('hover');});
	$(".slide2").slide({ mainCell:".bd ul",effect:"topLoop",vis:3,scroll:1,autoPlay:true});
	$.fn.sel2015 = function(selector){
		var t = $(this),tit = t.find('.title'),list = t.find('a');
		t.hover(function(){
			window['timer'] = setTimeout(function(){t.addClass('open');},100);
		},function(){
			clearTimeout(window['timer']);
			t.removeClass('open');
		});
		list.bind('click',function(e){
			$('#'+selector).val($(this).attr('data-val'));
			tit.html($(this).html());
			t.trigger('mouseleave');
			e.preventDefault();
		});
	}
	$('#sel_search').sel2015('key');
	$('#newsWeixin .gz').hover(function(){
		$(this).parent().toggleClass('open');
	}).click(function(e){e.preventDefault();});
});
</script>
</body>
</html>