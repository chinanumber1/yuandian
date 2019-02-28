<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-shopping-cart"></i>
                <a href="{pigcms{:U('village_order')}">功能库</a>
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
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">缴费项</th>
                                    <th width="5%">已缴金额</th>
                                    <th width="10%">支付时间</th>
                                    <th width="10%">业主名</th>
                                    <th width="10%">联系方式</th>
                                    <th width="10%">住址</th>
                                    <th width="5%">编号</th>
                                    <th width="10%">对账状态</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$order_list">
                                    <volist name="order_list['order_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.order_name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.money}</div></td>
                                            <td><div class="shopNameDiv">{pigcms{$vo.time|date='Y-m-d H:i:s',###}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.username}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.address}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.usernum}</div></td>
                                            <td><if condition="$vo['is_pay_bill'] eq 0"><strong style="color: red">未对账</strong><else /><strong style="color: green"><strong type="color:green">已对账</strong></if></td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >暂时没有任何缴费记录。</td></tr>
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