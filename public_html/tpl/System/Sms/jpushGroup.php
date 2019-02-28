<include file="Public:header"/>
<div class="mainbox">
    <div id="nav" class="mainnav_title"><ul><a class="on">群发</a></ul></div>
    <form method="post" action="{pigcms{:U('System/Sms/jpushGroup')}">
        <table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="" >
            <tbody>
            <tr>
                <th width="160">推送设备：</th>
                <td>
                    <select name="jpush_client" id="jpush_client">
                        <option value="3">全部设备</option>
                        <option value="1">苹果设备</option>
                        <option value="2">安卓设备</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th width="160">推送标题：</th>
                <td>
                    <input type="text" class="input-text" name="jpush_title" id="jpush_title" value="{pigcms{$ios_version}" size="60" validate="required:true" tips="请填写推送标题">
                </td>
            </tr>
            <tr>
                <th width="160">跳转url：</th>
                <td><!--validate="required:true"-->
                    <input type="text" class="input-text" name="jpush_url" id="jpush_url" value="{pigcms{$ios_download_url}" size="60" validate="required:true" tips="请填写跳转url">
                </td>
            </tr>
            <tr>
                <th width="160">推送内容：</th>
                <td><textarea name="jpush_msg" style="width:480px;height:130px;" id="jpush_msg"></textarea></td>
            </tr>
            <tr>
                <th width="160">device_id：</th>
                <td><textarea name="device_id" style="width:480px;height:130px;" id="device_id"></textarea><br>不填写代表群发。填写多个设备，用回车分开。<br>比如：<br>EFE90AEE-D13D-4AE8-8501-758D2F65BAE5<br>52932EB2-201B-44A6-A698-0F5E546CAE50</td>
            </tr>
            </tbody>
        </table>
        <div class="btn" style="margin-top:20px;">
            <input type="submit" value="提交" class="button" />
            <input type="reset" value="取消" class="button" />
        </div>
    </form>
</div>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<style>
    .table_form{border:1px solid #ddd;}
    .tab_ul{margin-top:10px;border-color:#C5D0DC;margin-bottom:0!important;margin-left:0;position:relative;top:1px;border-bottom:1px solid #ddd;padding-left:0;list-style:none;}
    .tab_ul>li{position:relative;display:block;float:left;margin-bottom:-1px;}
    .tab_ul>li>a {
        position: relative;
        display: block;
        padding: 10px 15px;
        margin-right: 2px;
        line-height: 1.42857143;
        border: 1px solid transparent;
        border-radius: 4px 4px 0 0;
        padding: 7px 12px 8px;
        min-width: 100px;
        text-align: center;
    }
    .tab_ul>li>a, .tab_ul>li>a:focus {
        border-radius: 0!important;
        border-color: #c5d0dc;
        background-color: #F9F9F9;
        color: #999;
        margin-right: -1px;
        line-height: 18px;
        position: relative;
    }
    .tab_ul>li>a:focus, .tab_ul>li>a:hover {
        text-decoration: none;
        background-color: #eee;
    }
    .tab_ul>li>a:hover {
        border-color: #eee #eee #ddd;
    }
    .tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
        color: #555;
        background-color: #fff;
        border: 1px solid #ddd;
        border-bottom-color: transparent;
        cursor: default;
    }
    .tab_ul>li>a:hover {
        background-color: #FFF;
        color: #4c8fbd;
        border-color: #c5d0dc;
    }
    .tab_ul>li:first-child>a {
        margin-left: 0;
    }
    .tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
        color: #576373;
        border-color: #c5d0dc #c5d0dc transparent;
        border-top: 2px solid #4c8fbd;
        background-color: #FFF;
        z-index: 1;
        line-height: 18px;
        margin-top: -1px;
        box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
    }
    .tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
        color: #555;
        background-color: #fff;
        border: 1px solid #ddd;
        border-bottom-color: transparent;
        cursor: default;
    }
    .tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
        color: #576373;
        border-color: #c5d0dc #c5d0dc transparent;
        border-top: 2px solid #4c8fbd;
        background-color: #FFF;
        z-index: 1;
        line-height: 18px;
        margin-top: -1px;
        box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
    }
    .tab_ul:before,.tab_ul:after{
        content: " ";
        display: table;
    }
    .tab_ul:after{
        clear: both;
    }
</style>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script type="text/javascript">
    KindEditor.ready(function(KE){
         kind_editor = KE.create("#about",{
            width:'402px',
            height:'300px',
            resizeType : 1,
            allowPreviewEmoticons:false,
            allowImageUpload : true,
            filterMode: true,
            items : [
                'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                'insertunorderedlist', '|', 'emoticons', 'image', 'link'
            ],
            emoticonsPath : './static/emoticons/',
            uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=merchant/news"
        });

       var kind_editor2 = KE.create("#rules",{
            width:'402px',
            height:'300px',
            resizeType : 1,
            allowPreviewEmoticons:false,
            allowImageUpload : true,
            filterMode: true,
            items : [
                'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                'insertunorderedlist', '|', 'emoticons', 'image', 'link'
            ],
            emoticonsPath : './static/emoticons/',
            uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=merchant/news"
        });
    });
</script>
<include file="Public:footer"/>