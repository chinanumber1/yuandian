<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="javascript:void(0);">收费管理</a>
            </li>
            <li class="active">已缴账单</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <style>
                .ace-file-input a {display:none;}
            </style>

            <div class="row">
                <table class="search_table" width="100%">
                    <tr>
                        <td>
                            <form action="{pigcms{:U('cashier_paid_list')}" method="get">
                                <input type="hidden" name="c" value="Cashier"/>
                                <input type="hidden" name="a" value="cashier_paid_list"/>
                                
                                筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"  style="height:42px"/>&nbsp;&nbsp;
                                <select name="searchtype"  style="height:42px">
                                    <option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>联系电话</option> 
                                    <option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>姓名</option>                              
                                </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                支付方式: <select name="searchstatus" style="height:42px">
                                    <option value="0" <if condition="$_GET['searchstatus'] eq '0'">selected="selected"</if>>所有</option>
                                    <option value="1" <if condition="$_GET['searchstatus'] eq '1'">selected="selected"</if>>在线支付</option> 
                                    <option value="2" <if condition="$_GET['searchstatus'] eq '2'">selected="selected"</if>>线下支付</option>
                                </select>
                                时间筛选：
                                <input type="text" name="begin_time" class="input-text" value="{pigcms{$_GET['begin_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:true,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间"/>&nbsp;&nbsp;-&nbsp;&nbsp;
                                <input type="text" name="end_time" class="input-text" value="{pigcms{$_GET['end_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:true,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间"/>&nbsp;&nbsp;
                                <button class="btn btn-success" type="submit">查询</button>&nbsp;&nbsp;
                                <button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('cashier_paid_list')}'">重置</button>
                            </form>
                        </td>
                    </tr>
                </table>
                <div class="col-xs-12">
                        <div class="tab-pane active" id="txtstore">
                            <div id="shopList" class="grid-view">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">收银台订单编号</th>
                                            <th width="5%">业主名</th>
                                            <th width="5%">联系方式</th>
                                            <th width="10%">住址</th>
                                            <th width="8%">编号</th>
                                            <th width="5%">已缴金额</th>
                                            <th width="5%">支付方式</th>
                                            <th width="8%">支付时间</th>
                                            <th width="5%">备注</th>
                                            <th width="5%">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <if condition="$paid_list['order_list']">
                                            <volist name="paid_list['order_list']" id="vo">
                                                <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                                    <td>{pigcms{$vo.cashier_id}</td>
                                                    <td><if condition='$vo["username"]'>{pigcms{$vo.username}<else/>--</if></td>
                                                    <td><if condition='$vo["phone"]'>{pigcms{$vo.phone}<else/>--</if></td>
                                                    <td>{pigcms{$vo.address}</td>
                                                    <td>{pigcms{$vo.usernum}</td>  
                                                    <td><label>{pigcms{$vo.money}</label>元</td>
                                                    <td>{pigcms{$vo.pay_type_name}</td>
                                                    <td>{pigcms{$vo.pay_time|date='Y-m-d H:i:s',###}</td> 
                                                    <td>{pigcms{$vo.remarks}</td>
                                                    <td>
                                                        <a href="javascript:void(0);" url="{pigcms{:U('cashier_detail',array('cashier_id'=>$vo['cashier_id']))}" onclick="cashier_detail(this)">明细</a>

                                                        <if condition="in_array(84,$house_session['menus'])">
                                                         | <a href="javascript:void(0);" onclick="print_select(this)" cid="{pigcms{$vo.cashier_id}">打印</a> 
                                                        </if>
                                                        <!-- | <a href="javascript:void(0);" onclick="">删除</a> -->
                                                    </td>
                                                </tr>
                                            </volist>
                                            <tr class="even">
                                                <td colspan="17">
                                                    本页总金额：<strong style="color: green">{pigcms{$paid_list.total}</strong>　<br/> 
                                                    总金额：<strong style="color: green">{pigcms{$paid_list.totalMoney.totalMoney}</strong>　<br/>
                                                </td>
                                            </tr>
                                            <tr class="odd">
                                                <td colspan="16" id="show_count"></td>
                                            </tr>
                                            <tr><td class="textcenter pagebar" colspan="10">{pigcms{$paid_list.pagebar}</td></tr>
                                        <else />
                                            <tr class="odd"><td class="button-column" colspan="15" >没有任何数据。</td></tr>
                                        </if>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="print_template" style="display: none;">
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <tr >
                <th>打印模板：</th>
                <td>
                    <select name="template" id="template">
                        <option value="0">请选择模板</option>
                        <volist name="print_template" id="vo">
                            <option value="{pigcms{$vo.template_id}">{pigcms{$vo.title}</option>
                        </volist>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div style="font-size: 30px; padding: 15px;"></div>
<input type="hidden" name="checkedmoney" value="0">
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
    function cashier_detail(obj){
        url = $(obj).attr('url');

        art.dialog.open(url,  
        {  
            id: "mydialog",  
            width: "80%",  
            height: 500,  
            title: "订单详情",//如果不想要弹窗的标题，可以直接赋值为false  
            lock: true,  
            button:[{name:'关闭'}],
        });  
    }

    function print_select(obj){
        var cashier_id = $(obj).attr('cid');
        var post_url = '{pigcms{:U('print_start')}';
        art.dialog({
            content: document.getElementById('print_template'),
            id: 'handle',
            title:'选择打印模板',
            padding: 0,
            width: 260,
            height: 120,
            lock: true,
            resize: false,
            background:'black',
            fixed: false,
            okVal:'确定',
            cancelVal:'取消',
            left: '50%',
            top: '38.2%',
            opacity:'0.4',
            ok:function (argument) {
                var template_id = $('#template').val();
                if (!template_id) {
                    alert('请选择打印模板');
                    return false;
                }
                art.dialog.open(post_url+"&cashier_id="+cashier_id+"&template_id="+template_id,  
                {  
                    id: "mydialog",  
                    width: 900,  
                    height: 600,  
                    title: "打印预览",//如果不想要弹窗的标题，可以直接赋值为false  
                    lock: true,  
                    button:[{name:'关闭'}],
                });  
                // window.open("post_url+"&cashier_id="+cashier_id+"&template_id="+template_id);
            }
        });

    }
</script>
<include file="Public:footer"/>
