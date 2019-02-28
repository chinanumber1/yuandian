<include file="Public:header"/>
<form id="myform" frame="true" refresh="true">
    <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
    <if condition="is_array($aUser)">
        <volist name="aUser" id="vo">
            <tr>
                <th width="80">分组ID</th>
                <td>{pigcms{$vo.action_id}</td>
                <th width="80">分组名</th>
                <td>{pigcms{$vo.action_name}</td>
            </tr>
        </volist>
    <else/>
        <tr><td class="textcenter red" colspan="8">分组为空！</td></tr>
    </if>
    </table>
</form>
<include file="Public:footer"/>