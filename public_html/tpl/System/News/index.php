<include file="Public:header"/>
<div class="mainbox">
    <div id="nav" class="mainnav_title">
        <ul>
            <a href="{pigcms{:U('News/index')}" class="on">资讯列表</a>|
            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('News/news_add')}','添加资讯',850,700,true,false,false,addbtn,'store_add',true);">添加资讯</a>
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
                    <col width="180" align="center"/>
                </colgroup>
                <thead>
                <tr>
                    <th>编号</th>
                    <th>一级分类</th>
                    <th>二级分类</th>
                    <th>三级分类</th>
                    <th>标题</th>
                    <th>添加时间</th>
                    <th>是否可见</th>
                    <th class="textcenter">操作</th>
                </tr>
                </thead>
                <tbody>
                <if condition="is_array($newsList)">
                    <volist name="newsList" id="vo">
                        <tr>
                            <td>{pigcms{$vo.news_id}</td>
                            <td>{pigcms{$vo.onename}</td>
                            <td>{pigcms{$vo.twoname}</td>
                            <td>{pigcms{$vo.threename}</td>
                            <td><?php echo  msubstr($vo['title'],0,10);?></td>
                            <td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
                            <td>
                                <if condition="$vo['is_display'] eq 0"><font color="red">不可见</font><elseif condition="$vo['is_display'] eq 1"/><font color="green">可见</font></if>
                            </td>

                            <td class="textcenter">
                                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('News/news_save',array('news_id'=>$vo['news_id']))}','编辑资讯',850,700,true,false,false,editbtn,'edit',true);">编辑</a>|
                                <a href="javascript:void(0);" class="delete_row" parameter="news_id={pigcms{$vo.news_id}" url="{pigcms{:U('News/news_del')}">删除</a>
                            </td>
                        </tr>
                    </volist>
                    <tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
                    <else/>
                    <tr><td class="textcenter red" colspan="9">列表为空！</td></tr>
                </if>
                </tbody>
            </table>
        </div>
    </form>
</div>
<include file="Public:footer"/>