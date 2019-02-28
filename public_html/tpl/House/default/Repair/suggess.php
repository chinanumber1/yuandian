<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-comments-o"></i>
                <a href="{pigcms{:U('Repair/suggess')}">投诉建议列表</a>
            </li>
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
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                	<th width="10%">业主编号</th>
                                    <th width="10%">投诉人</th>
                                    <th width="45%">投诉内容</th>
                                    <th width="10%">投诉时间</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$repair_list">
                                    <volist name="repair_list['repair_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                        	<td><div class="tagDiv">{pigcms{$vo.usernum}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.content}</div></td>
                                            <td><div class="shopNameDiv">{pigcms{$vo.time|date='Y-m-d H:i:s',###}</div></td>
                                            <td class="button-column">
                                           		<if condition="$vo['is_read'] eq 0">
                                                <a style="width:100px;" class="label label-sm label-info" title="已处理" href="javascript:;" onclick="read(this)" bindid='{pigcms{$vo.bind_id}' pid="{pigcms{$vo.pid}">标记为已处理</a>
                                                </if>
                                                <a style="width: 60px;" class="label label-sm label-info handle_btn" title="查看详情" href="{pigcms{:U('Repair/info',array('bindid'=>$vo['bind_id'],'pid'=>$vo['pid']))}" >详情</a>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >暂无数据。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$repair_list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
function read(obj){
	if(confirm('您确定要标记为已处理？')){
		var bindid = $(obj).attr('bindid');
		var cid = $(obj).attr('pid');
		$.post("{pigcms{:U('Repair/do_repair')}",{bind_id:bindid,cid:cid},function(result){
			if(result.error == 0){
				window.location.reload();
			}
		})
	}
}
	$(function(){
		$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看详情',
				padding: 0,
				width: 820,
				height: 520,
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
