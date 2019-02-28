<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-shopping-cart gear-icon"></i>
				功能库
			</li>
			<li class="active"><a href="{pigcms{:U('index_nav')}">首页自定义导航</a></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
					<if condition="!$house_session['has_index_nav']">
						<div style="margin-top:10px; cursor:pointer" class="alert alert-danger" onClick="window.open('{pigcms{:U('Index/index')}')">
							<button data-dismiss="alert" class="close" type="button"><i class="ace-icon fa fa-times"></i></button>
							还未开启&nbsp;&nbsp;<span style="font-weight:bold">首页自定义导航</span>&nbsp;&nbsp;功能，请先到&nbsp;&nbsp;<span style="font-weight:bold">社区管理 - 基本信息&nbsp;&nbsp;</span>开启相应配置。
						</div>
					</if>
					<button class="btn btn-success" onclick="nav_add()" <if condition="!in_array(240,$house_session['menus'])">disabled="disabled"</if>>添加</button>
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th width="35%">分类名称</th>
                                    <th width="20%">排序</th>
                                    <th width="20%">状态</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$result['list']">
                                    <volist name="result['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                           	<td><div class="tagDiv">{pigcms{$vo.sort}</div></td>
                                            <td>
												<div class="tagDiv">
													<if condition="$vo['status'] eq 0">
														<div class="tagDiv red">关闭</div>
													<else />
														<div class="tagDiv green">开启</div>
													</if>
												</div>
											</td>
                                            <td class="button-column">
												<a style="width: 60px;" class="label label-sm label-info" title="修改" href="{pigcms{:U('nav_edit',array('id'=>$vo['id']))}">修改</a>&nbsp;
                                                <if condition="in_array(242,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认删除该信息？')){location.href='{pigcms{:U('nav_del',array('id'=>$vo['id']))}'}">删除</a>
                                                </if>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="6" >没有任何分类。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$result.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function nav_add(){
	var url = "{pigcms{:U('nav_add')}";
	window.location.href = url;
}
</script>

<include file="Public:footer"/>