/*本代码由素材家园原创，转载请保留网址：www.sucaijiayuan.com*/
var zldateObj = { date: new Date(), year: -1, month: -1, priceArr: [] };
var htmlObj = { header: "", left: "", right: "" };
var elemId = null;
function getAbsoluteLeft(objectId) {
   var o = document.getElementById(objectId)
   var oLeft = o.offsetLeft;
    while (o.offsetParent != null) {
        oParent = o.offsetParent
        oLeft += oParent.offsetLeft
        o = oParent
    }
    return oLeft
}
//获取控件上绝对位置
function getAbsoluteTop(objectId) {
   var o = document.getElementById(objectId);
   var oTop = o.offsetTop + o.offsetHeight + 10;
    while (o.offsetParent != null) {
        oParent = o.offsetParent
        oTop += oParent.offsetTop
        o = oParent
    }
    return oTop
}
//获取控件宽度
function getElementWidth(objectId) {
    x = document.getElementById(objectId);
    return x.clientHeight;
}
var pickerEvent = {
    Init: function (elemid) {
        if (zldateObj.year == -1) {
            dateUtil.getCurrent();
        }
        for (var item in pickerHtml) {
            pickerHtml[item]();
        }
        var p = document.getElementById("calendar_choose");
        if (p != null) {
            document.body.removeChild(p);
			$('#calendar_choose_bg').remove();
        }
        var html = '<div id="calendar_choose" class="calendar" style="display: block; position: absolute; top:0px; left:0px;">'
        html += htmlObj.header;
        html += '<div class="basefix" id="bigCalendar" style="display: block;">';
        html += htmlObj.left;
        html += htmlObj.right;
        html += '<div style="clear: both;"></div>';
        html += "</div></div>";
        elemId=elemid;
        var elemObj = document.getElementById(elemid);
        $(document.body).append(html);
        $(document.body).append('<div id="calendar_choose_bg"></div>');
        document.getElementById("picker_last").onclick = pickerEvent.getLast;
        document.getElementById("picker_next").onclick = pickerEvent.getNext;
		document.getElementById("picker_today").onclick = pickerEvent.getToday;
        document.getElementById("calendar_choose").style.left = (($(window).width()-780)/2)+"px";
        document.getElementById("calendar_choose").style.top  = (($(window).height()-438)/2)+"px";
        document.getElementById("calendar_choose").style.zIndex = 1000;
        var tds = document.getElementById("calendar_tab").getElementsByTagName("td");
        for (var i = 0; i < tds.length; i++) {
			$(tds[i]).find('.calendar_price01').blur(function(){
				var nowPrice = parseFloat($.trim($(this).val()));
				if(isNaN(nowPrice)){
					$(this).val();
					return false;
				}
				var tmpTime = $(this).closest('td').attr('date');
				var tmpTimeArr = tmpTime.split('-');
				if(parseInt(tmpTimeArr[1]) < 10) tmpTimeArr[1] = '0' + tmpTimeArr[1];
				if(parseInt(tmpTimeArr[2]) < 10) tmpTimeArr[2] = '0' + tmpTimeArr[2];
				var tmpTime = tmpTimeArr[0]+'-'+tmpTimeArr[1]+'-'+tmpTimeArr[2];
				var is_find = false;
				for(var i in jsonTimeArr){
					if(jsonTimeArr[i]['Date'] == tmpTime){
						jsonTimeArr[i]['Price'] = nowPrice.toString();
						is_find = true;
						break;
					}
				}
				if(is_find == false){
					jsonTimeArr.push({'Date':tmpTime,'Price':nowPrice.toString()});
				}
			});
        }
        // return html;
        //return elemObj;
    },
    getLast: function () {
        dateUtil.getLastDate();
        pickerEvent.Init(elemId);
    },
    getNext: function () {
        dateUtil.getNexDate();
        pickerEvent.Init(elemId);
    },
	getToday:function(){
		dateUtil.getCurrent();
		pickerEvent.Init(elemId);
	},
    setPriceArr: function (arr) {
        zldateObj.priceArr = arr;
    },
    remove: function () {
        var p = document.getElementById("calendar_choose");
        if (p != null) {
            document.body.removeChild(p);
        }
    },
    isShow: function () {
        var p = document.getElementById("calendar_choose");
        if (p != null) {
            return true;
        }
        else {
            return false;
        }
    }
}
var pickerHtml = {
    getHead: function () {
        var head = '<ul class="calendar_num basefix"><li class="bold">六</li><li>五</li><li>四</li><li>三</li><li>二</li><li>一</li><li class="bold">日</li><li class="picker_today bold" id="picker_today">回到今天</li></ul>';
        htmlObj.header = head;
    },
    getLeft: function () {
    	if(zldateObj.year == new Date().getFullYear() && zldateObj.month == new Date().getMonth()+1){
        	var left = '<div class="calendar_left pkg_double_month"><p class="date_text">' + zldateObj.year + '年' + zldateObj.month + '月</p><a href="javascript:void(0);" title="上一月" id="picker_last"></a><a href="javascript:void(0);" title="下一月" id="picker_next" class="pkg_circle_bottom"></a></div>';
    	}else{
        	var left = '<div class="calendar_left pkg_double_month"><p class="date_text">' + zldateObj.year + '年' + zldateObj.month + '月</p><a href="javascript:void(0);" title="上一月" id="picker_last" class="pkg_circle_top"></a><a href="javascript:void(0);" title="下一月" id="picker_next" class="pkg_circle_bottom "></a></div>';
    	}
        htmlObj.left = left;
    },
    getRight: function () {
        var days = dateUtil.getLastDay();
        var week = dateUtil.getWeek();
        var html = '<table id="calendar_tab" class="calendar_right"><tbody>';
        var index = 0;
        for (var i = 1; i <= 42; i++) {
            if (index == 0) {
                html += "<tr>";
            }
            var c = week > 0 ? week : 0;
            if ((i - 1) >= week && (i - c) <= days) {
                var price = commonUtil.getPrice((i - c));
                var priceStr = "";
                var classStyle = "";
                if (price != -1) {
                    priceStr = price;
                    classStyle = "class='on'";
                }
				if (price != -1&&zldateObj.year==new Date().getFullYear()&&zldateObj.month==new Date().getMonth()+1&&i-c==new Date().getDate()) {
                    classStyle = "class='on today'";
                }
				//判断今天
				if(zldateObj.year==new Date().getFullYear()&&zldateObj.month==new Date().getMonth()+1&&i-c==new Date().getDate()){
					html += '<td  ' + classStyle + ' date="' + zldateObj.year + "-" + zldateObj.month + "-" + (i - c) + '" price="' + price + '"><a><span class="date basefix">今天</span><span class="team basefix" style="display: none;">&nbsp;</span><input class="calendar_price01" value='+ priceStr + '></a></td>';
				}else{
                	html += '<td  ' + classStyle + ' date="' + zldateObj.year + "-" + zldateObj.month + "-" + (i - c) + '" price="' + price + '"><a><span class="date basefix">' + (i - c) + '</span><span class="team basefix" style="display: none;">&nbsp;</span><input class="calendar_price01" value='+ priceStr + '></a></td>';
				}
                if (index == 6) {

                    html += '</tr>';
                    index = -1;
                }
            }
            else {
                html += "<td></td>";
                if (index == 6) {
                    html += "</tr>";
                    index = -1;
                }
            }
            index++;
        }
        html += "</tbody></table>";
        htmlObj.right = html;
    }
}
var dateUtil = {
    //根据日期得到星期
    getWeek: function () {
        var d = new Date(zldateObj.year, zldateObj.month - 1, 1);
        return d.getDay();
    },
    //得到一个月的天数
    getLastDay: function () {
        var new_year = zldateObj.year;//取当前的年份
        var new_month = zldateObj.month;//取下一个月的第一天，方便计算（最后一不固定）
        var new_date = new Date(new_year, new_month, 1);                //取当年当月中的第一天
        return (new Date(new_date.getTime() - 1000 * 60 * 60 * 24)).getDate();//获取当月最后一天日期
    },
    getCurrent: function () {
        var dt = zldateObj.date;
        zldateObj.year = dt.getFullYear();
        zldateObj.month = dt.getMonth() + 1;
		zldateObj.day = dt.getDate();
    },
    getLastDate: function () {
        if (zldateObj.year == -1) {
            var dt = new Date(zldateObj.date);
            zldateObj.year = dt.getFullYear();
            zldateObj.month = dt.getMonth() + 1;
        }
        else {
            var newMonth = zldateObj.month - 1;
            if (newMonth <= 0) {
                zldateObj.year -= 1;
                zldateObj.month = 12;
            }
            else {
                zldateObj.month -= 1;
            }
        }
    },
    getNexDate: function () {
        if (zldateObj.year == -1) {
            var dt = new Date(zldateObj.date);
            zldateObj.year = dt.getFullYear();
            zldateObj.month = dt.getMonth() + 1;
        }
        else {
            var newMonth = zldateObj.month + 1;
            if (newMonth > 12) {
                zldateObj.year += 1;
                zldateObj.month = 1;
            }
            else {
                zldateObj.month += 1;
            }
        }
    }
}
var commonUtil = {
    getPrice: function (day) {
        var dt = zldateObj.year + "-";
        if (zldateObj.month < 10)
        {
            dt += "0"+zldateObj.month;
        }
        else
        {
            dt+=zldateObj.month;
        }
        if (day < 10) {
            dt += "-0" + day;
        }
        else {
            dt += "-" + day;
        }

        for (var i = 0; i < zldateObj.priceArr.length; i++) {
            if (zldateObj.priceArr[i].Date == dt) {
                return zldateObj.priceArr[i].Price.split('.')[0];
            }
        }
        return -1;
    },
    chooseClick: function (sender) {
        var date = sender.getAttribute("date");
        var price = sender.getAttribute("price");
        var el = document.getElementById(elemId);
        if (el != null) {
            el.value = date;
			// alert("日期是："+date);
			// alert("价格是：￥"+price);
            pickerEvent.remove();
			$('#calendar_choose_bg').remove();
        }
    }
}
$(document).bind("click", function (event) {
    var e = event || window.event;
    var elem = e.srcElement || e.target;
    while (elem) {
        if (elem.id == "calendar_choose" || elem.id == "calendar") {
            return;
        }
        elem = elem.parentNode;
    }
    pickerEvent.remove();
	$('#calendar_choose_bg').remove();
});