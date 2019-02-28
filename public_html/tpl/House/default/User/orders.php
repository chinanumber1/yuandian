<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/index')}">业主管理</a>
            </li>
            <li class="active">已缴费列表</li>
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
                    <div class="alert alert-info" style="margin:10px 0;">
                        <b>总金额：{pigcms{$totalmoney|floatval}</b>　
                    </div>
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">订单编号</th>
                                    <th width="5%">收银台订单编号</th>
                                    <th width="5%">缴费项</th>
                                    <th width="5%">已缴金额</th>
                                    <th width="10%">支付时间</th>
									<th width="10%">物业服务周期</th>
									<th width="10%">赠送物业服务时间</th>
									<th width="10%">服务时间</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$order_list">
                                    <volist name="order_list['order_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td>{pigcms{$vo.order_id}</td>
                                            <td><if condition="$vo['cashier_id']">{pigcms{$vo.cashier_id}<else/>--</if></td>
                                            <td><div class="tagDiv">{pigcms{$vo.order_name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.money}</div></td>
                                            <td><div class="shopNameDiv">{pigcms{$vo.time|date='Y-m-d H:i:s',###}</div></td>
											<td><div class="tagDiv"><if condition='$vo["property_month_num"]'>{pigcms{$vo.property_month_num}<else /><span class="red">暂无</span></if></div></td>
											<td><div class="tagDiv"><if condition='$vo["presented_property_month_num"]'>{pigcms{$vo.presented_property_month_num}<else /><span class="red">暂无</span></if></div></td>
											<td><div class="tagDiv"><if condition='$vo["property_time_str"]'>{pigcms{$vo.property_time_str}<else /><span class="red">暂无</span></if></div></td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >您没有任何缴费记录。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$order_list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<include file="Public:footer"/>