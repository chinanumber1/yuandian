<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-comments-o"></i>
                <a href="{pigcms{:U('News/reply')}">业主交流</a>
            </li>
            <li class="active">新闻评论列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <if condition="in_array(123,$house_session['menus'])">
        <span style="font-size: 20px;">是否开启评论审核</span>
        <td class="button-column" height="40px;">
            <label class="statusSwitch1" style="display:inline-block;line-height: 20px;">
                <input name="switch-field-1" class="ace ace-switch ace-switch-888" type="checkbox" <php>if ($is_check == 1) {</php>checked="checked" data-status="OPEN"<php>}else{</php>data-status="CLOSED"<php>}</php>/>
                <span class="lbl"></span>
            </label>
        </td>
        </if>
        <div class="page-content-area">
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="20%">新闻title</th>
                                    <th width="40%">评论内容</th>
                                    <th width="10%">评论人</th>
                                    <th width="10%">前台是否显示</th>
                                    <th width="10%">回复时间</th>
                                    <th class="button-column" width="10%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$news">
                                    <volist name="news['reply_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.title}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.content}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.nickname}</div></td>

                                            <td class="button-column">
                                                <if condition="in_array(126,$house_session['menus'])">
                                                <label class="statusSwitch" style="display:inline-block;">
                                                    <input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-id="{pigcms{$vo['pigcms_id']}" <php>if ($vo['status'] == 1) {</php>checked="checked" data-status="OPEN"<php>}else{</php>data-status="CLOSED"<php>}</php>/>
                                                    <span class="lbl"></span>
                                                </label>
                                                <else/> 
                                                <label style="display:inline-block;">
                                                    <php>if ($vo['status'] == 1) {</php>是<php>}else{</php>否<php>}</php>
                                                </label>
                                                </if>
                                            </td>

                                            <td><div class="shopNameDiv">{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</div></td>
                                            <td class="button-column">
                                                <if condition="in_array(124,$house_session['menus'])">
                                               		<if condition="$vo['is_read'] eq 0">
                                                    <a style="width:60px;" class="label label-sm label-info" title="已读" href="javascript:;" onclick="read(this)" cmsid='{pigcms{$vo.pigcms_id}'>已读</a>
                                                    </if>
                                                </if>
                                                <if condition="in_array(125,$house_session['menus'])">
                                                <a style="width:60px;margin-left: 10px;" class="label label-sm label-info" title="删除" href="javascript:;" onclick="reply_del(this)" cmsid='{pigcms{$vo.pigcms_id}'>删除</a>
                                                </if>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >没有评论。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$news.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
    $('.statusSwitch1 .ace-switch-888').click(function() {
        var _this = $(this), type = 'open';
        _this.attr("disabled", true);
        if (_this.attr("checked")) {	//开启
            type = 'open';
        } else {		//关闭
            type = 'close';
        }
        $.ajax({
            url: "{pigcms{:U('News/is_check')}",
            type: "post",
            data: {"type": type},
            dataType: "text",
            success: function (d) {
                if (d != '1') {		//失败
                    if (type == 'open') {
                        _this.attr("checked", false);
                    } else {
                        _this.attr("checked", true);
                    }
                    bootbox.alert(d);
                }else{
                    bootbox.alert("操作成功");
                }

                _this.attr("disabled", false);
            }
        });
    })

</script>
<script>
function read(obj){
	if(confirm('您确定要标记为已读？')){
		var cmsid = $(obj).attr('cmsid');
		$.post("{pigcms{:U('News/read')}",{cmsid:cmsid},function(result){
			if(result.status == 1){
				window.location.reload();
			}else{
                alert(result.msg);
            }
		})
	}
}
function reply_del(obj){
    if(confirm('您确定删除吗？')) {
        var cmsid = $(obj).attr('cmsid');
        $.post("{pigcms{:U('News/reply_del')}", {cmsid: cmsid}, function (result) {
            if (result.status == 1) {
                alert(result.msg);
                window.location.reload();
            } else {
                alert(result.msg);
            }
        })
    }
}

$(function(){
    updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED");
});
function updateStatus(dom1, dom2, status1, status2){
    $(dom1).each(function(){
        if($(this).attr("data-status")==status1){
            $(this).attr("checked",true);
        }else{
            $(this).attr("checked",false);
        }
        $(dom2).show();
    }).click(function(){
        var _this = $(this), type = 'open', id = $(this).attr("data-id");
        _this.attr("disabled",true);
        if(_this.attr("checked")){	//开启
            type = 'open';
        }else{		//关闭
            type = 'close';
        }
        $.ajax({
            url:"{pigcms{:U('News/change_status')}",
            type:"post",
            data:{"type":type,"id":id,"status1":status1,"status2":status2},
            dataType:"text",
            success:function(d){
                if(d != '1'){		//失败
                    if(type=='open'){
                        _this.attr("checked",false);
                    }else{
                        _this.attr("checked",true);
                    }
                    bootbox.alert(d);
                }
                _this.attr("disabled",false);
            }
        });
    });
}
</script>
<include file="Public:footer"/>
