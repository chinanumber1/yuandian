<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/index')}">物业管理</a>
            </li>
            <li class="active">押金管理</li>
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
            <span><button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('deposit_add',$_GET)}'" <if condition="!in_array(42,$house_session['menus'])">disabled="disabled"</if>>新增</button></span>
            <!-- <span><button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('deposit_refund',$_GET)}'">退款</button></span> -->
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('deposit_management')}" method="get">
							<input type="hidden" name="c" value="Unit"/>
							<input type="hidden" name="a" value="deposit_management"/>
                            押金状态: <select name="is_refund" style="width:100px;height:42px;">
                                        <option value="0" <if condition="$_GET['is_refund'] eq '0'">selected</if>>--请选择--</option>                                
                                        <option value="1" <if condition="$_GET['is_refund'] eq '1'">selected</if>>未退款</option>                                
                                        <option value="2" <if condition="$_GET['is_refund'] eq '2'">selected</if>>已退款</option>                              
                                     </select>
							收款日期筛选：
							<input type="text" name="begin_time" class="input-text" value="{pigcms{$_GET['begin_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:true,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间"/>-
							<input type="text" name="end_time" class="input-text" value="{pigcms{$_GET['end_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:true,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间"/>
							<button class="select" type="submit">查询</button>
						</form>
					</td>
				</tr>
			</table>
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="3%"><input type="checkbox" id="select_all"></th>
                                    <th width="5%">房间编号</th>
                                    <th width="5%">客户姓名</th>
                                    <th width="5%">付款方式</th>
                                    <th width="5%">押金项目</th>
                                    <th width="5%">应缴金额</th>
                                    <th width="5%">实缴金额</th>
                                    <th width="5%">押金余额</th>
                                    <th width="5%">已退金额</th>
                                    <th width="5%">押金状态</th>
                                    <th width="5%">收费员</th>
                                    <th width="8%">付款时间</th>
                                    <th width="5%">收款备注</th>
                                    <th width="5%">退款备注</th>
                                    <th width="6%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$info_list['info_list']">
                                    <volist name="info_list['info_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><input type="checkbox" name="is_check" value="{pigcms{$vo.deposit_id}"></td>
                                            <td>{pigcms{$vo.room_num}</td>
                                            <td>{pigcms{$vo.name}</td>
                                            <td>{pigcms{$vo.pay_name}</td>
                                            <td>{pigcms{$vo.deposit_name}</td>
                                            <td>{pigcms{$vo.payment_money}</td>
                                            <td>{pigcms{$vo.actual_money}</td>
                                            <td>{pigcms{$vo.deposit_balance}</td>
                                            <td>{pigcms{$vo.refund_money}</td>
                                            <td>
                                                <if condition="$vo[is_refund] eq '1'">
                                                未退款
                                                <else />
                                                已退款
                                                </if>
                                            </td>
                                            <td>
                                                <if condition="$vo[role_id] neq 0 ">
                                                    <if condition="$vo['realname']">
                                                    {pigcms{$vo.realname}
                                                    <else/>
                                                    {pigcms{$vo.account}
                                                    </if>
                                                <else/>
                                                    {pigcms{$house_session.account}
                                                </if>   
                                            </td>
                                            <td>{pigcms{$vo.pay_time|date='Y-m-d H:i',###}</td>
                                            <td>{pigcms{$vo.deposit_note}</td>
                                            <td>{pigcms{$vo.refund_note}</td>
											<td>
                                                <if condition="in_array(44,$house_session['menus'])">
                                                <a class="label label-sm label-info" title="打印" href="javascript:void(0);" onclick="print_select(this)" cid="{pigcms{$vo.deposit_id}">打印</a>
                                                </if>
                                                <if condition="in_array(252,$house_session['menus']) && $vo['is_refund'] eq 1">
                                                &nbsp;<a class="label label-sm label-info" title="退款" href="{pigcms{:U('deposit_refund',array('deposit_id'=>$vo['deposit_id']))}">退款</a>
                                                </if>
                                                <if condition="in_array(43,$house_session['menus'])">
                                                &nbsp;<a class="label label-sm label-info" title="删除" href="javascript:void(0)" onclick="one_del({pigcms{$vo.deposit_id},this)">删除</a>
                                                </if>
                                                </td>
                                        </tr>
                                    </volist>
                                <else />
                                    <tr class="odd"><td class="button-column" colspan="15" >没有任何数据。</td></tr>
								</if>
                            </tbody>
                        </table>
                        <if condition="in_array(43,$house_session['menus'])">
                        <div>
                            <input type="button" value="删除" class="btn" id="getValue">
                        </div>
                        </if>
                        {pigcms{$info_list.pagebar}
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
<script type="text/javascript">
    $("#select_all").click(function () { 
        $("input[name='is_check']").each(function () {
            this.checked=!this.checked;
        });
    });

    function one_del(id,obj){
        if (!id) {
            layer.msg('参数传递错误!');
        }
        layer.confirm('确认删除信息？', {
          btn: ['确定','取消'] //按钮
        }, function(){
            var del_url = "{pigcms{:U('deposit_del')}";
            $.post(del_url,{'deposit_id':id},function(data){
                if(data.code == 1){
                    layer.msg(data.msg,{icon: 1});
                    setTimeout(location.reload(),3000);
                }
                if(data.code == 2){
                    layer.msg(data.msg,{icon: 2});
                }
            },'json');
        });
    }

    $("#getValue").click(function () { 
        var deposit_id = '';
        $("input[name='is_check']").each(function () {
            if($(this).attr("checked")){
                deposit_id += $(this).val()+',';
            }
        });

        deposit_id=deposit_id.substring(0,deposit_id.length-1);

        if(!deposit_id){
            layer.msg('请选择您要删除的信息。');
            return false;
        }

        layer.confirm('确认删除选中的信息？', {
          btn: ['确定','取消'] //按钮
        }, function(){
            var del_url = "{pigcms{:U('deposit_del')}";
            $.post(del_url,{'deposit_id':deposit_id},function(data){
                if(data.code == 1){
                    layer.msg(data.msg,{icon: 1});
                    setTimeout(location.reload(),3000);
                }
                if(data.code == 2){
                    layer.msg(data.msg,{icon: 2});
                }
            },'json');
        }, function(){
          
        });
    });


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
        // if (window.PrePrint != null) window.PrePrint();
        try{
            print.portrait   =  false    ;//横向打印 
        }catch(e){
            // alert("不支持此方法");
        }
        var bdhtml=window.document.body.innerHTML;//获取当前页的html代码
        var sprnstr="<!--begin-->";//设置打印开始区域    
        var eprnstr="<!--end-->";//设置打印结束区域    
        var prnhtml=bdhtml.substring(bdhtml.indexOf(sprnstr)); //从开始代码向后取html    
        // var prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));//从结束代码向前取html    
        window.document.body.innerHTML=html;
        window.print();
        window.document.body.innerHTML=bdhtml;
        setTimeout("window.close();", 0)
        // PageSetup_Default() ;
    }

    function cashier_detail(obj){
        url = $(obj).attr('url');

        art.dialog.open(url,  
        {  
            id: "mydialog",  
            width: "80%",  
            height: 500,  
            title: "押金详情",//如果不想要弹窗的标题，可以直接赋值为false  
            lock: true,  
            button:[{name:'关闭'}],
        });  
    }

    function print_select(obj){
        var deposit_id = $(obj).attr('cid');
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
                // $.post("/shequ.php?g=House&c=Unit&a=print_start&deposit_id="+deposit_id+"&template_id="+template_id,{},function(data){
                //     console.log(data)
                //     PrintPage(data)
                   
                // },'json')
                art.dialog.open("/shequ.php?g=House&c=Unit&a=print_deposit&deposit_id="+deposit_id+"&template_id="+template_id,  
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
<include file="Public:footer"/>
