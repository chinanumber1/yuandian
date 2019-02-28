/** laytpl-v1.1 */
;!function(){"use strict";var f,b={open:"{{",close:"}}"},c={exp:function(a){return new RegExp(a,"g")},query:function(a,c,e){var f=["#([\\s\\S])+?","([^{#}])*?"][a||0];return d((c||"")+b.open+f+b.close+(e||""))},escape:function(a){return String(a||"").replace(/&(?!#?[a-zA-Z0-9]+;)/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/'/g,"&#39;").replace(/"/g,"&quot;")},error:function(a,b){var c="Laytpl Error：";return"object"==typeof console&&console.error(c+a+"\n"+(b||"")),c+a}},d=c.exp,e=function(a){this.tpl=a};e.pt=e.prototype,e.pt.parse=function(a,e){var f=this,g=a,h=d("^"+b.open+"#",""),i=d(b.close+"$","");a=a.replace(/[\r\t\n]/g," ").replace(d(b.open+"#"),b.open+"# ").replace(d(b.close+"}"),"} "+b.close).replace(/\\/g,"\\\\").replace(/(?="|')/g,"\\").replace(c.query(),function(a){return a=a.replace(h,"").replace(i,""),'";'+a.replace(/\\/g,"")+'; view+="'}).replace(c.query(1),function(a){var c='"+(';return a.replace(/\s/g,"")===b.open+b.close?"":(a=a.replace(d(b.open+"|"+b.close),""),/^=/.test(a)&&(a=a.replace(/^=/,""),c='"+_escape_('),c+a.replace(/\\/g,"")+')+"')}),a='"use strict";var view = "'+a+'";return view;';try{return f.cache=a=new Function("d, _escape_",a),a(e,c.escape)}catch(j){return delete f.cache,c.error(j,g)}},e.pt.render=function(a,b){var e,d=this;return a?(e=d.cache?d.cache(a,c.escape):d.parse(d.tpl,a),b?(b(e),void 0):e):c.error("no data")},f=function(a){return"string"!=typeof a?c.error("Template not found"):new e(a)},f.config=function(a){a=a||{};for(var c in a)b[c]=a[c]},f.v="1.1","function"==typeof define?define(function(){return f}):"undefined"!=typeof exports?module.exports=f:window.laytpl=f}();


 /*
 * 跳转页面
 * （默认页面往左滑动，即 openRightWindow）
 * （页面往右滑动，即 openLeftWindow）
 */
function redirect(url,type){
  var animateCss = {},animateAfterCss = {};
  if(!type){
    type = 'openRightWindow';
  }
  switch(type){
    case 'openRightWindow':
      animateCss = {'left':'-'+$(window).width()+'px'};
      animateAfterCss = {'left':'0px'};
      break;
    case 'openLeftWindow':
      animateCss = {'left':$(window).width()+'px'};
      animateAfterCss = {'left':'0px'};
      break;
  }
  $('body').animate(animateCss,function(){
    pageLoadTip();
    window.addEventListener("pagehide", function(){
      $('body').css(animateAfterCss);
      pageLoadTipHide();
    },false);
    window.location.href = url;
  });
}
/*页面加载提示*/
function pageLoadTip(msg){
    var defaultMsg='',top=0;  //'加载中...'
    //如果msg是数字，则是top的值！是字符串就是消息
    if(typeof(msg) == 'number'){
    top = msg;
    msg = defaultMsg;
    }else if(!msg){
    top = 0;
    msg = defaultMsg;
    }
    $('#pageLoadTip').css({top:top+'px','display':'block'}).find('div').css({'margin-top':(($(window).height()-100-top)/2)+'px'}).html(msg);
}
function pageLoadTipHide(){
  $('#pageLoadTip').hide();
}


/*优化手机中的点击事件*/
if(typeof(FastClick) == 'function'){
  FastClick.attach(document.body);
}

/*页面点击事件*/
  $(document).on('click','.link-url',function(){
    if(typeof(noAnimate) == "undefined"){
      redirect($(this).data('url'),$(this).data('url-type'));
      return false;
    }else{
      window.location.href = $(this).data('url');
    }
  });    

/*A标签*/
$(document).on('click','a',function(){
    if($(this).data('nobtn')){
      return false;
    }
    if(typeof(noAnimate) == "undefined"){
      $('body').append('<div id="pageLoadTip" style="display:none;"><div></div></div>');
      var href = $(this).attr('href');
      if(href && href.substr(0,3) != 'tel' && href.substr(0,10) != 'javascript'){
        redirect(href,$(this).data('url-type'));
        return false;
      }
    }
});

  /* 简单的消息弹出层 */
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
  
};


// rem单位计算
    var win_h=$(window).height();
    var win_w=$(window).width();

    if(win_w <= 640){
        var per = win_w/640;
    }else{
        var per = 1;
    }

    var _viewport=$(window).width();
      _viewport=_viewport>640?640:_viewport;
    var fontSize=_viewport/6.4;
    window.screenWidth_ = _viewport;
    $("html").css('font-size',fontSize+'px');

