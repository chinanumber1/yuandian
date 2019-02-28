<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="javascript:void(0);">收银台</a>
            </li>
            <li class="active">未缴费用</li>
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
                    <p style="font-size: 20px;margin-bottom: 12px;">收银台</p>
                    <li style="margin-bottom:30px;">
                        <label style="margin-right: 100px">编号：{pigcms{$user_info.usernum}</label>
                        <label style="margin-right: 100px">业主姓名：{pigcms{$user_info.name}</label>
                        <label style="margin-right: 100px">手机号：{pigcms{$user_info.phone}</label>
                        <label>地址：{pigcms{$user_info.address}</label>
                    </li>
                    <div class="tabbable">
                        <ul id="myTab" class="nav nav-tabs">
                            <li class="active">
                                <a href="{pigcms{:U('personal_order_list',array('bind_id'=>$pigcms_id))}">未缴费用</a>
                            </li>
                            <li>
                                <a href="{pigcms{:U('history_cashier_order',array('bind_id'=>$pigcms_id))}">历史缴费</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="basicinfo">
                            <if condition="in_array(67,$house_session['menus'])">
                            <a href="javascript:void(0)" url="{pigcms{:U('owner_order_add',array('pigcms_id'=>$pigcms_id))}" class="btn btn-sm btn-success" id="addorder" data-toggle="modal" onclick="addOrder(this)">添加缴费</a>
                            <else/>
                            <button class="btn btn-success disabled" disabled="disabled">添加缴费</button>
                            </if>
                            &nbsp;&nbsp;
                            <if condition="in_array(68,$house_session['menus'])">
                            <a href="#modal-table" class="btn btn-sm btn-success" id="cashier_pay" url="{pigcms{:U('cashier_pay',array('pigcms_id'=>$pigcms_id))}">收款</a>
                            <else/>
                            <button class="btn btn-success disabled" disabled="disabled">收款</button>
                            </if>
                            <div id="shopList" class="grid-view">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%"><input type="checkbox" name="checkall" onclick="checkall()"></th>
                                            <th width="5%">缴费项</th>
                                            <th width="5%">应缴金额</th>
                                            <th width="5%">业主名</th>
                                            <th width="5%">联系方式</th>
                                            <th width="10%">住址</th>
                                            <th width="8%">编号</th>
        									<th width="5%">物业服务周期</th>
        									<th width="8%">赠送物业服务时间</th>
        									<th width="5%">服务时间</th>
                                            <th width="5%">自定义缴费周期</th>
                                            <th width="5%">备注</th>
                                            <th width="5%">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <if condition="$pay_list_order['order_list'] || $pay_list">
                                            <if condition="$pay_list">
                                                <volist name="pay_list" id="vo">
                                                    <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                                        <td><input type="checkbox" name="orderid[]" value="{pigcms{$vo.type}"></td>
                                                        <td>{pigcms{$vo.name}</td>
                                                        <td><label>{pigcms{$vo.money}</label>元</td>
                                                        <td>{pigcms{$now_user_info.name}</td>
                                                        <td>{pigcms{$now_user_info.phone}</td>
                                                        <td>{pigcms{$now_user_info.address}</td>
                                                        <td>{pigcms{$now_user_info.usernum}</td>
                                                        <td>--</td>
                                                        <td class="red">无</td>                                         
                                                        <td style="text-align: center;">--</td>                                            
                                                        <td style="text-align: center;">--</td>
                                                        <td >--</td>
                                                        <td >--</td>
                                                    </tr>
                                                </volist>
                                                <tr>
                                                    <td colspan="12">其他费用</td>
                                                </tr>
                                            </if>
                                            <if condition="$pay_list_order['order_list']">
                                            <volist name="pay_list_order['order_list']" id="vo">
                                                <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                                    <td><input type="checkbox" name="orderid[]" value="{pigcms{$vo.order_type}|{pigcms{$vo.order_id}"></td>
                                                    <td>{pigcms{$vo.order_name}</td>
                                                    <td><label>{pigcms{$vo.money}</label>元</td>
                                                    <td>{pigcms{$vo.username}</td>
                                                    <td>{pigcms{$vo.phone}</td>
                                                    <td>{pigcms{$vo.address}</td>
                                                    <td>{pigcms{$vo.usernum}</td>
        											<td>{pigcms{$vo.property_month_num}个月</td>
        											<if condition='!empty($vo["presented_property_month_num"]) AND ($vo["diy_type"] eq 0)'>
                                                        <td>{pigcms{$vo.presented_property_month_num}个月</td>
                                                    <elseif condition='$vo["diy_type"] eq 1' />
                                                        <td>{pigcms{$vo.diy_content}</td>
                                                    <else />
                                                        <td class="red">无</td>
                                                    </if>                                            
                                                    <td style="text-align: center;">
                                                        <if condition="$vo['order_type'] eq 'custom_payment'">—
                                                        <else/>{pigcms{$vo.property_time_str}</if>
                                                    </td>                                            
                                                    <td style="text-align: center;">
                                                        <if condition="$vo['order_type'] eq 'custom_payment'">{pigcms{$vo.payment_paid_cycle}/周期<else/>—</if>
                                                    </td>
                                                    <td onclick="aaaa('{pigcms{$vo.remarks}')">查看</td>
                                                    <td>
                                                    <if condition="in_array(69,$house_session['menus'])">
                                                    <a style="width: 60px;" class="label label-sm label-info" href="javascript:void(0);" onclick="del_order('{pigcms{$vo.order_id}')">删除</a>
                                                    <else/>
                                                        无
                                                    </if>
                                                    </td>
                                                </tr>
                                                <!-- <tr class="odd">
                                                    <td colspan="16" id="show_count"></td>
                                                </tr> -->
                                            </volist>
                                            <tr><td class="textcenter pagebar" colspan="15">{pigcms{$pay_list_order.pagebar}</td></tr>
                                            </if>
        									<tr class="even">
        										<td colspan="17">
        											本页总金额：<strong style="color: green">{pigcms{$totalmoney}</strong>　
        										</td>
        									</tr>
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
<div style="font-size: 30px; padding: 15px;"></div>
<input type="hidden" name="checkedmoney" value="0">
<div id="cashier_pay_html" class="grid-view" style="display: none;">
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <tr >
                <th style="width: 150px; text-align: right;">收款方式：</th>
                <td>
                    <select name="pay_type_1" id="pay_type_1" style="width: 250px">
                        <option value="1">扫码支付</option>
                        <option value="2">线下支付</option>
                    </select>
                </td>
            </tr>
            <tr class="under_line" style="display: none;">
                <th style="width: 150px; text-align: right;">线下支付方式：</th>
                <td>
                    <select name="pay_type" id="pay_type" style="width: 250px">
                        <option value="0">请选择收款方式</option>
                        <volist name="pay_type_list" id="vo">
                        <option value="{pigcms{$vo.id}">{pigcms{$vo.name}</option>
                        </volist>
                    </select>
                </td>
            </tr>
            <tr >
                <th style="width: 150px; text-align: right;">应缴金额：</th>
                <td><label id="totalmoney"></label>元</td>
            </tr>
            <tr >
                <th style="width: 150px; text-align: right;">实收金额：</th>
                <td><input id="real_money" value="">元</td>
            </tr>
            <tr class="under_line" style="display: none;">
                <th style="width: 150px; text-align: right;">备注：</th>
                <td><textarea id="remarks" style="width: 250px;height: 50px;"></textarea></td>
            </tr>
            <tr class="wx_code" style="display: none;">
                <th style="width: 150px; text-align: right;">微信扫码支付：</th>
                <td>
                   <img id="wx_code" style="width:250px;height:250px;">
                </td>
            </tr>
            <tr >
                <td colspan="2">
                    <button class="btn btn-success" style="margin-left: 38%;" id="go_pay">生成二维码</button>
                </td>
            </tr>
        </tbody>
        <input type="hidden" id="orderids" value="">
    </table>
    <!-- <img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id={pigcms{$now_order['order_id']+700000000}" style="width:250px;height:250px;"/> -->
    <input type="hidden" name="pigcms_id" value="{pigcms{$vo.pigcms_id}">
</div>
<script type="text/javascript" src="{pigcms{$static_path}js/index.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
    var addbtn = [
        {
            name:'添加',
            callback:function () {
                var iframe = this.iframe.contentWindow;
                if (iframe.document.body) {
                    var submits=iframe.document.getElementById('dosubmit');
                    submits.click();
                    var that = this;
                    setTimeout(function(){
                        that.close();
                        var win = art.dialog.open.origin;//来源页面  
                        win.location.reload();  
                    }, 500); 
                    return false;
                }else{
                    return false;
                }
            },
            focus:true
        },
        {name:'关闭',
            callback:function () {
               window.location.reload(); 
            }}
    ];
    function addOrder(obj){
        var url = $(obj).attr('url');
        art.dialog.open(url, {
            title: "添加缴费",
            lock: true,
            width: 800,
            height: 600,
            button:addbtn,
        },true);
    }
    
    function del_order(orderid){
        layer.confirm('确定删除该订单信息吗？', {
            btn: ['确定', '取消'] //可以无限个按钮
            ,
        }, function(index, layero){
            $.post("{pigcms{:U('del_pay_order')}",{order_id:orderid},function(result){
               if (result.status>0) {
                    alert(result.msg);return false;
               } else {
                    window.location.reload();
               }
            },'json');

        }, function(index){
          //按钮【按钮二】的回调
        });
        
    }
    function aaaa(content){
        if(content==''){
            content = '<span style=" color:red; padding:38%;">暂无数据</span>';
        }
        layer.open({
            type: 1,
            area: ['420px', '240px'],
            content: '<div style=" font-size: 20px;  padding: 15px;">'+String(content)+'</div>'
        });
    }
    function checkall(){
        if($('input[name=checkall]').attr('checked')=='checked'){
            $("input[name='orderid[]']").attr('checked','true');
        }else{
            $("input[name='orderid[]']").removeAttr('checked');
        }
        
    }

    $('#pay_type_1').change(function(){
        if ($(this).val()=='1') {
            $('.under_line').hide();
            $('.wx_code').hide();
            $('#go_pay').html('生成二维码');
        } else if($(this).val()=='2'){
            $('.under_line').show();
            $('.wx_code').hide();
            $('#go_pay').html('确认收款');
        }

    })

    //两小数相加解决精度出现的问题
    var numAdd=function (num1, num2) {//要相加的两个数
        var baseNum, baseNum1, baseNum2;
        try {
            baseNum1 = num1.toString().split(".")[1].length;
        } catch (e) {
            baseNum1 = 0;
        }
        try {
            baseNum2 = num2.toString().split(".")[1].length;
        } catch (e) {
            baseNum2 = 0;
        }
        baseNum = Math.pow(10, Math.max(baseNum1, baseNum2));
        return (num1 * baseNum + num2 * baseNum) / baseNum;
    };

    $('#cashier_pay').click(function(){
        //获得选中费用
        var ids='',url;
        var totalmoney=0;
        $("input[name='orderid[]']:checkbox").each(function(){
            if ('checked' == $(this).attr("checked")) { 
                ids += $(this).val()+','; 
                // totalmoney += $(this).parent('td').siblings().children('label').text()*1;
                totalmoney = numAdd(totalmoney,$(this).parent('td').siblings().children('label').text()*1);
            }
        })
        $('#totalmoney').html(totalmoney);
        $('#real_money').val(totalmoney);
        $('#orderids').val(ids);
        console.log(remarks);
        if (ids=='') {
            alert('请选择要收款项');
            return false;
        }
        url = $(this).attr('url');
        url += '&orderids='+ids;

        art.dialog({
            content: document.getElementById('cashier_pay_html'),
            id: 'handle',
            title:'收款',
            padding: 0,
            width: 500,
            height: 350,
            lock: true,
            resize: false,
            background:'black',
            fixed: false,
            button: false,
            left: '50%',
            top: '38.2%',
            opacity:'0.4',
            cancel:function(){
                window.location.reload();
            }
        });
        return false;
    })

$('#go_pay').click(function(){
    var ids = $('#orderids').val();
    var remarks = $('#remarks').val();
    var pay_type_1 = $('#pay_type_1').val();
    var pay_type = $('#pay_type').val();
    var real_money = $('#real_money').val();
    $('#real_money').attr('disabled','disabled');
    $('#pay_type_1').attr('disabled','disabled');
    $('#pay_type_1').css({"cursor":"not-allowed"});
    if (pay_type_1=='1') {
        //线上扫码支付
        $('#go_pay').hide();
    }
    if (pay_type_1=='2') {
        if (pay_type=='0') {
            alert('请选择收款方式');
            return false;
        }
    }
    $.post("{pigcms{:U('ajax_cashier_pay',array('pigcms_id'=>$pigcms_id))}",{orderids:ids,is_online:pay_type_1,pay_type:pay_type,remarks:remarks,real_money:real_money},function(result){
        console.log(result)
       if (result.status>0) {
            alert(result.msg);return false;
       } else if (result.status==0){
            // window.location.reload();
            if (pay_type_1=='1') {
                //线上扫码支付
                // console.log(result)
                if (result.data) {
                    var qrcode_id = result.data.cashier_id*1 + 4200000000;
                    $('#wx_code').prop('src',"{pigcms{$config.site_url}/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id="+qrcode_id+"}");

                    $('.wx_code').show();
                }else{
                    window.location.href="{pigcms{:U('history_cashier_order',array('bind_id'=>$pigcms_id))}";
                }
            }
            if (pay_type_1=='2') {
                window.location.href="{pigcms{:U('history_cashier_order',array('bind_id'=>$pigcms_id))}";
            }
       }
    },'json');
})

</script>
<include file="Public:footer"/>
