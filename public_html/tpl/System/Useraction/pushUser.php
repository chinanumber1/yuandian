<include file="Public:header"/>
<form name="myform" id="myform" action="" method="post">
    <div class="table-list">
        <table width="100%" cellspacing="0">
            <colgroup>
                <col/>
                <col/>
                <col/>
                <col/>
                <col/>
                <col/>
                <col width="240" align="center"/>
            </colgroup>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>手机号</th>
                    <th>昵称</th>
                    <th>省份</th>
                    <th>城市</th>
                </tr>
            </thead>
            <tbody>
                <if condition="is_array($aUser)">
                    <volist name="aUser" id="vo">
                        <tr>
                            <td>{pigcms{$vo.uid}</td>
                            <td>{pigcms{$vo.phone}</td>
                            <td>{pigcms{$vo.nickname}</td>
                            <td>{pigcms{$vo.province}</td>
                            <td>{pigcms{$vo.city}</td>
                        </tr>
                    </volist>
                    <tr><td class="textcenter pagebar" colspan="8">{pigcms{$pagebar}</td></tr>
                <else/>
                    <tr><td class="textcenter red" colspan="8">列表为空！</td></tr>
                </if>
            </tbody>
        </table>
    </div>
</form>
<include file="Public:footer"/>