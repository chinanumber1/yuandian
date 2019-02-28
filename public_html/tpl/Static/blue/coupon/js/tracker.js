function setBICookie(name,value,days,domain){
	try{
		var exp = new Date();
		exp.setTime(exp.getTime() + days*24*60*60*1000);
		document.cookie = name + "=" + value + ";" + "expires=" + exp.toGMTString()+";path=/;" + (domain ? ("domain=" + domain + ";") : "");
	}catch(e){}
}
function getBICookie(name) {
	try{
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg))
            return arr[2];
        else
            return null;
	}catch(e){}
}
function delCookie(name,domain){
	try{
        var date = new Date();
        date.setDate(date.getDate() - 100000);
        document.cookie = name + "=a; expires=" + date.toGMTString() + ";path=/" +";" + (domain ? ("domain=" + domain + ";") : "");
	}catch(e){}
}
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return r[2]; return null;
}
function trackClick(data){
    try{
        if("undefined" != typeof data){
            writelog(data,"clickview");
            if(data!=null&&typeof data=="string"){
            	if(data.indexOf("login=success")>=0){
            		loginStrategy(data);
            	}
            }
        }else{
            writelog(null,"clickview");
        }
    }catch(e){}
}
function trackPageview(data){
    try{
        if("undefined" != typeof data){
           writelog(data,"pageview");
        }else{
           writelog(null,"pageview");
        }
    }catch(e){}
}
function sendTrackerLog(a){(new Image).src=a}
function writelog(data,logtype){
    var cookie_days = 730;//Cookie Save Days
    var url = document.domain;
    var cookie_domain ="." + url.match(/[\w][\w-]*\.(?:com\.cn|com|cn|co|net|org|gov|cc|biz|info)/)[0];
    var log_url = "http://tj.58daojia.com/a.gif?r="+Math.random()+"&logv=1.1&type=" + logtype;
    var bi_hmsr = getHmsr();
	if(bi_hmsr!=null&&bi_hmsr!=""){
        setBICookie("bi_hmsr",bi_hmsr,cookie_days,cookie_domain);
    }else{
        if(getBICookie("bi_hmsr")!=null&&getBICookie("bi_hmsr")!=""){
            bi_hmsr = getBICookie("bi_hmsr");
        }else{
			bi_hmsr="none";
            setBICookie("bi_hmsr",bi_hmsr,cookie_days,cookie_domain);
        }
    }
    var bi_znsr = "none";
	if(getQueryString("znsr")!=null&&getQueryString("znsr")!=""){
        bi_znsr = getQueryString("znsr");
        setBICookie("bi_znsr",bi_znsr,cookie_days,cookie_domain);
    }else{
        if(getBICookie("bi_znsr")!=null&&getBICookie("bi_znsr")!=""){
            bi_znsr = getBICookie("bi_znsr");
        }else{
            setBICookie("bi_znsr",bi_znsr,cookie_days,cookie_domain);
        }
    }
    var bi_hmmd ="none";
	if(getQueryString("hmmd")!=null&&getQueryString("hmmd")!=""){
        bi_hmmd = getQueryString("hmmd");
        setBICookie("bi_hmmd",bi_hmmd,cookie_days,cookie_domain);
    }else{
        if(getBICookie("bi_hmmd")!=null&&getBICookie("bi_hmmd")!=""){
            bi_hmmd = getBICookie("bi_hmmd");
        }else{
            setBICookie("bi_hmmd",bi_hmmd,cookie_days,cookie_domain);
        }
    }
    var bi_hmpl ="none";
	if(getQueryString("hmpl")!=null&&getQueryString("hmpl")!=""){
        bi_hmpl = getQueryString("hmpl");
        setBICookie("bi_hmpl",bi_hmpl,cookie_days,cookie_domain);
    }else{
        if(getBICookie("bi_hmpl")!=null&&getBICookie("bi_hmpl")!=""){
            bi_hmpl = getBICookie("bi_hmpl");
        }else{
            setBICookie("bi_hmpl",bi_hmpl,cookie_days,cookie_domain);
        }
    }
    var bi_hmkw="";
    hmkw=_getKeyWord(document.referrer);
    if(hmkw!=null&&hmkw!=""){
    	bi_hmkw=hmkw;
    	setBICookie("bi_hmkw",bi_hmkw,cookie_days,cookie_domain);
    }else{
    	if(getBICookie("bi_hmkw")!=null&&getBICookie("bi_hmkw")!=""){
            bi_hmkw = getBICookie("bi_hmkw");
        }else{
			setBICookie("bi_hmkw",bi_hmkw,cookie_days,cookie_domain);
		}
    }
    var bi_cookieid = getBICookie("bi_cookieid");
    if(bi_cookieid==null||bi_cookieid==""){
        bi_cookieid=(new Date).valueOf()+""+parseInt(Math.random()*10000000000);
        setBICookie("bi_cookieid",bi_cookieid,cookie_days,cookie_domain);
    }
    var referrer=encodeURIComponent(document.referrer);
    if(referrer==null||referrer==""){
        referrer="none";
    }

    log_url+="&hmsr="+bi_hmsr+"&referrer="+referrer+"&cookieid="+bi_cookieid +"&hmpl="+bi_hmpl +"&hmmd="+bi_hmmd+"&hmkw="+bi_hmkw+"&znsr="+bi_znsr;
    
    if(data!=null&&typeof data=="object"){
        for(var key in data){
            var value=data[key];
            log_url += "&"+key+"=" + value;
        }
    }else if(data!=null&&typeof data=="string"){
        log_url += "&" + data;
    }
    sendTrackerLog(log_url);
}
try{
    if("undefined" != typeof bi_params){
        writelog(bi_params,"pageview");
    }else{
        writelog(null,"pageview");
    }
}catch(e){}

function parse_url(url) {
    var a = document.createElement('a');
    a.href = url;
    return {
        source: url,
        protocol: a.protocol.replace(':', ''),
        host: a.hostname,
        port: a.port,
        query: a.search,
        params: (function() {
            var ret = {},
                seg = a.search.replace(/^\?/, '').split('&'),
                len = seg.length,
                i = 0,
                s;
            for (; i < len; i++) {
                if (!seg[i]) {
                    continue;
                }
                s = seg[i].split('=');
                ret[s[0]] = s[1];
            }
            return ret;
        })(),
        file: (a.pathname.match(/\/([^\/?#]+)$/i) || [, ''])[1],
        hash: a.hash.replace('#', ''),
        path: a.pathname.replace(/^([^\/])/, '/$1'),
        relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [, ''])[1],
        segments: a.pathname.replace(/^\//, '').split('/')
    };
}
function _getKeyWord(referrer){
	var _kw="";
	if(referrer!=null&&referrer!=""){
		var rf_obj=parse_url(referrer);
	     var rf_host=rf_obj.host;
	     if(rf_host=="www.baidu.com"){
	    	 _kw=rf_obj.params.wd;
	     }else if(rf_host=="m.baidu.com"){
	    	 _kw=rf_obj.params.word;
	     }else if(rf_host=="www.sogou.com"){
	    	 _kw=rf_obj.params.query;
	     }else if(rf_host=="m.sogou.com"){
	    	 _kw=rf_obj.params.keyword;
	     }else if(rf_host=="www.haosou.com"||rf_host=="m.haosou.com"||rf_host=="cn.bing.com"||rf_host=="global.bing.com"||rf_host=="m.sm.cn"||rf_host=="www.google.cn"||rf_host=="www.google.com.hk"){
	    	 _kw=rf_obj.params.q;
	     }
	     if(typeof _kw=="undefined"){
	    	 _kw="";
	     }
	}
     return _kw;
}
function getHmsr(){
	var _hmsr=getQueryString("hmsr");
	var referrer=document.referrer;
	if(referrer!=null&&referrer!=""&&(_hmsr==null||_hmsr=="")){
		var rf_obj=parse_url(referrer);
		var rf_host=rf_obj.host;
		if(rf_host=="www.baidu.com"){
			_hmsr="seo_baidu_pc";
		}else if(rf_host=="m.baidu.com"){
			_hmsr="seo_baidu_m";
		}
		else if(rf_host=="www.sogou.com"){
			_hmsr="seo_sogou_pc";
		}
		else if(rf_host=="m.sogou.com"){
			_hmsr="seo_sogou_m";
		}
		else if(rf_host=="www.haosou.com"){
			_hmsr="seo_haosou_pc";
		}
		else if(rf_host=="m.haosou.com"){
			_hmsr="seo_haosou_m";
		}
		else if(rf_host=="cn.bing.com"||rf_host=="global.bing.com"){
			_hmsr="seo_bing";
		}
		else if(rf_host=="m.sm.cn"){
			_hmsr="seo_sm_m";
		}
		else if(rf_host=="www.google.cn"||rf_host=="www.google.com.hk"){
			_hmsr="seo_google";
		}
	}
	return _hmsr;
}


//---------------------------------fzb----------------------------


(function() {
	try{
		_fmOpt = {
				partner: 'daojia',
				appName: 'daojia_web',
				token: uuid()        
        };
//		bi_eventid='';
		if("undefined" != typeof bi_params){
	    	if(bi_params!=null&&typeof bi_params=="object"){
	    		if("undefined" != typeof bi_params.pagetype){
	    			if('bindsuccess'==bi_params.pagetype||'ordersuccess'==bi_params.pagetype){
	    				var cimg = new Image(1,1);
	    				cimg.onload = function() {
	    					_fmOpt.imgLoaded = true;
	    				};
	    			
//	    				bi_eventid='bangjuan_web';
			        	cimg.src = "https://fp.fraudmetrix.cn/fp/clear.png?partnerCode=daojia&appName=daojia_web&tokenId=" + _fmOpt.token;
	    				//cimg.src = "http://tracker.daojia.com/dj_against_cheat/fp/clear.png?partnerCode=daojia&appName=daojia_web&tokenId=" + _fmOpt.token;
	    				var fm = document.createElement('script'); fm.type = 'text/javascript'; fm.async = true;
//			        	fm.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'static.fraudmetrix.cn/fm.js?ver=0.1&t=' + (new Date().getTime()/3600000).toFixed(0);
	    				fm.src = ('http://') + 'tracker.daojia.com/dj_against_cheat/script/bi/sc/fm.js?ver=0.1&t=' + (new Date().getTime()/3600000).toFixed(0);
	    				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(fm, s);
	    			}else{
	    				//else page
	    			}
	    		}else{
	    		}
	    	}
	    }
	
	}catch(e){}
	
})();

function uuid() {
	var s = [];
	var hexDigits = "0123456789ABCDEF";
	for (var i = 0; i < 36; i++) {
		s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
	}
	s[14] = "4"; // bits 12-15 of the time_hi_and_version field to 0010
	s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01
	s[8] = s[13] = s[18] = s[23] = "-";
	var uuid = s.join("");
	return uuid;
}

function loginStrategy(data){
	try{
		_fmOpt = {
				partner: 'daojia',
				appName: 'daojia_web',
				pagetype: 'login',
				token: uuid()        
        };
		 if("undefined" !=data&&data!=null&&typeof data=="string"){
	        	if(data.indexOf("login")>-1){
    				var cimg = new Image(1,1);
    				cimg.onload = function() {
    					_fmOpt.imgLoaded = true;
    				};
    				_fmOpt.biData=data;
		        	cimg.src = "https://fp.fraudmetrix.cn/fp/clear.png?partnerCode=daojia&appName=daojia_web&tokenId=" + _fmOpt.token;
    				//cimg.src = "http://tracker.daojia.com/dj_against_cheat/fp/clear.png?partnerCode=daojia&appName=daojia_web&tokenId=" + _fmOpt.token;
    				var fm = document.createElement('script'); fm.type = 'text/javascript'; fm.async = true;
//		        	fm.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'static.fraudmetrix.cn/fm.js?ver=0.1&t=' + (new Date().getTime()/3600000).toFixed(0);
    				fm.src = ('http://') + 'tracker.daojia.com/dj_against_cheat/script/bi/sc/fm.js?ver=0.1&t=' + (new Date().getTime()/3600000).toFixed(0);
    				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(fm, s);
	        	}else{
	        		return true;
	        	}
	     }
	}catch(e){}
}
//-----------------------------------------------------------------------------