$(function()
{
    var fileNum=1;
    var fileSize=2;
    /*上传背景图片*/
    var uploader=WebUploader.create({
        server:_uploadUrl,
        auto:true,
        //runtimeOrder:'html5',
        pick:{id:'#uploader_pick',innerHTML:'',multiple:false},
        accept:{mimeTypes:'image/*',extensions:'png,jpg,jpeg,gif',title:'Image'},
        chunked:true,
        compress:false,//安卓版微信内置浏览器不支持压缩
        // fileNumLimit:fileNum,//不限制数量
        fileSizeLimit:fileNum*(fileSize*1024*1024),
        fileSingleSizeLimit:fileSize*1024*1024,
        formData:{}
    });

    var errorInfo=
    {
        'Q_EXCEED_NUM_LIMIT':'文件数量不能超出'+fileNum+'个',
        'F_EXCEED_NUM':'文件数量不能超出'+fileNum+'个',
        'F_EXCEED_SIZE':'文件大小不能超出'+fileSize+'MB',
        'Q_EXCEED_SIZE_LIMIT':'文件大小不能超出'+fileSize+'MB',
        'Q_TYPE_DENIED':'文件类型不合法',
        'F_TYPE_DENIED':'文件类型不合法',
        'F_DUPLICATE':'图片重复'
    };

    //文件加入队列
    uploader.onFileQueued = function(file)
    {
        uploader.makeThumb( file, function( error, ret ) {
            if ( error) {
                $('.imgLoding_bg').html('无法预览');
            } else {
                $('.imgLoding_bg').html('<img alt="" src="' + ret + '" />');
            }
        });
    }

    //加入队列错误
    uploader.onError=function(err)
    {
        layer.open({
            style: 'border:none; background-color:rgba(0,0,0,0.6); color:#fff;',
            content:errorInfo[err]?errorInfo[err]:'选择错误'+err,
            time:2
        })
    }

    //开始上传
    uploader.onUploadStart=function(file)
    {
        createSpin($('.bgimg_view').get(0));
    }

    //上传成功
    uploader.onUploadSuccess=function(file,response)
    {
        $('.bgimg_view').find('.spinner').remove();
        if(!response||response['status']!='1')
        {
            layer.open({
                style: 'border:none; background-color:rgba(0,0,0,0.6); color:#fff;',
                content:file.name+':'+(response['info']?response['info']:'未知错误'),
                time:2
            })
            file.setStatus('error',(response['info']?response['info']:'未知错误'));
        }
        else
        {
            $('.bgimg_view').attr('file_path',response['data']);
            $('.bgimg_view').trigger('upload_complete',response['data']);
        }
    }

    uploader.uploadError=function(file,reason)
    {
        layer.open({
            style: 'border:none; background-color:rgba(0,0,0,0.6); color:#fff;',
            content:'图片上传错误('+reason+')',
            time:2
        })
    }

    //创建加载动画
    function createSpin(element)
    {
        var opts = {
            lines: 13, // The number of lines to draw
            length: 7, // The length of each line
            width: 4, // The line thickness
            radius: 10, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 0, // The rotation offset
            color: '#fff', // #rgb or #rrggbb
            speed: 1, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: 'auto', // Top position relative to parent in px
            left: 'auto' // Left position relative to parent in px
        };
        var spinner = new Spinner(opts).spin(element);
    }
})