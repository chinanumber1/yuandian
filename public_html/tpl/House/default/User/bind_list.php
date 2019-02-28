<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 社区管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	</head>
<style type="text/css">
.jqstooltip {
	position: absolute;
	left: 0px;
	top: 0px;
	visibility: hidden;
	background: rgb(0, 0, 0) transparent;
	background-color: rgba(0, 0, 0, 0.6);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000,endColorstr=#99000000);
	-ms-filter:"progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
	color: white;
	font: 10px arial, san serif;
	text-align: left;
	white-space: nowrap;
	padding: 5px;
	border: 1px solid white;
	z-index: 10000;
}

.jqsfield {
	color: white;
	font: 10px arial, san serif;
	text-align: left;
}

.statusSwitch, .orderValidSwitch, .unitShowSwitch, .authTypeSwitch {
	display: none;
}

#shopList .shopNameInput, #shopList .tagInput, #shopList .orderPrefixInput
	{
	font-size: 12px;
	color: black;
	display: none;
	width: 100%;
}
.fl{ float:left;}
.fr{ float:right;}
	a{
		margin-right:5px;
	}
#bind_info_btn{
	float:left ;
	margin-left:10px;
	margin-bottom: 5px;
	margin-top: 5px;
    border: 1px solid #87b87f;
    color:#87b87f;
}
#bind_info_btn span{
	padding-top: 5px;
    color:#FFF
}
</style>
<div class="main-content">
    <div class="page-content">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
						 <if condition="in_array(110,$house_session['menus'])">
						 <button class="chk_express bind_info" id="bind_info_btn" style="" href="{pigcms{:U('User/bind_other',array('pigcms_id'=>$_GET['pigcms_id']))}">绑定家属租客</button>
						 </if>
						
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>姓名</th>
                                    <th>手机号</th>
                                    <th>门禁卡编号</th>
                                    <th>关系</th>
                                    <th>状态</th>
                                    <th class="button-column" width="30%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$user_list">
                                    <volist name="user_list" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.card_no}</div></td>
											<td><div class="tagDiv">
												<if condition='$vo["type"] eq 1'>
													家人
												<elseif condition='$vo["type"] eq 2' />
													租客
												<elseif condition='$vo["type"] eq 3' />
													更新房主
												<else />
													未知
												</if>
											</div></td>
                                            <td>
                                            	<if condition="$vo['status'] eq 1">
                                            	<span class="green">已绑定</span>
                                                <else />
                                                <span class="red">审核中</span>
                                                </if>
                                            </td>
                                            <td>
												<div class="tagDiv">
													<if condition="in_array(111,$house_session['menus'])">
														<if condition='$vo["status"] eq 1'>
															<if condition='$vo["type"] eq 3'>
																<span class="green">绑定成功<span>
															<else />
																<a href="javascript:void(0)" onclick="if(confirm('确认进行绑定,请谨慎操作？')){location.href='{pigcms{:U('bind_edit',array('pigcms_id'=>$vo['pigcms_id'],'no_bind'=>1))}'}">解除绑定</a>
															</if>
															
														<else />
															<a href="javascript:void(0)" onclick="if(confirm('确认进行绑定,请谨慎操作？')){location.href='{pigcms{:U('bind_edit',array('pigcms_id'=>$vo['pigcms_id']))}'}">绑定</a>
														</if>														
													</if>

													<if condition="in_array(116,$house_session['menus'])">
													<a class="bind_info" href="{pigcms{:U('bind_other',array('pigcms_id'=>$vo['pigcms_id'],'edit'=>1))}">编辑</a>
													</if>
														
													<if condition="in_array(113,$house_session['menus'])">
													<a href="javascript:void(0)" onclick="if(confirm('确认进行删除,请谨慎操作？')){location.href='{pigcms{:U('bind_delete',array('pigcms_id'=>$vo['pigcms_id']))}'}">删除</a>
													</if>
													
													<if condition="in_array(112,$house_session['menus'])">
														<if condition="$config['PC_write_card'] eq 1">
															<a onclick="WriteSector('{pigcms{$door_sector}','{pigcms{$door_pwd}','{pigcms{$parent_info.door_str}','{pigcms{$vo.pigcms_id}')" href="javascript:void(0);">写卡</a>
														</if>
													</if>														
												</div>
											</td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="12" >暂无信息。</td></tr>
                                </if>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>



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



<script>
	$('.bind_info').click(function(){
		art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle_',iframe);
			},
			id: 'handle_',
			title:'添加家属/租客',
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
			opacity:'0.4',
			cancel: function () {
                window.location.reload()
            }
		});
		return false;
	});
</script>
</body>
</html>