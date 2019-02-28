<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-empire"></i>
                <a href="{pigcms{:U('Index/active_appoint_list')}">推荐预约管理</a>
            </li>
            <li class="active">预约列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        	<button class="btn btn-success" onclick="CreateCategory()" <if condition="!in_array(147,$house_session['menus'])">disabled="disabled"</if>>新增预约</button>
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="40%">预约名字</th>
                                    <th width="20%">商家名字</th>
                                    <th width="10%">别名</th>
                                    <th width="10%">预约详情</th>
                                    <th width="10%">排序</th>
                                    <th class="button-column" width="10%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$appoint">
                                    <volist name="appoint['appoint_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.appoint_name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.merchant_name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.label}</div></td>
                                            <td><div class="tagDiv"><a title="看详情" target='_blank' href="<if condition="$vo.url eq ''">{pigcms{$config.site_url}/appoint/{pigcms{$vo.appoint_id}.html<else />{pigcms{$vo.url}</if>">访问</a></div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.sort}</div></td>
                                            <td class="button-column">
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('Index/active_appoint_edit',array('id'=>$vo['pigcms_id']))}">编辑</a>
                                                <if condition="in_array(149,$house_session['menus'])">
                                                    <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:;" onclick='del(this);' appointid="{pigcms{$vo['appoint_id']}">删除</a>
                                                </if>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >您没有添加任何预约信息。</td></tr>
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
	window.location.href = "{pigcms{:U('Index/active_appoint')}";
}
function del(obj){
	if(confirm('您确定要删除？')){
		var appointid = $(obj).attr('appointid');
		if(appointid){
			$.post('{pigcms{:U("Index/active_appoint_del")}', {appoint_id:appointid}, function(result){
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