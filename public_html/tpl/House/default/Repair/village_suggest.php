<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-tasks"></i>
                <a href="{pigcms{:U('Repair/village_suggest')}">投诉列表</a>
            </li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <style>
                .ace-file-input a {display:none;}
            </style>
			
			<div style="border:1px solid #c5d0dc;padding:10px;" class="form-group">
				<form method="get" action="{pigcms{:U('Repair/index')}">
					<input type="hidden" value="Repair" name="c">
					<input type="hidden" value="village_suggest" name="a">
				
					<select style="margin-right:10px;height:42px; width:120px" class="col-sm-1" id="find_type" name="find_type">
						<option value="0" <if condition="$_GET['find_type'] eq 0">selected="selected"</if>>请选择</option>
						<option value="1" <if condition="$_GET['find_type'] eq 1">selected="selected"</if>>业主编号</option>
						<option value="2" <if condition="$_GET['find_type'] eq 2">selected="selected"</if>>手机号</option>
						<option value="3" <if condition="$_GET['find_type'] eq 3">selected="selected"</if>>上报地址</option>
					</select>
					<input type="text" style="margin-right:10px;font-size:18px;height:42px;" id="find_value" name="find_value" class="col-sm-2" value="{pigcms{$_GET['find_value']}">
					
					<select style="margin-right:10px;height:42px; width:150px" class="col-sm-1" id="status" name="status">
						<option value="0" <if condition="!$_GET['status']">selected="selected"</if>>状态</option>
						<option value="1" <if condition="$_GET['status'] eq 1">selected="selected"</if>>未受理</option>
						<option value="2" <if condition="$_GET['status'] eq 2">selected="selected"</if>>物业已受理</option>
						<option value="3" <if condition="$_GET['status'] eq 3">selected="selected"</if>>客服专员已受理</option>
						<option value="4" <if condition="$_GET['status'] eq 4">selected="selected"</if>>客服专员已处理</option>
						<option value="5" <if condition="$_GET['status'] eq 5">selected="selected"</if>>已评价</option>
					</select>
					
					<input type="text" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间" name="begin_time" class="col-sm-2" style="margin-right:10px;font-size:18px;height:42px;" value="{pigcms{$_GET['begin_time']}" />
					<input type="text" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间" name="end_time" class="col-sm-2" style="margin-right:10px;font-size:18px;height:42px;" value="{pigcms{$_GET['end_time']}" />
					
					<input type="submit" value="查找" id="find_submit" class="btn btn-success"/>&nbsp;&nbsp;
					<a onclick="location.href='{pigcms{:U('Repair/village_suggest')}'" class="btn btn-success">重置</a>
					<if condition="in_array(225,$house_session['menus'])">
					<a onclick="location.href='{pigcms{:U('repair_export',array_merge($_GET,array('type'=>3)))}'" class="btn btn-success fr">EXCEL导出</a>
					<else/>
					<button class="btn btn-success fr" disabled="disabled">EXCEL导出</button>
					</if>
				</form>
			</div>
			
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                	<th width="10%">业主编号</th>
                                    <th width="10%">上报人</th>
									<th width="10%">手机号</th>
									<th width="10%">状态</th>
                                    <th width="25%">上报内容</th>
									
									<if condition='$_GET["time"] eq "desc"'>
									<th width="10%"><a style="color:blue;cursor: pointer" href="{pigcms{:U('Repair/village_suggest',array_merge($_GET,array('time'=>asc)))}">上报时间↓</a></th>
									<elseif condition='$_GET["time"] eq "asc"' />
									<th width="10%"><a style="color:blue;cursor: pointer" href="{pigcms{:U('Repair/village_suggest',array_merge($_GET,array('time'=>desc)))}">上报时间↑</a></th>
									<else />
									<th width="10%"><a style="color:blue;cursor: pointer" href="{pigcms{:U('Repair/village_suggest',array_merge($_GET,array('time'=>desc)))}">上报时间</a></th>
									</if>
									
                                   	<th width="10%">上报地址</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$repair_list['repair_list']">
                                    <volist name="repair_list['repair_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                        	<td><div class="tagDiv">{pigcms{$vo.usernum}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
											<td>
												<if condition='$vo["r_status"] eq 0'>
													<span class="red">未受理</span>
												<elseif condition='$vo["r_status"] eq 1' />
													<span class="green">物业已受理</span>
												<elseif condition='$vo["r_status"] eq 2' />
													<span class="green">客服专员已受理</span>
												<elseif condition='$vo["r_status"] eq 3' />
													<span class="green">客服专员已处理</span>
												<elseif condition='$vo["r_status"] eq 4' />
													<span class="green">业主已评价</span>
												</if>
											</td>
                                            <td><div class="tagDiv">{pigcms{$vo.content}</div></td>
                                            <td><div class="shopNameDiv">{pigcms{$vo.time|date='Y-m-d H:i:s',###}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.address}</div></td>
                                            <td class="button-column">
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
