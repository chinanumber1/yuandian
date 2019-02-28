<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-file-excel-o"></i>
                <a href="{pigcms{:U('Bbs/index')}">社区论坛</a>
            </li>
            <li>分类管理</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        	<button class="btn btn-success" onclick="addCategory()" <if condition="!in_array(129,$house_session['menus'])">disabled="disabled"</if>>新增分类</button>
        	<button class="btn btn-success" style="margin-left:10px;" onclick="modifyIndexImg()" <if condition="!in_array(128,$house_session['menus'])">disabled="disabled"</if>>论坛配置</button>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                	<th width="10%">分类ID</th>
                                	<th width="10%">分类名称</th>
                                    <th width="10%">图标URL</th>
                                    <th width="10%">状态</th>
                                   	<th width="10%">文章总数</th>
                                   	<th width="10%">排序</th>
                                   	<th width="10%">更新时间</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$list">
                                    <volist name="list" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                        	<td><div class="tagDiv">{pigcms{$vo.cat_id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.cat_name}</div></td>
                                            <td><div class="tagDiv"><img src="{pigcms{$vo.cat_logo}" width="45" height="45" /></div></td>
                                            <if condition="$vo.cat_status eq 1">
                                            	<td><div class="tagDiv" style="color:green;">开启</div></td>
                                            <elseif condition="$vo.cat_status eq 2" />
                                            	<td><div class="tagDiv" style="color:red;">关闭</div></td>
                                            <else />
                                            	<td><div class="tagDiv" style="color:Gray;">待用</div></td>
                                            </if>
                                            <td><div class="tagDiv">{pigcms{$vo.cat_aricle_total}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.cat_order}</div></td>
                                            <td><div class="shopNameDiv">{pigcms{$vo.last_time|date='Y-m-d',###}</div></td>
                                            <td class="button-column">
                                                <a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info handle_btn" title="修改分类" href="{pigcms{:U('cat_status',array('cat_id'=>$vo['cat_id']))}">修改分类</a>
                                                <if condition="in_array(131,$house_session['menus'])">
                                                <a style="margin-left:10px;width:80px;height:26px;line-height:20px;" class="label label-sm label-info handle_btn" title="文章列表" href="{pigcms{:U('aricle_list',array('cat_id'=>$vo['cat_id']))}">文章列表</a>
                                                </if>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >暂无数据。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
function addCategory(){
	window.location.href = "{pigcms{:U('Bbs/category_add_show')}";
}
function modifyIndexImg(){
	window.location.href = "{pigcms{:U('Bbs/modify_index_img')}";
}
</script>
<include file="Public:footer"/>
