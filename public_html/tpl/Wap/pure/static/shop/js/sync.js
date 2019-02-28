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
var _system = {
	$:function(id){return document.getElementById(id);},
	_client:function(){
		return {w:document.documentElement.scrollWidth,h:document.documentElement.scrollHeight,bw:document.documentElement.clientWidth,bh:document.documentElement.clientHeight};
	},
	_scroll:function(){
		return {x:document.documentElement.scrollLeft?document.documentElement.scrollLeft:document.body.scrollLeft,y:document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop};
	},
	_cover:function(show){
		if(show){
			this.$("cover").style.display="block";
			this.$("cover").style.width=(this._client().bw>this._client().w?this._client().bw:this._client().w)+"px";
			this.$("cover").style.height=(this._client().bh>this._client().h?this._client().bh:this._client().h)+"px";
		}else{
			this.$("cover").style.display="none";
		}
	},
	_guide:function(click){
		this._cover(true);
		this.$("guide").style.display="block";
		this.$("guide").style.top=(_system._scroll().y+5)+"px";
		window.onresize=function(){_system._cover(true);_system.$("guide").style.top=(_system._scroll().y+5)+"px";};
		if(click){
			_system.$("cover").onclick=function(){
				_system._cover();
				_system.$("guide").style.display="none";
				_system.$("cover").onclick=null;
				window.onresize=null;
			};
		}
	},
	_zero:function(n){
		return n < 0 ? 0 : n;
	}
}
var DispClose = true;
$(function(){
	init_load();
//     $first = $.cookie('first_vist');
//     if (!$first || $first=='null' || $first==null) {
//         $.cookie('first_vist','1',{ expires: 7 });
//         init_load();
//     } else {
//         $.cookie('first_vist',null);
//         init_load();
//     }
     
//	$(window).on("pageshow", function(){
//		init_load();
//	});
     
//     var strcookie = document.cookie;  
//     var arrcookie = strcookie.spit("=")  
//     var statuscookie = arrcookie[1];  
//     if(statuscookie == "" || statuscookie == "0"){  
//         init_load();
//         document.cookie="statuscookie=1";  
//     }else{  
//         init_load(); 
//         document.cookie="statuscookie=0";  
//     }  
     
     
	//ul列表高度	
	$(".many_end ul").each(function(){
		$(this).height($(window).height() - $(".many_top").outerHeight() - 138);
	});

	//空图片的高度
	$(".none_img").each(function(){
		$(this).height($(window).height() - $(".many_top").outerHeight()- 138);
	});

	//弹窗背景图等比例
	$(".set_bj").each(function(){
		$(this).css({"height":$(".settlement").width()*0.615});
	});

	//解锁圆形等比例
	$(".locking").each(function(){
		$(this).css({"height":$(this).outerWidth(),"margin-top":- $(this).outerWidth()/2});
	});

	//添加订购人数值
	$(".add_to").each(function(){
		$(this).css("margin-top",-($(this).outerHeight()/2));
	});

	//添加订购人弹窗
	$(".many_top .h2").click(function(){
		$(".add_to").show();
		$(".mask").show();
	});
	
	$('.add_to .spell').click(function(){
		$.post(root_url + 'addNew', {'cartid':cartid, 'store_id':store_id}, function(response){
			if (response.error == false) {
				var html = '<li><dl><dd class="h2 clr"><i>' + response.index + '</i><div class="fl name">' + response.index + '号订购人</div><div class="fr del" style="cursor: pointer;" data-index="index_' + response.index + '">删除Ta</div><a class="fr repair" style="cursor: pointer;" data-href="' + response.url + '">添加商品</a></dd><dd class="list clr">还没有添加商品</dd></dl></li>';
				$('#spell').prepend(html);
			} else {
				motify.log(response.msg);
			}
		}, 'json');
		
		$('.add_to, .mask').hide();
	});
	
	$(".add_to .li2").click(function(){
		$('.add_to, .mask').hide();
		_system._guide(true);
	});

	//去结算弹窗
	$(document).on('click', '.many_bot .confirm', function(){
		$('.go_settlement, .mask').show();
	});

	//去ji
	$(".go_settlement .indeed").click(function(){
		$('.go_settlement, .mask').hide();
		$.post(root_url + 'saveCart', {'cartid':cartid, 'store_id':store_id, 'status':1}, function(response){
			if (response.error == false) {
				init_load();
				setTimeout(function(){window.location.href = root_url + 'confirm_order&store_id=' + store_id + '&cartid=' + cartid;}, 500);
			} else {
				motify.log(response.msg);
			}
		}, 'json');
	});

	//解锁弹窗
	$(".locking .unlock").click(function(){
		$('.locking, .mask_white').hide();
		$('.go_unlock, .mask').show();
	});

	//解锁
	$('.go_unlock .indeed').click(function(){
		$.post(root_url + 'saveCart', {'cartid':cartid, 'store_id':store_id, 'status':0}, function(response){
			if (response.error == false) {
				window.localStorage.setItem('sync_cart_' + store_id, cartid);
				init_load();
				$('.go_unlock, .mask').hide();
			} else {
				motify.log(response.msg);
			}
		}, 'json');
	});
	
	$('.go_unlock .cancel').click(function(){
		window.location.href = root_url + 'confirm_order&store_id=' + store_id + '&cartid=' + cartid;
	});
	
	$(".go_settlement .cancel,.go_settlement .indeed").click(function(){
		$('.settlement, .mask').hide();
	});

	$(".mask").click(function(){
		$('.add_to, .go_settlement, .go_unlock, .mask').hide();
	});


//	$(".mask_white").click(function(){
//		$(".locking").hide();
//		$(".mask_white").hide();
//	});

	//列表删除
	$(document).on('click', '.many_end .del', function(){
		var obj = $(this), index = $(this).data('index');
		layer.open({
			content: '您确定要删除Ta吗？',
			btn: ['确定', '取消'],
			yes: function(inx){
				layer.close(inx);
				$.post(root_url + 'delCart', {'index':index, 'cartid':cartid, 'store_id':store_id}, function(response){
					if (response.error == false) {
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
	
	$(document).on('click', '.repair', function(){
		DispClose = false;
		window.location.href = $(this).data('href');
	});
	setInterval(init_load, 10000);
});

function init_load()
{
//	var cartid = window.localStorage.getItem('sync_cart_' + store_id);
	$.get(root_url + 'ajaxAll', {'cartid':cartid, 'store_id':store_id, 'from':0}, function(response){
		if (response.error == false) {
			var status = parseInt(response.status);
			
			if (status == 0) {
				$('.many_top .h2, .confirm').show();
			} else {
				if (status == 1) {
					if ($(".go_unlock").is(":hidden")) {
						$('.locking, .mask_white').show();
					}
				}
				$('.many_top .h2, .confirm').hide();
			}
			
			if (response.data == '') {
				$('.many_end, .many_bot').hide();
				$('.none_img').show();
				return false;
			} else {
				$('.many_end, .many_bot').show();
				$('.none_img').hide();
			}
			laytpl($('#allData').html()).render(response.data, function(html){
				$('#spell').html(html);
			});
			var html = '';
			if (status < 2) {
				var price =  parseFloat((parseFloat(response.totalPrice) + parseFloat(response.totalPack)).toFixed(2));
				$('.totalPrice').html('￥' + price);
				if (parseFloat(response.totalPack) > 0) {
					html += '<li>';
					html += '<dl>';
					html += '<dd class="h2 clr">';
					html += '<div class="fl name">其他费用</div>';
					html += '</dd>';
					html += '<dd class="list clr">';
					html += '<div class="name fl">' + response.store.pack_alias + '</div>';
					html += '<div class="price fr">￥' + parseFloat(response.totalPack) + '</div>';
					html += '<div class="num fr"></div>';
					html += '</dd>';
//					html += '<dd class="list consumption clr ">享受优惠费后,我一共消费:<i>￥100</i></dd>';
					html += '</dl>';
					html += '</li>';
				}
			} else {
				$('.totalPrice').html('￥' + parseFloat(parseFloat(response.order.price).toFixed(2)));
				if (parseFloat(response.order.packing_charge) > 0 || parseFloat(response.order.freight_charge) > 0) {
					html += '<li>';
					html += '<dl>';
					html += '<dd class="h2 clr">';
					html += '<div class="fl name">其他费用</div>';
					html += '</dd>';
					if (parseFloat(response.order.packing_charge) > 0) {
						html += '<dd class="list clr">';
						html += '<div class="name fl">' + response.store.pack_alias + '</div>';
						html += '<div class="price fr">￥' + parseFloat(response.order.packing_charge) + '</div>';
						html += '<div class="num fr"></div>';
						html += '</dd>';
					}
					if (parseFloat(response.order.freight_charge) > 0) {
						html += '<dd class="list clr">';
						html += '<div class="name fl">' + response.store.freight_alias + '</div>';
						html += '<div class="price fr">￥' + parseFloat(response.order.freight_charge) + '</div>';
						html += '<div class="num fr"></div>';
						html += '</dd>';
					}
//					html += '<dd class="list consumption clr ">享受优惠费后,我一共消费:<i>￥100</i></dd>';
					html += '</dl>';
					html += '</li>';
				}
			}
			
			$('#spell').append(html);
			if (response.isGo) {
				$('.go').removeClass('noEmpty').addClass('confirm').html('去结算');
			} else {
				$('.go').removeClass('confirm').addClass('noEmpty').html('还差￥' + response.addPrice + ' 起送');
			}
		} else {
			motify.log(response.msg);
		}
	}, 'json');
}