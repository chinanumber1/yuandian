<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/audit_unbind')}">申请解绑</a>
            </li>
            <li class="active">申请解绑列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <div class="row">
                <div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
                    <form method="get" id="find-form">
                        <input type="hidden" name="c" value="User"/>
                        <input type="hidden" name="a" value="audit_unbind"/>
                        <select name="find_type" id="find_type" class="col-sm-1" style="margin-right:10px;height:42px;">
                            <option value="3" <if condition="$_GET['find_type'] eq 3">selected="selected"</if>>手机号</option>
                            <option value="2" <if condition="$_GET['find_type'] eq 2">selected="selected"</if>>姓名</option>
                        </select>
                        <input value="{pigcms{$_GET['find_value']}" class="col-sm-2" placeholder="" name="find_value" id="find_value" type="text" style="margin-right:10px;font-size:18px;height:42px;"/>

                        <select name="status" id="status" class="col-sm-1" style="margin-right:10px;height:42px;">
                            <option value="" selected="selected">请选择</option>
                            <option value="1" <if condition="$_GET['status'] eq 1">selected="selected"</if>>审核中</option>
                            <option value="2" <if condition="$_GET['status'] eq 2">selected="selected"</if>>拒绝解绑</option>
                            <option value="3" <if condition="$_GET['status'] eq 3">selected="selected"</if>>已解绑</option>
                        </select>
                        &nbsp;&nbsp;
                        申请日期：
                        <input type="text" name="begin_time" class="input-text" value="{pigcms{$_GET['begin_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:true,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间"/>-
                        <input type="text" name="end_time" class="input-text" value="{pigcms{$_GET['end_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:true,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间"/>&nbsp;&nbsp;
                        
                        <input class="btn btn-success" type="submit" id="find_submit" value="查找业主" />&nbsp;
                        <a class="btn btn-success" onclick="location.href='{pigcms{:U('User/audit_unbind')}'">重置</a>
                    </form>
                </div>
                
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="10%">申请编号</th> 
                                    <th width="10%">姓名</th>
                                    <th width="10%">手机号码</th>
									<th width="20%">单元/房间</th>
                                    <th width="10%">所属角色</th>
                                    <th width="10%">状态</th>
                                    <th width="15%">申请时间</th>
                                    <th width="15%">操作时间</th>
                                    <th class="button-column" >操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$lists['list']">
                                    <volist name="lists['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.itemid}</div></td>
                                            <td>{pigcms{$vo.name}</td>
                                            <td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
											<td>{pigcms{$vo.address}</td>
                                            <td>
												<div class="tagDiv">
													<if condition='$vo["type"] eq 0'>
														房主
													<elseif condition='$vo["type"] eq 1' />
														家人
													<elseif condition='$vo["type"] eq 2' />
														租客
                                                    <elseif condition='$vo["type"] eq 3' />
														替换房主    
													</if>
												</div>
											</td>
                                            <td>
												<div class="tagDiv">
													<if condition='$vo["status"] eq 1'>
														<span class="red">审核中</span>
													<elseif condition='$vo["status"] eq 2' />
														<span class="red">拒绝解绑</span>
													<elseif condition='$vo["status"] eq 3' />
														<span class="green">已解绑</span>
													</if>
												</div>
											</td>
                                            <td>{pigcms{$vo.addtime|date='Y-m-d H:i:s',###}</td>
                                            <td>{pigcms{$vo.edittime|date='Y-m-d H:i:s',###}</td>
                                            
                                            <td class="button-column">
												<if condition='$vo["status"] neq 3'>
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('User/audit_unbind_edit',array('itemid'=>$vo['itemid']))}">编辑</a>&nbsp;&nbsp;
												</if>
                                                
                                                <if condition="in_array(109,$house_session['menus'])">
												<a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onclick="if(confirm('确认删除该条信息？')){location.href=\'{pigcms{:U('audit_unbind_del',array('itemid'=>$vo['itemid']))}\'}">删除</a>
                                                </if>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="12" >没有任何申请信息</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$lists.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" language="javascript">



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
</script>
<include file="Public:footer"/>
