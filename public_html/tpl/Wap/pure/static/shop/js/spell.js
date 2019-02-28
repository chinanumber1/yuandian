/* jQuery cookie 操作*/
jQuery.cookie = function (key, value, options) {
    if (arguments.length > 1 && (value === null || typeof value !== "object")){
        options = jQuery.extend({}, options);
        if (value === null) {
            options.expires = -1;
        }
        if (typeof options.expires === 'number'){
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }
        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? String(value) : encodeURIComponent(String(value)),
            options.expires ? '; expires=' + options.expires.toUTCString() : '',
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};
/** laytpl-v1.1 */

;!function(){"use strict";var f,b={open:"{{",close:"}}"},c={exp:function(a){return new RegExp(a,"g")},query:function(a,c,e){var f=["#([\\s\\S])+?","([^{#}])*?"][a||0];return d((c||"")+b.open+f+b.close+(e||""))},escape:function(a){return String(a||"").replace(/&(?!#?[a-zA-Z0-9]+;)/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/'/g,"&#39;").replace(/"/g,"&quot;")},error:function(a,b){var c="Laytpl Error：";return"object"==typeof console&&console.error(c+a+"\n"+(b||"")),c+a}},d=c.exp,e=function(a){this.tpl=a};e.pt=e.prototype,e.pt.parse=function(a,e){var f=this,g=a,h=d("^"+b.open+"#",""),i=d(b.close+"$","");a=a.replace(/[\r\t\n]/g," ").replace(d(b.open+"#"),b.open+"# ").replace(d(b.close+"}"),"} "+b.close).replace(/\\/g,"\\\\").replace(/(?="|')/g,"\\").replace(c.query(),function(a){return a=a.replace(h,"").replace(i,""),'";'+a.replace(/\\/g,"")+'; view+="'}).replace(c.query(1),function(a){var c='"+(';return a.replace(/\s/g,"")===b.open+b.close?"":(a=a.replace(d(b.open+"|"+b.close),""),/^=/.test(a)&&(a=a.replace(/^=/,""),c='"+_escape_('),c+a.replace(/\\/g,"")+')+"')}),a='"use strict";var view = "'+a+'";return view;';try{return f.cache=a=new Function("d, _escape_",a),a(e,c.escape)}catch(j){return delete f.cache,c.error(j,g)}},e.pt.render=function(a,b){var e,d=this;return a?(e=d.cache?d.cache(a,c.escape):d.parse(d.tpl,a),b?(b(e),void 0):e):c.error("no data")},f=function(a){return"string"!=typeof a?c.error("Template not found"):new e(a)},f.config=function(a){a=a||{};for(var c in a)b[c]=a[c]},f.v="1.1","function"==typeof define?define(function(){return f}):"undefined"!=typeof exports?module.exports=f:window.laytpl=f}();
var motify = {
		timer:null,
		/*shade 为 object调用 show为true显示 opcity 透明度*/
		log:function(msg,time,shade){
			$('.motifyShade,.motify').hide();
			if(motify.timer) clearTimeout(motify.timer);
			if($('.motify').size() > 0){
				$('.motify').show().find('.motify-inner').html(msg);
			}else{
				$('body').append('<div class="motify" style="display:block;"><div class="motify-inner">'+msg+'</div></div>');
			}
			if(shade && shade.show){
				if($('.motifyShade').size() > 0){
					$('.motifyShade').css({'background-color':'rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+')'}).show();
				}else{
					$('body').append('<div class="motifyShade" style="display:block;background-color:rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+');"></div>');
				}
			}
			if(typeof(time) == 'undefined'){
				time = 3000;
			}
			if(time != 0){
				motify.timer = setTimeout(function(){
					$('.motify').hide();
				},time);
			}
		},
		clearLog:function(){
			$('.motifyShade,.motify').hide();
		}
};

$(function(){
	if (openid != '') {
		var jsonArray = {'index':openid, 'name':name, 'avatar':avatar};
//		jsonArray.index = openid;
//		jsonArray.name = name;
//		jsonArray.avatar = avatar;
		window.localStorage.setItem(cartid, JSON.stringify(jsonArray));
	}
	init_load();
	
	$('.choose').click(function(){
		var jsonData = window.localStorage.getItem(cartid);
		var index = '', name = '', avatar = '';
		if (jsonData != '' && jsonData != null) {
			jsonData = $.parseJSON(jsonData);
			index = jsonData.index;
			avatar = jsonData.avatar;
			name = jsonData.name;
		}
		$.post(root_url + 'addNew', {'cartid':cartid, 'store_id':store_id, 'name':name, 'avatar':avatar, 'index':index, 'from':1}, function(response){
			if (response.error == false) {
				var jsonArray = {'index':response.index, 'name':response.name, 'avatar':response.avatar};
//				jsonArray.index = response.index;
//				jsonArray.name = response.name;
//				jsonArray.avatar = response.avatar;
				window.localStorage.setItem(cartid, JSON.stringify(jsonArray));
				window.location.href = root_url + 'index&frm=spell&index=' + response.index + '&cartid=' + cartid + "#shop-" + store_id;
			} else {
				motify.log(response.msg);
			}
		}, 'json');
	});
	
	$(document).on('click', '.copyme', function(){
		var jsonData = window.localStorage.getItem(cartid);
		var index = '', name = '', avatar = '';
		if (jsonData != '' && jsonData != null) {
			jsonData = $.parseJSON(jsonData);
			index = jsonData.index;
			avatar = jsonData.avatar;
			name = jsonData.name;
		}
		$.post(root_url + 'addNew', {'cartid':cartid, 'store_id':store_id, 'name':name, 'avatar':avatar, 'index':index, 'from':1, 'copyindex':$(this).data('index')}, function(response){
			if (response.error == false) {
				var jsonArray = {'index':response.index, 'name':response.name, 'avatar':response.avatar};
//				jsonArray.index = response.index;
//				jsonArray.name = response.name;
//				jsonArray.avatar = response.avatar;
				window.localStorage.setItem(cartid, JSON.stringify(jsonArray));
				init_load();
			} else {
				motify.log(response.msg);
			}
		}, 'json');
	});
	
	//列表删除
	$(document).on('click', '.many_end .del', function(){
		var obj = $(this), jsonData = window.localStorage.getItem(cartid);
		var index = '';
		if (jsonData != '' && jsonData != null) {
			jsonData = $.parseJSON(jsonData);
			index = jsonData.index;
		}
		layer.open({
			content: '您确定不订了吗？',
			btn: ['确定', '取消'],
			yes: function(inx){
				layer.close(inx);
				$.post(root_url + 'delCart', {'index':index, 'cartid':cartid, 'store_id':store_id}, function(response){
					if (response.error == false) {
						window.localStorage.removeItem(cartid + '_' + jsonData.index);
						obj.parents("li").slideUp(function(){
							obj.remove();
						});
						init_load();
					} else {
						motify.log(response.msg);
					}
				}, 'json');
			}
		});
	});
	setInterval(init_load, 10000);
});

function init_load()
{
	var jsonData = window.localStorage.getItem(cartid);
	var myindex = '';
	if (jsonData != '' && jsonData != null) {
		jsonData = $.parseJSON(jsonData);
		myindex = jsonData.index;
	}
	$.get(root_url + 'ajaxAll', {'cartid':cartid, 'myindex':myindex, 'store_id':store_id, 'from':1}, function(response){
		if (response.error == false) {
			$('.title .img img').attr('src', response.store.images);
			$('.title_h2').html(response.store.name);
			var html_title = '', status = parseInt(response.status);;
			switch (status) {
				case 0:
					html_title = '1号订购人邀请您一起点单';
					break;
				case 1:
					html_title = '1号订购人已锁单，正在支付';
					break;
				case 2:
					html_title = '1号订购人付款成功，等待对方接单';
					break;
				case 3:
					html_title = '1号订购人付款成功，对方配送中';
					break;
				case 4:
					html_title = '1号订购人订单完成 ';
					break;
				case 5:
					html_title = '此次拼单已退款 ';
					break;
				case 6:
					html_title = '此次拼单已取消 ';
					break;
			}
			$('.title_p').html(html_title);
			if (response.myData != '') {
				laytpl($('#myData').html()).render(response.myData, function(html){
					$('.many_me ul').html(html);
				});
				is_same = false;
				$('.choose').hide();
			} else if (status == 0) {
				$('.choose').show();
			}
			
			laytpl($('#allData').html()).render(response.data, function(html){
				$('.allData ul').html(html);
			});
			if (response.myData != '') {
				var html = '';
				html += '<li id="other">';
				html += '<dl>';
				html += '<dd class="h2 clr">';
				html += '<div class="fl name">我的费用</div>';
				html += '</dd>';
				if (parseFloat(response.myTotalPack) > 0) {
					html += '<dd class="list clr">';
					html += '<div class="name fl">' + response.store.pack_alias + '</div>';
					html += '<div class="price fr">￥' + parseFloat(response.myTotalPack) + '</div>';
					html += '<div class="num fr"></div>';
					html += '</dd>';
				}
				if (status < 2) {
					html += '<dd class="list consumption clr ">我一共消费:<i>￥' + parseFloat((parseFloat(response.myTotalPrice) + parseFloat(response.myTotalPack)).toFixed(2)) + '</i>（发起人还未支付）</dd>';
				} else {
					html += '<dd class="list consumption clr ">我一共消费:<i>￥' + parseFloat((response.myDiscountPrice).toFixed(2)) + '</i>（含优惠，发起人已支付）</dd>';
				}
				html += '</dl>';
				html += '</li>';
				$('.allData ul').append(html);
			} else {
				$('.many_me ul').html('');
				$('.allData ul').find('#other').remove();
			}
		} else {
			motify.log(response.msg);
		}
	}, 'json');
}