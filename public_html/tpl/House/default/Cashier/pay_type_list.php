<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="javascript:void(0);">收费管理</a>
            </li>
            <li class="active">收费设置</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <a class="btn btn-cancel" href="{pigcms{:U('payment_item')}">返回</a> &nbsp;&nbsp;
        	<button class="btn btn-success" onclick="Add()" <if condition="!in_array(80,$house_session['menus'])">disabled="disabled"</if>>添加线下支付方式</button>
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="20%">编号</th>
                                    <th width="30%">支付名称</th>
                                    <th class="button-column" width="20%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$paytype_list">
                                    <volist name="paytype_list" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                            <td>
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('pay_type_add',array('id'=>$vo['id']))}">编辑</a> &nbsp;
                                                <if condition="in_array(82,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认要删除吗？')){location.href='{pigcms{:U('pay_type_del',array('id'=>$vo['id']))}'}">删除</a>
                                                </if>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="4" >没有任何收费项。</td></tr>
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
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
function Add(){
	window.location.href = "{pigcms{:U('pay_type_add')}";
}
function importUserDetail(){
	window.location.href = "{pigcms{:U('User/detail_import')}";
}
</script>
<include file="Public:footer"/>
