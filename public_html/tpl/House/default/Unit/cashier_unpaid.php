<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="javascript:void(0);">收费管理</a>
            </li>
            <li class="active">未缴账单</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <style>
                .ace-file-input a {display:none;}
                .div-intro{ float:right; margin-top:20px}
                .div-intro-detail{width:10px; height:10px; background-color:red; float:left; margin-top:5px}
                .div-intro span{ float:left; margin-left:5px;}
            </style>
            <div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
            <table class="search_table" width="100%">
                    <tr>
                        <td>
                            <form action="{pigcms{:U('cashier_unpaid')}" method="get">
                                <input type="hidden" name="c" value="Unit"/>
                                <input type="hidden" name="a" value="cashier_unpaid"/>
                                
                                筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"  style="height:42px"/>&nbsp;&nbsp;
                                <select name="searchtype"  style="height:42px">
                                    <option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>联系电话</option> 
                                    <option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>姓名</option>
                                </select>&nbsp;&nbsp;
                                是否绑定微信：
                                <select name="is_bind_weixin" id="is_bind_weixin"  style="height:42px;">
                                    <option value="0" <if condition="$_GET['is_bind_weixin'] eq 0">selected="selected"</if>>全部</option>
                                    <option value="1" <if condition="$_GET['is_bind_weixin'] eq 1">selected="selected"</if>>是</option>
                                    <option value="2" <if condition="$_GET['is_bind_weixin'] eq 2">selected="selected"</if>>否</option>
                                </select>
                                &nbsp;&nbsp;
                                <button class="btn btn-success" type="submit">查询</button>&nbsp;&nbsp;
                                <button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('cashier_unpaid')}'">重置</button>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
            <button class="btn btn-success" onclick="send_weixin(1)" <if condition="!in_array(264,$house_session['menus'])">disabled="disabled"</if>>全部发送微信通知</button>&nbsp;
            <button class="btn btn-success" onclick="send_weixin(2)" <if condition="!in_array(265,$house_session['menus'])">disabled="disabled"</if>>群发微信通知</button>&nbsp;
            <button class="btn btn-success" onclick="location.href='{pigcms{:U('cashier_unpaid_export',$_GET)}'" <if condition="!in_array(267,$house_session['menus'])">disabled="disabled"</if>>导出账单</button>
           
            <div class="div-intro">
                <div class="div-intro-detail" style="background:orange"></div>
                <span>业主暂未绑定微信（注：暂时无法收到缴费提醒）</span>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-info" style="margin:10px 0;">
                        固定统计不随筛选项改变：
                        <b>水费：{pigcms{$total.water_money|floatval}</b>　
                        <b>电费：{pigcms{$total.electric_money|floatval}</b>　
                        <b>燃气费：{pigcms{$total.gas_money|floatval}</b>　
                        <b>停车费：{pigcms{$total.park_money|floatval}</b>　　
                        <b>物业费：{pigcms{$total.property_money|floatval}</b>　
                        <b>自定义缴费项欠费汇总：{pigcms{$total.cunstom_money|floatval}</b>　
                        <b>合计：{pigcms{$total.total_money|floatval}</b>　
                    </div>
                        <div class="tab-pane active" id="txtstore">
                            <div id="shopList" class="grid-view">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="3%" style="text-align:center"><input type="checkbox" class="checkbox_all" style="wdith:20px; height:20px;"></th>
                                            <th width="5%">业主ID</th> 
                                            <th width="5%">业主名</th>
                                            <th width="5%">联系方式</th>
                                            <th width="10%">住址</th>
                                            <th width="8%">编号</th>
                                            <!-- <th width="5%">水费</th>
                                            <th width="5%">电费</th>
                                            <th width="8%">燃气费</th>
                                            <th width="5%">停车费</th> -->
                                            <th width="8%">合计</th>
                                            <th width="8%">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <if condition="$list['list']">
                                            <volist name="list['list']" id="vo">
                                                <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                                    <td align="center"><input type="checkbox" name="checkbox_one[]" style="wdith:20px; height:20px;" value="{pigcms{$vo.pigcms_id}" onclick="return Dcheckbox($(this));"></td>
                                                    <td>{pigcms{$vo.pigcms_id}</td>
                                                    <td>
                                                        <if condition='$vo["name"]'>
                                                            <if condition='$vo["openid"]'><div class="tagDiv">{pigcms{$vo.name}</div><else /><div class="tagDiv" style="color:orange">{pigcms{$vo.name}</div></if>
                                                        <else/>
                                                        --
                                                        </if>
                                                    </td>
                                                    <td><if condition='$vo["phone"]'>{pigcms{$vo.phone}<else/>--</if></td>
                                                    <td>{pigcms{$vo.address}</td>
                                                    <td>{pigcms{$vo.usernum}</td>  
                                                   <!--  <td>{pigcms{$vo.water_price}元</td>
                                                    <td>{pigcms{$vo.electric_price}元</td>
                                                    <td>{pigcms{$vo.gas_price}元</td>
                                                    <td>{pigcms{$vo.park_price}元</td> -->
                                                    <td style="color:red;">{pigcms{$vo.total}元</td>
                                                    <td> <a href="javascript:void(0);" class="label label-info detail" pid="{pigcms{$vo.pigcms_id}">明细</a>&nbsp;
                                                        <if condition="in_array(266,$house_session['menus'])">
                                                        <a class="label label-sm label-info" href="javascript:void(0);" onclick="send_weixin(3,this)" pid="{pigcms{$vo.pigcms_id}">发送微信通知</a>&nbsp;
                                                        </if>
                                                        <if condition="in_array(66,$house_session['menus'])">
                                                        <!-- <a  href="{pigcms{:U('personal_order_list',array('bind_id'=>$vo['pigcms_id']))}" style="width: 60px;" class="label label-sm label-info" title="去收款">去收款</a> -->
                                                        </if>

                                                    </td>
                                                </tr>
                                            </volist>
                                            <tr><td class="textcenter pagebar" colspan="11">{pigcms{$list.pagebar}</td></tr>
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
<div id="fee_detail" class="grid-view" style="display: none;">
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <tr >
                <th style="width: 190px;">水费：</th>
                <td style="width: 190px;"><label id="water_price"></label>&nbsp;元</td>
            </tr>
            <tr >
                <th style="width: 190px;">电费：</th>
                <td style="width: 190px;"><label id="electric_price"></label>&nbsp;元</td>
            </tr>
            <tr class="under_line">
                <th style="width: 190px;">燃气费：</th>
                <td style="width: 190px;"><label id="gas_price"></label>&nbsp;元</td>
            </tr>
            <tr class="under_line" >
                <th style="width: 190px;">停车费：</th>
                <td style="width: 190px;"><label id="park_price"></label>&nbsp;元</td>
            </tr>
            <tr class="under_line" >
                <th style="width: 190px;">物业费：</th>
                <td style="width: 190px;"><label id="property_price"></label>&nbsp;元</td>
            </tr>
            <tr class="under_line" >
                <th style="width: 190px;">自定义缴费项欠费汇总：</th>
                <td style="width: 190px;"><label id="cunstom_money"></label>&nbsp;元</td>
            </tr>
        </tbody>
    </table>
</div>
<div id="send_user_type_div" class="grid-view" style="display: none;">
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <tr >
                <th style="width: 150px; text-align: right;">发送给：</th>
                <td>
                    <select name="send_user_type" id="send_user_type" style="width: 250px">
                        <option value="1">仅业主</option>
                        <option value="2">业主和家属</option>
                        <option value="3">仅家属</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- <img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id={pigcms{$now_order['order_id']+700000000}" style="width:250px;height:250px;"/> -->
    <input type="hidden" name="pigcms_id" value="{pigcms{$vo.pigcms_id}">
</div>
<input type="hidden" name="checkedmoney" value="0">
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
    $(".checkbox_all").on('click',function(){
    
    if($(this).is(':checked')){
        $("input[name='checkbox_one[]']").prop("checked",true);  
    }else{
        $("input[name='checkbox_one[]']").prop("checked",false);     
    }   
        
});
    function Dcheckbox(e){
        var n=0;
        var len = $("input[name='checkbox_one[]']").length;
        for(var i=0;i<len;i++){
            if($("input[name='checkbox_one[]']")[i].checked) n++;        
        }
        if(n==len){
            $(".checkbox_all").prop("checked",true); 
        }else{
            $(".checkbox_all").prop("checked",false); 
        }
            
    }

     $('.detail').click(function(){
        //获得选中费用
        var pigcms_id = $(this).attr("pid");
        $.post("{pigcms{:U('Cashier/ajax_cashier_unpaid_detail')}",{pigcms_id:pigcms_id},function(result){
           if (result.status>0) {
                alert(result.msg);return false;
           } else if (result.status==0){
                $('#water_price').html(result.data.water_price);
                $('#electric_price').html(result.data.electric_price);
                $('#gas_price').html(result.data.gas_price);
                $('#park_price').html(result.data.park_price);
                $('#property_price').html(result.data.property_price);
                $('#cunstom_money').html(result.data.cunstom_money);
           }
        },'json');
        art.dialog({
            content: document.getElementById('fee_detail'),
            id: 'handle',
            title:'欠费详情',
            padding: 0,
            width: 450,
            height: 280,
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
            }
        });
        return false;
    })
    //发送微信通知 
    function send_weixin(type,obj){
        var confirm_txt = "确认发送微信消息（缴费通知）";
        if (type==2) {
            var len = $("input[name='checkbox_one[]']:checked").length;
            if(len<=0){
               layer.alert('未选中任何业主');return false;
            }
        }
        // var send_user_type = 1;
         art.dialog({
            content: document.getElementById('send_user_type_div'),
            id: 'handle',
            title:'欠费详情',
            padding: 0,
            width: 450,
            height: 280,
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
                var send_user_type = $('#send_user_type').val();
                if(type==1){
                    if(confirm(confirm_txt)){
                        var url = "{pigcms{:U('User/send_weixin_notice')}";
                        $.post(url , {'is_all':1,send_user_type:send_user_type},function(data){
                                alert(data['msg']);
                        },'json')
                    }
                } else if (type==2) {
                    var len = $("input[name='checkbox_one[]']:checked").length;
                    if(len<=0){
                       layer.alert('未选中任何业主');return false;
                    }

                    var length = $("input[name='checkbox_one[]']").length;
                    var ids="";
                    for(var i=0;i<length;i++){
                        if($("input[name='checkbox_one[]']")[i].checked) ids += "," + $("input[name='checkbox_one[]']")[i].value;   
                    }

                    if(confirm(confirm_txt)){
                        var url = "{pigcms{:U('User/send_weixin_notice')}";
                        $.post(url , {'ids':ids,send_user_type:send_user_type},function(data){
                                alert(data['msg']);
                        },'json')
                    }
                } else if (type==3) {
                    if(confirm(confirm_txt)){
                        var pigcms_id = $(obj).attr("pid");
                        var url = "{pigcms{:U('User/send_weixin_notice')}";
                        $.post(url , {'pigcms_id':pigcms_id,send_user_type:send_user_type},function(data){
                                alert(data['msg']);
                        },'json')
                    }
                }
            }
        });
    }
</script>
<include file="Public:footer"/>
