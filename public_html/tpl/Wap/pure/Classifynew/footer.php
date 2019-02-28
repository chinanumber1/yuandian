<div id="backtotop" class="backtotop" > 
	<span class="icon-vertical-align-top">
		<i class="iconfont icon-iconfontarrowup"></i>
	</span>
</div>
<script>
function get_currenturl() {
	var loc = location.href.split('#')[0];
	return loc;
}
function Anmi(obj, x, fast, callback) {
	var ani = ' animated';
	if(fast == 'fast'){
		ani = ' animated-fast';
	}
	obj.removeClass().addClass(x + ani).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
		jQuery(this).removeClass();
		if(callback && typeof callback == 'function') {
			callback();
		}
	});
}
jQuery(function(){
	var f_iosActionsheet = jQuery('#f_iosActionsheet');
	var f_iosMask = jQuery('#f_iosMask');
	function f_hideActionSheet() {
		f_iosActionsheet.removeClass('weui-actionsheet_toggle');
		f_iosMask.fadeOut(200);
	}
	f_iosMask.on('click', f_hideActionSheet);
	jQuery('#f_iosActionsheetCancel').on('click', f_hideActionSheet);
	jQuery(" .f_sharebtn").removeAttr('onclick').unbind( "click").unbind("touchstart").unbind("touchend").on("click", function(){
		f_iosActionsheet.addClass('weui-actionsheet_toggle');
		f_iosMask.fadeIn(200);
	});
});

</script>
<script>
	var wechatbtn = jQuery('.weixin,.weixin_timeline');
	var wechatmask = jQuery('#wechat-masker');
	wechatmask.find('div').hide();
	
	var wechatguider = jQuery('#other-guider');
	var showmethd = 'fadeInDown';
	
	wechatguider.show();

	wechatbtn.on('click',function(){
		wechatmask.show();
		Anmi(wechatguider, showmethd, 'fast');
		Anmi(wechatmask, 'fadeIn', 'fast');
	});
	wechatmask.on('click',function(){
		Anmi(wechatguider, 'fadeOutUp', 'normal');
		Anmi(wechatmask, 'fadeOut', 'normal', function(){
			wechatmask.hide();
		});
	});
</script>
<if condition="$config['is_demo_domain']">
	<script>
		function demoDomain_tip(text,title){
			$.alert(text+"<br/><br/><div style='font-size:12px;letter-spacing:1px;color:#ccc;'>此提醒仅小猪O2O演示站可见</div>", title);
		}
	</script>
</if>
<div id="wechat-mask"><div id="wechat-guider"></div></div>
<div class="mask none"></div>
<script src="{pigcms{$static_path}classifynew/js/fastclick.js" type="text/javascript"></script>
<script src="{pigcms{$static_path}classifynew/js/slider.js" type="text/javascript"></script>
<script src="{pigcms{$static_path}classifynew/js/swiper.min.js" type="text/javascript"></script>
<script src="{pigcms{$static_path}classifynew/js/md5.min.js" type="text/javascript"></script>
<script src="{pigcms{$static_path}classifynew/js/app.js?t={pigcms{:time()}" type="text/javascript"></script>
<script>
function hb_dig(id){
	$.actions({
		title: '请选择置顶类型',
		actions: [            
			{text: "置顶一天(收费{pigcms{$config['classify_settop_day_money']}元)", onClick: function () {hb_jump('{pigcms{:U('settop')}&digtype=1&id=' + id+_URLEXT);}},
			{text: "置顶一周(收费{pigcms{$config['classify_settop_day_money']*7}元)", onClick: function () {hb_jump('{pigcms{:U('settop')}&digtype=7&id=' + id+_URLEXT);}},
			{text: "置顶15天(收费{pigcms{$config['classify_settop_day_money']*15}元)", onClick: function () {hb_jump('{pigcms{:U('settop')}&digtype=15&id=' + id+_URLEXT);}},
			{text: "置顶一月(收费{pigcms{$config['classify_settop_day_money']*30}元)", onClick: function () {hb_jump('{pigcms{:U('settop')}&digtype=30&id=' + id+_URLEXT);}},
		]
	});
}
function hb_hbchoice(id){
	$.actions({
		title: '请选择红包金额',
		actions: [            
			{text: "1元分5包",onClick: function() { hb_jump('{pigcms{:U('redpack')}&redpack_money=1&id='+id+_URLEXT);}},
			{text: "5元分8包",onClick: function() { hb_jump('{pigcms{:U('redpack')}&redpack_money=5&id='+id+_URLEXT);}},
			{text: "10元分20包",onClick: function() { hb_jump('{pigcms{:U('redpack')}&redpack_money=10&id='+id+_URLEXT);}},
			{text: "50元分100包",onClick: function() { hb_jump('{pigcms{:U('redpack')}&redpack_money=50&id='+id+_URLEXT);}},
		]
	});
}
function hb_shuaxin(id){
	var classify_refresh_money = {pigcms{$config.classify_refresh_money|floatval=###};
	if(classify_refresh_money > 0){
		$.confirm({
			title: '刷新信息',
			text: '刷新信息需要支付<strong class="amount">'+classify_refresh_money+'</strong>元<br>您确认要刷新选定的信息吗?',
			onOK: function () {
				hb_jump('{pigcms{:U('refresh')}&id=' + id+_URLEXT);
			}
		});
	}else{
		hb_jump('{pigcms{:U('refresh')}&id=' + id+_URLEXT);
	}
}
function hb_paytel(id, pri, pricat){
	$.confirm({
		title: '查看联系方式',
		text: '查看联系方式需支付<strong class="amount">'+pri+'</strong>元<br>确认支付？',
		onOK: function () {
			hb_jump('{pigcms{:U('paytel')}&cat='+pricat+'&id=' + id+_URLEXT);
		}
	});
}
function showansi(obj) {
	var act = [], that = $(obj);
	var catid = that.data('catid'), id= that.data('id');

	if(catid){
		// act.push({
			// text: '立即支付', onClick: function () {
				// hb_jump('');
			// }
		// });
	}else {
		if(that.data('canhb')){
			act.push({
				text: '红包扩散', onClick: function () {
					$.actions({
						title: '请选择红包金额',
						actions: [
							{text: "1元分5包",onClick: function() {
								hb_jump('{pigcms{:U('redpack')}&redpack_money=1&id='+id+_URLEXT);
							}},
							{text: "5元分8包",onClick: function() {
								hb_jump('{pigcms{:U('redpack')}&redpack_money=5&id='+id+_URLEXT);
							}},
							{text: "10元分20包",onClick: function() {
								hb_jump('{pigcms{:U('redpack')}&redpack_money=10&id='+id+_URLEXT);
							}},
							{text: "50元分100包",onClick: function() {
								hb_jump('{pigcms{:U('redpack')}&redpack_money=50&id='+id+_URLEXT);
							}},
						]
					});
				}
			});
		}
		act.push({
			text: '分享扩散', onClick: function () {
				localStorage.setItem('wetip_'+id, 1);
				hb_jump('{pigcms{:U('view')}&id='+id+_URLEXT);
			}
		});
		if(that.data('canzd')){
			act.push({
				text: '置顶扩散', onClick: function () {
					$.actions({
						title: '请选择置顶类型',
						actions: [
							{
								text: "置顶一天(收费{pigcms{$config['classify_settop_day_money']}元)", onClick: function () {
									hb_jump('{pigcms{:U('settop')}&digtype=1&id=' + id+_URLEXT);
								}
							},
							{
								text: "置顶一周(收费{pigcms{$config['classify_settop_day_money']*7}元)", onClick: function () {
									hb_jump('{pigcms{:U('settop')}&digtype=7&id=' + id+_URLEXT);
								}
							},
							{
								text: "置顶15天(收费{pigcms{$config['classify_settop_day_money']*15}元)", onClick: function () {
									hb_jump('{pigcms{:U('settop')}&digtype=15&id=' + id+_URLEXT);
								}
							},
							{
								text: "置顶一月(收费{pigcms{$config['classify_settop_day_money']*30}元)", onClick: function () {
									hb_jump('{pigcms{:U('settop')}&digtype=30&id=' + id+_URLEXT);
								}
							},
						]
					});
				}
			});
		}
	}
	if(that.data('canzd')) {
		act.push({
			text: '刷新信息', onClick: function () {
				var classify_refresh_money = {pigcms{$config.classify_refresh_money|floatval=###};
				if(classify_refresh_money > 0){
					$.confirm({
						title: '刷新信息',
						text: '刷新信息需要支付<strong class="amount">'+classify_refresh_money+'</strong>元<br>您确认要刷新选定的信息吗?',
						onOK: function () {
							hb_jump('{pigcms{:U('refresh')}&id=' + id+_URLEXT);
						}
					});
				}else{
					hb_jump('{pigcms{:U('refresh')}&id=' + id+_URLEXT);
				}
			}
		});
	}

	
	act.push({
		text: '编辑', onClick: function () {
			hb_jump('{pigcms{:U('edit')}&id='+id+_URLEXT);
		}
	});
	act.push({
		text: '删除', onClick: function () {
			confirm_del('删除无法恢复，确认删除?', '{pigcms{:U('del')}&id='+id+_URLEXT, id);
		}
	});
	$.actions({
		title: '信息ID: '+id,
		actions: act
	});
	hb_setcookie('disable_'+id, 1, 86400);
	return false;
}
</script>
<div class="swiper-container global-lightbox animated" id="globalLightbox">
	<div class="swiper-wrapper" id="globalWrapper">

	</div>
	<div class="swiper-pagination lightbox-pagination"></div>
	<a class="iconfont icon-guanbijiantou closeLightbox"> </a>
</div>
<if condition="$is_wexin_browser">
	<script>
		if(typeof wx !=='undefined'){
			wx.ready(function () {
				if (window.__wxjs_environment === 'miniprogram') {
					$(document).on('click', 'a', function () {
						var that = $(this);
						var jmpurl = that.attr('href');
						if (jmpurl && jmpurl.indexOf('tel') === -1 && jmpurl.indexOf('sms') === -1 && jmpurl.indexOf('java') === -1 && jmpurl.indexOf("{pigcms{:U('Classifynew/index')}") === -1 && jmpurl.indexOf('&city=') === -1) {
							if (jmpurl.indexOf('https://') === -1 && jmpurl.indexOf('http://') === -1) {
								jmpurl = GSITE + jmpurl;
							}
							jmpurl = jmpurl.replace('http://', 'https://');
							if(typeof wx.miniProgram.navigateTo!=='undefined'){
								wx.miniProgram.navigateTo({ url: '/pages/webview/webview?webview_url=' + encodeURIComponent(jmpurl)+'&webview_title='+encodeURIComponent(_APPTITLE)});
								return false;
							}
						}
					});
					hb_setcookie('miniprogram', 1, 120);
					$('#fav_guide_mask').hide();
					$('.g_guide').remove();
				}else{
					hb_setcookie('miniprogram', '', 0);
				}
			});
		}
	</script>
</if>