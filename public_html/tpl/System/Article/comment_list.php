<include file="Public:header"/>
<div class="mainbox">
    <div id="nav" class="mainnav_title">
        <ul>
            <a href="{pigcms{:U('Article/index')}" >分类列表</a>|
            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Article/create')}','添加分类',480,260,true,false,false,addbtn,'add',true);">添加分类</a>|
            <a href="{pigcms{:U('Article/article_list')}" >文章列表</a>|
            <a href="{pigcms{:U('Article/comment_list')}" class="on">评论列表</a>
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
                    <col width="180" align="center"/>
                </colgroup>
                <thead>
                <tr>
                    <th>文章信息</th>
                    <th>评论人信息</th>
                    <th>评论内容</th>
                    <th>评论时间</th>
                    <th class="textcenter">操作</th>
                </tr>
                </thead>
                <tbody>
                <if condition="is_array($commentList)">
                    <volist name="commentList" id="vo">
                        <tr>
                            <td>
                                {pigcms{$vo.article_title}<br/>
                                (ID:{pigcms{$vo.article_id})
                            </td>
                            <td>
                               <!-- <img src="{pigcms{$vo.avatar}" style="width:75px;height:75px;"><br/>-->
                                {pigcms{$vo.nickname}<br/>
                                (ID: {pigcms{$vo.uid})
                            </td>
                            <td><?php echo  msubstr($vo['comment_content'],0,10);?></td>
                            <td>{pigcms{$vo.create_time|date='Y-m-d',###}</td>
                            <td style="text-align: center;">
                                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Article/comment_edit',array('id'=>$vo['id'],'frame_show'=>true))}','查看',480,260,true,false,false,false,'detail',true);">查看</a> |
                                <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('comment_delete')}">删除评论</a>
                            </td>
                        </tr>
                    </volist>
                    <tr><td class="textcenter pagebar" colspan="5">{pigcms{$page}</td></tr>
                    <else/>
                    <tr><td class="textcenter red" colspan="5">列表为空！</td></tr>
                </if>
                </tbody>
            </table>
        </div>
    </form>
</div>
<include file="Public:footer"/>