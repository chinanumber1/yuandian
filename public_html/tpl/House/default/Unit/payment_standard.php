<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/index')}">物业管理</a>
            </li>
            <li class="active">收费标准</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        	<button class="btn btn-success" onclick="importAdd()">添加收费标准</button>
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="">收费模式</th>
                                    <th width="">计量方式</th>
                                    <th width="">收费金额</th>
                                    <th width="">收费周期</th>
                                    <th width="">周期上限</th>
                                    <th width="">收费图标</th>
                                    <th class="button-column" width="">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$standard_list">
                                    <volist name="standard_list" id="vo">
                                        <tr>
                                            <td><div class="tagDiv"><if condition="$vo.pay_type eq 1">固定费用<else/>按单价*数量</if></div></td>
                                            <td><div class="tagDiv"><if condition="$vo['metering_mode'] eq ''">无<else/>{pigcms{$vo.metering_mode}</if></div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.pay_money}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.pay_cycle} ({pigcms{$cycle_type[$vo['cycle_type']]})&nbsp;/&nbsp;周期</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.max_cycle}&nbsp;周期</div></td>
                                            <td><div class="tagDiv"><img src="{pigcms{$vo.pay_icon}" style="width: 50px; height: 50px;"></div></td>
                                            <td class="button-column">
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('payment_standard_edit',array('payment_id'=>$vo['payment_id'],'standard_id'=>$vo['standard_id']))}">编辑</a> &nbsp;
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('删除收费标准后，所有使用此标准的已收费数据，客户资料中的收费标准都将删除，确认要删除吗？')){location.href='{pigcms{:U('payment_standard_del',array('standard_id'=>$vo['standard_id'],'payment_id'=>$vo['payment_id']))}'}">删除</a>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="8" >没有任何收费标准。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
    function importAdd(){
    	window.location.href = "{pigcms{:U('payment_standard_add',array('payment_id'=>$_GET['payment_id']))}";
    }
</script>
<include file="Public:footer"/>
