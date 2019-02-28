<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="javascript:void(0);">收费管理</a>
            </li>
            <li class="active">打印模板设置</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <button class="btn btn-success" onclick="Add()" <if condition="!in_array(87,$house_session['menus'])">disabled="disabled"</if>>添加打印模板</button>
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
                                    <th width="30%">模板名称</th>
                                    <th width="30%">说明</th>
                                    <th class="button-column" width="20%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$list">
                                    <volist name="list['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.template_id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.title}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.desc}</div></td>
                                            <td>
                                                <a style="width: 60px;" class="label label-sm label-info" title="预览" href="javascript:void(0)" url="{pigcms{:U('print_template_detail',array('template_id'=>$vo['template_id']))}" onclick="detail(this)">预览</a> &nbsp; 
                                                <if condition="in_array(88,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('print_template_add',array('template_id'=>$vo['template_id']))}">编辑</a> &nbsp;
                                                </if>
                                                <if condition="in_array(89,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认要删除吗？')){location.href='{pigcms{:U('print_template_del',array('template_id'=>$vo['template_id']))}'}">删除</a>
                                                </if>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="4" >没有任何打印模板。</td></tr>
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
    window.location.href = "{pigcms{:U('print_template_add')}";
}

function detail(obj){
    var url = $(obj).attr('url');
    art.dialog.open(url, {
        title: "打印预览",
        lock: true,
        width: 800,
        height: 370,
        button:[{name:'关闭'}],
    },true);
}
</script>
<include file="Public:footer"/>
