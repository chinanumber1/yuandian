<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/index')}">业主管理</a>
            </li>
            <li class="active">欠费明细</li>
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
                                    <th width="5%">编号</th>
                                    <th width="5%">姓名</th>
                                    <th width="5%">手机号</th>
                                    <th width="10%">住址</th>
                                    <th width="10%">水费总欠费</th>
                                    <th width="10%">电费总欠费</th>
                                    <th width="10%">燃气费欠费</th>
                                    <th width="10%">停车费欠费</th>
                                    <th width="10%">物业费欠费</th>
                                    <th width="10%">欠费时间</th>
                                    <th width="5%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$user_list">
                                    <volist name="user_list['user_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.usernum}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.address}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.water_price}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.electric_price}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.gas_price}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.park_price}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.property_price}</div></td>
                                            <td><div class="shopNameDiv">{pigcms{$vo.ydate}年{pigcms{$vo.mdate}月</div></td>
											<td><div class="tagDiv"><a href="javascript:void(0)" onclick="if(confirm('确认删除该条信息?')){location.href='{pigcms{:U('pay_one_del',array('pigcms_id'=>$vo['pigcms_id']))}'}">删除</a></div></td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >没有任何欠费。</td></tr>
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
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
function importUser(){
	window.location.href = "{pigcms{:U('User/user_import')}";
}
</script>
<include file="Public:footer"/>