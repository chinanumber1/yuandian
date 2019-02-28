<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('import_village')}">物业管理</a>
            </li>
            <li class="active">房间管理</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            
            <div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
                <form action="{pigcms{:U('Unit/import_village')}" method="get" >
                    <input type="hidden" name="c" value="Unit"/>
                    <input type="hidden" name="a" value="import_village"/>
                    <if condition="in_array(38,$house_session['menus'])">
                    <a href="{pigcms{:U('import_village_add')}" class="btn btn-success fl">导入数据</a>&nbsp;
                    <else/>
                    <button class="btn btn-success disabled" disabled="disabled">导入数据</button>
                    </if>
                    <select name="status" class="" style=" margin-right:10px;height:42px;">
                        <option value="1" <if condition="$_GET['status'] eq 1">selected="selected"</if>>空置</option>
                        <option value="2" <if condition="$_GET['status'] eq 2">selected="selected"</if>>审核中</option>
                        <option value="3" <if condition="$_GET['status'] eq 3">selected="selected"</if>>已绑定业主</option>
                        <option value="0" <if condition="$_GET['status'] eq '0'">selected="selected"</if>>关闭</option>
                    </select>
                    <input class="btn btn-success" type="submit" id="find_submit" value="查找业主" />&nbsp;
                    <a class="btn btn-success" onclick="location.href='{pigcms{:U('Unit/import_village')}'">重置</a>
                </form>
                <!-- <button class="btn btn-success fr" onclick="importAdd()">导入数据</button> -->
            </div>

        	
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">选择房间</th>
                                    <th width="10%">楼层编号</th>
                                    <th width="10%">楼号</th>
                                    <th width="20%">单元名称</th>
									<th width="10%">层号</th>
									<th width="10%">房间号</th>
                                    <th width="5%">房屋面积</th>
                                    <th width="10%">添加时间</th>
                                    <th width="10%">状态</th>
                                    <th class="button-column" width="20%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$result['list']">
                                    <volist name="result['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td>
                                                <div class="tagDiv">
                                                    <if condition="$vo['status'] eq 1">
                                                        <input name="is_remind" value="{pigcms{$vo.pigcms_id}" type="checkbox">
                                                    <else/>
                                                        <input name="is_remind_false" value="{pigcms{$vo.pigcms_id}" disabled="disabled" type="checkbox">
                                                    </if>
                                                    
                                                </div>
                                            </td>
                                            <td><div class="tagDiv">{pigcms{$vo.pigcms_id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.floor_layer}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.floor_name}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.layer}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.room}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.housesize}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</div></td>
                                            <td><div class="tagDiv">
												<if condition='$vo["status"] eq 1'>
													<span class="green">空置</span>
												<elseif condition='$vo["status"] eq 2' />
													 <span class="green">审核中</span>
												<elseif condition='$vo["status"] eq 3' />
													 <span class="green">已绑定业主 <if condition="$vo['uid']==0 && $vo['name']!='' && $vo['phone']!=''">&nbsp;<span class="red">[未注册]</span></if></span>
												<else />
													<span class="red">关闭</span>
												</if>
											</div></td>
                                            <td class="button-column">
											
											
											<a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('import_village_edit',array('id'=>$vo['pigcms_id']))}">编辑</a> 
                                            <if condition="in_array(40,$house_session['menus'])">
											<a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认删除该条信息？')){location.href='{pigcms{:U('import_village_del',array('id'=>$vo['pigcms_id']))}'}">删除</a>
                                            </if>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="12" >没有任何信息。</td></tr>
                                </if>
                            </tbody>
                        </table>

                    <input type="button" value="全选" class="btn" id="selectAll">&nbsp;
                    <input type="button" value="全不选" class="btn" id="unSelect">  &nbsp;
                    <input type="button" value="反选" class="btn" id="reverse">  &nbsp;
                    <input type="button" value="删除" class="btn" id="getValue">&nbsp;
                    <div style="float: right;">
                        <input type="text" name="pageNumber" id="pageNumber" onkeyup="value=value.replace(/[^\d]/g,'')" value="" class="input-sm" style="width: 50px;">&nbsp;
                        <input type="button" class="btn btn-sm btn-default" value="跳转" onclick='flipPage()' class="btn" id="uuu">
                    </div>
                    
    
                        {pigcms{$result.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>

<script>

    // 翻页
    function flipPage(){
        if($("#pageNumber").val() > parseInt("{pigcms{$result.pageCount}")){
            alert('请输入正确的页码。');
        }else{
            location.href="{pigcms{:U('import_village')}&page="+$("#pageNumber").val();
        }
    }

    $("#selectAll").click(function () {
       $("input[name='is_remind']").each(function () {
            $(this).attr("checked", true);
        }); 
    });

    $("#unSelect").click(function () {  
       $("input[name='is_remind']").each(function () {
            $(this).attr("checked", false);
        });
    });

    $("#reverse").click(function () { 
        $("input[name='is_remind']").each(function () {
            this.checked=!this.checked;
        });
    });

    $("#getValue").click(function () { 
        if(confirm('确认删除选中的信息？')){
            var pigcms_id = '';
            $("input[name='is_remind']").each(function () {
                if($(this).attr("checked")){
                    pigcms_id += $(this).val()+',';
                }
            });

            pigcms_id=pigcms_id.substring(0,pigcms_id.length-1);

            if(!pigcms_id){
                alert('请选择您要删除的信息。');
                return false;
            }

            var del_url = "{pigcms{:U('import_village_del_many')}";

            $.post(del_url,{'pigcms_id':pigcms_id},function(data){
                if(data.status == 1){
                    alert(data.msg);
                    location.href = location.href;
                }else{
                    alert(data.msg)
                }
            },'json');
        }
    });


    $(".transfrom-room").on('click' , function(){
    	
    	if(confirm("您确定将当前小区业主的房间信息导入进来吗？")){
    			
			art.dialog.open("{pigcms{:U('updata_old_village_room_info',array('is_true_old_user'=>$is_old_data))}",{lock:true,title:'导入房间',width:760,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
    		
    		//location.href="{pigcms{:U('updata_old_village_room_info',array('is_true_old_user'=>$is_old_data))}";
    		
    	}else{
    		return flse;	
    	}
    		
    });

    // function importAdd(){
    // 	window.location.href = "{pigcms{:U('import_village_add')}";
    // }

    function importUserDetail(){
    	window.location.href = "{pigcms{:U('User/detail_import')}";
    }
</script>
<include file="Public:footer"/>
