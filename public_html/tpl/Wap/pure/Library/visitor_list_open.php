
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <title>给访客开门</title>
    </if>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name='apple-touch-fullscreen' content='yes'/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="address=no"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script>
    <style type="text/css">
        p{ font-size:12px;}
        .order_list{ background-color: #EAEAEB !important;    padding-right: 15px !important;}
        .village_my nav{border-top:none !important; border-bottom:none !important;}
        .village_my nav.order_list section p{ padding-left:0;}
        .village_my nav.order_list section p .red{ color:red}
        .village_my nav.order_list section p .green{ color:green}
        .order_list{border:none !important;}
        .visitor_person{width: 100%;overflow: hidden;height: 35px;margin-bottom: 10px;    display: flex;background:#fff}
        .visitor_person_checked{margin-top: 7px;vertical-align: bottom;width: 20px;height: 20px;    margin-right: 9px;;position: relative;}
        .visitor_person_checked div {left: 3px;position: absolute;text-align: center;width: 12px;border:2px solid #ddd;border-radius:50%;height: 12px;top: 3px;}
        .selectChecked{background: #06c1ae;}
        .visitor_person_checked input{opacity: 0; -webkit-opacity: 0;-moz-opacity: 0;  -khtml-opacity: 0;    position: absolute;top: 0px;z-index:2;width:100%;height:100%;}
        .visitor_person_home{line-height:35px;flex: 1;  height: 35px;background: #fff;font-size: 12px !important;padding-left: 10px;}

        .village_my nav {border-bottom: 0px solid #DADADC !important;}
        .village_my nav section{padding-bottom:0px !important;padding-top:0px !important;border-bottom: 0px solid #DADADC !important;}
        .minute{   line-height: 16px;width: 23%;   height: 16px;   margin-bottom: 30px;   padding: 6px 15px;   -webkit-user-select: text;   border: 1px solid rgba(0, 0, 0, .2);    border-radius: 3px;   outline: none; background-color: #fff;-webkit-appearance: none;}
        .timeType{  height: 30px; padding: 0 3px 3px; border: 1px solid rgba(0, 0, 0, .2);    border-radius: 3px; }
        .greenColor{color:#06c1ae;}
        .align_center{text-align:center;}
        .mar-10{margin-bottom:10px;}
        .openWx{background: #06c1ae; color: #fff;  padding: 10px 30px;}
    </style>
</head>
<body>
<header class="pageSliderHide" ><div id="backBtn"></div>给访客开门</header>
<div id="container" >
    <div id="scroller" class="village_my ">
        <div style='color:#06c1ae;line-height:25px;height:25px; margin: 8px 0px;；font-size:12px'>
            <span style='  padding: 7px 15px;'>选择将开放给访客开门的单元</span>
        </div>
        <if condition="$village_floor_list">
            <form enctype="multipart/form-data" class="form-horizontal" method="post" id="add_share_form">
            <input id="form_village_id" name="village_id" value="{pigcms{$_GET['village_id']}" type="hidden">
            <nav class="order_list">
                <div>
                    <volist name="village_floor_list" id="vo">
                        <div class="form-group">
                            <div class="radio">
                                <volist name="vo" id="child">
                                    <div  class='visitor_person' data-open="false">
                                        <div  class='visitor_person_home'>
                                            {pigcms{$child.floor_name}
                                        </div>
                                        <div class='visitor_person_checked'>
                                            <div class=''></div>
                                            <input type="checkbox" name="share_info[]" value="{pigcms{$child.floor_id}" id="Config_share_{pigcms{$child.floor_id}">
                                        </div>
                                    </div>
                                </volist>
                            </div>
                        </div>
                    </volist>


                    <!--								<div  class='visitor_person'>-->
                    <!--                            	    <div  class='visitor_person_home'>-->
                    <!--                            	    	14单元-->
                    <!--                            	    </div>-->
                    <!--                            	     <div class='visitor_person_checked'>-->
                    <!--                                        <div class=''></div>-->
                    <!--                                         <input type="checkbox" class='checked' value='0'>-->
                    <!--                                      </div>-->
                    <!--                            	</div> -->
                    <!--                            	<div  class='visitor_person'>-->
                    <!--                                   <div  class='visitor_person_home'>14单元 </div>-->
                    <!--                                   <div class='visitor_person_checked'>-->
                    <!--                                       <div class=''></div>-->
                    <!--                                       <input type="checkbox" class='checked' value='1'>-->
                    <!--                                   </div>-->
                    <!--                                </div>-->
                    <!--                                <div  class='visitor_person'>-->
                    <!--                                    <div  class='visitor_person_home'>14单元 </div>-->
                    <!--                                     <div class='visitor_person_checked'>-->
                    <!--                                        <div class=''></div>-->
                    <!--                                         <input type="checkbox" class='checked' value='2'>-->
                    <!--                                     </div>-->
                    <!--                                 </div>-->
                </div>
                <div class='greenColor  mar-10'style="font-size:12px;"><span style="padding-left: 24px;">设置开门时效</span></div>
                <div class='align_center'>
                    <input id="share_time_length_id" type='number' name="share_time_length" class='minute'/>
                    <select id="share_time_type_id" name="share_time_type" class='timeType greenColor'>
                        <option value="0" selected="selected">分钟</option>
                        <option value="1">小时</option>
                        <option value="2">天</option>
                    </select>
                </div>
                <div class='align_center'><a  href='javascript:;' class='openWx'>分享给访客开门</a></div>
            </nav>
        </form>
            <else/>
            <div class="noMoreDiv" style="margin-top:20px;background:#ebebeb;">暂无单元数据</div>
        </if>
    </div>
    <div  id="shareVillage" >
        <div id="" >

        </div>
    </div>

</div>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
{pigcms{$shareScript}
<script type="text/javascript">
    window.shareData = {
        "moduleName":"Village",
        "moduleID":"0",
        "imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
        "sendFriendLink": "http://hf.pigcms.com/wap.php?g=Wap",
        "tTitle": "访客开门",
        "tContent": "我赠送一个临时开门权限"
    };
</script>
<script type="text/javascript">
    console.log(wx.config.debug)
    //隐藏分享功能
     function hideMenu(){
         wx.ready(
             function (){
                 wx.hideOptionMenu();
             });
     }
     hideMenu()
    //显示分享功能
     function showMenu(){
         wx.showOptionMenu();
     }
     //判断在县程序环境中
    function readys() {
        console.log(window.__wxjs_environment === 'miniprogram')
    }
    if (!window.WeixinJSBridge || !WeixinJSBridge.invoke) {
        document.addEventListener('WeixinJSBridgeReady', readys, false)
    } else {
        readys()
    }
    //清除弹层
     function shareVillage (){
          $("#shareVillage").show();
          $("#container").css("top",'0px');
         setTimeout(function(){
             $("#shareVillage").hide();
             $("#container").css("top",'50px')
         },3000)
     }
    $("#shareVillage").on("click",function(){
        $("#container").css("top",'50px');
        $(this).hide()

    });
     var bind_name = 'input';
     if (navigator.userAgent.indexOf("MSIE") != -1){ bind_name = 'propertychange' }
     $(".minute").on(bind_name,function(){
         hideMenu();

     });
     //选择时间类型
     $("#share_time_type_id").on("change",function(){
         hideMenu();
     })
     //选择门禁
    $(".visitor_person").on("click", function () {
        if ($(this).attr('data-open') == 'false') {
            $(this).attr('data-open', 'true');
            $(this).find(".visitor_person_checked div").addClass('selectChecked');
            $(this).find(".visitor_person_checked input").prop('checked', true);
        } else {
            $(this).attr('data-open', 'false');
            $(this).find(".visitor_person_checked div").removeClass('selectChecked');
            $(this).find(".visitor_person_checked input").prop('checked', false);
        }
    })
    $(".openWx").on("click",function(){
        //分享按钮
        if ($(".selectChecked").length > 0) {
            if ($(".minute").val() != '') {
                var share_info = [];
                var cbValue = 0;
                $(":checkbox:checked").each(function (i,item) {
                    cbValue =$(this).val();
                    share_info[i] = cbValue
                });
                var share_time_type = $('#share_time_type_id').val();
                var share_time_length = $('#share_time_length_id').val();
                var village_id = $('#form_village_id').val();
                var ua = window.navigator.userAgent.toLowerCase();
                if(window.__wxjs_environment === 'miniprogram' || ua.match(/MicroMessenger/i) == 'micromessenger'){
                    $.post('{pigcms{:U("Library/house_village_door_share_add")}', {'village_id':village_id, 'share_info':share_info, 'share_time_type':share_time_type, 'share_time_length': share_time_length}, function(response){
                        var url = response['share_url'];
                        console.log(url);
                        time=$("#share_time_length_id").val();
                        window.shareData.tContent='我赠送一个'+$("#share_time_length_id").val()+$('#share_time_type_id option:selected').text()+'临时开门权限';
                        window.shareData.sendFriendLink=url;
                        window.shareData.tTitle='访客开门';
                        console.log(  window.shareData.moduleName);
                        showMenu();
                        shareVillage();





                        wx.ready(function () {
                            wx.showOptionMenu();
                            // 2. 分享接口
                            // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
                            wx.onMenuShareAppMessage({
                                title: window.shareData.tTitle,
                                desc: window.shareData.tContent,
                                link: window.shareData.sendFriendLink ,
                                imgUrl: window.shareData.imgUrl,
                                type: '', // 分享类型,music、video或link，不填默认为link
                                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                                success: function () {

                                    //alert('分享朋友成功');
                                },
                                cancel: function () {
                                    //alert('分享朋友失败');
                                }
                            });


                            // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
                            wx.onMenuShareTimeline({
                                title: window.shareData.tTitle,
                                link: window.shareData.sendFriendLink,
                                imgUrl: window.shareData.imgUrl,
                                success: function () {

                                    //alert('分享朋友圈成功');
                                },
                                cancel: function () {
                                    //alert('分享朋友圈失败');
                                }
                            });

                            // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
                            wx.onMenuShareWeibo({
                                title: window.shareData.tTitle,
                                desc: window.shareData.tContent,
                                link: window.shareData.sendFriendLink ,
                                imgUrl: window.shareData.imgUrl,
                                success: function () {

                                    //alert('分享微博成功');
                                },
                                cancel: function () {
                                    //alert('分享微博失败');
                                }
                            });

                        });
















                    }, 'json')

                }else{
                    layer.open({content:'请在微信中登录！',btn: ['确定'],end:function(){
                            hideMenu()
                        }
                    });

                }
            } else {
                layer.open({
                    content: '请输入开门有效时间', btn: ['确定'], end: function () {
                        hideMenu()
                    }
                });
            }

        } else {

            layer.open({
                content: '请选择开门单元', btn: ['确定'], end: function () {
                    hideMenu()
                }
            });
        }

    })

</script>
</body>
</html>