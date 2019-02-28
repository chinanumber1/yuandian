<include file="Public:header"/>
        <div class="mainbox">
            <div id="nav" class="mainnav_title">
                <ul>
                    <a href="{pigcms{:U('Useraction/index')}">用户行为管理</a>>><a href="{pigcms{:U('Useraction/relation',array('action_id'=>$_GET['action_id'],'action_name'=>$_GET['action_name']))}">分类和分组关系</a>>><a>查看用户</a>|
                    <a href="{pigcms{:U('Useraction/massGroup')}">创建群发</a>
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
                                <th>关系ID</th>
                                <th>分组ID</th>
                                <th>用户ID</th>
                                <th>购买数量</th>
                                <th>查看用户</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <if condition="is_array($action_relation)">
                                <volist name="action_relation" id="vo">
                                    <tr>
                                        <td>{pigcms{$vo.log_id}</td>
                                        <td>{pigcms{$vo.rela_id}</td>
                                        <td>{pigcms{$action_name}</td>
                                        <td>{pigcms{$vo.uid}</td>
                                        <td>{pigcms{$vo.log_number}</td>
                                        <td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Useraction/userDetailsLog',array('uid'=>$vo['uid']))}','查看用户详情',450,320,true,false,false,addbtn,'add',true);">查看用户详情</a></td>
                                        <td>
                                            <a href="javascript:void(0);" class="delete_row" parameter="log_id={pigcms{$vo.log_id}" url="{pigcms{:U('Useraction/del',array('status'=>3))}">删除</a>
                                        </td>
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