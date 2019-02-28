<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-empire"></i>
                <a href="{pigcms{:U('Index/active_store_list')}">推荐店铺管理</a>
            </li>
            <li class="active">快店列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        	<button class="btn btn-success" onclick="CreateCategory()" <if condition="!in_array(151,$house_session['menus'])">disabled="disabled"</if>>添加店铺</button>
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="storeList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="10%">店铺编号</th>
                                    <th width="20%">店铺名字</th>
                                    <th width="20%">商家名字</th>
                                    <th width="10%">店铺详情</th>
                                    <th width="10%">排序</th>
                                    <th class="button-column" width="10%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$store_list">
                                    <volist name="store_list" id="vo">
                                        <tr>
                                            <td><div class="tagDiv">{pigcms{$vo.store_id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.store_name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.merchant_name}</div></td>

                                            <td><div class="tagDiv"><a title="看详情" target='_blank' href="<if condition="$vo.url eq ''">{pigcms{$config.site_url}/wap.php?c=Shop&a=classic_shop&shop_id={pigcms{$vo.store_id}<else />{pigcms{$vo.url}</if>">访问</a></div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.sort}</div></td>
                                            <td class="button-column">
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('Index/active_store_edit',array('id'=>$vo['pigcms_id']))}">编辑</a>&nbsp;&nbsp;
                                                <if condition="in_array(153,$house_session['menus'])">
                                                    <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:;" onclick='del(this);' store_id="{pigcms{$vo['store_id']}">删除</a>
                                                </if>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >您没有添加任何店铺信息。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$appoint.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function CreateCategory(){
	window.location.href = "{pigcms{:U('Index/active_store')}";
}
function del(obj){
	if(confirm('您确定要删除？')){
		var store_id = $(obj).attr('store_id');
		if(store_id){
			$.post('{pigcms{:U("Index/active_store_del")}', {store_id:store_id}, function(result){
				if(result.error == 0){
					window.location.reload();
				}else if(result.error == 1){
					alert(result.msg);
					
				}
				return false;
			});
		}
	}
}
</script>
<include file="Public:footer"/>