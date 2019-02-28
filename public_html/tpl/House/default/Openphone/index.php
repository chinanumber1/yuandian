<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-shopping-cart gear-icon"></i>
				功能库
			</li>
			<li class="active"><a href="{pigcms{:U('Openphone/phone')}">常用电话</a></li>
            <li class="active">分类列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
        <div class="page-content-area">
        	
            <div class="row">
                <div class="col-xs-12">
					<button class="btn btn-success" onclick="catgory_add()" <if condition="!in_array(200,$house_session['menus'])">disabled="disabled"</if>>添加分类</button>
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="35%">分类名称</th>
                                    <!-- <th width="15%">电话列表</th> -->
                                    <th width="15%">排序</th>
                                    <th width="15%">状态</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$cat_list">
                                    <volist name="cat_list" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.cat_id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.cat_name}</div></td>
                                           <!-- 	<td><div class="tagDiv">
                                                <if condition="in_array(203,$house_session['menus'])">
                                                <a href="{pigcms{:U('Openphone/phone',array('cat_id'=>$vo['cat_id']))}">电话列表</a>
                                                <else/>电话列表(无权限)
                                                </if>
                                            </div></td> -->
                                           	<td><div class="tagDiv">{pigcms{$vo.cat_sort}</div></td>
                                            <td>
												<div class="tagDiv">
													<if condition="$vo['cat_status'] eq 0">
														<div class="tagDiv red">关闭</div>
													<else />
														<div class="tagDiv green">开启</div>
													</if>
												</div>
											</td>
                                            <td class="button-column">
												<a style="width: 60px;" class="label label-sm label-info" title="修改" href="{pigcms{:U('cat_edit',array('cat_id'=>$vo['cat_id']))}">修改</a>&nbsp;
                                                <if condition="in_array(202,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认删除该分类？分类下的电话也会被删除？')){location.href='{pigcms{:U('cat_del',array('cat_id'=>$vo['cat_id']))}'}">删除</a>
                                                </if>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="6" >没有任何分类。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function catgory_add(){
	var url = "{pigcms{:U('cat_add')}";
	var cat_id = "{pigcms{$_GET.cat_id}";
	if(cat_id){
		url+='&cat_id='+cat_id;
	}
	window.location.href = url;
}
</script>

<include file="Public:footer"/>