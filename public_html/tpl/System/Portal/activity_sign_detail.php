<include file="Public:header"/>
<script src="{pigcms{$static_public}js/layer/layer.js"></script>
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
    <tr>
        <th colspan="1">序号</th>
        <th colspan="6">{pigcms{$peopleInfo.sid}</th>
    </tr>
    <tr>
        <th colspan="1">姓名</th>
        <th colspan="6">{pigcms{$peopleInfo.truename}</th>
    </tr>
    <tr>
        <th colspan="1">UID</th>
        <th colspan="6">{pigcms{$peopleInfo.uid}</th>
    </tr>
    <tr>
        <th colspan="1">电话</th>
        <th colspan="6">{pigcms{$peopleInfo.phone}</th>
    </tr>
    <tr>
        <th colspan="1">QQ</th>
        <th colspan="6">{pigcms{$peopleInfo.qq}</th>
    </tr>
    <tr>
        <th colspan="1">报名费用</th>
        <th colspan="6">{pigcms{$money}</th>
    </tr>
    <tr>
        <th colspan="1">备注</th>
        <th colspan="6">{pigcms{$peopleInfo.message}</th>
    </tr>
    <tr>
        <th colspan="1">报名时间</th>
        <th colspan="6">{pigcms{$peopleInfo.create_time|date="Y-m-d H:i:s",###}</th>
    </tr>




</table>

<include file="Public:footer"/>