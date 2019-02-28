<include file="Public:header"/>
<form id="myform" frame="true" refresh="true">
    <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
        <tr>
            <th width="80">用户ID</th>
            <td>{pigcms{$aUser.uid}</td>
            <th width="80">用户名称</th>
            <td>{pigcms{$aUser.nickname}</td>
        </tr>
        <tr>
            <th width="80">手机号</th>
            <td>{pigcms{$aUser.phone}</td>
            <th width="80">注册时间</th>
            <td>{pigcms{$aUser.add_time}</td>
        </tr>
        <tr>
            <th width="80">省份</th>
            <td>{pigcms{$aUser.province}</td>
            <th width="80">城市</th>
            <td>{pigcms{$aUser.city}</td>
        </tr>
    </table>
</form>
<!--<include file="Public:footer"/>-->