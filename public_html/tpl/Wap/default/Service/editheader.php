<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="yes" name="apple-touch-fullscreen" />
<meta content="telephone=no" name="format-detection" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0,user-scalable=no"/>
<meta name="baidu-site-verification" content="Rp99zZhcYy" />
<meta name="keywords" content=""/>
<meta name="description" content=""/>
<link href='{pigcms{$static_path}service/css/basic.css?t=58da05f1' rel='stylesheet' type='text/css' /><!-- Public js-->
<script src='{pigcms{$static_path}service/js/jquery-2.1.4.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/json2.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/basic.js?t=58d24290'></script>
<script src='{pigcms{$static_path}service/js/jquery.validate.min.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/md5.min.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/newcode-src.js?t=58a16a34'></script>
<script type="text/javascript" src="{pigcms{$static_public}js/ajaxfileupload.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
<title>修改服务商名称</title>
<link href='{pigcms{$static_path}service/css/quote.css?t=58d24290' rel='stylesheet' type='text/css' />
<link href='{pigcms{$static_path}service/css/demand.css?t=58d8bf20' rel='stylesheet' type='text/css' />    <!-- private css file -->
</head>
<body>


<!-- <include file="Service:right_nav"/> -->
<div class="pagewrap" id="mainpage">
    <!-- <include file="Service:header_top"/> -->

    <div class="clear"></div>
        <div class="show-top-bar-wrap show-top-bar-s2">
             <div class="show-top-bar js_topfixed js_map_topfixed">
                <div class="show-bar">完善服务商资料可提高用户下单率</div>
            </div>
        </div>
    <div class="main">
        <div class="service-form js_form_edit_toggle" id="demand_form">
            <form action="{pigcms{:U('Service/editheader')}" id="publish_demand_form" method="post">
                <input type="hidden" name="pid" value="{pigcms{$providerInfo.pid}">
                <div class="service-edit-box1">
                    <div class="topbar2-wrap banner-4">
                        <div class="info-basic-wrap">
                            <div class="user_head_box">
                                <div class="user_head"  mobileftp="true">
                                    <span class="single_img_box">
                                        <img id="avatar_src" class="single_pickfiles" src="{pigcms{$providerInfo.avatar}">
                                        <input type="hidden" id="avatar" name="avatar" value="{pigcms{$providerInfo.avatar}">
                                    </span>
                                </div>
                            </div>
                            <div class="info-basic">
                                <p class="ftp-head-wrap" id="upimgFileBtn"><a href="javascript:;" class="single_pickfiles btn btn-s2 btn-blue1 btn-ftp-head" style="font-size:0.86rem;">上传服务商头像</a></p>
                            </div>
                            <input type="file" id="imgUploadFile" accept="image/*" onchange="imgUpload()" style="display: none;" name="imgFile" value="选择文件上传"/>
                        </div>
                    </div>
                    <div class="form-list3 form-list5">
                        <div class="li label-inline1 li-frist">
                            <label class="label-1">服务商名称：</label>
                            <div class="edit">
                                <input class="form-control js_validate" type="text" name="name" value="{pigcms{$providerInfo.name}"maxlength="15"/>
                            </div>
                        </div>
                        <div class="li label-inline1 li-frist">
                            <label class="label-1">服务商电话：</label>
                            <div class="edit">
                                <input class="form-control js_validate" type="text" name="phone" value="{pigcms{$providerInfo.phone}"maxlength="15"/>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="pop_action pop_action_s2 js_bottomfixed">
                    <a actioncolse="false" href="javascript:history.back();" class="btn btn-blue btn-s2">
                        <i class="ico ico-goback hidden"></i>返回
                    </a>
                    <button type="submit" class="btn btn-orange btn-s2"><i class="ico ico-sure"></i>确定</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("#upimgFileBtn").click(function(){
        $("#imgUploadFile").click();
    })

    function imgUpload(){
        $.ajaxFileUpload({
            url:"{pigcms{:U('Service/ajax_upload_file')}",
            secureuri:false,
            fileElementId:'imgUploadFile',
            dataType: 'json',
            success: function (data) {
                if(data.error == 2){
                    $("#avatar_src").attr("src",data.url);  
                    $("#avatar").val(data.url);
                }else{
                    layer.open({
                        content: data.msg
                        ,btn: ['确定']
                    });
                }
            }
        }); 
    }
</script>
</body>
</html>