<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/index')}">业主管理</a>
            </li>
            <li class="active">业主列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        	<div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
				<form method="get" action="{pigcms{:U('User/index')}" id="find-form">
                    <input type="hidden" name="c" value="User"/>
                    <input type="hidden" name="a" value="index"/>
					<select name="find_type" id="find_type" class="col-sm-1" style="margin-right:10px;height:42px;">
						<option value="1" <if condition="$find_type eq 1">selected="selected"</if>>物业编号</option>
						<option value="2" <if condition="$find_type eq 2">selected="selected"</if>>姓名</option>
						<option value="3" <if condition="$find_type eq 3">selected="selected"</if>>手机号</option>
						<option value="4" <if condition="$find_type eq 4">selected="selected"</if>>住址</option>
					</select>
					<input value="{pigcms{$find_value}" class="col-sm-2" name="find_value" id="find_value" type="text" style="margin-right:10px;font-size:18px;height:42px;"/>
					&nbsp;&nbsp;
					
					是否为平台用户：
					<select name="is_platform" id="is_platform"  style="height:42px;">
						<option value="0" <if condition="$_GET['is_platform'] eq 0">selected="selected"</if>>全部</option>
						<option value="1" <if condition="$_GET['is_platform'] eq 1">selected="selected"</if>>是</option>
						<option value="2" <if condition="$_GET['is_platform'] eq 2">selected="selected"</if>>否</option>
					</select>
					&nbsp;&nbsp;
					
					物业截至时间筛选：
					<input type="text" name="property_endtime_start" class="input-text" value="{pigcms{$property_endtime_start}"  style="height:42px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="开始"/>
					&nbsp;—&nbsp;
					<input type="text" name="property_endtime_end" class="input-text" value="{pigcms{$property_endtime_end}"  style="height:42px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="结束"/>&nbsp;&nbsp;&nbsp;&nbsp;
 

					<input class="btn btn-success" type="submit" id="find_submit" value="查找业主" />&nbsp;
					<a class="btn btn-success" onclick="location.href='{pigcms{:U('User/index')}'">重置</a>
				</form>
			</div>
        	<button class="btn btn-success" onclick="addUser()" <if condition="!in_array(92,$house_session['menus'])">disabled="disabled"</if>>添加业主</button>&nbsp;
        	<button class="btn btn-success" onclick="importUser()" <if condition="!in_array(92,$house_session['menus'])">disabled="disabled"</if>>导入业主</button>&nbsp;
        	<button class="btn btn-success" onclick="importUserDetail()" <if condition="!in_array(117,$house_session['menus'])">disabled="disabled"</if>>导入业主每月帐单明细</button>&nbsp;
			<!-- <button class="btn btn-success" onclick="send_property()" <if condition="!in_array(100,$house_session['menus'])">disabled="disabled"</if>>群发微信消息</button>&nbsp; -->
			<button class="btn btn-success" onclick="location.href='{pigcms{:U('user_data')}'" <if condition="!in_array(99,$house_session['menus'])">disabled="disabled"</if>>数据统计</button>&nbsp;
			<button class="btn btn-success" onclick="payment_add()" <if condition="!in_array(97,$house_session['menus'])">disabled="disabled"</if>>批量添加缴费项</button>
			&nbsp;
			<if condition="in_array(98,$house_session['menus'])">
			<a onclick="location.href='{pigcms{:U('user_export',$_GET)}'" class="btn btn-success">EXCEL导出</a>
            <else/>
            <button class="btn btn-success disabled" disabled="disabled">EXCEL导出</button>
			</if>
			<style type="text/css">
				.ace-file-input a {display:none;}
				.div-intro{ float:right; margin-top:20px}
				.div-intro-detail{width:10px; height:10px; background-color:red; float:left; margin-top:5px}
				.div-intro span{ float:left; margin-left:5px;}
			</style>
			<div class="div-intro">
				<div class="div-intro-detail"></div>
				<span>尚不是平台用户（注：将无法使用社区服务）</span><br />
				<div class="div-intro-detail" style="background:orange"></div>
				<span>尚未绑定微信</span>
			</div>
            
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover" style="position:relative;">
                            <thead>
                                <tr>
                                	<th width="3%" style="text-align:center"><input type="checkbox" class="checkbox_all" style="wdith:20px; height:20px;"></th>
                                    <th width="10%">物业编号</th>
                                    <th width="5%">姓名</th>
                                    <th width="8%">家属及租客</th>
                                    <th width="10%">手机号</th>
                                    <th width="15%">住址</th>
                                    <!-- <th width="10%">未缴总费用</th> -->
                                    <th width="12%">物业服务时间（开始 - 结束）</th>
                                    <th width="4%">停车位</th>
                                    <th width="5%">房子大小</th>
                                    <if condition="$config['PC_write_card'] eq 1"><th width="8%">门禁卡编号</th></if>
                                    <th class="button-column" width="20%">操作</th>
                                </tr>
                            </thead>
                            <tbody class="class-checkbox">
                                <if condition="$user_list">
                                    <volist name="user_list['user_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">

                                            <td align="center"><input type="checkbox" name="delCheckbox[]" style="wdith:20px; height:20px;" value="{pigcms{$vo.pigcms_id}" onclick="return Dcheckbox($(this));"></td>
                                            <td><div class="tagDiv">{pigcms{$vo.usernum}</div></td>
                                            <td>
                                            	<if condition="$vo['name']">
                                            	<if condition='!$vo["uid"]'>
                                            		<div class="tagDiv" style="color:red">{pigcms{$vo.name}</div>
                                            	<elseif  condition='!$vo["openid"]'/>
                                            		<div class="tagDiv" style="color:orange">{pigcms{$vo.name}</div>
                                            	<else/>
                                            		{pigcms{$vo.name}
                                            	</if>
                                            	<else/>--</if>
											
											<if condition='$vo["bind_list"] AND 1==2'>
												<a class="bind_info red" href="{pigcms{:U('bind_list',array('pigcms_id'=>$vo['pigcms_id']))}">他的家属和租客&nbsp;>&nbsp;</a>
											</if>
											
											</td>
                                            <td>
											
												<div class="tagDiv">
												
                                                <if condition="in_array(115,$house_session['menus'])">
												<a class="bind_info red" href="{pigcms{:U('bind_list',array('pigcms_id'=>$vo['pigcms_id']))}">他的家属和租客</a>
												<else/>
												他的家属和租客(无权限查看)
												</if>
											
											
												<if condition="$vo.bind_unverify_num gt 0"><br>有{pigcms{$vo.bind_unverify_num}个待审核业主</if>
												</div>
											</td>
                                            <td><div class="tagDiv"><if condition="$vo['phone']">{pigcms{$vo.phone}<else/>--</if></div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.address}</div></td>
                                    <!--         <td>
												<div class="tagDiv">
													

													物业费：￥{pigcms{:floatval($vo['property_price'])}<br/>
													水费：￥{pigcms{:floatval($vo['water_price'])}<br/>
													电费：￥{pigcms{:floatval($vo['electric_price'])}<br/>
													燃气费：￥{pigcms{:floatval($vo['gas_price'])}<br/>
													停车费：￥{pigcms{:floatval($vo['park_price'])}<br/> 
												</div>
											</td> -->
											<td>
												<div><if condition="$vo['property_starttime']">{pigcms{$vo.property_starttime|date="Y-m-d",###}<else/>还未设置</if> - 
												<if condition="$vo['property_endtime']">{pigcms{$vo.property_endtime|date="Y-m-d",###}<else/>还未设置</if></div>
											</td>

                                            <td><div class="shopNameDiv"><if condition="$vo['bind_position']">有<else />无</if></div></td>
                                            <td><div class="shopNameDiv">{pigcms{$vo.housesize} ㎡</div></td>
                                            <if condition="$config['PC_write_card'] eq 1"><td><div class="shopNameDiv">{pigcms{$vo.card_no}</div></td></if>
                                            <td class="button-column">
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('User/edit',array('pigcms_id'=>$vo['pigcms_id'],'usernum'=>$vo['usernum'],'page'=>$_GET['page']))}">编辑</a>&nbsp;

                                               <!--  <if condition="in_array(118,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="欠费明细" href="{pigcms{:U('User/pay_detail',array('pigcms_id'=>$vo['pigcms_id'],'usernum'=>$vo['usernum']))}">欠费明细</a>&nbsp;
                                              	</if> -->
												<a href="javascript:void(0);" style="width: 60px;" class="label label-info detail" pid="{pigcms{$vo.pigcms_id}" usn="{pigcms{$vo.usernum}">未缴明细</a>&nbsp;
                                                <if condition="in_array(120,$house_session['menus'])">
                                           		<if condition="$vo['uid'] neq 0">
													<a style="width: 60px;" class="label label-sm label-info" title="缴费明细" href="{pigcms{:U('User/orders',array('bind_id'=>$vo['pigcms_id']))}">已缴明细</a>
                                           		</if>&nbsp;
                                              	</if>

                                                <if condition="in_array(94,$house_session['menus'])">
												<a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onclick="if(confirm('确认删除该条信息？')){location.href=\'{pigcms{:U('User/user_delete',array('pigcms_id'=>$vo['pigcms_id'],'usernum'=>$vo['usernum']))}\'}">删除</a>
                                              	</if>
												
                                                <if condition="in_array(96,$house_session['menus'])">
												<if condition="(((time() gt strtotime(date('Y-m-d' , ($vo['property_endtime'] - ($village_info['property_warn_day'] *24*3600))))) OR (!$village_info['property_warn_day'])) AND (!empty($vo['openid'])))">
													<!-- <br /><a style="width: 80px;background-color:#f00 !important" class="label label-sm label-info" title="发送微信通知" onclick="send_property_one('{pigcms{$vo['pigcms_id']}' , '{pigcms{$vo['usernum']}')" href="javascript:void(0)" >发送微信通知</a> -->
												</if>
                                              	</if>

                                                <if condition="in_array(95,$house_session['menus'])">
												<if condition="($config['PC_write_card'] eq 1) AND ($vo['uid'] neq '0')">&nbsp; <a style="width: 60px;" class="label label-sm label-info" title="写卡" onclick="WriteSector('{pigcms{$door_sector}','{pigcms{$door_pwd}','{pigcms{$vo.door_str}','{pigcms{$vo.pigcms_id}')" href="javascript:void(0);">写卡</a></if>
                                              	</if>
                                           </td>
                                        </tr>
                                    </volist>
                                    <if condition="in_array(269,$house_session['menus'])">
                                    <tr>
                                    	<td colspan="14">&nbsp;&nbsp;&nbsp;<button class=" btn delete_class">删除选中</button></td>
                                    </tr>
                                	</if>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="14" >没有任何业主。</td></tr>
                                </if>
                            </tbody>
                           
                        </table>
                        <div style="float: right; padding-left: 5px; ">
	                        <input style="width: 50px;" type="text" name="page" id="page" value="{pigcms{$_GET['page']}">
	                        <button onclick='checkPage()' style="background-color: #3a87ad!important; color: #fff; width: 50px; height: 30px;">跳转</button>
	                        <script>
	                        	function checkPage(){
	                        		var page = $("#page").val();
	                        		if(page == ''){
	                        			return false;
	                        		}
	                        		location.href = "{pigcms{:U('index')}"+"&page="+page;
	                        	}
	                        </script>
                        </div>
                        {pigcms{$user_list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	var static_path = "{pigcms{$static_path}";
</script>
	<if condition="$config['PC_write_card'] eq 1">
		<script language="javascript" src="http://127.0.0.1:8008/YOWOCloudRFIDReader.js"></script>
		<script language="javascript" src="{pigcms{$static_path}/js/YOWOReaderBase.js"></script>

		<script language="javascript" type="text/javascript">
		    rfidreader.onResult(function(resultdata) {
		        switch(resultdata.FunctionID){
		            case 8:
		                document.getElementById("CardNo").value = resultdata.CardNo;
		                if(resultdata.Result>0) {
		                    alert("同步成功！");
		                    var cardNoUrl = "{pigcms{:U('User/card_no_add')}";
		                    var card_no = resultdata.CardNo;
		                    var pigcms_id = $("#pigcms_id").val();
			                $.post(cardNoUrl,{card_no:card_no,pigcms_id:pigcms_id},function(data){

			                },'json')
		                } else {
		                    alert("同步失败，错误：" + GetErrStr(resultdata.Result));   
		                }
		                break;
		        }
		    });
		    function WriteSector(SectorID,Key,Data,pigcms_id){
		        rfidreader.KeyMode=0;
		        rfidreader.KeyStringMode=0;
		        rfidreader.KeyString=Key;
		        rfidreader.Repeat=0
		        rfidreader.M1WriteSector(SectorID, Data,0); 
		        $("#pigcms_id").val(pigcms_id);
		    }
		</script>
	</if>


<input name="CardNo" type="hidden" id="CardNo" size="10" maxlength="8" readonly>
<input name="pigcms_id" type="hidden" id="pigcms_id">
<style>
	.modal-content{
		width:400px;	
	}
	.modal-body{
		text-align:center;
		padding:20px 15px;
	}
</style>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
					警告提示
				</h4>
			</div>
			<div class="modal-body">
				删除业主后，业主下的家属/租客会被一起删除，绑定的房间会被释放，确定删除？
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary class-delete">
					确定
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>
<div class="modal fade" id="myModal_alert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">提示信息</h4>
			</div>
			<div class="modal-body">
				请先选择要执行的业主！
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary class-tip">确定</button>
			</div>
		</div>
	</div>
</div>
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
            <tr class="under_line" >
                <th style="width: 190px;">合计：</th>
                <td style="width: 190px;"><label id="total_money"></label>&nbsp;元</td>
            </tr>
            <if condition="in_array(118,$house_session['menus'])">
            <tr class="under_line" >
                <td colspan="2" style="width: 190px;text-align: center;">
                	<a  class="label label-sm label-info" title="欠费明细" href="javascript:pay_detail();">每月账单欠费明细</a>
                </td>
                
            </tr>
        	</if>
        </tbody>
    </table>
    <input type="hidden" id="now_id" value="">
    <input type="hidden" id="usn" value="">
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" language="javascript">

$(".checkbox_all").on('click',function(){
	
	if($(this).is(':checked')){
		$("input[name='delCheckbox[]']").prop("checked",true);  
	}else{
		$("input[name='delCheckbox[]']").prop("checked",false);  	
	}	
		
});

function pay_detail(){
	var usernum = $("#usn").val();
	var url = "{pigcms{:U('User/pay_detail')}";
	window.location.href = url + '&usernum='+usernum;
}
$('.detail').click(function(){
        //获得选中费用
        var pigcms_id = $(this).attr("pid");
        var usernum = $(this).attr("usn");
        $("#now_id").val(pigcms_id);
        $("#usn").val(usernum);
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
                $('#total_money').html(result.data.total);
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
function Dcheckbox(e){
	var n=0;
	var len = $("input[name='delCheckbox[]']").length;
	for(var i=0;i<len;i++){
		if($("input[name='delCheckbox[]']")[i].checked) n++;		
	}
	if(n==len){
		$(".checkbox_all").prop("checked",true); 
	}else{
		$(".checkbox_all").prop("checked",false); 
	}
		
}

$(".class-tip").on('click',function(){
	$('#myModal_alert').modal('hide');	
});

$(".delete_class").on('click',function(){
	var len = $("input[name='delCheckbox[]']:checked").length;
	if(len<=0){
		$('#myModal_alert').modal({
			keyboard: false,
			backdrop: 'static'
		})
	}else{
		$('#myModal').modal({
			keyboard: false,
			backdrop: 'static'
		})	
	}
});	


function payment_add(){
	var len = $("input[name='delCheckbox[]']:checked").length;
	if(len<=0){
		$('#myModal_alert').modal({
			keyboard: false,
			backdrop: 'static'
		})
	}else{
		var length = $("input[name='delCheckbox[]']").length;
		var value="";
		for(var i=0;i<length;i++){
			if($("input[name='delCheckbox[]']")[i].checked) value += "," + $("input[name='delCheckbox[]']")[i].value;	
		}

		var href = "{pigcms{:U('payment_add',array('pigcms_id'=>$info['pigcms_id']))}"+'uid='+value;
		art.dialog.open(href,{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'查看',
			padding: 0,
			width: 900,
			height: 603,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4'
		});
	}
}


$('.bind_info').click(function(){
	
	return false;
});



$(".class-delete").on('click',function(){
	$('#myModal').modal('hide');
	var length = $("input[name='delCheckbox[]']").length;
	var value="";
	for(var i=0;i<length;i++){
		if($("input[name='delCheckbox[]']")[i].checked) value += "," + $("input[name='delCheckbox[]']")[i].value;	
	}
	value = value.substring(1);
	if(value){
		var village_id = "{pigcms{$village_info.village_id}";
		var url = "{pigcms{:U('User/ajaxDelete')}";
		$.post(url,{'village_id':village_id,'arr_pigcms_id':value},function(data){
			if(data['status']){
				location.reload();
			}else{
				alert(data['msg']);
			}
		},'json')
	}	
		
});

function importUser(){
	window.location.href = "{pigcms{:U('User/user_import')}";
}
function importUserDetail(){
	window.location.href = "{pigcms{:U('User/detail_import')}";
}
function addUser(){
	window.location.href = "{pigcms{:U('User/user_add')}";
}
function send_property(){
	var property_warn_day = "{pigcms{$village_info['property_warn_day']}";
	if(parseInt(property_warn_day) > 0){
		var confirm_txt = "确认群发微信消息（物业费到期提前" + property_warn_day + "天提醒）";
	}else{
		var confirm_txt = "确认群发微信消息（物业费到期提醒）";
	}
	
	if(confirm(confirm_txt)){
		var url = "{pigcms{:U('User/send_property')}";
		$.post(url , {'is_collective':1},function(data){
			// if(data['status']){
				alert(data['msg']);
			// }
		},'json')
	}
}

function send_property_one(pigcms_id , usernum){
	if(confirm('确认发送微信消息？')){
		var url = "{pigcms{:U('User/send_property')}";
		$.post(url,{'pigcms_id':pigcms_id,'usernum':usernum},function(data){
			if(data['status']){
				alert(data['msg']);
			}
		},'json')
	}
}

$('.bind_info').click(function(){
	art.dialog.open($(this).attr('href'),{
		init: function(){
			var iframe = this.iframe.contentWindow;
			window.top.art.dialog.data('iframe_handle',iframe);
		},
		id: 'handle',
		title:'查看',
		padding: 0,
		width: 800,
		height: 603,
		lock: true,
		resize: false,
		background:'black',
		button: null,
		fixed: false,
		close: null,
		left: '50%',
		top: '38.2%',
		opacity:'0.4'
	});
	return false;
});
</script>
<include file="Public:footer"/>
