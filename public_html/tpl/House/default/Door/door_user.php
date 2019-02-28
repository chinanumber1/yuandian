<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-gear gear-icon"></i>
                <a href="{pigcms{:U('door_list')}">门禁设置</a>
            </li>
            <li class="active">用户列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        	<button class="btn btn-success" onclick="addUser()" <if condition="!in_array(229,$house_session['menus'])">disabled="disabled"</if>>新增用户</button>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                	<th width="5%">No.</th>
                                	<th width="8%">用户</th>
                                	<th width="20%">地址</th>
                                	<th width="8%">门牌</th>
                                    <th width="10%">开始时间</th>
                                    <th width="10%">结束时间</th>
                                   	<th width="5%">状态</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$aDoor">
                                    <volist name="aDoor" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                        	<td><div class="tagDiv">{pigcms{$vo.pigcms_id}</div></td>
                                            <td style="word-break:break-all"><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                            <td style="word-break:break-all"><div class="tagDiv">{pigcms{$vo.address}</div></td>
                                            <td style="word-break:break-all"><div class="tagDiv">{pigcms{$vo.room_addrss}</div></td>
											<td><div class="shopNameDiv">{pigcms{$vo.start_time|date='Y-m-d',###}</div></td>
                                            <if condition="$vo.end_time eq 0">
                                            	<td><div class="shopNameDiv">不过期</div></td>
                                            <else />
                                            <td><div class="shopNameDiv">{pigcms{$vo.end_time|date='Y-m-d',###}</div></td>
                                            </if>
                                            <if condition="$vo.status eq 1">
                                            	<td><div class="tagDiv" style="color:green;">启用</div></td>
                                            <elseif condition="$vo.status eq 2" />
                                            	<td><div class="tagDiv" style="color:red;">禁用</div></td>
                                            </if>
                                            <td class="button-column">
                                                <if condition="in_array(231,$house_session['menus'])">
                                            	<a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info" title="删除用户" href="{pigcms{:U('door_del_user',array('pigcms_id'=>$vo['pigcms_id']))}">删除用户</a>
                                                </if>
                                                <a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info" title="修改用户" href="{pigcms{:U('door_eidt_user',array('pigcms_id'=>$vo['pigcms_id'],'door_id'=>$door_id))}">修改用户</a>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >暂无数据。</td></tr>
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
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
function addUser(){
	window.location.href = "{pigcms{:U('door_user_add',array('door_id'=>$door_id))}";
}
</script>
<include file="Public:footer"/>
