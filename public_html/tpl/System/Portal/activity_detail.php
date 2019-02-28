<include file="Public:header"/>
<script src="{pigcms{$static_public}js/layer/layer.js"></script>
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
    <tr>
        <th colspan="1">活动名称</th>
        <th colspan="6">{pigcms{$activityInfo.title}</th>
    </tr>
    <tr>
        <th colspan="1">活动现图</th>
        <td colspan="6"><img src="{pigcms{$config.site_url}/upload/portal/{pigcms{$activityInfo.pic}" style="width:260px;height:80px;" class="view_msg"/></td>
    </tr>
    <tr>
        <th colspan="1">活动时间</th>
        <th colspan="6">{pigcms{$activityInfo.time}</th>
    </tr>
    <tr>
        <th colspan="1">活动地址</th>
        <th colspan="6">{pigcms{$activityInfo.place}</th>
    </tr>
    <tr>
        <th colspan="1">活动价格</th>
        <th colspan="6">{pigcms{$activityInfo.price}</th>
    </tr>
    <tr>
        <th colspan="1">活动人数</th>
        <th colspan="6">{pigcms{$activityInfo.number}</th>
    </tr>
    <tr>
        <th colspan="1">报名截止时间</th>
        <th colspan="6">{pigcms{$activityInfo.enroll_time|date="Y-m-d H:i:s",###}</th>
    </tr>
    <tr>
        <th colspan="1">所属分类</th>
        <th colspan="6">{pigcms{$catInfo}</th>
    </tr>
    <tr>
        <th colspan="1">负责人</th>
        <th colspan="6">{pigcms{$activityInfo.leader}</th>
    </tr>
    <tr>
        <th width="80">活动状态</th>
        <td>
             <span><if condition="$activityInfo['status'] eq 1">启用</if></span>
             <span><if condition="$activityInfo['status'] eq 0">关闭</if></span>
        </td>
    </tr>


</table>

<include file="Public:footer"/>