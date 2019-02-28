<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-tablet"></i>
                <a href="{pigcms{:U('News/cate')}">新闻分类管理</a>
            </li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        	<button class="btn btn-success" onclick="CreateCategory()" <if condition="!in_array(156,$house_session['menus'])">disabled="disabled"</if>>新增新闻分类</button>
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="70%">分类名称</th>
                                    <th width="10%">排序</th>
                                    <th width="10%">分类状态</th>
                                    <th class="button-column" width="10%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$news_category">
                                    <volist name="news_category" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.cat_name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.cat_sort}</div></td>
                                            <td><div class="shopNameDiv"><if condition="$vo.cat_status eq '1' ">正常<else />关闭</if></div></td>
                                            <td class="button-column">
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('News/cate_edit',array('cat_id'=>$vo['cat_id']))}">编辑</a>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="4" >您没有添加任何新闻分类。</td></tr>
                                </if>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
function CreateCategory(){
	window.location.href = "{pigcms{:U('News/cate_edit')}";
}
</script>
<include file="Public:footer"/>
