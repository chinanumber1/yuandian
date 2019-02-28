var pic_num = 0;

$(function() {
    /**
     * 多文件上传工具
     */
    var Q2 = new QiniuJsSDK();
    var limitNum = 0;
    if ($('#limit_num').length > 0) {
        limitNum = $('#limit_num').val();
    } else {
        limitNum = 6;
    }

    var uploader2 = Q2.uploader({
        runtimes: 'html5,flash,html4',
        browse_button: 'pickfiles',
        container: 'container',
        drop_element: 'container',
        max_file_size: '5mb',
        flash_swf_url: 'js/plupload/Moxie.swf',
        dragdrop: true,
        chunk_size: '4mb',
        uptoken_url: $('#uptoken_url').val(),
        domain: $('#domain').val(),
        auto_start: true,
        unique_names:false,
        save_key:false,
        init: {
            'BeforeUpload': function(up, file) {
              // 每个文件上传前,处理相关的事情
                /*图片上传加载中*/
                var loadingImgFtp=[];
                loadingImgFtp.push('<div class="loading-wrap">');
                loadingImgFtp.push('    <div class="anim-loading">');
                loadingImgFtp.push('        <i></i>');
                loadingImgFtp.push('        <i></i>');
                loadingImgFtp.push('        <i></i>');
                loadingImgFtp.push('    </div>');
                loadingImgFtp.push('    <div class="loading-txt">别着急，正在努力上传中……</div>');
                loadingImgFtp.push('</div>');
                if($('#loadingImgFtp').length==0){
                    popnormal({
                        //"eventEle":".js_popnormal",//点击事件元素（不定义：立即弹出）
                        "popconTpl":loadingImgFtp.join(''),
                        "popId":"loadingImgFtp"
                    });
                }
            },
            FilesAdded: function(up, files) {
                console.log('checkNum 2');
                checkNum(limitNum,up);
            },
            'UploadComplete': function() {
                //队列文件处理完毕后,处理相关的事情
                $('#loadingImgFtp').remove();
            },
            'FileUploaded': function(up, file, info) {
                console.log('checkNum 1');
                if (checkNum(limitNum,up)) {
                    // 每个文件上传成功后,处理相关的事情
                    var domain = up.getOption('domain');
                    var res = jQuery.parseJSON(info);
                    console.log('FileUploaded:' + pic_num);
                    // var sourceLink = domain + res.key; 获取上传成功后的文件的Url
                    //AJAX 获取图片的URL
                    var sourceLink = '';
                    var thumbLink = '';
                    var liElement = '';
                    if (res['key']=='') {
                        return false;
                    }
                    $.ajax({
                        type: "GET",
                        url: '/upload/getUrl?key=' + res['key']+'&size='+$("#multiple_size").val(),
                        dataType:"json",
                        async:false,
                        success:function(result){
                            sourceLink = result['url'];
                            thumbLink = result['thumb_url'];
                            var cellimgH=$('.cell .btn-ftp1').outerHeight();
                            liElement = '<div class="cell">' +
                                '<div class="cell-img " style="height:'+cellimgH+'px;width:'+cellimgH+'px">' +
                                '<a href="'+sourceLink+'" class="fancybox cell_li" rel="gallery" title="">'+
                                ' <img src="'+thumbLink+'" key="'+res['key']+'">' +
                                '</a>' +
                                '</div>';
                            if ($('.multiple_cover_key').length > 0) {
                                //有指定首图
                                liElement = liElement + '<div class="cell-cover">'+
                                    '<a class="select_cover_radio">设为封面</a>'+
                                    '</div>';
                            }
                            liElement = liElement+
                                '<div class="cell-del">' +
                                '<a href="javascript:;" class="del-btn"><i class="ico ico-del"></i>' +
                                '<span class="txt-del">删除</span></a></div></div>';

                        }
                    });
                    $("#result").append(liElement);

                    if(isExitsFunction('imgShow')){
                        imgShow();
                    }
                    //刷新keys input框的内容
                    refreshKeys();
                    //有指定首图 首图文案为封面
                    (function(){
                        if($('#result .cell .cell-cover .select_cover_radio').length>0){
                            $('#result .cell:first .cell-cover .select_cover_radio').text('封面');
                            return false;
                        }   
                    })();
                }

            },
            'Error': function(up, err, errTip) {
                console.log(err.code);
                if (err.code == -600) {
                    poperror({
                        "popconMsg":"所选图片大小超过5M,不能上传！",
                        "popId":"poperror"
                    });
                } else if (err.code== -200) {
                    poperror({
                        "popconMsg":"所选图片文件格式错误,不能上传！",
                        "popId":"poperror"
                    });
                }
            },
            'Key': function(up, file) {
                pic_num = $("#result .cell-img img").length;
                // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                // 该配置必须要在 unique_names: false , save_key: false 时才生效
                // do something with key here
                var id = $('#upload_uid').val();
                console.log('id:' + id)
                var num = id % 100;
                var timestamp = (new Date()).valueOf() + pic_num;
                var key = $('#upload_type').val()+'/'+num +'/'+ md5(timestamp);
                console.log('key : ' + key);
                pic_num = pic_num+1;
                return key
            }
        }

    });
    /**
     * 删除图片按钮
     */
    $(document).on('click', '.del-btn', function() {
        var is_cover = $(this).parent().parent().find('.cell_li img').attr('is_cover');
        if (is_cover==1) {
            $('.multiple_cover_key').val('');
        }
        $(this).parent().parent().remove();
        refreshKeys();
        $('#pickfiles').show();
    });
    /**
     * 选择首图
     */
    $(document).on('click', '.select_cover_radio', function() {
        var select_key = $(this).parent().parent().find('.cell_li img').attr('key');
        $('.cell_li img').attr('is_cover',0);
        $(this).parent().parent().find('.cell_li img').attr('is_cover',1);
        $('.multiple_cover_key').val(select_key);
        var imglist1=$(this).closest('.add-imglist1');
        var imgcell=$(this).closest('.cell');
        $('.select_cover_radio',imglist1).html('设为封面');
        $('.select_cover_radio',imgcell).html('封面');
    });
});

/**
 * 刷新keys隐藏节点的值
 */
var refreshKeys = function() {
    var keys = [];
    $("#result .cell-img img").each(function(){
        var key = $(this).attr('key');
        if (key!='') {
            keys.push(key)
        }
    });
    pic_num = $("#result .cell-img img").length;
    console.log($("#result .cell-img img").length);
    $(".multiple_field_name").val(keys.join(','));

};

var checkNum = function(limitNum,up){
    pic_num = $("#result .cell-img img").length;
    var maxfiles = limitNum;
    console.log('pic:max = '+ pic_num + ':' + maxfiles);
    if( maxfiles > 0 && (pic_num >= maxfiles)){
        up.splice(maxfiles);
        //小提示层;
        validatePop({
            "popconMsg":'上传不能超过'+maxfiles+'个文件'
        });
        $('#uploader_browse').hide("slow");
        $('#pickfiles').hide();
        return false;
    } else {
        if (pic_num == maxfiles){
            $('#pickfiles').hide();
        }
        return true;
    }
}

