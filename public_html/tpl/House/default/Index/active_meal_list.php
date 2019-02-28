<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-empire"></i>
                <a href="{pigcms{:U('Index/active_meal_list')}">推荐{pigcms{$config.meal_alias_name}管理</a>
            </li>
            <li class="active">{pigcms{$config.meal_alias_name}列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        	<button class="btn btn-success" onclick="CreateCategory()" <if condition="!in_array(144,$house_session['menus'])">disabled="disabled"</if>>新增{pigcms{$config.meal_alias_name}</button>
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="55%">{pigcms{$config.meal_alias_name}名字</th>
                                    <th width="20%">商家名字</th>
                                    <th width="10%">{pigcms{$config.meal_alias_name}详情</th>
                                    <th width="10%">排序</th>
                                    <th class="button-column" width="5%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$meal">
                                    <volist name="meal['store_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.store_name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.merchant_name}</div></td>
                                            <td><div class="tagDiv"><a title="看详情" target='_blank' href="<if condition="$vo.url eq ''">{pigcms{$config.site_url}/meal/{pigcms{$vo['store_id']}.html<else />{pigcms{$vo.url}</if>">访问</a></div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.sort}</div></td>
                                            <td class="button-column">
                                            <if condition="in_array(145,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:;" onclick='del(this);' storeid="{pigcms{$vo['store_id']}">删除</a>
                                            <else/>
                                                无
                                            </if>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="5" >您没有添加任何{pigcms{$config.meal_alias_name}信息。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$meal.pagebar}
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
	window.location.href = "{pigcms{:U('Index/active_meal')}";
}
function del(obj){
	var storeid = $(obj).attr('storeid');
	if(storeid){
		$.post('{pigcms{:U("Index/active_meal_del")}', {store_id:storeid}, function(result){
			if(result.error == 0){
				window.location.reload();
			}else if(result.error == 1){
				alert(result.msg);
				
			}
			return false;
		});
	}
}
</script>
<include file="Public:footer"/>