<include file="Public:header"/>
        <div class="mainbox">
            <div id="nav" class="mainnav_title">
                <ul>
                    <a href="{pigcms{:U('Useraction/index')}">用户行为管理</a>>><a href="{pigcms{:U('Useraction/massGroup')}">群发</a>>><a>群发记录</a>
                </ul>
            </div>
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
                                <th>群发方法</th>
                                <th>标题</th>
                                <th>群发的用户</th>
                                <th>群发的分组</th>
                                <th>状态</th>
                                <th>推送时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            <if condition="is_array($aPushLog)">
                                <volist name="aPushLog" id="vo">
                                    <tr>
                                        <td>{pigcms{$vo.log_push_id}</td>
                                        <td>{pigcms{$vo.receiver_client}</td>
                                        <td><a href="{pigcms{$vo.push_url}" target="view_window"><span style="color:#3900ff;">{pigcms{$vo.push_title}</span></a></td>
                                        <td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Useraction/pushUser',array('log_push_id'=>$vo['log_push_id']))}','查看用户',800,600,true,false,false,addbtn,'add',true);">查看用户</a></td>
                                        <td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Useraction/pushAction',array('log_push_id'=>$vo['log_push_id']))}','查看分组',450,320,true,false,false,addbtn,'add',true);">查看分组</a></td>
                                        <if condition="$vo['push_status'] eq 1">
                                            <td><span style="color:green;">{pigcms{$vo.push_statu}</span></td>
                                        <else/>
                                            <td><span style="color:red;">{pigcms{$vo.push_statu}</span></td>
                                        </if>
                                        <td>{pigcms{$vo.push_time}</td>
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
        </div>
<include file="Public:footer"/>