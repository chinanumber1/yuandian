<include file="Public:header"/>
<div class="mainbox">
    <div id="nav" class="mainnav_title"><ul><a href="{pigcms{:U('Sms/mobileApp')}" class="on" >平台APP版本配置</a>
			<a href="{pigcms{:U('Sms/storestaffApp')}" >店员APP版本配置</a>
			<a href="{pigcms{:U('Sms/merchantApp')}" >商家中心APP版本配置</a>
			<a href="{pigcms{:U('Sms/deliverApp')}" >配送员APP版本配置</a>
			<a href="{pigcms{:U('Sms/VillageApp')}">社区APP版本配置</a>
			<a href="{pigcms{:U('Sms/VillageManergeApp')}">社区管理APP版本配置</a>
			</ul></div>
    <form method="post" action="{pigcms{:U('System/Sms/mobileApp')}">
        <table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="" >
            <tbody>
            <tr>
                <th width="160">IOS版本名称：</th>
                <td>
					IOS版本管理：   AppStore规定，必须通过AppStore中进行升级，软件不允许提示升级。
                </td>
            </tr>
			<tr>
				<th width="160"></th>
                <td>
                </td>
			</tr>
            <!---<tr>
                <th width="160">IOS版本号：</th>
                <td><!--validate="required:true"-->
                   <!--- <input type="text" class="input-text" name="ios_version_code" id="ios_version_code" value="{pigcms{$ios_version_code}" size="60" validate="required:true" tips="请填写当前ios版本号">
                </td>
            </tr>
            <tr>
                <th width="160">IOS下载地址：</th>
                <td><!--validate="required:true"-->
                   <!--- <input type="text" class="input-text" name="ios_download_url" id="ios_download_url" value="{pigcms{$ios_download_url}" size="60" validate="required:true" tips="ios下载地址">
                </td>
            </tr>
            <tr>
                <th width="160">IOS版本描述：</th>
                <td><textarea name="ios_version_desc" style="width:480px;height:160px;" id="ios_version_desc">{pigcms{$ios_version_desc}</textarea></td>
            </tr>--->
            <tr>
                <th width="160">Android版本名称：</th>
                <td>
                    <input type="text" class="input-text" name="android_version" id="android_version" value="{pigcms{$android_version}" size="60" validate="required:true" tips="请填写当前Android版本名称"/>
					<span>版本名称，x.x.xx 格式</span>
                </td>
            </tr>
            <tr>
                <th width="160">Android版本号：</th>
                <td>
                    <input type="text" class="input-text" name="android_version_code" id="android_version_code" value="{pigcms{$android_version_code}" size="60" validate="required:true" tips="请填写当前Android版本号"/>
					<span>版本号，数字 格式</span>
                </td>
            </tr>
            <tr>
                <th width="160">Android版本描述：</th>
                <td><textarea name="android_version_desc" style="width:480px;height:160px;" id="android_version_desc">{pigcms{$android_version_desc}</textarea></td>
            </tr>
            <tr>
                <th width="160">Android下载地址：</th>
                <td><!--validate="required:true"-->
                    <input type="text" class="input-text" name="android_download_url" id="android_download_url" value="{pigcms{$android_download_url}" size="60" validate="required:true" tips="Android下载地址">
                </td>
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