
var isSearchListShow=true;
var total1 = 10;
var total2 = 10;
var pagesize = 6;
var pages1 = Math.ceil(total1 / pagesize);
var pages2 = Math.ceil(total2 / pagesize);


$(function(){
    // 获取路径后面的值
    var locationHash = location.hash.replace("#","");
    var locationHashParam = locationHash.split('-');
    var locationHashItem = locationHashParam[0];
    console.log(locationHashItem);
    if (locationHashItem == 'invitationSearch') {
        $('.head-ad').css('display', 'none');
        $('#pageShopSearchHeader').css('display', 'block');
        $('#women_info').css('display', 'none');
        $('#men_info').css('display', 'none');
        $('#sex_1').css('display', 'none');
        $('#sex_2').css('display', 'none');
        $('#search_info').css('display', 'none');
    }


    /*页面点击事件*/
    $('.invitation-link').on('click',function(){
        window.location.href = window.location.href + '#' + $(this).attr('data-url');
        $('#search_info').css('display', 'none');
        $('#show_sex_3').html('');
        $('#show_sex_4').html('');
    });

    /*搜索的返回*/
    $('.searhBackBtn').click(function () {
        if(checkLifeApp() && getLifeAppVersion() >= 50){
            if(checkIos()){
                $('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none;"></iframe>');
                if(getLifeAppVersion() < 70){
                    window.history.go(-1);
                }
            }else{
                window.lifepasslogin.webViewGoBack();
            }
        }else{
            window.history.go(-1);
        }
        $('#search_info').css('display', 'none');
        $('#show_sex_3').html('');
        $('#show_sex_4').html('');
        $('#pageShopSearchTxt').val('').trigger('input');
        $('.head-ad').css('display', 'block');
        $('#pageShopSearchHeader').css('display', 'none');
        $('#women_info').css('display', 'block');
        $('#men_info').css('display', 'block');
        $('#sex_1').css('display', 'block');
        $('#sex_2').css('display', 'block');
        setTimeout(function () {
            window.location.href = window.location.href
        }, 100)
    });


    /*搜索的清除*/
    $('#pageShopSearchDel').click(function(){
        $('#pageShopSearchTxt').val('').trigger('input');
        $('#search_info').css('display', 'none');
        $('#show_sex_3').html('');
        $('#show_sex_4').html('');
    });


    /*搜索昵称*/
    $('#pageShopSearchBtn').click(function(){
        var nickname = $.trim($("#pageShopSearchTxt").val());
        if(nickname == ''){
            motify.log('请您输入昵称');
        }else{
            isSearchListShow = false;
            var _page = 1;
            $.ajax({
                type : "GET",
                data : {'page' : _page, 'pagesize' : pagesize, 'nickname': nickname},
                url :  '/wap.php?c=Invitation&a=search_ajaxmore',
                dataType : "json",
                success : function(RES) {
                    data = RES.data;
                    console.log('返回的数据---------', data);
                    var _tmp_html = '';
                    var _tmp_html2 = '';
                    var sex1_lenth = 0;
                    var sex2_lenth = 0;
                    $.each(data, function(x, y) {
                        if (y.sex==1) {
                            /*如果有性别为1 的显示高富帅*/
                            _tmp_html += '<dd><a href="/wap.php?c=Invitation&a=userinfo&uid='+ y.uid +'">';
                            _tmp_html += '<figure><div><img src="'+ y.avatar +'"  style="height:100px;"/></div>';
                            _tmp_html += '<figcaption><label style="cursor:pointer;width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'+ y.nickname +'</label></figcaption>';
                            _tmp_html += '</figure>';
                            _tmp_html += '</a></dd>';
                            sex1_lenth++;
                        } else {
                            /*如果有性别为2 的显示女神*/
                            _tmp_html2 += '<dd><a href="/wap.php?c=Invitation&a=userinfo&uid='+ y.uid +'">';
                            _tmp_html2 += '<figure><div><img src="'+ y.avatar +'"  style="height:100px;"/></div>';
                            _tmp_html2 += '<figcaption><label style="cursor:pointer;width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'+ y.nickname +'</label></figcaption>';
                            _tmp_html2 += '</figure>';
                            _tmp_html2 += '</a></dd>';
                            sex2_lenth++;
                        }
                    });
                    if (sex1_lenth == 0) {
                        $('#search_men').css('display', 'none');
                    } else {
                        $('#search_men').css('display', 'block');
                    }
                    if (sex2_lenth == 0) {
                        $('#search_women').css('display', 'none');
                    } else {
                        $('#search_women').css('display', 'block');
                    }
                    $('#show_sex_3').html('');
                    $('#show_sex_4').html('');
                    $('#show_sex_3').append(_tmp_html);
                    $('#show_sex_4').append(_tmp_html2);
                    $('#search_info').css('display', 'block');
                }
            });
        }
    });
})

function checkLifeApp() {
    if(/(pigcmso2oreallifeapp)/.test(navigator.userAgent.toLowerCase()) || (/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase()))){
        return true;
    }else{
        return false;
    }
}

function getLifeAppVersion(){
    var reg = /versioncode=(\d+),/;
    var arr = reg.exec(navigator.userAgent.toLowerCase());
    if(arr == null){
        return 0;
    }else{
        return parseInt(arr[1]);
    }
}
function checkIos(){
    if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
        return true;
    }else{
        return false;
    }
}

function invitationSearch() {
    console.log('搜索')
    $('.head-ad').css('display', 'none');
    $('#pageShopSearchHeader').css('display', 'block');
    $('#women_info').css('display', 'none');
    $('#men_info').css('display', 'none');
    $('#sex_1').css('display', 'none');
    $('#sex_2').css('display', 'none');
}