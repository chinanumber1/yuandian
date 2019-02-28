var myScroll,myScroll2=null,myScroll3=null,now_page = 0,hasMorePage = true,isLoading = true;
$(function(){
	$('#backBtn').click(function(){
		if(document.referrer){
			redirect(document.referrer,'openLeftWindow');
		}else{
			redirect(backUrl,'openLeftWindow');
		}
	});
	
	$('#scroller').css({'min-height':($(window).height()-50+1)+'px'});
	myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
	var upIcon = $("#pullUp"),
		downIcon = $("#pullDown");
	myScroll.on("scroll",function(){
		var maxY = this.maxScrollY - this.y;
		if(this.y >= 50){
			if(!downIcon.hasClass("reverse_icon")) downIcon.addClass("reverse_icon").find('.pullDownLabel').html('松开可以刷新');
			return "";
		}else if(this.y < 50 && this.y > 0){
			if(downIcon.hasClass("reverse_icon")) downIcon.removeClass("reverse_icon").find('.pullDownLabel').html('下拉可以刷新');
			return "";
		}
		if(maxY >= 50){
			if(!upIcon.hasClass("reverse_icon")) upIcon.addClass("reverse_icon").find('.pullUpLabel').html('松开加载更多');
			return "";
		}else if(maxY < 50 && maxY >=0){
			if(upIcon.hasClass("reverse_icon")) upIcon.removeClass("reverse_icon").find('.pullUpLabel').html('上拉加载更多');
			return "";
		}
	});
	myScroll.on("slideDown",function(){
		if(this.y > 50){
			now_page = 0;
			hasMorePage = true;
			upIcon.removeClass('noMore loading').show();
			pageLoadTip(50);
			getList(false);
		}
	});
	myScroll.on("slideUp",function(){
		if(hasMorePage){
			$('.listBox dl').append('<dd class="loadMoreList">正在加载</dd>');
			// upIcon.addClass('loading');
			// setTimeout(function(){
				myScroll.refresh();
				myScroll.scrollTo(0,this.maxScrollY);
				getList(true);
			// },200);
		}
		/* if(this.maxScrollY - this.y > 50 && !upIcon.hasClass('noMore')){
			upIcon.addClass('noMore').hide();
		} */
	});
	/* myScroll.on("scrollEnd",function(){
		if(hasMorePage && upIcon.hasClass('noMore') && !upIcon.hasClass('loading')){
			$('.listBox dl').append('<dd class="loadMoreList">正在加载</dd>');
			upIcon.addClass('loading');
			// setTimeout(function(){
				myScroll.refresh();
				myScroll.scrollTo(0,this.maxScrollY);
				getList(true);
			// },200);
		}
	}); */
	/* $(window).resize(function(){
		window.location.reload();
	}); */

	pageLoadTip(50);
	pageGetList(true);
//	if(user_long == '0'){
//		getUserLocation({okFunction:'pageGetList',okFunctionParam:[true],errorFunction:'pageGetList',errorFunctionParam:[false]});
//	}else{
//		pageGetList(user_long,user_lat);
//	}
});
function pageGetList(){
	getList(false);
}
function getList(more){
	// return false;
	isLoading = true;
	var go_url = location_url;
	now_page += 1;
	go_url += "&page="+now_page;
	$.post(go_url,function(result){
		if(result.count > 0){
			hasMorePage = now_page < result.totalPage ? true : false;

			$('.listBox').addClass('storeListBox');
			laytpl($('#listBoxTpl').html()).render(result, function(html){
				if(more){
					if(hasMorePage){
						$("#pullUp").removeClass('noMore loading').show();
					}
					$('.loadMoreList').remove();
					$('.listBox dl').append(html);
				}else{
					$('.listBox dl').html(html).removeClass('dealcard').show();
				}
			});
			
			if(!hasMorePage){
				$("#pullUp").addClass('noMore').hide();
			}
		}else{
			$("#pullUp").addClass('noMore').hide();
			$('.listBox dl').hide();
			$('.listBox .no-deals').removeClass('hide');
		}
		pageLoadTipHide();
		setTimeout(function(){
			// console.log(more);
			myScroll.refresh();
			if(!more){
				myScroll.scrollTo(0,0);
			}
		},200);
		isLoading = false;
	});
}

var obj2String = function(_obj) {
    var t = typeof (_obj);
    if (t != 'object' || _obj === null) {
      // simple data type
      if (t == 'string') {
        _obj = '"' + _obj + '"';
      }
      return String(_obj);
    } else {
      if ( _obj instanceof Date) {
        return _obj.toLocaleString();
      }
      // recurse array or object
      var n, v, json = [], arr = (_obj && _obj.constructor == Array);
      for (n in _obj) {
        v = _obj[n];
        t = typeof (v);
        if (t == 'string') {
          v = '"' + v + '"';
        } else if (t == "object" && v !== null) {
          v = this.obj2String(v);
        }
        json.push(( arr ? '' : '"' + n + '":') + String(v));
      }
      return ( arr ? '[' : '{') + String(json) + ( arr ? ']' : '}');
    }
  };
  var obj = {
    "result" : {
      "fs" : {
        "TSP.IBR.MIRROR" : [{
          "_value" : "1.0",
          "_class" : 4
        }],
        "TSP.IBR.GET_FNAMES" : [{
          "_value" : "0.0",
          "_class" : 4
        }],
        "TSP.IBR.GET_TOKEN_ID" : [{
          "_value" : "0.0",
          "_class" : 4
        }],
        "TSP.IBR.INFO" : [{
          "_value" : "0.0",
          "_class" : 4
        }]
      }
    },
    "isCanceled" : false,
    "e" : "",
    "isResponsed" : true,
    "aoqSize" : 0,
    "isAsyncPost" : false,
    "code" : 0,
    "reqUID" : "xxxx-xxxxxx-xxxxx-6c2f17bb-ea18-42ec-98fa-3f63b8d26aba-nd-rq",
    "version" : "1.0",
    "fName" : "TSP.IBR.GET_FNAMES",
    "message" : "成功获取 4 个功能",
    "dir" : "DOWN",
    "nodeTime" : 1362462128706,
    "isKeyCompressed" : false,
    "seq" : 2
  }