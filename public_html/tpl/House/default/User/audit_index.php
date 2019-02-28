<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/audit_index')}">审核业主</a>
            </li>
            <li class="active">业主审核列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        	<div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
				<form method="get" id="find-form">
					<input type="hidden" name="c" value="User"/>
					<input type="hidden" name="a" value="audit_index"/>
					<select name="find_type" id="find_type" class="col-sm-1" style="margin-right:10px;height:42px;">
						<option value="1" <if condition="$find_type eq 1">selected="selected"</if>>物业编号</option>
						<option value="3" <if condition="$find_type eq 3">selected="selected"</if>>手机号</option>
                        <option value="2" <if condition="$find_type eq 2">selected="selected"</if>>姓名</option>
					</select>
					<input value="{pigcms{$find_value}" class="col-sm-2" placeholder="" name="find_value" id="find_value" type="text" style="margin-right:10px;font-size:18px;height:42px;"/>

					<select name="status" id="status" class="col-sm-1" style="margin-right:10px;height:42px;">
						<option value="" selected="selected">请选择</option>
						<option value="1" <if condition="$find_status eq 1">selected="selected"</if>>正常</option>
                        <option value="2" <if condition="$find_status eq 2">selected="selected"</if>>审核中</option>
                        <option value="3" <if condition="$find_status eq 3">selected="selected"</if>>审核通过</option>
						<option value="0" <if condition="$find_status === 0">selected="selected"</if>>未通过</option>
					</select>
					&nbsp;&nbsp;
					申请日期：
					<input type="text" name="begin_time" class="input-text" value="{pigcms{$_GET['begin_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:true,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间"/>-
					<input type="text" name="end_time" class="input-text" value="{pigcms{$_GET['end_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:true,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间"/>&nbsp;&nbsp;
					
					<input class="btn btn-success" type="submit" id="find_submit" value="查找业主" />&nbsp;
					<a class="btn btn-success" onclick="location.href='{pigcms{:U('User/audit_index')}'">重置</a>
				</form>
			</div>
			
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                	<th width="3%" style="text-align:center"><input type="checkbox" class="checkbox_all" style="wdith:20px; height:20px;"></th>
                                    <th width="10%">物业编号</th>
                                    <th width="10%">姓名</th>
                                    <th width="10%">手机号</th>
									 <th width="5%">住宅类型</th>
                                    <th width="15%">住址</th>
                                    <th width="15%">绑定关系</th>
                                    <th width="15%">申请时间</th>
                                    <th width="5%">状态</th>
                                    <th class="button-column" width="20%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$user_list['list']">
                                    <volist name="user_list['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td align="center">
                                        	  	<if condition="$vo['status'] eq 0">
                                        	  		<input type="checkbox" name="delCheckbox[]" style="wdith:20px; height:20px;" value="{pigcms{$vo.pigcms_id}" onclick="return Dcheckbox($(this));">
                                                <else/>
                                                	<input type="checkbox" name="delCheckbox[]" style="wdith:20px; height:20px;" onclick="return Dcheckbox($(this));"  disabled="disabled">
                                                </if>
                                            </td>
                                            <td><div class="tagDiv">{pigcms{$vo.usernum}</div></td>
                                            <td>{pigcms{$vo.name}</td>
                                            <td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
											<td><if condition='$vo["floor_type_name"]'><div class="tagDiv">{pigcms{$vo.floor_type_name}</div><else /><div class="tagDiv red">暂无</div></if></td>
                                            <td><div class="tagDiv">{pigcms{$vo.floor_layer} {pigcms{$vo.floor_name} {pigcms{$vo.layer} {pigcms{$vo.room}</div></td>
                                            <td>
												<div class="tagDiv">
													<if condition='$vo["type"] eq 0'>
														房主
													<elseif condition='$vo["type"] eq 1' />
														家人
													<elseif condition='$vo["type"] eq 2' />
														租客
													</if>
												</div>
											</td>
                                            <td><if condition="$vo['application_time']">{pigcms{$vo.application_time|date='Y-m-d H:i:s',###}<else/>--</if></td>
                                            <td>
												<if condition='$vo["status"] eq 0'>
													<div class="shopNameDiv red">未通过</div>
												<elseif condition='$vo["status"] eq 1' />
													<div class="shopNameDiv green">正常</div>
												<elseif condition='$vo["status"] eq 2' />
													<div class="shopNameDiv red">审核中</div>
												<elseif condition='$vo["status"] eq 3' />
													<div class="shopNameDiv green">审核通过</div>
												<else />
													<div class="shopNameDiv red">已解绑</div>
												</if>
											</td>
                                            <td class="button-column">
												<if condition='$vo["status"] neq 1'>
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('User/audit_edit',array('pigcms_id'=>$vo['pigcms_id'],'usernum'=>$vo['usernum']))}">编辑</a>
												</if>

												<if condition="in_array(268,$house_session['menus']) && $vo['status'] eq 0">
												&nbsp;&nbsp;
												<a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onclick="if(confirm('确认删除该条信息？')){location.href=\'{pigcms{:U('User/audit_delete',array('pigcms_id'=>$vo['pigcms_id']))}\'}">删除</a>
                                              	</if>
                                                
												<!--a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onclick="if(confirm('确认删除该条信息？')){location.href=\'{pigcms{:U('audit_del',array('pigcms_id'=>$vo['pigcms_id']))}\'}">删除</a-->
                                           </td>
                                        </tr>
                                    </volist>
                                    <if condition="in_array(268,$house_session['menus'])">
                                    <tr>
                                    	<td colspan="14">&nbsp;&nbsp;&nbsp;<button class=" btn delete_class">删除选中</button></td>
                                    </tr>
                                	</if>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="12" >没有任何业主。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$user_list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					警告提示
				</h4>
			</div>
			<div class="modal-body">
				删除业主后，业主下的家属/租客会被一起删除，绑定的房间会被释放，确定删除？
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary class-delete">
					确定
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>
<div class="modal fade" id="myModal_alert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">提示信息</h4>
			</div>
			<div class="modal-body">
				请先选择要执行的业主！
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary class-tip">确定</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" language="javascript">
function importUser(){
	window.location.href = "{pigcms{:U('User/user_import')}";
}
function importUserDetail(){
	window.location.href = "{pigcms{:U('User/detail_import')}";
}

function send_property(){
	var property_warn_day = "{pigcms{$village_info['property_warn_day']}";
	if(parseInt(property_warn_day) > 0){
		var confirm_txt = "确认群发微信消息（物业费到期提前" + property_warn_day + "天提醒）";
	}else{
		var confirm_txt = "确认群发微信消息（物业费到期提醒）";
	}
	
	if(confirm(confirm_txt)){
		var url = "{pigcms{:U('User/send_property')}";
		$.post(url , {'is_collective':1},function(data){
			if(data['status']){
				alert(data['msg']);
			}
		},'json')
	}
}

function send_property_one(pigcms_id , usernum){
	if(confirm('确认发送微信消息？')){
		var url = "{pigcms{:U('User/send_property')}";
		$.post(url,{'pigcms_id':pigcms_id,'usernum':usernum},function(data){
			if(data['status']){
				alert(data['msg']);
			}
		},'json')
	}
}

$(".checkbox_all").on('click',function(){
	
	if($(this).is(':checked')){
		var len = $("input[name='delCheckbox[]']").length;
		for(var i=0;i<len;i++){
			console.log($("input[name='delCheckbox[]']")[i].disabled)
			if($("input[name='delCheckbox[]']")[i].disabled==false) {
				$("input[name='delCheckbox[]']").eq(i).prop("checked",true);  
			};		
		}
	}else{
		$("input[name='delCheckbox[]']").prop("checked",false);  	
	}	
		
});

function Dcheckbox(e){
	var n=0;
	var len = $("input[name='delCheckbox[]']").length;
	for(var i=0;i<len;i++){
		if($("input[name='delCheckbox[]']")[i].checked) n++;		
	}
	if(n==len){
		$(".checkbox_all").prop("checked",true); 
	}else{
		$(".checkbox_all").prop("checked",false); 
	}
		
}

$(".delete_class").on('click',function(){
	var len = $("input[name='delCheckbox[]']:checked").length;
	if(len<=0){
		$('#myModal_alert').modal({
			keyboard: false,
			backdrop: 'static'
		})
	}else{
		$('#myModal').modal({
			keyboard: false,
			backdrop: 'static'
		})	
	}
});	
$(".class-delete").on('click',function(){
	$('#myModal').modal('hide');
	var length = $("input[name='delCheckbox[]']").length;
	var value="";
	for(var i=0;i<length;i++){
		if($("input[name='delCheckbox[]']")[i].checked) value += "," + $("input[name='delCheckbox[]']")[i].value;	
	}
	value = value.substring(1);
	if(value){
		var village_id = "{pigcms{$village_info.village_id}";
		var url = "{pigcms{:U('User/ajax_del_audit')}";
		$.post(url,{'village_id':village_id,'arr_pigcms_id':value},function(data){
			if(data['status']){
				location.reload();
			}else{
				alert(data['msg']);
			}
		},'json')
	}	
		
});
$(".class-tip").on('click',function(){
	$('#myModal_alert').modal('hide');	
});

</script>
<include file="Public:footer"/>
