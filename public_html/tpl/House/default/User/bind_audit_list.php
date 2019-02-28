<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/bind_audit_list')}">审核家属</a>
            </li>
            <li class="active">审核家属列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        	<div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
				<form method="get" id="find-form">
					<input type="hidden" name="c" value="User"/>
					<input type="hidden" name="a" value="bind_audit_list"/>
					<select name="find_type" id="find_type" class="col-sm-1" style="margin-right:10px;height:42px;">
						<option value="1" <if condition="$find_type eq 1">selected="selected"</if>>家属姓名</option>
                        <option value="2" <if condition="$find_type eq 2">selected="selected"</if>>家属手机号</option>
						<option value="3" <if condition="$find_type eq 3">selected="selected"</if>>业主姓名</option>
                        <option value="4" <if condition="$find_type eq 4">selected="selected"</if>>业主手机号</option>
					</select>
					<input value="{pigcms{$find_value}" class="col-sm-2" placeholder="" name="find_value" id="find_value" type="text" style="margin-right:10px;font-size:18px;height:42px;"/>

					<select name="status" id="status" class="col-sm-1" style="margin-right:10px;height:42px;">
						<option value="" selected="selected">请选择</option>
						<option value="1" <if condition="$find_status eq 1">selected="selected"</if>>审核通过</option>
                        <option value="2" <if condition="$find_status eq 2">selected="selected"</if>>审核中</option>
					</select>
					&nbsp;&nbsp;
					申请日期：
					<input type="text" name="begin_time" class="input-text" value="{pigcms{$_GET['begin_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:true,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间"/>-
					<input type="text" name="end_time" class="input-text" value="{pigcms{$_GET['end_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:true,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间"/>&nbsp;&nbsp;
					
					<input class="btn btn-success" type="submit" id="find_submit" value="查找" />&nbsp;
					<a class="btn btn-success" onclick="location.href='{pigcms{:U('User/bind_audit_list')}'">重置</a>
				</form>
			</div>
			
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                	<th width="3%" style="text-align:center"><input type="checkbox" class="checkbox_all" style="wdith:20px; height:20px;"></th>
                                    <th width="10%">业主姓名</th>
                                    <th width="10%">业主手机号</th>
                                    <th width="15%">业主地址</th>
                                    <th width="10%">家属姓名</th>
                                    <th width="10%">家属手机号</th>
                                    <th width="15%">申请日期</th>
                                    <th width="5%">绑定关系</th>
                                    <th width="15%">备注</th>
                                    <th width="5%">状态</th>
                                    <th class="button-column" width="20%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$user_list['list']">
                                    <volist name="user_list['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                        	<td> <input type="checkbox" name="delCheckbox[]" style="wdith:20px; height:20px;" value="{pigcms{$vo.pigcms_id}" onclick="return Dcheckbox($(this));"></td>
                                            <td>{pigcms{$vo.parent_name}</td>
                                            <td><div class="tagDiv">{pigcms{$vo.parent_phone}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.address}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.add_time}</div></td>
                                            <td>
												<div class="tagDiv">
													<if condition='$vo["type"] eq 0'>
														房主
													<elseif condition='$vo["type"] eq 1' />
														家属
													<elseif condition='$vo["type"] eq 2' />
														租客
													<elseif condition='$vo["type"] eq 3' />
														更新房主
													</if>
												</div>
											</td>
                                            <td>{pigcms{$vo.memo}</td>
                                            <td>
												<if condition='$vo["status"] eq 0'>
													<div class="shopNameDiv red">禁止</div>
												<elseif condition='$vo["status"] eq 1' />
													<div class="shopNameDiv green">审核通过</div>
												<elseif condition='$vo["status"] eq 2' />
													<div class="shopNameDiv red">审核中</div>
												</if>
											</td>
                                            <td class="button-column">
												<if condition="in_array(105,$house_session['menus'])">
	                                        		<if condition='$vo["status"] eq 1'>
														<if condition='$vo["type"] eq 3'>
															<span class="green">绑定成功<span>
														<else />
															<a style="width: 60px;" class="label label-sm label-info" href="javascript:void(0)" onclick="if(confirm('确认进行绑定,请谨慎操作？')){location.href='{pigcms{:U('bind_edit',array('pigcms_id'=>$vo['pigcms_id'],'no_bind'=>1))}'}">解除绑定</a>
														</if>
													<else />
														<a style="width: 60px;" class="label label-sm label-info" href="javascript:void(0)" onclick="if(confirm('确认进行绑定,请谨慎操作？')){location.href='{pigcms{:U('bind_edit',array('pigcms_id'=>$vo['pigcms_id']))}'}">确认绑定</a>
													</if>													
												</if>
												&nbsp;&nbsp;&nbsp;
												<a style="width: 60px;" class="label label-sm label-info bind_info" title="编辑" href="{pigcms{:U('bind_other',array('pigcms_id'=>$vo['pigcms_id'],'edit'=>1))}">编辑</a>
												
												<if condition="in_array(106,$house_session['menus'])">
												&nbsp;&nbsp;&nbsp;
												<a style="width: 60px;" class="label label-sm label-info" href="javascript:void(0)" onclick="if(confirm('确认进行删除,请谨慎操作？')){location.href='{pigcms{:U('bind_delete',array('pigcms_id'=>$vo['pigcms_id']))}'}">删除</a>											
												</if>											
                                           </td>
                                        </tr>
                                    </volist> 
                                    <if condition="in_array(106,$house_session['menus'])">
                                    <tr>
                                    	<td colspan="14">&nbsp;&nbsp;&nbsp;<button class=" btn delete_class">删除选中</button></td>
                                    </tr>
                                	</if>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="12" >没有家属审核信息。</td></tr>
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
				确定删除家属/租客信息？
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
				请先选择要执行的信息！
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
$('.bind_info').click(function(){
	art.dialog.open($(this).attr('href'),{
		init: function(){
			var iframe = this.iframe.contentWindow;
			window.top.art.dialog.data('iframe_handle_',iframe);
		},
		id: 'handle_',
		title:'编辑家属/租客',
		padding: 0,
		width: 800,
		height: 603,
		lock: true,
		resize: false,
		background:'black',
		button: null,
		fixed: false,
		close: null,
		left: '50%',
		top: '38.2%',
		opacity:'0.4',
		cancel: function () {
            window.location.reload()
        }
	});
	return false;
});

$(".checkbox_all").on('click',function(){
	
	if($(this).is(':checked')){
		$("input[name='delCheckbox[]']").prop("checked",true);  
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
		var url = "{pigcms{:U('User/ajax_del_bind')}";
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
