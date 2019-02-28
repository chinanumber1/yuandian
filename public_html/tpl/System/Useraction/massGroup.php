<include file="Public:header"/>
<div class="mainbox">
    <div id="nav" class="mainnav_title">
        <ul>
            <a href="{pigcms{:U('Useraction/index')}">用户行为管理</a>>><a>群发</a>|
            <a href="{pigcms{:U('Useraction/pushLog')}">群发记录</a>
        </ul>
    </div>
    <form method="post" action="{pigcms{:U('System/Useraction/pushGroup')}">
        <!--推送需要添加的资料-->
        <table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="" >
            <tbody>
            <tr>
                <th width="160">群发方式：</th>
                <td>
                    <select name="receiver_client" id="receiver_client">
                        <option value="1">APP</option>
                        <option value="2">微信</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th width="160">推送标题：</th>
                <td>
                    <input type="text" class="input-text" name="push_title" id="push_title" value="{pigcms{$ios_version}" size="60" validate="required:true" tips="请填写推送标题">
                </td>
            </tr>
            <tr>
                <th width="160">跳转url：</th>
                <td><!--validate="required:true"-->
                    <input type="text" class="input-text" name="push_url" id="push_url" value="{pigcms{$ios_download_url}" size="60" validate="required:true" tips="请填写跳转url">
                </td>
            </tr>
            <tr>
                <th width="160">推送内容：</th>
                <td><textarea name="push_msg" style="width:480px;height:130px;" id="push_msg"></textarea></td>
            </tr>
            <tr>
                <th width="160">分组选择：</th>
                <td>
                <div style="margin:10px 0 10px 0;font-size:14px; color: #3900ff;"><label>全选<input id="checkall" style="width:18px; height:18px; margin-right:10px;" name="checkall" value="1" type="checkbox"></label></div>
                    <div style="width:100%; margin-bottom:10px;">
                    <volist name="aCate" id="vo">
                    <div style="margin-right:20px;float:left; text-align:center; margin-top: 10px;">
                        <label>{pigcms{$vo.action_name} <input style="width:18px; height:18px; margin-right:10px;" name="checkname[]" value="{pigcms{$vo.action_id}" type="checkbox"></label>
                    </div>
                    </volist>
                    <div style="clear:both;"></div>
                    </div>
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
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css"/>
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
<script type="text/javascript">
$('#checkall').click(function(){
    if(this.checked){
        $("input[type='checkbox']").each(function(){this.checked=true;}); 
    }else{ 
        $("input[type='checkbox']").each(function(){this.checked=false;}); 
    } 
});
</script>
<include file="Public:footer"/>