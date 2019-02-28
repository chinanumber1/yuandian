<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <i class="ace-icon fa fa-cloud"></i>
            <li class="active">基本信息</li>
            <li class="active">小票打印机</li>
        </ul>
    </div>
    <div class="alert alert-info" style="margin:10px;">
        <button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>无线订单打印机（小票打印机）是指无需人工处理，有订单的时候会自动打印订单信息的小型打印机！<br/>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
                    <div class="tabbable">
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <if condition="in_array(13,$house_session['menus'])">
                                <a href="{pigcms{:U('Index/printer_add')}" class="btn btn-success">创建打印机</a>
                                <else/>
                                <button class="btn btn-success disabled" disabled="disabled">创建打印机</button>
                                </if>
                                <div id="shopList" class="grid-view">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="100">打印机名称</th>
                                                <th width="100">绑定账号</th>
                                                <th width="100">终端号</th>
                                                <th width="100">密钥</th>
                                                <th width="40">打印份数</th>
                                                <th width="150">打印条件</th>
                                                <!-- <th width="80">是否是主打印机</th> -->
                                                <!--th width="100">打印二维码</th-->
                                                <th width="80" class="button-column">操作</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <if condition="$list">
                                                <volist name="list" id="row">
                                                    <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                                        <td>{pigcms{$row.name}</td>
                                                        <td>{pigcms{$row.username}</td>
                                                        <td>{pigcms{$row.mcode}</td>
                                                        <td>{pigcms{$row.mkey}</td>
                                                        <td>{pigcms{$row.count}</td>
                                                        <td>{pigcms{$row.str}</td>
                                                       <!--  <td class="button-column">
                                                            <label class="statusSwitch" style="display:inline-block;">
                                                                <input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-id="{pigcms{$row['pigcms_id']}" <if condition="$row['is_main'] eq 1">checked="checked" data-status="OPEN"<else/>data-status="CLOSED"</if>/>
                                                                <span class="lbl"></span>
                                                            </label>
                                                        </td> -->
                                                        <!--td><if condition="$row['qrcode']"><img src="{pigcms{$row['qrcode']}" width="70"/><else /></if></td-->
                                                        <td class="button-column">
                                                            <a class="green" style="padding-right:8px;" href="{pigcms{:U('Index/printer_add', array('pigcms_id' => $row['pigcms_id']))}" >
                                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                            </a>
                                                            <if condition="in_array(15,$house_session['menus'])">
                                                            <a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('Index/delprint', array('pigcms_id' => $row['pigcms_id']))}">
                                                                <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                            </a>
                                                            </if>
                                                        </td>
                                                    </tr>
                                                </volist>
                                            <else/>
                                                <tr class="odd"><td class="button-column" colspan="8" >暂无打印机</td></tr>
                                            </if>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    jQuery(document).on('click','#shopList a.red',function(){
        if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
    });
    updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");
});
function drop_confirm(msg, url)
{
    if (confirm(msg)) {
        window.location.href = url;
    }
}
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
            url:"{pigcms{:U('Index/print_status')}",
            type:"post",
            data:{"type":type,"id":id,"status1":status1,"status2":status2,"attribute":attribute},
            dataType:"text",
            success:function(d){
                if(d != '1'){       //失败
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
<include file="Public:footer"/>
