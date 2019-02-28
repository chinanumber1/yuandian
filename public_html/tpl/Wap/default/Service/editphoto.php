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
<meta name="keywords" content="务,找法律服务"/>
<meta name="description" content="商。到位平台，不再是距离，而是服务品质。"/>
 

<link href='{pigcms{$static_path}service/css/basic.css?t=58da05f1' rel='stylesheet' type='text/css' /><!-- Public js-->
<script src='{pigcms{$static_path}service/js/jquery-2.1.4.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/json2.js?t=58a16a34'></script>
<script src='{pigcms{$static_path}service/js/basic.js?t=58d24290'></script>
<script type="text/javascript" src="{pigcms{$static_public}js/ajaxfileupload.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
<title>修改图片</title>
<link href='{pigcms{$static_path}service/css/demand.css?t=58d8bf20' rel='stylesheet' type='text/css' /></head>
<body>

<!-- <include file="Service:right_nav"/> -->
<div class="pagewrap" id="mainpage">
<!-- <include file="Service:header_top"/> -->

  <div class="clear"></div>
    <div class="show-top-bar-wrap show-top-bar-s2">
         <div class="show-top-bar js_topfixed js_map_topfixed">
            <div class="show-bar">上传服务图片或营业执照，将大幅提高接单率<i class="ico ico-play2"></i></div>
        </div>
    </div>

    <div class="main">
        <div class="service-form" id="demand_form">
            <form action="{pigcms{:U('Service/editphoto')}" id="publish_demand_form" method="post">
                <div class="service-edit-box1 demand-form-list form-list-show">
                    <div class="form-list1">
                        <div class="li js_img_show_wrap">
                            <label class="lab-title"><span class="validate-title">商户图片：</span><span class="lab-blue">（最多上传8张图片）</span></label>
                            <div class="ele-wrap add-imglist-col4">
                                <div class="add-imglist1">
                                    <div id="result">
                                        <volist name="imgList" id="vo">
                                            <div class="cell">
                                                <div class="cell-img ">
                                                    <a href="javascript:;" class="fancybox cell_li" rel="gallery" title="">
                                                        <img src="{pigcms{$vo.img_url}">
                                                    </a>
                                                </div>
                                                <input type="hidden" name="img[]" value="{pigcms{$vo.img_url}" />
                                                <div class="cell-del">
                                                    <a href="javascript:;" class="del-btn"> <i class="ico ico-del"></i>
                                                        <span class="txt-del">删除</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </volist>
                                    </div>

                                    <div class="cell" id="container">
                                        <div class="btn-ftp1" id="upimgFileBtn"> <i  class="ico ico-add"></i></div>
                                        <input type="file" id="imgUploadFile" accept="image/*" onchange="imgUpload()" style="display: none;" name="imgFile" value="选择文件上传"/>
                                    </div>
                                </div>
       
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="li">
                            <div class="info-tips">
                                <i class="ico ico-face-n2"></i>
                                <div class="tips3"> <i></i>上传服务图片或营业执照，提高100%接单率。 </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="pop_action pop_action_s2 js_bottomfixed">
                    <a actioncolse="false" href="javascript:history.back();" class="btn btn-blue btn-s2"><i class="ico ico-goback hidden"></i> 取消 </a>
                    <button type="submit"  id="js_publish_demand_submit" class="btn btn-orange btn-s2"><i class="ico ico-sure"></i>确定</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var length=$("#result .cell").length;

if(length>=8){
    $('#container').remove();
}
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

                    $("#result").append('<div class="cell"> <div class="cell-img " style="height:79.43px;width:79.43px"> <a href="javascript:void(0);" class="fancybox cell_li" rel="gallery" title=""> <img src="'+data.url+'"> </a> </div> <input type="hidden" name="img[]" value="'+data.url+'" /> <div class="cell-del"><a href="javascript:;" class="del-btn"> <i class="ico ico-del"></i> <span class="txt-del">删除</span> </a> </div> </div>');
                     let sum = $("#result .cell").length;
                     if(sum >= 8){
                        // layer.open({
                        //     content: '最多上传8张',
                        //     btn: ['确定']
                        // });
                        $('#container').remove();
                        return false;
                    }
                }else{
                    layer.open({
                        content: data.msg
                        ,btn: ['确定']
                    });
                }
            }
        }); 
    }

    $(document).on('click', '.del-btn', function() {
        var is_cover = $(this).parent().parent().find('.cell_li img').attr('is_cover');
        if (is_cover==1) {
            $('.multiple_cover_key').val('');
        }
        $(this).parent().parent().remove();
        let sum = $("#result .cell").length;
        let svm=$('#container').length;
        if(sum < 8&&svm<=0){
             $('.add-imglist1').append('<div class="cell" id="container"><div class="btn-ftp1" id="upimgFileBtn" style="width: 82.72px; height: 82.72px;"> <i class="ico ico-add"></i></div><input type="file" id="imgUploadFile" onchange="imgUpload()" style="display: none;" name="imgFile" value="选择文件上传"></div>');
        }
    });

</script>

</body>
</html>
