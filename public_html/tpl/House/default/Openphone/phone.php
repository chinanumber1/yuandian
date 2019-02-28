<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-shopping-cart gear-icon"></i>
				功能库
			</li>
			<li><a href="{pigcms{:U('Openphone/phone')}">常用电话</a></li>
			<!-- <li class="active"><a href="{pigcms{:U('Openphone/phone',array('cat_id'=>$now_cat['cat_id']))}">电话列表</a></li> -->
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
        <div class="page-content-area">
        	
                <button class="btn btn-success" style="margin-bottom: 10px" onclick="location.href='{pigcms{:U('index')}'" <if condition="!in_array(199,$house_session['menus'])">disabled="disabled"</if>>分类管理</button> &nbsp;&nbsp;
                <button class="btn btn-success" style="margin-bottom: 10px" onclick="phone_add()" <if condition="!in_array(204,$house_session['menus'])">disabled="disabled"</if>>添加电话</button>  
            <div class="row">
                <table class="search_table" width="100%">
                    <tr>
                        <td>
                            <form action="{pigcms{:U('phone')}" method="get">
                                <input type="hidden" name="c" value="Openphone"/>
                                <input type="hidden" name="a" value="phone"/>
                                
                                名称: <input type="text" name="name" class="input-text" value="{pigcms{$_GET['name']}"  style="height:42px"/>&nbsp;&nbsp;
                                号码: <input type="text" name="phone" class="input-text" value="{pigcms{$_GET['phone']}"  style="height:42px"/>&nbsp;&nbsp;
                                分类：
                               <select name="cate" id="cate" style="height:42px">
                                    <option value="0" <if condition="$_GET['cate'] eq '0'">selected="selected"</if>>所有</option>
                                    <volist name="cate_list" id="vo">
                                    <option value="{pigcms{$vo['cat_id']}" <if condition="$_GET['cate'] eq $vo['cat_id']">selected="selected"</if>>{pigcms{$vo['cat_name']}</option> 
                                    </volist>
                                </select>&nbsp;&nbsp;
                                <button class="btn btn-success" type="submit">查询</button>&nbsp;&nbsp;
                                <button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('phone')}'">重置</button>&nbsp;&nbsp;
                            </form>
                        </td>
                    </tr>
                </table>
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="25%">分类</th>
                                    <th width="25%">名称</th>
                                    <th width="20%">电话</th>
                                    <th width="20%">排序</th>
                                    <th width="15%">状态</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$phone_list">
                                    <volist name="phone_list['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.pigcms_id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.cat_name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                           	<td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
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
												<a style="width: 60px;" class="label label-sm label-info" title="修改" href="{pigcms{:U('phone_edit',array('id'=>$vo['pigcms_id']))}">修改</a>&nbsp;
                                                <if condition="in_array(206,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认删除该电话？')){location.href='{pigcms{:U('phone_del',array('id'=>$vo['pigcms_id']))}'}">删除</a>
                                                </if>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="6" >没有任何分类。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$phone_list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function phone_add(){
	var url = "{pigcms{:U('phone_add',array('cat_id'=>$now_cat['cat_id']))}";
	window.location.href = url;
}
</script>

<include file="Public:footer"/>