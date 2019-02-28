<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/parking_management')}">车位管理</a>
            </li>
            <li class="active">车位详情</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <style>
                .ace-file-input a {display:none;}
                .btn-success{
                    margin-left:13px;
                }
                .select{
                    width:65px;
                    height: 30px;
                    margin-left:20px;
                }
                .input-text{
                    margin-top:15px;
                }
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <!-- <div style="font-size:16px;">数据详情:</div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">车位编号</th>
                                    <th width="10%">所属小区</th>
                                    <th width="5%">车位号</th>
                                    <th width="5%">车位类型</th>
                                    <th width="5%">车位状态</th>
                                    <th width="5%">车位面积(平方米)</th>
									<th width="8%">备注</th>
                                    <th width="5%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$info_list">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td>{pigcms{$info_list.position_id}</td>
                                            <td>{pigcms{$house_session.village_name}</td>
                                            <td>{pigcms{$info_list.position_num}</td>
                                            <td>
                                                <if condition="$info_list.position_type eq '1'">
                                                    <span>产权车位</span>
                                                <elseif condition="$info_list.position_type eq '2'"/>
                                                    <span>租赁车位</span>
                                                <else/>
                                                    <span>临停车位</span>    
                                                </if>
                                            </td>
                                            <td>
                                                <if condition="$info_list.position_status eq '1'">
                                                    <span style="color:red;">自用</span>
                                                <elseif condition="$info_list.position_status eq '2'"/>
                                                    <span style="color:green;">空置</span>
                                                <elseif condition="$info_list.position_status eq '3'"/>
                                                    <span style="color:#1F71D7;">出售</span>
                                                <else/>
                                                    <span style="color:#080C32;">出租</span>    
                                                </if>
                                            </td>
                                            <td>{pigcms{$info_list.position_area}</td>
                                            <td>{pigcms{$info_list.position_note}</td>
											<td><a href="{pigcms{:U('parking_edit',array('position_id'=>$info_list[position_id]))}">修改</a></td>
                                        </tr>
                                <else />
                                    <tr class="odd"><td class="button-column" colspan="15" >没有任何数据。</td></tr>
								</if>
                            </tbody>
                        </table> -->
                        <div style="font-size:16px; margin-bottom: 12px;">相关住户:</div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">姓名</th>
                                    <th width="10%">所属小区</th>
                                    <th width="5%">楼宇</th>
                                    <th width="5%">单元</th>
                                    <th width="5%">房号</th>
                                    <th width="5%">手机</th>
                                    <th width="5%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$data_list">
                                    <volist name="data_list" id="vol">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                                <td>{pigcms{$vol.name}</td>
                                                <td>{pigcms{$house_session.village_name}</td>
                                                <td>{pigcms{$vol.floor_name}</td>
                                                <td>{pigcms{$vol.floor_layer}</td>
                                                <td>{pigcms{$vol.room_addrss}</td>
                                                <td>{pigcms{$vol.phone}</td>
                                                <td class="untie">
                                                    <if condition="in_array(254,$house_session['menus'])">
                                                    <a class="label label-sm label-info" title="解绑" href="javascript:void(0)" onclick="untie(this,{pigcms{$vol.bind_id})">解绑</a>
                                                    </if>
                                                </td>
                                        </tr>
                                        </volist>
                                <else />
                                    <tr class="odd"><td class="button-column" colspan="15" >没有任何数据。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$data_list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function untie(obj,bind_id){
        if(!bind_id || bind_id==''){
            layer.msg('请选择您要解绑的用户!',{icon: 2});
            setTimeout(location.reload(),3000);
        }
        var url = "{pigcms{:U('unbind_position')}";

        layer.confirm('确认解绑车位？', {
          btn: ['确定','取消'] //按钮
        }, function(){
            $.post(url,{'bind_id':bind_id},function(data){
                if(data.code == 1){
                    layer.msg(data.msg,{icon: 1},function(){
                        location.reload();
                    });
                }
                if(data.code == 2){
                    layer.msg(data.msg,{icon: 2},function(){
                        location.reload();
                    });
                }
            },'json');
        }, function(){
          
        });  

        
    }
</script>
<include file="Public:footer"/>
