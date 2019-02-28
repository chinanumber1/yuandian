<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-desktop"></i>
                <a href="{pigcms{:U('Deliver/user')}">配送管理</a>
            </li>
            <li class="active">配送员列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                	<button onclick="CreateDeliver()" class="btn btn-success">添加配送员</button>
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">编号</th>
                                    <th width="5%">昵称</th>
                                    <th width="10%">手机号</th>
                                    <th width="8%">常驻地址</th>
                                    <th width="8%">所属店铺</th>
                                    <th width="10%">最后修改时间</th>
                                    <th width="10%">状态</th>
                                    <th width="10%">配送总量</th>
                                    <th class="button-column" width="12%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="is_array($user_list)">
                                    <volist name="user_list" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.uid}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                            <td>{pigcms{$vo.phone}</td>
                                            <td>{pigcms{$vo.site}</td>
                                            <td><?php if($storeInfoNew[$vo['store_id']]){echo $storeInfoNew[$vo['store_id']]['name'];}?></td>
                                            <td><div class="tagDiv">{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</div></td>
                                            <td class="button-column">
                                               <php>if ($vo['status'] == 1) {</php><font color="green">正常</font><php> }else { </php><font color="red">禁止</font><php> } </php>
                                            </td>
                                            <td>{pigcms{$vo.num}</td>
                                            <td class="button-column">
                                            	<a style="width: 60px;" class="" href="{pigcms{:U('Deliver/count_log',array('uid'=>$vo['uid']))}">历史记录统计</a>　 | 　
                                            	<a style="width: 60px;" class="" href="{pigcms{:U('Deliver/log_list',array('uid'=>$vo['uid']))}">查看配送记录</a>　 | 　
                                                <a style="width: 60px;" class="" href="{pigcms{:U('Deliver/user_edit',array('uid'=>$vo['uid']))}">编辑</a><!-- 　 | 　
                                                <a style="width: 60px;" class="red"  href="{pigcms{:U('Deliver/user_del',array('uid'=>$vo['uid']))}">删除</a> -->
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="9" >您没有添加配送员</td></tr>
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
$(function(){
    /*店铺状态*/
    updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");
    
    jQuery(document).on('click','#shopList a.red',function(){
        if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
    });
});
function CreateDeliver(){
    window.location.href = "{pigcms{:U('Deliver/user_add')}";
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
<include file="Public:footer"/>