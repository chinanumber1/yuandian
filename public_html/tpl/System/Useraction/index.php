<include file="Public:header"/>
        <div class="mainbox">
            <div id="nav" class="mainnav_title">
                <ul>
                    <a href="{pigcms{:U('Useraction/index')}" class="on">用户行为管理</a>|
                    <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Useraction/add',array('type'=>1))}','添加用户行为分组',450,320,true,false,false,addbtn,'add',true);">添加用户行为分组</a>|
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
                                <th>排序</th>
                                <th>No.</th>
                                <th>分组名</th>
                                <th>进入分组明细</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <if condition="is_array($action_category)">
                                <volist name="action_category" id="vo">
                                    <tr>
                                        <td>{pigcms{$vo.action_sort}</td>
                                        <td>{pigcms{$vo.action_id}</td>
                                        <td>{pigcms{$vo.action_name}</td>
                                        <td><a href="{pigcms{:U('Useraction/relation',array('action_id'=>$vo['action_id'],'action_name'=>$vo['action_name']))}">进入明细</a></td>
                                        <td>
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Useraction/edit',array('type'=>1,'action_id'=>$vo['action_id']))}','编辑行为分组',450,320,true,false,false,editbtn,'add',true);">编辑</a> | 
                                            <a href="javascript:void(0);" class="delete_row" parameter="action_id={pigcms{$vo.action_id}" url="{pigcms{:U('Useraction/del',array('status'=>1))}">删除</a>
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