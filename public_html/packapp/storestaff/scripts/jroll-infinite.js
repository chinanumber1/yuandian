/*! JRoll-Infinite v2.1.3 ~ (c) 2016 Author:BarZu Git:https://github.com/chjtx/JRoll/tree/master/extends/jroll-infinite */
!function(e,n,i){"use strict";function t(e,n){var i=e,t="'use strict';\nvar __t='';__t+='",l=0,o=/\{\{=([^{]+)\}\}|\{\{(each [\w$._]+ as [\w$_]+ [\w$_]+)\}\}|\{\{if ([^{]+)\}\}|\{\{(else)\}\}|\{\{else if ([^{]+)\}\}|(\{\{\/each\}\}|\{\{\/if\}\})|\{\{([^{]+)\}\}/g;return i=i.replace(/(\n|\r)|(\{\{\s+)|(\s+\}\})|(\s{2,})|(')/g,function(e,n,i,t,l,o){var r;return n?r="":i?r="{{":t?r="}}":l?r=" ":o&&(r="\\'"),r}),i.replace(o,function(e,n,o,r,a,s,p,c,f){if(t+=i.slice(l,f),l=f+e.length,n)t+="'+("+n.replace(/\\'/g,"'")+")+'";else if(o){var g=o.split(" ");t+="';\nfor(var "+g[4]+"=0,"+g[3]+";"+g[4]+"<"+g[1]+".length;"+g[4]+"++){\n"+g[3]+"="+g[1]+"["+g[4]+"];\n__t+='"}else r?t+="';\nif("+r.replace(/\\'/g,"'")+"){\n__t+='":a?t+="';\n}else{\n__t+='":s?t+="';\n}else if("+s.replace(/\\'/g,"'")+"){\n__t+='":p?t+="';\n};\n__t+='":c&&(t+="';\n"+c.replace(/\\'/g,"'")+";\n__t+='");return e}),t+=i.slice(l)+"';return __t;",new Function(n,t)}function l(e,n){return e(n)}i.prototype.infinite=function(e){function i(e){return e.replace(/("|')|\$|\(|\)|\+|\*|\.|\[|]|\?|\\|\^|\{|\}|\|/g,function(e,n){return n?"[\"']":"\\"+e})}function o(e){var n;if(a=!1,e){n="<section class='jroll-infinite-page'>";for(var i=0,t=e.length;i<t;i++)n+=g.render(s,e[i],i);n+="</section>",n+=c.options.total===c.options.page?g.completeTip:g.loadingTip,c.scroller.innerHTML=c.scroller.innerHTML.replace(p,"")+n,c.refresh(),(g.hideImg||g.blank)&&setTimeout(function(){var e=c.scroller.querySelectorAll(".jroll-infinite-page"),n=e[e.length-1];n&&(n.style.height=n.offsetHeight+"px"),r(e)},10)}}function r(e){if(g.hideImg||g.blank)for(var n=e||c.scroller.querySelectorAll(".jroll-infinite-page"),i=c.wrapper.clientHeight,t=c.y,l=g.blank?"jroll-infinite-hide":"jroll-infinite-hideimg",o=0,r=n.length;o<r;o++)n[o].offsetTop-i+t>0||n[o].offsetTop+n[o].offsetHeight+t<0?n[o].classList.add(l):n[o].classList.remove(l)}var a,s,p,c=this,f=Object.keys(e||{}),g={total:99,getData:null,hideImg:!1,blank:!1,template:"",loadingTip:'<div class="jroll-infinite-tip">正在加载...</div>',completeTip:'<div class="jroll-infinite-tip">已加载全部内容</div>',root:"_obj",compile:t,render:l};for(var d in f)g[f[d]]=e[f[d]];c.options.total=g.total,c.options.page=1,c.infinite_callback=o,s=g.compile(g.template,g.root),p=new RegExp("("+i(g.loadingTip)+"|"+i(g.completeTip)+")","g");var u=n.getElementById("jroll_style"),_="\n/* jroll-infinite */\n.jroll-infinite-hide>*{display:none}.jroll-infinite-hideimg img{display:none}\n";u?/jroll-infinite/.test(u.innerHTML)||(u.innerHTML+=_):(u=n.createElement("style"),u.id="jroll_style",u.innerHTML=_,n.head.appendChild(u)),n.createElement("div").innerHTML=g.loadingTip+g.completeTip,"function"==typeof g.getData&&(c.scroller.innerHTML=g.loadingTip,g.getData(c.options.page,o)),c.on("scrollEnd",function(){c.y<c.maxScrollY+c.scroller.querySelector(".jroll-infinite-tip").offsetHeight&&c.options.page!==c.options.total&&!a&&(a=!0,g.getData(++c.options.page,o)),r()})},i.prototype.infinite.version="2.1.3","undefined"!=typeof module&&module.exports&&(module.exports=i),"function"==typeof define&&define(function(){return i})}(window,document,JRoll);