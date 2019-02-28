var myScroll = null;
var isSearch = false, isLoadSelect = false;
var hasMore = true;
var nowPage = 1, is_open_pick = null, deliver_type = null, is_change = null, timeOut = null;
$(document).ready(function(){
	var indexData = common.getCache('indexData',true);
	$('.public .content').html(indexData.have_appoint_name + '订单');
	
    $(".mask").height($(window).height());
 
    var currYear = new Date().getFullYear();
    var opt = {  
        'dateYMD': {
            preset: 'date',
            dateFormat: 'yyyy-mm-dd',
            theme: 'android-ics light', //皮肤样式
            display: 'bottom',           //显示方式
            mode: 'scroller',           //日期选择模式
            showNow: true,
            nowText: "今天",
            onSelect: function (valueText, inst) {
            	$('.entry ul').empty();
                if($('#find_value').val() == ''){
                    isSearch = false;
                } else {
                    isSearch = true;
                }
                hasMore = true;
                nowPage = 1;
                showList();
            }
        },'select': {
            preset: 'select'
        }
    } 
    $('#stime').scroller($.extend(opt['dateYMD'],opt['default']));
    $('#etime').scroller($.extend(opt['dateYMD'],opt['default']));
    
    $('.entry ul').empty();
    showList();
    $('body,html').animate({scrollTop : 0}, 300);
    $('.query').click(function(){
    	$('.entry ul').empty();
        if($('#find_value').val() == ''){
            isSearch = false;
        } else {
            isSearch = true;
        }
        hasMore = true;
        order_id = 0;
        nowPage = 1;
        showList();
        return false;
    });
	
    $('.entry').css({height:$(window).height()-184});
    $('.entry ul').after('<div class="jroll-infinite-tip">正在加载中...</div>');
    common.scroll($('.entry'),function(scrollIndex){
        showList(scrollIndex);
    });
    
    //验证
    $(document).on('click', '.yanzhen', function(e){
        e.stopPropagation();
        $('#order_id').val($(this).data('id'));
        $(".seek, .mask").show();
    });
    $(document).on('click', '.ensure', function(e){
        common.http('Storestaff&a=appointVerify',{'order_id':$('#order_id').val(), noTip:true}, function(data){
        	$('.overtime_' + $('#order_id').val()).remove();
        	$(".seek, .mask").hide();
        });
    });
    $(".mask,.seek .del, .close").click(function(){
        $(".seek,.mask").hide();
    });

});



function showList(scrollIndex)
{
    if (hasMore == false) {
    	return false;
    }
    var pay_type = $('select[name=pay_type]').val(), searchtype = $('select[name=searchtype]').val();
    var stime = $('#stime').val(), etime = $('#etime').val(), key = $('#find_value').val();
    if (isSearch == false) {
        common.http('Storestaff&a=appointList',{'page':nowPage, 'key':key, 'stime':stime, 'etime':etime, 'pay_type':pay_type, 'searchtype':searchtype, noTip:true}, function(data){
        	if (!isLoadSelect) {
        		var html = '<select name="pay_type"><option value="">全部支付方式</option>';
        		for (var i in data.pay_list) {
        			html += '<option value="' + i + '">' + data.pay_list[i].name + '</option>';
        		}
        		html += '</select>';
        		$('.selsct_pad').html(html);
        		isLoadSelect = true;
        	}
            if(nowPage >= data.page){
                hasMore = false;
                $('.jroll-infinite-tip').addClass('hideText');
            }
            if (data.order_list.length > 0) {
	            laytpl($('#listTpl').html()).render(data.order_list, function(html){
	                $('.entry ul').append(html);
	                common.scrollEnd(scrollIndex);
	            });
	            nowPage++;
            } else {
            	$('.entry ul').html('<div class="jroll-infinite-tip">暂无数据</div>');
            }
        });
    } else {
        common.http('Storestaff&a=appointList',{'page':nowPage, 'stime':stime, 'etime':etime, 'pay_type':pay_type, 'key':key, 'searchtype':searchtype, noTip:true}, function(data){
            if(nowPage >= data.page){
                hasMore = false;
                $('.jroll-infinite-tip').addClass('hideText');
            }
            if (data.order_list.length > 0) {
	            laytpl($('#listTpl').html()).render(data.order_list, function(html){
	                $('.entry ul').append(html);
	                common.scrollEnd(scrollIndex);
	            });
	            nowPage++;
            } else {
            	$('.entry ul').html('<div class="jroll-infinite-tip">暂无数据</div>');
            }
        });
    }
}