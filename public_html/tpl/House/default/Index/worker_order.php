<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-gear gear-icon"></i>
                <a href="{pigcms{:U('Index/worker')}">工作人员列表</a>
            </li>
            <li>
                <a href="{pigcms{:U('Index/worker_order')}">处理的任务列表</a>
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
				<div style="border:1px solid #c5d0dc;padding:10px;" class="form-group">
				<form method="post" action="{pigcms{:U('Index/worker_order', array('wid' => $wid))}">
					<input type="hidden" value="Index" name="c">
					<input type="hidden" value="worker_order" name="a">
					
					<input type="text" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间" name="begin_time" class="col-sm-2" style="margin-right:10px;font-size:18px;height:42px;" value="{pigcms{$_POST['begin_time']}" />
					<input type="text" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间" name="end_time" class="col-sm-2" style="margin-right:10px;font-size:18px;height:42px;" value="{pigcms{$_POST['end_time']}" />
					
					<select style="margin-right:10px;height:42px; width:100px" class="col-sm-1" id="status" name="status">
						<option value="0" <if condition="!$_POST['status']">selected="selected"</if>>状态</option>
						<option value="1" <if condition="$_POST['status'] eq 1">selected="selected"</if>>未指派</option>
						<option value="2" <if condition="$_POST['status'] eq 2">selected="selected"</if>>已指派</option>
						<option value="3" <if condition="$_POST['status'] eq 3">selected="selected"</if>>已受理</option>
						<option value="4" <if condition="$_POST['status'] eq 4">selected="selected"</if>>已处理</option>
						<option value="5" <if condition="$_POST['status'] eq 5">selected="selected"</if>>已评价</option>
					</select>
					
					<input type="submit" value="查找" id="find_submit" class="btn btn-success">
					<a onclick="location.href='{pigcms{:U('Index/worker_order',array('wid'=>$_GET['wid']))}'" class="btn btn-success">重置</a>
					<a onclick="location.href='{pigcms{:U('worker_export',array_merge($_POST,array('wid'=>$_GET['wid'])))}'" class="btn btn-success fr">EXCEL导出</a>
				</form>
			</div>
				
				
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                	<th width="10%">业主编号</th>
                                    <th width="10%">报修人</th>
									<th width="10%">状态</th>
                                    <th width="45%">报修内容</th>
									
									<if condition='$_GET["time"] eq "desc"'>
									<th width="10%"><a style="color:blue;cursor: pointer" href="{pigcms{:U('Index/worker_order',array('time'=>'asc','wid'=>$_GET['wid']))}">报修时间↓</a></th>
									<elseif condition='$_GET["time"] eq "asc"' />
									<th width="10%"><a style="color:blue;cursor: pointer" href="{pigcms{:U('Index/worker_order',array('time'=>'desc','wid'=>$_GET['wid']))}">报修时间↑</a></th>
									<else />
									<th width="10%"><a style="color:blue;cursor: pointer" href="{pigcms{:U('Index/worker_order',array('time'=>'desc','wid'=>$_GET['wid']))}">报修时间</a></th>
									</if>
									
                                   	<th width="10%">报修地址</th>
                                   	<th width="10%">评分</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$repair_list['repair_list']">
                                    <volist name="repair_list['repair_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                        	<td><div class="tagDiv">{pigcms{$vo.usernum}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
											<td>
												<if condition='$vo["status"] eq 0'>
													<span class="red">未指派</span>
												<elseif condition='$vo["status"] eq 1' />
													<span class="green">已指派</span>
												<elseif condition='$vo["status"] eq 2' />
													<span class="green">已受理</span>
												<elseif condition='$vo["status"] eq 3' />
													<span class="green">已处理</span>
												<elseif condition='$vo["status"] eq 4' />
													<span class="green">业主已评价</span>
												</if>
											</td>
                                            <td><div class="tagDiv">{pigcms{$vo.content}</div></td>
                                            <td><div class="shopNameDiv">{pigcms{$vo.time|date='Y-m-d H:i:s',###}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.address}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.score}</div></td>
                                            <td class="button-column">
                                           		<if condition="$vo['is_read'] eq 0 AND false">
                                                <a style="width:100px;" class="label label-sm label-info" title="已处理" href="javascript:;" onclick="read(this)" bindid='{pigcms{$vo.bind_id}' pid="{pigcms{$vo.pid}">标记为已处理</a>
                                                </if>
                                                <a style="width:60px;" class="label label-sm label-info handle_btn" title="查看详情" href="{pigcms{:U('Index/info',array('bindid'=>$vo['bind_id'],'pid'=>$vo['pid']))}" >详情</a>
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
