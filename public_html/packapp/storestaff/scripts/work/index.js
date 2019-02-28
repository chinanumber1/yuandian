var androidUrl = '', iosUrl = '', iosBao = '', androidBao = '', code = '', mkey = '', load = 1;
var doubble = false;
var device_type = 1;
var tooth = false;
var interval = null, setIn = null, print_mobile_code = '';
var downloads = true;
var print_mcode = '';
var print_paper = '';
var print_image = '';
var print_mkey = '';
var lanyaList = [];
var client = common.checkAndroidApp() ? 2 : (common.checkIosApp() ? 1 : 0);
if (common.checkIosApp()) {
    downloads = false;
    common.iosFunction('iOS_Device_UUID');
}
function back_device_uuid(res) {
    print_mobile_code = res;
}

$(document).ready(function () {
    var indexDatas = common.getCache('indexData', true);

    if (common.getCache('isStarted', true)) {
        if (indexDatas && indexDatas.pay_in_store == '1') {
            if ($(window).height() > 526) {
                $('.indexCashierTop .fl').css('padding', (($(window).height() * 0.3 - 80) / 2) + 'px 0px');
            }
            if (common.checkIosApp()) {
                $('.indexCashierTop .fl').css('padding-top', parseInt($('.indexCashierTop .fl').css('padding-top').replace('px', '')) + 20 + 'px');
            }

            $('#cashierPage').removeClass('hide');
        } else {
            $('#mainPage').removeClass('hide');
            $(".group_list a").each(function () {
                $(this).height($(this).width() * 0.94);
            });
        }
        $('#startBg').addClass('hide');
    } else {
        common.setCache('isStarted', 'true', true);
        $('#startBg img').css({height: $(window).height(), width: $(window).width()});
        $('#startBg').removeClass('hide');
        $('#mainPage').addClass('hide');
        $('#cashierPage').addClass('hide');
    }
    if (urlParam.from == 'merchant' && urlParam.ticket) {
        common.removeAllCache(false);
        common.removeAllCache(true);
        common.setCache('isStarted', 'true', true);
        common.setCache('ticket', urlParam.ticket, true);
    }
    if (common.checkLogin() == false) {
        return false;
    }
    if (common.checkIosApp()) {
        common.iosFunction('changecolor/#2ECC71');

    } else if (common.checkAndroidApp()) {
        window.pigcmspackapp.changecolor('#2ECC71');
    }
    var staffArr = common.getCache('store_staff', true);
    if (staffArr) {
        indexDataObj(staffArr);
    } else {
        common.http('Storestaff&a=login', {noTip: true, 'client': client}, function (data) {

            common.setCache('ticket', data.ticket, true);
            common.setCache('ticket', data.ticket);
            common.setCache('store_staff', data.user, true);
            indexDataObj(data.user);
        }, function (data) {
            common.removeAllCache(false);
            common.removeAllCache(true);
            location.href = 'login.html';
        });
    }

    $('.group .group_list').height($(window).height() - (common.checkIosApp() ? 95 : 75) - 40);
    common.onlyScroll($('.group .group_list'));
    if (!common.checkWeixin() && !common.checkApp()) {
        $('#scanQrcodeBox').remove();
        $('#noScanShow').height($('#noScanShow').width()).removeClass('hide');
    }
    $('#scanQrcode,#cashierScanCode').click(function () {
        common.scan('scanResult');
    });

    $('.printer').click(function () {
        var content = '<div style="letter-spacing:2px;font-size:16px;">';
        content += ' <div>终&nbsp;端&nbsp;号：' + print_mcode + '</div>';
        content += ' <div>密&nbsp;&nbsp;&nbsp;&nbsp;钥：' + print_mkey + '</div>';
        content += ' <div>纸张类型：' + print_paper + 'mm</div>';
        content += ' <div>支持图片：' + (print_image == '1' ? '支持' : '不支持') + '</div>';
        content += ' <div>&nbsp;</div>';
        content += ' <div style="font-size:12px;">添加打印机后，请重新启动本软件。</div>';
        content += '</div>';
        layer.open({
            title: '打印机参数',
            content: content,
            btn: []
        });
    });
    common.http('Storestaff&a=config', {noTip: true, 'client': client}, function (data) {
        console.log(data);
        androidUrl = data.mer_android_download_url;
        iosUrl = data.mer_ios_download_url;
        androidBao = data.mer_android_package_name;//安卓包名
        iosBao = data.mer_ios_package_name;//ios包名
    });

    laytpl.mobileCode = function (res) {
        if (res == print_mobile_code) {
            return true
        } else {
            return false
        }
    };
    if (common.checkApp()) {
        if (client == 2) {
            device_type = 1;
        } else {
            device_type = 2;
        }
        setTimeout(function () {
            common.http('Storestaff&a=blueteeth_hardware', {
                'device_type': device_type,
                'noTip': true
            }, function (data) {
                //获取蓝牙打印机数据
                if (data.print_list && data.print_list.length >= 2) {
                    if (device_type == 2) {
                        var lens = 0;
                        for (var i = 0; i < data.print_list.length; i++) {
                            if (data.print_list[i].print_mobile_code == print_mobile_code) {
                                lens++
                            }
                        }
                        if (lens >= 2) {
                            laytpl(document.getElementById('cardlist').innerHTML).render(data.print_list, function (html) {
                                $('#kd_entry').html(html);
                                doubble = true;
                                $(".printerAdd").show();
                            });
							if (device_type == 2) {
								common.iosFunction('get_printer/get_printer');
							} else {
								window.pigcmspackapp.get_printer('get_printer');
							}
                        } else {
                            doubble = false;
                            $(".printerAdd").hide();
							if (device_type == 2) {
								common.iosFunction('get_printer/get_printer');
							} else {
								window.pigcmspackapp.get_printer('get_printer');
							}
                        }
                    } else {
                        laytpl(document.getElementById('cardlist').innerHTML).render(data.print_list, function (html) {
                            $('#kd_entry').html(html);
                            doubble = true;
                            $(".printerAdd").show();
                        });
						if (device_type == 2) {
							common.iosFunction('get_printer/get_printer');
						} else {
							window.pigcmspackapp.get_printer('get_printer');
						}
                    }
                } else {
                    if (data.print_list && data.print_list.length > 0) {
                        lanyaList = data.print_list;
                    }
                    doubble = false;
                    $(".printerAdd").hide();
                    if (device_type == 2) {
                        common.iosFunction('get_printer/get_printer');
                    } else {
                        window.pigcmspackapp.get_printer('get_printer');
                    }
                }
            });
        }, 1000)
    }
    /*启动监听APP退出事件*/
    if (common.checkApp()) {
        setInterval(function () {
            var isLogout = common.getCache('isLogout', true);
            if (isLogout) {
                common.removeCache('isLogout', true);
                location.href = 'login.html';
            }
        }, 300);
    }
    ;

    $('#loginout').click(function () {
        layer.open({
            content: '您确定要退出吗？'
            , btn: ['确定', '取消']
            , yes: function (index) {
                common.removeCache('ticket');
                common.removeCache('ticket', true);

                location.href = 'login.html';

                layer.close(index);
            }
        });
    });
    $('.openShopApp').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        if (common.checkApp()) {
            if (common.checkAndroidApp()) {
                window.pigcmspackapp.judgeappexist(androidBao, 'downApp');
            } else {
                common.iosFunction('judgeappexist/' + iosBao + '/downApp');
            }
        }
    });
});

function downApp(status) {
    var ticket1 = common.getCache('ticket');
    var ios_device = common.getDeviceId();
    if (status == 1) {
        if (common.checkAndroidApp()) {
            //打开商家APP
            window.pigcmspackapp.openapp(androidBao, '', 'from=storestaff&ticket=' + ticket1);
        } else {
            common.iosFunction('openapp/' + iosBao + '//' + 'from=storestaff&ios_device=' + ios_device + '&ticket=' + ticket1);
        }
    } else {
        layer.open({
            content: '暂时未检测到您的商家中心app，系统默认给您下载，点击确定按钮后开始下载。',
            btn: ['确定', '取消'],
            yes: function (index) {
                if (!downloads) {

                    var iosHref = window.btoa(iosUrl);
                    iosHref = iosHref.replace(/\//g,"&");
                    common.iosFunction('downLoadApp/' + iosBao + '/' + iosHref);
                    layer.close(index);
                } else {

                    window.pigcmspackapp.downLoadApp(androidUrl, androidBao);
                    layer.close(index);
                }

            }
        });
    }
}

function getParam(url, name) {
    var reg = new RegExp("[&|?]" + name + "=([^&$]*)", "gi");
    var a = reg.test(url);
    return a ? RegExp.$1 : "";
}

function scanResult(value) {
    var result = value;
    if (result.indexOf('subcard') != -1) {
        result = value.split('_');
        pass = result[1];

        common.http('Storestaff&a=sub_card_verify', {'pass': pass, 'noTip': false}, function (data) {
            if (!data.errorCode) {
                motify.log('验证消费成功！');
                setTimeout(openWebviewUrl("sub_card_order_detail.html?from_scan=1&pass=" + pass), 5000);
            } else {
                layer.open({
                    title: ['错误提示：', 'background-color:#2ecc71;color:#fff;'],
                    content: '您扫描的内容 “ <font color="red">' + result + '</font> ” ' + data.errorMsg,
                    btn: ['确定'],
                    end: function () {
                    }
                });
            }
        });
    } else if (result.length == 14) {
        common.http('Storestaff&a=scan_payid_check', {'payid': result}, function (data) {
            if (data.uid > 0) {
                openWebviewUrl("cashier_set.html?uid=" + data.uid + "&from_scan=1&payid=" + data.payid);
            }
        });
    } else {
        if (result.indexOf('http://') !== 0 && result.indexOf('https://') !== 0) {
            var strArr = result.split(',');
            if (strArr.length == 2) {
                var barCode = strArr[1];
            } else {
                barCode = result;
            }
            if (barCode.length == 13) {
                common.setCache('shopBarCode', barCode, true);
                openWebviewUrl("retail.html");
            } else {
                layer.open({
                    title: ['错误提示：', 'background-color:#2ecc71;color:#fff;'],
                    content: '您扫描的内容 “ <font color="red">' + result + '</font> ” 暂时无法识别',
                    btn: ['确定'],
                    end: function () {
                    }
                });
            }
        } else {
            var ctype = getParam(result, 'a'), id = getParam(result, 'id'), c = getParam(result, 'c');
            if (ctype != 'group_qrcode' || id == '' || c != 'Storestaff') {
                var indexData = common.getCache('indexData', true);
                layer.open({
                    title: ['错误提示：', 'background-color:#2ecc71;color:#fff;'],
                    content: '您扫描的内容不是有效的' + indexData.have_group_name + '验证二维码',
                    btn: ['确定'],
                    end: function () {
                    }
                });
            } else {
                if (ctype == 'group_qrcode') {
                    openWebviewUrl("group_detail.html?order_id=" + getParam(result, 'order_id'));
                }
            }
        }
    }
}

function scanCardResult(str) {
    var code = str
    common.http('Storestaff&a=scan_payid_check', {'payid': str}, function (data) {
        if (data.uid > 0) {
            openWebviewUrl("cashier_set.html?uid=" + data.uid + "&from_scan=1&payid=" + data.payid);
        }
    });
}

function indexDataObj(staffArr) {
    if (staffArr) {
        $('#staff_name,#footer_staff_name').html(staffArr.name);
        $('#footer_store_name').html(staffArr.store_name);
    }
    var indexData = common.getCache('indexData', true);

    if (staffArr.type == 2) {
        if(common.checkApp()){
            $('.openShopApp').show();
        }
    } else {
        $('.openShopApp').hide();
    }
    if (indexData) {
        editData(indexData);
    } else {
        common.http('Storestaff&a=index', {noTip: true}, function (data) {
            common.setCache('indexData', data, true);
            editData(data);
        });
    }
}

function editData(data) {
    console.log(data)
    var countArr = [];
    if (data.have_group != '1') {
        $('.groupBox').remove();
    } else {
        $('.groupName').html(data.have_group_name);
    }

    if (data.have_shop != '1') {
        $('.shopBox').remove();
    } else {
        $('.shopName').html(data.have_shop_name);
    }

    if (data.have_meal != '1') {
        $('.mealBox').remove();
    } else {
        $('.mealName').html(data.have_meal_name);
    }

    if (data.have_appoint != '1') {
        $('.appointBox').remove();
    } else {
        $('.appointName').html(data.have_appoint_name);
    }

    if (data.have_store != '1') {
        $('.storeBox').remove();
    } else {
        $('.storeName').html(data.have_store_name);
    }

    if (data.pay_in_store != '1') {
        $('.cashBox').remove();
    } else {
        $('.cashName').html(data.have_cash_name);
    }

    if (data.open_sub_card != '0') {
        $('.sub_card').show();
    } else {
        $('.sub_card').hide();
    }

//页面布局
    if (data.pay_in_store == '1') {
        if ($(window).height() > 526) {
            $('.indexCashierTop .fl').css('padding', (($(window).height() * 0.3 - 80) / 2) + 'px 0px');
        }
        if (common.checkIosApp()) {
            $('.indexCashierTop .fl').css('padding-top', parseInt($('.indexCashierTop .fl').css('padding-top').replace('px', '')) + 20 + 'px');
        }

        $('#mainPage').addClass('hide');
        $('#cashierPage').removeClass('hide');
    } else {
        $('#cashierPage').addClass('hide');
        $('#mainPage').removeClass('hide');
        $(".group_list a").each(function () {
            $(this).height($(this).width() * 0.94);
        });
    }
    $('#startBg').addClass('hide');

//请求完页面参数判断是否需要直接跳转，例如页面列表，或订单详情。
    if (urlParam.gopage && !common.getCache('isGoOtherPage', true)) {
        var href = location.protocol + '//' + location.host + '/packapp/' + visitWork + '/' + urlParam.gopage + '.html' + (urlParam.goparam ? '?' + urlParam.goparam : '');

        if (common.checkApp()) {
            if (common.checkAndroidApp()) {
                window.pigcmspackapp.createwebview(href);
            } else {
                var iosHref = window.btoa(href);
                iosHref = iosHref.replace(/\//g,"&");
                common.iosFunction('createwebview/' + iosHref);
            }
        } else {
            common.setCache('isGoOtherPage', 'true', true);
            location.href = href;
            return false;
        }
    }

//声音提醒
    $('body').append('<video id="playMp3Tip" controls="true" loop="loop" src="source/new_order.mp3" style="display:none;" webkit-playsinline playsinline></video>');
    HTMLVideoElement.prototype.stop = function () {
        this.pause();
        this.currentTime = 0.0;
    }
    if (countArr.length > 0) {
        var nowIndex = 0;
        var timeArr = {};
        var playMp3Tip = null;
        var newOrderTip = null;
        setInterval(function () {
            var nowType = countArr[nowIndex];
            $('.loader').removeClass('on');
            $('.' + nowType + '_loader').addClass('on').show();
            common.http('Storestaff&a=' + nowType + '_count', {noTip: true, time: timeArr[nowType]}, function (data) {
                timeArr[nowType] = data.time;
                $('.' + nowType + '_loader em').html(data.count);
                $('.loader').removeClass('on');

                if (data.count > 0) {
                    if (newOrderTip != null) {
                        $('#playMp3Tip').trigger('stop');
                        layer.close(newOrderTip);
                    }

                    if (playMp3Tip == null) {
                        $('#playMp3Tip').trigger('play');
                    }

                    /*音乐播放5分钟*/
                    playMp3Tip = setTimeout(function () {
                        $('#playMp3Tip').trigger('stop');
                    }, 300000);
                    newOrderTip = layer.open({
                        title: '新订单提示'
                        , content: '您有新的订单需要处理。'
                        , btn: ['确定']
                        , end: function (index) {
                            $('#playMp3Tip').trigger('stop');
                            clearTimeout(playMp3Tip);
                            playMp3Tip = null;
                            newOrderTip = null;
                        }
                    });
                }
            });
            if (nowIndex + 1 == countArr.length) {
                nowIndex = 0;
            } else {
                nowIndex++;
            }
        }, 5000);
    }
}

var exitLayer = -1;

function appbackmonitor() {
    if (exitLayer != -1) {
        window.pigcmspackapp.closewebview(2);
    } else {
        layer.closeAll();
        exitLayer = layer.open({
            content: '您确定要退出程序吗？再次按返回键将退出。'
            , btn: ['确定', '取消']
            , yes: function (index) {
                window.pigcmspackapp.closewebview(2);
                layer.close(index);
            }
            , end: function (index) {
                exitLayer = -1;
            }
        });
    }
}

function lanya(data) {
    var mkey1 = data.mkey;
    if (common.checkAndroidApp()) {

        window.pigcmspackapp.connectDevice(data.print_bluetooth_code);
    } else {
        common.iosFunction('connectDevice/' + data.print_bluetooth_code);
    }
    motify.log("打印模块开始每3秒自动请求打印");
    setInterval(function () {
        common.http('Storestaff&a=own_print_work', {noTip: true, mkey: mkey1}, function (data) {
            if (data.info != '') {
                    if (common.checkAndroidApp()) {
                        window.pigcmspackapp.printer_work(data.info, '');
                    } else {
                        var iosHref = window.btoa(unescape(encodeURIComponent(data.info)));

                        iosHref = iosHref.replace(/\//g,"&");

                        common.iosFunction('printer_work/' + iosHref + '/');
                    }

            }

        }, function (data) {
        });
    }, 3000);
};

function get_printer(arg1, arg2, arg3) {
    if (common.checkApp()) {
        if (load > 2) {
            clearInterval(setIn);
            clearInterval(interval);
        }
        if (arg1 != '') {
			 $('.printer').show();
			$(".printerAdd").hide();
            print_mcode = arg1;
            print_paper = arg2;
            print_image = arg3;
            print_mkey = common.getDeviceId();
            common.http('Storestaff&a=get_print_has',{noTip:true,mkey:print_mkey}, function(data){
				motify.log("打印模块开始每3秒自动请求打印");
                if (!doubble) {
                    setInterval(function () {
                        common.http('Storestaff&a=own_print_work', {noTip: true, mkey: print_mkey}, function (data) {

                                if (data.info != '') {
                                    if (common.checkAndroidApp()) {
                                        window.pigcmspackapp.printer_work(data.info, '');
                                    } else {
                                        var iosHref = window.btoa(unescape(encodeURIComponent(data.info)));

                                        iosHref = iosHref.replace(/\//g,"&");

                                        common.iosFunction('printer_work/' + iosHref + '/');
                                    }
                                }


                        }, function (data) {

                        });
                    }, 3000);
                } else {
                    setIn = setInterval(function () {
                        common.http('Storestaff&a=own_print_work', {noTip: true, mkey: print_mkey}, function (data) {
                            if (data.info != '') {
                                    if (common.checkAndroidApp()) {
                                        window.pigcmspackapp.printer_work(data.info, '');
                                    } else {
                                        var iosHref = window.btoa(unescape(encodeURIComponent(data.info)));

                                        iosHref = iosHref.replace(/\//g,"&");

                                        common.iosFunction('printer_work/' + iosHref + '/');
                                    }

                            }
                        }, function (data) {

                        });
                    }, 3000);
                }
            },function(res){
                motify.log(res.errorMsg)
            })
        } else {
            if (!doubble) {
                if (lanyaList.length > 0) {
                    if (client == 2) {
                        lanya(lanyaList[0])
                    } else {
                        for (var i = 0; i < lanyaList.length; i++) {
                            if (lanyaList[i].print_mobile_code == print_mobile_code) {
                                lanya(lanyaList[i])
                            }
                        }
                    }
                }
            } else {
                if (tooth) {
                    tooth = false;
                    if (common.checkAndroidApp()) {

                        window.pigcmspackapp.connectDevice(code);
                    } else {

                        common.iosFunction('connectDevice/' + code);
                    }

                    motify.log("打印模块开始每3秒自动请求打印");
                    interval = setInterval(function () {
                        common.http('Storestaff&a=own_print_work', {noTip: true, mkey: mkey}, function (data) {
                            if (data.info != '') {
                                    if (common.checkAndroidApp()) {
                                        window.pigcmspackapp.printer_work(data.info, '');
                                    } else {

                                        var iosHref = window.btoa(unescape(encodeURIComponent(data.info)));

                                        iosHref = iosHref.replace(/\//g,"&");

                                        common.iosFunction('printer_work/' + iosHref + '/');
                                    }
                            }
                        }, function (data) {

                        });
                    }, 3000);
                }

            }

        }
    }


}

function getDeviceStatus(type) {
    if (type == 1) {
        motify.log("蓝牙打印机正在连接");
    } else if (type == 2) {
        motify.log('蓝牙打印机断开连接');
        $('.tishi').show();
        $('.tishi').addClass('active');
        $('.indexCashierTop').addClass('active');
        $('.tishi i').click(function (e) {
            $('.tishi').hide();
            $('.indexCashierTop').removeClass('active');
        });
    } else {
        motify.log('蓝牙打印机连接成功');
    }
}

$(".printerAdd").on("click", function () {
    $("#printerAdd").show()
});
$("#printerAdd").on("click", ".laymshade", function () {
    $("#printerAdd").hide()
});
$("#printerAdd").on("click", ".bluetooth", function () {
    load++;
    $(".bluetooth").removeClass('active');
    $(".noBluetooth").removeClass('active');
    $(this).addClass("active");
    code = $(this).attr('data-code');
    mkey = $(this).attr("data-mkey");
    tooth = false;
    $("#printerAdd").hide();
    if (common.checkAndroidApp()) {
        tooth = true;
        window.pigcmspackapp.get_printer('get_printer');
    } else {
        tooth = true;
        common.iosFunction('get_printer/get_printer');
    }
});
$("#printerAdd").on("click", ".noBluetooth", function () {
    $(this).addClass("active");
    $(".bluetooth").removeClass('active');
    setTimeout(function () {
        if (load != 1) {
            clearInterval(setIn);
            clearInterval(interval);
        }
        $("#printerAdd").hide()
    }, 100)
})