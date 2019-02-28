<include file="Public:header"/>
<style>
    .station{width: 80px; height: 40px; float: left;}
</style>
<form id="myform" method="post" action="{pigcms{:U('Village/update')}" >
    <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">

        <tr>
            <th width="80">文章标题</th>
            <td>
                <input type="text" class="input fl" name="name" value="{pigcms{$info.article_title}" placeholder="文章名称" validate="required:true"></td>
        </tr>
        <tr>
            <th width="80">类别</th>
            <td>
                <input type="text" class="input fl" name="name" value="{pigcms{$info.cat_name}" placeholder="文章类别" validate="required:true"></td>
        </tr>

        <tr>
            <td width="80">发布人</td>
            <td>
                <input type="text" class="input fl" name="name" value="{pigcms{$info.nickname}" placeholder="评论人昵称" validate="required:true"></td>
        </tr>

        <tr>
            <td width="80">文章内容</td>
            <td>
                <input type="text" class="input fl" name="address" value="{pigcms{$info.article_content}" placeholder="" validate="required:true"></td>
        </tr>

        <tr>
            <td width="80">发布时间</td>
            <td>
                <input type="text" class="input fl" name="administrator_tel" value="{pigcms{$info.create_time|date='Y-m-d H:i:s',###}" placeholder="评论时间" validate="required:true"></td>
        </tr>
    </table>
    <div class="btn hidden">
        <input type="hidden" name="id" value="{pigcms{$_GET['id']}" />
        <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
        <input type="reset" value="取消" class="button" />
    </div>
</form>
<include file="Public:footer"/>