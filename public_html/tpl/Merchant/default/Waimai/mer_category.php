<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-cutlery"></i>
                <a href="{pigcms{:U('Waimai/mer_category')}">{pigcms{$config.waimai_alias_name}管理</a>
            </li>
            <li class="active">店铺分类列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        <button onclick="CreateCategory()" class="btn btn-success">管理店铺分类</button>
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>编号</th>
									<th>分类名</th>
									<th>拼音名</th>
									<th>所属店铺</th>
									<th>排序</th>
									<th>状态</th>
									<th>创建时间</th>
									<th>最后修改时间</th>
									<th class="textcenter">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$categoryList">
                                    <volist name="categoryList['category_list']" id="vo">
                                        <tr class="<if condition="$key%2 eq 0">odd<else/>even</if>">
                                           	<td>{pigcms{$vo.gcat_id}</td>
											<td>{pigcms{$vo.gcat_name}</td>
											<td>{pigcms{$vo.gcat_pinyin}</td>
											<td>{pigcms{$vo.store_name}</td>
											<td>{pigcms{$vo.gcat_sort}</td>
											<td>
												<if condition="$vo['gcat_status'] eq 0"><span style="color:red">关闭</span>
												<elseif condition="$vo['gcat_status'] eq 1" /><span style="color:green">开启</span>
												</if>
											</td>
											<td>{pigcms{$vo.create_time|date="Y-m-d H:i:s",###}</td>
											<td>{pigcms{$vo.last_time|date="Y-m-d H:i:s",###}</td>
											<td class="textcenter">
											  	<a href="{pigcms{:U('Waimai/product_category_manage',array('cat_id'=>$vo['gcat_id']))}" >编辑</a> |
											  	<a id='js-del' href="{pigcms{:U('Waimai/product_category_del',array('cat_id'=>$vo['gcat_id']))}" class="delete_row" >删除</a>
											 </td>
                                        </tr>
                                    </volist>
                                    <tr><td class="textcenter pagebar" colspan="11">{pigcms{$pagebar}</td></tr>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="9" >列表为空！</td></tr>
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
<script type="text/javascript">
	function CreateCategory(){
		window.location.href = "{pigcms{:U('Waimai/mer_category_manage')}";
	}
</script>
<script type="text/javascript">
$(function(){
    
    jQuery(document).on('click','#js-del',function(){
        if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
    });
});

function updateStatus(dom1, dom2, status1, status2, attribute){
    $(dom1).each(function(){
        if($(this).attr("data-status")==status1){
            $(this).attr("checked",true);
        }else{
            $(this).attr("checked",false);
        }
        $(dom2).show();
    }).click(function(){
        var _this = $(this),
            type = 'open',
            id = $(this).attr("data-id");
        _this.attr("disabled",true);
        if(_this.attr("checked")){  //开启
            type = 'open';
        }else{      //关闭
            type = 'close';
        }
        $.ajax({
            url:"{pigcms{:U('Config/store_status')}",
            type:"post",
            data:{"type":type,"id":id,"status1":status1,"status2":status2,"attribute":attribute},
            dataType:"text",
            success:function(d){
                if(!d){     //失败
                    if(type=='open'){
                        _this.attr("checked",false);
                    }else{
                        _this.attr("checked",true);
                    }
                    bootbox.alert("操作失败");
                }
                _this.attr("disabled",false);
            }
        });
    });
}
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
    $(function(){
        $('.see_qrcode').click(function(){
            art.dialog.open($(this).attr('href'),{
                init: function(){
                    var iframe = this.iframe.contentWindow;
                    window.top.art.dialog.data('iframe_handle',iframe);
                },
                id: 'handle',
                title:'查看渠道二维码',
                padding: 0,
                width: 430,
                height: 433,
                lock: true,
                resize: false,
                background:'black',
                button: null,
                fixed: false,
                close: null,
                left: '50%',
                top: '38.2%',
                opacity:'0.4'
            });
            return false;
        });
    });
</script>
<include file="Public:footer"/>
