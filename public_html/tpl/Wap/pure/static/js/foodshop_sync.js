
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
var timeout = 0;
$(function(){
    var height = parseInt($(window).height())*0.4;
    var height1 = $(".Cartbot_list").height();
    if (height1 > height) {
        $(".Cartbot_list").css("height", height+'px');
    }

    var notice = false;
    $('.notice').click(function(){
        if (notice) return false;
        notice = true;
        $.post(call_store_url, {'note':$('#note').val()}, function(response){
            if (response.err_code) {
                layer.open({content:response.msg,skin: 'msg',time: 2});
            } else {
                location.reload();
                timeout = setInterval(check_status, 10000);
                layer.open({content:response.msg,skin: 'msg',time: 2});
                $('.Serving,.Total').hide();
            }
        }, 'json');
    });
    var call = false;
    $('.call').click(function(){
        if (call) return false;
        call = true;
        $.post(call_server_url, function(response){
            if (response.err_code) {
                layer.open({content:response.msg,skin: 'msg',time: 2});
            } else {
                layer.open({content:response.msg,skin: 'msg',time: 2});
            }
            call = false;
        }, 'json');
    });

    $("#share").click(function(){
        _system._guide(true);
    });
        
    if (status < 3) {
        timeout = setInterval(check_status, 10000);
    }

    // 点击展开影藏
    $(".Package_end dl").each(function(){
        var height = $(this).height();
        if (height > 80) {
            $(this).css({"height":"80px","overflow":"hidden"});
        } else {
            $(this).siblings("a.more").hide();
        }
    });
    $(".Package_end").on('click', '.more', function(){
        $(this).hide();
        $(this).siblings(".Package_end dl").css("height","auto");
    });
});

function check_status()
{
    $.get(check_status_url, function(response){
        if (response.err_code) {
        } else {
            clearInterval(timeout);
            location.reload();
        }
    }, 'json');
}
setInterval(function(){
    $.get(get_data_info_url, function(response){
        if (response.errcode == 1) {
            layer.msg(response.msg);
        } else {
            laytpl($('#allData').html()).render(response.data, function(html){
                $('#data').html(html);
                $(".Package_end dl").each(function(){
                    var height = $(this).height();
                    if (height > 80) {
                        $(this).css({"height":"80px","overflow":"hidden"});
                    } else {
                        $(this).siblings("a.more").hide();
                    }
                });
            });
            if (response.data.is_pay == 1) {
                $('.pay').show();
            } else {
                $('.pay').hide();
            }
            if (response.data.is_add_menu == 1) {
                $('.add').show();
            } else {
                $('.add').hide();
            }
            if (response.data.is_call_store == 1) {
                $('.notice').show();
            } else {
                $('.notice').hide();
            }
            if (response.data.is_call_server == 1) {
                $('.call').show();
            } else {
                $('.call').hide();
            }
            if (response.data.is_comment == 1) {
                $('.comment').show();
            } else {
                $('.comment').hide();
            }
        }
    }, 'json');
}, 5000);
