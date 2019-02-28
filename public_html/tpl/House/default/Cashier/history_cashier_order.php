<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('cashier')}">收银台</a>
            </li>
            <li class="active">历史缴费</li>
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
                            <li>
                                <a href="{pigcms{:U('personal_order_list',array('bind_id'=>$pigcms_id))}">未缴费用</a>
                            </li>
                            <li class="active">
                                <a href="{pigcms{:U('history_cashier_order',array('bind_id'=>$pigcms_id))}">历史缴费</a>
                            </li>
                        </ul>
                    </div>
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
                                                    <td>{pigcms{$vo.username}</td>
                                                    <td>{pigcms{$vo.phone}</td>
                                                    <td>{pigcms{$vo.address}</td>
                                                    <td>{pigcms{$vo.usernum}</td>  
                                                    <td><label>{pigcms{$vo.money}</label>元</td>
                                                    <td>{pigcms{$vo.pay_type_name}</td>
                                                    <td>{pigcms{$vo.time|date='Y-m-d H:i:s',###}</td> 
                                                    <td>{pigcms{$vo.remarks}</td>
                                                    <td>
                                                        <a href="javascript:void(0);" url="{pigcms{:U('cashier_detail',array('cashier_id'=>$vo['cashier_id']))}" onclick="cashier_detail(this)">明细</a>&nbsp;

                                                        <if condition="in_array(70,$house_session['menus'])">
                                                         | &nbsp;<a href="javascript:void(0);" onclick="print_select(this)" cid="{pigcms{$vo.cashier_id}">打印</a>
                                                        </if>
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
    var hkey_root,hkey_path,hkey_key
    hkey_root="HKEY_CURRENT_USER"
    hkey_path="\\Software\\Microsoft\\Internet Explorer\\PageSetup\\"

    // 设置页眉页脚为空
    function PageSetup_Null()
    {
        try{
            var RegWsh = new ActiveXObject("WScript.Shell") ;
            hkey_key="header" ;
            RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"") ;
            hkey_key="footer" ;
            RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"") ;
        }
        catch(e){}
    }

    // 设置页眉页脚为默认值
    function PageSetup_Default()
    {
        try{
            var RegWsh = new ActiveXObject("WScript.Shell") ;
            hkey_key="header" ;
            RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"&w&b页码，&p/&P") ;
            hkey_key="footer" ;
            RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"&u&b&d") ;
        }
        catch(e){}
    }

    // 打印
    function PrintPage(html)
    {
        PageSetup_Null() ;
        if (window.PrePrint != null) window.PrePrint();
        try{
            print.portrait   =  false    ;//横向打印 
        }catch(e){
            alert("不支持此方法");
        }
        var bdhtml=window.document.body.innerHTML;//获取当前页的html代码
        // var sprnstr="<!--begin-->";//设置打印开始区域    
        // var eprnstr="<!--end-->";//设置打印结束区域    
        // var prnhtml=bdhtml.substring(bdhtml.indexOf(sprnstr)); //从开始代码向后取html    
        // var prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));//从结束代码向前取html    
        window.document.body.innerHTML=html;
        window.print();
        window.document.body.innerHTML=bdhtml;
        // setTimeout("window.close();", 0)
        // PageSetup_Default() ;
    }

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

        var myPrint = art.dialog({
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
                // this.unlock();
                // this.hide();
                // setTimeout(function(){
                //     $.post(post_url+"&cashier_id="+cashier_id+"&template_id="+template_id,{},function(data){
                //         // console.log(data)
                //         PrintPage(data)
                       
                //     },'json')

                // },100)

                var print = art.dialog.open(post_url+"&cashier_id="+cashier_id+"&template_id="+template_id,  
                {  
                    id: "mydialog",  
                    width: 900,  
                    height: 600,  
                    title: "打印预览",//如果不想要弹窗的标题，可以直接赋值为false  
                    lock: true,  
                    button:[{name:'关闭'}],
                });
               

                // window.open("/shequ.php?g=House&c=Unit&a=print_start&cashier_id="+cashier_id+"&template_id="+template_id);
            }
        });

    }
</script>
</body>
</html>
