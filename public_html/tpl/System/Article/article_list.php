<include file="Public:header"/>
<div class="mainbox">
    <div id="nav" class="mainnav_title">
        <ul>
            <a href="{pigcms{:U('Article/index')}" >分类列表</a>|
            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Article/create')}','添加分类',480,260,true,false,false,addbtn,'add',true);">添加分类</a>|
            <a href="{pigcms{:U('Article/article_list')}" class="on">文章列表</a>|
            <a href="{pigcms{:U('Article/comment_list')}">评论列表</a>
        </ul>
    </div>
    <table class="search_table" width="100%">
        <tr>
            <td>
                <form action="{pigcms{:U('Article/article_list')}" method="get">
                    <input type="hidden" name="c" value="Article"/>
                    <input type="hidden" name="a" value="article_list"/>
                    筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
                    <select name="type">
                        <option value="article_title" <if condition="$_GET['type'] eq 'article_title'">selected="selected"</if>>文章标题</option>
                        <option value="nickname" <if condition="$_GET['type'] eq 'nickname'">selected="selected"</if>>用户昵称</option>
                    </select>
                    <input type="submit" value="查询" class="button"/>
                </form>
            </td>
        </tr>
    </table>
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
                    <th>ID</th>
                    <th>文章标题</th>
                    <th>文章图片</th>
                    <th>发布人信息</th>
                    <th>类别</th>
                    <th>赞数量</th>
                    <th>评论数</th>
                    <th>更新时间</th>
                    <th class="textcenter">操作</th>
                </tr>
                </thead>
                <tbody>
                <if condition="is_array($articleList)">
                    <volist name="articleList" id="vo">
                        <tr>
                            <td>{pigcms{$vo.id}</td>
                            <td>{pigcms{$vo.article_title}</td>
                            <td><img src="{pigcms{$vo.article_img}"></td>
                            <td>
                                {pigcms{$vo.nickname}<br/>
                                (ID: {pigcms{$vo.uid})
                            </td>
                            <td>{pigcms{$vo.category_name}</td>
                            <td>{pigcms{$vo.article_praise_num}</td>
                            <td>{pigcms{$vo.article_comment_total}</td>
                            <td>{pigcms{$vo.update_time|date='Y-m-d',###}</td>
                            <!--<td>

                                <span class="cb-enable">
                                <label class="cb-enable <if condition="$vo['is_display'] eq 1"> selected </if>">
                                <span class="js-enable" data-id="{pigcms{$vo.id}">可见</span>
                                <input type="radio" name="is_display" value="1"  <if condition="$vo['is_display'] eq 1"> checked </if> ></label>
                                </span>

                                <span class="cb-disable">
                                <label class="cb-disable <if condition="$vo['status'] eq 0"> selected </if>">
                                <span class="js-disable" data-id="{pigcms{$vo.id}">不可见</span>
                                <input type="radio" name="status" value="0" <if condition="$vo['status'] eq 0"> checked </if>></label>
                                </span>
                            </td>-->
                            <td style="text-align: center;">
                                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Article/aricle_list_details',array('id'=>$vo['id'],'frame_show'=>true))}','查看',480,260,true,false,false,false,'detail',true);">查看</a> |
                                <a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info" title="查看评论" href="{pigcms{:U('comment_list',array('article_id'=>$vo['id']))}">查看评论</a>|
                                <a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onclick="if(confirm('确认删除该文章及相关评论？')){location.href='{pigcms{:U('article_del',array('id'=>$vo['id']))}}'}">删除</a>
                            </td>
                        </tr>
                    </volist>
                    <tr><td class="textcenter pagebar" colspan="9">{pigcms{$page}</td></tr>
                    <else/>
                    <tr><td class="textcenter red" colspan="9">列表为空！</td></tr>
                </if>
                </tbody>
            </table>
        </div>
    </form>
</div>

<!--<script>
    $(function(){
        $('.js-enable').click(function(){ //启用
            var id = $(this).data('id');
            $.post("{pigcms{:U('Bbs/bbs_disable')}",{'id':id,'status':1},function(){

            })
        });

        $('.js-disable').click(function(){ //禁用
            var id = $(this).data('id');
            $.post("{pigcms{:U('Bbs/bbs_disable')}",{'id':id,'status':0},function(){

            })
        });
    });

</script>-->
<include file="Public:footer"/>