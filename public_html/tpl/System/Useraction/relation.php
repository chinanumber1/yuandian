<include file="Public:header"/>
        <div class="mainbox">
            <div id="nav" class="mainnav_title">
                <ul>
                    <a href="{pigcms{:U('Useraction/index')}">用户行为管理</a>>><a>分类和分组关系</a>|
                    <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Useraction/add',array('action_id'=>$_GET['action_id'],'type'=>2))}','添加分组和分类关系',450,320,true,false,false,addbtn,'add',true);">添加分组和分类关系</a>|
                    <a href="{pigcms{:U('Useraction/userLog',array('status'=>2,'action_id'=>$_GET['action_id'],'action_name'=>$action_name))}">查看分组所有用户</a>|
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
                                <th>分组ID</th>
                                <th>分类名</th>
                                <th>分类ID</th>
                                <th>进入下级分类</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <if condition="is_array($action_relation)">
                                <volist name="action_relation" id="vo">
                                    <tr>
                                        <td>{pigcms{$vo.rela_id}</td>
                                        <td>{pigcms{$action_name}</td>
                                        <td>{pigcms{$vo.cat_type}</td>
                                        <td>{pigcms{$vo.cat_id}</td>
                                        <td><a href="{pigcms{:U('Useraction/userLog',array('status'=>1,'rela_id'=>$vo['rela_id'],'action_name'=>$action_name,'action_id'=>$_GET['action_id']))}">查看分组用户</a></td>
                                        <td>
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Useraction/edit',array('type'=>2,'rela_id'=>$vo['rela_id']))}','编辑行为分类',450,320,true,false,false,editbtn,'add',true);">编辑</a> | 
                                            <a href="javascript:void(0);" class="delete_row" parameter="rela_id={pigcms{$vo.rela_id}" url="{pigcms{:U('Useraction/del',array('status'=>2))}">删除</a>
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